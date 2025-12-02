<?php

namespace App\Http\Controllers;

use App\Models\ChuyenXe;
use App\Models\NhaXe;
use App\Models\TuyenDuong;
use App\Models\Ghe;
use App\Models\VeXe;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class ChuyenXeController extends Controller
{
    public function index(): View
    {
        $chuyens = ChuyenXe::with(['nhaXe', 'tuyenDuong'])
            ->orderByDesc('MaChuyenXe')
            ->paginate(20);
        return view('chuyenxe.index', compact('chuyens'));
    }

    public function create(): View
    {
        $nhaxes = NhaXe::orderBy('TenNhaXe')->pluck('TenNhaXe', 'MaNhaXe');
        $tuyens = TuyenDuong::orderBy('MaTuyen')
            ->get(['MaTuyen', 'DiemDi', 'DiemDen'])
            ->mapWithKeys(fn($t) => [$t->MaTuyen => "{$t->DiemDi} - {$t->DiemDen}"]);
        return view('chuyenxe.create', compact('nhaxes', 'tuyens'));
    }

    public function store(Request $r): RedirectResponse
    {
        $validated = $r->validate([
            'MaNhaXe' => ['required', 'integer', 'exists:nhaxe,MaNhaXe'],
            'MaTuyen' => ['required', 'integer', 'exists:tuyenduong,MaTuyen'],
            'GioKhoiHanh' => ['required', 'date'],
            'GioDen' => ['nullable', 'date', 'after:GioKhoiHanh'],
            'GiaVe' => ['required', 'numeric', 'min:0'],
            'TrangThai' => ['nullable', 'string', 'max:50'],
        ]);

        ChuyenXe::create($validated);
        return redirect()->route('chuyenxe.index')->with('success', 'Thêm chuyến xe thành công!');
    }

    public function edit(int $id): View
    {
        $chuyen = ChuyenXe::findOrFail($id);
        $nhaxes = NhaXe::orderBy('TenNhaXe')->pluck('TenNhaXe', 'MaNhaXe');
        $tuyens = TuyenDuong::orderBy('MaTuyen')
            ->get(['MaTuyen', 'DiemDi', 'DiemDen'])
            ->mapWithKeys(fn($t) => [$t->MaTuyen => "{$t->DiemDi} - {$t->DiemDen}"]);
        return view('chuyenxe.edit', compact('chuyen', 'nhaxes', 'tuyens'));
    }

    public function update(Request $r, int $id): RedirectResponse
    {
        $chuyen = ChuyenXe::findOrFail($id);
        $validated = $r->validate([
            'MaNhaXe' => ['required', 'integer', 'exists:nhaxe,MaNhaXe'],
            'MaTuyen' => ['required', 'integer', 'exists:tuyenduong,MaTuyen'],
            'GioKhoiHanh' => ['required', 'date'],
            'GioDen' => ['nullable', 'date', 'after:GioKhoiHanh'],
            'GiaVe' => ['required', 'numeric', 'min:0'],
            'TrangThai' => ['nullable', 'string', 'max:50'],
        ]);

        $chuyen->update($validated);
        return redirect()->route('chuyenxe.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy(int $id): RedirectResponse
    {
        ChuyenXe::findOrFail($id)->delete();
        return redirect()->route('chuyenxe.index')->with('success', 'Xóa chuyến xe thành công!');
    }

    public function show(int $id): View
    {
        $chuyen = ChuyenXe::with(['nhaXe', 'tuyenDuong', 'veXe.nguoiDung', 'veXe.ghe'])->findOrFail($id);
        return view('chuyenxe.show', compact('chuyen'));
    }

    // Tìm kiếm chuyến xe
    public function search(Request $request): View
    {
        $validated = $request->validate([
            'diem_di' => 'required|string',
            'diem_den' => 'required|string',
            'ngay_khoi_hanh' => 'required|date',
            'so_ghe' => 'required|integer|min:1',
        ]);

        $diemDi = trim($validated['diem_di']);
        $diemDen = trim($validated['diem_den']);
        
        // Tối ưu: Tìm tuyến đường phù hợp với index hỗ trợ
        $tuyenIds = TuyenDuong::where(function($q) use ($diemDi, $diemDen) {
                $q->where('DiemDi', $diemDi)
                  ->where('DiemDen', $diemDen);
            })
            ->orWhere(function($q) use ($diemDi, $diemDen) {
                $q->where('DiemDi', 'like', "%{$diemDi}%")
                  ->where('DiemDen', 'like', "%{$diemDen}%");
            })
            ->where('TrangThai', 'DaDuyet') // Chỉ lấy tuyến đã duyệt
            ->pluck('MaTuyen');

        if ($tuyenIds->isEmpty()) {
            return view('chuyenxe.search_results', [
                'chuyens' => collect(),
                'request' => $request,
                'countByTime' => ['sang_som' => 0, 'buoi_sang' => 0, 'buoi_chieu' => 0, 'buoi_toi' => 0]
            ]);
        }

        $ngayKhoiHanh = Carbon::parse($validated['ngay_khoi_hanh'])->startOfDay();
        $ngayKetThucExtended = $ngayKhoiHanh->copy()->addDays(30)->endOfDay();
        
        // Tối ưu: Sử dụng scope và eager loading tốt hơn
        $query = ChuyenXe::with(['nhaXe', 'tuyenDuong', 'ghe', 'xe'])
            ->whereIn('MaTuyen', $tuyenIds)
            ->whereBetween('GioKhoiHanh', [$ngayKhoiHanh, $ngayKetThucExtended])
            ->daDuyet(); // Sử dụng scope thay vì whereNotIn

        // Lọc theo giờ đi
        if ($request->filled('gio_di')) {
            $timeFilters = [
                'sang_som' => ['00:00:00', '06:00:00'],
                'buoi_sang' => ['06:00:00', '12:00:00'],
                'buoi_chieu' => ['12:00:00', '18:00:00'],
                'buoi_toi' => ['18:00:00', '23:59:59'],
            ];
            
            if (isset($timeFilters[$request->gio_di])) {
                [$start, $end] = $timeFilters[$request->gio_di];
                $query->whereTime('GioKhoiHanh', '>=', $start)
                      ->whereTime('GioKhoiHanh', $end === '23:59:59' ? '<=' : '<', $end);
            }
        }

        // Sắp xếp
        $sortBy = $request->get('sort', 'gia_re');
        match($sortBy) {
            'gia_re' => $query->orderBy('GiaVe', 'asc'),
            'gio_khoi_hanh' => $query->orderBy('GioKhoiHanh', 'asc'),
            default => $query->orderBy('GiaVe', 'asc'),
        };

        $chuyens = $query->get();

        // Tối ưu: Lấy tất cả dữ liệu cần thiết trước để tránh N+1 queries
        $maChuyenXeIds = $chuyens->pluck('MaChuyenXe')->toArray();
        $maNhaXeIds = $chuyens->pluck('MaNhaXe')->unique()->filter()->toArray();
        
        // Tối ưu: Sử dụng scope mới
        $gheDaDatCounts = VeXe::whereIn('MaChuyenXe', $maChuyenXeIds)
            ->daThanhToan()
            ->selectRaw('MaChuyenXe, COUNT(*) as count')
            ->groupBy('MaChuyenXe')
            ->pluck('count', 'MaChuyenXe')
            ->toArray();
        
        // Lấy rating data cho tất cả nhà xe một lần
        $ratingDataByNhaXe = DB::table('danhgia')
            ->whereIn('MaNhaXe', $maNhaXeIds)
            ->where('HienThi', 1)
            ->selectRaw('MaNhaXe, ROUND(AVG(SoSao), 1) as avg_rating, COUNT(*) as total_reviews')
            ->groupBy('MaNhaXe')
            ->get()
            ->keyBy('MaNhaXe')
            ->toArray();
        
        // Lấy đánh giá gần đây cho tất cả nhà xe (giới hạn 5 mỗi nhà xe để tránh quá nhiều dữ liệu)
        $recentReviewsByNhaXe = DB::table('danhgia')
            ->whereIn('MaNhaXe', $maNhaXeIds)
            ->where('HienThi', 1)
            ->leftJoin('nguoidung', 'danhgia.MaNguoiDung', '=', 'nguoidung.MaNguoiDung')
            ->select('danhgia.*', 'nguoidung.HoTen', 'danhgia.MaNhaXe')
            ->orderByDesc('danhgia.NgayDanhGia')
            ->get()
            ->groupBy('MaNhaXe')
            ->map(function($reviews) {
                return $reviews->take(5)->values();
            })
            ->toArray();

        // Tính số ghế trống và rating, map dữ liệu từ relationships
        $chuyens = $chuyens->map(function ($chuyen) use ($gheDaDatCounts, $ratingDataByNhaXe, $recentReviewsByNhaXe) {
            // Map thoiGianHanhTrinh từ tuyenDuong
            $chuyen->thoiGianHanhTrinh = $chuyen->tuyenDuong?->ThoiGianHanhTrinh ?? null;
            $tongGhe = $chuyen->ghe->count();
            
            // Lấy số ghế đã đặt từ cache
            $gheDaDat = $gheDaDatCounts[$chuyen->MaChuyenXe] ?? 0;
            $chuyen->so_ghe_trong = max(0, $tongGhe - $gheDaDat);
            
            if ($tongGhe == 0 && $chuyen->xe) {
                $chuyen->so_ghe_trong = 40; // Default
            }
            
            // Rating cho nhà xe từ cache
            if ($chuyen->nhaXe && isset($ratingDataByNhaXe[$chuyen->nhaXe->MaNhaXe])) {
                $ratingData = $ratingDataByNhaXe[$chuyen->nhaXe->MaNhaXe];
                $chuyen->nhaXe->rating = $ratingData->avg_rating ?? 0;
                $chuyen->nhaXe->total_reviews = $ratingData->total_reviews ?? 0;
                $chuyen->nhaXe->recent_reviews = $recentReviewsByNhaXe[$chuyen->nhaXe->MaNhaXe] ?? collect();
            } else {
                $chuyen->nhaXe->rating = 0;
                $chuyen->nhaXe->total_reviews = 0;
                $chuyen->nhaXe->recent_reviews = collect();
            }
            
            return $chuyen;
        });

        if ($sortBy == 'ghe_trong') {
            $chuyens = $chuyens->sortByDesc('so_ghe_trong')->values();
        }

        // Đếm theo giờ đi
        $countByTime = [
            'sang_som' => $chuyens->filter(fn($c) => Carbon::parse($c->GioKhoiHanh)->hour >= 0 && Carbon::parse($c->GioKhoiHanh)->hour < 6)->count(),
            'buoi_sang' => $chuyens->filter(fn($c) => Carbon::parse($c->GioKhoiHanh)->hour >= 6 && Carbon::parse($c->GioKhoiHanh)->hour < 12)->count(),
            'buoi_chieu' => $chuyens->filter(fn($c) => Carbon::parse($c->GioKhoiHanh)->hour >= 12 && Carbon::parse($c->GioKhoiHanh)->hour < 18)->count(),
            'buoi_toi' => $chuyens->filter(fn($c) => Carbon::parse($c->GioKhoiHanh)->hour >= 18)->count(),
        ];

        // Lọc theo số ghế trống
        $chuyens = $chuyens->filter(fn($chuyen) => 
            $chuyen->ghe->count() == 0 || $chuyen->so_ghe_trong >= $validated['so_ghe']
        );

        return view('chuyenxe.search_results', compact('chuyens', 'request', 'countByTime'));
    }
}
