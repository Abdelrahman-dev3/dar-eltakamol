@extends('layouts.app')
@section('title', __('طلبات الخدمات'))
@include('contributor-portal.partials.styles')

@section('content')
@php
    $statusLabels = \App\Models\Booking::getStatuses();
@endphp

<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ __('طلبات الخدمات') }}</h1>
            <p class="cp-subtitle">{{ __('تابع طلبات الخدمات التي أرسلتها للإدارة وحالتها الحالية.') }}</p>
        </div>
        <div class="cp-actions">
            <a class="cp-btn cp-btn-primary" href="{{ route('contributor.services.request') }}"><i class="bi bi-plus-circle-fill"></i>{{ __('طلب خدمة') }}</a>
        </div>
    </section>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <section class="cp-card">
        <div class="cp-table-wrap">
            <table class="cp-table">
                <thead>
                    <tr>
                        <th>{{ __('الخدمة') }}</th>
                        <th>{{ __('التاريخ المطلوب') }}</th>
                        <th>{{ __('الوقت') }}</th>
                        <th>{{ __('الحالة') }}</th>
                        <th>{{ __('ملاحظات') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($serviceRequests as $request)
                        <tr>
                            <td>{{ optional($request->service)->name ?: __('خدمة غير محددة') }}</td>
                            <td>{{ $request->booking_date?->format('Y-m-d') }}</td>
                            <td>{{ $request->booking_time?->format('H:i') }}</td>
                            <td><span class="cp-badge">{{ $statusLabels[$request->status] ?? $request->status }}</span></td>
                            <td>{{ $request->notes ?: __('لا توجد') }}</td>
                        </tr>
                    @empty
                        <tr><td class="cp-empty" colspan="5">{{ __('لا توجد طلبات خدمات حتى الآن') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $serviceRequests->links() }}
    </section>
</div>
@endsection
