<?php

namespace App\Http\Controllers;

use App\Models\BaoCao;
use App\Services\BaoCaoService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;

class BaoCaoController extends Controller
{
    public function index(Request $req): View
    {
        $query = BaoCao::with('nhaXe');

        if ($req->filled('MaNhaXe')) {
            $query->where('MaNhaXe', (int)$req->MaNhaXe);
        }
        if ($req->filled('from') && $req->filled('to')) {
            $query->whereBetween('ThoiGianBaoCao', [$req->from, $req->to]);
        }
        if ($req->filled('date')) {
            $query->whereDate('ThoiGianBaoCao', $req->date);
        }

        $baocao = $query->orderByDesc('ThoiGianBaoCao')->paginate(20);
        return view('baocao.index', compact('baocao'));
    }

    public function ketSoNgay(Request $req, BaoCaoService $svc): RedirectResponse
    {
        $validated = $req->validate([
            'MaNhaXe' => 'required|integer|exists:nhaxe,MaNhaXe',
            'date' => 'required|date_format:Y-m-d',
            'GhiChu' => 'nullable|string|max:255',
        ]);

        $svc->ketSoNgay((int)$validated['MaNhaXe'], $validated['date'], $validated['GhiChu'] ?? null);
        return redirect()->route('baocao.index')->with('success', 'Đã kết sổ ngày ' . $validated['date']);
    }

    public function ketSoThang(Request $req, BaoCaoService $svc): RedirectResponse
    {
        $validated = $req->validate([
            'MaNhaXe' => 'required|integer|exists:nhaxe,MaNhaXe',
            'year' => 'required|integer|min:2000',
            'month' => 'required|integer|min:1|max:12',
            'GhiChu' => 'nullable|string|max:255',
        ]);

        $svc->ketSoThang(
            (int)$validated['MaNhaXe'],
            (int)$validated['year'],
            (int)$validated['month'],
            $validated['GhiChu'] ?? null
        );
        return redirect()->route('baocao.index')->with('success', 'Đã kết sổ tháng ' . $validated['month'] . '/' . $validated['year']);
    }
}
