@extends('layouts.app')
@section('title', __('داشبورد المساهم'))
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ __('مرحبًا') }} {{ $contributor->name }}</h1>
            <p class="cp-subtitle">{{ __('ملخص سريع لحسابك كمساهم، وأهم المؤشرات المرتبطة بأسهمك وحركتك.') }}</p>
        </div>
        <div class="cp-actions">
            <a class="cp-btn cp-btn-primary" href="{{ route('contributor.sell-offers.create') }}"><i class="bi bi-plus-circle-fill"></i>{{ __('عرض بيع جديد') }}</a>
            <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.purchase-orders.create') }}"><i class="bi bi-cart-plus-fill"></i>{{ __('طلب شراء جديد') }}</a>
        </div>
    </section>

    <section class="cp-grid">
        <article class="cp-stat"><i class="bi bi-layers-fill"></i><p class="cp-stat-value">{{ number_format($stats['shares'], 2) }}</p><p class="cp-stat-label">{{ __('عدد الأسهم الخاصة بك') }}</p></article>
        <article class="cp-stat"><i class="bi bi-cash-coin"></i><p class="cp-stat-value">{{ number_format($stats['share_price'], 2) }}</p><p class="cp-stat-label">{{ __('سعر السهم') }}</p></article>
        <article class="cp-stat"><i class="bi bi-pie-chart-fill"></i><p class="cp-stat-value">{{ number_format($stats['ownership_percentage'], 4) }}%</p><p class="cp-stat-label">{{ __('نسبتك من الأسهم الكلية') }}</p></article>
        <article class="cp-stat"><i class="bi bi-calculator-fill"></i><p class="cp-stat-value">{{ number_format($stats['estimated_value'], 2) }}</p><p class="cp-stat-label">{{ __('القيمة التقديرية') }}</p></article>
    </section>

    <section class="cp-grid">
        <article class="cp-stat"><i class="bi bi-megaphone-fill"></i><p class="cp-stat-value">{{ number_format($sellOffersCount) }}</p><p class="cp-stat-label">{{ __('عروض البيع') }}</p></article>
        <article class="cp-stat"><i class="bi bi-cart-check-fill"></i><p class="cp-stat-value">{{ number_format($purchaseOrdersCount) }}</p><p class="cp-stat-label">{{ __('طلبات الشراء') }}</p></article>
        <article class="cp-stat"><i class="bi bi-arrow-left-right"></i><p class="cp-stat-value">{{ number_format($movementsCount) }}</p><p class="cp-stat-label">{{ __('الحركات') }}</p></article>
        <article class="cp-stat"><i class="bi bi-journal-text"></i><p class="cp-stat-value">{{ number_format($stats['total_shares'], 2) }}</p><p class="cp-stat-label">{{ __('إجمالي أسهم المساهمين') }}</p></article>
    </section>
</div>
@endsection
