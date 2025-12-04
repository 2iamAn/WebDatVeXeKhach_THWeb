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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DatVeController extends Controller
{
    private const TRANG_THAI_HUY = ['Hủy', 'Huy', 'Hoàn tiền', 'Hoan tien'];

    public function create(int $ma_chuyen): View
    {
        $chuyen = ChuyenXe::with(['nhaXe', 'tuyenDuong', 'ghe', 'xe'])->findOrFail($ma_chuyen);
        
        $tongGhe = $chuyen->ghe->count() ?: ($chuyen->xe?->SoGhe ?? 0);
        
        // Tối ưu: Cache thông tin ghế đã đặt trong 1 phút
        $cacheKey = "ghe_da_dat_chuyen_{$ma_chuyen}";
        $gheData = Cache::remember($cacheKey, 60, function() use ($ma_chuyen) {
            // CHỈ tính ghế đã đặt khi vé đã thanh toán thành công
            // (để hiển thị chính xác số ghế đã bán)
            // Logic này phải giống hệt với PartnerController để đảm bảo đồng bộ
            
            $gheDaDat = VeXe::where('MaChuyenXe', $ma_chuyen)
                ->whereNotIn('TrangThai', self::TRANG_THAI_HUY)
                ->whereHas('thanhToan', function($q) {
                    $q->where('TrangThai', 'Success');
                })
                ->pluck('MaGhe')
                ->map(fn($id) => (int)$id)
                ->unique()
                ->toArray();
            
            $soGheDaDat = count($gheDaDat);
            
            // Debug log để kiểm tra
            \Log::info('DatVeController - Ghế đã đặt', [
                'ma_chuyen' => $ma_chuyen,
                'soGheDaDat' => $soGheDaDat,
                'gheDaDat' => $gheDaDat,
            ]);
            
            return [
                'soGheDaDat' => $soGheDaDat,
                'gheDaDat' => $gheDaDat,
            ];
        });
        
        // Lấy dữ liệu từ cache
        $soGheDaDat = $gheData['soGheDaDat'];
        $gheDaDat = $gheData['gheDaDat'];
        
        // Đảm bảo $gheDaDat là mảng và chỉ chứa ghế từ vé đã thanh toán thành công
        if (!is_array($gheDaDat)) {
            $gheDaDat = [];
        }
        
        // Kiểm tra lại để đảm bảo không có vé chưa thanh toán được tính vào
        // (Double check để chắc chắn - BỎ QUA CACHE, LẤY TRỰC TIẾP TỪ DATABASE)
        $gheDaDatVerified = VeXe::where('MaChuyenXe', $ma_chuyen)
            ->whereNotIn('TrangThai', self::TRANG_THAI_HUY)
            ->whereHas('thanhToan', function($q) {
                $q->where('TrangThai', 'Success');
            })
            ->pluck('MaGhe')
            ->map(fn($id) => (int)$id)
            ->unique()
            ->toArray();
        
        // Debug: Kiểm tra xem có vé chưa thanh toán nào đang được tính vào không
        $veChuaThanhToan = VeXe::where('MaChuyenXe', $ma_chuyen)
            ->where('TrangThai', 'da_dat')
            ->whereDoesntHave('thanhToan', function($q) {
                $q->where('TrangThai', 'Success');
            })
            ->pluck('MaGhe')
            ->map(fn($id) => (int)$id)
            ->toArray();
        
        \Log::info('DatVeController - Verify Ghế đã đặt', [
            'ma_chuyen' => $ma_chuyen,
            'gheDaDat_from_cache' => $gheDaDat,
            'gheDaDat_verified' => $gheDaDatVerified,
            'veChuaThanhToan_maGhe' => $veChuaThanhToan,
            'soGheDaDat_verified' => count($gheDaDatVerified),
        ]);
        
        // Sử dụng dữ liệu đã verify (BỎ QUA CACHE)
        $gheDaDat = $gheDaDatVerified;
        $soGheDaDat = count($gheDaDat);
        
        // Xóa cache cũ và cập nhật lại với dữ liệu đúng
        Cache::forget($cacheKey);
        Cache::put($cacheKey, [
            'soGheDaDat' => $soGheDaDat,
            'gheDaDat' => $gheDaDat,
        ], 60);
        
        // Lấy thêm danh sách ghế đã đặt nhưng chưa thanh toán và ghế bị khóa
        // (để tránh double booking khi đặt vé, nhưng không tính vào số ghế đã đặt)
        $gheDaDatChuaThanhToan = VeXe::where('MaChuyenXe', $ma_chuyen)
            ->where('TrangThai', 'da_dat')
            ->whereDoesntHave('thanhToan', function($q) {
                $q->where('TrangThai', 'Success');
            })
            ->pluck('MaGhe')
            ->map(fn($id) => (int)$id)
            ->toArray();
        
        $gheBiKhoa = Ghe::where('MaChuyenXe', $ma_chuyen)
            ->where(function($q) {
                $q->where('TrangThai', 'Giữ chỗ')
                  ->orWhere('TrangThai', 'giu cho');
            })
            ->pluck('MaGhe')
            ->map(fn($id) => (int)$id)
            ->toArray();
        
        // Hợp nhất để tránh double booking (nhưng không tính vào số ghế đã đặt)
        $gheKhongTheDat = array_unique(array_merge($gheDaDat, $gheDaDatChuaThanhToan, $gheBiKhoa));
        
        // Tính số ghế trống: chỉ tính ghế đã thanh toán thành công
        $soGheTrong = max(0, $tongGhe - $soGheDaDat);
        
        // Lấy tất cả ghế hiện có của chuyến (bao gồm cả ghế từ vé đã đặt)
        $tatCaGhe = $chuyen->ghe()->orderBy('SoGhe')->get();
        
        // Lấy thêm các ghế từ vé đã đặt (kể cả chưa thanh toán) nhưng chưa có trong bảng Ghe
        if (!empty($gheDaDat)) {
            $gheTuVe = Ghe::whereIn('MaGhe', $gheDaDat)
                ->where('MaChuyenXe', $ma_chuyen)
                ->get();
            $maGheDaCo = $tatCaGhe->pluck('MaGhe')->toArray();
            foreach ($gheTuVe as $ghe) {
                if (!in_array($ghe->MaGhe, $maGheDaCo)) {
                    $tatCaGhe->push($ghe);
                }
            }
            $tatCaGhe = $tatCaGhe->sortBy('SoGhe')->values();
        }
        
        // Lấy thêm các ghế từ vé đã đặt nhưng chưa thanh toán
        $gheTuVeChuaThanhToan = VeXe::where('MaChuyenXe', $ma_chuyen)
            ->where('TrangThai', 'da_dat')
            ->whereDoesntHave('thanhToan', function($query) {
                $query->where('TrangThai', 'Success');
            })
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
        $tatCaGhe = $tatCaGhe->sortBy('SoGhe')->values();
        
        // Danh sách ghế trống hiện tại (loại bỏ ghế đã đặt, ghế đã đặt chưa thanh toán, và ghế bị khóa)
        $gheTrong = $chuyen->ghe()
            ->whereNotIn('MaGhe', $gheKhongTheDat)
            ->where(function($query) {
                // Chỉ lấy ghế trống (không phải "Giữ chỗ")
                $query->where('TrangThai', 'Trống')
                      ->orWhere('TrangThai', 'trong')
                      ->orWhereNull('TrangThai');
            })
            ->orderBy('SoGhe')
            ->get();
        
        // Tạo ghế tự động nếu cần (lần load đầu tiên khi chưa có ghế trong DB)
        if ($gheTrong->isEmpty() && $tongGhe > 0 && $soGheTrong > 0 && $chuyen->xe) {
            $this->createSeatsForTrip($ma_chuyen, $chuyen->xe->SoGhe);

            // Reload lại quan hệ ghế sau khi tạo
            $chuyen->refresh()->load('ghe');

            // Cập nhật lại danh sách ghế trống (loại bỏ ghế đã đặt, ghế đã đặt chưa thanh toán, và ghế bị khóa)
            $gheTrong = $chuyen->ghe()
                ->whereNotIn('MaGhe', $gheKhongTheDat)
                ->where(function($query) {
                    // Chỉ lấy ghế trống (không phải "Giữ chỗ")
                    $query->where('TrangThai', 'Trống')
                          ->orWhere('TrangThai', 'trong')
                          ->orWhereNull('TrangThai');
                })
                ->orderBy('SoGhe')
                ->get();

            // Cập nhật lại tất cả ghế để truyền xuống view (sử dụng cho sơ đồ ghế)
            $tatCaGhe = $chuyen->ghe()->orderBy('SoGhe')->get();

            // Cập nhật lại tổng số ghế và số ghế trống để hiển thị chính xác ngay lần đầu
            $tongGhe = $chuyen->ghe->count() ?: ($chuyen->xe?->SoGhe ?? 0);
            $soGheTrong = max(0, $tongGhe - $soGheDaDat);
        }
        
        $user = session('user');
        // Ghế đã chọn tạm thời (khi quay lại từ màn thanh toán)
        $selectedSeats = session('selectedSeats', []);
        return view('datve.create', compact('chuyen', 'gheTrong', 'tatCaGhe', 'gheDaDat', 'user', 'tongGhe', 'soGheTrong', 'soGheDaDat', 'selectedSeats'));
    }

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
        
        $tongGhe = $chuyen->ghe->count() ?: ($chuyen->xe?->SoGhe ?? 0);
        // Chỉ tính ghế đã đặt khi vé đã thanh toán thành công
        $soGheDaDat = $chuyen->veXe->filter(fn($ve) => 
            !in_array(strtolower($ve->TrangThai ?? ''), array_map('strtolower', self::TRANG_THAI_HUY))
            && $ve->thanhToan && $ve->thanhToan->TrangThai === 'Success'
        )->count();
        $soGheTrong = max(0, $tongGhe - $soGheDaDat);
        
        if ($soGheTrong < count($validated['MaGhe'])) {
            return redirect()->back()
                ->with('error', "Chuyến xe này chỉ còn {$soGheTrong} ghế trống. Không thể đặt " . count($validated['MaGhe']) . " ghế!")
                ->withInput();
        }

        // Tối ưu: Sử dụng scope mới
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

        $veXeList = [];
        $selectedSeats = [];
        foreach ($validated['MaGhe'] as $maGhe) {
            // Tối ưu: Sử dụng scope mới
            if (VeXe::where('MaGhe', $maGhe)->daThanhToan()->exists()) {
                continue;
            }
            
            // NgayDat sẽ được set tại thời điểm thanh toán
            $ve = VeXe::create([
                'MaChuyenXe' => $validated['MaChuyenXe'],
                'MaNguoiDung' => $validated['MaNguoiDung'],
                'MaGhe' => $maGhe,
                'TrangThai' => 'da_dat',
                'GiaTaiThoiDiemDat' => $chuyen->GiaVe,
            ]);
            $veXeList[] = $ve;

            // Lưu thông tin ghế đã chọn để khi quay lại vẫn hiển thị
            $soGhe = Ghe::find($maGhe)?->SoGhe;
            $selectedSeats[] = [
                'MaGhe' => $maGhe,
                'SoGhe' => $soGhe,
            ];
        }

        if (empty($veXeList)) {
            return redirect()->back()
                ->with('error', 'Không thể đặt vé. Vui lòng thử lại!')
                ->withInput();
        }

        $tongTien = count($veXeList) * $chuyen->GiaVe;
        
        session([
            'veXeList' => collect($veXeList)->pluck('MaVe')->toArray(),
            'tongTien' => $tongTien,
            'soGhe' => count($veXeList),
            'selectedSeats' => $selectedSeats,
        ]);

        return redirect()->route('datve.payment', $veXeList[0]->MaVe)
            ->with('success', 'Đặt vé thành công! Vui lòng thanh toán để hoàn tất.');
    }

    public function payment(int $ma_ve): View
    {
        // Refresh lại dữ liệu để đảm bảo lấy thông tin mới nhất sau khi thanh toán
        $veXe = VeXe::with(['chuyenXe.tuyenDuong', 'chuyenXe.nhaXe', 'ghe', 'nguoiDung'])
            ->findOrFail($ma_ve);
        $veXe->refresh(); // Refresh để lấy dữ liệu mới nhất từ database
        
        $veXeList = session('veXeList', [$ma_ve]);
        $tongTien = session('tongTien', $veXe->chuyenXe->GiaVe);
        $soGhe = session('soGhe', 1);
        
        // Refresh lại tất cả vé để đảm bảo có dữ liệu mới nhất
        $tatCaVe = VeXe::whereIn('MaVe', $veXeList)
            ->with(['ghe', 'chuyenXe'])
            ->get();
        foreach ($tatCaVe as $ve) {
            $ve->refresh();
        }
        
        $daThanhToan = ThanhToan::whereIn('MaVe', $veXeList)
            ->where('TrangThai', 'Success')
            ->count() === count($veXeList);
        
        $user = session('user');
        return view('datve.payment', compact('veXe', 'tatCaVe', 'tongTien', 'soGhe', 'user', 'daThanhToan'));
    }

    public function processPayment(Request $r, int $ma_ve): RedirectResponse
    {
        $validated = $r->validate([
            'PhuongThuc' => ['required', 'string', 'max:30'],
        ]);

        $veXe = VeXe::with('chuyenXe')->findOrFail($ma_ve);
        $veXeList = session('veXeList', [$ma_ve]);
        
        if (ThanhToan::whereIn('MaVe', $veXeList)->where('TrangThai', 'Success')->exists()) {
            return redirect()->back()->with('error', 'Các vé này đã được thanh toán rồi!');
        }

        $now = now();
        foreach ($veXeList as $maVe) {
            $ve = VeXe::with('chuyenXe')->find($maVe);
            if ($ve) {
                // Cập nhật thời gian đặt vé đúng thời điểm thanh toán
                $ve->NgayDat = $now;
                $ve->save();
                // Refresh lại để đảm bảo dữ liệu được cập nhật
                $ve->refresh();

                ThanhToan::create([
                    'MaVe' => $maVe,
                    'SoTien' => $ve->chuyenXe->GiaVe,
                    'PhuongThuc' => $validated['PhuongThuc'],
                    'TrangThai' => 'Success',
                    'NgayThanhToan' => $now,
                ]);
            }
        }

        // Xóa session selectedSeats khi thanh toán thành công
        session()->forget(['veXeList', 'tongTien', 'soGhe', 'selectedSeats']);

        return redirect()->route('datve.payment', $ma_ve)
            ->with('payment_success', 'Thanh toán thành công! Cảm ơn bạn đã sử dụng dịch vụ.');
    }

    private function createSeatsForTrip(int $maChuyenXe, int $soGheXe): void
    {
        $seatConfigs = [
            34 => [['A', 17], ['B', 17]], // Limousine: 17 trên, 17 dưới
            42 => [['A', 21], ['B', 21]], // Xe thường: 21 trên, 21 dưới
            41 => [['A', 14], ['B', 14], ['C', 13]], // Giữ lại cho tương thích ngược
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
