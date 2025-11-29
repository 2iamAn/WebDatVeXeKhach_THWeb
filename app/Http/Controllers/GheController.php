<?php

namespace App\Http\Controllers;

use App\Models\Ghe;
use App\Models\ChuyenXe;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;

class GheController extends Controller
{
    public function index(): View
    {
        $ghes = Ghe::with(['chuyenXe.tuyenDuong', 'chuyenXe.nhaXe'])
            ->orderByDesc('MaGhe')
            ->paginate(30);
        return view('ghe.index', compact('ghes'));
    }

    public function create(): View
    {
        $chuyens = ChuyenXe::orderByDesc('MaChuyenXe')
            ->get(['MaChuyenXe', 'GioKhoiHanh', 'GiaVe']);
        return view('ghe.create', compact('chuyens'));
    }

    public function store(Request $r): RedirectResponse
    {
        $validated = $r->validate([
            'MaChuyenXe' => ['required', 'integer', 'exists:chuyenxe,MaChuyenXe'],
            'SoGhe' => [
                'required', 'string', 'max:10',
                Rule::unique('Ghe')->where(fn($q) =>
                    $q->where('MaChuyenXe', $r->MaChuyenXe)->where('SoGhe', $r->SoGhe)
                ),
            ],
            'TrangThai' => ['nullable', 'in:Trống,Giữ chỗ,Đã đặt'],
        ]);

        Ghe::create([
            'MaChuyenXe' => $validated['MaChuyenXe'],
            'SoGhe' => $validated['SoGhe'],
            'TrangThai' => $validated['TrangThai'] ?? 'Trống',
        ]);

        return redirect()->route('ghe.index')->with('success', 'Thêm ghế thành công!');
    }

    public function edit(int $id): View
    {
        $ghe = Ghe::findOrFail($id);
        $chuyens = ChuyenXe::orderByDesc('MaChuyenXe')
            ->get(['MaChuyenXe', 'GioKhoiHanh']);
        return view('ghe.edit', compact('ghe', 'chuyens'));
    }

    public function update(Request $r, int $id): RedirectResponse
    {
        $ghe = Ghe::findOrFail($id);
        $validated = $r->validate([
            'MaChuyenXe' => ['required', 'integer', 'exists:chuyenxe,MaChuyenXe'],
            'SoGhe' => [
                'required', 'string', 'max:10',
                Rule::unique('Ghe')->ignore($id, 'MaGhe')
                    ->where(fn($q) => $q->where('MaChuyenXe', $r->MaChuyenXe)->where('SoGhe', $r->SoGhe)),
            ],
            'TrangThai' => ['nullable', 'in:Trống,Giữ chỗ,Đã đặt'],
        ]);

        $ghe->update($validated);
        return redirect()->route('ghe.index')->with('success', 'Cập nhật ghế thành công!');
    }

    public function destroy(int $id): RedirectResponse
    {
        Ghe::findOrFail($id)->delete();
        return redirect()->route('ghe.index')->with('success', 'Xóa ghế thành công!');
    }
}
