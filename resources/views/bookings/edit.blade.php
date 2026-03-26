@extends('layouts.app')

@section('title', __('تعديل الحجز'))

@include('bookings.partials.form-styles')

@section('content')
<div class="container-fluid bookf-page">
    <div class="bookf-shell">
        <section class="bookf-hero">
            <div class="bookf-hero-inner">
                <div>
                    <span class="bookf-badge">
                        <i class="bi bi-pencil-square"></i>
                        {{ __('تعديل الحجز') }}
                    </span>
                    <h1 class="bookf-title">{{ optional($booking->service)->name ?: __('حجز') }}</h1>
                    <p class="bookf-subtitle">{{ __('حدّث تفاصيل الحجز الحالية مثل الخدمة أو المستخدم أو الموعد مع الحفاظ على نفس السجل داخل النظام.') }}</p>
                </div>

                <div class="bookf-actions">
                    <a href="{{ route('bookings.index') }}" class="bookf-btn-muted">
                        <i class="bi bi-arrow-right-circle"></i>
                        {{ __('العودة إلى الحجوزات') }}
                    </a>
                </div>
            </div>
        </section>

        <div class="bookf-grid">
            <section class="bookf-panel">
                <div class="bookf-panel-header">
                    <div class="bookf-panel-title-wrap">
                        <span class="bookf-panel-icon"><i class="bi bi-calendar2-check"></i></span>
                        <div>
                            <h2 class="bookf-panel-title">{{ __('تحديث بيانات الحجز') }}</h2>
                            <p class="bookf-panel-subtitle">{{ __('راجع بيانات الحجز الحالية ثم حدّثها عند الحاجة، مع بقاء حالة الحجز كما هي حتى يتم تغييرها من قائمة الحجوزات.') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('bookings.update', $booking->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('bookings.partials.form-fields', [
                        'isEdit' => true,
                        'booking' => $booking,
                        'services' => $services,
                        'users' => $users,
                    ])

                    <div class="bookf-footer">
                        <p class="bookf-footer-note">{{ __('تعديل البيانات هنا لا يغير حالة الحجز تلقائيًا؛ حالة الحجز تُدار بشكل منفصل من شاشة قائمة الحجوزات.') }}</p>

                        <div class="bookf-footer-actions">
                            <button type="submit" class="bookf-btn">
                                <i class="bi bi-check2-circle"></i>
                                {{ __('حفظ التحديث') }}
                            </button>
                            <a href="{{ route('bookings.index') }}" class="bookf-btn-muted">
                                <i class="bi bi-x-circle"></i>
                                {{ __('إلغاء') }}
                            </a>
                        </div>
                    </div>
                </form>
            </section>

            <aside class="bookf-side-stack">
                <section class="bookf-mini-card">
                    <h3 class="bookf-mini-title">
                        <i class="bi bi-info-circle"></i>
                        {{ __('ملخص سريع') }}
                    </h3>
                    <div class="bookf-stat-grid">
                        <div class="bookf-stat-box">
                            <strong>#{{ $booking->id }}</strong>
                            <span>{{ __('رقم الحجز') }}</span>
                        </div>
                        <div class="bookf-stat-box">
                            <strong>{{ optional($booking->booking_date)->format('Y-m-d') ?: '--' }}</strong>
                            <span>{{ __('التاريخ الحالي') }}</span>
                        </div>
                        <div class="bookf-stat-box">
                            <strong>{{ optional($booking->booking_time)->format('H:i') ?: '--' }}</strong>
                            <span>{{ __('الوقت الحالي') }}</span>
                        </div>
                        <div class="bookf-stat-box">
                            <strong>{{ $booking->updated_at?->diffForHumans() }}</strong>
                            <span>{{ __('آخر تحديث') }}</span>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</div>
@endsection

@include('bookings.partials.form-scripts')
