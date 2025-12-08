<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Model Vé xe
 * 
 * Quản lý thông tin vé xe đã đặt
 * 
 * @property int $MaVe Mã vé (khóa chính)
 * @property int $MaNguoiDung Mã người đặt
 * @property int $MaChuyenXe Mã chuyến xe
 * @property int $MaGhe Mã ghế
 * @property \DateTime $NgayDat Ngày đặt vé
 * @property float $GiaTaiThoiDiemDat Giá vé tại thời điểm đặt
 * @property string $TrangThai Trạng thái vé
 */
class VeXe extends Model
{
    use HasFactory;

    protected $table = 'vexe';
    protected $primaryKey = 'MaVe';
    public $timestamps = false;

    protected $fillable = [
        'MaNguoiDung',
        'MaChuyenXe',
        'MaGhe',
        'NgayDat',
        'GiaTaiThoiDiemDat',
        'TrangThai',
    ];

    protected $casts = [
        'NgayDat' => 'datetime',
        'GiaTaiThoiDiemDat' => 'decimal:2',
    ];

    /**
     * Quan hệ n-1 với chuyến xe
     */
    public function chuyenXe(): BelongsTo
    {
        return $this->belongsTo(ChuyenXe::class, 'MaChuyenXe', 'MaChuyenXe');
    }

    /**
     * Quan hệ n-1 với người đặt
     */
    public function nguoiDung(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    /**
     * Quan hệ 1-1 với thanh toán
     */
    public function thanhToan(): HasOne
    {
        return $this->hasOne(ThanhToan::class, 'MaVe', 'MaVe');
    }

    /**
     * Quan hệ n-1 với ghế
     */
    public function ghe(): BelongsTo
    {
        return $this->belongsTo(Ghe::class, 'MaGhe', 'MaGhe');
    }

    /**
     * Scope lấy vé đã thanh toán thành công
     */
    public function scopeDaThanhToan($query)
    {
        return $query->whereNotIn('TrangThai', ['Hủy', 'Huy', 'Hoàn tiền', 'Hoan tien'])
            ->whereHas('thanhToan', fn($q) => $q->where('TrangThai', 'Success'));
    }

    /**
     * Scope lấy vé chưa hủy
     */
    public function scopeChuaHuy($query)
    {
        return $query->whereNotIn('TrangThai', ['Hủy', 'Huy', 'Hoàn tiền', 'Hoan tien']);
    }
}
