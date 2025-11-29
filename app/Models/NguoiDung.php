<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NguoiDung extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_KHACH_HANG = 1;
    public const ROLE_NHA_XE = 2;
    public const ROLE_ADMIN = 3;

    protected $table = 'nguoidung';
    protected $primaryKey = 'MaNguoiDung';
    public $timestamps = false;

    protected $fillable = [
        'HoTen',
        'TenDangNhap',
        'MatKhau',
        'LoaiNguoiDung',
        'SDT',
        'Email',
        'TrangThai',
    ];

    protected $casts = [
        'LoaiNguoiDung' => 'integer',
        'TrangThai' => 'integer',
    ];

    protected $hidden = ['MatKhau'];

    public function nhaXe(): HasOne
    {
        return $this->hasOne(NhaXe::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    public function veXe(): HasMany
    {
        return $this->hasMany(VeXe::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    public function danhGia(): HasMany
    {
        return $this->hasMany(DanhGia::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    public function getRoleLabelAttribute(): string
    {
        return match($this->LoaiNguoiDung) {
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_NHA_XE => 'Nhà xe',
            default => 'Khách hàng',
        };
    }
}
