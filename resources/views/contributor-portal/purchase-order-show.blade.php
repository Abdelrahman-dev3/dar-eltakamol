@extends('layouts.app')
@section('title', __('تفاصيل طلب الشراء المستقل'))
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ __('طلب شراء مستقل') }} #{{ $order->id }}</h1>
            <p class="cp-subtitle">{{ __('إدارة عروض البيع المقدمة على طلب الشراء والرد عليها كلياً أو جزئياً.') }}</p>
        </div>
        <div class="cp-actions">
            @if($canClose)
                <form method="POST" action="{{ route('contributor.purchase-orders.independent.close', $order) }}">
                    @csrf
                    <button class="cp-btn cp-btn-danger" type="submit"><i class="bi bi-archive-fill"></i>{{ __('إغلاق الطلب') }}</button>
                </form>
            @endif
            <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.purchase-orders') }}"><i class="bi bi-arrow-right"></i>{{ __('رجوع') }}</a>
        </div>
    </section>

    <section class="cp-grid">
        <article class="cp-stat"><i class="bi bi-layers-fill"></i><p class="cp-stat-value">{{ number_format((float) $order->count, 2) }}</p><p class="cp-stat-label">{{ __('الكمية المطلوبة') }}</p></article>
        <article class="cp-stat"><i class="bi bi-check2-circle"></i><p class="cp-stat-value">{{ number_format($acceptedShares, 2) }}</p><p class="cp-stat-label">{{ __('الكمية المقبولة') }}</p></article>
        <article class="cp-stat"><i class="bi bi-hourglass-split"></i><p class="cp-stat-value">{{ number_format($pendingOffersCount) }}</p><p class="cp-stat-label">{{ __('عروض بانتظار الرد') }}</p></article>
        <article class="cp-stat"><i class="bi bi-flag-fill"></i><p class="cp-stat-value">{{ $order->getStatusText() }}</p><p class="cp-stat-label">{{ __('الحالة') }}</p></article>
    </section>

    <section class="cp-card">
        <h2 class="cp-card-title"><i class="bi bi-info-circle-fill"></i>{{ __('تفاصيل الطلب') }}</h2>
        <div class="cp-table-wrap">
            <table class="cp-table">
                <tbody>
                    <tr><th>{{ __('تاريخ الطلب') }}</th><td>{{ $order->requested_at?->format('Y-m-d H:i') ?: '-' }}</td></tr>
                    <tr><th>{{ __('تاريخ النشر') }}</th><td>{{ $order->published_at?->format('Y-m-d H:i') ?: '-' }}</td></tr>
                    <tr><th>{{ __('سعر السهم') }}</th><td>{{ number_format((float) $order->amount_per_share, 2) }}</td></tr>
                    <tr><th>{{ __('القيمة الإجمالية') }}</th><td>{{ number_format($order->total_amount, 2) }}</td></tr>
                    <tr><th>{{ __('ملاحظات') }}</th><td>{{ $order->notes ?: '-' }}</td></tr>
                </tbody>
            </table>
        </div>
    </section>

    <section class="cp-card">
        <h2 class="cp-card-title"><i class="bi bi-inboxes-fill"></i>{{ __('عروض البيع المقدمة') }}</h2>
        <div class="cp-table-wrap">
            <table class="cp-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('المساهم') }}</th>
                        <th>{{ __('الكمية') }}</th>
                        <th>{{ __('السعر') }}</th>
                        <th>{{ __('المقبول') }}</th>
                        <th>{{ __('الحالة') }}</th>
                        <th>{{ __('إجراء') }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($order->sellOffers as $offer)
                    <tr>
                        <td>#{{ $offer->id }}</td>
                        <td>{{ $offer->seller?->name ?: '-' }}</td>
                        <td>{{ number_format((float) $offer->count, 2) }}</td>
                        <td>{{ number_format((float) $offer->amount_per_share, 2) }}</td>
                        <td>{{ number_format((float) $offer->accepted_count, 2) }}</td>
                        <td><span class="cp-badge">{{ $offer->getIndependentOfferStatusText() }}</span></td>
                        <td>
                            @if($offer->independent_offer_status === \App\Models\SellShares::INDEPENDENT_STATUS_PENDING && (int) $order->status === \App\Models\IndependentPurchaseOrder::STATUS_PUBLISHED)
                                @php $remainingForOffer = min((float) $offer->count, (float) $order->remaining_shares); @endphp
                                <form class="cp-form" method="POST" action="{{ route('contributor.purchase-orders.independent.sell-offers.respond', [$order, $offer]) }}">
                                    @csrf
                                    @if($remainingForOffer > 0)
                                        <div class="cp-field">
                                            <label class="cp-label">{{ __('الكمية المقبولة') }}</label>
                                            <input class="cp-input" name="accepted_count" type="number" min="0.01" max="{{ $remainingForOffer }}" step="0.01" value="{{ old('accepted_count', $remainingForOffer) }}">
                                            @error('accepted_count')<span class="cp-error">{{ $message }}</span>@enderror
                                        </div>
                                    @else
                                        <div class="cp-empty">{{ __('اكتملت الكمية المطلوبة، يمكن رفض العرض المتبقي.') }}</div>
                                    @endif
                                    <div class="cp-actions">
                                        @if($remainingForOffer > 0)
                                            <button class="cp-btn cp-btn-primary" type="submit" name="decision" value="accept"><i class="bi bi-check2-circle"></i>{{ __('قبول') }}</button>
                                        @endif
                                        <button class="cp-btn cp-btn-danger" type="submit" name="decision" value="reject"><i class="bi bi-x-circle"></i>{{ __('رفض') }}</button>
                                    </div>
                                </form>
                            @else
                                <span class="cp-badge">{{ $offer->responded_at?->format('Y-m-d') ?: __('تمت المعالجة') }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="cp-empty">{{ __('لم يقدم أي مساهم عرض بيع على هذا الطلب بعد.') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
