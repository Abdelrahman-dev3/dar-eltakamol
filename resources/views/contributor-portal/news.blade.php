@extends('layouts.app')
@section('title', __('الأخبار'))
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ __('الأخبار') }}</h1>
            <p class="cp-subtitle">{{ __('الأخبار الموجهة لحسابك من الإدارة.') }}</p>
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
                            <span><i class="bi bi-hdd"></i>{{ $item->file_size_human }}</span>
                        </div>
                    </div>
                    <div class="cp-actions">
                        <a class="cp-btn cp-btn-primary" href="{{ route('contributor.news.show', $item) }}"><i class="bi bi-eye-fill"></i>{{ __('عرض الخبر') }}</a>
                        <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.news.download', $item) }}"><i class="bi bi-download"></i>{{ __('تحميل') }}</a>
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
