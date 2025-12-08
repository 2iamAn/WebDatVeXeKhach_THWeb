<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model Ghế
 * 
 * Quản lý thông tin ghế ngồi trên xe
 * 
 * @property int $MaGhe Mã ghế (khóa chính)
 * @property int $MaChuyenXe Mã chuyến xe
 * @property string $SoGhe Số ghế (VD: A01, B05)
 * @property string $TrangThai Trạng thái (Trống, Giữ chỗ, Đã đặt)
 */
class Ghe extends Model
{
    use HasFactory;

    protected $table = 'ghe';
    protected $primaryKey = 'MaGhe';
    public $timestamps = false;

    protected $fillable = [
        'MaChuyenXe',
        'SoGhe',
        'TrangThai',
    ];

    /**
     * Quan hệ n-1 với chuyến xe
     */
    public function chuyenXe(): BelongsTo
    {
        return $this->belongsTo(ChuyenXe::class, 'MaChuyenXe', 'MaChuyenXe');
    }

    /**
     * Scope lọc theo trạng thái ghế
     */
    public function scopeTrangThai(Builder $query, string $status): Builder
    {
        return $query->where('TrangThai', $status);
    }
}
