@extends('layouts.app')
@section('title', __('طلبات الشراء'))
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ __('طلبات الشراء') }}</h1>
            <p class="cp-subtitle">{{ __('كل طلبات الشراء التي قدمتها، المرتبطة بعروض البيع والمستقلة.') }}</p>
        </div>
        <a class="cp-btn cp-btn-primary" href="{{ route('contributor.purchase-orders.create') }}"><i class="bi bi-plus-circle"></i>{{ __('طلب شراء جديد') }}</a>
    </section>

    <section class="cp-card">
        <h2 class="cp-card-title"><i class="bi bi-cart-plus-fill"></i>{{ __('طلبات الشراء المستقلة') }}</h2>
        <div class="cp-table-wrap">
            <table class="cp-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('التاريخ') }}</th>
                        <th>{{ __('الكمية') }}</th>
                        <th>{{ __('المقبول') }}</th>
                        <th>{{ __('السعر') }}</th>
                        <th>{{ __('الإجمالي') }}</th>
                        <th>{{ __('الحالة') }}</th>
                        <th>{{ __('عروض البيع') }}</th>
                        <th>{{ __('الوصف') }}</th>
                        <th>{{ __('إجراءات') }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($independentPurchaseOrders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->requested_at?->format('Y-m-d') }}</td>
                        <td>{{ number_format((float) $order->count, 2) }}</td>
                        <td>{{ number_format((float) $order->amount_per_share, 2) }}</td>
                        <td>{{ number_format($order->total_amount, 2) }}</td>
                        <td><span class="cp-badge">{{ $order->getStatusText() }}</span></td>
                        <td>{{ number_format($order->sell_offers_count ?? 0) }}</td>
                        <td>{{ $order->notes ?: '-' }}</td>
                        <td>
                            <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.purchase-orders.independent.show', $order) }}">
                                <i class="bi bi-eye-fill"></i>{{ __('عرض') }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td class="cp-empty" colspan="9">{{ __('لا توجد طلبات شراء مستقلة حتى الآن') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $independentPurchaseOrders->links() }}
    </section>

    <section class="cp-card">
        <h2 class="cp-card-title"><i class="bi bi-bag-check-fill"></i>{{ __('طلبات الشراء المرتبطة بعروض البيع') }}</h2>
        <div class="cp-table-wrap">
            <table class="cp-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('عرض البيع') }}</th>
                        <th>{{ __('التاريخ') }}</th>
                        <th>{{ __('الكمية') }}</th>
                        <th>{{ __('السعر') }}</th>
                        <th>{{ __('الإجمالي') }}</th>
                        <th>{{ __('الحالة') }}</th>
                        <th>{{ __('تحديث السعر') }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($purchaseOrders as $order)
                    @php
                        $activeOfferOrders = $order->sellShare?->sharesPOs?->reject(fn ($offerOrder) => in_array((int) $offerOrder->po_status, [\App\Models\SharesPO::PO_STATUS_COMPLETED, \App\Models\SharesPO::PO_STATUS_REJECTED], true)) ?? collect();
                        $highestOfferPrice = (float) ($activeOfferOrders->max(fn ($offerOrder) => (float) $offerOrder->amount_per_share) ?? 0);
                        $suggestedPrice = max((float) $order->amount_per_share, $highestOfferPrice);
                        $canRaisePrice = $currentPhase === \App\Models\TradingPeriod::PHASE_PROCESSING
                            && !$order->accept
                            && !in_array((int) $order->po_status, [\App\Models\SharesPO::PO_STATUS_COMPLETED, \App\Models\SharesPO::PO_STATUS_REJECTED], true)
                            && $order->sellShare
                            && !in_array((int) $order->sellShare->ad_status, [\App\Models\SellShares::AD_STATUS_COMPLETED, \App\Models\SellShares::AD_STATUS_CANCELLED], true);
                    @endphp
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>
                            @if($order->sellShare)
                                <a href="{{ route('sell-shares.show', $order->sellShare) }}">#{{ $order->sale_number }}</a>
                            @else
                                #{{ $order->sale_number }}
                            @endif
                        </td>
                        <td>{{ $order->insert_date?->format('Y-m-d') }}</td>
                        <td>{{ number_format((float) $order->count, 2) }}</td>
                        <td>{{ (float) $order->accepted_count > 0 ? number_format((float) $order->accepted_count, 2) : '-' }}</td>
                        <td>{{ number_format((float) $order->amount_per_share, 2) }}</td>
                        <td>{{ number_format($order->total_amount, 2) }}</td>
                        <td><span class="cp-badge">{{ $order->getPoStatusText() }}</span></td>
                        <td>
                            @if($order->price_negotiation_requested_at)
                                <div class="cp-error" style="margin-bottom: .45rem;">
                                    {{ $order->price_negotiation_message ?: __('طلب البائع رفع السعر وإلا قد يتم رفض الطلب.') }}
                                    @if($highestOfferPrice > 0)
                                        {{ __('أعلى سعر حالي:') }} {{ number_format($highestOfferPrice, 2) }}
                                    @endif
                                </div>
                            @endif
                            @if($canRaisePrice)
                                <form class="cp-inline-form" method="POST" action="{{ route('contributor.purchase-orders.price.update', $order) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input class="cp-input" name="amount_per_share" type="number" min="{{ $suggestedPrice }}" step="0.01" value="{{ old('amount_per_share', $suggestedPrice) }}" required>
                                    <button class="cp-btn cp-btn-primary cp-btn-sm" type="submit">
                                        <i class="bi bi-arrow-up-circle"></i>{{ __('رفع السعر') }}
                                    </button>
                                </form>
                                @error('amount_per_share')<span class="cp-error">{{ $message }}</span>@enderror
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td class="cp-empty" colspan="9">{{ __('لا توجد طلبات شراء مرتبطة حتى الآن') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $purchaseOrders->links() }}
    </section>
</div>
@endsection
