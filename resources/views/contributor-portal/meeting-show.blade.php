@extends('layouts.app')
@section('title', $meeting->name)
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ $meeting->name }}</h1>
            <p class="cp-subtitle">{{ __('موعد الاجتماع') }}: {{ $meeting->date?->format('Y-m-d H:i') }}</p>
        </div>
        <div class="cp-actions">
            @if($meeting->url)
                <a class="cp-btn cp-btn-primary" href="{{ $meeting->url }}" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-left"></i>{{ __('فتح رابط الاجتماع') }}</a>
            @endif
            <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.meetings') }}"><i class="bi bi-arrow-right"></i>{{ __('رجوع للاجتماعات') }}</a>
        </div>
    </section>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <section class="cp-grid-2">
        <article class="cp-card">
            <h2 class="cp-card-title"><i class="bi bi-info-circle-fill"></i>{{ __('بيانات الاجتماع') }}</h2>
            <div class="cp-news-meta">
                <span><i class="bi bi-calendar3"></i>{{ $meeting->date?->format('Y-m-d H:i') }}</span>
                <span><i class="bi bi-people-fill"></i>{{ number_format($meeting->users->count()) }} {{ __('مدعو') }}</span>
                <span><i class="bi bi-paperclip"></i>{{ number_format($meeting->attachments->count()) }} {{ __('مرفق') }}</span>
            </div>
        </article>

        <article class="cp-card">
            <h2 class="cp-card-title"><i class="bi bi-link-45deg"></i>{{ __('رابط الاجتماع') }}</h2>
            @if($meeting->url)
                <a class="cp-btn cp-btn-secondary" href="{{ $meeting->url }}" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-left"></i>{{ __('فتح الرابط') }}</a>
            @else
                <div class="cp-empty">{{ __('لا يوجد رابط مضاف لهذا الاجتماع') }}</div>
            @endif
        </article>
    </section>

    @include('contributor-portal.partials.meeting-polls-card', ['meeting' => $meeting])

    <section class="cp-card">
        <h2 class="cp-card-title"><i class="bi bi-paperclip"></i>{{ __('مرفقات الاجتماع') }}</h2>
        <div class="cp-news-list">
            @forelse($meeting->attachments as $attachment)
                <article class="cp-news-item">
                    <div class="cp-news-icon"><i class="fa {{ $attachment->file_icon }}"></i></div>
                    <div class="cp-news-body">
                        <h3 class="cp-news-title">{{ $attachment->file_name }}</h3>
                        <div class="cp-news-meta">
                            <span><i class="bi bi-hdd"></i>{{ $attachment->file_size_human }}</span>
                            <span><i class="bi bi-calendar3"></i>{{ $attachment->created_at?->format('Y-m-d H:i') }}</span>
                            @if($attachment->description)
                                <span><i class="bi bi-chat-left-text"></i>{{ $attachment->description }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="cp-actions">
                        <a class="cp-btn cp-btn-primary" href="{{ route('contributor.meetings.attachments.view', [$meeting, $attachment]) }}" target="_blank" rel="noopener"><i class="bi bi-eye-fill"></i>{{ __('عرض') }}</a>
                        <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.meetings.attachments.download', [$meeting, $attachment]) }}"><i class="bi bi-download"></i>{{ __('تحميل') }}</a>
                    </div>
                </article>
            @empty
                <div class="cp-empty">{{ __('لا توجد مرفقات لهذا الاجتماع') }}</div>
            @endforelse
        </div>
    </section>
</div>
@endsection
