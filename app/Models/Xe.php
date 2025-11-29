<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function nhaXe(): BelongsTo
    {
        return $this->belongsTo(NhaXe::class, 'MaNhaXe', 'MaNhaXe');
    }
}
