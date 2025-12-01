<?php

namespace App\Http\Controllers;

use App\Models\NguoiDung;
use App\Models\NhaXe;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'HoTen' => 'required|string|max:100',
            'TenDangNhap' => 'required|string|max:50',
            'Email' => 'required|email|max:100|unique:NguoiDung,Email',
            'MatKhau' => 'required|string|min:4|max:255',
            'SDT' => 'required|string|max:15|unique:NguoiDung,SDT',
        ]);

        $username = $this->generateUniqueUsername($validated['TenDangNhap']);

        NguoiDung::create([
            'HoTen' => $validated['HoTen'],
            'TenDangNhap' => $username,
            'SDT' => $validated['SDT'],
            'Email' => $validated['Email'],
            'LoaiNguoiDung' => NguoiDung::ROLE_KHACH_HANG,
            'MatKhau' => $validated['MatKhau'],
            'TrangThai' => 1,
        ]);

        return redirect()->route('login.form')
            ->with('success', 'Đăng ký thành công! Mời bạn đăng nhập.');
    }

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'TenDangNhap' => 'required|string',
            'MatKhau' => 'required|string'
        ]);

        $user = NguoiDung::where('TenDangNhap', $credentials['TenDangNhap'])
            ->orWhere('Email', $credentials['TenDangNhap'])
            ->first();

        if (!$user || $user->MatKhau !== $credentials['MatKhau']) {
            throw ValidationException::withMessages([
                'login_error' => 'Tên đăng nhập/Email hoặc mật khẩu không đúng'
            ]);
        }

        // Kiểm tra nhà xe
        if ($user->LoaiNguoiDung === NguoiDung::ROLE_NHA_XE) {
            if (!NhaXe::where('MaNguoiDung', $user->MaNguoiDung)->exists()) {
                throw ValidationException::withMessages([
                    'login_error' => 'Tài khoản nhà xe chưa được kích hoạt. Vui lòng liên hệ admin!'
                ]);
            }
        }

        session()->regenerate();
        session(['user' => $user, 'role' => $this->getUserRole($user->LoaiNguoiDung)]);

        return match($user->LoaiNguoiDung) {
            NguoiDung::ROLE_ADMIN => redirect()->route('admin.dashboard'),
            NguoiDung::ROLE_NHA_XE => redirect()->route('partner.dashboard'),
            default => redirect('/'),
        };
    }

    public function logout(): RedirectResponse
    {
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    }

    public function userHome(): View
    {
        return view('user.home');
    }

    public function partnerHome(): View
    {
        return view('partner.home');
    }

    public function adminHome(): View
    {
        return view('admin.home');
    }

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

    private function getUserRole(int $loaiNguoiDung): string
    {
        return match($loaiNguoiDung) {
            NguoiDung::ROLE_ADMIN => 'admin',
            NguoiDung::ROLE_NHA_XE => 'partner',
            default => 'user',
        };
    }
}
