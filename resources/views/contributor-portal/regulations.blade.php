@extends('layouts.app')
@section('title', __('اللوائح'))
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ __('اللوائح') }}</h1>
            <p class="cp-subtitle">{{ __('اللوائح الموجهة لحسابك من الإدارة.') }}</p>
        </div>
    </section>

    <section class="cp-card">
        <div class="cp-news-list">
            @forelse($regulations as $regulation)
                <article class="cp-news-item">
                    <div class="cp-news-icon"><i class="fa {{ $regulation->file_icon }}"></i></div>
                    <div class="cp-news-body">
                        <h2 class="cp-news-title">{{ $regulation->name }}</h2>
                        <div class="cp-news-meta">
                            <span><i class="bi bi-calendar3"></i>{{ $regulation->created_at?->format('Y-m-d H:i') }}</span>
                            <span><i class="bi bi-paperclip"></i>{{ $regulation->original_filename }}</span>
                            <span><i class="bi bi-hdd"></i>{{ $regulation->file_size_human }}</span>
                        </div>
                    </div>
                    <div class="cp-actions">
                        <a class="cp-btn cp-btn-primary" href="{{ route('contributor.regulations.show', $regulation) }}"><i class="bi bi-eye-fill"></i>{{ __('عرض اللائحة') }}</a>
                        <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.regulations.download', $regulation) }}"><i class="bi bi-download"></i>{{ __('تحميل') }}</a>
                    </div>
                </article>
            @empty
                <div class="cp-empty">{{ __('لا توجد لوائح مخصصة لحسابك حاليا') }}</div>
            @endforelse
        </div>

        {{ $regulations->links() }}
    </section>
</div>
@endsection
