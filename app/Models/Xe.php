<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Xe
 * 
 * Quản lý thông tin phương tiện của nhà xe
 * 
 * @property int $MaXe Mã xe (khóa chính)
 * @property int $MaNhaXe Mã nhà xe sở hữu
 * @property string $TenXe Tên xe
 * @property string $LoaiXe Loại xe (Giường nằm, Limousine, ...)
 * @property string $BienSoXe Biển số xe
 * @property int $SoGhe Số ghế
 * @property int $SoGiuong Số giường nằm
 * @property string $TienNghi Tiện nghi trên xe
 * @property string $HinhAnh1 Đường dẫn hình ảnh 1
 * @property string $HinhAnh2 Đường dẫn hình ảnh 2
 * @property string $HinhAnh3 Đường dẫn hình ảnh 3
 */
class Xe extends Model
{
    use HasFactory;

    protected $table = 'xe';
    protected $primaryKey = 'MaXe';
    public $timestamps = false;

    protected $fillable = [
        'MaNhaXe',
        'TenXe',
        'LoaiXe',
        'BienSoXe',
        'SoGhe',
        'SoGiuong',
        'TienNghi',
        'HinhAnh1',
        'HinhAnh2',
        'HinhAnh3',
    ];

    protected $casts = [
        'SoGhe' => 'integer',
        'SoGiuong' => 'integer',
    ];

    /**
     * Quan hệ n-1 với nhà xe
     */
    public function nhaXe(): BelongsTo
    {
        return $this->belongsTo(NhaXe::class, 'MaNhaXe', 'MaNhaXe');
    }
}
