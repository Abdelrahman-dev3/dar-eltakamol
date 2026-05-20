@extends('layouts.app')
@section('title', __('طلبات الخدمات'))

@php
    $totalBookings = $bookings->count();
    $receivedBookings = $bookings->where('status', \App\Models\Booking::STATUS_RECEIVED)->count() + $bookings->where('status', 'pending')->count();
    $progressBookings = $bookings->where('status', \App\Models\Booking::STATUS_IN_PROGRESS)->count() + $bookings->where('status', 'confirmed')->count();
    $completedBookings = $bookings->where('status', \App\Models\Booking::STATUS_COMPLETED)->count();
@endphp

@push('styles')
<style>
    .svc-page { display: flex; flex-direction: column; gap: 1.25rem; }
    .svc-hero, .svc-card, .svc-stat { background: rgba(255,255,255,.96); border: 1px solid rgba(170,134,63,.14); border-radius: 1.1rem; box-shadow: 0 .8rem 1.8rem rgba(15,23,42,.06); }
    .svc-hero { padding: 1.5rem; display: flex; justify-content: space-between; gap: 1rem; flex-wrap: wrap; align-items: center; }
    .svc-title { margin: 0; color: var(--text-primary); font-size: 2rem; font-weight: 900; }
    .svc-subtitle { margin: .35rem 0 0; color: var(--text-secondary); line-height: 1.7; }
    .svc-actions { display: flex; gap: .6rem; flex-wrap: wrap; align-items: center; }
    .svc-btn { display: inline-flex; align-items: center; justify-content: center; gap: .45rem; min-height: 2.65rem; padding: .65rem 1rem; border-radius: .8rem; border: 0; text-decoration: none !important; font-weight: 800; }
    .svc-btn-primary { background: linear-gradient(135deg, var(--primary-color), #c49b48); color: #fff; }
    .svc-btn-secondary { background: rgba(170,134,63,.08); color: var(--primary-color); border: 1px solid rgba(170,134,63,.14); }
    .svc-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1rem; }
    .svc-stat { padding: 1rem; }
    .svc-stat i { width: 2.7rem; height: 2.7rem; display: inline-flex; align-items: center; justify-content: center; border-radius: .8rem; background: rgba(170,134,63,.1); color: var(--primary-color); margin-bottom: .7rem; }
    .svc-stat-value { margin: 0; color: var(--text-primary); font-size: 1.6rem; font-weight: 900; }
    .svc-stat-label { margin: .25rem 0 0; color: var(--text-secondary); font-weight: 700; }
    .svc-card { padding: 1.1rem; }
    .svc-table-wrap { overflow-x: auto; border: 1px solid rgba(170,134,63,.12); border-radius: 1rem; }
    .svc-table { width: 100%; border-collapse: collapse; }
    .svc-table th, .svc-table td { padding: .85rem; border-bottom: 1px solid rgba(226,232,240,.9); color: var(--text-primary); vertical-align: middle; }
    .svc-table th { background: #fcf7ee; color: var(--text-secondary); white-space: nowrap; }
    .svc-badge { display: inline-flex; align-items: center; padding: .35rem .6rem; border-radius: 999px; background: rgba(170,134,63,.09); color: var(--primary-color); font-weight: 800; white-space: nowrap; }
    .svc-empty { padding: 2rem; text-align: center; color: var(--text-secondary); }
    @media (max-width: 1000px) { .svc-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 680px) { .svc-grid { grid-template-columns: 1fr; } .svc-btn { width: 100%; } }
</style>
@endpush

@section('content')
<div class="svc-page">
    <section class="svc-hero">
        <div>
            <h1 class="svc-title">{{ __('طلبات الخدمات') }}</h1>
            <p class="svc-subtitle">{{ __('كل طلبات الخدمات المقدمة من المساهمين مع إمكانية المتابعة والرد والإغلاق.') }}</p>
        </div>
        <div class="svc-actions">
            <a href="{{ route('bookings.create') }}" class="svc-btn svc-btn-primary"><i class="bi bi-plus-circle-fill"></i>{{ __('إضافة طلب') }}</a>
        </div>
    </section>

    <section class="svc-grid">
        <article class="svc-stat"><i class="bi bi-inboxes-fill"></i><p class="svc-stat-value">{{ number_format($totalBookings) }}</p><p class="svc-stat-label">{{ __('إجمالي الطلبات') }}</p></article>
        <article class="svc-stat"><i class="bi bi-check2-circle"></i><p class="svc-stat-value">{{ number_format($receivedBookings) }}</p><p class="svc-stat-label">{{ __('تم استلام الطلب') }}</p></article>
        <article class="svc-stat"><i class="bi bi-arrow-repeat"></i><p class="svc-stat-value">{{ number_format($progressBookings) }}</p><p class="svc-stat-label">{{ __('قيد التقدم') }}</p></article>
        <article class="svc-stat"><i class="bi bi-patch-check-fill"></i><p class="svc-stat-value">{{ number_format($completedBookings) }}</p><p class="svc-stat-label">{{ __('مكتملة') }}</p></article>
    </section>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <section class="svc-card">
        <div class="svc-table-wrap">
            <table class="svc-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('الخدمة') }}</th>
                        <th>{{ __('المساهم') }}</th>
                        <th>{{ __('تاريخ تقديم الطلب') }}</th>
                        <th>{{ __('الحالة') }}</th>
                        <th>{{ __('المتابعات') }}</th>
                        <th>{{ __('إجراءات') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>#{{ $booking->id }}</td>
                            <td>{{ $booking->service?->name ?: '-' }}</td>
                            <td>{{ $booking->user?->name ?: '-' }}</td>
                            <td>{{ $booking->created_at?->format('Y-m-d H:i') }}</td>
                            <td><span class="svc-badge">{{ $booking->status_text }}</span></td>
                            <td>{{ number_format($booking->messages_count) }}</td>
                            <td>
                                <div class="svc-actions">
                                    <a href="{{ route('bookings.show', $booking->id) }}" class="svc-btn svc-btn-secondary"><i class="bi bi-eye-fill"></i>{{ __('تفاصيل') }}</a>
                                    <a href="{{ route('bookings.edit', $booking->id) }}" class="svc-btn svc-btn-secondary"><i class="bi bi-pencil-square"></i>{{ __('تعديل') }}</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="svc-empty" colspan="7">{{ __('لا توجد طلبات خدمات حتى الآن') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
