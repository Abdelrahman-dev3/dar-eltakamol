@extends('layouts.app')

@section('title', __('إضافة ملف جديد'))

@php
    $meetingsCount = $meetings->count();
@endphp

@include('documents.partials.form-styles')

@section('content')
<div class="container-fluid doc-form-page">
    <div class="doc-form-shell">
        <section class="doc-form-hero">
            <div class="doc-form-hero-inner">
                <div>
                    <span class="doc-form-badge">
                        <i class="bi bi-folder-plus"></i>
                        {{ __('إضافة ملف جديد') }}
                    </span>
                    <h1 class="doc-form-title">{{ __('ارفع الملفات ونظّمها داخل النظام من تجربة واحدة أكثر وضوحًا') }}</h1>
                </div>

                <div class="doc-form-actions">
                    <a href="{{ route('documents.index') }}" class="doc-form-btn-muted">
                        <i class="bi bi-arrow-right-circle"></i>
                        {{ __('العودة إلى الملفات') }}
                    </a>
                </div>
            </div>
        </section>

        <div class="doc-form-grid">
            <section class="doc-panel">
                <div class="doc-panel-header">
                    <div class="doc-panel-title-wrap">
                        <span class="doc-panel-icon"><i class="bi bi-cloud-arrow-up"></i></span>
                        <div>
                            <h2 class="doc-panel-title">{{ __('رفع الملفات') }}</h2>
                            <p class="doc-panel-subtitle">{{ __('يمكنك رفع ملف واحد أو عدة ملفات دفعة واحدة مع ربط اختياري باجتماع لتحسين الوصول والتنظيم.') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data"
                    data-doc-form
                    data-confirm-message="{{ __('هل أنت متأكد من إضافة هذه الملفات؟') }}"
                    data-name-label="{{ __('الاسم العام') }}"
                    data-empty-files="{{ __('يرجى اختيار ملف واحد على الأقل') }}"
                    data-require-name="false">
                    @csrf

                    @include('documents.partials.form-fields', [
                        'isEdit' => false,
                        'document' => null,
                        'meetings' => $meetings,
                        'users' => $users,
                        'audienceScopes' => $audienceScopes,
                        'committeeOptions' => $committeeOptions,
                        'companies' => $companies,
                        'departments' => $departments,
                    ])

                    <div class="doc-form-footer">
                        <p class="doc-form-footer-note">
                            {{ __('سيتم إنشاء سجل مستقل لكل ملف مرفوع. إذا تركت الاسم العام فارغًا فسيُستخدم اسم الملف الأصلي تلقائيًا لكل عنصر.') }}
                        </p>

                        <div class="doc-form-footer-actions">
                            <button type="submit" class="doc-form-btn">
                                <i class="bi bi-check2-circle"></i>
                                {{ __('حفظ الملفات') }}
                            </button>
                            <a href="{{ route('documents.index') }}" class="doc-form-btn-muted">
                                <i class="bi bi-x-circle"></i>
                                {{ __('إلغاء') }}
                            </a>
                        </div>
                    </div>
                </form>
            </section>

            <aside class="doc-side-stack">
                <section class="doc-mini-card">
                    <h3 class="doc-mini-title">
                        <i class="bi bi-lightbulb"></i>
                        {{ __('إرشادات سريعة') }}
                    </h3>
                    <div class="doc-tip-list">
                        <div class="doc-tip-item">
                            <i class="bi bi-1-circle"></i>
                            <div>{{ __('استخدم اسمًا عامًا فقط إذا أردت أن تحمل جميع الملفات المرفوعة نفس العنوان داخل القائمة.') }}</div>
                        </div>
                        <div class="doc-tip-item">
                            <i class="bi bi-2-circle"></i>
                            <div>{{ __('ربط الملف باجتماع يساعدك على الرجوع إليه سريعًا من صفحة الاجتماع أو من قائمة الملفات المرتبطة.') }}</div>
                        </div>
                        <div class="doc-tip-item">
                            <i class="bi bi-3-circle"></i>
                            <div>{{ __('يفضّل رفع ملفات بأسماء واضحة قبل الرفع لتظهر بشكل أفضل في التحميل والأرشفة لاحقًا.') }}</div>
                        </div>
                    </div>
                </section>

                <section class="doc-mini-card">
                    <h3 class="doc-mini-title">
                        <i class="bi bi-bar-chart"></i>
                        {{ __('ملخص الرفع') }}
                    </h3>
                    <div class="doc-stat-grid">
                        <div class="doc-stat-box">
                            <strong>50MB</strong>
                            <span>{{ __('الحد الأقصى لكل ملف') }}</span>
                        </div>
                        <div class="doc-stat-box">
                            <strong>{{ $meetingsCount }}</strong>
                            <span>{{ __('عدد الاجتماعات المتاحة للربط') }}</span>
                        </div>
                        <div class="doc-stat-box">
                            <strong>{{ __('متعدد') }}</strong>
                            <span>{{ __('رفع أكثر من ملف مدعوم') }}</span>
                        </div>
                        <div class="doc-stat-box">
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

@include('documents.partials.form-scripts')
