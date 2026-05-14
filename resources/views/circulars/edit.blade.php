@extends('layouts.app')

@section('title', __('تعديل التعميم'))

@php
    $extension = strtoupper($circular->file_extension ?: '-');
@endphp

@include('circulars.partials.form-styles')

@section('content')
<div class="container-fluid cir-form-page">
    <div class="cir-form-shell">
        <section class="cir-form-hero">
            <div class="cir-form-hero-inner">
                <div>
                    <span class="cir-form-badge">
                        <i class="bi bi-pencil-fill"></i>
                        {{ __('تعديل التعميم') }} #{{ $circular->id }}
                    </span>
                    <h1 class="cir-form-title">{{ __('حدّث اسم التعميم أو استبدل ملفه مع الحفاظ على وضوح الجمهور المستهدف') }}</h1>
                    <p class="cir-form-subtitle">
                        {{ __('يمكنك تعديل الاسم المعروض، مراجعة الملف الحالي، وتحديث الجمهور المستهدف أو استبداله بملف أحدث من نفس الشاشة.') }}
                    </p>
                </div>

                <div class="cir-form-actions">
                    <a href="{{ route('circulars.show', $circular) }}" class="cir-form-btn">
                        <i class="bi bi-eye"></i>
                        {{ __('عرض التعميم') }}
                    </a>
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
                        <span class="cir-panel-icon"><i class="bi bi-sliders2"></i></span>
                        <div>
                            <h2 class="cir-panel-title">{{ __('تحديث بيانات التعميم') }}</h2>
                            <p class="cir-panel-subtitle">{{ __('احتفظ بالملف الحالي أو استبدله، وحدّث الاسم والجمهور المستهدف من نفس الواجهة.') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('circulars.update', $circular) }}" method="POST" enctype="multipart/form-data"
                    data-cir-form
                    data-confirm-message="{{ __('هل أنت متأكد من حفظ تعديلات التعميم؟') }}"
                    data-name-label="{{ __('اسم التعميم') }}"
                    data-empty-name="{{ __('يرجى إدخال اسم التعميم') }}"
                    data-require-name="true">
                    @csrf
                    @method('PUT')

                    @include('circulars.partials.form-fields', [
                        'isEdit' => true,
                        'circular' => $circular,
                        'users' => $users,
                        'audienceScopes' => $audienceScopes,
                        'committeeOptions' => $committeeOptions,
                        'companies' => $companies,
                        'departments' => $departments,
                    ])

                    <div class="cir-form-footer">
                        <p class="cir-form-footer-note">
                            {{ __('إذا لم يتم اختيار ملف بديل فسيتم الاحتفاظ بالملف الحالي تلقائيًا. وعند الاستبدال ستُحدّث بيانات الملف بالكامل بالنسخة الجديدة.') }}
                        </p>

                        <div class="cir-form-footer-actions">
                            <button type="submit" class="cir-form-btn">
                                <i class="bi bi-save2"></i>
                                {{ __('حفظ التغييرات') }}
                            </button>
                            <a href="{{ route('circulars.show', $circular) }}" class="cir-form-btn-muted">
                                <i class="bi bi-eye"></i>
                                {{ __('عرض') }}
                            </a>
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
                        <i class="bi bi-file-earmark-text"></i>
                        {{ __('ملخص سريع') }}
                    </h3>
                    <div class="cir-stat-grid">
                        <div class="cir-stat-box">
                            <strong>#{{ $circular->id }}</strong>
                            <span>{{ __('رقم التعميم') }}</span>
                        </div>
                        <div class="cir-stat-box">
                            <strong>{{ $extension }}</strong>
                            <span>{{ __('امتداد الملف الحالي') }}</span>
                        </div>
                        <div class="cir-stat-box">
                            <strong>{{ $circular->file_size_human }}</strong>
                            <span>{{ __('حجم الملف الحالي') }}</span>
                        </div>
                        <div class="cir-stat-box">
                            <strong>{{ $circular->updated_at->diffForHumans() }}</strong>
                            <span>{{ __('آخر تحديث') }}</span>
                        </div>
                    </div>
                </section>

                <section class="cir-mini-card">
                    <h3 class="cir-mini-title">
                        <i class="bi bi-people"></i>
                        {{ __('الجمهور الحالي') }}
                    </h3>
                    <div class="cir-meta-list">
                        <div class="cir-meta-item">
                            <i class="bi bi-calendar-plus"></i>
                            <div>{{ __('أُضيف في') }}: {{ $circular->created_at->format('Y-m-d H:i') }}</div>
                        </div>
                        <div class="cir-meta-item">
                            <i class="bi bi-arrow-repeat"></i>
                            <div>{{ __('آخر تحديث') }}: {{ $circular->updated_at->format('Y-m-d H:i') }}</div>
                        </div>
                        <div class="cir-meta-item">
                            <i class="bi bi-person-check"></i>
                            <div>{{ __('عدد المستلمين') }}: {{ $circular->recipients->count() }}</div>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</div>
@endsection

@include('circulars.partials.form-scripts')
