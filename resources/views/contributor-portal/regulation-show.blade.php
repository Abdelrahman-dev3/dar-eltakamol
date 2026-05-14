@extends('layouts.app')
@section('title', $regulation->name)
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ $regulation->name }}</h1>
            <p class="cp-subtitle">{{ __('أضيفت في') }} {{ $regulation->created_at?->format('Y-m-d H:i') }}</p>
        </div>
        <div class="cp-actions">
            <a class="cp-btn cp-btn-primary" href="{{ route('contributor.regulations.download', $regulation) }}"><i class="bi bi-download"></i>{{ __('تحميل اللائحة') }}</a>
            <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.regulations') }}"><i class="bi bi-arrow-right"></i>{{ __('رجوع للوائح') }}</a>
        </div>
    </section>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <section class="cp-card">
        <h2 class="cp-card-title"><i class="bi bi-journal-richtext"></i>{{ __('تفاصيل اللائحة') }}</h2>
        <div class="cp-news-item">
            <div class="cp-news-icon"><i class="fa {{ $regulation->file_icon }}"></i></div>
            <div class="cp-news-body">
                <h3 class="cp-news-title">{{ $regulation->original_filename }}</h3>
                <div class="cp-news-meta">
                    <span><i class="bi bi-file-earmark"></i>{{ strtoupper($regulation->file_extension ?: '-') }}</span>
                    <span><i class="bi bi-hdd"></i>{{ $regulation->file_size_human }}</span>
                    <span><i class="bi bi-calendar3"></i>{{ $regulation->updated_at?->format('Y-m-d H:i') }}</span>
                </div>
            </div>
            <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.regulations.download', $regulation) }}"><i class="bi bi-download"></i>{{ __('تحميل') }}</a>
        </div>
    </section>
</div>
@endsection
