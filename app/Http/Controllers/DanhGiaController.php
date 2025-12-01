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
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        if (!session()->has('user')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập để đánh giá!'
                ], 401);
            }
            return redirect()->back()->with('error', 'Vui lòng đăng nhập để đánh giá!');
        }
        
        $userId = session('user')->MaNguoiDung;

        try {
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }

        // Kiểm tra đã hoàn thành chuyến xe (điều kiện bắt buộc để đánh giá)
        // Tìm vé đã hoàn thành chưa được đánh giá, nếu không có thì lấy vé mới nhất
        $veDaDanhGiaIds = DanhGia::where('MaNguoiDung', $userId)
            ->where('MaNhaXe', $validated['MaNhaXe'])
            ->whereNotNull('MaVeXe')
            ->pluck('MaVeXe')
            ->toArray();
        
        // Tìm vé chưa đánh giá: ưu tiên vé "Đã sử dụng" (không cần kiểm tra giờ đến)
        $veChuaDanhGia = VeXe::whereHas('chuyenXe', fn($q) => 
            $q->where('MaNhaXe', $validated['MaNhaXe'])
        )
        ->where('MaNguoiDung', $userId)
        ->where(function($query) {
            // Trường hợp 1: Vé đã được xác nhận lên xe (Đã sử dụng)
            $query->where('TrangThai', 'Đã sử dụng')
                  // Trường hợp 2: Vé đã thanh toán và giờ đến đã qua
                  ->orWhere(function($q) {
                      $q->whereIn('TrangThai', ['DaDat', 'Đã thanh toán', 'da_dat'])
                        ->whereHas('chuyenXe', function($chuyenQuery) {
                            $chuyenQuery->where('GioDen', '<', now());
                        });
                  });
        })
        ->whereNotIn('MaVe', $veDaDanhGiaIds)
        ->orderByDesc('NgayDat')
        ->first();
        
        // Nếu không có vé chưa đánh giá, lấy vé mới nhất đã hoàn thành (cho phép đánh giá lại)
        $veHoanThanh = $veChuaDanhGia ?? VeXe::whereHas('chuyenXe', fn($q) => 
            $q->where('MaNhaXe', $validated['MaNhaXe'])
        )
        ->where('MaNguoiDung', $userId)
        ->where(function($query) {
            // Trường hợp 1: Vé đã được xác nhận lên xe (Đã sử dụng)
            $query->where('TrangThai', 'Đã sử dụng')
                  // Trường hợp 2: Vé đã thanh toán và giờ đến đã qua
                  ->orWhere(function($q) {
                      $q->whereIn('TrangThai', ['DaDat', 'Đã thanh toán', 'da_dat'])
                        ->whereHas('chuyenXe', function($chuyenQuery) {
                            $chuyenQuery->where('GioDen', '<', now());
                        });
                  });
        })
        ->orderByDesc('NgayDat')
        ->first();
        
        if (!$veHoanThanh) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn chỉ có thể đánh giá sau khi đã hoàn thành chuyến xe của nhà xe này!'
                ], 400);
            }
            return redirect()->back()->with('error', 'Bạn chỉ có thể đánh giá sau khi đã hoàn thành chuyến xe của nhà xe này!');
        }

        // Cho phép đánh giá nhiều lần, mỗi lần đánh giá phải gắn với một vé đã hoàn thành
        // Lưu MaVeXe để biết đánh giá này gắn với vé nào
        DanhGia::create([
            'MaNguoiDung' => $userId,
            'MaNhaXe' => $validated['MaNhaXe'],
            'MaVeXe' => $veHoanThanh->MaVe, // Lưu vé liên quan
            'SoSao' => $validated['SoSao'],
            'NoiDung' => $validated['NoiDung'],
            'DaMuaQua' => true,
            'HienThi' => true,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cảm ơn bạn đã đánh giá!'
            ]);
        }

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
