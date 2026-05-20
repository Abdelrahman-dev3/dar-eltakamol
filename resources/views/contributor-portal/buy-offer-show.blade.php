@extends('layouts.app')
@section('title', __('تفاصيل عرض الشراء'))
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ __('عرض شراء') }} #{{ $order->id }}</h1>
            <p class="cp-subtitle">{{ __('تفاصيل طلب الشراء المستقل المنشور وإمكانية تقديم عرض بيع مرتبط به.') }}</p>
        </div>
        <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.buy-offers') }}"><i class="bi bi-arrow-right"></i>{{ __('رجوع') }}</a>
    </section>

    <section class="cp-grid">
        <article class="cp-stat"><i class="bi bi-layers-fill"></i><p class="cp-stat-value">{{ number_format((float) $order->count, 2) }}</p><p class="cp-stat-label">{{ __('الكمية المطلوبة') }}</p></article>
        <article class="cp-stat"><i class="bi bi-cash-stack"></i><p class="cp-stat-value">{{ number_format((float) $order->amount_per_share, 2) }}</p><p class="cp-stat-label">{{ __('السعر المقترح') }}</p></article>
        <article class="cp-stat"><i class="bi bi-check2-circle"></i><p class="cp-stat-value">{{ number_format((float) $order->accepted_shares, 2) }}</p><p class="cp-stat-label">{{ __('الكمية المقبولة') }}</p></article>
        <article class="cp-stat"><i class="bi bi-hourglass-split"></i><p class="cp-stat-value">{{ number_format((float) $order->remaining_shares, 2) }}</p><p class="cp-stat-label">{{ __('المتبقي') }}</p></article>
    </section>

    <section class="cp-card">
        <h2 class="cp-card-title"><i class="bi bi-info-circle-fill"></i>{{ __('تفاصيل الطلب') }}</h2>
        <div class="cp-table-wrap">
            <table class="cp-table">
                <tbody>
                    <tr><th>{{ __('تاريخ النشر') }}</th><td>{{ $order->published_at?->format('Y-m-d H:i') ?: '-' }}</td></tr>
                    <tr><th>{{ __('القيمة الإجمالية') }}</th><td>{{ number_format($order->total_amount, 2) }}</td></tr>
                    <tr><th>{{ __('الحالة') }}</th><td><span class="cp-badge">{{ $order->getStatusText() }}</span></td></tr>
                    <tr><th>{{ __('ملاحظات') }}</th><td>{{ $order->notes ?: '-' }}</td></tr>
                </tbody>
            </table>
        </div>
    </section>

    <section class="cp-card">
        <h2 class="cp-card-title"><i class="bi bi-send-check-fill"></i>{{ __('تقديم عرض بيع') }}</h2>
        @if($existingOffer)
            <div class="cp-empty">
                {{ __('لديك عرض بيع مقدم على هذا الطلب.') }}
                <span class="cp-badge">{{ $existingOffer->getIndependentOfferStatusText() }}</span>
            </div>
        @elseif($availableShares <= 0)
            <div class="cp-empty">{{ __('لا توجد كمية متاحة لتقديم عرض بيع حالياً.') }}</div>
        @else
            <form class="cp-form" method="POST" action="{{ route('contributor.buy-offers.sell-offers.store', $order) }}">
                @csrf
                <div class="cp-field">
                    <label class="cp-label">{{ __('عدد الأسهم') }}</label>
                    <input class="cp-input" name="count" type="number" min="0.01" max="{{ $availableShares }}" step="0.01" value="{{ old('count') }}" required>
                    @error('count')<span class="cp-error">{{ $message }}</span>@enderror
                </div>
                <div class="cp-field">
                    <label class="cp-label">{{ __('سعر السهم') }}</label>
                    <input class="cp-input" name="amount_per_share" type="number" min="0.01" step="0.01" value="{{ old('amount_per_share', $order->amount_per_share) }}" required>
                    @error('amount_per_share')<span class="cp-error">{{ $message }}</span>@enderror
                </div>
                <div class="cp-field full">
                    <label class="cp-label">{{ __('ملاحظات') }}</label>
                    <textarea class="cp-textarea" name="notes">{{ old('notes') }}</textarea>
                    @error('notes')<span class="cp-error">{{ $message }}</span>@enderror
                </div>
                <div class="cp-actions">
                    <button class="cp-btn cp-btn-primary" type="submit"><i class="bi bi-check2-circle"></i>{{ __('تقديم عرض البيع') }}</button>
                </div>
            </form>
        @endif
    </section>
</div>
@endsection
