@extends('layouts.app')

@section('title', __('تسجيل جديد'))

@section('content')
<div class="auth-container">
    <div class="auth-header">
        <div class="auth-logo">
            <img src="{{ asset('images/logos/Ù„ÙˆØ¬Ùˆ Ø¯Ø§Ø± Ø§Ù„ØªÙƒØ§Ù…Ù„ Ø°Ù‡Ø¨ÙŠ.png') }}" alt="Logo" style="width: 185px; height: auto; object-fit: contain;">
        </div>
        <h1 class="auth-title">{{ __('إنشاء حساب جديد') }}</h1>
        <p class="auth-subtitle">{{ __('انضم إلى نظام إدارة دار التكامل') }}</p>
    </div>

    <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf

        <div class="form-group">
            <label for="name" class="form-label">{{ __('الاسم الكامل') }}</label>
            <div class="input-group">
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                       name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
            </div>
            @error('name')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email" class="form-label">{{ __('البريد الإلكتروني') }}</label>
            <div class="input-group">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}" required autocomplete="email">
            </div>
            @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">{{ __('كلمة المرور') }}</label>
            <div class="input-group">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                       name="password" required autocomplete="new-password">
            </div>
            @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password-confirm" class="form-label">{{ __('تأكيد كلمة المرور') }}</label>
            <div class="input-group">
                <input id="password-confirm" type="password" class="form-control"
                       name="password_confirmation" required autocomplete="new-password">
            </div>
        </div>

        <div class="form-group">
            <label for="department_id" class="form-label">{{ __('الإدارة (اختياري)') }}</label>
            <select name="department_id" id="department_id" class="form-control">
                <option value="">{{ __('-- اختر الإدارة --') }}</option>
                @foreach(\App\Models\Category::departments()->with('parent')->orderBy('name')->get() as $department)
                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}{{ $department->parent ? ' - ' . $department->parent->name : '' }}
                    </option>
                @endforeach
            </select>
            @error('department_id')
                <span class="invalid-feedback" style="display: block;">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-modern btn-primary-modern" id="registerBtn">
            {{ __('إنشاء الحساب') }}
        </button>
    </form>

    <div class="divider">
        <span>{{ __('أو') }}</span>
    </div>

    <div class="form-links">
        <a href="{{ route('login') }}">{{ __('لديك حساب بالفعل؟ تسجيل الدخول') }}</a>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', function() {
    const btn = document.getElementById('registerBtn');
    btn.classList.add('btn-loading');
    btn.disabled = true;
});

document.getElementById('password-confirm').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;

    if (confirmPassword && password !== confirmPassword) {
        this.setCustomValidity('كلمة المرور غير متطابقة');
    } else {
        this.setCustomValidity('');
    }
});
</script>
@endsection
