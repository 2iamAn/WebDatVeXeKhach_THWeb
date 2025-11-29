<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TuyenDuong extends Model
{
    use HasFactory;

    protected $table = 'tuyenduong';
    protected $primaryKey = 'MaTuyen';
    public $timestamps = false;

    protected $fillable = [
        'DiemDi',
        'DiemDen',
        'KhoangCach',
        'ThoiGianHanhTrinh',
        'MaNhaXe',
        'TrangThai',
        'LyDoTuChoi',
    ];

    protected $casts = [
        'KhoangCach' => 'integer',
    ];

    public function chuyenXe(): HasMany
    {
        return $this->hasMany(ChuyenXe::class, 'MaTuyen', 'MaTuyen');
    }

    public function nhaXe(): BelongsTo
    {
        return $this->belongsTo(NhaXe::class, 'MaNhaXe', 'MaNhaXe');
    }
}
