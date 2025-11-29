<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThanhToan extends Model
{
    use HasFactory;

    protected $table = 'thanhtoan';
    protected $primaryKey = 'MaThanhToan';
    public $timestamps = false;

    protected $fillable = [
        'MaVe',
        'SoTien',
        'PhuongThuc',
        'TrangThai',
        'NgayThanhToan',
    ];

    protected $casts = [
        'SoTien' => 'decimal:2',
        'NgayThanhToan' => 'datetime',
    ];

    public function veXe(): BelongsTo
    {
        return $this->belongsTo(VeXe::class, 'MaVe', 'MaVe');
    }
}
