@extends('layouts.app')
@php
    $pageTitle = $pageTitle ?? __('الأخبار');
    $pageSubtitle = $pageSubtitle ?? __('الأخبار الموجهة لحسابك من الإدارة.');
    $newsShowRoute = $newsShowRoute ?? 'contributor.news.show';
    $newsViewRoute = $newsViewRoute ?? 'contributor.news.view';
    $newsDownloadRoute = $newsDownloadRoute ?? 'contributor.news.download';
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
            @forelse($news as $item)
                <article class="cp-news-item">
                    <div class="cp-news-icon"><i class="fa {{ $item->file_icon }}"></i></div>
                    <div class="cp-news-body">
                        <h2 class="cp-news-title">{{ $item->name }}</h2>
                        <div class="cp-news-meta">
                            <span><i class="bi bi-calendar3"></i>{{ $item->created_at?->format('Y-m-d H:i') }}</span>
                            <span><i class="bi bi-paperclip"></i>{{ $item->original_filename }}</span>
                            <span><i class="bi bi-files"></i>{{ number_format(max((int) ($item->attachments_count ?? 0), 1)) }} {{ __('مرفق') }}</span>
                            <span><i class="bi bi-hdd"></i>{{ $item->file_size_human }}</span>
                        </div>
                        @if($item->description)
                            <p class="cp-news-excerpt">{{ \Illuminate\Support\Str::limit($item->description, 160) }}</p>
                        @endif
                    </div>
                    <div class="cp-actions">
                        <a class="cp-btn cp-btn-primary" href="{{ route($newsShowRoute, $item) }}"><i class="bi bi-eye-fill"></i>{{ __('عرض الخبر') }}</a>
                        <a class="cp-btn cp-btn-secondary" href="{{ route($newsViewRoute, $item) }}" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right"></i>{{ __('عرض المرفق') }}</a>
                        <a class="cp-btn cp-btn-secondary" href="{{ route($newsDownloadRoute, $item) }}"><i class="bi bi-download"></i>{{ __('تحميل') }}</a>
                    </div>
                </article>
            @empty
                <div class="cp-empty">{{ __('لا توجد أخبار مخصصة لحسابك حاليا') }}</div>
            @endforelse
        </div>

        {{ $news->links() }}
    </section>
</div>
@endsection
