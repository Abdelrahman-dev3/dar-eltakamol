@extends('layouts.app')
@section('title', __('طلب شراء مستقل'))
@include('shares-pos.partials.styles')

@section('content')
<div class="st-page">
    <section class="st-hero">
        <div>
            <p class="st-hero-kicker">{{ __('طلب مستقل') }}</p>
            <h1 class="st-hero-title">{{ __('طلب شراء مستقل') }} #{{ $order->id }}</h1>
            <p class="st-hero-subtitle">{{ __('طلب شراء غير مرتبط بعرض بيع محدد.') }}</p>
        </div>
        <div class="st-hero-actions"><a href="{{ route('independent-purchase-orders.index') }}" class="st-btn st-btn-secondary"><i class="bi bi-arrow-right"></i>{{ __('رجوع') }}</a></div>
    </section>

    <section class="st-card">
        <div class="st-info-grid">
            <div class="st-info-item"><span class="st-info-label">{{ __('المساهم') }}</span><div class="st-info-value">{{ $order->contributor?->name }}</div></div>
            <div class="st-info-item"><span class="st-info-label">{{ __('التاريخ') }}</span><div class="st-info-value">{{ $order->requested_at?->format('Y-m-d H:i') }}</div></div>
            <div class="st-info-item"><span class="st-info-label">{{ __('عدد الأسهم') }}</span><div class="st-info-value">{{ number_format((float)$order->count, 2) }}</div></div>
            <div class="st-info-item"><span class="st-info-label">{{ __('سعر السهم') }}</span><div class="st-info-value">{{ number_format((float)$order->amount_per_share, 2) }}</div></div>
            <div class="st-info-item"><span class="st-info-label">{{ __('الإجمالي') }}</span><div class="st-info-value">{{ number_format($order->total_amount, 2) }}</div></div>
            <div class="st-info-item"><span class="st-info-label">{{ __('الحالة') }}</span><div class="st-info-value">{{ $order->getStatusText() }}</div></div>
            <div class="st-info-item st-info-wide"><span class="st-info-label">{{ __('الوصف') }}</span><div class="st-info-value">{{ $order->notes ?: __('-') }}</div></div>
        </div>
    </section>

    <section class="st-card">
        <div class="st-card-header"><div><h2 class="st-card-title">{{ __('تحديث الحالة') }}</h2></div></div>
        <form class="st-form" method="POST" action="{{ route('independent-purchase-orders.update', $order) }}">
            @csrf
            @method('PUT')
            <div class="st-form-grid">
                <div class="st-field">
                    <label class="st-label">{{ __('الحالة') }}</label>
                    <select class="st-select" name="status" required>
                        <option value="{{ \App\Models\IndependentPurchaseOrder::STATUS_PENDING }}" @selected((int)$order->status === \App\Models\IndependentPurchaseOrder::STATUS_PENDING)>{{ __('في الانتظار') }}</option>
                        <option value="{{ \App\Models\IndependentPurchaseOrder::STATUS_REVIEW }}" @selected((int)$order->status === \App\Models\IndependentPurchaseOrder::STATUS_REVIEW)>{{ __('قيد المراجعة') }}</option>
                        <option value="{{ \App\Models\IndependentPurchaseOrder::STATUS_COMPLETED }}" @selected((int)$order->status === \App\Models\IndependentPurchaseOrder::STATUS_COMPLETED)>{{ __('مكتمل') }}</option>
                        <option value="{{ \App\Models\IndependentPurchaseOrder::STATUS_CANCELLED }}" @selected((int)$order->status === \App\Models\IndependentPurchaseOrder::STATUS_CANCELLED)>{{ __('ملغي') }}</option>
                    </select>
                    @error('status')<span class="st-error">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="st-form-actions"><button class="st-btn st-btn-primary" type="submit"><i class="bi bi-check2-circle"></i>{{ __('حفظ') }}</button></div>
        </form>
    </section>
</div>
@endsection
