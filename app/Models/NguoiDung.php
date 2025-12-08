<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Người dùng
 * 
 * Quản lý thông tin tài khoản người dùng trong hệ thống
 * Bao gồm: khách hàng, nhà xe, admin
 * 
 * @property int $MaNguoiDung Mã người dùng (khóa chính)
 * @property string $HoTen Họ tên người dùng
 * @property string $TenDangNhap Tên đăng nhập
 * @property string $MatKhau Mật khẩu
 * @property int $LoaiNguoiDung Loại người dùng (1: Khách hàng, 2: Nhà xe, 3: Admin)
 * @property string $SDT Số điện thoại
 * @property string $Email Email
 * @property int $TrangThai Trạng thái (0: Chưa kích hoạt, 1: Đã kích hoạt)
 */
class NguoiDung extends Authenticatable
{
    use HasFactory, Notifiable;

    /** Hằng số loại người dùng */
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

    /**
     * Quan hệ 1-1 với nhà xe (nếu là tài khoản nhà xe)
     */
    public function nhaXe(): HasOne
    {
        return $this->hasOne(NhaXe::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    /**
     * Quan hệ 1-n với vé xe đã đặt
     */
    public function veXe(): HasMany
    {
        return $this->hasMany(VeXe::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    /**
     * Quan hệ 1-n với đánh giá đã viết
     */
    public function danhGia(): HasMany
    {
        return $this->hasMany(DanhGia::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    /**
     * Accessor lấy nhãn vai trò người dùng
     */
    public function getRoleLabelAttribute(): string
    {
        return match($this->LoaiNguoiDung) {
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_NHA_XE => 'Nhà xe',
            default => 'Khách hàng',
        };
    }
}
