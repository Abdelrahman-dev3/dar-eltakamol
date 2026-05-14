@extends('layouts.app')
@section('title', $circular->name)
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ $circular->name }}</h1>
            <p class="cp-subtitle">{{ __('نشر في') }} {{ $circular->created_at?->format('Y-m-d H:i') }}</p>
        </div>
        <div class="cp-actions">
            <a class="cp-btn cp-btn-primary" href="{{ route('contributor.news.download', $circular) }}"><i class="bi bi-download"></i>{{ __('تحميل المرفق') }}</a>
            <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.news') }}"><i class="bi bi-arrow-right"></i>{{ __('رجوع للأخبار') }}</a>
        </div>
    </section>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <section class="cp-card">
        <h2 class="cp-card-title"><i class="bi bi-newspaper"></i>{{ __('تفاصيل الخبر') }}</h2>
        <div class="cp-news-content">
            <p>{{ __('تم نشر هذا الخبر لحسابك من قبل الإدارة، ويمكنك الاطلاع على المرفق المرتبط به.') }}</p>
        </div>
    </section>

    <section class="cp-card">
        <h2 class="cp-card-title"><i class="bi bi-paperclip"></i>{{ __('مرفق الخبر') }}</h2>
        <div class="cp-news-item">
            <div class="cp-news-icon"><i class="fa {{ $circular->file_icon }}"></i></div>
            <div class="cp-news-body">
                <h3 class="cp-news-title">{{ $circular->original_filename }}</h3>
                <div class="cp-news-meta">
                    <span><i class="bi bi-file-earmark"></i>{{ strtoupper($circular->file_extension ?: '-') }}</span>
                    <span><i class="bi bi-hdd"></i>{{ $circular->file_size_human }}</span>
                    <span><i class="bi bi-calendar3"></i>{{ $circular->updated_at?->format('Y-m-d H:i') }}</span>
                </div>
            </div>
            <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.news.download', $circular) }}"><i class="bi bi-download"></i>{{ __('تحميل') }}</a>
        </div>
    </section>
</div>
@endsection
