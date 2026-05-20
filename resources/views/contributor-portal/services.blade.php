@extends('layouts.app')
@section('title', __('طلبات الخدمات'))
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ __('طلبات الخدمات') }}</h1>
            <p class="cp-subtitle">{{ __('تابع طلبات الخدمات والردود الواردة من الإدارة حتى اكتمال الطلب.') }}</p>
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
                        <th>{{ __('اسم الخدمة') }}</th>
                        <th>{{ __('تاريخ تقديم الطلب') }}</th>
                        <th>{{ __('حالة الطلب') }}</th>
                        <th>{{ __('إجراءات') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($serviceRequests as $request)
                        <tr>
                            <td>{{ optional($request->service)->name ?: __('خدمة غير محددة') }}</td>
                            <td>{{ $request->created_at?->format('Y-m-d H:i') }}</td>
                            <td><span class="cp-badge">{{ $request->status_text }}</span></td>
                            <td>
                                <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.services.show', $request) }}">
                                    <i class="bi bi-eye-fill"></i>{{ __('تفاصيل') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="cp-empty" colspan="4">{{ __('لا توجد طلبات خدمات حتى الآن') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $serviceRequests->links() }}
    </section>
</div>
@endsection
