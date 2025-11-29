<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AdminPartnerController extends Controller
{
    // Danh sách yêu cầu hợp tác
    public function index()
    {
        $requests = DB::table('yeucaunhaxe')->orderBy('id', 'DESC')->get();
        return view('admin.partner.index', compact('requests'));
    }

    // Xem chi tiết yêu cầu
    public function view($id)
    {
        $data = DB::table('yeucaunhaxe')->where('id', $id)->first();
        return view('admin.partner.view', compact('data'));
    }

    // Duyệt yêu cầu
    public function approve(Request $request, $id)
    {
        $info = DB::table('yeucaunhaxe')->where('id', $id)->first();

        // Tạo tài khoản nhà xe
        $password = substr(md5(time()), 0, 8);

        DB::table('nhaxe')->insert([
            'TenNhaXe' => $info->TenNhaXe,
            'Email' => $info->Email,
            'SDT' => $info->SDT,
            'DiaChi' => $info->DiaChi,
            'MatKhau' => $password,
            'TrangThai' => 1,
            'created_at' => now()
        ]);

        // Cập nhật trạng thái yêu cầu
        DB::table('yeucaunhaxe')->where('id', $id)->update([
            'TrangThai' => 'DaDuyet'
        ]);

        // Gửi email tài khoản
        Mail::raw("Chúc mừng! Yêu cầu hợp tác đã được duyệt.\nTài khoản: {$info->Email}\nMật khẩu: $password", function ($msg) use ($info) {
            $msg->to($info->Email)->subject('Yêu cầu hợp tác đã được duyệt');
        });

        return redirect()->route('admin.partner.index')->with('success', 'Đã duyệt yêu cầu và gửi email tài khoản!');
    }

    // Từ chối yêu cầu
    public function reject(Request $request, $id)
    {
        DB::table('yeucaunhaxe')->where('id', $id)->update([
            'TrangThai' => 'TuChoi',
            'LyDoTuChoi' => $request->LyDoTuChoi
        ]);

        // Gửi email thông báo
        $info = DB::table('yeucaunhaxe')->where('id', $id)->first();

        Mail::raw("Yêu cầu hợp tác của bạn đã bị từ chối.\nLý do: {$request->LyDoTuChoi}", function ($msg) use ($info) {
            $msg->to($info->Email)->subject('Yêu cầu hợp tác bị từ chối');
        });

        return redirect()->route('admin.partner.index')->with('success', 'Đã từ chối yêu cầu!');
    }
}
