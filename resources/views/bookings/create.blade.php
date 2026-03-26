@extends('layouts.app')

@section('title', __('إضافة حجز جديد'))

@php
    $servicesCount = $services->count();
    $usersCount = $users->count();
@endphp

@include('bookings.partials.form-styles')

@section('content')
<div class="container-fluid bookf-page">
    <div class="bookf-shell">
        <section class="bookf-hero">
            <div class="bookf-hero-inner">
                <div>
                    <span class="bookf-badge">
                        <i class="bi bi-calendar-plus-fill"></i>
                        {{ __('إضافة حجز جديد') }}
                    </span>
                    <h1 class="bookf-title">{{ __('أنشئ حجزًا جديدًا وحدد الخدمة والموعد والمستخدم من شاشة واحدة') }}</h1>
                    <p class="bookf-subtitle">{{ __('اختر الخدمة وطالبها ثم حدّد التاريخ والوقت باستخدام التقويم الحديث، مع إمكانية إضافة ملاحظات لتبقى تفاصيل الحجز واضحة للإدارة.') }}</p>
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
                        <span class="bookf-panel-icon"><i class="bi bi-journal-plus"></i></span>
                        <div>
                            <h2 class="bookf-panel-title">{{ __('بيانات الحجز') }}</h2>
                            <p class="bookf-panel-subtitle">{{ __('املأ تفاصيل الحجز بعناية ليظهر في قائمة الحجوزات بشكل منظم مع الحالة الافتراضية قيد الانتظار.') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('bookings.store') }}" method="POST">
                    @csrf

                    @include('bookings.partials.form-fields', [
                        'isEdit' => false,
                        'booking' => null,
                        'services' => $services,
                        'users' => $users,
                    ])

                    <div class="bookf-footer">
                        <p class="bookf-footer-note">{{ __('سيتم إنشاء الحجز بحالة قيد الانتظار تلقائيًا، ويمكنك لاحقًا تأكيده أو تعديله من صفحة قائمة الحجوزات.') }}</p>

                        <div class="bookf-footer-actions">
                            <button type="submit" class="bookf-btn">
                                <i class="bi bi-check2-circle"></i>
                                {{ __('حفظ الحجز') }}
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
                        <i class="bi bi-lightbulb"></i>
                        {{ __('إرشادات سريعة') }}
                    </h3>
                    <div class="bookf-tip-list">
                        <div class="bookf-tip-item">
                            <i class="bi bi-1-circle"></i>
                            <div>{{ __('تأكد من اختيار المستخدم الصحيح حتى يظهر الحجز في سياقه المناسب داخل النظام.') }}</div>
                        </div>
                        <div class="bookf-tip-item">
                            <i class="bi bi-2-circle"></i>
                            <div>{{ __('استخدم الملاحظات فقط عند الحاجة إلى تفاصيل إضافية مثل متطلبات خاصة أو تذكيرات إدارية.') }}</div>
                        </div>
                        <div class="bookf-tip-item">
                            <i class="bi bi-3-circle"></i>
                            <div>{{ __('يمكن تغيير حالة الحجز لاحقًا من قائمة الحجوزات باستخدام زر التبديل السريع.') }}</div>
                        </div>
                    </div>
                </section>

                <section class="bookf-mini-card">
                    <h3 class="bookf-mini-title">
                        <i class="bi bi-bar-chart"></i>
                        {{ __('ملخص سريع') }}
                    </h3>
                    <div class="bookf-stat-grid">
                        <div class="bookf-stat-box">
                            <strong>{{ $servicesCount }}</strong>
                            <span>{{ __('خدمة متاحة للحجز') }}</span>
                        </div>
                        <div class="bookf-stat-box">
                            <strong>{{ $usersCount }}</strong>
                            <span>{{ __('مستخدم متاح للاختيار') }}</span>
                        </div>
                        <div class="bookf-stat-box">
                            <strong>{{ __('معلّق') }}</strong>
                            <span>{{ __('الحالة الافتراضية عند الإنشاء') }}</span>
                        </div>
                        <div class="bookf-stat-box">
                            <strong>{{ now()->format('Y-m-d') }}</strong>
                            <span>{{ __('تاريخ الإنشاء الحالي') }}</span>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</div>
@endsection

@include('bookings.partials.form-scripts')
