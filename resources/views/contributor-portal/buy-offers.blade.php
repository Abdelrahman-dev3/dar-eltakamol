@extends('layouts.app')
@section('title', __('عروض الشراء'))
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ __('عروض الشراء') }}</h1>
            <p class="cp-subtitle">{{ __('طلبات الشراء المستقلة المنشورة من مساهمين آخرين، ويمكنك تقديم عرض بيع مناسب عليها.') }}</p>
        </div>
    </section>

    <section class="cp-card">
        <div class="cp-table-wrap">
            <table class="cp-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('تاريخ النشر') }}</th>
                        <th>{{ __('الكمية المطلوبة') }}</th>
                        <th>{{ __('السعر المقترح') }}</th>
                        <th>{{ __('المتبقي') }}</th>
                        <th>{{ __('عروض البيع') }}</th>
                        <th>{{ __('إجراءات') }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->published_at?->format('Y-m-d') ?: $order->requested_at?->format('Y-m-d') }}</td>
                        <td>{{ number_format((float) $order->count, 2) }}</td>
                        <td>{{ number_format((float) $order->amount_per_share, 2) }}</td>
                        <td>{{ number_format((float) $order->remaining_shares, 2) }}</td>
                        <td>{{ number_format($order->sell_offers_count) }}</td>
                        <td>
                            <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.buy-offers.show', $order) }}">
                                <i class="bi bi-eye-fill"></i>{{ __('عرض') }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="cp-empty">{{ __('لا توجد عروض شراء منشورة حالياً.') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $orders->links() }}
    </section>
</div>
@endsection
