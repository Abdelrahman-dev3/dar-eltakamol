@extends('layouts.app')

@section('title', __('إضافة تعميم جديد'))

@include('circulars.partials.form-styles')

@section('content')
<div class="container-fluid cir-form-page">
    <div class="cir-form-shell">
        <section class="cir-form-hero">
            <div class="cir-form-hero-inner">
                <div>
                    <span class="cir-form-badge">
                        <i class="bi bi-megaphone-fill"></i>
                        {{ __('إضافة تعميم جديد') }}
                    </span>
                    <h1 class="cir-form-title">{{ __('أنشئ التعميمات وحدد جمهورها من شاشة أوضح وأكثر تنظيمًا') }}</h1>
                </div>

                <div class="cir-form-actions">
                    <a href="{{ route('circulars.index') }}" class="cir-form-btn-muted">
                        <i class="bi bi-arrow-right-circle"></i>
                        {{ __('العودة إلى التعميمات') }}
                    </a>
                </div>
            </div>
        </section>

        <div class="cir-form-grid">
            <section class="cir-panel">
                <div class="cir-panel-header">
                    <div class="cir-panel-title-wrap">
                        <span class="cir-panel-icon"><i class="bi bi-cloud-arrow-up"></i></span>
                        <div>
                            <h2 class="cir-panel-title">{{ __('رفع التعميمات') }}</h2>
                            <p class="cir-panel-subtitle">{{ __('النموذج الحالي يدعم رفع أكثر من ملف مع تعيين اسم عام وتحديد الجمهور المستهدف للتعميم.') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('circulars.store') }}" method="POST" enctype="multipart/form-data"
                    data-cir-form
                    data-confirm-message="{{ __('هل أنت متأكد من إضافة هذه التعميمات؟') }}"
                    data-name-label="{{ __('الاسم العام') }}"
                    data-empty-files="{{ __('يرجى اختيار ملف واحد على الأقل') }}"
                    data-require-name="false">
                    @csrf

                    @include('circulars.partials.form-fields', [
                        'isEdit' => false,
                        'circular' => null,
                        'users' => $users,
                        'audienceScopes' => $audienceScopes,
                        'committeeOptions' => $committeeOptions,
                        'companies' => $companies,
                        'departments' => $departments,
                    ])

                    <div class="cir-form-footer">
                        <p class="cir-form-footer-note">
                            {{ __('سيتم إنشاء سجل مستقل لكل ملف مرفوع. إذا تركت الاسم العام فارغًا فسيُستخدم اسم الملف الأصلي تلقائيًا لكل تعميم.') }}
                        </p>

                        <div class="cir-form-footer-actions">
                            <button type="submit" class="cir-form-btn">
                                <i class="bi bi-check2-circle"></i>
                                {{ __('حفظ التعميمات') }}
                            </button>
                            <a href="{{ route('circulars.index') }}" class="cir-form-btn-muted">
                                <i class="bi bi-x-circle"></i>
                                {{ __('إلغاء') }}
                            </a>
                        </div>
                    </div>
                </form>
            </section>

            <aside class="cir-side-stack">
                <section class="cir-mini-card">
                    <h3 class="cir-mini-title">
                        <i class="bi bi-lightbulb"></i>
                        {{ __('إرشادات سريعة') }}
                    </h3>
                    <div class="cir-tip-list">
                        <div class="cir-tip-item">
                            <i class="bi bi-1-circle"></i>
                            <div>{{ __('استخدم اسمًا عامًا فقط إذا رغبت في أن تظهر كل الملفات المرفوعة تحت عنوان واحد داخل القائمة.') }}</div>
                        </div>
                        <div class="cir-tip-item">
                            <i class="bi bi-2-circle"></i>
                            <div>{{ __('حدد الجمهور المستهدف حتى يظهر التعميم منظما حسب المستخدمين أو المساهمين أو اللجان أو الشركات.') }}</div>
                        </div>
                        <div class="cir-tip-item">
                            <i class="bi bi-3-circle"></i>
                            <div>{{ __('الأسماء الواضحة للملفات قبل الرفع تجعل التنزيل والبحث أسهل لاحقًا داخل النظام.') }}</div>
                        </div>
                    </div>
                </section>

                <section class="cir-mini-card">
                    <h3 class="cir-mini-title">
                        <i class="bi bi-bar-chart"></i>
                        {{ __('ملخص الرفع') }}
                    </h3>
                    <div class="cir-stat-grid">
                        <div class="cir-stat-box">
                            <strong>50MB</strong>
                            <span>{{ __('الحد الأقصى لكل ملف') }}</span>
                        </div>
                        <div class="cir-stat-box">
                            <strong>{{ $users->count() }}</strong>
                            <span>{{ __('عدد المستخدمين المتاحين للاختيار') }}</span>
                        </div>
                        <div class="cir-stat-box">
                            <strong>{{ __('متعدد') }}</strong>
                            <span>{{ __('رفع أكثر من ملف مدعوم') }}</span>
                        </div>
                        <div class="cir-stat-box">
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

@include('circulars.partials.form-scripts')
