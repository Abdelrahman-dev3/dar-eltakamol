@extends('layouts.app')
@section('title', __('طلبات الشراء'))
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div><h1 class="cp-title">{{ __('طلبات الشراء') }}</h1><p class="cp-subtitle">{{ __('كل طلبات الشراء التي قدمتها، المرتبطة بعروض البيع والمستقلة.') }}</p></div>
        <a class="cp-btn cp-btn-primary" href="{{ route('contributor.purchase-orders.create') }}"><i class="bi bi-plus-circle"></i>{{ __('طلب شراء جديد') }}</a>
    </section>

    <section class="cp-card">
        <h2 class="cp-card-title"><i class="bi bi-cart-plus-fill"></i>{{ __('طلبات الشراء المستقلة') }}</h2>
        <div class="cp-table-wrap"><table class="cp-table"><thead><tr><th>#</th><th>{{ __('التاريخ') }}</th><th>{{ __('الكمية') }}</th><th>{{ __('السعر') }}</th><th>{{ __('الإجمالي') }}</th><th>{{ __('الحالة') }}</th><th>{{ __('الوصف') }}</th></tr></thead><tbody>
            @forelse($independentPurchaseOrders as $order)
                <tr><td>#{{ $order->id }}</td><td>{{ $order->requested_at?->format('Y-m-d') }}</td><td>{{ number_format((float)$order->count, 2) }}</td><td>{{ number_format((float)$order->amount_per_share, 2) }}</td><td>{{ number_format($order->total_amount, 2) }}</td><td><span class="cp-badge">{{ $order->getStatusText() }}</span></td><td>{{ $order->notes ?: __('-') }}</td></tr>
            @empty
                <tr><td class="cp-empty" colspan="7">{{ __('لا توجد طلبات شراء مستقلة حتى الآن') }}</td></tr>
            @endforelse
        </tbody></table></div>
        {{ $independentPurchaseOrders->links() }}
    </section>

    <section class="cp-card">
        <h2 class="cp-card-title"><i class="bi bi-bag-check-fill"></i>{{ __('طلبات الشراء المرتبطة بعروض البيع') }}</h2>
        <div class="cp-table-wrap"><table class="cp-table"><thead><tr><th>#</th><th>{{ __('عرض البيع') }}</th><th>{{ __('التاريخ') }}</th><th>{{ __('الكمية') }}</th><th>{{ __('السعر') }}</th><th>{{ __('الإجمالي') }}</th><th>{{ __('الحالة') }}</th></tr></thead><tbody>
            @forelse($purchaseOrders as $order)
                <tr><td>#{{ $order->id }}</td><td>@if($order->sellShare)<a href="{{ route('sell-shares.show', $order->sellShare) }}">#{{ $order->sale_number }}</a>@else#{{ $order->sale_number }}@endif</td><td>{{ $order->insert_date?->format('Y-m-d') }}</td><td>{{ number_format((float)$order->count, 2) }}</td><td>{{ number_format((float)$order->amount_per_share, 2) }}</td><td>{{ number_format($order->total_amount, 2) }}</td><td><span class="cp-badge">{{ $order->getPoStatusText() }}</span></td></tr>
            @empty
                <tr><td class="cp-empty" colspan="7">{{ __('لا توجد طلبات شراء مرتبطة حتى الآن') }}</td></tr>
            @endforelse
        </tbody></table></div>
        {{ $purchaseOrders->links() }}
    </section>
</div>
@endsection
