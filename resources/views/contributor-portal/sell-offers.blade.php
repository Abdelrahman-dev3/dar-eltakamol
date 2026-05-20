@extends('layouts.app')
@section('title', __('عروض البيع الخاصة بي'))
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ __('عروض البيع الخاصة بي') }}</h1>
            <p class="cp-subtitle">{{ __('كل عروض البيع التي قدمتها مع حالتها وعدد طلبات الشراء عليها.') }}</p>
        </div>
        <div class="cp-actions">
            @if($canCreate)
                <a class="cp-btn cp-btn-primary" href="{{ route('contributor.sell-offers.create') }}"><i class="bi bi-plus-circle-fill"></i>{{ __('إنشاء عرض بيع') }}</a>
            @endif
        </div>
    </section>

    <section class="cp-card">
        <div class="cp-table-wrap">
            <table class="cp-table">
                <thead>
                    <tr><th>#</th><th>{{ __('التاريخ') }}</th><th>{{ __('الكمية') }}</th><th>{{ __('السعر') }}</th><th>{{ __('الحالة') }}</th><th>{{ __('طلبات شراء') }}</th><th>{{ __('إجراءات') }}</th></tr>
                </thead>
                <tbody>
                @forelse($sellShares as $offer)
                    @php
                        $canEditOffer = $currentPhase === \App\Models\TradingPeriod::PHASE_OFFER
                            && !in_array((int) $offer->ad_status, [\App\Models\SellShares::AD_STATUS_COMPLETED, \App\Models\SellShares::AD_STATUS_CANCELLED], true)
                            && (!$offer->independent_purchase_order_id || $offer->independent_offer_status === \App\Models\SellShares::INDEPENDENT_STATUS_PENDING)
                            && (int) $offer->shares_p_os_count === 0;
                    @endphp
                    <tr>
                        <td>#{{ $offer->id }}</td>
                        <td>{{ $offer->insert_date?->format('Y-m-d') }}</td>
                        <td>{{ number_format((float)$offer->count, 2) }}</td>
                        <td>{{ number_format((float)$offer->amount_per_share, 2) }}</td>
                        <td>@include('sell-shares.partials.status-badge', ['status' => $offer->ad_status])</td>
                        <td>{{ $offer->shares_p_os_count }}</td>
                        <td>
                            <div class="cp-actions">
                                <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.sell-offers.show', $offer) }}" title="{{ __('عرض التفاصيل') }}"><i class="bi bi-eye-fill"></i></a>
                                @if($canEditOffer)
                                    <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.sell-offers.edit', $offer) }}"><i class="bi bi-pencil-square"></i></a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="cp-empty">{{ __('لا توجد عروض بيع.') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $sellShares->links() }}
    </section>
</div>
@endsection
