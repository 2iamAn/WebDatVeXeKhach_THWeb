<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

class BaoCao extends Model
{
    use HasFactory;

    protected $table = 'BaoCao';
    protected $primaryKey = 'MaBaoCao';
    public $timestamps = false;

    protected $fillable = [
        'MaNhaXe',
        'ThoiGianBaoCao',
        'TongSoVe',
        'TongDoanhThu',
        'GhiChu',
    ];

    protected $casts = [
        'ThoiGianBaoCao' => 'datetime',
        'TongSoVe' => 'integer',
        'TongDoanhThu' => 'decimal:2',
    ];

    public function nhaXe(): BelongsTo
    {
        return $this->belongsTo(NhaXe::class, 'MaNhaXe', 'MaNhaXe');
    }

    public function scopeOfNhaXe(Builder $query, int $maNhaXe): Builder
    {
        return $query->where('MaNhaXe', $maNhaXe);
    }

    public function scopeBetween(Builder $query, string $from, string $to): Builder
    {
        return $query->whereBetween('ThoiGianBaoCao', [$from, $to]);
    }

    public function scopeForDate(Builder $query, string $date): Builder
    {
        return $query->whereDate('ThoiGianBaoCao', $date);
    }

    public function scopeForMonth(Builder $query, int $year, int $month): Builder
    {
        return $query->whereYear('ThoiGianBaoCao', $year)
            ->whereMonth('ThoiGianBaoCao', $month);
    }

    protected function tongDoanhThuFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->TongDoanhThu, 0, ',', '.') . ' ₫'
        );
    }
}
