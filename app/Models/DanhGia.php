<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Đánh giá
 * 
 * Quản lý đánh giá của khách hàng về nhà xe
 * 
 * @property int $MaDanhGia Mã đánh giá (khóa chính)
 * @property int $MaNguoiDung Mã người đánh giá
 * @property int $MaNhaXe Mã nhà xe được đánh giá
 * @property int $MaVeXe Mã vé xe liên quan
 * @property int $SoSao Số sao đánh giá (1-5)
 * @property string $NoiDung Nội dung đánh giá
 * @property bool $DaMuaQua Đã mua vé trước đó
 * @property \DateTime $NgayDanhGia Ngày đánh giá
 * @property bool $HienThi Trạng thái hiển thị
 */
class DanhGia extends Model
{
    use HasFactory;

    protected $table = 'danhgia';
    protected $primaryKey = 'MaDanhGia';
    public $timestamps = false;

    protected $fillable = [
        'MaNguoiDung',
        'MaNhaXe',
        'MaVeXe',
        'SoSao',
        'NoiDung',
        'DaMuaQua',
        'NgayDanhGia',
        'HienThi',
    ];

    protected $casts = [
        'DaMuaQua' => 'boolean',
        'HienThi' => 'boolean',
        'NgayDanhGia' => 'datetime',
        'SoSao' => 'integer',
    ];

    /**
     * Quan hệ n-1 với người đánh giá
     */
    public function nguoiDung(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    /**
     * Quan hệ n-1 với nhà xe được đánh giá
     */
    public function nhaXe(): BelongsTo
    {
        return $this->belongsTo(NhaXe::class, 'MaNhaXe', 'MaNhaXe');
    }

    /**
     * Quan hệ n-1 với vé xe liên quan
     */
    public function veXe(): BelongsTo
    {
        return $this->belongsTo(VeXe::class, 'MaVeXe', 'MaVe');
    }
}
