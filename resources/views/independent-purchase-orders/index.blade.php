@extends('layouts.app')
@section('title', __('طلبات الشراء المستقلة'))
@include('shares-pos.partials.styles')

@section('content')
<div class="st-page">
    <section class="st-hero">
        <div>
            <p class="st-hero-kicker">{{ __('إدارة الطلبات') }}</p>
            <h1 class="st-hero-title">{{ __('طلبات الشراء المستقلة') }}</h1>
            <p class="st-hero-subtitle">{{ __('طلبات شراء قدمها المساهمون دون ربطها بعرض بيع محدد، ويمكن نشرها لتظهر في بوابة المساهمين.') }}</p>
        </div>
    </section>

    <section class="st-stats-grid">
        <article class="st-stat"><span class="st-stat-icon"><i class="bi bi-cart-plus-fill"></i></span><p class="st-stat-value">{{ number_format($stats['total_count']) }}</p><p class="st-stat-label">{{ __('إجمالي الطلبات') }}</p></article>
        <article class="st-stat"><span class="st-stat-icon"><i class="bi bi-hourglass-split"></i></span><p class="st-stat-value">{{ number_format($stats['pending_count']) }}</p><p class="st-stat-label">{{ __('قيد الانتظار') }}</p></article>
        <article class="st-stat"><span class="st-stat-icon"><i class="bi bi-broadcast-pin"></i></span><p class="st-stat-value">{{ number_format($stats['published_count']) }}</p><p class="st-stat-label">{{ __('منشور') }}</p></article>
        <article class="st-stat"><span class="st-stat-icon"><i class="bi bi-layers-fill"></i></span><p class="st-stat-value">{{ number_format($stats['total_shares'], 2) }}</p><p class="st-stat-label">{{ __('إجمالي الأسهم المطلوبة') }}</p></article>
    </section>

    <section class="st-card">
        <div class="st-card-header">
            <div>
                <h2 class="st-card-title">{{ __('قائمة الطلبات المستقلة') }}</h2>
                <p class="st-card-subtitle">{{ __('غيّر الحالة إلى منشور ليظهر الطلب لدى بقية المساهمين ضمن عروض الشراء.') }}</p>
            </div>
        </div>
        <div class="st-list">
            @forelse($orders as $order)
                <article class="st-row">
                    <div class="st-row-main">
                        <div class="st-row-icon"><i class="bi bi-cart-plus-fill"></i></div>
                        <div>
                            <h3 class="st-row-title">#{{ $order->id }} - {{ $order->contributor?->name }}</h3>
                            <div class="st-row-meta">
                                <span><i class="bi bi-calendar-event"></i>{{ $order->requested_at?->format('Y-m-d') }}</span>
                                <span><i class="bi bi-layers"></i>{{ number_format((float) $order->count, 2) }} {{ __('سهم') }}</span>
                                <span><i class="bi bi-cash"></i>{{ number_format((float) $order->amount_per_share, 2) }}</span>
                                <span><i class="bi bi-inboxes"></i>{{ number_format($order->sell_offers_count) }} {{ __('عرض بيع') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="st-row-actions">
                        <span class="st-badge st-badge-warning">{{ $order->getStatusText() }}</span>
                        <a href="{{ route('independent-purchase-orders.show', $order) }}" class="st-icon-btn st-icon-btn-info" title="{{ __('عرض') }}"><i class="bi bi-eye-fill"></i></a>
                    </div>
                </article>
            @empty
                <div class="st-empty"><i class="bi bi-inbox"></i><p>{{ __('لا توجد طلبات شراء مستقلة حتى الآن') }}</p></div>
            @endforelse
        </div>
        {{ $orders->links() }}
    </section>
</div>
@endsection
