<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

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

    public function nhaXe(): BelongsTo
    {
        return $this->belongsTo(NhaXe::class, 'MaNhaXe', 'MaNhaXe');
    }

    public function tuyenDuong(): BelongsTo
    {
        return $this->belongsTo(TuyenDuong::class, 'MaTuyen', 'MaTuyen');
    }

    public function veXe(): HasMany
    {
        return $this->hasMany(VeXe::class, 'MaChuyenXe', 'MaChuyenXe');
    }

    public function ghe(): HasMany
    {
        return $this->hasMany(Ghe::class, 'MaChuyenXe', 'MaChuyenXe');
    }

    public function xe(): BelongsTo
    {
        return $this->belongsTo(Xe::class, 'MaXe', 'MaXe');
    }

    public function scopeConCho(Builder $query): Builder
    {
        return $query->where('TrangThai', 'Còn chỗ');
    }

    /**
     * Scope để lấy các chuyến đã được duyệt
     */
    public function scopeDaDuyet(Builder $query): Builder
    {
        return $query->whereNotIn('TrangThai', ['ChoDuyet', 'TuChoi', 'BiKhoa']);
    }

    /**
     * Scope để lấy các chuyến trong khoảng thời gian
     */
    public function scopeTrongKhoangThoiGian(Builder $query, $startDate, $endDate): Builder
    {
        return $query->whereBetween('GioKhoiHanh', [$startDate, $endDate]);
    }
}
