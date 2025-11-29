<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NhaXe;

class ContactController extends Controller
{
    public function index()
    {
        // Lấy danh sách nhà xe để hiển thị trong dropdown
        $nhaxes = NhaXe::orderBy('TenNhaXe')->get();
        
        return view('contact', compact('nhaxes'));
    }

    public function store(Request $request)
    {
        $rules = [
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'dien_thoai' => 'required|string|max:20',
            'tieu_de' => 'required|string|max:255',
            'ghi_chu' => 'nullable|string|max:1000',
            'loai_lien_he' => 'required|in:nha_xe,khach_hang',
        ];

        // Nếu là nhà xe đối tác, bắt buộc phải có tên nhà xe
        if ($request->loai_lien_he === 'nha_xe') {
            $rules['nha_xe'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules);

        // TODO: Lưu vào database hoặc gửi email
        // Ví dụ: Contact::create($validated);
        // Hoặc: Mail::to('dinhthuphuong1302@gmail.com')->send(new ContactMail($validated));

        $message = $request->loai_lien_he === 'nha_xe' 
            ? 'Cảm ơn nhà xe đối tác đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.'
            : 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.';

        return redirect()->route('contact.index')->with('success', $message);
    }
}
