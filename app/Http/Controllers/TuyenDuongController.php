<?php

namespace App\Http\Controllers;

use App\Models\TuyenDuong;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Carbon\Carbon;

class TuyenDuongController extends Controller
{
    public function index(Request $request): View
    {
        $query = TuyenDuong::with(['chuyenXe' => fn($q) => $q->with('nhaXe')->orderBy('GiaVe', 'asc')]);
        
        if ($request->filled('diem_di')) {
            $query->where('DiemDi', 'like', "%{$request->diem_di}%");
        }
        
        if ($request->filled('diem_den')) {
            $query->where('DiemDen', 'like', "%{$request->diem_den}%");
        }
        
        $tuyens = $query->get();
        
        $tuyensUnique = $tuyens->groupBy(fn($tuyen) => "{$tuyen->DiemDi}|{$tuyen->DiemDen}")
            ->map(function($group) {
                $tuyen = $group->first();
                $allChuyenXes = $group->flatMap->chuyenXe;
                $tuyen->chuyenXe = $allChuyenXes;
                $tuyen->giaVeThapNhat = $allChuyenXes->where('GiaVe', '>', 0)->min('GiaVe');
                $tuyen->danhSachNhaXe = $allChuyenXes->pluck('nhaXe.TenNhaXe')->filter()->unique();
                
                // Dùng ThoiGianHanhTrinh từ database nếu có, nếu không thì tính từ chuyến xe đầu tiên
                if ($tuyen->ThoiGianHanhTrinh) {
                    $tuyen->thoiGianHanhTrinh = $tuyen->ThoiGianHanhTrinh;
                } else {
                    $chuyenDauTien = $allChuyenXes->first();
                    if ($chuyenDauTien?->GioKhoiHanh && $chuyenDauTien->GioDen) {
                        try {
                            $start = Carbon::parse($chuyenDauTien->GioKhoiHanh);
                            $end = Carbon::parse($chuyenDauTien->GioDen);
                            $diff = $start->diff($end);
                            $thoiGian = $diff->h . ' giờ';
                            if ($diff->i > 0) $thoiGian .= ' ' . $diff->i . ' phút';
                            if ($diff->days > 0) $thoiGian = $diff->days . ' ngày ' . $thoiGian;
                            $tuyen->thoiGianHanhTrinh = $thoiGian;
                        } catch (\Exception $e) {
                            $tuyen->thoiGianHanhTrinh = null;
                        }
                    }
                }
                return $tuyen;
            })->values();
        
        return view('tuyenduong.index', compact('tuyensUnique'));
    }

    public function create(): View
    {
        return view('tuyenduong.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'DiemDi' => ['required', 'string', 'max:100'],
            'DiemDen' => ['required', 'string', 'max:100'],
            'KhoangCach' => ['required', 'integer', 'min:1'],
        ]);

        TuyenDuong::create($validated);
        return redirect()->route('tuyenduong.index')->with('success', 'Thêm tuyến thành công');
    }

    public function show(int $id): View
    {
        $tuyen = TuyenDuong::with('chuyenXe.nhaXe')->findOrFail($id);
        return view('tuyenduong.show', compact('tuyen'));
    }

    public function edit(int $id): View
    {
        $tuyen = TuyenDuong::findOrFail($id);
        return view('tuyenduong.edit', compact('tuyen'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'DiemDi' => ['required', 'string', 'max:100'],
            'DiemDen' => ['required', 'string', 'max:100'],
            'KhoangCach' => ['required', 'integer', 'min:1'],
        ]);

        TuyenDuong::findOrFail($id)->update($validated);
        return redirect()->route('tuyenduong.index')->with('success', 'Cập nhật tuyến thành công');
    }

    public function destroy(int $id): RedirectResponse
    {
        TuyenDuong::findOrFail($id)->delete();
        return redirect()->route('tuyenduong.index')->with('success', 'Xóa tuyến đường thành công!');
    }
}
