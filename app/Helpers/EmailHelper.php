<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

/**
 * Helper class để gửi email đơn giản trên InfinityFree
 * Sử dụng Gmail SMTP - cách đơn giản nhất
 */
class EmailHelper
{
    /**
     * Gửi email đơn giản (text)
     * 
     * @param string $to Email người nhận
     * @param string $subject Tiêu đề email
     * @param string $message Nội dung email
     * @param string|null $fromName Tên người gửi (tùy chọn)
     * @return bool
     */
    public static function send($to, $subject, $message, $fromName = null)
    {
        try {
            Mail::raw($message, function ($mail) use ($to, $subject, $fromName) {
                $mail->to($to)->subject($subject);
                
                if ($fromName) {
                    $mail->from(config('mail.from.address'), $fromName);
                }
            });
            
            Log::info("Email sent successfully to: {$to}");
            return true;
        } catch (\Exception $e) {
            Log::error("Email sending failed to {$to}: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Gửi email với HTML template
     * 
     * @param string $to Email người nhận
     * @param string $subject Tiêu đề email
     * @param string $view Tên view template (ví dụ: 'emails.verification')
     * @param array $data Dữ liệu truyền vào view
     * @return bool
     */
    public static function sendTemplate($to, $subject, $view, $data = [])
    {
        try {
            Mail::send($view, $data, function ($mail) use ($to, $subject) {
                $mail->to($to)->subject($subject);
            });
            
            Log::info("Email template sent successfully to: {$to}");
            return true;
        } catch (\Exception $e) {
            Log::error("Email template sending failed to {$to}: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Gửi email xác thực tài khoản
     * 
     * @param string $to Email người nhận
     * @param string $verificationCode Mã xác thực
     * @param string $userName Tên người dùng
     * @return bool
     */
    public static function sendVerification($to, $verificationCode, $userName = '')
    {
        $subject = 'Xác thực tài khoản - ' . config('app.name');
        $message = "Xin chào {$userName},\n\n";
        $message .= "Mã xác thực tài khoản của bạn là: {$verificationCode}\n\n";
        $message .= "Mã này có hiệu lực trong 15 phút.\n\n";
        $message .= "Nếu bạn không yêu cầu mã này, vui lòng bỏ qua email này.\n\n";
        $message .= "Trân trọng,\n" . config('app.name');
        
        return self::send($to, $subject, $message);
    }
    
    /**
     * Gửi email thông báo đơn giản
     * 
     * @param string $to Email người nhận
     * @param string $title Tiêu đề thông báo
     * @param string $content Nội dung thông báo
     * @return bool
     */
    public static function sendNotification($to, $title, $content)
    {
        $subject = $title . ' - ' . config('app.name');
        $message = "{$title}\n\n{$content}\n\n";
        $message .= "Trân trọng,\n" . config('app.name');
        
        return self::send($to, $subject, $message);
    }
}
