<?php

namespace App\Http\Controllers;

use App\Models\NguoiDung;
use App\Models\ChuyenXe;
use App\Models\TuyenDuong;
use App\Models\NhaXe;
use App\Models\VeXe;
use App\Models\ThanhToan;
use App\Mail\PartnerRejectionMail;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    // ===========================
    // DASHBOARD
    // ===========================
    public function dashboard(): View
    {
        // Cache thống kê trong 5 phút để tối ưu performance
        $cacheKey = 'admin_dashboard_' . now()->format('Y-m-d-H-i');
        
        $stats = Cache::remember($cacheKey, 300, function() {
            // Tối ưu: Sử dụng Eloquent và eager loading
            $yeuCauChoDuyet = NhaXe::whereHas('nguoiDung', function($q) {
                $q->where('TrangThai', 0)
                  ->where('LoaiNguoiDung', NguoiDung::ROLE_NHA_XE);
            })->count();

            $tuyenChoDuyet = TuyenDuong::where('TrangThai', 'ChoDuyet')->count();

            return [
                'tongUser' => NguoiDung::where('LoaiNguoiDung', NguoiDung::ROLE_KHACH_HANG)->count(),
                'tongNhaXe' => NhaXe::count(),
                'tongVe' => VeXe::count(),
                'tongChuyen' => ChuyenXe::count(),
                'tongDoanhThu' => ThanhToan::sum('SoTien') ?? 0,
                'yeuCauChoDuyet' => $yeuCauChoDuyet,
                'tuyenChoDuyet' => $tuyenChoDuyet,
            ];
        });

        return view('admin.dashboard', $stats);
    }

    // ===========================
    // QUẢN LÝ USER
    // ===========================
    public function users(): View
    {
        $users = NguoiDung::whereIn('LoaiNguoiDung', [NguoiDung::ROLE_KHACH_HANG, NguoiDung::ROLE_NHA_XE])
            ->orderByDesc('MaNguoiDung')
            ->get();
        return view('admin.users', compact('users'));
    }

    public function editUser(int $id): View
    {
        $user = NguoiDung::findOrFail($id);
        return view('admin.user_edit', compact('user'));
    }

    public function updateUser(Request $request, int $id): RedirectResponse
    {
        $user = NguoiDung::findOrFail($id);
        $user->update([
            'HoTen' => $request->HoTen,
            'SDT' => $request->SDT,
            'Email' => $request->Email,
            'TrangThai' => $request->TrangThai ?? 1,
        ]);

        return redirect()->route('admin.users')->with('success', 'Cập nhật thành công!');
    }

    public function deleteUser(int $id): RedirectResponse
    {
        NguoiDung::findOrFail($id)->delete();
        return back()->with('success', 'Đã xóa người dùng');
    }

    public function approveUser(int $id): RedirectResponse
    {
        $user = NguoiDung::findOrFail($id);
        $user->update(['TrangThai' => 1]);
        return back()->with('success', 'Đã phê duyệt tài khoản thành công!');
    }

    public function rejectUser(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'LyDoTuChoi' => 'required|string|max:500',
        ]);

        $user = NguoiDung::findOrFail($id);
        $user->update(['TrangThai' => 0]);
        return back()->with('success', 'Đã từ chối tài khoản thành công!');
    }

    public function toggleUserStatus(int $id): RedirectResponse
    {
        $user = NguoiDung::findOrFail($id);
        $newStatus = $user->TrangThai == 1 ? 0 : 1;
        $statusText = $newStatus == 1 ? 'kích hoạt' : 'ngưng hoạt động';
        $user->update(['TrangThai' => $newStatus]);
        return back()->with('success', "Đã {$statusText} tài khoản thành công!");
    }

    // ===========================
    // QUẢN LÝ NHÀ XE
    // ===========================
    public function partners(): View
    {
        $partners = NhaXe::with('nguoiDung')
            ->orderByDesc('MaNhaXe')
            ->get();

        $pendingPartners = $partners->where('nguoiDung.TrangThai', 0);
        $approvedPartners = $partners->where('nguoiDung.TrangThai', 1);

        return view('admin.partners', compact('pendingPartners', 'approvedPartners'));
    }

    public function approvePartner(int $id): RedirectResponse
    {
        $nhaXe = NhaXe::with('nguoiDung')->findOrFail($id);
        $nhaXe->nguoiDung->update(['TrangThai' => 1]);
        return back()->with('success', 'Đã duyệt nhà xe thành công! Nhà xe có thể đăng nhập bằng email: ' . $nhaXe->nguoiDung->Email . ' và mật khẩu đã đăng ký.');
    }

    public function rejectPartner(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'LyDoTuChoi' => 'required|string|max:500',
        ]);

        $nhaXe = NhaXe::with('nguoiDung')->findOrFail($id);
        $nhaXe->update(['LyDoTuChoi' => $request->LyDoTuChoi]);

        try {
            Mail::to($nhaXe->nguoiDung->Email)->send(new PartnerRejectionMail($nhaXe->TenNhaXe, $request->LyDoTuChoi));
            Log::info('Email từ chối đã được gửi thành công đến: ' . $nhaXe->nguoiDung->Email);
        } catch (\Exception $e) {
            Log::error('Lỗi gửi email từ chối: ' . $e->getMessage(), [
                'email' => $nhaXe->nguoiDung->Email,
                'error' => $e->getTraceAsString()
            ]);
            return back()->with('warning', 'Đã từ chối yêu cầu hợp tác nhưng không thể gửi email. Vui lòng kiểm tra cấu hình mail hoặc log để xem chi tiết lỗi.');
        }

        return back()->with('success', 'Đã từ chối yêu cầu hợp tác và gửi email phản hồi cho nhà xe thành công!');
    }

    public function deletePartner(int $id): RedirectResponse
    {
        $nhaXe = NhaXe::with('nguoiDung')->findOrFail($id);
        $nhaXe->nguoiDung->delete();
        return back()->with('success', 'Đã xoá nhà xe thành công!');
    }

    // ===========================
    // QUẢN LÝ CHUYẾN XE
    // ===========================
    public function pendingTrips(Request $request): View
    {
        $query = ChuyenXe::with(['nhaXe', 'tuyenDuong', 'xe'])
            ->select('chuyenxe.*');

        if ($request->filled('status') && $request->status != 'all') {
            $query->where('TrangThai', $request->status);
        }

        if ($request->filled('nha_xe') && $request->nha_xe != 'all') {
            $query->where('MaNhaXe', $request->nha_xe);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('tuyenDuong', function($tq) use ($search) {
                    $tq->where('DiemDi', 'like', "%{$search}%")
                       ->orWhere('DiemDen', 'like', "%{$search}%");
                })
                ->orWhereHas('nhaXe', function($nq) use ($search) {
                    $nq->where('TenNhaXe', 'like', "%{$search}%");
                });
            });
        }

        $allTrips = $query->orderByDesc('MaChuyenXe')->get()
            ->map(function ($trip) {
                // Map dữ liệu từ relationships để views có thể dùng
                $trip->TenNhaXe = $trip->nhaXe?->TenNhaXe ?? 'N/A';
                $trip->DiemDi = $trip->tuyenDuong?->DiemDi ?? '--';
                $trip->DiemDen = $trip->tuyenDuong?->DiemDen ?? '--';
                $trip->TenXe = $trip->xe?->TenXe ?? 'N/A';
                $trip->LoaiXe = $trip->xe?->LoaiXe ?? 'N/A';
                return $trip;
            });
        
        $pendingTrips = $allTrips->where('TrangThai', 'ChoDuyet');
        $approvedTrips = $allTrips->whereIn('TrangThai', ['DaDuyet', 'Còn chỗ', 'Hết chỗ']);
        $rejectedTrips = $allTrips->where('TrangThai', 'TuChoi');
        $lockedTrips = $allTrips->where('TrangThai', 'BiKhoa');
        $suspendedTrips = $allTrips->where('TrangThai', 'Tạm dừng');
        
        $allNhaXe = NhaXe::orderBy('TenNhaXe')->get();
        
        return view('admin.trips_pending', compact(
            'pendingTrips', 
            'approvedTrips', 
            'rejectedTrips',
            'lockedTrips',
            'suspendedTrips',
            'allTrips',
            'allNhaXe'
        ));
    }

    public function approveTrip(int $id): RedirectResponse
    {
        $trip = ChuyenXe::findOrFail($id);
        $trip->update([
            'TrangThai' => 'DaDuyet',
            'LyDoTuChoi' => null,
        ]);
        return back()->with('success', 'Đã phê duyệt chuyến xe thành công!');
    }

    public function rejectTrip(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'LyDoTuChoi' => 'required|string|max:500',
        ]);

        ChuyenXe::findOrFail($id)->update([
            'TrangThai' => 'TuChoi',
            'LyDoTuChoi' => $request->LyDoTuChoi,
        ]);
        return back()->with('success', 'Đã từ chối chuyến xe và gửi phản hồi cho nhà xe!');
    }

    public function lockTrip(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'LyDoKhoa' => 'required|string|max:500',
        ]);

        ChuyenXe::findOrFail($id)->update([
            'TrangThai' => 'BiKhoa',
            'LyDoTuChoi' => $request->LyDoKhoa,
        ]);
        return back()->with('success', 'Đã khóa tạm thời chuyến xe thành công!');
    }

    public function unlockTrip(int $id): RedirectResponse
    {
        $trip = ChuyenXe::findOrFail($id);
        
        if ($trip->TrangThai != 'BiKhoa') {
            return back()->with('error', 'Chuyến xe này không ở trạng thái bị khóa!');
        }

        $trip->update([
            'TrangThai' => 'Còn chỗ',
            'LyDoTuChoi' => null,
        ]);
        return back()->with('success', 'Đã mở khóa chuyến xe thành công!');
    }

    public function showTrip(int $id): View
    {
        $chuyen = ChuyenXe::with(['nhaXe', 'tuyenDuong', 'veXe.nguoiDung', 'veXe.ghe'])->findOrFail($id);
        return view('admin.trip_show', compact('chuyen'));
    }

    // ===========================
    // BÁO CÁO THỐNG KÊ
    // ===========================
    public function reports(Request $request): View
    {
        // Lấy danh sách nhà xe cho dropdown
        $nhaxes = NhaXe::orderBy('TenNhaXe')->get();

        // Xử lý filter
        $maNhaXe = $request->input('ma_nha_xe');
        $tuNgay = $request->input('tu_ngay', now()->format('Y-m-d'));
        $denNgay = $request->input('den_ngay', now()->format('Y-m-d'));
        $loaiThoiGian = $request->input('loai_thoi_gian', 'ngay'); // ngay, thang, tuy_chon

        // Xác định khoảng thời gian
        if ($loaiThoiGian === 'ngay') {
            $startDate = now()->startOfDay();
            $endDate = now()->endOfDay();
        } elseif ($loaiThoiGian === 'thang') {
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
        } else {
            $startDate = \Carbon\Carbon::parse($tuNgay)->startOfDay();
            $endDate = \Carbon\Carbon::parse($denNgay)->endOfDay();
        }

        // Query base với filter nhà xe
        $thanhToanQuery = ThanhToan::whereBetween('NgayThanhToan', [$startDate, $endDate]);
        $veXeQuery = VeXe::whereBetween('NgayDat', [$startDate, $endDate]);
        $chuyenXeQuery = ChuyenXe::whereBetween('GioKhoiHanh', [$startDate, $endDate]);

        if ($maNhaXe) {
            $thanhToanQuery->whereHas('veXe.chuyenXe', function($q) use ($maNhaXe) {
                $q->where('chuyenxe.MaNhaXe', $maNhaXe);
            });
            $veXeQuery->whereHas('chuyenXe', function($q) use ($maNhaXe) {
                $q->where('MaNhaXe', $maNhaXe);
            });
            $chuyenXeQuery->where('MaNhaXe', $maNhaXe);
        }

        // Tính toán các chỉ số
        $doanhThu = $thanhToanQuery->sum('SoTien');
        $tongSoVe = $veXeQuery->count();
        $soChuyenChay = $chuyenXeQuery->count();

        // Doanh thu hôm nay và tháng này (không filter)
        $doanhThuNgay = ThanhToan::whereDate('NgayThanhToan', now())->sum('SoTien');
        $doanhThuThang = ThanhToan::whereMonth('NgayThanhToan', now()->month)
            ->whereYear('NgayThanhToan', now()->year)
            ->sum('SoTien');
        
        // Tổng số vé hôm nay và tháng này
        $tongSoVeNgay = VeXe::whereDate('NgayDat', now())->count();
        $tongSoVeThang = VeXe::whereMonth('NgayDat', now()->month)
            ->whereYear('NgayDat', now()->year)
            ->count();
        
        // Số chuyến chạy hôm nay
        $soChuyenChayNgay = ChuyenXe::whereDate('GioKhoiHanh', now())->count();

        // Top tuyến đường (có thể filter theo nhà xe)
        $topTuyenQuery = VeXe::select(
                DB::raw("CONCAT(tuyenduong.DiemDi, ' - ', tuyenduong.DiemDen) as TuyenDuong"),
                DB::raw('COUNT(*) as SoLuong')
            )
            ->join('chuyenxe', 'vexe.MaChuyenXe', '=', 'chuyenxe.MaChuyenXe')
            ->join('tuyenduong', 'chuyenxe.MaTuyen', '=', 'tuyenduong.MaTuyen')
            ->whereBetween('vexe.NgayDat', [$startDate, $endDate]);

        if ($maNhaXe) {
            $topTuyenQuery->where('chuyenxe.MaNhaXe', $maNhaXe);
        }

        $topTuyen = $topTuyenQuery
            ->groupBy('tuyenduong.MaTuyen', 'tuyenduong.DiemDi', 'tuyenduong.DiemDen')
            ->orderByDesc('SoLuong')
            ->limit(5)
            ->get();

        return view('admin.reports', compact(
            'doanhThuNgay', 
            'doanhThuThang',
            'tongSoVeNgay',
            'tongSoVeThang',
            'soChuyenChayNgay',
            'doanhThu',
            'tongSoVe',
            'soChuyenChay',
            'topTuyen',
            'nhaxes',
            'maNhaXe',
            'tuNgay',
            'denNgay',
            'loaiThoiGian'
        ));
    }

    // ===========================
    // QUẢN LÝ TUYẾN ĐƯỜNG
    // ===========================
    public function pendingRoutes(Request $request): View
    {
        $query = TuyenDuong::with('nhaXe');

        if ($request->filled('status') && $request->status != 'all') {
            $query->where('TrangThai', $request->status);
        }

        if ($request->filled('nha_xe') && $request->nha_xe != 'all') {
            $query->where('MaNhaXe', $request->nha_xe);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $separators = ['-', '→', '−', '–'];
            $hasSeparator = false;
            $diemDi = '';
            $diemDen = '';
            
            foreach ($separators as $sep) {
                if (str_contains($search, $sep)) {
                    $parts = explode($sep, $search);
                    if (count($parts) >= 2) {
                        $diemDi = trim($parts[0]);
                        $diemDen = trim($parts[1]);
                        $hasSeparator = true;
                        break;
                    }
                }
            }
            
            $query->where(function($q) use ($search, $hasSeparator, $diemDi, $diemDen) {
                if ($hasSeparator && $diemDi && $diemDen) {
                    $q->where(function($subQ) use ($diemDi, $diemDen) {
                        $subQ->where('DiemDi', 'like', "%{$diemDi}%")
                             ->where('DiemDen', 'like', "%{$diemDen}%");
                    })
                    ->orWhereHas('nhaXe', function($nq) use ($search) {
                        $nq->where('TenNhaXe', 'like', "%{$search}%");
                    });
                } else {
                    $q->where('DiemDi', 'like', "%{$search}%")
                      ->orWhere('DiemDen', 'like', "%{$search}%")
                      ->orWhereHas('nhaXe', function($nq) use ($search) {
                          $nq->where('TenNhaXe', 'like', "%{$search}%");
                      });
                }
            });
        }

        $allRoutes = $query->orderByDesc('MaTuyen')->get()
            ->map(function ($route) {
                // Map dữ liệu từ relationships để views có thể dùng
                $route->TenNhaXe = $route->nhaXe?->TenNhaXe ?? 'N/A';
                return $route;
            });
        
        $pendingRoutes = $allRoutes->where('TrangThai', 'ChoDuyet');
        $approvedRoutes = $allRoutes->where('TrangThai', 'DaDuyet');
        $rejectedRoutes = $allRoutes->where('TrangThai', 'TuChoi');
        
        $allNhaXe = NhaXe::orderBy('TenNhaXe')->get();
        
        return view('admin.routes_pending', compact(
            'pendingRoutes', 
            'approvedRoutes', 
            'rejectedRoutes',
            'allRoutes',
            'allNhaXe'
        ));
    }

    public function approveRoute(int $id): RedirectResponse
    {
        TuyenDuong::findOrFail($id)->update([
            'TrangThai' => 'DaDuyet',
            'LyDoTuChoi' => null,
        ]);
        return back()->with('success', 'Đã phê duyệt tuyến đường thành công!');
    }

    public function rejectRoute(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'LyDoTuChoi' => 'required|string|max:500',
        ]);

        TuyenDuong::findOrFail($id)->update([
            'TrangThai' => 'TuChoi',
            'LyDoTuChoi' => $request->LyDoTuChoi,
        ]);
        return back()->with('success', 'Đã từ chối tuyến đường và gửi phản hồi cho nhà xe!');
    }
}
