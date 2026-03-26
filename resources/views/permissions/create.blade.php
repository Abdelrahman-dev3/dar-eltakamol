@extends('layouts.app')

@section('title', __('إضافة صلاحية جديدة'))

@php
    $modulesCount = count($modules);
    $departmentsCount = $departments->count();
@endphp

@include('permissions.partials.form-styles')

@section('content')
<div class="container-fluid permf-page">
    <div class="permf-shell">
        <section class="permf-hero">
            <div class="permf-hero-inner">
                <div>
                    <span class="permf-badge">
                        <i class="bi bi-key-fill"></i>
                        {{ __('إضافة صلاحية جديدة') }}
                    </span>
                    <h1 class="permf-title">{{ __('أنشئ صلاحية جديدة وحدد شكلها الإداري وربطها بالإدارات من شاشة واحدة') }}</h1>
                    <p class="permf-subtitle">{{ __('اكتب الاسم البرمجي للصلاحية مثل bookings.view أو meetings.attachments.download، وسيظهر للمشرف داخل النظام بصياغة عربية أوضح تلقائيًا.') }}</p>
                </div>

                <div class="permf-actions">
                    <a href="{{ route('permissions.index') }}" class="permf-btn-muted">
                        <i class="bi bi-arrow-right-circle"></i>
                        {{ __('العودة إلى الصلاحيات') }}
                    </a>
                </div>
            </div>
        </section>

        <div class="permf-grid">
            <section class="permf-panel">
                <div class="permf-panel-header">
                    <div class="permf-panel-title-wrap">
                        <span class="permf-panel-icon"><i class="bi bi-shield-plus"></i></span>
                        <div>
                            <h2 class="permf-panel-title">{{ __('بيانات الصلاحية وربطها') }}</h2>
                            <p class="permf-panel-subtitle">{{ __('استخدم هذا النموذج لبناء صلاحية جديدة بشكل منظم، ثم اربطها مباشرة بالإدارات التي يجب أن ترثها أو تستفيد منها.') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('permissions.store') }}" method="POST">
                    @csrf

                    @include('permissions.partials.form-fields', [
                        'isEdit' => false,
                        'permission' => null,
                        'modules' => $modules,
                        'departments' => $departments,
                    ])

                    <div class="permf-footer">
                        <p class="permf-footer-note">{{ __('يمكنك لاحقًا تعديل الاسم البرمجي أو تغيير الإدارات المرتبطة من شاشة التعديل. إذا كانت قاعدة البيانات الحالية لا تحفظ بعض الحقول المساعدة فسيستمر النموذج في العمل بشكل متوافق.') }}</p>

                        <div class="permf-footer-actions">
                            <button type="submit" class="permf-btn">
                                <i class="bi bi-check2-circle"></i>
                                {{ __('حفظ الصلاحية') }}
                            </button>
                            <a href="{{ route('permissions.index') }}" class="permf-btn-muted">
                                <i class="bi bi-x-circle"></i>
                                {{ __('إلغاء') }}
                            </a>
                        </div>
                    </div>
                </form>
            </section>

            <aside class="permf-side-stack">
                <section class="permf-mini-card">
                    <h3 class="permf-mini-title">
                        <i class="bi bi-lightbulb"></i>
                        {{ __('إرشادات سريعة') }}
                    </h3>
                    <div class="permf-tip-list">
                        <div class="permf-tip-item">
                            <i class="bi bi-1-circle"></i>
                            <div>{{ __('يفضل أن يكون الاسم البرمجي بنمط المورد ثم الإجراء مثل documents.download أو users.edit.') }}</div>
                        </div>
                        <div class="permf-tip-item">
                            <i class="bi bi-2-circle"></i>
                            <div>{{ __('اختيار الوحدة يسهل تجميع الصلاحيات وعرضها داخل شاشات المستخدمين والإدارات.') }}</div>
                        </div>
                        <div class="permf-tip-item">
                            <i class="bi bi-3-circle"></i>
                            <div>{{ __('اربط الصلاحية فقط بالإدارات التي تحتاجها حتى يظل توزيع الصلاحيات واضحًا ومتحكمًا فيه.') }}</div>
                        </div>
                    </div>
                </section>

                <section class="permf-mini-card">
                    <h3 class="permf-mini-title">
                        <i class="bi bi-bar-chart"></i>
                        {{ __('ملخص سريع') }}
                    </h3>
                    <div class="permf-stat-grid">
                        <div class="permf-stat-box">
                            <strong>{{ $modulesCount }}</strong>
                            <span>{{ __('وحدة متاحة للتنظيم') }}</span>
                        </div>
                        <div class="permf-stat-box">
                            <strong>{{ $departmentsCount }}</strong>
                            <span>{{ __('إدارة متاحة للربط') }}</span>
                        </div>
                        <div class="permf-stat-box">
                            <strong>{{ __('عربي') }}</strong>
                            <span>{{ __('عرض واضح للمشرف داخل النظام') }}</span>
                        </div>
                        <div class="permf-stat-box">
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

@include('permissions.partials.form-scripts')
