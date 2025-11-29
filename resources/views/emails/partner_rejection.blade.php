<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yêu cầu hợp tác bị từ chối</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #4FB99F 0%, #3a8f7a 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border: 1px solid #ddd;
            border-top: none;
        }
        .alert-danger {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .reason-box {
            background: white;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .footer {
            background: #f0f0f0;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-radius: 0 0 8px 8px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #4FB99F;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Bustrip - Thông báo từ chối yêu cầu hợp tác</h2>
    </div>
    
    <div class="content">
        <p>Xin chào <strong>{{ $tenNhaXe }}</strong>,</p>
        
        <p>Chúng tôi rất tiếc phải thông báo rằng yêu cầu hợp tác của bạn đã bị từ chối.</p>
        
        <div class="alert-danger">
            <strong><i class="fa fa-exclamation-triangle"></i> Lý do từ chối:</strong>
        </div>
        
        <div class="reason-box">
            <p style="margin: 0; white-space: pre-wrap;">{{ $lyDoTuChoi }}</p>
        </div>
        
        <p>Vui lòng xem xét lý do trên và bổ sung thông tin cần thiết. Sau đó, bạn có thể gửi lại yêu cầu hợp tác.</p>
        
        <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi qua email hoặc số điện thoại hỗ trợ.</p>
        
        <a href="{{ url('/hop-tac') }}" class="btn">Gửi lại yêu cầu hợp tác</a>
        
        <p style="margin-top: 30px;">Trân trọng,<br>
        <strong>Đội ngũ Bustrip</strong></p>
    </div>
    
    <div class="footer">
        <p>Email này được gửi tự động từ hệ thống Bustrip.</p>
        <p>Vui lòng không trả lời email này.</p>
    </div>
</body>
</html>

