<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Thanh toán
 * 
 * Quản lý thông tin thanh toán vé xe
 * 
 * @property int $MaThanhToan Mã thanh toán (khóa chính)
 * @property int $MaVe Mã vé được thanh toán
 * @property float $SoTien Số tiền thanh toán
 * @property string $PhuongThuc Phương thức thanh toán
 * @property string $TrangThai Trạng thái (Pending, Success, Failed)
 * @property \DateTime $NgayThanhToan Ngày thanh toán
 */
class ThanhToan extends Model
{
    use HasFactory;

    protected $table = 'thanhtoan';
    protected $primaryKey = 'MaThanhToan';
    public $timestamps = false;

    protected $fillable = [
        'MaVe',
        'SoTien',
        'PhuongThuc',
        'TrangThai',
        'NgayThanhToan',
    ];

    protected $casts = [
        'SoTien' => 'decimal:2',
        'NgayThanhToan' => 'datetime',
    ];

    /**
     * Quan hệ n-1 với vé xe
     */
    public function veXe(): BelongsTo
    {
        return $this->belongsTo(VeXe::class, 'MaVe', 'MaVe');
    }
}
