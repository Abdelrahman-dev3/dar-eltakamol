@extends('layouts.app')

@section('title', __('تعديل الملف'))

@php
    $extension = strtoupper($document->file_extension ?: '-');
@endphp

@include('documents.partials.form-styles')

@section('content')
<div class="container-fluid doc-form-page">
    <div class="doc-form-shell">
        <section class="doc-form-hero">
            <div class="doc-form-hero-inner">
                <div>
                    <span class="doc-form-badge">
                        <i class="bi bi-pencil-fill"></i>
                        {{ __('تعديل الملف') }} #{{ $document->id }}
                    </span>
                    <h1 class="doc-form-title">{{ __('حدّث اسم الملف أو استبدله من نفس الشاشة بدون فقدان السياق') }}</h1>
                </div>

                <div class="doc-form-actions">
                    <a href="{{ route('documents.show', $document) }}" class="doc-form-btn">
                        <i class="bi bi-eye"></i>
                        {{ __('عرض الملف') }}
                    </a>
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
                        <span class="doc-panel-icon"><i class="bi bi-sliders2"></i></span>
                        <div>
                            <h2 class="doc-panel-title">{{ __('تحديث بيانات الملف') }}</h2>
                            <p class="doc-panel-subtitle">{{ __('يمكنك الاحتفاظ بالملف الحالي أو استبداله، مع تعديل الاسم وربطه باجتماع محدد حسب الحاجة.') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('documents.update', $document) }}" method="POST" enctype="multipart/form-data"
                    data-doc-form
                    data-confirm-message="{{ __('هل أنت متأكد من حفظ تعديلات الملف؟') }}"
                    data-name-label="{{ __('اسم الملف') }}"
                    data-empty-name="{{ __('يرجى إدخال اسم الملف') }}"
                    data-require-name="true">
                    @csrf
                    @method('PUT')

                    @include('documents.partials.form-fields', [
                        'isEdit' => true,
                        'document' => $document,
                        'meetings' => $meetings,
                        'users' => $users,
                        'audienceScopes' => $audienceScopes,
                        'committeeOptions' => $committeeOptions,
                        'companies' => $companies,
                        'departments' => $departments,
                    ])

                    <div class="doc-form-footer">
                        <p class="doc-form-footer-note">
                            {{ __('سيتم الاحتفاظ بالملف الحالي تلقائيًا إذا لم يتم اختيار ملف بديل. وعند الاستبدال ستُحدّث بيانات الملف المخزنة بالملف الجديد.') }}
                        </p>

                        <div class="doc-form-footer-actions">
                            <button type="submit" class="doc-form-btn">
                                <i class="bi bi-save2"></i>
                                {{ __('حفظ التغييرات') }}
                            </button>
                            <a href="{{ route('documents.show', $document) }}" class="doc-form-btn-muted">
                                <i class="bi bi-eye"></i>
                                {{ __('عرض') }}
                            </a>
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
                        <i class="bi bi-file-earmark-text"></i>
                        {{ __('ملخص سريع') }}
                    </h3>
                    <div class="doc-stat-grid">
                        <div class="doc-stat-box">
                            <strong>#{{ $document->id }}</strong>
                            <span>{{ __('رقم الملف') }}</span>
                        </div>
                        <div class="doc-stat-box">
                            <strong>{{ $extension }}</strong>
                            <span>{{ __('امتداد الملف الحالي') }}</span>
                        </div>
                        <div class="doc-stat-box">
                            <strong>{{ $document->file_size_human }}</strong>
                            <span>{{ __('حجم الملف الحالي') }}</span>
                        </div>
                        <div class="doc-stat-box">
                            <strong>{{ $document->updated_at->diffForHumans() }}</strong>
                            <span>{{ __('آخر تحديث') }}</span>
                        </div>
                    </div>
                </section>

                <section class="doc-mini-card">
                    <h3 class="doc-mini-title">
                        <i class="bi bi-link-45deg"></i>
                        {{ __('الارتباط الحالي') }}
                    </h3>
                    <div class="doc-meta-list">
                        <div class="doc-meta-item">
                            <i class="bi bi-calendar-plus"></i>
                            <div>{{ __('أُضيف في') }}: {{ $document->created_at->format('Y-m-d H:i') }}</div>
                        </div>
                        <div class="doc-meta-item">
                            <i class="bi bi-arrow-repeat"></i>
                            <div>{{ __('آخر تحديث') }}: {{ $document->updated_at->format('Y-m-d H:i') }}</div>
                        </div>
                        <div class="doc-meta-item">
                            <i class="bi bi-people"></i>
                            <div>{{ __('الاجتماع المرتبط') }}: {{ optional($document->meeting)->name ?: __('بدون اجتماع') }}</div>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</div>
@endsection

@include('documents.partials.form-scripts')
