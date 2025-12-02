<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class VeXe extends Model
{
    use HasFactory;

    protected $table = 'vexe';
    protected $primaryKey = 'MaVe';
    public $timestamps = false;

    protected $fillable = [
        'MaNguoiDung',
        'MaChuyenXe',
        'MaGhe',
        'NgayDat',
        'GiaTaiThoiDiemDat',
        'TrangThai',
    ];

    protected $casts = [
        'NgayDat' => 'datetime',
        'GiaTaiThoiDiemDat' => 'decimal:2',
    ];

    public function chuyenXe(): BelongsTo
    {
        return $this->belongsTo(ChuyenXe::class, 'MaChuyenXe', 'MaChuyenXe');
    }

    public function nguoiDung(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    public function thanhToan(): HasOne
    {
        return $this->hasOne(ThanhToan::class, 'MaVe', 'MaVe');
    }

    public function ghe(): BelongsTo
    {
        return $this->belongsTo(Ghe::class, 'MaGhe', 'MaGhe');
    }

    /**
     * Scope để lấy các vé đã thanh toán thành công
     */
    public function scopeDaThanhToan($query)
    {
        return $query->whereNotIn('TrangThai', ['Hủy', 'Huy', 'Hoàn tiền', 'Hoan tien'])
            ->whereHas('thanhToan', function($q) {
                $q->where('TrangThai', 'Success');
            });
    }

    /**
     * Scope để lấy các vé chưa hủy
     */
    public function scopeChuaHuy($query)
    {
        return $query->whereNotIn('TrangThai', ['Hủy', 'Huy', 'Hoàn tiền', 'Hoan tien']);
    }
}
