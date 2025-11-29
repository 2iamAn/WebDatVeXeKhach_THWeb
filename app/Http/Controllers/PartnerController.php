<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChuyenXe;
use App\Models\Ghe;
use App\Models\VeXe;
use App\Models\NguoiDung;
use App\Models\TuyenDuong;
use App\Models\Xe;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PartnerController extends Controller
{
    public function showRegisterForm()
    {
        // Kiểm tra nếu có email trong request, hiển thị lý do từ chối nếu có
        $lyDoTuChoi = null;
        if (request()->has('email')) {
            $email = request()->get('email');
            $nguoiDung = DB::table('nguoidung')->where('Email', $email)->first();
            if ($nguoiDung && $nguoiDung->TrangThai == 0) {
                $nhaXe = DB::table('nhaxe')->where('MaNguoiDung', $nguoiDung->MaNguoiDung)->first();
                if ($nhaXe && $nhaXe->LyDoTuChoi) {
                    $lyDoTuChoi = $nhaXe->LyDoTuChoi;
                }
            }
        }
        return view('partner.request', compact('lyDoTuChoi'));
    }

    public function sendRequest(Request $request)
    {
        $request->validate([
            'TenNhaXe' => 'required|string|max:150',
            'NguoiDaiDien' => 'required|string|max:100',
            'Email' => 'required|email|max:100|unique:NguoiDung,Email',
            'MatKhau' => 'required|string|min:6|confirmed',
            'SDT' => 'required|string|max:15',
            'DiaChi' => 'required|string|max:255',
            'MoTa' => 'nullable|string|max:255',
        ], [
            'Email.unique' => 'Email này đã được sử dụng. Vui lòng chọn email khác.',
            'MatKhau.required' => 'Vui lòng nhập mật khẩu.',
            'MatKhau.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'MatKhau.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        // Kiểm tra email đã tồn tại chưa
        if (DB::table('nguoidung')->where('Email', $request->Email)->exists()) {
            return redirect()->back()->withErrors(['Email' => 'Email này đã được sử dụng. Vui lòng chọn email khác.'])->withInput();
        }

        // Tạo tên đăng nhập từ email (phần trước @)
        $emailParts = explode('@', $request->Email);
        $base = Str::slug($emailParts[0]);
        if (!$base) {
            $base = 'nhaxe';
        }
        $username = $base;
        $count = 1;
        while (DB::table('nguoidung')->where('TenDangNhap', $username)->exists()) {
            $username = $base . $count;
            $count++;
        }

        // Lưu mật khẩu do nhà xe tự tạo (plain text)
        $maNguoiDung = DB::table('nguoidung')->insertGetId([
            'HoTen' => $request->NguoiDaiDien,
            'TenDangNhap' => $username,
            'MatKhau' => $request->MatKhau,
            'LoaiNguoiDung' => NguoiDung::ROLE_NHA_XE,
            'SDT' => $request->SDT,
            'Email' => $request->Email,
            'TrangThai' => 0,
        ]);

        DB::table('nhaxe')->insert([
            'MaNguoiDung' => $maNguoiDung,
            'TenNhaXe' => $request->TenNhaXe,
            'MoTa' => $request->MoTa ?: 'Đăng ký hợp tác',
        ]);

        return redirect()->back()->with('success', 'Đã gửi yêu cầu hợp tác thành công! Sau khi được admin phê duyệt, bạn có thể đăng nhập bằng email và mật khẩu đã đăng ký.');
    }

    // Dashboard cho nhà xe
    public function dashboard()
    {
        $maNhaXe = $this->ensurePartner();

        // Tính toán thống kê
        $tongChuyen = DB::table('chuyenxe')
            ->where('MaNhaXe', $maNhaXe)
            ->count();

        $veDaBan = DB::table('vexe')
            ->join('chuyenxe', 'vexe.MaChuyenXe', '=', 'chuyenxe.MaChuyenXe')
            ->where('chuyenxe.MaNhaXe', $maNhaXe)
            ->count();

        $doanhThuNgay = DB::table('thanhtoan')
            ->join('vexe', 'thanhtoan.MaVe', '=', 'vexe.MaVe')
            ->join('chuyenxe', 'vexe.MaChuyenXe', '=', 'chuyenxe.MaChuyenXe')
            ->where('chuyenxe.MaNhaXe', $maNhaXe)
            ->whereDate('thanhtoan.NgayThanhToan', now()->toDateString())
            ->sum('thanhtoan.SoTien') ?? 0;

        $doanhThuThang = DB::table('thanhtoan')
            ->join('vexe', 'thanhtoan.MaVe', '=', 'vexe.MaVe')
            ->join('chuyenxe', 'vexe.MaChuyenXe', '=', 'chuyenxe.MaChuyenXe')
            ->where('chuyenxe.MaNhaXe', $maNhaXe)
            ->whereYear('thanhtoan.NgayThanhToan', now()->year)
            ->whereMonth('thanhtoan.NgayThanhToan', now()->month)
            ->sum('thanhtoan.SoTien') ?? 0;

        return view('partner.dashboard', [
            'tongChuyen' => $tongChuyen,
            'veDaBan' => $veDaBan,
            'doanhThuNgay' => $doanhThuNgay,
            'doanhThuThang' => $doanhThuThang,
        ]);
    }
    
private function ensurePartner()
{
    if (session('role') !== 'partner' || !session('user')) {
        abort(403, 'Bạn không có quyền truy cập');
    }

    $user = DB::table('nguoidung')->where('MaNguoiDung', session('user')->MaNguoiDung)->first();
    $nhaxe = DB::table('nhaxe')->where('MaNguoiDung', $user->MaNguoiDung)->first();

    if (!$nhaxe || (int)$user->TrangThai === 0) {
        abort(403, 'Tài khoản nhà xe chưa được kích hoạt');
    }

    return $nhaxe->MaNhaXe;
}

public function trips(Request $request)
{
    $maNhaXe = $this->ensurePartner();

    $query = ChuyenXe::with(['tuyenDuong', 'ghe', 'veXe', 'xe'])
        ->where('MaNhaXe', $maNhaXe);

    // Lọc theo tháng nếu có
    if ($request->has('month') && $request->month) {
        $month = $request->month; // Format: YYYY-MM
        $query->whereYear('GioKhoiHanh', substr($month, 0, 4))
               ->whereMonth('GioKhoiHanh', substr($month, 5, 2));
    } else {
        // Mặc định hiển thị tháng hiện tại
        $query->whereYear('GioKhoiHanh', now()->year)
               ->whereMonth('GioKhoiHanh', now()->month);
    }

    // Lọc theo ngày nếu có
    if ($request->has('date') && $request->date) {
        $query->whereDate('GioKhoiHanh', $request->date);
    }

    $trips = $query->orderBy('GioKhoiHanh', 'asc')
        ->orderByDesc('MaChuyenXe')
        ->get()
        ->map(function ($trip) {
            $trip->DiemDi = $trip->tuyenDuong?->DiemDi;
            $trip->DiemDen = $trip->tuyenDuong?->DiemDen;
            
            // Tính số ghế trống và đã đặt
            $tongGhe = $trip->ghe->count();
            
            // Chỉ đếm các vé hợp lệ (không phải "Hủy" hoặc "Hoàn tiền")
            $gheDaDat = $trip->veXe->filter(function($ve) {
                $trangThai = strtolower($ve->TrangThai ?? '');
                return !in_array($trangThai, ['hủy', 'huy', 'hoàn tiền', 'hoan tien']);
            })->count();
            
            // Nếu chưa có ghế nào trong bảng Ghe, lấy từ thông tin xe
            if ($tongGhe == 0 && $trip->xe && $trip->xe->SoGhe) {
                $tongGhe = $trip->xe->SoGhe;
            }
            
            $trip->so_ghe_trong = max(0, $tongGhe - $gheDaDat);
            $trip->tong_ghe = $tongGhe;
            $trip->so_ve_da_dat = $gheDaDat;
            
            // Lấy lý do từ chối nếu có
            $trip->LyDoTuChoi = $trip->LyDoTuChoi;
            
            return $trip;
        });

    $selectedMonth = $request->month ?? now()->format('Y-m');
    $selectedDate = $request->date ?? null;

    // Lấy danh sách các ngày có chuyến trong tháng
    $daysWithTrips = ChuyenXe::where('MaNhaXe', $maNhaXe)
        ->whereYear('GioKhoiHanh', substr($selectedMonth, 0, 4))
        ->whereMonth('GioKhoiHanh', substr($selectedMonth, 5, 2))
        ->selectRaw('DATE(GioKhoiHanh) as date, COUNT(*) as count')
        ->groupBy('date')
        ->pluck('count', 'date')
        ->toArray();

    return view('partner.trips.index', compact('trips', 'selectedMonth', 'selectedDate', 'daysWithTrips'));
}

public function seats(Request $request)
{
    $maNhaXe = $this->ensurePartner();

    $selectedDate = $request->date ?? now()->toDateString();
    $selectedMonth = $request->month ?? now()->format('Y-m');

    // Lấy các chuyến xe trong ngày được chọn
    $chuyens = ChuyenXe::with(['xe', 'ghe', 'veXe'])
        ->where('MaNhaXe', $maNhaXe)
        ->whereDate('GioKhoiHanh', $selectedDate)
        ->get();

    // Lấy chuyến xe được chọn (nếu có) - cần lấy sớm để lọc dữ liệu
    $selectedChuyenId = $request->chuyen_id;
    $selectedChuyen = null;
    if ($selectedChuyenId) {
        $selectedChuyen = ChuyenXe::with(['tuyenDuong', 'nhaXe', 'xe'])
            ->where('MaNhaXe', $maNhaXe)
            ->where('MaChuyenXe', $selectedChuyenId)
            ->first();
    } elseif ($chuyens->count() > 0) {
        // Nếu không chọn chuyến cụ thể, lấy chuyến đầu tiên
        $selectedChuyen = $chuyens->first();
        $selectedChuyen->load(['tuyenDuong', 'nhaXe', 'xe']);
    }
    
    // Lấy danh sách ghế đã được đặt (từ bảng VeXe) CHỈ CHO CHUYẾN ĐƯỢC CHỌN
    $maChuyenXeSelected = $selectedChuyen ? $selectedChuyen->MaChuyenXe : null;
    
    if ($maChuyenXeSelected) {
        // Lấy thông tin chi tiết vé và người đặt cho mỗi ghế CỦA CHUYẾN ĐƯỢC CHỌN
        $veXeInfo = VeXe::with(['nguoiDung', 'ghe', 'chuyenXe', 'thanhToan'])
            ->where('MaChuyenXe', $maChuyenXeSelected)
            ->whereNotIn('TrangThai', ['Hủy', 'Huy', 'Hoàn tiền', 'Hoan tien'])
            ->get();
        
        $gheDaDat = $veXeInfo->pluck('MaGhe')->toArray();
        
        // Tạo map veXeInfo với key là MaGhe
        $veXeInfoMap = $veXeInfo->mapWithKeys(function($ve) {
            return [$ve->MaGhe => [
                'maVe' => $ve->MaVe,
                'hoTen' => $ve->nguoiDung->HoTen ?? '---',
                'sdt' => $ve->nguoiDung->SDT ?? '---',
                'email' => $ve->nguoiDung->Email ?? '---',
                'ngayDat' => $ve->NgayDat ? \Carbon\Carbon::parse($ve->NgayDat)->format('d/m/Y H:i') : '---',
                'trangThai' => $ve->TrangThai ?? '---',
                'soGhe' => $ve->ghe->SoGhe ?? '---',
                'phuongThuc' => $ve->thanhToan->PhuongThuc ?? 'Chưa thanh toán',
            ]];
        })->toArray();
    } else {
        // Nếu không có chuyến được chọn, khởi tạo rỗng
        $veXeInfo = collect();
        $gheDaDat = [];
        $veXeInfoMap = [];
    }
    
    // Lấy tất cả ghế CHỈ CỦA CHUYẾN ĐƯỢC CHỌN
    if ($maChuyenXeSelected) {
        $query = Ghe::with('chuyenXe.tuyenDuong')
            ->where('MaChuyenXe', $maChuyenXeSelected);

        $ghe = $query->orderBy('SoGhe')->get();
        
        // Nếu có vé đã đặt nhưng ghế chưa có trong bảng Ghe, thêm vào danh sách
        $gheTuVe = $veXeInfo->map(function($ve) {
            if ($ve->ghe) {
                return $ve->ghe;
            }
            return null;
        })->filter()->unique('MaGhe');
        
        // Lấy danh sách MaGhe đã có
        $maGheDaCo = $ghe->pluck('MaGhe')->toArray();
        
        // Thêm các ghế từ vé vào danh sách nếu chưa có
        foreach ($gheTuVe as $gheVe) {
            if (!in_array($gheVe->MaGhe, $maGheDaCo)) {
                $ghe->push($gheVe);
            }
        }
        
        // Sắp xếp lại
        $ghe = $ghe->sortBy(function($g) {
            return $g->SoGhe;
        })->values();
    } else {
        $ghe = collect();
    }

    // Tính số ghế theo từng loại xe (34 và 41 chỗ) riêng biệt
    // Lấy chuyến xe có xe được gán và có biển số của mỗi loại để hiển thị số liệu
    $tongGhe34 = 0;
    $soGheDaDat34 = 0;
    $tongGhe41 = 0;
    $soGheDaDat41 = 0;
    $chuyen34 = null;
    $chuyen41 = null;
    $chuyen34Backup = null; // Dự phòng nếu không có chuyến có biển số
    $chuyen41Backup = null;
    
    // Xác định số ghế của chuyến được chọn (nếu có)
    $selectedChuyenSoGhe = null;
    if ($selectedChuyen) {
        $selectedChuyen->load(['xe']);
        $selectedChuyenSoGhe = $selectedChuyen->ghe->count();
        if ($selectedChuyenSoGhe == 0 && $selectedChuyen->xe && $selectedChuyen->xe->SoGhe) {
            $selectedChuyenSoGhe = $selectedChuyen->xe->SoGhe;
        }
    }
    
    foreach ($chuyens as $chuyen) {
        $chuyen->load(['xe', 'tuyenDuong', 'nhaXe']);
        $soGheChuyen = $chuyen->ghe->count();
        
        // Nếu chưa có ghế trong bảng Ghe, lấy từ thông tin xe
        if ($soGheChuyen == 0 && $chuyen->xe && $chuyen->xe->SoGhe) {
            $soGheChuyen = $chuyen->xe->SoGhe;
        }
        
        // Chỉ đếm các vé hợp lệ (không phải "Hủy" hoặc "Hoàn tiền")
        $veDaDat = $chuyen->veXe->filter(function($ve) {
            $trangThai = strtolower($ve->TrangThai ?? '');
            return !in_array($trangThai, ['hủy', 'huy', 'hoàn tiền', 'hoan tien']);
        })->count();
        
        // Xử lý xe 34 chỗ
        if ($soGheChuyen == 34) {
            if ($tongGhe34 == 0) {
                $tongGhe34 = $soGheChuyen;
                $soGheDaDat34 = $veDaDat;
            }
            
            // Ưu tiên 1: Chuyến được chọn nếu là 34 chỗ và có xe có biển số
            if ($selectedChuyen && $selectedChuyen->MaChuyenXe == $chuyen->MaChuyenXe && 
                $selectedChuyenSoGhe == 34 && $chuyen->xe && $chuyen->xe->BienSoXe) {
                $chuyen34 = $chuyen;
            }
            // Ưu tiên 2: Chuyến có xe được gán và có biển số
            elseif ($chuyen->xe && $chuyen->xe->BienSoXe && !$chuyen34) {
                $chuyen34 = $chuyen;
            } 
            // Ưu tiên 3: Chuyến được chọn nếu là 34 chỗ (dù không có biển số)
            elseif ($selectedChuyen && $selectedChuyen->MaChuyenXe == $chuyen->MaChuyenXe && 
                    $selectedChuyenSoGhe == 34 && !$chuyen34) {
                $chuyen34 = $chuyen;
            }
            // Dự phòng: Chuyến đầu tiên
            elseif (!$chuyen34Backup && !$chuyen34) {
                $chuyen34Backup = $chuyen;
            }
        }
        
        // Xử lý xe 41 chỗ
        if ($soGheChuyen == 41) {
            if ($tongGhe41 == 0) {
                $tongGhe41 = $soGheChuyen;
                $soGheDaDat41 = $veDaDat;
            }
            
            // Ưu tiên 1: Chuyến được chọn nếu là 41 chỗ và có xe có biển số
            if ($selectedChuyen && $selectedChuyen->MaChuyenXe == $chuyen->MaChuyenXe && 
                $selectedChuyenSoGhe == 41 && $chuyen->xe && $chuyen->xe->BienSoXe) {
                $chuyen41 = $chuyen;
            }
            // Ưu tiên 2: Chuyến có xe được gán và có biển số
            elseif ($chuyen->xe && $chuyen->xe->BienSoXe && !$chuyen41) {
                $chuyen41 = $chuyen;
            }
            // Ưu tiên 3: Chuyến được chọn nếu là 41 chỗ (dù không có biển số)
            elseif ($selectedChuyen && $selectedChuyen->MaChuyenXe == $chuyen->MaChuyenXe && 
                    $selectedChuyenSoGhe == 41 && !$chuyen41) {
                $chuyen41 = $chuyen;
            }
            // Dự phòng: Chuyến đầu tiên
            elseif (!$chuyen41Backup && !$chuyen41) {
                $chuyen41Backup = $chuyen;
            }
        }
    }
    
    // Sử dụng chuyến dự phòng nếu không có chuyến có biển số
    if (!$chuyen34 && $chuyen34Backup) {
        $chuyen34 = $chuyen34Backup;
    }
    if (!$chuyen41 && $chuyen41Backup) {
        $chuyen41 = $chuyen41Backup;
    }
    
    // Mặc định hiển thị cho xe 34 chỗ
    $tongGhe = $tongGhe34;
    $soGheDaDat = $soGheDaDat34;

    // Lấy danh sách ghế đã được đặt (từ bảng VeXe) - đã lấy ở trên
    // $gheDaDat đã được định nghĩa ở trên

    // Lấy danh sách các ngày có chuyến trong tháng
    $daysWithTrips = \App\Models\ChuyenXe::where('MaNhaXe', $maNhaXe)
        ->whereYear('GioKhoiHanh', substr($selectedMonth, 0, 4))
        ->whereMonth('GioKhoiHanh', substr($selectedMonth, 5, 2))
        ->selectRaw('DATE(GioKhoiHanh) as date, COUNT(*) as count')
        ->groupBy('date')
        ->pluck('count', 'date')
        ->toArray();


    return view('partner.seats', compact('ghe', 'selectedDate', 'selectedMonth', 'daysWithTrips', 'tongGhe', 'soGheDaDat', 'tongGhe34', 'soGheDaDat34', 'tongGhe41', 'soGheDaDat41', 'gheDaDat', 'veXeInfo', 'chuyens', 'selectedChuyen', 'veXeInfoMap', 'chuyen34', 'chuyen41'));
}

public function lockSeat(Request $request, $maGhe)
{
    $maNhaXe = $this->ensurePartner();
    
    $ghe = Ghe::with('chuyenXe')->findOrFail($maGhe);
    
    // Kiểm tra quyền
    if ($ghe->chuyenXe->MaNhaXe != $maNhaXe) {
        return response()->json(['success' => false, 'message' => 'Bạn không có quyền khóa ghế này!'], 403);
    }
    
    // Kiểm tra ghế đã được đặt chưa
    $veXe = VeXe::where('MaGhe', $maGhe)
        ->whereNotIn('TrangThai', ['Hủy', 'Huy', 'Hoàn tiền', 'Hoan tien'])
        ->first();
    
    if ($veXe) {
        return response()->json(['success' => false, 'message' => 'Không thể khóa ghế đã có khách đặt!'], 400);
    }
    
    $ghe->TrangThai = 'Giữ chỗ';
    $ghe->save();
    
    return response()->json(['success' => true, 'message' => 'Đã khóa ghế thành công!']);
}

public function unlockSeat(Request $request, $maGhe)
{
    $maNhaXe = $this->ensurePartner();
    
    $ghe = Ghe::with('chuyenXe')->findOrFail($maGhe);
    
    // Kiểm tra quyền
    if ($ghe->chuyenXe->MaNhaXe != $maNhaXe) {
        return response()->json(['success' => false, 'message' => 'Bạn không có quyền mở khóa ghế này!'], 403);
    }
    
    // Chỉ mở khóa nếu ghế đang ở trạng thái "Giữ chỗ"
    if ($ghe->TrangThai != 'Giữ chỗ') {
        return response()->json(['success' => false, 'message' => 'Ghế này không ở trạng thái khóa!'], 400);
    }
    
    $ghe->TrangThai = 'Trống';
    $ghe->save();
    
    return response()->json(['success' => true, 'message' => 'Đã mở khóa ghế thành công!']);
}

public function cancelTicketFromPartner(Request $request, $maVe)
{
    $maNhaXe = $this->ensurePartner();
    
    $ve = VeXe::with(['chuyenXe', 'ghe'])->findOrFail($maVe);
    
    // Kiểm tra quyền
    if ($ve->chuyenXe->MaNhaXe != $maNhaXe) {
        return redirect()->back()->with('error', 'Bạn không có quyền hủy vé này!');
    }
    
    // Kiểm tra vé đã được hủy chưa
    if (in_array(strtolower($ve->TrangThai ?? ''), ['hủy', 'huy', 'hoàn tiền', 'hoan tien'])) {
        return redirect()->back()->with('error', 'Vé này đã được hủy rồi!');
    }
    
    DB::transaction(function () use ($ve) {
        // Cập nhật trạng thái vé
        $ve->TrangThai = 'Hủy';
        $ve->save();
        
        // Cập nhật trạng thái ghế về "Trống"
        if ($ve->ghe) {
            $ve->ghe->TrangThai = 'Trống';
            $ve->ghe->save();
        }
    });
    
    return redirect()->back()->with('success', 'Đã hủy vé #' . $ve->MaVe . ' thành công!');
}

public function tickets()
{
    $maNhaXe = $this->ensurePartner();

    $tickets = VeXe::with(['ghe', 'chuyenXe.tuyenDuong', 'chuyenXe.nhaXe', 'nguoiDung', 'thanhToan'])
        ->whereHas('chuyenXe', fn($q) => $q->where('MaNhaXe', $maNhaXe))
        ->orderByDesc('MaVe')
        ->get();

    return view('partner.tickets', compact('tickets'));
}

public function updateTicketStatus(Request $request, $id)
{
    $maNhaXe = $this->ensurePartner();
    
    $ticket = VeXe::whereHas('chuyenXe', fn($q) => $q->where('MaNhaXe', $maNhaXe))
        ->findOrFail($id);

    $request->validate([
        'TrangThai' => 'required|string|in:Đã thanh toán,Đã đi,Hủy,Hoàn tiền,Chưa thanh toán',
    ]);

    $oldStatus = $ticket->TrangThai;
    $ticket->TrangThai = $request->TrangThai;
    $ticket->save();

    return redirect()->route('partner.tickets')->with('success', "Đã cập nhật trạng thái vé #{$ticket->MaVe} từ '{$oldStatus}' sang '{$request->TrangThai}'");
}

public function revenue()
{
    $maNhaXe = $this->ensurePartner();

    $tongVe = VeXe::whereHas('chuyenXe', fn($q) => $q->where('MaNhaXe', $maNhaXe))->count();

    $doanhThuNgay = DB::table('thanhtoan')
        ->join('vexe', 'thanhtoan.MaVe', '=', 'vexe.MaVe')
        ->join('chuyenxe', 'vexe.MaChuyenXe', '=', 'chuyenxe.MaChuyenXe')
        ->where('chuyenxe.MaNhaXe', $maNhaXe)
        ->whereDate('thanhtoan.NgayThanhToan', now())
        ->sum('thanhtoan.SoTien');

    $doanhThuThang = DB::table('thanhtoan')
        ->join('vexe', 'thanhtoan.MaVe', '=', 'vexe.MaVe')
        ->join('chuyenxe', 'vexe.MaChuyenXe', '=', 'chuyenxe.MaChuyenXe')
        ->where('chuyenxe.MaNhaXe', $maNhaXe)
        ->whereYear('thanhtoan.NgayThanhToan', now()->year)
        ->whereMonth('thanhtoan.NgayThanhToan', now()->month)
        ->sum('thanhtoan.SoTien');

    return view('partner.revenue', compact('doanhThuNgay', 'doanhThuThang', 'tongVe'));
}

// Quản lý chuyến đi - CRUD
public function createTrip()
{
    $maNhaXe = $this->ensurePartner();
    $tuyens = TuyenDuong::whereHas('chuyenXe', function($query) use ($maNhaXe) {
        $query->where('MaNhaXe', $maNhaXe);
    })->orWhereDoesntHave('chuyenXe')->orderBy('DiemDi')->orderBy('DiemDen')->get();
    $xes = Xe::where('MaNhaXe', $maNhaXe)->get();
    
    // Lấy tên nhà xe
    $user = DB::table('nguoidung')->where('MaNguoiDung', session('user')->MaNguoiDung)->first();
    $nhaxe = DB::table('nhaxe')->where('MaNguoiDung', $user->MaNguoiDung)->first();
    $tenNhaXe = $nhaxe ? $nhaxe->TenNhaXe : null;
    
    return view('partner.trips.create', compact('maNhaXe', 'tuyens', 'xes', 'tenNhaXe'));
}

public function storeTrip(Request $request)
{
    $maNhaXe = $this->ensurePartner();
    
    $request->validate([
        'MaTuyen' => 'required|integer|exists:tuyenduong,MaTuyen',
        'DiemLenXe' => 'required|string|max:255',
        'DiemXuongXe' => 'required|string|max:255',
        'MaXe' => 'nullable|integer|exists:xe,MaXe',
        'GioKhoiHanh' => 'required|date',
        'GioDen' => 'nullable|date|after:GioKhoiHanh',
        'GiaVe' => 'required|numeric|min:0',
    ]);

    // Kiểm tra tuyến đường thuộc về nhà xe này hoặc chưa được sử dụng
    $tuyen = TuyenDuong::findOrFail($request->MaTuyen);
    $tuyenUsedByOther = ChuyenXe::where('MaTuyen', $tuyen->MaTuyen)
        ->where('MaNhaXe', '!=', $maNhaXe)
        ->exists();
    
    if ($tuyenUsedByOther) {
        return redirect()->back()->with('error', 'Tuyến đường này đã được nhà xe khác sử dụng!');
    }

    // Kiểm tra xe thuộc về nhà xe này
    if ($request->MaXe) {
        $xe = Xe::where('MaXe', $request->MaXe)
            ->where('MaNhaXe', $maNhaXe)
            ->firstOrFail();
    }

    ChuyenXe::create([
        'MaNhaXe' => $maNhaXe,
        'MaTuyen' => $request->MaTuyen,
        'DiemLenXe' => $request->DiemLenXe,
        'DiemXuongXe' => $request->DiemXuongXe,
        'MaXe' => $request->MaXe,
        'GioKhoiHanh' => $request->GioKhoiHanh,
        'GioDen' => $request->GioDen,
        'GiaVe' => $request->GiaVe,
        'TrangThai' => 'ChoDuyet', // Mặc định là chờ duyệt
    ]);

    return redirect()->route('partner.trips')->with('success', 'Đã gửi yêu cầu thêm chuyến xe! Chuyến xe sẽ được hiển thị sau khi admin phê duyệt.');
}

public function editTrip($id)
{
    $maNhaXe = $this->ensurePartner();
    $chuyen = ChuyenXe::with(['tuyenDuong', 'xe'])
        ->where('MaChuyenXe', $id)
        ->where('MaNhaXe', $maNhaXe)
        ->firstOrFail();
    
    $tuyens = TuyenDuong::whereHas('chuyenXe', function($query) use ($maNhaXe) {
        $query->where('MaNhaXe', $maNhaXe);
    })->orWhereDoesntHave('chuyenXe')->orderBy('DiemDi')->orderBy('DiemDen')->get();
    $xes = Xe::where('MaNhaXe', $maNhaXe)->get();
    
    // Lấy tên nhà xe
    $user = DB::table('nguoidung')->where('MaNguoiDung', session('user')->MaNguoiDung)->first();
    $nhaxe = DB::table('nhaxe')->where('MaNguoiDung', $user->MaNguoiDung)->first();
    $tenNhaXe = $nhaxe ? $nhaxe->TenNhaXe : null;
    
    return view('partner.trips.edit', compact('chuyen', 'tuyens', 'xes', 'tenNhaXe'));
}

public function updateTrip(Request $request, $id)
{
    $maNhaXe = $this->ensurePartner();
    $chuyen = ChuyenXe::where('MaChuyenXe', $id)
        ->where('MaNhaXe', $maNhaXe)
        ->firstOrFail();

    $request->validate([
        'MaTuyen' => 'required|integer|exists:tuyenduong,MaTuyen',
        'DiemLenXe' => 'required|string|max:255',
        'DiemXuongXe' => 'required|string|max:255',
        'MaXe' => 'nullable|integer|exists:xe,MaXe',
        'GioKhoiHanh' => 'required|date',
        'GioDen' => 'nullable|date|after:GioKhoiHanh',
        'GiaVe' => 'required|numeric|min:0',
    ]);

    // Kiểm tra tuyến đường
    $tuyen = TuyenDuong::findOrFail($request->MaTuyen);
    $tuyenUsedByOther = ChuyenXe::where('MaTuyen', $tuyen->MaTuyen)
        ->where('MaNhaXe', '!=', $maNhaXe)
        ->where('MaChuyenXe', '!=', $id)
        ->exists();
    
    if ($tuyenUsedByOther) {
        return redirect()->back()->with('error', 'Tuyến đường này đã được nhà xe khác sử dụng!');
    }

    // Kiểm tra xe thuộc về nhà xe này
    if ($request->MaXe) {
        $xe = Xe::where('MaXe', $request->MaXe)
            ->where('MaNhaXe', $maNhaXe)
            ->firstOrFail();
    }

    // Nếu chuyến bị từ chối, khi sửa sẽ chuyển về trạng thái chờ duyệt
    $trangThai = $chuyen->TrangThai;
    $isRejected = ($chuyen->TrangThai == 'TuChoi');
    if ($isRejected) {
        $trangThai = 'ChoDuyet';
    }

    $chuyen->update([
        'MaTuyen' => $request->MaTuyen,
        'DiemLenXe' => $request->DiemLenXe,
        'DiemXuongXe' => $request->DiemXuongXe,
        'MaXe' => $request->MaXe,
        'GioKhoiHanh' => $request->GioKhoiHanh,
        'GioDen' => $request->GioDen,
        'GiaVe' => $request->GiaVe,
        'TrangThai' => $trangThai,
        'LyDoTuChoi' => null, // Xóa lý do từ chối khi chỉnh sửa
    ]);

    $message = $isRejected 
        ? 'Đã cập nhật và gửi lại yêu cầu phê duyệt!' 
        : 'Cập nhật chuyến xe thành công!';

    return redirect()->route('partner.trips')->with('success', $message);
}

public function deleteTrip($id)
{
    $maNhaXe = $this->ensurePartner();
    $chuyen = ChuyenXe::where('MaChuyenXe', $id)
        ->where('MaNhaXe', $maNhaXe)
        ->firstOrFail();
    
    $chuyen->delete();
    return redirect()->route('partner.trips')->with('success', 'Xóa chuyến xe thành công!');
}

// Toggle trạng thái chuyến (Ngưng/Kích hoạt)
public function toggleTripStatus($id)
{
    $maNhaXe = $this->ensurePartner();
    $chuyen = ChuyenXe::where('MaChuyenXe', $id)
        ->where('MaNhaXe', $maNhaXe)
        ->firstOrFail();
    
    // Chỉ cho phép toggle nếu chuyến đã được admin duyệt
    if ($chuyen->TrangThai == 'ChoDuyet') {
        return redirect()->route('partner.trips')->with('error', 'Chuyến xe đang chờ admin phê duyệt! Bạn chưa thể kích hoạt/tạm dừng.');
    }
    
    if ($chuyen->TrangThai == 'TuChoi') {
        return redirect()->route('partner.trips')->with('error', 'Chuyến xe đã bị từ chối! Vui lòng chỉnh sửa và gửi lại yêu cầu phê duyệt.');
    }
    
    if ($chuyen->TrangThai == 'BiKhoa') {
        return redirect()->route('partner.trips')->with('error', 'Chuyến xe đã bị admin khóa! Bạn không thể kích hoạt.');
    }
    
    // Nếu đang "Còn chỗ" hoặc "DaDuyet" thì chuyển sang "Tạm dừng", ngược lại
    if ($chuyen->TrangThai == 'Còn chỗ' || $chuyen->TrangThai == 'DaDuyet') {
        $chuyen->TrangThai = 'Tạm dừng';
        $message = 'Đã ngưng chuyến xe';
    } else if ($chuyen->TrangThai == 'Tạm dừng') {
        $chuyen->TrangThai = 'Còn chỗ';
        $message = 'Đã kích hoạt chuyến xe';
    } else {
        return redirect()->route('partner.trips')->with('error', 'Không thể thay đổi trạng thái chuyến xe này!');
    }
    
    $chuyen->save();
    return redirect()->route('partner.trips')->with('success', $message);
}

// Xem vé đã đặt theo chuyến
public function viewTripTickets($id)
{
    $maNhaXe = $this->ensurePartner();
    $chuyen = ChuyenXe::with(['tuyenDuong', 'veXe.nguoiDung', 'veXe.ghe'])
        ->where('MaChuyenXe', $id)
        ->where('MaNhaXe', $maNhaXe)
        ->firstOrFail();
    
    return view('partner.trips.tickets', compact('chuyen'));
}

// Cập nhật nhanh giờ chạy/giá vé
public function quickUpdateTrip(Request $request, $id)
{
    $maNhaXe = $this->ensurePartner();
    $chuyen = ChuyenXe::where('MaChuyenXe', $id)
        ->where('MaNhaXe', $maNhaXe)
        ->firstOrFail();

    $request->validate([
        'GioKhoiHanh' => 'nullable|date',
        'GioDen' => 'nullable|date|after:GioKhoiHanh',
        'GiaVe' => 'nullable|numeric|min:0',
    ]);

    if ($request->has('GioKhoiHanh')) {
        $chuyen->GioKhoiHanh = $request->GioKhoiHanh;
    }
    if ($request->has('GioDen')) {
        $chuyen->GioDen = $request->GioDen;
    }
    if ($request->has('GiaVe')) {
        $chuyen->GiaVe = $request->GiaVe;
    }

    $chuyen->save();
    return redirect()->route('partner.trips')->with('success', 'Cập nhật thành công!');
}

// ==================== QUẢN LÝ TUYẾN ĐƯỜNG ====================
public function routes()
{
    $maNhaXe = $this->ensurePartner();
    
    // Lấy tất cả tuyến đường của nhà xe này (bao gồm cả chờ duyệt và đã duyệt)
    $routes = TuyenDuong::where('MaNhaXe', $maNhaXe)
        ->orderByDesc('MaTuyen')
        ->get();

    return view('partner.routes.index', compact('routes'));
}

public function createRoute()
{
    $maNhaXe = $this->ensurePartner();
    return view('partner.routes.create', compact('maNhaXe'));
}

public function storeRoute(Request $request)
{
    $maNhaXe = $this->ensurePartner();
    
    $request->validate([
        'DiemDi' => 'required|string|max:100',
        'DiemDen' => 'required|string|max:100',
        'KhoangCach' => 'required|integer|min:1',
        'ThoiGianHanhTrinh' => 'nullable|string|max:50',
    ]);

    TuyenDuong::create([
        'DiemDi' => $request->DiemDi,
        'DiemDen' => $request->DiemDen,
        'KhoangCach' => $request->KhoangCach,
        'ThoiGianHanhTrinh' => $request->ThoiGianHanhTrinh,
        'MaNhaXe' => $maNhaXe,
        'TrangThai' => 'ChoDuyet', // Gửi cho admin phê duyệt
    ]);

    return redirect()->route('partner.routes')->with('success', 'Đã gửi yêu cầu thêm tuyến đường! Tuyến đường sẽ được hiển thị sau khi admin phê duyệt.');
}

public function editRoute($id)
{
    $maNhaXe = $this->ensurePartner();
    
    $route = TuyenDuong::where('MaNhaXe', $maNhaXe)
        ->findOrFail($id);

    return view('partner.routes.edit', compact('route'));
}

public function updateRoute(Request $request, $id)
{
    $maNhaXe = $this->ensurePartner();
    
    $route = TuyenDuong::where('MaNhaXe', $maNhaXe)
        ->findOrFail($id);

    $request->validate([
        'DiemDi' => 'required|string|max:100',
        'DiemDen' => 'required|string|max:100',
        'KhoangCach' => 'required|integer|min:1',
        'ThoiGianHanhTrinh' => 'nullable|string|max:50',
    ]);

    // Nếu tuyến đã được duyệt, khi sửa sẽ chuyển về trạng thái chờ duyệt
    $trangThai = $route->TrangThai;
    $isRejected = ($route->TrangThai == 'TuChoi');
    if ($isRejected || $route->TrangThai == 'DaDuyet') {
        $trangThai = 'ChoDuyet';
    }

    $route->update([
        'DiemDi' => $request->DiemDi,
        'DiemDen' => $request->DiemDen,
        'KhoangCach' => $request->KhoangCach,
        'ThoiGianHanhTrinh' => $request->ThoiGianHanhTrinh,
        'TrangThai' => $trangThai,
        'LyDoTuChoi' => null, // Xóa lý do từ chối khi chỉnh sửa
    ]);

    $message = $isRejected 
        ? 'Đã cập nhật và gửi lại yêu cầu phê duyệt!' 
        : 'Đã gửi yêu cầu cập nhật tuyến đường! Tuyến đường sẽ được cập nhật sau khi admin phê duyệt.';

    return redirect()->route('partner.routes')->with('success', $message);
}

public function deleteRoute($id)
{
    $maNhaXe = $this->ensurePartner();
    
    $route = TuyenDuong::where('MaNhaXe', $maNhaXe)
        ->findOrFail($id);

    // Kiểm tra ràng buộc: Nếu tuyến đã có chuyến xe
    $hasTrips = ChuyenXe::where('MaTuyen', $id)
        ->where('MaNhaXe', $maNhaXe)
        ->exists();

    if ($hasTrips) {
        // Kiểm tra xem có vé xe nào đã được đặt cho các chuyến xe này không
        $hasTickets = VeXe::whereHas('chuyenXe', function($query) use ($id, $maNhaXe) {
            $query->where('MaTuyen', $id)
                  ->where('MaNhaXe', $maNhaXe);
        })->exists();

        if ($hasTickets) {
            return redirect()->route('partner.routes')->with('error', 'Không thể xóa tuyến đường! Tuyến đường này đã có vé xe được đặt.');
        }
        
        return redirect()->route('partner.routes')->with('error', 'Không thể xóa tuyến đường! Tuyến đường này đã có chuyến xe.');
    }

    $route->delete();
    return redirect()->route('partner.routes')->with('success', 'Xóa tuyến đường thành công!');
}

// ==================== QUẢN LÝ XE ====================
public function vehicles()
{
    $maNhaXe = $this->ensurePartner();
    
    $vehicles = Xe::where('MaNhaXe', $maNhaXe)
        ->orderByDesc('MaXe')
        ->get();

    return view('partner.vehicles.index', compact('vehicles'));
}

public function createVehicle()
{
    $maNhaXe = $this->ensurePartner();
    return view('partner.vehicles.create', compact('maNhaXe'));
}

public function storeVehicle(Request $request)
{
    $maNhaXe = $this->ensurePartner();
    
    $request->validate([
        'TenXe' => 'required|string|max:150',
        'LoaiXe' => 'nullable|string|max:100',
        'BienSoXe' => 'nullable|string|max:20',
        'SoGhe' => 'nullable|integer|min:0',
        'TienNghi' => 'nullable|string',
        'HinhAnh1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'HinhAnh2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'HinhAnh3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $data = [
        'MaNhaXe' => $maNhaXe,
        'TenXe' => $request->TenXe,
        'LoaiXe' => $request->LoaiXe,
        'BienSoXe' => $request->BienSoXe,
        'SoGhe' => $request->SoGhe,
        'TienNghi' => $request->TienNghi,
    ];

    for ($i = 1; $i <= 3; $i++) {
        if ($request->hasFile("HinhAnh{$i}")) {
            $image = $request->file("HinhAnh{$i}");
            $imageName = 'xe_' . time() . '_' . $i . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/xe'), $imageName);
            $data["HinhAnh{$i}"] = 'uploads/xe/' . $imageName;
        }
    }

    Xe::create($data);

    return redirect()->route('partner.vehicles')->with('success', 'Thêm xe thành công!');
}

public function editVehicle($id)
{
    $maNhaXe = $this->ensurePartner();
    
    $vehicle = Xe::where('MaNhaXe', $maNhaXe)->findOrFail($id);

    return view('partner.vehicles.edit', compact('vehicle'));
}

public function updateVehicle(Request $request, $id)
{
    $maNhaXe = $this->ensurePartner();
    
    $vehicle = Xe::where('MaNhaXe', $maNhaXe)->findOrFail($id);

    $request->validate([
        'TenXe' => 'required|string|max:150',
        'LoaiXe' => 'nullable|string|max:100',
        'BienSoXe' => 'nullable|string|max:20',
        'SoGhe' => 'nullable|integer|min:0',
        'TienNghi' => 'nullable|string',
        'HinhAnh1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'HinhAnh2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'HinhAnh3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $data = [
        'TenXe' => $request->TenXe,
        'LoaiXe' => $request->LoaiXe,
        'BienSoXe' => $request->BienSoXe,
        'SoGhe' => $request->SoGhe,
        'TienNghi' => $request->TienNghi,
    ];

    for ($i = 1; $i <= 3; $i++) {
        if ($request->hasFile("HinhAnh{$i}")) {
            if ($vehicle->{"HinhAnh{$i}"} && file_exists(public_path($vehicle->{"HinhAnh{$i}"}))) {
                unlink(public_path($vehicle->{"HinhAnh{$i}"}));
            }
            
            $image = $request->file("HinhAnh{$i}");
            $imageName = 'xe_' . time() . '_' . $i . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/xe'), $imageName);
            $data["HinhAnh{$i}"] = 'uploads/xe/' . $imageName;
        }
    }

    $vehicle->update($data);

    return redirect()->route('partner.vehicles')->with('success', 'Cập nhật xe thành công!');
}

public function deleteVehicle($id)
{
    $maNhaXe = $this->ensurePartner();
    
    $vehicle = Xe::where('MaNhaXe', $maNhaXe)->findOrFail($id);

    for ($i = 1; $i <= 3; $i++) {
        if ($vehicle->{"HinhAnh{$i}"} && file_exists(public_path($vehicle->{"HinhAnh{$i}"}))) {
            unlink(public_path($vehicle->{"HinhAnh{$i}"}));
        }
    }

    $vehicle->delete();
    return redirect()->route('partner.vehicles')->with('success', 'Xóa xe thành công!');
}
}
