<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model Chuyến xe
 * 
 * Quản lý thông tin chuyến xe
 * 
 * @property int $MaChuyenXe Mã chuyến (khóa chính)
 * @property int $MaNhaXe Mã nhà xe
 * @property int $MaTuyen Mã tuyến đường
 * @property string $DiemLenXe Điểm lên xe
 * @property string $DiemXuongXe Điểm xuống xe
 * @property int $MaXe Mã xe
 * @property \DateTime $GioKhoiHanh Giờ khởi hành
 * @property \DateTime $GioDen Giờ đến
 * @property float $GiaVe Giá vé
 * @property string $TrangThai Trạng thái (ChoDuyet, DaDuyet, TuChoi, BiKhoa)
 * @property string $LyDoTuChoi Lý do từ chối (nếu có)
 */
class ChuyenXe extends Model
{
    use HasFactory;

    protected $table = 'chuyenxe';
    protected $primaryKey = 'MaChuyenXe';
    public $timestamps = false;

    protected $fillable = [
        'MaNhaXe',
        'MaTuyen',
        'DiemLenXe',
        'DiemXuongXe',
        'MaXe',
        'GioKhoiHanh',
        'GioDen',
        'GiaVe',
        'TrangThai',
        'LyDoTuChoi',
    ];

    protected $casts = [
        'GioKhoiHanh' => 'datetime',
        'GioDen' => 'datetime',
        'GiaVe' => 'decimal:2',
    ];

    /**
     * Quan hệ n-1 với nhà xe
     */
    public function nhaXe(): BelongsTo
    {
        return $this->belongsTo(NhaXe::class, 'MaNhaXe', 'MaNhaXe');
    }

    /**
     * Quan hệ n-1 với tuyến đường
     */
    public function tuyenDuong(): BelongsTo
    {
        return $this->belongsTo(TuyenDuong::class, 'MaTuyen', 'MaTuyen');
    }

    /**
     * Quan hệ 1-n với vé xe
     */
    public function veXe(): HasMany
    {
        return $this->hasMany(VeXe::class, 'MaChuyenXe', 'MaChuyenXe');
    }

    /**
     * Quan hệ 1-n với ghế
     */
    public function ghe(): HasMany
    {
        return $this->hasMany(Ghe::class, 'MaChuyenXe', 'MaChuyenXe');
    }

    /**
     * Quan hệ n-1 với xe
     */
    public function xe(): BelongsTo
    {
        return $this->belongsTo(Xe::class, 'MaXe', 'MaXe');
    }

    /**
     * Scope lấy chuyến còn chỗ
     */
    public function scopeConCho(Builder $query): Builder
    {
        return $query->where('TrangThai', 'Còn chỗ');
    }

    /**
     * Scope lấy chuyến đã được duyệt
     */
    public function scopeDaDuyet(Builder $query): Builder
    {
        return $query->whereNotIn('TrangThai', ['ChoDuyet', 'TuChoi', 'BiKhoa']);
    }

    /**
     * Scope lấy chuyến trong khoảng thời gian
     */
    public function scopeTrongKhoangThoiGian(Builder $query, $startDate, $endDate): Builder
    {
        return $query->whereBetween('GioKhoiHanh', [$startDate, $endDate]);
    }
}
