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
        <article class="cp-stat"><i class="bi bi-newspaper"></i><p class="cp-stat-value">{{ number_format($newsCount) }}</p><p class="cp-stat-label">{{ __('الأخبار') }}</p></article>
        <article class="cp-stat"><i class="bi bi-folder2-open"></i><p class="cp-stat-value">{{ number_format($filesCount) }}</p><p class="cp-stat-label">{{ __('الملفات') }}</p></article>
        <article class="cp-stat"><i class="bi bi-journal-richtext"></i><p class="cp-stat-value">{{ number_format($regulationsCount) }}</p><p class="cp-stat-label">{{ __('اللوائح') }}</p></article>
        <article class="cp-stat"><i class="bi bi-headset"></i><p class="cp-stat-value">{{ number_format($serviceRequestsCount) }}</p><p class="cp-stat-label">{{ __('طلبات الخدمات') }}</p></article>
        <article class="cp-stat"><i class="bi bi-journal-text"></i><p class="cp-stat-value">{{ number_format($stats['total_shares'], 2) }}</p><p class="cp-stat-label">{{ __('إجمالي أسهم المساهمين') }}</p></article>
    </section>

    <section class="cp-card">
        <div class="cp-section-head">
            <h2 class="cp-card-title"><i class="bi bi-newspaper"></i>{{ __('آخر الأخبار') }}</h2>
            <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.news') }}">{{ __('عرض الكل') }}</a>
        </div>
        <div class="cp-news-list compact">
            @forelse($latestNews as $item)
                <article class="cp-news-item">
                    <div class="cp-news-icon"><i class="fa {{ $item->file_icon }}"></i></div>
                    <div class="cp-news-body">
                        <h3 class="cp-news-title">{{ $item->name }}</h3>
                        <div class="cp-news-meta">
                            <span><i class="bi bi-calendar3"></i>{{ $item->created_at?->format('Y-m-d H:i') }}</span>
                            <span><i class="bi bi-paperclip"></i>{{ $item->original_filename }}</span>
                        </div>
                    </div>
                    <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.news.show', $item) }}">{{ __('عرض') }}</a>
                </article>
            @empty
                <div class="cp-empty">{{ __('لا توجد أخبار مخصصة لحسابك حاليا') }}</div>
            @endforelse
        </div>
    </section>
</div>
@endsection
