<?php

namespace App\Http\Controllers;

use App\Models\VeXe;
use App\Models\ChuyenXe;
use App\Models\Ghe;
use App\Models\ThanhToan;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;

/**
 * Controller xử lý đặt vé xe khách
 * Bao gồm: hiển thị sơ đồ ghế, đặt vé, thanh toán
 */
class DatVeController extends Controller
{
    /** Các trạng thái vé bị hủy */
    private const TRANG_THAI_HUY = ['Hủy', 'Huy', 'Hoàn tiền', 'Hoan tien'];

    /**
     * Hiển thị trang chọn ghế và đặt vé
     * @param Request $request Request hiện tại
     * @param int $ma_chuyen Mã chuyến xe cần đặt vé
     */
    public function create(Request $request, int $ma_chuyen): View
    {
        $chuyen = ChuyenXe::with(['nhaXe', 'tuyenDuong', 'ghe', 'xe'])->findOrFail($ma_chuyen);
        
        // Lấy số ghế từ query parameter (từ trang tìm kiếm)
        $soGheTuTimKiem = max(1, min(10, (int)$request->query('so_ghe', 1)));
        
        $tongGhe = $chuyen->ghe->count() ?: ($chuyen->xe?->SoGhe ?? 0);
        
        // Lấy danh sách ghế đã đặt (chỉ tính vé đã thanh toán thành công)
        $gheDaDat = $this->layGheDaDat($ma_chuyen);
        $soGheDaDat = count($gheDaDat);
        
        // Lấy ghế đã đặt nhưng chưa thanh toán (để tránh đặt trùng)
        $gheDaDatChuaThanhToan = $this->layGheChuaThanhToan($ma_chuyen);
        
        // Lấy ghế bị khóa/giữ chỗ
        $gheBiKhoa = $this->layGheBiKhoa($ma_chuyen);
        
        // Hợp nhất các ghế không thể đặt
        $gheKhongTheDat = array_unique(array_merge($gheDaDat, $gheDaDatChuaThanhToan, $gheBiKhoa));
        
        // Tính số ghế trống (chỉ tính ghế đã thanh toán thành công)
        $soGheTrong = max(0, $tongGhe - $soGheDaDat);
        
        // Lấy tất cả ghế của chuyến
        $tatCaGhe = $this->layTatCaGhe($chuyen, $gheDaDat, $ma_chuyen);
        
        // Lấy danh sách ghế trống hiện tại
        $gheTrong = $this->layGheTrong($chuyen, $gheKhongTheDat);
        
        // Tạo ghế tự động nếu chưa có
        if ($gheTrong->isEmpty() && $tongGhe > 0 && $soGheTrong > 0 && $chuyen->xe) {
            $this->taoGheChoChuyenXe($ma_chuyen, $chuyen->xe->SoGhe);
            $chuyen->refresh()->load('ghe');
            
            $gheTrong = $this->layGheTrong($chuyen, $gheKhongTheDat);
            $tatCaGhe = $chuyen->ghe()->orderBy('SoGhe')->get();
            $tongGhe = $chuyen->ghe->count() ?: ($chuyen->xe?->SoGhe ?? 0);
            $soGheTrong = max(0, $tongGhe - $soGheDaDat);
        }
        
        $user = session('user');
        $selectedSeats = session('selectedSeats', []);
        
        return view('datve.create', compact(
            'chuyen', 'gheTrong', 'tatCaGhe', 'gheDaDat', 'user',
            'tongGhe', 'soGheTrong', 'soGheDaDat', 'selectedSeats', 'soGheTuTimKiem'
        ));
    }

    /**
     * Xử lý đặt vé
     * Tạo vé cho các ghế được chọn
     */
    public function store(Request $r): RedirectResponse
    {
        $validated = $r->validate([
            'MaChuyenXe' => ['required', 'integer', 'exists:chuyenxe,MaChuyenXe'],
            'MaNguoiDung' => ['required', 'integer', 'exists:nguoidung,MaNguoiDung'],
            'MaGhe' => ['required', 'array', 'min:1'],
            'MaGhe.*' => [
                'required', 'integer',
                Rule::exists('Ghe', 'MaGhe')->where(fn($q) => $q->where('MaChuyenXe', $r->MaChuyenXe)),
            ],
        ]);

        $chuyen = ChuyenXe::with(['xe', 'ghe', 'veXe.thanhToan'])->findOrFail($validated['MaChuyenXe']);
        
        // Tính số ghế trống
        $tongGhe = $chuyen->ghe->count() ?: ($chuyen->xe?->SoGhe ?? 0);
        $soGheDaDat = $chuyen->veXe->filter(fn($ve) => 
            !in_array(strtolower($ve->TrangThai ?? ''), array_map('strtolower', self::TRANG_THAI_HUY))
            && $ve->thanhToan && $ve->thanhToan->TrangThai === 'Success'
        )->count();
        $soGheTrong = max(0, $tongGhe - $soGheDaDat);
        
        // Kiểm tra đủ ghế trống
        if ($soGheTrong < count($validated['MaGhe'])) {
            return redirect()->back()
                ->with('error', "Chuyến xe này chỉ còn {$soGheTrong} ghế trống!")
                ->withInput();
        }

        // Kiểm tra ghế đã được đặt chưa
        $gheDaDat = VeXe::where('MaChuyenXe', $validated['MaChuyenXe'])
            ->daThanhToan()
            ->pluck('MaGhe')
            ->toArray();
        
        $gheTrung = collect($validated['MaGhe'])
            ->filter(fn($maGhe) => in_array($maGhe, $gheDaDat))
            ->map(fn($maGhe) => Ghe::find($maGhe)?->SoGhe ?? "Mã ghế {$maGhe}")
            ->values();
        
        if ($gheTrung->isNotEmpty()) {
            return redirect()->back()
                ->with('error', 'Các ghế sau đã được đặt: ' . $gheTrung->implode(', '))
                ->withInput();
        }

        // Tạo vé cho từng ghế
        $veXeList = [];
        $selectedSeats = [];
        
        foreach ($validated['MaGhe'] as $maGhe) {
            if (VeXe::where('MaGhe', $maGhe)->daThanhToan()->exists()) {
                continue;
            }
            
            $ve = VeXe::create([
                'MaChuyenXe' => $validated['MaChuyenXe'],
                'MaNguoiDung' => $validated['MaNguoiDung'],
                'MaGhe' => $maGhe,
                'TrangThai' => 'da_dat',
                'GiaTaiThoiDiemDat' => $chuyen->GiaVe,
            ]);
            $veXeList[] = $ve;

            $selectedSeats[] = [
                'MaGhe' => $maGhe,
                'SoGhe' => Ghe::find($maGhe)?->SoGhe,
            ];
        }

        if (empty($veXeList)) {
            return redirect()->back()
                ->with('error', 'Không thể đặt vé. Vui lòng thử lại!')
                ->withInput();
        }

        // Lưu thông tin vào session
        session([
            'veXeList' => collect($veXeList)->pluck('MaVe')->toArray(),
            'tongTien' => count($veXeList) * $chuyen->GiaVe,
            'soGhe' => count($veXeList),
            'selectedSeats' => $selectedSeats,
        ]);

        return redirect()->route('datve.payment', $veXeList[0]->MaVe)
            ->with('success', 'Đặt vé thành công! Vui lòng thanh toán để hoàn tất.');
    }

    /**
     * Hiển thị trang thanh toán
     * @param int $ma_ve Mã vé cần thanh toán
     */
    public function payment(int $ma_ve): View
    {
        $veXe = VeXe::with(['chuyenXe.tuyenDuong', 'chuyenXe.nhaXe', 'ghe', 'nguoiDung'])
            ->findOrFail($ma_ve);
        $veXe->refresh();
        
        $veXeList = session('veXeList', []);
        $tongTien = session('tongTien');
        $soGhe = session('soGhe');
        
        // Lấy thông tin vé nếu session không có
        if (empty($veXeList)) {
            $tatCaVe = $this->layVeCungNhom($veXe);
            $veXeList = $tatCaVe->pluck('MaVe')->toArray();
            $tongTien = $tatCaVe->sum(fn($ve) => $ve->chuyenXe->GiaVe);
            $soGhe = $tatCaVe->count();
        } else {
            $tatCaVe = VeXe::whereIn('MaVe', $veXeList)
                ->with(['ghe', 'chuyenXe'])
                ->get();
            
            $tongTien = $tongTien ?: $tatCaVe->sum(fn($ve) => $ve->chuyenXe->GiaVe);
            $soGhe = $soGhe ?: $tatCaVe->count();
        }
        
        $daThanhToan = ThanhToan::whereIn('MaVe', $veXeList)
            ->where('TrangThai', 'Success')
            ->count() === count($veXeList);
        
        $user = session('user');
        return view('datve.payment', compact('veXe', 'tatCaVe', 'tongTien', 'soGhe', 'user', 'daThanhToan'));
    }

    /**
     * Xử lý thanh toán
     * @param int $ma_ve Mã vé cần thanh toán
     */
    public function processPayment(Request $r, int $ma_ve): RedirectResponse
    {
        $validated = $r->validate([
            'PhuongThuc' => ['required', 'string', 'max:30'],
        ]);

        $veXe = VeXe::with('chuyenXe')->findOrFail($ma_ve);
        $veXeList = session('veXeList', [$ma_ve]);
        
        // Kiểm tra đã thanh toán chưa
        if (ThanhToan::whereIn('MaVe', $veXeList)->where('TrangThai', 'Success')->exists()) {
            return redirect()->back()->with('error', 'Các vé này đã được thanh toán rồi!');
        }

        $now = now();
        
        // Tạo thanh toán cho từng vé
        foreach ($veXeList as $maVe) {
            $ve = VeXe::with('chuyenXe')->find($maVe);
            if ($ve) {
                $ve->NgayDat = $now;
                $ve->save();

                ThanhToan::create([
                    'MaVe' => $maVe,
                    'SoTien' => $ve->chuyenXe->GiaVe,
                    'PhuongThuc' => $validated['PhuongThuc'],
                    'TrangThai' => 'Success',
                    'NgayThanhToan' => $now,
                ]);
            }
        }

        // Xóa session
        session()->forget(['veXeList', 'tongTien', 'soGhe', 'selectedSeats']);

        return redirect()->route('datve.payment', $ma_ve)
            ->with('payment_success', 'Thanh toán thành công! Cảm ơn bạn đã sử dụng dịch vụ.');
    }

    /**
     * Lấy danh sách ghế đã đặt (đã thanh toán thành công)
     */
    private function layGheDaDat(int $maChuyenXe): array
    {
        return VeXe::where('MaChuyenXe', $maChuyenXe)
            ->whereNotIn('TrangThai', self::TRANG_THAI_HUY)
            ->whereHas('thanhToan', fn($q) => $q->where('TrangThai', 'Success'))
            ->pluck('MaGhe')
            ->map(fn($id) => (int)$id)
            ->unique()
            ->toArray();
    }

    /**
     * Lấy danh sách ghế đã đặt nhưng chưa thanh toán
     */
    private function layGheChuaThanhToan(int $maChuyenXe): array
    {
        return VeXe::where('MaChuyenXe', $maChuyenXe)
            ->where('TrangThai', 'da_dat')
            ->whereDoesntHave('thanhToan', fn($q) => $q->where('TrangThai', 'Success'))
            ->pluck('MaGhe')
            ->map(fn($id) => (int)$id)
            ->toArray();
    }

    /**
     * Lấy danh sách ghế bị khóa/giữ chỗ
     */
    private function layGheBiKhoa(int $maChuyenXe): array
    {
        return Ghe::where('MaChuyenXe', $maChuyenXe)
            ->whereIn('TrangThai', ['Giữ chỗ', 'giu cho'])
            ->pluck('MaGhe')
            ->map(fn($id) => (int)$id)
            ->toArray();
    }

    /**
     * Lấy tất cả ghế của chuyến xe
     */
    private function layTatCaGhe($chuyen, array $gheDaDat, int $maChuyenXe)
    {
        $tatCaGhe = $chuyen->ghe()->orderBy('SoGhe')->get();
        
        // Thêm ghế từ vé đã đặt
        if (!empty($gheDaDat)) {
            $gheTuVe = Ghe::whereIn('MaGhe', $gheDaDat)
                ->where('MaChuyenXe', $maChuyenXe)
                ->get();
            $maGheDaCo = $tatCaGhe->pluck('MaGhe')->toArray();
            
            foreach ($gheTuVe as $ghe) {
                if (!in_array($ghe->MaGhe, $maGheDaCo)) {
                    $tatCaGhe->push($ghe);
                }
            }
        }
        
        // Thêm ghế từ vé chưa thanh toán
        $gheTuVeChuaThanhToan = VeXe::where('MaChuyenXe', $maChuyenXe)
            ->where('TrangThai', 'da_dat')
            ->whereDoesntHave('thanhToan', fn($q) => $q->where('TrangThai', 'Success'))
            ->with('ghe')
            ->get()
            ->pluck('ghe')
            ->filter()
            ->unique('MaGhe');
        
        foreach ($gheTuVeChuaThanhToan as $ghe) {
            if (!in_array($ghe->MaGhe, $tatCaGhe->pluck('MaGhe')->toArray())) {
                $tatCaGhe->push($ghe);
            }
        }
        
        return $tatCaGhe->sortBy('SoGhe')->values();
    }

    /**
     * Lấy danh sách ghế trống
     */
    private function layGheTrong($chuyen, array $gheKhongTheDat)
    {
        return $chuyen->ghe()
            ->whereNotIn('MaGhe', $gheKhongTheDat)
            ->where(function($query) {
                $query->where('TrangThai', 'Trống')
                      ->orWhere('TrangThai', 'trong')
                      ->orWhereNull('TrangThai');
            })
            ->orderBy('SoGhe')
            ->get();
    }

    /**
     * Lấy các vé cùng nhóm (đặt cùng lúc)
     */
    private function layVeCungNhom($veXe)
    {
        $tatCaVe = VeXe::where('MaChuyenXe', $veXe->MaChuyenXe)
            ->where('MaNguoiDung', $veXe->MaNguoiDung)
            ->whereHas('thanhToan', fn($q) => $q->where('TrangThai', 'Success'))
            ->with(['ghe', 'chuyenXe'])
            ->orderByDesc('NgayDat')
            ->get();
        
        if ($tatCaVe->count() > 0) {
            $ngayDatNhat = $tatCaVe->first()->NgayDat;
            $tatCaVe = $tatCaVe->filter(function($ve) use ($ngayDatNhat) {
                return $ve->NgayDat && $ve->NgayDat->format('Y-m-d H:i:s') === $ngayDatNhat->format('Y-m-d H:i:s');
            })->values();
        }
        
        return $tatCaVe;
    }

    /**
     * Tạo ghế tự động cho chuyến xe
     * @param int $maChuyenXe Mã chuyến xe
     * @param int $soGheXe Số ghế của xe
     */
    private function taoGheChoChuyenXe(int $maChuyenXe, int $soGheXe): void
    {
        $seatConfigs = [
            34 => [['A', 17], ['B', 17]],
            42 => [['A', 21], ['B', 21]],
            41 => [['A', 14], ['B', 14], ['C', 13]],
        ];

        if (!isset($seatConfigs[$soGheXe])) {
            return;
        }

        $soGheMoiTao = 0;
        foreach ($seatConfigs[$soGheXe] as [$prefix, $soHang]) {
            for ($i = 1; $i <= $soHang && $soGheMoiTao < $soGheXe; $i++) {
                $soGhe = $prefix . str_pad($i, 2, '0', STR_PAD_LEFT);
                
                if (!Ghe::where('MaChuyenXe', $maChuyenXe)->where('SoGhe', $soGhe)->exists()) {
                    Ghe::create([
                        'MaChuyenXe' => $maChuyenXe,
                        'SoGhe' => $soGhe,
                        'TrangThai' => 'Trống'
                    ]);
                    $soGheMoiTao++;
                }
            }
        }
    }
}
