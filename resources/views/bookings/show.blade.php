@extends('layouts.app')
@section('title', __('تفاصيل طلب الخدمة'))

@push('styles')
<style>
    .svc-page { display: flex; flex-direction: column; gap: 1.25rem; }
    .svc-hero, .svc-card, .svc-stat { background: rgba(255,255,255,.96); border: 1px solid rgba(170,134,63,.14); border-radius: 1.1rem; box-shadow: 0 .8rem 1.8rem rgba(15,23,42,.06); }
    .svc-hero { padding: 1.5rem; display: flex; justify-content: space-between; gap: 1rem; flex-wrap: wrap; align-items: center; }
    .svc-title { margin: 0; color: var(--text-primary); font-size: 2rem; font-weight: 900; }
    .svc-subtitle { margin: .35rem 0 0; color: var(--text-secondary); line-height: 1.7; }
    .svc-actions { display: flex; gap: .6rem; flex-wrap: wrap; align-items: center; }
    .svc-btn { display: inline-flex; align-items: center; justify-content: center; gap: .45rem; min-height: 2.65rem; padding: .65rem 1rem; border-radius: .8rem; border: 0; text-decoration: none !important; font-weight: 800; }
    .svc-btn-primary { background: linear-gradient(135deg, var(--primary-color), #c49b48); color: #fff; }
    .svc-btn-secondary { background: rgba(170,134,63,.08); color: var(--primary-color); border: 1px solid rgba(170,134,63,.14); }
    .svc-btn-danger { background: rgba(220,38,38,.1); color: var(--danger-color); border: 1px solid rgba(220,38,38,.14); }
    .svc-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1rem; }
    .svc-stat, .svc-card { padding: 1rem; }
    .svc-stat i { width: 2.7rem; height: 2.7rem; display: inline-flex; align-items: center; justify-content: center; border-radius: .8rem; background: rgba(170,134,63,.1); color: var(--primary-color); margin-bottom: .7rem; }
    .svc-stat-value { margin: 0; color: var(--text-primary); font-size: 1.25rem; font-weight: 900; overflow-wrap: anywhere; }
    .svc-stat-label { margin: .25rem 0 0; color: var(--text-secondary); font-weight: 700; }
    .svc-card-title { margin: 0 0 .85rem; color: var(--text-primary); font-size: 1.2rem; font-weight: 900; display: flex; align-items: center; gap: .45rem; }
    .svc-message-list { display: flex; flex-direction: column; gap: .85rem; }
    .svc-message { padding: 1rem; border: 1px solid rgba(170,134,63,.12); border-radius: 1rem; background: #fff; }
    .svc-message-head { display: flex; justify-content: space-between; gap: .75rem; flex-wrap: wrap; margin-bottom: .5rem; color: var(--text-primary); font-weight: 900; }
    .svc-message-date { color: var(--text-secondary); font-weight: 700; }
    .svc-message-body { margin: 0; color: var(--text-secondary); line-height: 1.8; font-weight: 700; white-space: pre-wrap; }
    .svc-form { display: grid; gap: .85rem; }
    .svc-label { display: block; color: var(--text-primary); font-weight: 800; margin-bottom: .35rem; }
    .svc-textarea { width: 100%; min-height: 8rem; border: 1px solid rgba(170,134,63,.16); border-radius: .85rem; padding: .75rem .85rem; resize: vertical; }
    .svc-badge { display: inline-flex; align-items: center; padding: .35rem .6rem; border-radius: 999px; background: rgba(170,134,63,.09); color: var(--primary-color); font-weight: 800; white-space: nowrap; }
    .svc-empty { padding: 1.5rem; text-align: center; color: var(--text-secondary); }
    @media (max-width: 1000px) { .svc-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 680px) { .svc-grid { grid-template-columns: 1fr; } .svc-btn { width: 100%; } }
</style>
@endpush

@section('content')
<div class="svc-page">
    <section class="svc-hero">
        <div>
            <h1 class="svc-title">{{ __('طلب خدمة') }} #{{ $booking->id }}</h1>
            <p class="svc-subtitle">{{ __('تفاصيل الطلب وسجل المتابعة مع المساهم.') }}</p>
        </div>
        <div class="svc-actions">
            <a class="svc-btn svc-btn-secondary" href="{{ route('bookings.index') }}"><i class="bi bi-arrow-right"></i>{{ __('رجوع') }}</a>
            <a class="svc-btn svc-btn-secondary" href="{{ route('bookings.edit', $booking->id) }}"><i class="bi bi-pencil-square"></i>{{ __('تعديل') }}</a>
        </div>
    </section>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <section class="svc-grid">
        <article class="svc-stat"><i class="bi bi-briefcase-fill"></i><p class="svc-stat-value">{{ $booking->service?->name ?: '-' }}</p><p class="svc-stat-label">{{ __('الخدمة') }}</p></article>
        <article class="svc-stat"><i class="bi bi-person-fill"></i><p class="svc-stat-value">{{ $booking->user?->name ?: '-' }}</p><p class="svc-stat-label">{{ __('المساهم') }}</p></article>
        <article class="svc-stat"><i class="bi bi-calendar-check-fill"></i><p class="svc-stat-value">{{ $booking->created_at?->format('Y-m-d') }}</p><p class="svc-stat-label">{{ __('تاريخ التقديم') }}</p></article>
        <article class="svc-stat"><i class="bi bi-flag-fill"></i><p class="svc-stat-value">{{ $booking->status_text }}</p><p class="svc-stat-label">{{ __('الحالة') }}</p></article>
    </section>

    <section class="svc-card">
        <h2 class="svc-card-title"><i class="bi bi-file-text-fill"></i>{{ __('البيان') }}</h2>
        <p class="svc-message-body">{{ $booking->notes ?: __('لا يوجد بيان مرفق.') }}</p>
    </section>

    <section class="svc-card">
        <h2 class="svc-card-title"><i class="bi bi-chat-square-text-fill"></i>{{ __('متابعة التقدم') }}</h2>
        <div class="svc-message-list">
            @forelse($booking->messages as $message)
                <article class="svc-message">
                    <div class="svc-message-head">
                        <span>{{ $message->author_label }}</span>
                        <span class="svc-message-date">{{ $message->created_at?->format('Y-m-d H:i') }}</span>
                    </div>
                    <p class="svc-message-body">{{ $message->message }}</p>
                </article>
            @empty
                <div class="svc-empty">{{ __('لا توجد رسائل متابعة حتى الآن.') }}</div>
            @endforelse
        </div>
    </section>

    @if($booking->status !== \App\Models\Booking::STATUS_COMPLETED)
        <section class="svc-card">
            <h2 class="svc-card-title"><i class="bi bi-send-fill"></i>{{ __('إضافة متابعة') }}</h2>
            <form class="svc-form" method="POST" action="{{ route('bookings.progress', $booking->id) }}">
                @csrf
                <div>
                    <label class="svc-label" for="message">{{ __('النص التوضيحي') }}</label>
                    <textarea class="svc-textarea" name="message" id="message" required>{{ old('message') }}</textarea>
                    @error('message')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="svc-actions">
                    <button class="svc-btn svc-btn-primary" type="submit"><i class="bi bi-send-check-fill"></i>{{ __('إرسال المتابعة') }}</button>
                </div>
            </form>
        </section>

        <section class="svc-card">
            <h2 class="svc-card-title"><i class="bi bi-patch-check-fill"></i>{{ __('إغلاق الطلب') }}</h2>
            <form class="svc-form" method="POST" action="{{ route('bookings.complete', $booking->id) }}">
                @csrf
                <div>
                    <label class="svc-label" for="complete_message">{{ __('ملاحظة الإغلاق') }}</label>
                    <textarea class="svc-textarea" name="message" id="complete_message">{{ old('message') }}</textarea>
                </div>
                <div class="svc-actions">
                    <button class="svc-btn svc-btn-danger" type="submit"><i class="bi bi-check2-circle"></i>{{ __('إغلاق الخدمة كمكتملة') }}</button>
                </div>
            </form>
        </section>
    @else
        <section class="svc-card">
            <span class="svc-badge">{{ __('تم إغلاق هذا الطلب كمكتمل.') }}</span>
        </section>
    @endif
</div>
@endsection
