@extends('layouts.app')
@section('title', $circular->name)
@include('contributor-portal.partials.styles')

@php
    $attachments = $circular->attachments->isNotEmpty() ? $circular->attachments : collect([$circular]);
    $newsBackRoute = $newsBackRoute ?? 'contributor.news';
    $newsViewRoute = $newsViewRoute ?? 'contributor.news.view';
    $newsDownloadRoute = $newsDownloadRoute ?? 'contributor.news.download';
    $newsAttachmentViewRoute = $newsAttachmentViewRoute ?? 'contributor.news.attachments.view';
    $newsAttachmentDownloadRoute = $newsAttachmentDownloadRoute ?? 'contributor.news.attachments.download';
@endphp

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ $circular->name }}</h1>
            <p class="cp-subtitle">{{ __('نشر في') }} {{ $circular->created_at?->format('Y-m-d H:i') }}</p>
        </div>
        <div class="cp-actions">
            <a class="cp-btn cp-btn-primary" href="{{ route($newsViewRoute, $circular) }}" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right"></i>{{ __('عرض المرفق') }}</a>
            <a class="cp-btn cp-btn-secondary" href="{{ route($newsDownloadRoute, $circular) }}"><i class="bi bi-download"></i>{{ __('تحميل المرفق') }}</a>
            <a class="cp-btn cp-btn-secondary" href="{{ route($newsBackRoute) }}"><i class="bi bi-arrow-right"></i>{{ __('رجوع للأخبار') }}</a>
        </div>
    </section>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <section class="cp-card">
        <h2 class="cp-card-title"><i class="bi bi-newspaper"></i>{{ __('تفاصيل الخبر') }}</h2>
        <div class="cp-news-content">
            @if($circular->description)
                <p>{{ $circular->description }}</p>
            @else
                <p>{{ __('لا يوجد شرح إضافي لهذا الخبر.') }}</p>
            @endif
        </div>
    </section>

    <section class="cp-grid-2">
        <article class="cp-stat"><i class="bi bi-hash"></i><p class="cp-stat-value">#{{ $circular->id }}</p><p class="cp-stat-label">{{ __('رقم الخبر') }}</p></article>
        <article class="cp-stat"><i class="bi bi-files"></i><p class="cp-stat-value">{{ number_format($attachments->count()) }}</p><p class="cp-stat-label">{{ __('عدد المرفقات') }}</p></article>
        <article class="cp-stat"><i class="bi bi-hdd"></i><p class="cp-stat-value">{{ $circular->file_size_human }}</p><p class="cp-stat-label">{{ __('حجم المرفق') }}</p></article>
        <article class="cp-stat"><i class="bi bi-calendar-check"></i><p class="cp-stat-value">{{ $circular->updated_at?->format('Y-m-d') }}</p><p class="cp-stat-label">{{ __('آخر تحديث') }}</p></article>
    </section>

    <section class="cp-card">
        <h2 class="cp-card-title"><i class="bi bi-paperclip"></i>{{ __('مرفقات الخبر') }}</h2>
        <div class="cp-news-list">
            @foreach($attachments as $attachment)
                @php
                    $isExtraAttachment = $attachment instanceof \App\Models\CircularAttachment;
                    $viewRoute = $isExtraAttachment ? route($newsAttachmentViewRoute, $attachment) : route($newsViewRoute, $circular);
                    $downloadRoute = $isExtraAttachment ? route($newsAttachmentDownloadRoute, $attachment) : route($newsDownloadRoute, $circular);
                @endphp
                <article class="cp-news-item">
                    <div class="cp-news-icon"><i class="fa {{ $attachment->file_icon }}"></i></div>
                    <div class="cp-news-body">
                        <h3 class="cp-news-title">{{ $attachment->original_filename }}</h3>
                        <div class="cp-news-meta">
                            <span><i class="bi bi-file-earmark"></i>{{ strtoupper($attachment->file_extension ?: '-') }}</span>
                            <span><i class="bi bi-info-circle"></i>{{ $attachment->file_type ?: __('غير محدد') }}</span>
                            <span><i class="bi bi-hdd"></i>{{ $attachment->file_size_human }}</span>
                            <span><i class="bi bi-calendar-plus"></i>{{ $attachment->created_at?->format('Y-m-d H:i') }}</span>
                            <span><i class="bi bi-calendar3"></i>{{ $attachment->updated_at?->format('Y-m-d H:i') }}</span>
                        </div>
                    </div>
                    <div class="cp-actions">
                        <a class="cp-btn cp-btn-primary" href="{{ $viewRoute }}" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right"></i>{{ __('عرض') }}</a>
                        <a class="cp-btn cp-btn-secondary" href="{{ $downloadRoute }}"><i class="bi bi-download"></i>{{ __('تحميل') }}</a>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
</div>
@endsection
