@extends('layouts.app')
@php
    $pageTitle = $pageTitle ?? __('الملفات');
    $pageSubtitle = $pageSubtitle ?? __('الملفات الموجهة لحسابك من الإدارة.');
    $fileShowRoute = $fileShowRoute ?? 'contributor.files.show';
    $fileDownloadRoute = $fileDownloadRoute ?? 'contributor.files.download';
@endphp
@section('title', $pageTitle)
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ $pageTitle }}</h1>
            <p class="cp-subtitle">{{ $pageSubtitle }}</p>
        </div>
    </section>

    <section class="cp-card">
        <div class="cp-news-list">
            @forelse($files as $document)
                <article class="cp-news-item">
                    <div class="cp-news-icon"><i class="fa {{ $document->file_icon }}"></i></div>
                    <div class="cp-news-body">
                        <h2 class="cp-news-title">{{ $document->name }}</h2>
                        <div class="cp-news-meta">
                            <span><i class="bi bi-calendar3"></i>{{ $document->created_at?->format('Y-m-d H:i') }}</span>
                            <span><i class="bi bi-paperclip"></i>{{ $document->original_filename }}</span>
                            <span><i class="bi bi-hdd"></i>{{ $document->file_size_human }}</span>
                            @if($document->meeting)
                                <span><i class="bi bi-camera-video"></i>{{ $document->meeting->name }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="cp-actions">
                        <a class="cp-btn cp-btn-primary" href="{{ route($fileShowRoute, $document) }}"><i class="bi bi-eye-fill"></i>{{ __('عرض الملف') }}</a>
                        <a class="cp-btn cp-btn-secondary" href="{{ route($fileDownloadRoute, $document) }}"><i class="bi bi-download"></i>{{ __('تحميل') }}</a>
                    </div>
                </article>
            @empty
                <div class="cp-empty">{{ __('لا توجد ملفات مخصصة لحسابك حاليا') }}</div>
            @endforelse
        </div>

        {{ $files->links() }}
    </section>
</div>
@endsection
