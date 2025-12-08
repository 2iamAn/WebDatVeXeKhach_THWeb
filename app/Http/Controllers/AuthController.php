<?php

namespace App\Http\Controllers;

use App\Models\NguoiDung;
use App\Models\NhaXe;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * Controller xử lý xác thực người dùng
 * Bao gồm: đăng ký, đăng nhập, đăng xuất
 */
class AuthController extends Controller
{
    /**
     * Hiển thị form đăng ký
     * Yêu cầu email phải được xác thực trước khi đăng ký
     */
    public function showRegister(): View|RedirectResponse
    {
        $emailVerified = session('email_verified', false);
        $verifiedEmail = session('verified_email');
        
        // Kiểm tra xác thực email từ database nếu session không có
        if (!$emailVerified || !$verifiedEmail) {
            $requestEmail = request()->query('email');
            if ($requestEmail) {
                $dbVerification = DB::table('email_verifications')
                    ->where('email', $requestEmail)
                    ->where('type', 'register')
                    ->where('verified', true)
                    ->where('expires_at', '>', now())
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                if ($dbVerification) {
                    session()->put('email_verified', true);
                    session()->put('verified_email', $requestEmail);
                    $emailVerified = true;
                    $verifiedEmail = $requestEmail;
                }
            }
        }
        
        // Chuyển hướng đến trang xác thực nếu chưa xác thực
        if (!$emailVerified || !$verifiedEmail) {
            return redirect()->route('verification.email', ['type' => 'register'])
                ->with('info', 'Vui lòng xác thực email trước khi đăng ký tài khoản.');
        }
        
        return view('auth.register', ['verified_email' => $verifiedEmail]);
    }

    /**
     * Xử lý đăng ký tài khoản mới
     * Kiểm tra email đã xác thực và tạo tài khoản
     */
    public function register(Request $request): RedirectResponse
    {
        $emailVerified = session('email_verified', false);
        $verifiedEmail = session('verified_email');
        
        // Khôi phục session từ database nếu cần
        if (!$emailVerified || !$verifiedEmail) {
            $requestEmail = $request->input('Email');
            if ($requestEmail) {
                $dbVerification = DB::table('email_verifications')
                    ->where('email', $requestEmail)
                    ->where('type', 'register')
                    ->where('verified', true)
                    ->where('expires_at', '>', now())
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                if ($dbVerification) {
                    session()->put('email_verified', true);
                    session()->put('verified_email', $requestEmail);
                    $emailVerified = true;
                    $verifiedEmail = $requestEmail;
                }
            }
        }
        
        if (!$emailVerified || !$verifiedEmail) {
            return redirect()->route('verification.email', ['type' => 'register'])
                ->with('error', 'Vui lòng xác thực email trước khi đăng ký.');
        }

        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'HoTen' => 'required|string|max:100|min:2',
            'TenDangNhap' => 'required|string|max:50|min:3|regex:/^[a-zA-Z0-9_]+$/',
            'Email' => 'required|email|max:100|unique:nguoidung,Email',
            'MatKhau' => 'required|string|min:4|max:255',
            'SDT' => 'required|string|max:15|min:10|regex:/^[0-9]+$/|unique:nguoidung,SDT',
        ], [
            'HoTen.required' => 'Vui lòng nhập họ và tên.',
            'HoTen.min' => 'Họ và tên phải có ít nhất 2 ký tự.',
            'HoTen.max' => 'Họ và tên không được vượt quá 100 ký tự.',
            'TenDangNhap.required' => 'Vui lòng nhập tên đăng nhập.',
            'TenDangNhap.min' => 'Tên đăng nhập phải có ít nhất 3 ký tự.',
            'TenDangNhap.max' => 'Tên đăng nhập không được vượt quá 50 ký tự.',
            'TenDangNhap.regex' => 'Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới (_).',
            'Email.required' => 'Vui lòng nhập email.',
            'Email.email' => 'Email không hợp lệ.',
            'Email.max' => 'Email không được vượt quá 100 ký tự.',
            'Email.unique' => 'Email này đã được sử dụng.',
            'MatKhau.required' => 'Vui lòng nhập mật khẩu.',
            'MatKhau.min' => 'Mật khẩu phải có ít nhất 4 ký tự.',
            'SDT.required' => 'Vui lòng nhập số điện thoại.',
            'SDT.min' => 'Số điện thoại phải có ít nhất 10 chữ số.',
            'SDT.max' => 'Số điện thoại không được vượt quá 15 chữ số.',
            'SDT.regex' => 'Số điện thoại chỉ được chứa các chữ số.',
            'SDT.unique' => 'Số điện thoại này đã được sử dụng.',
        ]);

        // Kiểm tra email form phải khớp với email đã xác thực
        if ($validated['Email'] !== $verifiedEmail) {
            return redirect()->back()
                ->withErrors(['Email' => 'Email phải khớp với email đã xác thực: ' . $verifiedEmail])
                ->withInput();
        }

        $username = $this->generateUniqueUsername($validated['TenDangNhap']);

        try {
            DB::beginTransaction();
            
            $user = NguoiDung::create([
                'HoTen' => $validated['HoTen'],
                'TenDangNhap' => $username,
                'SDT' => $validated['SDT'],
                'Email' => $validated['Email'],
                'LoaiNguoiDung' => NguoiDung::ROLE_KHACH_HANG,
                'MatKhau' => $validated['MatKhau'],
                'TrangThai' => 1,
            ]);
            
            DB::commit();
            
            Log::info('Đăng ký thành công', ['user_id' => $user->MaNguoiDung, 'email' => $user->Email]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Đăng ký thất bại', ['error' => $e->getMessage()]);
            
            return redirect()->back()
                ->withErrors(['error' => 'Có lỗi xảy ra khi đăng ký. Vui lòng thử lại sau.'])
                ->withInput();
        }

        // Lưu thông tin để tự động điền form đăng nhập
        session()->forget(['email_verified', 'verified_email', 'verification_email', 'verification_type']);
        session([
            'auto_login_email' => $validated['Email'],
            'auto_login_password' => $validated['MatKhau'],
        ]);

        return redirect()->route('login.form')
            ->with('success', 'Đăng ký thành công! Thông tin đăng nhập đã được điền sẵn.')
            ->with('auto_fill', true);
    }

    /**
     * Hiển thị form đăng nhập
     */
    public function showLogin(): View
    {
        $autoEmail = session('auto_login_email');
        $autoPassword = session('auto_login_password');
        
        if ($autoEmail) {
            session()->forget(['auto_login_email', 'auto_login_password']);
        }
        
        return view('auth.login', [
            'auto_email' => $autoEmail,
            'auto_password' => $autoPassword,
        ]);
    }

    /**
     * Xử lý đăng nhập
     * Hỗ trợ đăng nhập bằng tên đăng nhập hoặc email
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'TenDangNhap' => 'required|string',
            'MatKhau' => 'required|string'
        ]);

        // Tìm người dùng theo tên đăng nhập hoặc email
        $user = NguoiDung::where('TenDangNhap', $credentials['TenDangNhap'])
            ->orWhere('Email', $credentials['TenDangNhap'])
            ->first();

        if (!$user || $credentials['MatKhau'] !== $user->MatKhau) {
            throw ValidationException::withMessages([
                'login_error' => 'Tên đăng nhập/Email hoặc mật khẩu không đúng'
            ]);
        }

        // Kiểm tra tài khoản nhà xe đã được kích hoạt chưa
        if ($user->LoaiNguoiDung === NguoiDung::ROLE_NHA_XE) {
            if (!NhaXe::where('MaNguoiDung', $user->MaNguoiDung)->exists()) {
                throw ValidationException::withMessages([
                    'login_error' => 'Tài khoản nhà xe chưa được kích hoạt. Vui lòng liên hệ admin!'
                ]);
            }
        }

        // Tái tạo session và lưu thông tin đăng nhập
        session()->regenerate();
        session(['user' => $user, 'role' => $this->getUserRole($user->LoaiNguoiDung)]);

        // Chuyển hướng theo loại người dùng
        return match($user->LoaiNguoiDung) {
            NguoiDung::ROLE_ADMIN => redirect()->route('admin.dashboard'),
            NguoiDung::ROLE_NHA_XE => redirect()->route('partner.dashboard'),
            default => redirect('/'),
        };
    }

    /**
     * Xử lý đăng xuất
     */
    public function logout(): RedirectResponse
    {
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    }

    /**
     * Trang chủ người dùng
     */
    public function userHome(): View
    {
        return view('user.home');
    }

    /**
     * Trang chủ đối tác nhà xe
     */
    public function partnerHome(): View
    {
        return view('partner.home');
    }

    /**
     * Trang chủ quản trị viên
     */
    public function adminHome(): View
    {
        return view('admin.home');
    }

    /**
     * Tạo tên đăng nhập duy nhất
     * Loại bỏ dấu tiếng Việt và thêm số nếu trùng
     */
    private function generateUniqueUsername(string $base): string
    {
        $removeAccents = fn($str) => strtolower(preg_replace([
            '/á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/u',
            '/đ/u',
            '/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/u',
            '/í|ì|ỉ|ĩ|ị/u',
            '/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/u',
            '/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/u',
            '/ý|ỳ|ỷ|ỹ|ỵ/u'
        ], ['a', 'd', 'e', 'i', 'o', 'u', 'y'], $str));

        $username = $removeAccents($base);
        $count = 1;

        while (NguoiDung::where('TenDangNhap', $username)->exists()) {
            $username = $removeAccents($base) . $count;
            $count++;
        }

        return $username;
    }

    /**
     * Lấy tên vai trò từ loại người dùng
     */
    private function getUserRole(int $loaiNguoiDung): string
    {
        return match($loaiNguoiDung) {
            NguoiDung::ROLE_ADMIN => 'admin',
            NguoiDung::ROLE_NHA_XE => 'partner',
            default => 'user',
        };
    }
}
