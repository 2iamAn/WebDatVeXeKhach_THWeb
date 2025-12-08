<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Tuyến đường
 * 
 * Quản lý thông tin tuyến đường xe chạy
 * 
 * @property int $MaTuyen Mã tuyến (khóa chính)
 * @property string $DiemDi Điểm xuất phát
 * @property string $DiemDen Điểm đến
 * @property int $KhoangCach Khoảng cách (km)
 * @property string $ThoiGianHanhTrinh Thời gian hành trình
 * @property int $MaNhaXe Mã nhà xe sở hữu tuyến
 * @property string $TrangThai Trạng thái (ChoDuyet, DaDuyet, TuChoi, NgungHoatDong)
 * @property string $LyDoTuChoi Lý do từ chối (nếu có)
 */
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

    /**
     * Quan hệ 1-n với chuyến xe
     */
    public function chuyenXe(): HasMany
    {
        return $this->hasMany(ChuyenXe::class, 'MaTuyen', 'MaTuyen');
    }

    /**
     * Quan hệ n-1 với nhà xe
     */
    public function nhaXe(): BelongsTo
    {
        return $this->belongsTo(NhaXe::class, 'MaNhaXe', 'MaNhaXe');
    }
}
