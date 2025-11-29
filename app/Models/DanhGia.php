<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DanhGia extends Model
{
    use HasFactory;

    protected $table = 'danhgia';
    protected $primaryKey = 'MaDanhGia';
    public $timestamps = false;

    protected $fillable = [
        'MaNguoiDung',
        'MaNhaXe',
        'MaVeXe',
        'SoSao',
        'NoiDung',
        'DaMuaQua',
        'NgayDanhGia',
        'HienThi',
    ];

    protected $casts = [
        'DaMuaQua' => 'boolean',
        'HienThi' => 'boolean',
        'NgayDanhGia' => 'datetime',
        'SoSao' => 'integer',
    ];

    public function nguoiDung(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    public function nhaXe(): BelongsTo
    {
        return $this->belongsTo(NhaXe::class, 'MaNhaXe', 'MaNhaXe');
    }

    public function veXe(): BelongsTo
    {
        return $this->belongsTo(VeXe::class, 'MaVeXe', 'MaVe');
    }
}
