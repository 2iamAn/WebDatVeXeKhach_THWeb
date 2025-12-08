<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Nhà xe
 * 
 * Quản lý thông tin nhà xe đối tác
 * 
 * @property int $MaNhaXe Mã nhà xe (khóa chính)
 * @property int $MaNguoiDung Mã người dùng liên kết
 * @property string $TenNhaXe Tên nhà xe
 * @property string $MoTa Mô tả nhà xe
 * @property string $TrangThai Trạng thái
 * @property string $LyDoTuChoi Lý do từ chối (nếu có)
 * @property string $ChinhSachHuyVe Chính sách hủy vé
 * @property string $QuyDinhNhaXe Quy định của nhà xe
 */
class NhaXe extends Model
{
    use HasFactory;

    protected $table = 'nhaxe';
    protected $primaryKey = 'MaNhaXe';
    public $timestamps = false;

    protected $fillable = [
        'MaNguoiDung',
        'TenNhaXe',
        'MoTa',
        'TrangThai',
        'LyDoTuChoi',
        'ChinhSachHuyVe',
        'QuyDinhNhaXe',
    ];

    /**
     * Quan hệ n-1 với người dùng
     */
    public function nguoiDung(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    /**
     * Quan hệ 1-n với chuyến xe
     */
    public function chuyenXe(): HasMany
    {
        return $this->hasMany(ChuyenXe::class, 'MaNhaXe', 'MaNhaXe');
    }

    /**
     * Quan hệ 1-n với đánh giá (chỉ lấy đánh giá hiển thị)
     */
    public function danhGia(): HasMany
    {
        return $this->hasMany(DanhGia::class, 'MaNhaXe', 'MaNhaXe')
            ->where('HienThi', true)
            ->orderByDesc('NgayDanhGia');
    }

    /**
     * Accessor lấy điểm đánh giá trung bình
     */
    public function getAverageRatingAttribute(): float
    {
        return (float) ($this->danhGia()->avg('SoSao') ?? 0);
    }

    /**
     * Accessor lấy tổng số đánh giá
     */
    public function getTotalReviewsAttribute(): int
    {
        return $this->danhGia()->count();
    }
}
