@extends('layouts.app')

@section('title', __('إضافة اجتماع جديد'))

@include('meetings.partials.form-styles')

@section('content')
<div class="container-fluid meeting-form-page">
    <div class="meeting-form-shell">
        <section class="meeting-form-hero">
            <div class="meeting-form-hero-inner">
                <div>
                    <span class="meeting-form-badge">
                        <i class="bi bi-calendar-plus-fill"></i>
                        {{ __('إضافة اجتماع جديد') }}
                    </span>
                    <h1 class="meeting-form-title">{{ __('أنشئ اجتماعًا منظمًا وواضحًا من أول خطوة') }}</h1>
                    <p class="meeting-form-subtitle">
                        {{ __('أدخل بيانات الاجتماع، حدّد المدعوين، وأرفق الملفات اللازمة في نفس الشاشة') }}
                    </p>
                </div>

                <div class="meeting-form-actions">
                    <a href="{{ route('meetings.index') }}" class="meeting-form-btn-muted">
                        <i class="bi bi-arrow-right-circle"></i>
                        {{ __('العودة للاجتماعات') }}
                    </a>
                </div>
            </div>
        </section>

        <div class="meeting-form-grid">
            <section class="meeting-panel">
                <div class="meeting-panel-header">
                    <div class="meeting-panel-title-wrap">
                        <span class="meeting-panel-icon"><i class="bi bi-pencil-square"></i></span>
                        <div>
                            <h2 class="meeting-panel-title">{{ __('نموذج إضافة الاجتماع') }}</h2>
                            <p class="meeting-panel-subtitle">{{ __('كل الحقول مرتبة في أقسام قصيرة لتسريع الإدخال وتقليل الأخطاء أثناء تجهيز الاجتماع.') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('meetings.store') }}" method="POST" enctype="multipart/form-data"
                    data-meeting-form
                    data-confirm-message="{{ __('هل أنت متأكد من إنشاء هذا الاجتماع؟') }}"
                    data-name-label="{{ __('اسم الاجتماع') }}"
                    data-date-label="{{ __('التاريخ') }}"
                    data-empty-name="{{ __('يرجى إدخال اسم الاجتماع') }}"
                    data-empty-url="{{ __('يرجى إدخال رابط الاجتماع') }}"
                    data-empty-date="{{ __('يرجى إدخال تاريخ الاجتماع') }}">
                    @csrf

                    @include('meetings.partials.form-fields', [
                        'isEdit' => false,
                        'meeting' => null,
                        'users' => $users,
                    ])

                    <div class="meeting-form-footer">
                        <p class="meeting-form-footer-note">
                            {{ __('سيتم حفظ الاجتماع وربط المدعوين والمرفقات مباشرة. يمكنك تعديل البيانات لاحقًا أو مراجعتها من صفحة العرض.') }}
                        </p>

                        <div class="meeting-form-footer-actions">
                            <button type="submit" class="meeting-form-btn">
                                <i class="bi bi-check2-circle"></i>
                                {{ __('حفظ الاجتماع') }}
                            </button>
                            <a href="{{ route('meetings.index') }}" class="meeting-form-btn-muted">
                                <i class="bi bi-x-circle"></i>
                                {{ __('إلغاء') }}
                            </a>
                        </div>
                    </div>
                </form>
            </section>

            <aside class="meeting-side-stack">
                <section class="meeting-mini-card">
                    <h3 class="meeting-mini-title">
                        <i class="bi bi-lightbulb"></i>
                        {{ __('خطوات سريعة') }}
                    </h3>
                    <div class="meeting-tip-list">
                        <div class="meeting-tip-item">
                            <i class="bi bi-1-circle"></i>
                            <div>{{ __('اختر اسمًا واضحًا يسهّل العثور على الاجتماع لاحقًا في القائمة.') }}</div>
                        </div>
                        <div class="meeting-tip-item">
                            <i class="bi bi-2-circle"></i>
                            <div>{{ __('حدّد التاريخ والوقت بدقة حتى تظهر حالة الاجتماع بشكل صحيح في صفحة العرض.') }}</div>
                        </div>
                        <div class="meeting-tip-item">
                            <i class="bi bi-3-circle"></i>
                            <div>{{ __('أضف المرفقات الأساسية مثل جدول الأعمال أو الملفات المرجعية قبل الحفظ.') }}</div>
                        </div>
                    </div>
                </section>

                <section class="meeting-mini-card">
                    <h3 class="meeting-mini-title">
                        <i class="bi bi-bar-chart"></i>
                        {{ __('ملخص سريع') }}
                    </h3>
                    <div class="meeting-stat-grid">
                        <div class="meeting-stat-box">
                            <strong>{{ number_format($users->count()) }}</strong>
                            <span>{{ __('مستخدم متاح للدعوة') }}</span>
                        </div>
                        <div class="meeting-stat-box">
                            <strong>20MB</strong>
                            <span>{{ __('الحد الأقصى لكل مرفق') }}</span>
                        </div>
                        <div class="meeting-stat-box">
                            <strong>{{ now()->format('Y-m-d') }}</strong>
                            <span>{{ __('تاريخ العمل الحالي') }}</span>
                        </div>
                        <div class="meeting-stat-box">
                            <strong>{{ __('مرن') }}</strong>
                            <span>{{ __('يمكن تعديل المدعوين والمرفقات لاحقًا') }}</span>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</div>
@endsection

@include('meetings.partials.form-scripts')
