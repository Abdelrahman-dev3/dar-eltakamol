@extends('layouts.app')

@section('title', __('تسجيل الدخول'))

@section('content')
<div class="auth-container">
    <div class="auth-header">
        <div class="auth-logo">
            <img src="{{ asset('images/logos/لوجو دار التكامل ذهبي.png') }}" alt="Logo" style="width: 185px; height: auto; object-fit: contain;">
        </div>
        <h1 class="auth-title">{{ __('تسجيل الدخول') }}</h1>
        <p class="auth-subtitle">{{ __('مرحباً بك في نظام إدارة دار التكامل') }}</p>
    </div>

    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf
        
        <div class="form-group">
            <label for="email" class="form-label">{{ __('البريد الإلكتروني') }}</label>
            <div class="input-group">
                <i class="input-icon fa fa-envelope"></i>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                       placeholder="{{ __('أدخل بريدك الإلكتروني') }}">
            </div>
            @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">
                <i class="fa fa-lock" style="margin-left: 8px;"></i>
                {{ __('كلمة المرور') }}
            </label>
            <div class="input-group">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                       name="password" required autocomplete="current-password" 
                       placeholder="{{ __('أدخل كلمة المرور') }}">
            </div>
            @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-check">
            <input type="checkbox" name="remember" id="remember" class="form-check-input" {{ old('remember') ? 'checked' : '' }}>
            <label for="remember" class="form-check-label">{{ __('تذكرني') }}</label>
        </div>

        <button type="submit" class="btn-modern btn-primary-modern" id="loginBtn">
            <i class="fa fa-sign-in" style="margin-right: 8px;"></i>
            {{ __('تسجيل الدخول') }}
        </button>
    </form>

    <div class="form-links">
        <a href="{{ route('password.request') }}">{{ __('هل نسيت كلمة المرور؟') }}</a>
    </div>

    <div class="divider">
        <span>{{ __('أو') }}</span>
    </div>

    <div class="form-links">
        <a href="{{ route('register') }}">{{ __('إنشاء حساب جديد') }}</a>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function() {
    const btn = document.getElementById('loginBtn');
    btn.classList.add('btn-loading');
    btn.disabled = true;
});
</script>
@endsection