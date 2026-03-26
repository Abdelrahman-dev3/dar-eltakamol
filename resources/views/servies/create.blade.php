@extends('layouts.app')

@section('title', __('إضافة خدمة جديدة'))

@include('servies.partials.form-styles')

@section('content')
<div class="container-fluid servf-page">
    <div class="servf-shell">
        <section class="servf-hero">
            <div class="servf-hero-inner">
                <div>
                    <span class="servf-badge">
                        <i class="bi bi-plus-circle-fill"></i>
                        {{ __('إضافة خدمة جديدة') }}
                    </span>
                    <h1 class="servf-title">{{ __('أنشئ خدمة جديدة ضمن إعدادات النظام من شاشة واضحة وسريعة') }}</h1>
                    <p class="servf-subtitle">{{ __('أضف اسم الخدمة بشكل منظم ليظهر لاحقًا داخل الحجوزات أو الأقسام المرتبطة بالخدمات بنفس الهوية الحديثة لباقي النظام.') }}</p>
                </div>

                <div class="servf-actions">
                    <a href="{{ route('servies.index') }}" class="servf-btn-muted">
                        <i class="bi bi-arrow-right-circle"></i>
                        {{ __('العودة إلى الخدمات') }}
                    </a>
                </div>
            </div>
        </section>

        <div class="servf-grid">
            <section class="servf-panel">
                <div class="servf-panel-header">
                    <div class="servf-panel-title-wrap">
                        <span class="servf-panel-icon"><i class="bi bi-gear-wide-connected"></i></span>
                        <div>
                            <h2 class="servf-panel-title">{{ __('بيانات الخدمة') }}</h2>
                            <p class="servf-panel-subtitle">{{ __('أدخل الاسم الذي سيُستخدم لتمثيل هذه الخدمة داخل النظام، ويمكنك تعديله لاحقًا من شاشة التعديل إذا احتجت.') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('servies.store') }}" method="POST">
                    @csrf

                    @include('servies.partials.form-fields', [
                        'isEdit' => false,
                        'service' => null,
                    ])

                    <div class="servf-footer">
                        <p class="servf-footer-note">{{ __('استخدم اسمًا واضحًا ومباشرًا حتى يكون اختيار الخدمة في الشاشات الأخرى أسهل على المستخدمين والمشرفين.') }}</p>

                        <div class="servf-footer-actions">
                            <button type="submit" class="servf-btn">
                                <i class="bi bi-check2-circle"></i>
                                {{ __('حفظ الخدمة') }}
                            </button>
                            <a href="{{ route('servies.index') }}" class="servf-btn-muted">
                                <i class="bi bi-x-circle"></i>
                                {{ __('إلغاء') }}
                            </a>
                        </div>
                    </div>
                </form>
            </section>

            <aside class="servf-side-stack">
                <section class="servf-mini-card">
                    <h3 class="servf-mini-title">
                        <i class="bi bi-lightbulb"></i>
                        {{ __('إرشادات سريعة') }}
                    </h3>
                    <div class="servf-tip-list">
                        <div class="servf-tip-item">
                            <i class="bi bi-1-circle"></i>
                            <div>{{ __('يفضل أن يكون اسم الخدمة قصيرًا وواضحًا حتى يظهر بشكل جيد في القوائم والنماذج.') }}</div>
                        </div>
                        <div class="servf-tip-item">
                            <i class="bi bi-2-circle"></i>
                            <div>{{ __('إذا كانت الخدمة مرتبطة بالحجوزات، فاختر اسمًا يفهمه الموظف والمستخدم النهائي بسهولة.') }}</div>
                        </div>
                        <div class="servf-tip-item">
                            <i class="bi bi-3-circle"></i>
                            <div>{{ __('يمكنك لاحقًا إعادة التسمية من دون الحاجة إلى حذف الخدمة وإضافتها من جديد.') }}</div>
                        </div>
                    </div>
                </section>

                <section class="servf-mini-card">
                    <h3 class="servf-mini-title">
                        <i class="bi bi-bar-chart"></i>
                        {{ __('معلومة سريعة') }}
                    </h3>
                    <div class="servf-stat-grid">
                        <div class="servf-stat-box">
                            <strong>{{ __('خدمة') }}</strong>
                            <span>{{ __('عنصر تنظيمي يمكن استخدامه في الأقسام المرتبطة') }}</span>
                        </div>
                        <div class="servf-stat-box">
                            <strong>255</strong>
                            <span>{{ __('الحد الأقصى لعدد الأحرف في اسم الخدمة') }}</span>
                        </div>
                        <div class="servf-stat-box">
                            <strong>{{ __('مرن') }}</strong>
                            <span>{{ __('يمكن تعديل الاسم لاحقًا بسهولة') }}</span>
                        </div>
                        <div class="servf-stat-box">
                            <strong>{{ now()->format('Y-m-d') }}</strong>
                            <span>{{ __('تاريخ الإضافة الحالي') }}</span>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</div>
@endsection
