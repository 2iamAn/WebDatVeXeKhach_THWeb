<?php

namespace App\Http\Controllers;

use App\Models\VeXe;
use App\Models\ChuyenXe;
use App\Models\NguoiDung;
use App\Models\Ghe;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class VeXeController extends Controller
{
    public function index(Request $request): View
    {
        if (session('role') === 'admin') {
            $ves = VeXe::with(['chuyenXe.tuyenDuong', 'nguoiDung', 'thanhToan', 'ghe'])
                ->orderByDesc('MaVe')
                ->paginate(20);
            return view('vexe.index', compact('ves'));
        }
        
        return view('vexe.lookup');
    }
    
    public function bookingRecovery(Request $request): View
    {
        if (session('user') && session('role') === 'user') {
            $user = session('user');
            $ves = VeXe::with(['chuyenXe.tuyenDuong', 'chuyenXe.nhaXe', 'nguoiDung', 'thanhToan', 'ghe'])
                ->where('MaNguoiDung', $user->MaNguoiDung)
                ->orderByDesc('MaVe')
                ->get();
            return view('vexe.index', compact('ves'));
        }
        
        return view('vexe.index');
    }

    public function create(): View
    {
        $chuyens = ChuyenXe::orderByDesc('MaChuyenXe')
            ->get(['MaChuyenXe', 'GioKhoiHanh', 'GiaVe']);
        $users = NguoiDung::orderBy('HoTen')
            ->get(['MaNguoiDung', 'HoTen']);
        $gheTrongs = Ghe::with('chuyenXe')
            ->where('TrangThai', 'Trống')
            ->orderBy('MaChuyenXe')
            ->orderBy('SoGhe')
            ->get();
        return view('vexe.create', compact('chuyens', 'users', 'gheTrongs'));
    }

    public function store(Request $r): RedirectResponse
    {
        $validated = $r->validate([
            'MaChuyenXe' => ['required', 'integer', 'exists:chuyenxe,MaChuyenXe'],
            'MaNguoiDung' => ['required', 'integer', 'exists:nguoidung,MaNguoiDung'],
            'MaGhe' => [
                'required', 'integer',
                Rule::exists('Ghe', 'MaGhe')->where(fn($q) => $q->where('MaChuyenXe', $r->MaChuyenXe)),
                Rule::unique('VeXe', 'MaGhe'),
            ],
            'TrangThai' => ['nullable', 'string', 'max:50'],
            'NgayDat' => ['nullable', 'date'],
            'GiaTaiThoiDiemDat' => ['nullable', 'numeric', 'min:0'],
        ]);

        $chuyen = ChuyenXe::findOrFail($validated['MaChuyenXe']);
        
        $data = [
            'MaChuyenXe' => $validated['MaChuyenXe'],
            'MaNguoiDung' => $validated['MaNguoiDung'],
            'MaGhe' => $validated['MaGhe'],
            'TrangThai' => $validated['TrangThai'] ?? 'Chưa thanh toán',
            'NgayDat' => $validated['NgayDat'] ?? now(),
            'GiaTaiThoiDiemDat' => $validated['GiaTaiThoiDiemDat'] ?? $chuyen->GiaVe,
        ];

        DB::transaction(function () use ($data) {
            VeXe::create($data);
            Ghe::whereKey($data['MaGhe'])->update(['TrangThai' => 'Đã đặt']);
        });

        return redirect()->route('vexe.index')->with('success', 'Thêm vé thành công!');
    }

    public function edit(int $id): View
    {
        $ve = VeXe::findOrFail($id);
        $chuyens = ChuyenXe::orderByDesc('MaChuyenXe')
            ->get(['MaChuyenXe', 'GioKhoiHanh', 'GiaVe']);
        $users = NguoiDung::orderBy('HoTen')
            ->get(['MaNguoiDung', 'HoTen']);
        $gheOptions = Ghe::where('MaChuyenXe', $ve->MaChuyenXe)
            ->orderBy('SoGhe')
            ->get();
        return view('vexe.edit', compact('ve', 'chuyens', 'users', 'gheOptions'));
    }

    public function update(Request $r, int $id): RedirectResponse
    {
        $ve = VeXe::findOrFail($id);
        $validated = $r->validate([
            'MaChuyenXe' => ['required', 'integer', 'exists:chuyenxe,MaChuyenXe'],
            'MaNguoiDung' => ['required', 'integer', 'exists:nguoidung,MaNguoiDung'],
            'MaGhe' => [
                'required', 'integer',
                Rule::exists('Ghe', 'MaGhe')->where(fn($q) => $q->where('MaChuyenXe', $r->MaChuyenXe)),
                Rule::unique('VeXe', 'MaGhe')->ignore($id, 'MaVe'),
            ],
            'TrangThai' => ['nullable', 'string', 'max:50'],
            'NgayDat' => ['nullable', 'date'],
            'GiaTaiThoiDiemDat' => ['nullable', 'numeric', 'min:0'],
        ]);

        $chuyen = ChuyenXe::findOrFail($validated['MaChuyenXe']);
        $data = [
            'MaChuyenXe' => $validated['MaChuyenXe'],
            'MaNguoiDung' => $validated['MaNguoiDung'],
            'MaGhe' => $validated['MaGhe'],
            'TrangThai' => $validated['TrangThai'] ?? $ve->TrangThai,
            'NgayDat' => $validated['NgayDat'] ?? $ve->NgayDat,
            'GiaTaiThoiDiemDat' => $validated['GiaTaiThoiDiemDat'] ?? $chuyen->GiaVe ?? $ve->GiaTaiThoiDiemDat,
        ];

        DB::transaction(function () use ($ve, $data) {
            if ($ve->MaGhe !== $data['MaGhe']) {
                Ghe::whereKey($ve->MaGhe)->update(['TrangThai' => 'Trống']);
            }
            $ve->update($data);
            Ghe::whereKey($data['MaGhe'])->update(['TrangThai' => 'Đã đặt']);
        });

        return redirect()->route('vexe.index')->with('success', 'Cập nhật vé thành công!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $ve = VeXe::findOrFail($id);
        DB::transaction(function () use ($ve) {
            $ve->delete();
            Ghe::whereKey($ve->MaGhe)->update(['TrangThai' => 'Trống']);
        });
        return redirect()->route('vexe.index')->with('success', 'Xóa vé thành công!');
    }

    public function show(int $id): View
    {
        $ve = VeXe::with(['chuyenXe.tuyenDuong', 'nguoiDung', 'thanhToan', 'ghe'])->findOrFail($id);
        return view('vexe.show', compact('ve'));
    }

    public function cancel(int $id): RedirectResponse
    {
        $ve = VeXe::with('ghe')->findOrFail($id);
        
        if (session('role') === 'user' && session('user')) {
            $user = session('user');
            if ($ve->MaNguoiDung != $user->MaNguoiDung) {
                return redirect()->route('vexe.booking')
                    ->with('error', 'Bạn không có quyền hủy vé này!');
            }
        }
        
        $trangThaiLower = strtolower($ve->TrangThai ?? '');
        if (in_array($trangThaiLower, ['hủy', 'huy', 'hoàn tiền', 'hoan tien'])) {
            return redirect()->route('vexe.booking')
                ->with('error', 'Vé này đã được hủy rồi!');
        }
        
        DB::transaction(function () use ($ve) {
            $ve->update(['TrangThai' => 'Hủy']);
            if ($ve->ghe) {
                $ve->ghe->update(['TrangThai' => 'Trống']);
            }
        });
        
        return redirect()->route('vexe.booking')
            ->with('success', 'Đã hủy vé #' . $ve->MaVe . ' thành công!');
    }
}
