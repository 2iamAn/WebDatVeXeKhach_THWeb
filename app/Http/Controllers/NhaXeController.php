<?php

namespace App\Http\Controllers;

use App\Models\NhaXe;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NhaXeController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        $nhaxes = NhaXe::with('nguoiDung')->paginate(20);
        return view('nhaxe.index', compact('nhaxes'));
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        $users = NguoiDung::where('LoaiNguoiDung', NguoiDung::ROLE_NHA_XE)
            ->orderBy('HoTen')
            ->get(['MaNguoiDung','HoTen']);
        return view('nhaxe.create', compact('users'));
    }

    public function store(Request $r): \Illuminate\Http\RedirectResponse
    {
        $r->validate([
            'MaNguoiDung' => ['required','integer','exists:nguoidung,MaNguoiDung', 'unique:nhaxe,MaNguoiDung'],
            'TenNhaXe'    => ['required','string','max:150'],
            'MoTa'        => ['nullable','string','max:255'],
        ]);

        NhaXe::create($r->only('MaNguoiDung','TenNhaXe','MoTa'));
        return redirect()->route('nhaxe.index')->with('success','Thêm nhà xe thành công!');
    }

    public function edit(int $id): \Illuminate\Contracts\View\View
    {
        $nhaxe = NhaXe::findOrFail($id);
        $users = NguoiDung::where('LoaiNguoiDung', NguoiDung::ROLE_NHA_XE)
            ->orderBy('HoTen')
            ->get(['MaNguoiDung','HoTen']);
        return view('nhaxe.edit', compact('nhaxe','users'));
    }

    public function update(Request $r, int $id): \Illuminate\Http\RedirectResponse
    {
        $nhaxe = NhaXe::findOrFail($id);
        $r->validate([
            'MaNguoiDung' => [
                'required','integer','exists:nguoidung,MaNguoiDung',
                Rule::unique('NhaXe','MaNguoiDung')->ignore($id,'MaNhaXe'),
            ],
            'TenNhaXe' => ['required','string','max:150'],
            'MoTa'     => ['nullable','string','max:255'],
        ]);

        $nhaxe->update($r->only('MaNguoiDung','TenNhaXe','MoTa'));
        return redirect()->route('nhaxe.index')->with('success','Cập nhật thành công!');
    }

    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        NhaXe::findOrFail($id)->delete();
        return redirect()->route('nhaxe.index')->with('success','Xóa nhà xe thành công!');
    }

    public function show(int $id): \Illuminate\Contracts\View\View
    {
        $nhaxe = NhaXe::with(['nguoiDung', 'chuyenXe.tuyenDuong', 'danhGia.nguoiDung'])->findOrFail($id);
        
        // Lấy các tuyến đường unique mà nhà xe đang chạy
        $tuyens = $nhaxe->chuyenXe
            ->pluck('tuyenDuong')
            ->filter()
            ->groupBy(function($tuyen) {
                return $tuyen->DiemDi . ' → ' . $tuyen->DiemDen;
            })
            ->map(function($group) {
                return $group->first();
            })
            ->values();
        
        // Lấy rating và tổng số đánh giá từ database
        $rating = round($nhaxe->danhGia()->avg('SoSao'), 1) ?: 0;
        $totalReviews = $nhaxe->danhGia()->count();
        
        // Lấy danh sách đánh giá (phân trang)
        $danhGias = $nhaxe->danhGia()->with('nguoiDung')->paginate(5);
        
        // Kiểm tra xem người dùng hiện tại đã đánh giá chưa và có quyền đánh giá không
        $daDanhGia = false;
        $duocPhepDanhGia = false;
        
        if (session()->has('user') && session('user')) {
            $userId = session('user')->MaNguoiDung;
            
            // Kiểm tra đã đánh giá chưa
            $daDanhGia = $nhaxe->danhGia()->where('MaNguoiDung', $userId)->exists();
            
            // Kiểm tra có vé đã hoàn thành chuyến của nhà xe này không
            $duocPhepDanhGia = \App\Models\VeXe::whereHas('chuyenXe', function($q) use ($nhaxe) {
                    $q->where('MaNhaXe', $nhaxe->MaNhaXe)
                      ->where('GioDen', '<', now()); // Chuyến xe đã hoàn thành
                })
                ->where('MaNguoiDung', $userId)
                ->whereIn('TrangThai', ['DaDat', 'Đã thanh toán'])
                ->exists();
        }
        
        return view('nhaxe.show', compact('nhaxe', 'tuyens', 'rating', 'totalReviews', 'danhGias', 'daDanhGia', 'duocPhepDanhGia'));
    }
    
}