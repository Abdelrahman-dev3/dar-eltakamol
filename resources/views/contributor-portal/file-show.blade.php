@extends('layouts.app')
@php
    $fileBackRoute = $fileBackRoute ?? 'contributor.files';
    $fileDownloadRoute = $fileDownloadRoute ?? 'contributor.files.download';
@endphp
@section('title', $document->name)
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ $document->name }}</h1>
            <p class="cp-subtitle">{{ __('أضيف في') }} {{ $document->created_at?->format('Y-m-d H:i') }}</p>
        </div>
        <div class="cp-actions">
            <a class="cp-btn cp-btn-primary" href="{{ route($fileDownloadRoute, $document) }}"><i class="bi bi-download"></i>{{ __('تحميل الملف') }}</a>
            <a class="cp-btn cp-btn-secondary" href="{{ route($fileBackRoute) }}"><i class="bi bi-arrow-right"></i>{{ __('رجوع للملفات') }}</a>
        </div>
    </section>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <section class="cp-card">
        <h2 class="cp-card-title"><i class="bi bi-folder2-open"></i>{{ __('تفاصيل الملف') }}</h2>
        <div class="cp-news-item">
            <div class="cp-news-icon"><i class="fa {{ $document->file_icon }}"></i></div>
            <div class="cp-news-body">
                <h3 class="cp-news-title">{{ $document->original_filename }}</h3>
                <div class="cp-news-meta">
                    <span><i class="bi bi-file-earmark"></i>{{ strtoupper($document->file_extension ?: '-') }}</span>
                    <span><i class="bi bi-hdd"></i>{{ $document->file_size_human }}</span>
                    <span><i class="bi bi-calendar3"></i>{{ $document->updated_at?->format('Y-m-d H:i') }}</span>
                    @if($document->meeting)
                        <span><i class="bi bi-camera-video"></i>{{ $document->meeting->name }}</span>
                    @endif
                </div>
            </div>
            <a class="cp-btn cp-btn-secondary" href="{{ route($fileDownloadRoute, $document) }}"><i class="bi bi-download"></i>{{ __('تحميل') }}</a>
        </div>
    </section>
</div>
@endsection
