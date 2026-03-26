@extends('layouts.app')

@section('title', __('إضافة لائحة جديدة'))

@include('regulations.partials.form-styles')

@section('content')
<div class="container-fluid reg-form-page">
    <div class="reg-form-shell">
        <section class="reg-form-hero">
            <div class="reg-form-hero-inner">
                <div>
                    <span class="reg-form-badge">
                        <i class="bi bi-journal-plus"></i>
                        {{ __('إضافة لائحة جديدة') }}
                    </span>
                    <h1 class="reg-form-title">{{ __('نظّم اللوائح والملفات المرجعية من شاشة واحدة واضحة') }}</h1>
                    <p class="reg-form-subtitle">
                        {{ __('ارفع ملفًا واحدًا أو عدة ملفات مع اسم عام اختياري، لتظهر اللوائح داخل النظام بشكل مرتب وسهل الوصول والتحميل.') }}
                    </p>
                </div>

                <div class="reg-form-actions">
                    <a href="{{ route('regulations.index') }}" class="reg-form-btn-muted">
                        <i class="bi bi-arrow-right-circle"></i>
                        {{ __('العودة للوائح') }}
                    </a>
                </div>
            </div>
        </section>

        <div class="reg-form-grid">
            <section class="reg-panel">
                <div class="reg-panel-header">
                    <div class="reg-panel-title-wrap">
                        <span class="reg-panel-icon"><i class="bi bi-cloud-upload"></i></span>
                        <div>
                            <h2 class="reg-panel-title">{{ __('رفع اللوائح') }}</h2>
                            <p class="reg-panel-subtitle">{{ __('النموذج الحالي يدعم رفع عدة ملفات دفعة واحدة مع تجربة أوضح للمعاينة والتنظيم قبل الحفظ.') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('regulations.store') }}" method="POST" enctype="multipart/form-data"
                    data-reg-form
                    data-confirm-message="{{ __('هل أنت متأكد من إضافة هذه اللوائح؟') }}"
                    data-name-label="{{ __('الاسم العام') }}"
                    data-empty-files="{{ __('يرجى اختيار ملف واحد على الأقل') }}"
                    data-require-name="false">
                    @csrf

                    @include('regulations.partials.form-fields', [
                        'isEdit' => false,
                        'regulation' => null,
                    ])

                    <div class="reg-form-footer">
                        <p class="reg-form-footer-note">
                            {{ __('سيتم إنشاء سجل مستقل لكل ملف مرفوع. إن تركت الاسم العام فارغًا فسيُعتمد اسم الملف الأصلي تلقائيًا.') }}
                        </p>

                        <div class="reg-form-footer-actions">
                            <button type="submit" class="reg-form-btn">
                                <i class="bi bi-check2-circle"></i>
                                {{ __('حفظ اللوائح') }}
                            </button>
                            <a href="{{ route('regulations.index') }}" class="reg-form-btn-muted">
                                <i class="bi bi-x-circle"></i>
                                {{ __('إلغاء') }}
                            </a>
                        </div>
                    </div>
                </form>
            </section>

            <aside class="reg-side-stack">
                <section class="reg-mini-card">
                    <h3 class="reg-mini-title">
                        <i class="bi bi-lightbulb"></i>
                        {{ __('إرشادات سريعة') }}
                    </h3>
                    <div class="reg-tip-list">
                        <div class="reg-tip-item">
                            <i class="bi bi-1-circle"></i>
                            <div>{{ __('استخدم اسمًا عامًا فقط إذا أردت أن تحمل جميع الملفات نفس عنوان اللائحة عند الإنشاء.') }}</div>
                        </div>
                        <div class="reg-tip-item">
                            <i class="bi bi-2-circle"></i>
                            <div>{{ __('يمكنك رفع أكثر من ملف مرة واحدة، وسيتم إنشاء عنصر مستقل لكل ملف داخل القائمة.') }}</div>
                        </div>
                        <div class="reg-tip-item">
                            <i class="bi bi-3-circle"></i>
                            <div>{{ __('يفضّل استخدام أسماء ملفات واضحة لتسهيل البحث والتنزيل لاحقًا.') }}</div>
                        </div>
                    </div>
                </section>

                <section class="reg-mini-card">
                    <h3 class="reg-mini-title">
                        <i class="bi bi-bar-chart"></i>
                        {{ __('ملخص الرفع') }}
                    </h3>
                    <div class="reg-stat-grid">
                        <div class="reg-stat-box">
                            <strong>50MB</strong>
                            <span>{{ __('الحد الأقصى لكل ملف') }}</span>
                        </div>
                        <div class="reg-stat-box">
                            <strong>{{ __('متعدد') }}</strong>
                            <span>{{ __('رفع أكثر من ملف مدعوم') }}</span>
                        </div>
                        <div class="reg-stat-box">
                            <strong>PDF+</strong>
                            <span>{{ __('مستندات وصور وأرشيف') }}</span>
                        </div>
                        <div class="reg-stat-box">
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

@include('regulations.partials.form-scripts')
