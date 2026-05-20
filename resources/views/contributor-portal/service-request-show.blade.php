@extends('layouts.app')
@section('title', __('تفاصيل طلب الخدمة'))
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ __('طلب خدمة') }} #{{ $booking->id }}</h1>
            <p class="cp-subtitle">{{ __('متابعة حالة الطلب والردود المتبادلة مع الإدارة.') }}</p>
        </div>
        <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.services') }}"><i class="bi bi-arrow-right"></i>{{ __('رجوع') }}</a>
    </section>

    <section class="cp-grid">
        <article class="cp-stat"><i class="bi bi-briefcase-fill"></i><p class="cp-stat-value">{{ $booking->service?->name ?: '-' }}</p><p class="cp-stat-label">{{ __('الخدمة') }}</p></article>
        <article class="cp-stat"><i class="bi bi-calendar-check-fill"></i><p class="cp-stat-value">{{ $booking->created_at?->format('Y-m-d') }}</p><p class="cp-stat-label">{{ __('تاريخ التقديم') }}</p></article>
        <article class="cp-stat"><i class="bi bi-flag-fill"></i><p class="cp-stat-value">{{ $booking->status_text }}</p><p class="cp-stat-label">{{ __('الحالة') }}</p></article>
        <article class="cp-stat"><i class="bi bi-chat-dots-fill"></i><p class="cp-stat-value">{{ number_format($booking->messages->count()) }}</p><p class="cp-stat-label">{{ __('رسائل المتابعة') }}</p></article>
    </section>

    <section class="cp-card">
        <h2 class="cp-card-title"><i class="bi bi-file-text-fill"></i>{{ __('البيان') }}</h2>
        <div class="cp-news-content">{{ $booking->notes ?: __('لا يوجد بيان مرفق.') }}</div>
    </section>

    <section class="cp-card">
        <h2 class="cp-card-title"><i class="bi bi-chat-square-text-fill"></i>{{ __('متابعة الطلب') }}</h2>
        <div class="cp-news-list">
            @forelse($booking->messages as $message)
                <article class="cp-news-item">
                    <div class="cp-news-icon"><i class="bi {{ $message->author_type === \App\Models\BookingMessage::AUTHOR_ADMIN ? 'bi-person-gear' : 'bi-person-fill' }}"></i></div>
                    <div class="cp-news-body">
                        <h3 class="cp-news-title">{{ $message->author_label }}</h3>
                        <div class="cp-news-meta"><span><i class="bi bi-clock"></i>{{ $message->created_at?->format('Y-m-d H:i') }}</span></div>
                        <p class="cp-news-excerpt">{{ $message->message }}</p>
                    </div>
                </article>
            @empty
                <div class="cp-empty">{{ __('لا توجد رسائل متابعة حتى الآن.') }}</div>
            @endforelse
        </div>
    </section>

    @if($booking->status !== \App\Models\Booking::STATUS_COMPLETED)
        <section class="cp-card">
            <h2 class="cp-card-title"><i class="bi bi-reply-fill"></i>{{ __('إضافة رد') }}</h2>
            <form class="cp-form" method="POST" action="{{ route('contributor.services.reply', $booking) }}">
                @csrf
                <div class="cp-field full">
                    <label class="cp-label" for="message">{{ __('الرد') }}</label>
                    <textarea class="cp-textarea" name="message" id="message" required>{{ old('message') }}</textarea>
                    @error('message')<span class="cp-error">{{ $message }}</span>@enderror
                </div>
                <div class="cp-actions">
                    <button class="cp-btn cp-btn-primary" type="submit"><i class="bi bi-send-fill"></i>{{ __('إرسال الرد') }}</button>
                </div>
            </form>
        </section>
    @endif
</div>
@endsection
