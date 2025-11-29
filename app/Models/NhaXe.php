<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

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

    public function nguoiDung(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    public function chuyenXe(): HasMany
    {
        return $this->hasMany(ChuyenXe::class, 'MaNhaXe', 'MaNhaXe');
    }

    public function danhGia(): HasMany
    {
        return $this->hasMany(DanhGia::class, 'MaNhaXe', 'MaNhaXe')
            ->where('HienThi', true)
            ->orderByDesc('NgayDanhGia');
    }

    public function getAverageRatingAttribute(): float
    {
        return (float) ($this->danhGia()->avg('SoSao') ?? 0);
    }

    public function getTotalReviewsAttribute(): int
    {
        return $this->danhGia()->count();
    }
}
