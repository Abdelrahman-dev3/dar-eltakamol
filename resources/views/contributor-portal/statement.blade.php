@extends('layouts.app')
@section('title', __('كشف الحساب'))
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ __('كشف الحساب') }}</h1>
            <p class="cp-subtitle">{{ __('كل العمليات المرتبطة بك مع التواريخ: عروض البيع، طلبات الشراء، والحركات من وإلى حسابك.') }}</p>
        </div>
    </section>

    <section class="cp-grid">
        <article class="cp-stat"><i class="bi bi-layers-fill"></i><p class="cp-stat-value">{{ number_format($stats['shares'], 2) }}</p><p class="cp-stat-label">{{ __('أسهمك الحالية') }}</p></article>
        <article class="cp-stat"><i class="bi bi-pie-chart-fill"></i><p class="cp-stat-value">{{ number_format($stats['ownership_percentage'], 4) }}%</p><p class="cp-stat-label">{{ __('نسبتك') }}</p></article>
        <article class="cp-stat"><i class="bi bi-cash-coin"></i><p class="cp-stat-value">{{ number_format($stats['share_price'], 2) }}</p><p class="cp-stat-label">{{ __('سعر السهم') }}</p></article>
        <article class="cp-stat"><i class="bi bi-calculator-fill"></i><p class="cp-stat-value">{{ number_format($stats['estimated_value'], 2) }}</p><p class="cp-stat-label">{{ __('القيمة التقديرية') }}</p></article>
    </section>

    <section class="cp-card">
        <h2 class="cp-card-title"><i class="bi bi-megaphone-fill"></i>{{ __('طلبات البيع وعروض الشراء عليها') }}</h2>
        <div class="cp-table-wrap"><table class="cp-table"><thead><tr><th>{{ __('التاريخ') }}</th><th>{{ __('الكمية') }}</th><th>{{ __('السعر') }}</th><th>{{ __('الحالة') }}</th><th>{{ __('طلبات الشراء') }}</th></tr></thead><tbody>
        @forelse($sellShares as $offer)
            <tr><td>{{ $offer->insert_date?->format('Y-m-d') }}</td><td>{{ number_format((float)$offer->count, 2) }}</td><td>{{ number_format((float)$offer->amount_per_share, 2) }}</td><td>@include('sell-shares.partials.status-badge', ['status' => $offer->ad_status])</td><td>{{ $offer->sharesPOs->count() }}</td></tr>
            @foreach($offer->sharesPOs as $po)
                <tr><td colspan="2"><span class="cp-badge">{{ __('طلب شراء') }} #{{ $po->id }}</span></td><td>{{ $po->contributor?->name }}</td><td>{{ number_format((float)$po->count, 2) }}</td><td>{{ $po->insert_date?->format('Y-m-d') }}</td></tr>
            @endforeach
        @empty
            <tr><td colspan="5" class="cp-empty">{{ __('لا توجد عروض بيع.') }}</td></tr>
        @endforelse
        </tbody></table></div>
    </section>

    <section class="cp-card">
        <h2 class="cp-card-title"><i class="bi bi-cart-check-fill"></i>{{ __('طلبات الشراء المرتبطة بعروض البيع') }}</h2>
        <div class="cp-table-wrap"><table class="cp-table"><thead><tr><th>{{ __('التاريخ') }}</th><th>{{ __('العرض') }}</th><th>{{ __('البائع') }}</th><th>{{ __('الكمية') }}</th><th>{{ __('السعر') }}</th></tr></thead><tbody>
        @forelse($purchaseOrders as $order)
            <tr><td>{{ $order->insert_date?->format('Y-m-d') }}</td><td>#{{ $order->sale_number }}</td><td>{{ $order->sellShare?->seller?->name ?: __('غير متاح') }}</td><td>{{ number_format((float)$order->count, 2) }}</td><td>{{ number_format((float)$order->amount_per_share, 2) }}</td></tr>
        @empty
            <tr><td colspan="5" class="cp-empty">{{ __('لا توجد طلبات شراء.') }}</td></tr>
        @endforelse
        </tbody></table></div>
    </section>

    <section class="cp-card">
        <h2 class="cp-card-title"><i class="bi bi-cart-plus-fill"></i>{{ __('طلبات الشراء المستقلة') }}</h2>
        <div class="cp-table-wrap"><table class="cp-table"><thead><tr><th>{{ __('التاريخ') }}</th><th>{{ __('الكمية') }}</th><th>{{ __('السعر') }}</th><th>{{ __('الإجمالي') }}</th><th>{{ __('الحالة') }}</th></tr></thead><tbody>
        @forelse($independentPurchaseOrders as $order)
            <tr><td>{{ $order->requested_at?->format('Y-m-d') }}</td><td>{{ number_format((float)$order->count, 2) }}</td><td>{{ number_format((float)$order->amount_per_share, 2) }}</td><td>{{ number_format($order->total_amount, 2) }}</td><td><span class="cp-badge">{{ $order->getStatusText() }}</span></td></tr>
        @empty
            <tr><td colspan="5" class="cp-empty">{{ __('لا توجد طلبات شراء مستقلة.') }}</td></tr>
        @endforelse
        </tbody></table></div>
    </section>

    <section class="cp-card">
        <h2 class="cp-card-title"><i class="bi bi-arrow-left-right"></i>{{ __('الحركات من وإلى المساهم') }}</h2>
        <div class="cp-table-wrap"><table class="cp-table"><thead><tr><th>{{ __('التاريخ') }}</th><th>{{ __('النوع') }}</th><th>{{ __('من') }}</th><th>{{ __('إلى') }}</th><th>{{ __('الأسهم') }}</th><th>{{ __('الرصيد') }}</th></tr></thead><tbody>
        @forelse($movements as $movement)
            @php $isFrom = (int)$movement->from_contributor_id === (int)$contributor->id; @endphp
            <tr><td>{{ $movement->date?->format('Y-m-d') }}</td><td>@include('shares-trans.partials.type-badge', ['type' => $movement->movement_type])</td><td>{{ $movement->fromContributor?->name ?: '-' }}</td><td>{{ $movement->toContributor?->name ?: '-' }}</td><td>{{ number_format((float)$movement->shares_count, 2) }}</td><td>{{ number_format((float)($isFrom ? $movement->from_balance_after : $movement->to_balance_after), 2) }}</td></tr>
        @empty
            <tr><td colspan="6" class="cp-empty">{{ __('لا توجد حركات.') }}</td></tr>
        @endforelse
        </tbody></table></div>
    </section>
</div>
@endsection
