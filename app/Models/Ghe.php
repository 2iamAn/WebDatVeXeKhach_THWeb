<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

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

    public function chuyenXe(): BelongsTo
    {
        return $this->belongsTo(ChuyenXe::class, 'MaChuyenXe', 'MaChuyenXe');
    }

    public function scopeTrangThai(Builder $query, string $status): Builder
    {
        return $query->where('TrangThai', $status);
    }
}
