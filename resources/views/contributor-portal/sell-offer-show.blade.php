@extends('layouts.app')
@section('title', __('تفاصيل عرض البيع'))
@include('contributor-portal.partials.styles')

@php
    $orders = $sellShare->sharesPOs;
    $acceptedOrders = $orders->where('accept', true)->count();
    $pendingOrders = $orders->where('accept', false)->count();
    $orderedShares = $orders->sum(fn ($order) => (float) $order->count);
    $eligibleOrders = $orders->reject(fn ($order) => in_array((int) $order->po_status, [\App\Models\SharesPO::PO_STATUS_COMPLETED, \App\Models\SharesPO::PO_STATUS_REJECTED], true));
    $highestPurchasePrice = (float) ($eligibleOrders->max(fn ($order) => (float) $order->amount_per_share) ?? 0);
    $priceLevels = $eligibleOrders->map(fn ($order) => number_format((float) $order->amount_per_share, 2, '.', ''))->unique();
    $hasMixedPrices = $priceLevels->count() > 1;
@endphp

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ __('تفاصيل عرض البيع') }} #{{ $sellShare->id }}</h1>
            <p class="cp-subtitle">{{ __('تفاصيل العرض الخاص بك وطلبات الشراء المرتبطة به.') }}</p>
        </div>
        <div class="cp-actions">
            @if($canEditOffer)
                <a class="cp-btn cp-btn-primary" href="{{ route('contributor.sell-offers.edit', $sellShare) }}"><i class="bi bi-pencil-square"></i>{{ __('تعديل العرض') }}</a>
            @endif
            @if($canSettleOffer)
                <form class="cp-inline-form" method="POST" action="{{ route('contributor.sell-offers.settle', $sellShare) }}">
                    @csrf
                    <button class="cp-btn cp-btn-primary" type="submit" data-confirm="{{ __('سيتم قبول الطلبات المتساوية تلقائياً، ولن تتم التسوية إذا بقيت طلبات بسعر أقل من أعلى سعر مقدم. هل تريد المتابعة؟') }}">
                        <i class="bi bi-diagram-3-fill"></i>{{ __('تسوية العرض') }}
                    </button>
                </form>
            @endif
            <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.sell-offers') }}"><i class="bi bi-arrow-right"></i>{{ __('رجوع للعروض') }}</a>
        </div>
    </section>

    <section class="cp-grid">
        <article class="cp-stat"><i class="bi bi-layers-fill"></i><p class="cp-stat-value">{{ number_format((float) $sellShare->count, 2) }}</p><p class="cp-stat-label">{{ __('عدد الأسهم المعروضة') }}</p></article>
        <article class="cp-stat"><i class="bi bi-cash-coin"></i><p class="cp-stat-value">{{ number_format((float) $sellShare->amount_per_share, 2) }}</p><p class="cp-stat-label">{{ __('السعر لكل سهم') }}</p></article>
        <article class="cp-stat"><i class="bi bi-calculator-fill"></i><p class="cp-stat-value">{{ number_format((float) $sellShare->total_amount, 2) }}</p><p class="cp-stat-label">{{ __('القيمة الإجمالية') }}</p></article>
        <article class="cp-stat"><i class="bi bi-cart-check-fill"></i><p class="cp-stat-value">{{ number_format($orders->count()) }}</p><p class="cp-stat-label">{{ __('طلبات الشراء') }}</p></article>
    </section>

    <section class="cp-card">
        <h2 class="cp-card-title"><i class="bi bi-info-circle-fill"></i>{{ __('بيانات العرض') }}</h2>
        <div class="cp-table-wrap">
            <table class="cp-table">
                <tbody>
                    <tr><th>{{ __('رقم العرض') }}</th><td>#{{ $sellShare->id }}</td><th>{{ __('الحالة') }}</th><td>@include('sell-shares.partials.status-badge', ['status' => $sellShare->ad_status])</td></tr>
                    <tr><th>{{ __('تاريخ الإدراج') }}</th><td>{{ $sellShare->insert_date?->format('Y-m-d H:i') }}</td><th>{{ __('تاريخ الانتهاء') }}</th><td>{{ $sellShare->end_date?->format('Y-m-d') ?? __('بدون انتهاء') }}</td></tr>
                    <tr><th>{{ __('المتاح السنوي بعد هذا العرض') }}</th><td>{{ number_format((float) $annualRemaining, 2) }} {{ __('سهم') }}</td><th>{{ __('آخر تحديث') }}</th><td>{{ $sellShare->updated_at?->format('Y-m-d H:i') }}</td></tr>
                    <tr><th>{{ __('الملاحظات') }}</th><td colspan="3">{{ $sellShare->notes ?: __('لا توجد ملاحظات') }}</td></tr>
                </tbody>
            </table>
        </div>
    </section>

    <section class="cp-grid">
        <article class="cp-stat"><i class="bi bi-check-circle-fill"></i><p class="cp-stat-value">{{ number_format($acceptedOrders) }}</p><p class="cp-stat-label">{{ __('طلبات مقبولة') }}</p></article>
        <article class="cp-stat"><i class="bi bi-hourglass-split"></i><p class="cp-stat-value">{{ number_format($pendingOrders) }}</p><p class="cp-stat-label">{{ __('طلبات قيد الانتظار') }}</p></article>
        <article class="cp-stat"><i class="bi bi-stack"></i><p class="cp-stat-value">{{ number_format((float) $orderedShares, 2) }}</p><p class="cp-stat-label">{{ __('إجمالي الأسهم المطلوبة') }}</p></article>
        <article class="cp-stat"><i class="bi bi-building-fill"></i><p class="cp-stat-value">{{ number_format($sellShare->companyPurchaseObligations->count()) }}</p><p class="cp-stat-label">{{ __('التزامات الشركة') }}</p></article>
    </section>

    @if($currentPhase === \App\Models\TradingPeriod::PHASE_PROCESSING && $orders->count())
        <section class="cp-card">
            <h2 class="cp-card-title"><i class="bi bi-sliders2"></i>{{ __('تسوية الفترة الثانية') }}</h2>
            @if($hasMixedPrices)
                <p class="cp-subtitle">{{ __('يوجد أكثر من سعر مقدم. أعلى سعر حالي هو') }} {{ number_format($highestPurchasePrice, 2) }} {{ __('ريال') }}. {{ __('يمكن للمشترين رفع أسعارهم، ويمكنك رفض الطلبات التي تبقى أقل من أعلى سعر قبل التسوية.') }}</p>
            @else
                <p class="cp-subtitle">{{ __('كل طلبات الشراء المؤهلة تحمل السعر نفسه، وعند تنفيذ التسوية سيتم قبولها كلها وتوزيع الأسهم عليها بالتساوي حسب الكميات المطلوبة.') }}</p>
            @endif
            @error('settlement')<span class="cp-error">{{ $message }}</span>@enderror
            @error('decision')<span class="cp-error">{{ $message }}</span>@enderror
            @error('negotiation')<span class="cp-error">{{ $message }}</span>@enderror
        </section>
    @endif

    <section class="cp-card">
        <h2 class="cp-card-title"><i class="bi bi-bag-check-fill"></i>{{ __('طلبات الشراء المرتبطة') }}</h2>
        <div class="cp-table-wrap">
            <table class="cp-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('المشتري') }}</th>
                        <th>{{ __('الكمية') }}</th>
                        <th>{{ __('المقبول') }}</th>
                        <th>{{ __('السعر') }}</th>
                        <th>{{ __('الإجمالي') }}</th>
                        <th>{{ __('الحالة') }}</th>
                        <th>{{ __('المفاضلة') }}</th>
                        <th>{{ __('إجراءات') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        @php
                            $isRejected = (int) $order->po_status === \App\Models\SharesPO::PO_STATUS_REJECTED;
                            $isCompleted = (int) $order->po_status === \App\Models\SharesPO::PO_STATUS_COMPLETED;
                            $isTopPrice = !$isRejected && $highestPurchasePrice > 0 && (float) $order->amount_per_share >= $highestPurchasePrice;
                            $canRespondOrder = $canSettleOffer && !$isCompleted;
                        @endphp
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->contributor->name ?? __('غير معروف') }}</td>
                            <td>{{ number_format((float) $order->count, 2) }}</td>
                            <td>{{ (float) $order->accepted_count > 0 ? number_format((float) $order->accepted_count, 2) : '-' }}</td>
                            <td>{{ number_format((float) $order->amount_per_share, 2) }}</td>
                            <td>{{ number_format((float) $order->total_amount, 2) }}</td>
                            <td>
                                <span class="cp-badge">
                                    {{ $order->accept ? __('مقبول') : $order->getPoStatusText() }}
                                </span>
                            </td>
                            <td>
                                @if($isRejected)
                                    <span class="cp-badge">{{ __('مرفوض') }}</span>
                                @elseif($isTopPrice)
                                    <span class="cp-badge">{{ __('أعلى سعر') }}</span>
                                @elseif($order->price_negotiation_requested_at)
                                    <span class="cp-badge">{{ __('بانتظار رفع السعر') }}</span>
                                @elseif($hasMixedPrices)
                                    <span class="cp-badge">{{ __('أقل من أعلى سعر') }}</span>
                                @else
                                    <span class="cp-badge">{{ __('متساو') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($canRespondOrder)
                                    <div class="cp-actions">
                                        @if(!$isRejected && $isTopPrice)
                                            <form class="cp-inline-form" method="POST" action="{{ route('contributor.sell-offers.purchase-orders.respond', [$sellShare, $order]) }}">
                                                @csrf
                                                <input class="cp-input" name="accepted_count" type="number" min="0.01" max="{{ (float) $order->count }}" step="0.01" value="{{ old('accepted_count', (float) $order->accepted_count > 0 ? $order->accepted_count : $order->count) }}" required title="{{ __('الكمية المقبولة') }}">
                                                <button class="cp-btn cp-btn-success cp-btn-sm" type="submit" name="decision" value="accept">
                                                    <i class="bi bi-check2-circle"></i>{{ __('قبول') }}
                                                </button>
                                            </form>
                                            @error('accepted_count')<span class="cp-error">{{ $message }}</span>@enderror
                                        @endif
                                        @if(!$isRejected && !$isTopPrice && $hasMixedPrices)
                                            <form class="cp-inline-form" method="POST" action="{{ route('contributor.sell-offers.purchase-orders.negotiate', [$sellShare, $order]) }}">
                                                @csrf
                                                <button class="cp-btn cp-btn-secondary cp-btn-sm" type="submit" data-confirm="{{ __('سيظهر للمشتري طلب رفع السعر، وإذا لم يرفع السعر يمكنك رفض الطلب قبل التسوية. هل تريد المتابعة؟') }}">
                                                    <i class="bi bi-chat-dots"></i>{{ $order->price_negotiation_requested_at ? __('إعادة طلب الرفع') : __('طلب رفع السعر') }}
                                                </button>
                                            </form>
                                        @endif
                                        @if(!$isRejected)
                                            <form class="cp-inline-form" method="POST" action="{{ route('contributor.sell-offers.purchase-orders.respond', [$sellShare, $order]) }}">
                                                @csrf
                                                <button class="cp-btn cp-btn-danger cp-btn-sm" type="submit" name="decision" value="reject" data-confirm="{{ __('هل تريد رفض طلب الشراء؟') }}">
                                                    <i class="bi bi-x-circle"></i>{{ __('رفض') }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="cp-empty">{{ __('لا توجد طلبات شراء على هذا العرض حتى الآن') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-confirm]').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    const message = this.getAttribute('data-confirm');

                    if (message && !window.confirm(message)) {
                        event.preventDefault();
                    }
                });
            });
        });
    </script>
@endpush
