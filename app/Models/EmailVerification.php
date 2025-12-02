<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EmailVerification extends Model
{
    protected $fillable = [
        'email',
        'code',
        'type',
        'data',
        'expires_at',
        'verified',
    ];

    protected $casts = [
        'data' => 'array',
        'expires_at' => 'datetime',
        'verified' => 'boolean',
    ];

    /**
     * Tạo mã xác thực mới
     */
    public static function createVerification(string $email, string $type = 'register', array $data = []): self
    {
        // Xóa các mã cũ chưa verify
        self::where('email', $email)
            ->where('verified', false)
            ->where('expires_at', '>', now())
            ->delete();

        // Tạo mã mới (6 chữ số)
        $code = str_pad((string) rand(0, 999999), 6, '0', STR_PAD_LEFT);

        return self::create([
            'email' => $email,
            'code' => $code,
            'type' => $type,
            'data' => $data,
            'expires_at' => now()->addMinutes(15), // Hết hạn sau 15 phút
            'verified' => false,
        ]);
    }

    /**
     * Xác thực mã
     */
    public static function verify(string $email, string $code, string $type = 'register'): ?self
    {
        $verification = self::where('email', $email)
            ->where('code', $code)
            ->where('type', $type)
            ->where('verified', false)
            ->where('expires_at', '>', now())
            ->first();

        if ($verification) {
            $verification->update(['verified' => true]);
            return $verification;
        }

        return null;
    }

    /**
     * Kiểm tra mã còn hiệu lực không
     */
    public function isValid(): bool
    {
        return !$this->verified && $this->expires_at > now();
    }
}
