<?php

namespace App\Http\Controllers;

use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class NguoiDungController extends Controller
{
    public function index(): View
    {
        $nguoidungs = NguoiDung::orderByDesc('MaNguoiDung')->get();
        return view('nguoidung.index', compact('nguoidungs'));
    }

    public function create(): View
    {
        return view('nguoidung.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'HoTen' => ['required', 'string', 'max:100'],
            'TenDangNhap' => ['required', 'string', 'max:50', 'unique:NguoiDung,TenDangNhap'],
            'MatKhau' => ['required', 'string', 'min:4', 'max:255'],
            'LoaiNguoiDung' => ['required', 'integer', 'in:1,2,3'],
            'SDT' => ['nullable', 'string', 'max:15'],
            'Email' => ['nullable', 'email', 'max:100'],
            'TrangThai' => ['nullable', 'integer', Rule::in([0, 1])],
        ]);

        // Hash mật khẩu trước khi lưu
        $validated['TrangThai'] = $validated['TrangThai'] ?? 1;
        $validated['MatKhau'] = Hash::make($validated['MatKhau']);

        NguoiDung::create($validated);
        return redirect()->route('nguoidung.index')->with('success', 'Thêm người dùng thành công!');
    }

    public function edit(int $id): View
    {
        $nguoidung = NguoiDung::findOrFail($id);
        return view('nguoidung.edit', compact('nguoidung'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $nguoidung = NguoiDung::findOrFail($id);

        $validated = $request->validate([
            'HoTen' => ['required', 'string', 'max:100'],
            'TenDangNhap' => [
                'required', 'string', 'max:50',
                Rule::unique('NguoiDung', 'TenDangNhap')->ignore($nguoidung->MaNguoiDung, 'MaNguoiDung'),
            ],
            'LoaiNguoiDung' => ['required', 'integer', 'in:1,2,3'],
            'SDT' => ['nullable', 'string', 'max:15'],
            'Email' => ['nullable', 'email', 'max:100'],
            'TrangThai' => ['nullable', 'integer', Rule::in([0, 1])],
        ]);

        $validated['TrangThai'] = $validated['TrangThai'] ?? $nguoidung->TrangThai;

        if ($request->filled('MatKhau')) {
            $validated['MatKhau'] = Hash::make($request->MatKhau);
        }

        $nguoidung->update($validated);
        return redirect()->route('nguoidung.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy(int $id): RedirectResponse
    {
        NguoiDung::findOrFail($id)->delete();
        return redirect()->route('nguoidung.index')->with('success', 'Xóa thành công!');
    }
}
