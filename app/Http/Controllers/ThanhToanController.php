<?php

namespace App\Http\Controllers;

use App\Models\ThanhToan;
use App\Models\VeXe;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;

class ThanhToanController extends Controller
{
    public function index(): View
    {
        $pays = ThanhToan::with(['veXe.chuyenXe', 'veXe.nguoiDung'])
            ->orderByDesc('MaThanhToan')
            ->paginate(20);
        return view('thanhtoan.index', compact('pays'));
    }

    public function create(): View
    {
        $ves = VeXe::with(['ghe', 'chuyenXe'])
            ->orderByDesc('MaVe')
            ->get(['MaVe', 'MaGhe', 'MaChuyenXe', 'MaNguoiDung']);
        return view('thanhtoan.create', compact('ves'));
    }

    public function store(Request $r): RedirectResponse
    {
        $validated = $r->validate([
            'MaVe' => ['required', 'integer', 'exists:vexe,MaVe', 'unique:thanhtoan,MaVe'],
            'SoTien' => ['required', 'numeric', 'min:0'],
            'PhuongThuc' => ['required', 'string', 'max:30'],
            'TrangThai' => ['required', 'string', 'max:30'],
            'NgayThanhToan' => ['nullable', 'date'],
        ]);

        ThanhToan::create($validated);
        return redirect()->route('thanhtoan.index')->with('success', 'Ghi nhận thanh toán thành công!');
    }

    public function edit(int $id): View
    {
        $pay = ThanhToan::findOrFail($id);
        $ves = VeXe::with('ghe')
            ->orderByDesc('MaVe')
            ->get(['MaVe', 'MaGhe']);
        return view('thanhtoan.edit', compact('pay', 'ves'));
    }

    public function update(Request $r, int $id): RedirectResponse
    {
        $pay = ThanhToan::findOrFail($id);
        $validated = $r->validate([
            'MaVe' => ['required', 'integer', 'exists:vexe,MaVe', Rule::unique('thanhtoan', 'MaVe')->ignore($id, 'MaThanhToan')],
            'SoTien' => ['required', 'numeric', 'min:0'],
            'PhuongThuc' => ['required', 'string', 'max:30'],
            'TrangThai' => ['required', 'string', 'max:30'],
            'NgayThanhToan' => ['nullable', 'date'],
        ]);

        $pay->update($validated);
        return redirect()->route('thanhtoan.index')->with('success', 'Cập nhật thanh toán thành công!');
    }

    public function destroy(int $id): RedirectResponse
    {
        ThanhToan::findOrFail($id)->delete();
        return redirect()->route('thanhtoan.index')->with('success', 'Xóa thanh toán thành công!');
    }

    public function show(int $id): View
    {
        $pay = ThanhToan::with(['veXe.chuyenXe', 'veXe.nguoiDung'])->findOrFail($id);
        return view('thanhtoan.show', compact('pay'));
    }
}
