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

class DatVeController extends Controller
{
    private const TRANG_THAI_HUY = ['Hủy', 'Huy', 'Hoàn tiền', 'Hoan tien'];

    public function create(int $ma_chuyen): View
    {
        $chuyen = ChuyenXe::with(['nhaXe', 'tuyenDuong', 'ghe', 'xe'])->findOrFail($ma_chuyen);
        
        $tongGhe = $chuyen->ghe->count() ?: ($chuyen->xe?->SoGhe ?? 0);
        $soGheDaDat = VeXe::where('MaChuyenXe', $ma_chuyen)
            ->whereNotIn('TrangThai', self::TRANG_THAI_HUY)
            ->count();
        $soGheTrong = max(0, $tongGhe - $soGheDaDat);
        
        $gheDaDat = VeXe::where('MaChuyenXe', $ma_chuyen)
            ->whereNotIn('TrangThai', self::TRANG_THAI_HUY)
            ->pluck('MaGhe')
            ->map(fn($id) => (int)$id)
            ->toArray();
        
        $tatCaGhe = $chuyen->ghe()->orderBy('SoGhe')->get();
        
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
        
        $gheTrong = $chuyen->ghe()
            ->whereNotIn('MaGhe', $gheDaDat)
            ->orderBy('SoGhe')
            ->get();
        
        // Tạo ghế tự động nếu cần
        if ($gheTrong->isEmpty() && $tongGhe > 0 && $soGheTrong > 0 && $chuyen->xe) {
            $this->createSeatsForTrip($ma_chuyen, $chuyen->xe->SoGhe);
            $chuyen->refresh()->load('ghe');
            $gheTrong = $chuyen->ghe()
                ->whereNotIn('MaGhe', $gheDaDat)
                ->orderBy('SoGhe')
                ->get();
        }
        
        $user = session('user');
        return view('datve.create', compact('chuyen', 'gheTrong', 'tatCaGhe', 'gheDaDat', 'user', 'tongGhe', 'soGheTrong', 'soGheDaDat'));
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

        $chuyen = ChuyenXe::with(['xe', 'ghe', 'veXe'])->findOrFail($validated['MaChuyenXe']);
        
        $tongGhe = $chuyen->ghe->count() ?: ($chuyen->xe?->SoGhe ?? 0);
        $soGheDaDat = $chuyen->veXe->filter(fn($ve) => 
            !in_array(strtolower($ve->TrangThai ?? ''), array_map('strtolower', self::TRANG_THAI_HUY))
        )->count();
        $soGheTrong = max(0, $tongGhe - $soGheDaDat);
        
        if ($soGheTrong < count($validated['MaGhe'])) {
            return redirect()->back()
                ->with('error', "Chuyến xe này chỉ còn {$soGheTrong} ghế trống. Không thể đặt " . count($validated['MaGhe']) . " ghế!")
                ->withInput();
        }

        $gheDaDat = VeXe::where('MaChuyenXe', $validated['MaChuyenXe'])
            ->whereNotIn('TrangThai', self::TRANG_THAI_HUY)
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
        foreach ($validated['MaGhe'] as $maGhe) {
            if (VeXe::where('MaGhe', $maGhe)
                ->whereNotIn('TrangThai', self::TRANG_THAI_HUY)
                ->exists()) {
                continue;
            }
            
            $veXeList[] = VeXe::create([
                'MaChuyenXe' => $validated['MaChuyenXe'],
                'MaNguoiDung' => $validated['MaNguoiDung'],
                'MaGhe' => $maGhe,
                'TrangThai' => 'da_dat',
                'NgayDat' => now(),
                'GiaTaiThoiDiemDat' => $chuyen->GiaVe,
            ]);
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
            'soGhe' => count($veXeList)
        ]);

        return redirect()->route('datve.payment', $veXeList[0]->MaVe)
            ->with('success', 'Đặt vé thành công! Vui lòng thanh toán để hoàn tất.');
    }

    public function payment(int $ma_ve): View
    {
        $veXe = VeXe::with(['chuyenXe.tuyenDuong', 'chuyenXe.nhaXe', 'ghe', 'nguoiDung'])
            ->findOrFail($ma_ve);
        
        $veXeList = session('veXeList', [$ma_ve]);
        $tongTien = session('tongTien', $veXe->chuyenXe->GiaVe);
        $soGhe = session('soGhe', 1);
        
        $tatCaVe = VeXe::whereIn('MaVe', $veXeList)
            ->with(['ghe', 'chuyenXe'])
            ->get();
        
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

        foreach ($veXeList as $maVe) {
            $ve = VeXe::with('chuyenXe')->find($maVe);
            if ($ve) {
                ThanhToan::create([
                    'MaVe' => $maVe,
                    'SoTien' => $ve->chuyenXe->GiaVe,
                    'PhuongThuc' => $validated['PhuongThuc'],
                    'TrangThai' => 'Success',
                    'NgayThanhToan' => now(),
                ]);
            }
        }

        session()->forget(['veXeList', 'tongTien', 'soGhe']);

        return redirect()->route('datve.payment', $ma_ve)
            ->with('payment_success', 'Thanh toán thành công! Cảm ơn bạn đã sử dụng dịch vụ.');
    }

    private function createSeatsForTrip(int $maChuyenXe, int $soGheXe): void
    {
        $seatConfigs = [
            34 => [['A', 17], ['B', 17]],
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
