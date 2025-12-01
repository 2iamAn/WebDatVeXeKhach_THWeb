<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NguoiDungController;
use App\Http\Controllers\NhaXeController;
use App\Http\Controllers\ChuyenXeController;
use App\Http\Controllers\GheController;
use App\Http\Controllers\VeXeController;
use App\Http\Controllers\ThanhToanController;
use App\Http\Controllers\BaoCaoController;
use App\Http\Controllers\TuyenDuongController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminPartnerController;
use App\Http\Controllers\DatVeController;
use App\Http\Controllers\DanhGiaController;
use App\Http\Controllers\ContactController;
// Trang chủ - đã được định nghĩa ở dưới
// ===============================================
// NGƯỜI DÙNG
// ===============================================
Route::prefix('nguoidung')->group(function () {
    Route::get('/', [NguoiDungController::class, 'index'])->name('nguoidung.index');
    Route::get('/tao', [NguoiDungController::class, 'create'])->name('nguoidung.create');
    Route::post('/luu', [NguoiDungController::class, 'store'])->name('nguoidung.store');
    Route::get('/sua/{id}', [NguoiDungController::class, 'edit'])->name('nguoidung.edit');
    Route::post('/capnhat/{id}', [NguoiDungController::class, 'update'])->name('nguoidung.update');
    Route::get('/xoa/{id}', [NguoiDungController::class, 'destroy'])->name('nguoidung.destroy');
});


// ===============================================
// NHÀ XE
// ===============================================
Route::prefix('nhaxe')->group(function () {
    Route::get('/', [NhaXeController::class, 'index'])->name('nhaxe.index');
    Route::get('/tao', [NhaXeController::class, 'create'])->name('nhaxe.create');
    Route::post('/luu', [NhaXeController::class, 'store'])->name('nhaxe.store');
    Route::get('/{id}', [NhaXeController::class, 'show'])->name('nhaxe.show');                // <— thêm
    Route::get('/sua/{id}', [NhaXeController::class, 'edit'])->name('nhaxe.edit');
    Route::post('/capnhat/{id}', [NhaXeController::class, 'update'])->name('nhaxe.update');
    Route::get('/xoa/{id}', [NhaXeController::class, 'destroy'])->name('nhaxe.destroy');
});


// ===============================================
// CHUYẾN XE
// ===============================================
Route::prefix('chuyenxe')->group(function () {
    Route::get('/', [ChuyenXeController::class, 'index'])->name('chuyenxe.index');
    Route::get('/tim-kiem', [ChuyenXeController::class, 'search'])->name('chuyenxe.search');
    Route::get('/tao', [ChuyenXeController::class, 'create'])->name('chuyenxe.create');
    Route::post('/luu', [ChuyenXeController::class, 'store'])->name('chuyenxe.store');
    Route::get('/{id}', [ChuyenXeController::class, 'show'])->name('chuyenxe.show');           // <— thêm
    Route::get('/sua/{id}', [ChuyenXeController::class, 'edit'])->name('chuyenxe.edit');
    Route::post('/capnhat/{id}', [ChuyenXeController::class, 'update'])->name('chuyenxe.update');
    Route::get('/xoa/{id}', [ChuyenXeController::class, 'destroy'])->name('chuyenxe.destroy');
});

// ===============================================
// GHẾ
// ===============================================
Route::prefix('ghe')->group(function () {
    Route::get('/', [GheController::class, 'index'])->name('ghe.index');
    Route::get('/tao', [GheController::class, 'create'])->name('ghe.create');
    Route::post('/luu', [GheController::class, 'store'])->name('ghe.store');
    Route::get('/sua/{id}', [GheController::class, 'edit'])->name('ghe.edit');
    Route::post('/capnhat/{id}', [GheController::class, 'update'])->name('ghe.update');
    Route::get('/xoa/{id}', [GheController::class, 'destroy'])->name('ghe.destroy');
});


// ===============================================
// VÉ XE
// ===============================================
Route::prefix('vexe')->group(function () {
    Route::get('/', [VeXeController::class, 'index'])->name('vexe.index');
    Route::get('/dat-cho-cua-toi', [VeXeController::class, 'bookingRecovery'])->name('vexe.booking');
    Route::post('/huy/{id}', [VeXeController::class, 'cancel'])->name('vexe.cancel');
    Route::get('/tao', [VeXeController::class, 'create'])->name('vexe.create');
    Route::post('/luu', [VeXeController::class, 'store'])->name('vexe.store');
    Route::get('/sua/{id}', [VeXeController::class, 'edit'])->name('vexe.edit');
    Route::post('/capnhat/{id}', [VeXeController::class, 'update'])->name('vexe.update');
    Route::get('/xoa/{id}', [VeXeController::class, 'destroy'])->name('vexe.destroy');
    Route::get('/{id}', [VeXeController::class, 'show'])->name('vexe.show');                   // <— thêm
});

// ===============================================
// ĐẶT VÉ
// ===============================================
Route::prefix('datve')->group(function () {
    Route::get('/tao/{ma_chuyen}', [DatVeController::class, 'create'])->name('datve.create');
    Route::post('/luu', [DatVeController::class, 'store'])->name('datve.store');
    Route::get('/thanh-toan/{ma_ve}', [DatVeController::class, 'payment'])->name('datve.payment');
    Route::post('/thanh-toan/{ma_ve}', [DatVeController::class, 'processPayment'])->name('datve.processPayment');
});


// ===============================================
// THANH TOÁN
// ===============================================
Route::prefix('thanhtoan')->group(function () {
    Route::get('/', [ThanhToanController::class, 'index'])->name('thanhtoan.index');
    Route::get('/tao', [ThanhToanController::class, 'create'])->name('thanhtoan.create');
    Route::post('/luu', [ThanhToanController::class, 'store'])->name('thanhtoan.store');
    Route::get('/{id}', [ThanhToanController::class, 'show'])->name('thanhtoan.show');         // <— thêm
    Route::get('/sua/{id}', [ThanhToanController::class, 'edit'])->name('thanhtoan.edit');
    Route::post('/capnhat/{id}', [ThanhToanController::class, 'update'])->name('thanhtoan.update');
    Route::get('/xoa/{id}', [ThanhToanController::class, 'destroy'])->name('thanhtoan.destroy');
});


// ===============================================
// BÁO CÁO
// ===============================================
Route::prefix('baocao')->group(function () {
    Route::get('/', [BaoCaoController::class, 'index'])->name('baocao.index');
    Route::get('/tao', [BaoCaoController::class, 'create'])->name('baocao.create');
    Route::post('/luu', [BaoCaoController::class, 'store'])->name('baocao.store');
    Route::post('/ketso/ngay', [BaoCaoController::class, 'ketSoNgay'])->name('baocao.ketso.ngay');     // <— thêm
    Route::post('/ketso/thang', [BaoCaoController::class, 'ketSoThang'])->name('baocao.ketso.thang');  // <— thêm
    Route::get('/sua/{id}', [BaoCaoController::class, 'edit'])->name('baocao.edit');
    Route::post('/capnhat/{id}', [BaoCaoController::class, 'update'])->name('baocao.update');
    Route::get('/xoa/{id}', [BaoCaoController::class, 'destroy'])->name('baocao.destroy');
});

Route::prefix('tuyenduong')->group(function () {
    Route::get('/', [TuyenDuongController::class, 'index'])->name('tuyenduong.index');
    Route::get('/tao', [TuyenDuongController::class, 'create'])->name('tuyenduong.create');
    Route::post('/luu', [TuyenDuongController::class, 'store'])->name('tuyenduong.store');
    Route::get('/{id}', [TuyenDuongController::class, 'show'])->name('tuyenduong.show'); // ✅ thêm dòng này
    Route::get('/sua/{id}', [TuyenDuongController::class, 'edit'])->name('tuyenduong.edit');
    Route::post('/capnhat/{id}', [TuyenDuongController::class, 'update'])->name('tuyenduong.update');
    Route::get('/xoa/{id}', [TuyenDuongController::class, 'destroy'])->name('tuyenduong.destroy');
});

// ===============================================
// ĐÁNH GIÁ
// ===============================================
Route::prefix('danhgia')->group(function () {
    Route::post('/luu', [DanhGiaController::class, 'store'])->name('danhgia.store');
    Route::get('/nhaxe/{nhaXeId}', [DanhGiaController::class, 'getReviews'])->name('danhgia.get');
    Route::get('/xoa/{id}', [DanhGiaController::class, 'destroy'])->name('danhgia.destroy');
    Route::get('/an-hien/{id}', [DanhGiaController::class, 'toggleVisibility'])->name('danhgia.toggle');
}); 
// ======================== LIÊN HỆ ===========================
Route::get('/lien-he', [ContactController::class, 'index'])->name('contact.index');
Route::post('/lien-he', [ContactController::class, 'store'])->name('contact.store');

// ======================== VỀ CHÚNG TÔI ===========================
Route::get('/ve-chung-toi', function () {
    return view('about');
})->name('about.index');

// ======================== TRANG CHỦ ===========================
Route::get('/', function () {
    // Kiểm tra session và redirect theo role
    if (session('role') === 'partner' && session('user')) {
        return redirect()->route('partner.dashboard');
    }
    if (session('role') === 'admin' && session('user')) {
        return redirect()->route('admin.dashboard');
    }
    // Nếu là user hoặc chưa đăng nhập, hiển thị trang chủ
    return view('welcome');
})->name('welcome');

// ===================== AUTH – ĐĂNG NHẬP / ĐĂNG KÝ =====================

// Hiển thị form
Route::get('/dang-ky', [AuthController::class, 'showRegister'])->name('register.form');
Route::get('/dang-nhap', [AuthController::class, 'showLogin'])->name('login.form');

// Xử lý submit

Route::post('/dang-ky', [AuthController::class, 'register'])->name('register.process');
Route::post('/dang-nhap', [AuthController::class, 'login'])->name('login.process');

// Đăng xuất
Route::get('/dang-xuat', [AuthController::class, 'logout'])->name('logout');


// ====================== DASHBOARD THEO ROLE ==========================

// Trang khách hàng
Route::get('/user/home', [AuthController::class, 'userHome'])->name('user.home');

// Trang nhà xe
Route::get('/partner/home', [AuthController::class, 'partnerHome'])->name('partner.home');

// Trang admin
Route::get('/admin/home', [AuthController::class, 'adminHome'])->name('admin.home');

// FORM yêu cầu hợp tác
Route::get('/hop-tac', [PartnerController::class, 'showRegisterForm'])->name('partner.request');
Route::post('/hop-tac', [PartnerController::class, 'sendRequest'])->name('partner.send');

// ================= ĐỐI TÁC NHÀ XE ===================
Route::prefix('partner')->name('partner.')->group(function () {
    Route::get('/dashboard', [PartnerController::class, 'dashboard'])->name('dashboard');
    
    // Quản lý chuyến đi
    Route::get('/trips', [PartnerController::class, 'trips'])->name('trips');
    Route::get('/trips/create', [PartnerController::class, 'createTrip'])->name('trips.create');
    Route::post('/trips/store', [PartnerController::class, 'storeTrip'])->name('trips.store');
    Route::get('/trips/edit/{id}', [PartnerController::class, 'editTrip'])->name('trips.edit');
    Route::post('/trips/update/{id}', [PartnerController::class, 'updateTrip'])->name('trips.update');
    Route::get('/trips/delete/{id}', [PartnerController::class, 'deleteTrip'])->name('trips.delete');
    Route::get('/trips/toggle/{id}', [PartnerController::class, 'toggleTripStatus'])->name('trips.toggle');
    Route::get('/trips/tickets/{id}', [PartnerController::class, 'viewTripTickets'])->name('trips.tickets');
    Route::post('/trips/quick-update/{id}', [PartnerController::class, 'quickUpdateTrip'])->name('trips.quick-update');
    
    Route::get('/seats', [PartnerController::class, 'seats'])->name('seats');
    Route::post('/seats/lock/{maGhe}', [PartnerController::class, 'lockSeat'])->name('seats.lock');
    Route::post('/seats/unlock/{maGhe}', [PartnerController::class, 'unlockSeat'])->name('seats.unlock');
    Route::post('/seats/cancel-ticket/{maVe}', [PartnerController::class, 'cancelTicketFromPartner'])->name('seats.cancel-ticket');
    Route::post('/seats/confirm-boarding/{maVe}', [PartnerController::class, 'confirmBoarding'])->name('seats.confirm-boarding');
    Route::get('/tickets', [PartnerController::class, 'tickets'])->name('tickets');
    Route::post('/tickets/update-status/{id}', [PartnerController::class, 'updateTicketStatus'])->name('tickets.update-status');
    Route::get('/revenue', [PartnerController::class, 'revenue'])->name('revenue');
    
    // Quản lý tuyến đường
    Route::get('/routes', [PartnerController::class, 'routes'])->name('routes');
    Route::get('/routes/create', [PartnerController::class, 'createRoute'])->name('routes.create');
    Route::post('/routes/store', [PartnerController::class, 'storeRoute'])->name('routes.store');
    Route::get('/routes/edit/{id}', [PartnerController::class, 'editRoute'])->name('routes.edit');
    Route::post('/routes/update/{id}', [PartnerController::class, 'updateRoute'])->name('routes.update');
    Route::get('/routes/delete/{id}', [PartnerController::class, 'deleteRoute'])->name('routes.delete');
    
    // Quản lý xe
    Route::get('/vehicles', [PartnerController::class, 'vehicles'])->name('vehicles');
    Route::get('/vehicles/create', [PartnerController::class, 'createVehicle'])->name('vehicles.create');
    Route::post('/vehicles/store', [PartnerController::class, 'storeVehicle'])->name('vehicles.store');
    Route::get('/vehicles/edit/{id}', [PartnerController::class, 'editVehicle'])->name('vehicles.edit');
    Route::post('/vehicles/update/{id}', [PartnerController::class, 'updateVehicle'])->name('vehicles.update');
    Route::get('/vehicles/delete/{id}', [PartnerController::class, 'deleteVehicle'])->name('vehicles.delete');
});

// ================= ADMIN ===================
Route::prefix('admin')->name('admin.')->group(function () {

    // DASHBOARD
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Quản lý người dùng
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/edit/{id}', [AdminController::class, 'editUser'])->name('users.edit');
    Route::post('/users/update/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::get('/users/delete/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::post('/users/approve/{id}', [AdminController::class, 'approveUser'])->name('users.approve');
    Route::post('/users/reject/{id}', [AdminController::class, 'rejectUser'])->name('users.reject');
    Route::post('/users/toggle-status/{id}', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');

    // Quản lý nhà xe
    Route::get('/partners', [AdminController::class, 'partners'])->name('partners');
    Route::post('/partners/approve/{id}', [AdminController::class, 'approvePartner'])->name('partners.approve');
    Route::post('/partners/reject/{id}', [AdminController::class, 'rejectPartner'])->name('partners.reject');
    Route::get('/partners/delete/{id}', [AdminController::class, 'deletePartner'])->name('partners.delete');

    // Quản lý chuyến xe
    Route::get('/trips', [AdminController::class, 'pendingTrips'])->name('trips.pending');
    Route::get('/trips/{id}', [AdminController::class, 'showTrip'])->name('trips.show');
    Route::post('/trips/approve/{id}', [AdminController::class, 'approveTrip'])->name('trips.approve');
    Route::post('/trips/reject/{id}', [AdminController::class, 'rejectTrip'])->name('trips.reject');
    Route::post('/trips/lock/{id}', [AdminController::class, 'lockTrip'])->name('trips.lock');
    Route::post('/trips/unlock/{id}', [AdminController::class, 'unlockTrip'])->name('trips.unlock');

    // Quản lý tuyến đường
    Route::get('/routes', [AdminController::class, 'pendingRoutes'])->name('routes.pending');
    Route::post('/routes/approve/{id}', [AdminController::class, 'approveRoute'])->name('routes.approve');
    Route::post('/routes/reject/{id}', [AdminController::class, 'rejectRoute'])->name('routes.reject');

    // Báo cáo
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
});
