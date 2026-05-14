@extends('layouts.app')
@section('title', __('الملفات'))
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ __('الملفات') }}</h1>
            <p class="cp-subtitle">{{ __('الملفات الموجهة لحسابك من الإدارة.') }}</p>
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
                        <a class="cp-btn cp-btn-primary" href="{{ route('contributor.files.show', $document) }}"><i class="bi bi-eye-fill"></i>{{ __('عرض الملف') }}</a>
                        <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.files.download', $document) }}"><i class="bi bi-download"></i>{{ __('تحميل') }}</a>
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
