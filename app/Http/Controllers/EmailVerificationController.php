<?php

namespace App\Http\Controllers;

use App\Models\EmailVerification;
use App\Helpers\EmailHelper;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class EmailVerificationController extends Controller
{
    /**
     * Hiển thị form nhập email (bước 1)
     */
    public function showEmailForm(string $type = 'register'): View
    {
        return view('auth.verify_email', ['type' => $type]);
    }

    /**
     * Gửi mã xác thực đến email (bước 2)
     */
    public function sendCode(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|max:100',
            'type' => 'required|in:register,partner',
        ]);

        $email = $validated['email'];
        $type = $validated['type'];

        // Kiểm tra email đã tồn tại chưa - KHÔNG cho gửi mã nếu email đã tồn tại
        $exists = DB::table('nguoidung')->where('Email', $email)->exists();
        if ($exists) {
            return redirect()->back()
                ->withErrors(['email' => 'Email này đã được sử dụng. Vui lòng chọn email khác hoặc đăng nhập.'])
                ->withInput()
                ->with('email_exists', true);
        }

        // Tạo mã xác thực
        $verification = EmailVerification::createVerification($email, $type);

        // Gửi email
        $userName = $request->input('name', 'Người dùng');
        $sent = EmailHelper::sendVerification($email, $verification->code, $userName);

        if (!$sent) {
            return redirect()->back()
                ->withErrors(['email' => 'Không thể gửi email. Vui lòng thử lại sau.'])
                ->withInput();
        }

        // Lưu email vào session để bước tiếp theo
        session(['verification_email' => $email, 'verification_type' => $type]);

        return redirect()->route('verification.verify.form', ['type' => $type])
            ->with('success', 'Mã xác thực đã được gửi đến email của bạn. Vui lòng kiểm tra hộp thư (cả thư mục spam).');
    }

    /**
     * Hiển thị form nhập mã xác thực (bước 3)
     */
    public function showVerifyForm(string $type = 'register'): View
    {
        $email = session('verification_email');
        
        if (!$email) {
            return redirect()->route('verification.email', ['type' => $type])
                ->withErrors(['error' => 'Vui lòng nhập email trước.']);
        }

        return view('auth.verify_code', [
            'email' => $email,
            'type' => $type,
        ]);
    }

    /**
     * Xác thực mã (bước 4)
     */
    public function verifyCode(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|size:6',
            'type' => 'required|in:register,partner',
        ]);

        $email = session('verification_email');
        $code = $validated['code'];
        $type = $validated['type'];

        if (!$email) {
            return redirect()->route('verification.email', ['type' => $type])
                ->withErrors(['error' => 'Phiên làm việc đã hết hạn. Vui lòng thử lại.']);
        }

        // Xác thực mã
        $verification = EmailVerification::verify($email, $code, $type);

        if (!$verification) {
            return redirect()->back()
                ->withErrors(['code' => 'Mã xác thực không đúng hoặc đã hết hạn. Vui lòng thử lại.'])
                ->withInput();
        }

        // Đánh dấu email đã được xác thực - Lưu vào persistent session
        session()->put('email_verified', true);
        session()->put('verified_email', $email);
        session()->put('verification_data', $verification->data ?? []);
        session()->save(); // Đảm bảo session được lưu ngay lập tức

        // Redirect đến form đăng ký tương ứng
        if ($type === 'partner') {
            return redirect()->route('partner.request')
                ->with('success', 'Email đã được xác thực thành công!');
        } else {
            return redirect()->route('register.form')
                ->with('success', 'Email đã được xác thực thành công!');
        }
    }

    /**
     * Gửi lại mã xác thực
     */
    public function resendCode(Request $request): RedirectResponse
    {
        $email = session('verification_email');
        $type = $request->input('type', 'register');

        if (!$email) {
            return redirect()->route('verification.email', ['type' => $type])
                ->withErrors(['error' => 'Vui lòng nhập email trước.']);
        }

        // Tạo mã mới
        $verification = EmailVerification::createVerification($email, $type);
        $sent = EmailHelper::sendVerification($email, $verification->code, 'Người dùng');

        if (!$sent) {
            return redirect()->back()
                ->withErrors(['error' => 'Không thể gửi email. Vui lòng thử lại sau.']);
        }

        return redirect()->back()
            ->with('success', 'Mã xác thực mới đã được gửi đến email của bạn.');
    }
}
