<?php

namespace App\Http\Controllers;

use App\Models\DanhGia;
use App\Models\NhaXe;
use App\Models\VeXe;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class DanhGiaController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        if (!session()->has('user')) {
            return redirect()->back()->with('error', 'Vui lòng đăng nhập để đánh giá!');
        }
        
        $userId = session('user')->MaNguoiDung;

        $validated = $request->validate([
            'MaNhaXe' => 'required|exists:nhaxe,MaNhaXe',
            'SoSao' => 'required|integer|min:1|max:5',
            'NoiDung' => 'nullable|string|max:1000',
        ], [
            'MaNhaXe.required' => 'Không tìm thấy nhà xe',
            'SoSao.required' => 'Vui lòng chọn số sao đánh giá',
            'SoSao.min' => 'Số sao tối thiểu là 1',
            'SoSao.max' => 'Số sao tối đa là 5',
            'NoiDung.max' => 'Nội dung đánh giá không được quá 1000 ký tự',
        ]);

        // Kiểm tra đã hoàn thành chuyến xe
        $veHoanThanh = VeXe::whereHas('chuyenXe', fn($q) => 
            $q->where('MaNhaXe', $validated['MaNhaXe'])
              ->where('GioDen', '<', now())
        )
        ->where('MaNguoiDung', $userId)
        ->whereIn('TrangThai', ['DaDat', 'Đã thanh toán'])
        ->exists();
        
        if (!$veHoanThanh) {
            return redirect()->back()->with('error', 'Bạn chỉ có thể đánh giá sau khi đã hoàn thành chuyến xe của nhà xe này!');
        }

        // Kiểm tra đã đánh giá chưa
        if (DanhGia::where('MaNguoiDung', $userId)->where('MaNhaXe', $validated['MaNhaXe'])->exists()) {
            return redirect()->back()->with('error', 'Bạn đã đánh giá nhà xe này rồi!');
        }

        DanhGia::create([
            'MaNguoiDung' => $userId,
            'MaNhaXe' => $validated['MaNhaXe'],
            'SoSao' => $validated['SoSao'],
            'NoiDung' => $validated['NoiDung'],
            'DaMuaQua' => true,
            'HienThi' => true,
        ]);

        return redirect()->back()->with('success', 'Cảm ơn bạn đã đánh giá!');
    }

    public function getReviews(int $nhaXeId): JsonResponse
    {
        $danhGia = DanhGia::with('nguoiDung')
            ->where('MaNhaXe', $nhaXeId)
            ->where('HienThi', true)
            ->orderByDesc('NgayDanhGia')
            ->paginate(10);

        return response()->json($danhGia);
    }

    public function destroy(int $id): RedirectResponse
    {
        if (session('role') !== 'admin') {
            return redirect()->back()->with('error', 'Bạn không có quyền thực hiện thao tác này!');
        }

        DanhGia::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Đã xóa đánh giá!');
    }

    public function toggleVisibility(int $id): RedirectResponse
    {
        if (session('role') !== 'admin') {
            return redirect()->back()->with('error', 'Bạn không có quyền thực hiện thao tác này!');
        }

        $danhGia = DanhGia::findOrFail($id);
        $danhGia->update(['HienThi' => !$danhGia->HienThi]);
        return redirect()->back()->with('success', 'Đã cập nhật trạng thái hiển thị!');
    }
}
