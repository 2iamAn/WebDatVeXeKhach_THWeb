@extends('layouts.app')

@section('title', 'Nhập mã xác thực')

@section('content')

<style>
.verify-container {
    min-height: calc(100vh - 120px);
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px 20px;
    background: linear-gradient(135deg, #e0f6f1, #f6fffd);
}

.verify-box {
    max-width: 500px;
    width: 100%;
    background: #fff;
    border-radius: 24px;
    box-shadow: 0 18px 45px rgba(0, 0, 0, 0.08);
    padding: 50px 40px;
    text-align: center;
}

.verify-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #4FB99F 0%, #3a8f7a 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 30px;
    font-size: 40px;
    color: white;
}

.verify-box h2 {
    font-size: 28px;
    font-weight: 700;
    color: #333;
    margin-bottom: 15px;
}

.verify-box p {
    color: #666;
    font-size: 16px;
    margin-bottom: 10px;
    line-height: 1.6;
}

.email-display {
    background: #f8f9fa;
    padding: 12px 20px;
    border-radius: 8px;
    margin: 20px 0;
    font-weight: 600;
    color: #4FB99F;
    font-size: 16px;
}

.code-input-group {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin: 30px 0;
}

.code-input {
    width: 50px;
    height: 60px;
    text-align: center;
    font-size: 28px;
    font-weight: 700;
    border: 2px solid rgba(168, 230, 212, 0.6);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.code-input:focus {
    outline: none;
    border-color: #4FB99F;
    box-shadow: 0 0 0 3px rgba(79, 185, 159, 0.15);
}

.code-input-hidden {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.btn-verify {
    width: 100%;
    background: linear-gradient(135deg, #4FB99F 0%, #3a8f7a 100%);
    padding: 14px;
    color: #fff;
    border: none;
    border-radius: 30px;
    font-size: 17px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(79, 185, 159, 0.4);
    margin-top: 20px;
}

.btn-verify:hover {
    background: linear-gradient(135deg, #3a8f7a 0%, #2d6f5e 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(79, 185, 159, 0.5);
    color: #fff;
}

.resend-link {
    display: block;
    margin-top: 20px;
    color: #4FB99F;
    text-decoration: none;
    font-size: 15px;
}

.resend-link:hover {
    text-decoration: underline;
}

.back-link {
    display: inline-block;
    margin-top: 15px;
    color: #666;
    text-decoration: none;
    font-size: 14px;
}

.back-link:hover {
    text-decoration: underline;
}

.alert {
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.timer {
    color: #ff6b6b;
    font-weight: 600;
    margin-top: 10px;
}
</style>

<div class="verify-container">
    <div class="verify-box">
        <div class="verify-icon">
            <i class="fa fa-key"></i>
        </div>
        
        <h2>Nhập mã xác thực</h2>
        <p>Chúng tôi đã gửi mã xác thực 6 chữ số đến:</p>
        <div class="email-display">
            <i class="fa fa-envelope"></i> {{ $email }}
        </div>
        <p style="font-size: 14px; color: #999;">Vui lòng kiểm tra hộp thư đến và thư mục spam</p>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0" style="text-align: left; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('verification.verify') }}" id="verifyForm">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">
            
            <div class="code-input-group">
                <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off">
                <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off">
                <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off">
                <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off">
                <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off">
                <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off">
            </div>
            
            <input type="text" name="code" id="codeInput" class="code-input-hidden" required>
            
            <button type="submit" class="btn-verify">
                <i class="fa fa-check"></i> Xác thực
            </button>
        </form>

        <form method="POST" action="{{ route('verification.resend') }}" style="display: inline;">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">
            <button type="submit" class="resend-link" style="background: none; border: none; cursor: pointer;">
                <i class="fa fa-redo"></i> Gửi lại mã xác thực
            </button>
        </form>

        <a href="{{ route('verification.email', ['type' => $type]) }}" class="back-link">
            <i class="fa fa-arrow-left"></i> Thay đổi email
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.code-input');
    const codeInput = document.getElementById('codeInput');
    
    inputs.forEach((input, index) => {
        input.addEventListener('input', function(e) {
            const value = e.target.value.replace(/[^0-9]/g, '');
            e.target.value = value;
            
            if (value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
            
            updateCode();
        });
        
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                inputs[index - 1].focus();
            }
        });
        
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            const numbers = paste.replace(/[^0-9]/g, '').slice(0, 6);
            
            numbers.split('').forEach((num, i) => {
                if (inputs[i]) {
                    inputs[i].value = num;
                }
            });
            
            updateCode();
            if (inputs[numbers.length - 1]) {
                inputs[numbers.length - 1].focus();
            }
        });
    });
    
    function updateCode() {
        const code = Array.from(inputs).map(input => input.value).join('');
        codeInput.value = code;
    }
    
    // Auto focus first input
    inputs[0].focus();
});
</script>

@endsection
