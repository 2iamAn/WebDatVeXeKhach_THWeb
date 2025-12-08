<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use App\Models\ChuyenXe;
use App\Models\Ghe;
use App\Models\VeXe;
use App\Models\NguoiDung;
use App\Models\TuyenDuong;
use App\Models\Xe;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Controller xử lý chức năng đối tác nhà xe
 * 
 * Bao gồm các chức năng:
 * - Đăng ký hợp tác nhà xe
 * - Dashboard thống kê
 * - Quản lý chuyến xe (thêm, sửa, xóa)
 * - Quản lý tuyến đường
 * - Quản lý xe
 * - Xem sơ đồ ghế và thông tin đặt vé
 */
class PartnerController extends Controller
{
    /**
     * Hiển thị form đăng ký hợp tác nhà xe
     * Yêu cầu email phải được xác thực trước
     */
    public function showRegisterForm(): View|RedirectResponse
    {
        $emailVerified = session('email_verified', false);
        $verifiedEmail = session('verified_email');
        
        if (!$emailVerified || !$verifiedEmail) {
            return redirect()->route('verification.email', ['type' => 'partner'])
                ->with('info', 'Vui lòng xác thực email trước khi đăng ký hợp tác nhà xe.');
        }
        
        // Kiểm tra nếu có email trong request, hiển thị lý do từ chối nếu có
        $lyDoTuChoi = null;
        if (request()->has('email')) {
            $email = request()->get('email');
            $nguoiDung = DB::table('nguoidung')->where('Email', $email)->first();
            if ($nguoiDung && $nguoiDung->TrangThai == 0) {
                $nhaXe = DB::table('nhaxe')->where('MaNguoiDung', $nguoiDung->MaNguoiDung)->first();
                if ($nhaXe && $nhaXe->LyDoTuChoi) {
                    $lyDoTuChoi = $nhaXe->LyDoTuChoi;
                }
            }
        }
        
        return view('partner.request', [
            'lyDoTuChoi' => $lyDoTuChoi,
            'verified_email' => $verifiedEmail,
        ]);
    }

    /**
     * Xử lý gửi yêu cầu đăng ký hợp tác
     * Tạo tài khoản nhà xe và chờ admin phê duyệt
     */
    public function sendRequest(Request $request)
    {
        $emailVerified = session('email_verified', false);
        $verifiedEmail = session('verified_email');
        
        // Nếu session không có, kiểm tra trong database
        if (!$emailVerified || !$verifiedEmail) {
            // Kiểm tra email từ request
            $requestEmail = $request->input('Email');
            if ($requestEmail) {
                // Kiểm tra trong database xem email đã được verify chưa
                $dbVerification = DB::table('email_verifications')
                    ->where('email', $requestEmail)
                    ->where('type', 'partner')
                    ->where('verified', true)
                    ->where('expires_at', '>', now())
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                if ($dbVerification) {
                    // Email đã được verify trong DB, khôi phục session
                    session()->put('email_verified', true);
                    session()->put('verified_email', $requestEmail);
                    $emailVerified = true;
                    $verifiedEmail = $requestEmail;
                }
            }
        }
        
        if (!$emailVerified || !$verifiedEmail) {
            return redirect()->route('verification.email', ['type' => 'partner'])
                ->with('error', 'Vui lòng xác thực email trước khi đăng ký hợp tác.');
        }

        $request->validate([
            'TenNhaXe' => 'required|string|max:150|min:2',
            'NguoiDaiDien' => 'required|string|max:100|min:2',
            'Email' => 'required|email|max:100|unique:nguoidung,Email',
            'MatKhau' => 'required|string|min:6|max:255|confirmed',
            'SDT' => 'required|string|max:15|min:10|regex:/^[0-9]+$/',
            'DiaChi' => 'required|string|max:255|min:5',
            'MoTa' => 'nullable|string|max:255',
        ], [
            // TenNhaXe
            'TenNhaXe.required' => 'Vui lòng nhập tên nhà xe.',
            'TenNhaXe.min' => 'Tên nhà xe phải có ít nhất 2 ký tự.',
            'TenNhaXe.max' => 'Tên nhà xe không được vượt quá 150 ký tự.',
            
            // NguoiDaiDien
            'NguoiDaiDien.required' => 'Vui lòng nhập tên người đại diện.',
            'NguoiDaiDien.min' => 'Tên người đại diện phải có ít nhất 2 ký tự.',
            'NguoiDaiDien.max' => 'Tên người đại diện không được vượt quá 100 ký tự.',
            
            // Email
            'Email.required' => 'Vui lòng nhập email.',
            'Email.email' => 'Email không hợp lệ. Vui lòng nhập đúng định dạng email.',
            'Email.max' => 'Email không được vượt quá 100 ký tự.',
            'Email.unique' => 'Email này đã được sử dụng. Vui lòng chọn email khác.',
            
            // MatKhau
            'MatKhau.required' => 'Vui lòng nhập mật khẩu.',
            'MatKhau.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'MatKhau.max' => 'Mật khẩu không được vượt quá 255 ký tự.',
            'MatKhau.confirmed' => 'Xác nhận mật khẩu không khớp. Vui lòng nhập lại.',
            
            // SDT
            'SDT.required' => 'Vui lòng nhập số điện thoại.',
            'SDT.min' => 'Số điện thoại phải có ít nhất 10 chữ số.',
            'SDT.max' => 'Số điện thoại không được vượt quá 15 chữ số.',
            'SDT.regex' => 'Số điện thoại chỉ được chứa các chữ số (0-9).',
            
            // DiaChi
            'DiaChi.required' => 'Vui lòng nhập địa chỉ trụ sở.',
            'DiaChi.min' => 'Địa chỉ phải có ít nhất 5 ký tự.',
            'DiaChi.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
            
            // MoTa
            'MoTa.max' => 'Mô tả không được vượt quá 255 ký tự.',
        ]);

        // Kiểm tra email trong form phải khớp với email đã xác thực
        if ($request->Email !== $verifiedEmail) {
            return redirect()->back()
                ->withErrors(['Email' => 'Email phải khớp với email đã xác thực: ' . $verifiedEmail])
                ->withInput();
        }

        // Kiểm tra email đã tồn tại chưa
        if (DB::table('nguoidung')->where('Email', $request->Email)->exists()) {
            return redirect()->back()->withErrors(['Email' => 'Email này đã được sử dụng. Vui lòng chọn email khác.'])->withInput();
        }

        // Tạo tên đăng nhập từ email (phần trước @)
        $emailParts = explode('@', $request->Email);
        $base = Str::slug($emailParts[0]);
        if (!$base) {
            $base = 'nhaxe';
        }
        $username = $base;
        $count = 1;
        while (DB::table('nguoidung')->where('TenDangNhap', $username)->exists()) {
            $username = $base . $count;
            $count++;
        }

        $maNguoiDung = DB::table('nguoidung')->insertGetId([
            'HoTen' => $request->NguoiDaiDien,
            'TenDangNhap' => $username,
            'MatKhau' => $request->MatKhau,
            'LoaiNguoiDung' => NguoiDung::ROLE_NHA_XE,
            'SDT' => $request->SDT,
            'Email' => $request->Email,
            'TrangThai' => 0,
        ]);

        DB::table('nhaxe')->insert([
            'MaNguoiDung' => $maNguoiDung,
            'TenNhaXe' => $request->TenNhaXe,
            'MoTa' => $request->MoTa ?: 'Đăng ký hợp tác',
        ]);

        // Lưu thông tin đăng nhập vào session để tự động điền vào form đăng nhập
        $loginEmail = $request->Email;
        $loginPassword = $request->MatKhau;

        // Xóa session verification sau khi đăng ký thành công
        session()->forget(['email_verified', 'verified_email', 'verification_email', 'verification_type']);

        // Lưu thông tin đăng nhập vào session
        session([
            'auto_login_email' => $loginEmail,
            'auto_login_password' => $loginPassword,
        ]);

        return redirect()->route('login.form')
            ->with('success', 'Đã gửi yêu cầu hợp tác thành công! Thông tin đăng nhập đã được điền sẵn. Lưu ý: Tài khoản của bạn cần được admin phê duyệt trước khi có thể đăng nhập.')
            ->with('auto_fill', true)
            ->with('pending_approval', true);
    }

    /**
     * Hiển thị dashboard thống kê cho nhà xe
     * Bao gồm: tổng chuyến, vé đã bán, doanh thu ngày/tháng
     */
    public function dashboard()
    {
        $maNhaXe = $this->ensurePartner();

        // Cache thống kê trong 5 phút để tối ưu performance
        $cacheKey = "partner_dashboard_{$maNhaXe}_" . now()->format('Y-m-d-H-i');
        
        $stats = Cache::remember($cacheKey, 300, function() use ($maNhaXe) {
            // Tính toán thống kê với query tối ưu hơn
            $tongChuyen = DB::table('chuyenxe')
                ->where('MaNhaXe', $maNhaXe)
                ->count();

            $veDaBan = DB::table('vexe')
                ->join('chuyenxe', 'vexe.MaChuyenXe', '=', 'chuyenxe.MaChuyenXe')
                ->where('chuyenxe.MaNhaXe', $maNhaXe)
                ->count();

            $doanhThuNgay = DB::table('thanhtoan')
                ->join('vexe', 'thanhtoan.MaVe', '=', 'vexe.MaVe')
                ->join('chuyenxe', 'vexe.MaChuyenXe', '=', 'chuyenxe.MaChuyenXe')
                ->where('chuyenxe.MaNhaXe', $maNhaXe)
                ->whereDate('thanhtoan.NgayThanhToan', now()->toDateString())
                ->sum('thanhtoan.SoTien') ?? 0;

            $doanhThuThang = DB::table('thanhtoan')
                ->join('vexe', 'thanhtoan.MaVe', '=', 'vexe.MaVe')
                ->join('chuyenxe', 'vexe.MaChuyenXe', '=', 'chuyenxe.MaChuyenXe')
                ->where('chuyenxe.MaNhaXe', $maNhaXe)
                ->whereYear('thanhtoan.NgayThanhToan', now()->year)
                ->whereMonth('thanhtoan.NgayThanhToan', now()->month)
                ->sum('thanhtoan.SoTien') ?? 0;

            return [
                'tongChuyen' => $tongChuyen,
                'veDaBan' => $veDaBan,
                'doanhThuNgay' => $doanhThuNgay,
                'doanhThuThang' => $doanhThuThang,
            ];
        });

        return view('partner.dashboard', $stats);
    }
    
/**
 * Kiểm tra và lấy mã nhà xe của partner đang đăng nhập
 * @throws \Symfony\Component\HttpKernel\Exception\HttpException Nếu không có quyền
 * @return int Mã nhà xe
 */
private function ensurePartner()
{
    if (session('role') !== 'partner' || !session('user')) {
        abort(403, 'Bạn không có quyền truy cập');
    }

    $user = DB::table('nguoidung')->where('MaNguoiDung', session('user')->MaNguoiDung)->first();
    $nhaxe = DB::table('nhaxe')->where('MaNguoiDung', $user->MaNguoiDung)->first();

    if (!$nhaxe || (int)$user->TrangThai === 0) {
        abort(403, 'Tài khoản nhà xe chưa được kích hoạt');
    }

    return $nhaxe->MaNhaXe;
}

/**
 * Hiển thị danh sách chuyến xe của nhà xe
 * Hỗ trợ lọc theo tháng và ngày
 */
public function trips(Request $request)
{
    $maNhaXe = $this->ensurePartner();

    $query = ChuyenXe::with(['tuyenDuong', 'ghe', 'veXe.thanhToan', 'xe'])
        ->where('MaNhaXe', $maNhaXe);

    // Lọc theo tháng nếu có
    if ($request->has('month') && $request->month) {
        $month = $request->month; // Format: YYYY-MM
        $query->whereYear('GioKhoiHanh', substr($month, 0, 4))
               ->whereMonth('GioKhoiHanh', substr($month, 5, 2));
    } else {
        // Mặc định hiển thị tháng hiện tại
        $query->whereYear('GioKhoiHanh', now()->year)
               ->whereMonth('GioKhoiHanh', now()->month);
    }

    // Lọc theo ngày nếu có
    if ($request->has('date') && $request->date) {
        $query->whereDate('GioKhoiHanh', $request->date);
    }

    $trips = $query->orderBy('GioKhoiHanh', 'asc')
        ->orderByDesc('MaChuyenXe')
        ->get()
        ->map(function ($trip) {
            $trip->DiemDi = $trip->tuyenDuong?->DiemDi;
            $trip->DiemDen = $trip->tuyenDuong?->DiemDen;
            
            // Tính số ghế trống và đã đặt
            $tongGhe = $trip->ghe->count();
            
            // Tối ưu: Sử dụng relationship đã load thay vì filter lại
            $gheDaDat = $trip->veXe->whereNotIn('TrangThai', ['Hủy', 'Huy', 'Hoàn tiền', 'Hoan tien'])
                ->filter(function($ve) {
                    return $ve->thanhToan && $ve->thanhToan->TrangThai === 'Success';
                })
                ->count();
            
            // Nếu chưa có ghế nào trong bảng Ghe, lấy từ thông tin xe
            if ($tongGhe == 0 && $trip->xe && $trip->xe->SoGhe) {
                $tongGhe = $trip->xe->SoGhe;
            }
            
            $trip->so_ghe_trong = max(0, $tongGhe - $gheDaDat);
            $trip->tong_ghe = $tongGhe;
            $trip->so_ve_da_dat = $gheDaDat;
            
            // Lấy lý do từ chối nếu có
            $trip->LyDoTuChoi = $trip->LyDoTuChoi;
            
            return $trip;
        });

    $selectedMonth = $request->month ?? now()->format('Y-m');
    $selectedDate = $request->date ?? null;

    // Lấy danh sách các ngày có chuyến trong tháng
    $daysWithTrips = ChuyenXe::where('MaNhaXe', $maNhaXe)
        ->whereYear('GioKhoiHanh', substr($selectedMonth, 0, 4))
        ->whereMonth('GioKhoiHanh', substr($selectedMonth, 5, 2))
        ->selectRaw('DATE(GioKhoiHanh) as date, COUNT(*) as count')
        ->groupBy('date')
        ->pluck('count', 'date')
        ->toArray();

    return view('partner.trips.index', compact('trips', 'selectedMonth', 'selectedDate', 'daysWithTrips'));
}

/**
 * Hiển thị sơ đồ ghế của các chuyến xe
 * Cho phép xem thông tin đặt vé chi tiết
 */
public function seats(Request $request)
{
    $maNhaXe = $this->ensurePartner();

    $selectedDate = $request->date ?? now()->toDateString();
    $selectedMonth = $request->month ?? now()->format('Y-m');

    // Lấy các chuyến xe đã được phê duyệt trong ngày được chọn
    $chuyens = ChuyenXe::with(['xe', 'ghe', 'veXe'])
        ->where('MaNhaXe', $maNhaXe)
        ->whereDate('GioKhoiHanh', $selectedDate)
        ->where('TrangThai', 'DaDuyet')
        ->get();

    // Lấy chuyến xe được chọn (nếu có) - chỉ lấy chuyến đã được phê duyệt
    $selectedChuyenId = $request->chuyen_id;
    $selectedChuyen = null;
    if ($selectedChuyenId) {
        $selectedChuyen = ChuyenXe::with(['tuyenDuong', 'nhaXe', 'xe'])
            ->where('MaNhaXe', $maNhaXe)
            ->where('MaChuyenXe', $selectedChuyenId)
            ->where('TrangThai', 'DaDuyet')
            ->first();
    } elseif ($chuyens->count() > 0) {
        // Nếu không chọn chuyến cụ thể, lấy chuyến đầu tiên (đã được filter là DaDuyet)
        $selectedChuyen = $chuyens->first();
        $selectedChuyen->load(['tuyenDuong', 'nhaXe', 'xe']);
    }
    
    // Lấy danh sách ghế đã được đặt (từ bảng VeXe) CHỈ CHO CHUYẾN ĐƯỢC CHỌN
    $maChuyenXeSelected = $selectedChuyen ? $selectedChuyen->MaChuyenXe : null;
    
    if ($maChuyenXeSelected) {
        // Lấy thông tin chi tiết vé và người đặt cho mỗi ghế CỦA CHUYẾN ĐƯỢC CHỌN
        // Chỉ lấy vé đã thanh toán thành công
        // Logic này phải giống hệt với DatVeController để đảm bảo đồng bộ
        $veXeInfo = VeXe::with(['nguoiDung', 'ghe', 'chuyenXe', 'thanhToan'])
            ->where('MaChuyenXe', $maChuyenXeSelected)
            ->whereNotIn('TrangThai', ['Hủy', 'Huy', 'Hoàn tiền', 'Hoan tien'])
            ->whereHas('thanhToan', function($query) {
                $query->where('TrangThai', 'Success');
            })
            ->get();
        
        $gheDaDat = $veXeInfo->pluck('MaGhe')->map(fn($id) => (int)$id)->unique()->toArray();
        
        // Debug log để kiểm tra
        \Log::info('PartnerController - Ghế đã đặt', [
            'ma_chuyen' => $maChuyenXeSelected,
            'soGheDaDat' => count($gheDaDat),
            'gheDaDat' => $gheDaDat,
        ]);
        
        // Tạo map veXeInfo với key là MaGhe
        $veXeInfoMap = $veXeInfo->mapWithKeys(function($ve) {
            return [$ve->MaGhe => [
                'maVe' => $ve->MaVe,
                'hoTen' => $ve->nguoiDung->HoTen ?? '---',
                'sdt' => $ve->nguoiDung->SDT ?? '---',
                'email' => $ve->nguoiDung->Email ?? '---',
                'ngayDat' => $ve->NgayDat ? \Carbon\Carbon::parse($ve->NgayDat)->format('d/m/Y H:i') : '---',
                'trangThai' => $ve->TrangThai ?? '---',
                'soGhe' => $ve->ghe->SoGhe ?? '---',
                'phuongThuc' => $ve->thanhToan->PhuongThuc ?? 'Chưa thanh toán',
            ]];
        })->toArray();
    } else {
        // Nếu không có chuyến được chọn, khởi tạo rỗng
        $veXeInfo = collect();
        $gheDaDat = [];
        $veXeInfoMap = [];
    }
    
    // Lấy tất cả ghế CHỈ CỦA CHUYẾN ĐƯỢC CHỌN
    if ($maChuyenXeSelected) {
        $query = Ghe::with('chuyenXe.tuyenDuong')
            ->where('MaChuyenXe', $maChuyenXeSelected);

        $ghe = $query->orderBy('SoGhe')->get();
        
        // Nếu có vé đã đặt nhưng ghế chưa có trong bảng Ghe, thêm vào danh sách
        $gheTuVe = $veXeInfo->map(function($ve) {
            if ($ve->ghe) {
                return $ve->ghe;
            }
            return null;
        })->filter()->unique('MaGhe');
        
        // Lấy danh sách MaGhe đã có
        $maGheDaCo = $ghe->pluck('MaGhe')->toArray();
        
        // Thêm các ghế từ vé vào danh sách nếu chưa có
        foreach ($gheTuVe as $gheVe) {
            if (!in_array($gheVe->MaGhe, $maGheDaCo)) {
                $ghe->push($gheVe);
            }
        }
        
        // Sắp xếp lại
        $ghe = $ghe->sortBy(function($g) {
            return $g->SoGhe;
        })->values();
    } else {
        $ghe = collect();
    }

    // Tính số ghế theo từng loại xe (34 và 41 chỗ) riêng biệt
    // Lấy chuyến xe có xe được gán và có biển số của mỗi loại để hiển thị số liệu
    $tongGhe34 = 0;
    $soGheDaDat34 = 0;
    $tongGhe41 = 0;
    $soGheDaDat41 = 0;
    $chuyen34 = null;
    $chuyen41 = null;
    $chuyen34Backup = null; // Dự phòng nếu không có chuyến có biển số
    $chuyen41Backup = null;
    
    // Xác định số ghế của chuyến được chọn (nếu có)
    $selectedChuyenSoGhe = null;
    if ($selectedChuyen) {
        $selectedChuyen->load(['xe']);
        $selectedChuyenSoGhe = $selectedChuyen->ghe->count();
        if ($selectedChuyenSoGhe == 0 && $selectedChuyen->xe && $selectedChuyen->xe->SoGhe) {
            $selectedChuyenSoGhe = $selectedChuyen->xe->SoGhe;
        }
    }
    
    foreach ($chuyens as $chuyen) {
        $chuyen->load(['xe', 'tuyenDuong', 'nhaXe']);
        $soGheChuyen = $chuyen->ghe->count();
        
        // Nếu chưa có ghế trong bảng Ghe, lấy từ thông tin xe
        if ($soGheChuyen == 0 && $chuyen->xe && $chuyen->xe->SoGhe) {
            $soGheChuyen = $chuyen->xe->SoGhe;
        }
        
        // Tối ưu: Sử dụng relationship đã load
        $veDaDat = $chuyen->veXe->whereNotIn('TrangThai', ['Hủy', 'Huy', 'Hoàn tiền', 'Hoan tien'])
            ->filter(function($ve) {
                return $ve->thanhToan && $ve->thanhToan->TrangThai === 'Success';
            })
            ->count();
        
        // Xử lý xe 34 chỗ
        if ($soGheChuyen == 34) {
            if ($tongGhe34 == 0) {
                $tongGhe34 = $soGheChuyen;
                $soGheDaDat34 = $veDaDat;
            }
            
            // Ưu tiên 1: Chuyến được chọn nếu là 34 chỗ và có xe có biển số
            if ($selectedChuyen && $selectedChuyen->MaChuyenXe == $chuyen->MaChuyenXe && 
                $selectedChuyenSoGhe == 34 && $chuyen->xe && $chuyen->xe->BienSoXe) {
                $chuyen34 = $chuyen;
            }
            // Ưu tiên 2: Chuyến có xe được gán và có biển số
            elseif ($chuyen->xe && $chuyen->xe->BienSoXe && !$chuyen34) {
                $chuyen34 = $chuyen;
            } 
            // Ưu tiên 3: Chuyến được chọn nếu là 34 chỗ (dù không có biển số)
            elseif ($selectedChuyen && $selectedChuyen->MaChuyenXe == $chuyen->MaChuyenXe && 
                    $selectedChuyenSoGhe == 34 && !$chuyen34) {
                $chuyen34 = $chuyen;
            }
            // Dự phòng: Chuyến đầu tiên
            elseif (!$chuyen34Backup && !$chuyen34) {
                $chuyen34Backup = $chuyen;
            }
        }
        
        // Xử lý xe 41 chỗ
        if ($soGheChuyen == 41) {
            if ($tongGhe41 == 0) {
                $tongGhe41 = $soGheChuyen;
                $soGheDaDat41 = $veDaDat;
            }
            
            // Ưu tiên 1: Chuyến được chọn nếu là 41 chỗ và có xe có biển số
            if ($selectedChuyen && $selectedChuyen->MaChuyenXe == $chuyen->MaChuyenXe && 
                $selectedChuyenSoGhe == 41 && $chuyen->xe && $chuyen->xe->BienSoXe) {
                $chuyen41 = $chuyen;
            }
            // Ưu tiên 2: Chuyến có xe được gán và có biển số
            elseif ($chuyen->xe && $chuyen->xe->BienSoXe && !$chuyen41) {
                $chuyen41 = $chuyen;
            }
            // Ưu tiên 3: Chuyến được chọn nếu là 41 chỗ (dù không có biển số)
            elseif ($selectedChuyen && $selectedChuyen->MaChuyenXe == $chuyen->MaChuyenXe && 
                    $selectedChuyenSoGhe == 41 && !$chuyen41) {
                $chuyen41 = $chuyen;
            }
            // Dự phòng: Chuyến đầu tiên
            elseif (!$chuyen41Backup && !$chuyen41) {
                $chuyen41Backup = $chuyen;
            }
        }
    }
    
    // Sử dụng chuyến dự phòng nếu không có chuyến có biển số
    if (!$chuyen34 && $chuyen34Backup) {
        $chuyen34 = $chuyen34Backup;
    }
    if (!$chuyen41 && $chuyen41Backup) {
        $chuyen41 = $chuyen41Backup;
    }
    
    // Mặc định hiển thị cho xe 34 chỗ
    $tongGhe = $tongGhe34;
    $soGheDaDat = $soGheDaDat34;

    // Lấy danh sách ghế đã được đặt (từ bảng VeXe) - đã lấy ở trên
    // $gheDaDat đã được định nghĩa ở trên

    // Lấy danh sách các ngày có chuyến đã được phê duyệt trong tháng
    $daysWithTrips = \App\Models\ChuyenXe::where('MaNhaXe', $maNhaXe)
        ->where('TrangThai', 'DaDuyet')
        ->whereYear('GioKhoiHanh', substr($selectedMonth, 0, 4))
        ->whereMonth('GioKhoiHanh', substr($selectedMonth, 5, 2))
        ->selectRaw('DATE(GioKhoiHanh) as date, COUNT(*) as count')
        ->groupBy('date')
        ->pluck('count', 'date')
        ->toArray();


    return view('partner.seats', compact('ghe', 'selectedDate', 'selectedMonth', 'daysWithTrips', 'tongGhe', 'soGheDaDat', 'tongGhe34', 'soGheDaDat34', 'tongGhe41', 'soGheDaDat41', 'gheDaDat', 'veXeInfo', 'chuyens', 'selectedChuyen', 'veXeInfoMap', 'chuyen34', 'chuyen41'));
}

public function lockSeat(Request $request, $maGhe)
{
    $maNhaXe = $this->ensurePartner();
    
    $ghe = Ghe::with('chuyenXe')->findOrFail($maGhe);
    
    // Kiểm tra quyền
    if ($ghe->chuyenXe->MaNhaXe != $maNhaXe) {
        return response()->json(['success' => false, 'message' => 'Bạn không có quyền khóa ghế này!'], 403);
    }
    
    // Kiểm tra ghế đã được đặt và thanh toán thành công chưa
    $veXe = VeXe::where('MaGhe', $maGhe)
        ->whereNotIn('TrangThai', ['Hủy', 'Huy', 'Hoàn tiền', 'Hoan tien'])
        ->whereHas('thanhToan', function($query) {
            $query->where('TrangThai', 'Success');
        })
        ->first();
    
    if ($veXe) {
        return response()->json(['success' => false, 'message' => 'Không thể khóa ghế đã có khách đặt!'], 400);
    }
    
    $ghe->TrangThai = 'Giữ chỗ';
    $ghe->save();
    
    return response()->json(['success' => true, 'message' => 'Đã khóa ghế thành công!']);
}

public function unlockSeat(Request $request, $maGhe)
{
    $maNhaXe = $this->ensurePartner();
    
    $ghe = Ghe::with('chuyenXe')->findOrFail($maGhe);
    
    // Kiểm tra quyền
    if ($ghe->chuyenXe->MaNhaXe != $maNhaXe) {
        return response()->json(['success' => false, 'message' => 'Bạn không có quyền mở khóa ghế này!'], 403);
    }
    
    // Chỉ mở khóa nếu ghế đang ở trạng thái "Giữ chỗ"
    if ($ghe->TrangThai != 'Giữ chỗ') {
        return response()->json(['success' => false, 'message' => 'Ghế này không ở trạng thái khóa!'], 400);
    }
    
    $ghe->TrangThai = 'Trống';
    $ghe->save();
    
    return response()->json(['success' => true, 'message' => 'Đã mở khóa ghế thành công!']);
}

public function cancelTicketFromPartner(Request $request, $maVe)
{
    $maNhaXe = $this->ensurePartner();
    
    $ve = VeXe::with(['chuyenXe', 'ghe'])->findOrFail($maVe);
    
    // Kiểm tra quyền
    if ($ve->chuyenXe->MaNhaXe != $maNhaXe) {
        return redirect()->back()->with('error', 'Bạn không có quyền hủy vé này!');
    }
    
    // Kiểm tra vé đã được hủy chưa
    if (in_array(strtolower($ve->TrangThai ?? ''), ['hủy', 'huy', 'hoàn tiền', 'hoan tien'])) {
        return redirect()->back()->with('error', 'Vé này đã được hủy rồi!');
    }
    
    DB::transaction(function () use ($ve) {
        // Cập nhật trạng thái vé
        $ve->TrangThai = 'Hủy';
        $ve->save();
        
        // Cập nhật trạng thái ghế về "Trống"
        if ($ve->ghe) {
            $ve->ghe->TrangThai = 'Trống';
            $ve->ghe->save();
        }
    });
    
    return redirect()->back()->with('success', 'Đã hủy vé #' . $ve->MaVe . ' thành công!');
}

public function confirmBoarding(Request $request, $maVe)
{
    $maNhaXe = $this->ensurePartner();
    
    $ve = VeXe::with(['chuyenXe', 'ghe'])->findOrFail($maVe);
    
    // Kiểm tra quyền
    if ($ve->chuyenXe->MaNhaXe != $maNhaXe) {
        return response()->json(['success' => false, 'message' => 'Bạn không có quyền xác nhận vé này!'], 403);
    }
    
    // Kiểm tra vé đã được hủy chưa
    if (in_array(strtolower($ve->TrangThai ?? ''), ['hủy', 'huy', 'hoàn tiền', 'hoan tien'])) {
        return response()->json(['success' => false, 'message' => 'Không thể xác nhận vé đã bị hủy!'], 400);
    }
    
    try {
        DB::transaction(function () use ($ve) {
            // Cập nhật trạng thái vé thành "Đã sử dụng"
            $ve->TrangThai = 'Đã sử dụng';
            $ve->save();
        });
        
        return response()->json([
            'success' => true, 
            'message' => 'Đã xác nhận khách lên xe thành công!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false, 
            'message' => 'Có lỗi xảy ra khi xác nhận: ' . $e->getMessage()
        ], 500);
    }
}

public function tickets()
{
    $maNhaXe = $this->ensurePartner();

    $tickets = VeXe::with(['ghe', 'chuyenXe.tuyenDuong', 'chuyenXe.nhaXe', 'nguoiDung', 'thanhToan'])
        ->whereHas('chuyenXe', fn($q) => $q->where('MaNhaXe', $maNhaXe))
        ->orderByDesc('MaVe')
        ->get();

    return view('partner.tickets', compact('tickets'));
}

public function updateTicketStatus(Request $request, $id)
{
    $maNhaXe = $this->ensurePartner();
    
    $ticket = VeXe::whereHas('chuyenXe', fn($q) => $q->where('MaNhaXe', $maNhaXe))
        ->findOrFail($id);

    $request->validate([
        'TrangThai' => 'required|string|in:Đã thanh toán,Đã đi,Hủy,Hoàn tiền,Chưa thanh toán',
    ]);

    $oldStatus = $ticket->TrangThai;
    $ticket->TrangThai = $request->TrangThai;
    $ticket->save();

    return redirect()->route('partner.tickets')->with('success', "Đã cập nhật trạng thái vé #{$ticket->MaVe} từ '{$oldStatus}' sang '{$request->TrangThai}'");
}

    public function revenue()
    {
        $maNhaXe = $this->ensurePartner();

        // Cache revenue stats trong 5 phút
        $cacheKey = "partner_revenue_{$maNhaXe}_" . now()->format('Y-m-d-H-i');
        
        $stats = Cache::remember($cacheKey, 300, function() use ($maNhaXe) {
            $tongVe = VeXe::whereHas('chuyenXe', fn($q) => $q->where('MaNhaXe', $maNhaXe))->count();

            // Tối ưu: Sử dụng một query duy nhất với subquery
            $doanhThuNgay = DB::table('thanhtoan')
                ->join('vexe', 'thanhtoan.MaVe', '=', 'vexe.MaVe')
                ->join('chuyenxe', 'vexe.MaChuyenXe', '=', 'chuyenxe.MaChuyenXe')
                ->where('chuyenxe.MaNhaXe', $maNhaXe)
                ->whereDate('thanhtoan.NgayThanhToan', now())
                ->sum('thanhtoan.SoTien');

            $doanhThuThang = DB::table('thanhtoan')
                ->join('vexe', 'thanhtoan.MaVe', '=', 'vexe.MaVe')
                ->join('chuyenxe', 'vexe.MaChuyenXe', '=', 'chuyenxe.MaChuyenXe')
                ->where('chuyenxe.MaNhaXe', $maNhaXe)
                ->whereYear('thanhtoan.NgayThanhToan', now()->year)
                ->whereMonth('thanhtoan.NgayThanhToan', now()->month)
                ->sum('thanhtoan.SoTien');

            return [
                'doanhThuNgay' => $doanhThuNgay ?? 0,
                'doanhThuThang' => $doanhThuThang ?? 0,
                'tongVe' => $tongVe,
            ];
        });

        return view('partner.revenue', $stats);
    }

// Quản lý chuyến đi - CRUD
public function createTrip()
{
    $maNhaXe = $this->ensurePartner();
    // Chỉ lấy các tuyến đường đã được admin phê duyệt của nhà xe này
    $tuyens = TuyenDuong::where('MaNhaXe', $maNhaXe)
        ->where('TrangThai', 'DaDuyet')
        ->orderBy('DiemDi')
        ->orderBy('DiemDen')
        ->get();
    $xes = Xe::where('MaNhaXe', $maNhaXe)->get();
    
    // Lấy tên nhà xe
    $user = DB::table('nguoidung')->where('MaNguoiDung', session('user')->MaNguoiDung)->first();
    $nhaxe = DB::table('nhaxe')->where('MaNguoiDung', $user->MaNguoiDung)->first();
    $tenNhaXe = $nhaxe ? $nhaxe->TenNhaXe : null;
    
    return view('partner.trips.create', compact('maNhaXe', 'tuyens', 'xes', 'tenNhaXe'));
}

public function storeTrip(Request $request)
{
    $maNhaXe = $this->ensurePartner();
    
    $request->validate([
        'MaTuyen' => 'required|integer|exists:tuyenduong,MaTuyen',
        'DiemLenXe' => 'required|string|max:255',
        'DiemXuongXe' => 'required|string|max:255',
        'MaXe' => 'nullable|integer|exists:xe,MaXe',
        'GioKhoiHanh' => 'required|date',
        'GioDen' => 'nullable|date|after:GioKhoiHanh',
        'GiaVe' => 'required|numeric|min:0',
    ]);

    // Kiểm tra tuyến đường thuộc về nhà xe này
    $tuyen = TuyenDuong::where('MaTuyen', $request->MaTuyen)
        ->where('MaNhaXe', $maNhaXe)
        ->firstOrFail();

    // Kiểm tra xe thuộc về nhà xe này
    if ($request->MaXe) {
        $xe = Xe::where('MaXe', $request->MaXe)
            ->where('MaNhaXe', $maNhaXe)
            ->firstOrFail();
    }

    ChuyenXe::create([
        'MaNhaXe' => $maNhaXe,
        'MaTuyen' => $request->MaTuyen,
        'DiemLenXe' => $request->DiemLenXe,
        'DiemXuongXe' => $request->DiemXuongXe,
        'MaXe' => $request->MaXe,
        'GioKhoiHanh' => $request->GioKhoiHanh,
        'GioDen' => $request->GioDen,
        'GiaVe' => $request->GiaVe,
        'TrangThai' => 'ChoDuyet', // Mặc định là chờ duyệt
    ]);

    return redirect()->route('partner.trips')->with('success', 'Đã gửi yêu cầu thêm chuyến xe! Chuyến xe sẽ được hiển thị sau khi admin phê duyệt.');
}

public function editTrip($id)
{
    $maNhaXe = $this->ensurePartner();
    $chuyen = ChuyenXe::with(['tuyenDuong', 'xe'])
        ->where('MaChuyenXe', $id)
        ->where('MaNhaXe', $maNhaXe)
        ->firstOrFail();
    
    // Chỉ lấy các tuyến đường đã được admin phê duyệt của nhà xe này
    $tuyens = TuyenDuong::where('MaNhaXe', $maNhaXe)
        ->where('TrangThai', 'DaDuyet')
        ->orderBy('DiemDi')
        ->orderBy('DiemDen')
        ->get();
    $xes = Xe::where('MaNhaXe', $maNhaXe)->get();
    
    // Lấy tên nhà xe
    $user = DB::table('nguoidung')->where('MaNguoiDung', session('user')->MaNguoiDung)->first();
    $nhaxe = DB::table('nhaxe')->where('MaNguoiDung', $user->MaNguoiDung)->first();
    $tenNhaXe = $nhaxe ? $nhaxe->TenNhaXe : null;
    
    return view('partner.trips.edit', compact('chuyen', 'tuyens', 'xes', 'tenNhaXe'));
}

public function updateTrip(Request $request, $id)
{
    $maNhaXe = $this->ensurePartner();
    $chuyen = ChuyenXe::where('MaChuyenXe', $id)
        ->where('MaNhaXe', $maNhaXe)
        ->firstOrFail();

    $request->validate([
        'MaTuyen' => 'required|integer|exists:tuyenduong,MaTuyen',
        'DiemLenXe' => 'required|string|max:255',
        'DiemXuongXe' => 'required|string|max:255',
        'MaXe' => 'nullable|integer|exists:xe,MaXe',
        'GioKhoiHanh' => 'required|date',
        'GioDen' => 'nullable|date|after:GioKhoiHanh',
        'GiaVe' => 'required|numeric|min:0',
    ]);

    // Kiểm tra tuyến đường thuộc về nhà xe này
    $tuyen = TuyenDuong::where('MaTuyen', $request->MaTuyen)
        ->where('MaNhaXe', $maNhaXe)
        ->firstOrFail();

    // Kiểm tra xe thuộc về nhà xe này
    if ($request->MaXe) {
        $xe = Xe::where('MaXe', $request->MaXe)
            ->where('MaNhaXe', $maNhaXe)
            ->firstOrFail();
    }

    // Nếu chuyến bị từ chối, khi sửa sẽ chuyển về trạng thái chờ duyệt
    $trangThai = $chuyen->TrangThai;
    $isRejected = ($chuyen->TrangThai == 'TuChoi');
    if ($isRejected) {
        $trangThai = 'ChoDuyet';
    }

    $chuyen->update([
        'MaTuyen' => $request->MaTuyen,
        'DiemLenXe' => $request->DiemLenXe,
        'DiemXuongXe' => $request->DiemXuongXe,
        'MaXe' => $request->MaXe,
        'GioKhoiHanh' => $request->GioKhoiHanh,
        'GioDen' => $request->GioDen,
        'GiaVe' => $request->GiaVe,
        'TrangThai' => $trangThai,
        'LyDoTuChoi' => null, // Xóa lý do từ chối khi chỉnh sửa
    ]);

    $message = $isRejected 
        ? 'Đã cập nhật và gửi lại yêu cầu phê duyệt!' 
        : 'Cập nhật chuyến xe thành công!';

    return redirect()->route('partner.trips')->with('success', $message);
}

/**
 * Xóa chuyến xe
 * Ràng buộc: Chỉ xóa chuyến khi chưa có vé nào được đặt
 */
public function deleteTrip($id)
{
    $maNhaXe = $this->ensurePartner();
    $chuyen = ChuyenXe::where('MaChuyenXe', $id)
        ->where('MaNhaXe', $maNhaXe)
        ->firstOrFail();
    
    // Kiểm tra ràng buộc: Chỉ xóa chuyến khi chưa có vé nào được đặt
    $hasActiveTickets = VeXe::where('MaChuyenXe', $id)
        ->chuaHuy() // Chỉ lấy vé chưa hủy
        ->exists();

    if ($hasActiveTickets) {
        return redirect()->route('partner.trips')
            ->with('error', 'Không thể xóa chuyến xe! Chuyến xe này đang có vé đang được đặt. Vui lòng hủy hoặc xử lý các vé trước khi xóa chuyến.');
    }
    
    $chuyen->delete();
    return redirect()->route('partner.trips')->with('success', 'Xóa chuyến xe thành công!');
}

// Toggle trạng thái chuyến (Ngưng/Kích hoạt)
public function toggleTripStatus($id)
{
    $maNhaXe = $this->ensurePartner();
    $chuyen = ChuyenXe::where('MaChuyenXe', $id)
        ->where('MaNhaXe', $maNhaXe)
        ->firstOrFail();
    
    // Chỉ cho phép toggle nếu chuyến đã được admin duyệt
    if ($chuyen->TrangThai == 'ChoDuyet') {
        return redirect()->route('partner.trips')->with('error', 'Chuyến xe đang chờ admin phê duyệt! Bạn chưa thể kích hoạt/tạm dừng.');
    }
    
    if ($chuyen->TrangThai == 'TuChoi') {
        return redirect()->route('partner.trips')->with('error', 'Chuyến xe đã bị từ chối! Vui lòng chỉnh sửa và gửi lại yêu cầu phê duyệt.');
    }
    
    if ($chuyen->TrangThai == 'BiKhoa') {
        return redirect()->route('partner.trips')->with('error', 'Chuyến xe đã bị admin khóa! Bạn không thể kích hoạt.');
    }
    
    // Nếu đang "Còn chỗ" hoặc "DaDuyet" thì chuyển sang "Tạm dừng", ngược lại
    if ($chuyen->TrangThai == 'Còn chỗ' || $chuyen->TrangThai == 'DaDuyet') {
        $chuyen->TrangThai = 'Tạm dừng';
        $message = 'Đã ngưng chuyến xe';
    } else if ($chuyen->TrangThai == 'Tạm dừng') {
        $chuyen->TrangThai = 'Còn chỗ';
        $message = 'Đã kích hoạt chuyến xe';
    } else {
        return redirect()->route('partner.trips')->with('error', 'Không thể thay đổi trạng thái chuyến xe này!');
    }
    
    $chuyen->save();
    return redirect()->route('partner.trips')->with('success', $message);
}

// Xem vé đã đặt theo chuyến
public function viewTripTickets($id)
{
    $maNhaXe = $this->ensurePartner();
    $chuyen = ChuyenXe::with(['tuyenDuong', 'veXe.nguoiDung', 'veXe.ghe'])
        ->where('MaChuyenXe', $id)
        ->where('MaNhaXe', $maNhaXe)
        ->firstOrFail();
    
    return view('partner.trips.tickets', compact('chuyen'));
}

// Cập nhật nhanh giờ chạy/giá vé
public function quickUpdateTrip(Request $request, $id)
{
    $maNhaXe = $this->ensurePartner();
    $chuyen = ChuyenXe::where('MaChuyenXe', $id)
        ->where('MaNhaXe', $maNhaXe)
        ->firstOrFail();

    $request->validate([
        'GioKhoiHanh' => 'nullable|date',
        'GioDen' => 'nullable|date|after:GioKhoiHanh',
        'GiaVe' => 'nullable|numeric|min:0',
    ]);

    if ($request->has('GioKhoiHanh')) {
        $chuyen->GioKhoiHanh = $request->GioKhoiHanh;
    }
    if ($request->has('GioDen')) {
        $chuyen->GioDen = $request->GioDen;
    }
    if ($request->has('GiaVe')) {
        $chuyen->GiaVe = $request->GiaVe;
    }

    $chuyen->save();
    return redirect()->route('partner.trips')->with('success', 'Cập nhật thành công!');
}

// ==================== QUẢN LÝ TUYẾN ĐƯỜNG ====================
public function routes()
{
    $maNhaXe = $this->ensurePartner();
    
    // Lấy tất cả tuyến đường của nhà xe này (bao gồm cả chờ duyệt và đã duyệt)
    $routes = TuyenDuong::where('MaNhaXe', $maNhaXe)
        ->orderByDesc('MaTuyen')
        ->get();

    return view('partner.routes.index', compact('routes'));
}

public function createRoute()
{
    $maNhaXe = $this->ensurePartner();
    return view('partner.routes.create', compact('maNhaXe'));
}

public function storeRoute(Request $request)
{
    $maNhaXe = $this->ensurePartner();
    
    $request->validate([
        'DiemDi' => 'required|string|max:100',
        'DiemDen' => 'required|string|max:100',
        'KhoangCach' => 'required|integer|min:1',
        'ThoiGianHanhTrinh' => 'nullable|string|max:50',
    ]);

    TuyenDuong::create([
        'DiemDi' => $request->DiemDi,
        'DiemDen' => $request->DiemDen,
        'KhoangCach' => $request->KhoangCach,
        'ThoiGianHanhTrinh' => $request->ThoiGianHanhTrinh,
        'MaNhaXe' => $maNhaXe,
        'TrangThai' => 'ChoDuyet', // Gửi cho admin phê duyệt
    ]);

    return redirect()->route('partner.routes')->with('success', 'Đã gửi yêu cầu thêm tuyến đường! Tuyến đường sẽ được hiển thị sau khi admin phê duyệt.');
}

public function editRoute($id)
{
    $maNhaXe = $this->ensurePartner();
    
    $route = TuyenDuong::where('MaNhaXe', $maNhaXe)
        ->findOrFail($id);

    return view('partner.routes.edit', compact('route'));
}

public function updateRoute(Request $request, $id)
{
    $maNhaXe = $this->ensurePartner();
    
    $route = TuyenDuong::where('MaNhaXe', $maNhaXe)
        ->findOrFail($id);

    $request->validate([
        'DiemDi' => 'required|string|max:100',
        'DiemDen' => 'required|string|max:100',
        'KhoangCach' => 'required|integer|min:1',
        'ThoiGianHanhTrinh' => 'nullable|string|max:50',
    ]);

    // Nếu tuyến đã được duyệt, khi sửa sẽ chuyển về trạng thái chờ duyệt
    $trangThai = $route->TrangThai;
    $isRejected = ($route->TrangThai == 'TuChoi');
    if ($isRejected || $route->TrangThai == 'DaDuyet') {
        $trangThai = 'ChoDuyet';
    }

    $route->update([
        'DiemDi' => $request->DiemDi,
        'DiemDen' => $request->DiemDen,
        'KhoangCach' => $request->KhoangCach,
        'ThoiGianHanhTrinh' => $request->ThoiGianHanhTrinh,
        'TrangThai' => $trangThai,
        'LyDoTuChoi' => null, // Xóa lý do từ chối khi chỉnh sửa
    ]);

    $message = $isRejected 
        ? 'Đã cập nhật và gửi lại yêu cầu phê duyệt!' 
        : 'Đã gửi yêu cầu cập nhật tuyến đường! Tuyến đường sẽ được cập nhật sau khi admin phê duyệt.';

    return redirect()->route('partner.routes')->with('success', $message);
}

public function deleteRoute($id)
{
    $maNhaXe = $this->ensurePartner();
    
    $route = TuyenDuong::where('MaNhaXe', $maNhaXe)
        ->findOrFail($id);

    // Kiểm tra ràng buộc: Nếu tuyến đã có chuyến xe
    $hasTrips = ChuyenXe::where('MaTuyen', $id)
        ->where('MaNhaXe', $maNhaXe)
        ->exists();

    if ($hasTrips) {
        // Kiểm tra xem có vé xe nào đã được đặt cho các chuyến xe này không
        $hasTickets = VeXe::whereHas('chuyenXe', function($query) use ($id, $maNhaXe) {
            $query->where('MaTuyen', $id)
                  ->where('MaNhaXe', $maNhaXe);
        })->exists();

        if ($hasTickets) {
            return redirect()->route('partner.routes')->with('error', 'Không thể xóa tuyến đường! Tuyến đường này đã có vé xe được đặt.');
        }
        
        return redirect()->route('partner.routes')->with('error', 'Không thể xóa tuyến đường! Tuyến đường này đã có chuyến xe.');
    }

    $route->delete();
    return redirect()->route('partner.routes')->with('success', 'Xóa tuyến đường thành công!');
}

/**
 * Ngưng hoặc kích hoạt lại tuyến đường
 * Chỉ cho phép với tuyến đã được phê duyệt
 * Ràng buộc: Chỉ ngưng tuyến khi không có vé nào đang được đặt
 */
public function toggleRouteStatus($id)
{
    $maNhaXe = $this->ensurePartner();
    
    $route = TuyenDuong::where('MaNhaXe', $maNhaXe)
        ->findOrFail($id);

    // Chỉ cho phép ngưng/kích hoạt tuyến đã được phê duyệt
    if ($route->TrangThai != 'DaDuyet' && $route->TrangThai != 'NgungHoatDong') {
        return redirect()->route('partner.routes')
            ->with('error', 'Chỉ có thể ngưng/kích hoạt tuyến đường đã được phê duyệt!');
    }

    // Chuyển đổi trạng thái
    if ($route->TrangThai == 'DaDuyet') {
        // Kiểm tra ràng buộc: Chỉ ngưng tuyến khi không có vé nào đang được đặt
        $hasActiveTickets = VeXe::whereHas('chuyenXe', function($query) use ($id, $maNhaXe) {
            $query->where('MaTuyen', $id)
                  ->where('MaNhaXe', $maNhaXe);
        })
        ->chuaHuy() // Chỉ lấy vé chưa hủy
        ->exists();

        if ($hasActiveTickets) {
            return redirect()->route('partner.routes')
                ->with('error', 'Không thể ngưng tuyến đường! Tuyến đường này đang có vé đang được đặt. Vui lòng hủy hoặc xử lý các vé trước khi ngưng tuyến.');
        }

        $route->TrangThai = 'NgungHoatDong';
        $message = 'Đã ngưng hoạt động tuyến đường thành công!';
    } else {
        // Kích hoạt lại không cần kiểm tra ràng buộc
        $route->TrangThai = 'DaDuyet';
        $message = 'Đã kích hoạt lại tuyến đường thành công!';
    }

    $route->save();

    return redirect()->route('partner.routes')->with('success', $message);
}

// ==================== QUẢN LÝ XE ====================
public function vehicles()
{
    $maNhaXe = $this->ensurePartner();
    
    $vehicles = Xe::where('MaNhaXe', $maNhaXe)
        ->orderByDesc('MaXe')
        ->get();

    return view('partner.vehicles.index', compact('vehicles'));
}

public function createVehicle()
{
    $maNhaXe = $this->ensurePartner();
    return view('partner.vehicles.create', compact('maNhaXe'));
}

public function storeVehicle(Request $request)
{
    $maNhaXe = $this->ensurePartner();
    
    $request->validate([
        'TenXe' => 'required|string|max:150',
        'LoaiXe' => 'nullable|string|max:100',
        'BienSoXe' => 'nullable|string|max:20',
        'SoGhe' => 'nullable|integer|min:0',
        'TienNghi' => 'nullable|string',
        'HinhAnh1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'HinhAnh2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'HinhAnh3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $data = [
        'MaNhaXe' => $maNhaXe,
        'TenXe' => $request->TenXe,
        'LoaiXe' => $request->LoaiXe,
        'BienSoXe' => $request->BienSoXe,
        'SoGhe' => $request->SoGhe,
        'TienNghi' => $request->TienNghi,
    ];

    for ($i = 1; $i <= 3; $i++) {
        if ($request->hasFile("HinhAnh{$i}")) {
            $image = $request->file("HinhAnh{$i}");
            $imageName = 'xe_' . time() . '_' . $i . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/xe'), $imageName);
            $data["HinhAnh{$i}"] = 'uploads/xe/' . $imageName;
        }
    }

    Xe::create($data);

    return redirect()->route('partner.vehicles')->with('success', 'Thêm xe thành công!');
}

public function editVehicle($id)
{
    $maNhaXe = $this->ensurePartner();
    
    $vehicle = Xe::where('MaNhaXe', $maNhaXe)->findOrFail($id);

    return view('partner.vehicles.edit', compact('vehicle'));
}

public function updateVehicle(Request $request, $id)
{
    $maNhaXe = $this->ensurePartner();
    
    $vehicle = Xe::where('MaNhaXe', $maNhaXe)->findOrFail($id);

    $request->validate([
        'TenXe' => 'required|string|max:150',
        'LoaiXe' => 'nullable|string|max:100',
        'BienSoXe' => 'nullable|string|max:20',
        'SoGhe' => 'nullable|integer|min:0',
        'TienNghi' => 'nullable|string',
        'HinhAnh1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'HinhAnh2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'HinhAnh3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $data = [
        'TenXe' => $request->TenXe,
        'LoaiXe' => $request->LoaiXe,
        'BienSoXe' => $request->BienSoXe,
        'SoGhe' => $request->SoGhe,
        'TienNghi' => $request->TienNghi,
    ];

    for ($i = 1; $i <= 3; $i++) {
        if ($request->hasFile("HinhAnh{$i}")) {
            if ($vehicle->{"HinhAnh{$i}"} && file_exists(public_path($vehicle->{"HinhAnh{$i}"}))) {
                unlink(public_path($vehicle->{"HinhAnh{$i}"}));
            }
            
            $image = $request->file("HinhAnh{$i}");
            $imageName = 'xe_' . time() . '_' . $i . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/xe'), $imageName);
            $data["HinhAnh{$i}"] = 'uploads/xe/' . $imageName;
        }
    }

    $vehicle->update($data);

    return redirect()->route('partner.vehicles')->with('success', 'Cập nhật xe thành công!');
}

public function deleteVehicle($id)
{
    $maNhaXe = $this->ensurePartner();
    
    $vehicle = Xe::where('MaNhaXe', $maNhaXe)->findOrFail($id);

    for ($i = 1; $i <= 3; $i++) {
        if ($vehicle->{"HinhAnh{$i}"} && file_exists(public_path($vehicle->{"HinhAnh{$i}"}))) {
            unlink(public_path($vehicle->{"HinhAnh{$i}"}));
        }
    }

    $vehicle->delete();
    return redirect()->route('partner.vehicles')->with('success', 'Xóa xe thành công!');
}
}
