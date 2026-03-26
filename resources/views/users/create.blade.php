@extends('layouts.app')

@section('title', __('إضافة مستخدم جديد'))

@php
    $permissionsCount = $permissions->count();
    $departmentsCount = $departments->count();
@endphp

@include('users.partials.form-styles')

@section('content')
<div class="container-fluid userf-page">
    <div class="userf-shell">
        <section class="userf-hero">
            <div class="userf-hero-inner">
                <div>
                    <span class="userf-badge">
                        <i class="bi bi-person-plus-fill"></i>
                        {{ __('إضافة مستخدم جديد') }}
                    </span>
                    <h1 class="userf-title">{{ __('أنشئ حسابًا جديدًا وحدد له صلاحياته المباشرة من شاشة واحدة') }}</h1>
                    <p class="userf-subtitle">
                        {{ __('أضف بيانات المستخدم، اربطه بالإدارة المناسبة، واختر له الصلاحيات الخاصة به بشكل مستقل مع استمرار الصلاحيات الموروثة من الإدارات إن وجدت.') }}
                    </p>
                </div>

                <div class="userf-actions">
                    <a href="{{ route('users.index') }}" class="userf-btn-muted">
                        <i class="bi bi-arrow-right-circle"></i>
                        {{ __('العودة إلى المستخدمين') }}
                    </a>
                </div>
            </div>
        </section>

        <div class="userf-grid">
            <section class="userf-panel">
                <div class="userf-panel-header">
                    <div class="userf-panel-title-wrap">
                        <span class="userf-panel-icon"><i class="bi bi-person-workspace"></i></span>
                        <div>
                            <h2 class="userf-panel-title">{{ __('بيانات المستخدم والصلاحيات') }}</h2>
                            <p class="userf-panel-subtitle">{{ __('هذا النموذج يتيح لك ضبط بيانات الدخول والربط الإداري والصلاحيات المباشرة بدون مغادرة الصفحة.') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('users.store') }}" method="POST"
                    data-user-form
                    data-confirm-message="{{ __('هل أنت متأكد من إنشاء هذا المستخدم؟') }}"
                    data-name-label="{{ __('اسم المستخدم') }}"
                    data-email-label="{{ __('البريد الإلكتروني') }}">
                    @csrf

                    @include('users.partials.form-fields', [
                        'isEdit' => false,
                        'user' => null,
                        'departments' => $departments,
                        'permissions' => $permissions,
                        'moduleLabels' => $moduleLabels,
                    ])

                    <div class="userf-footer">
                        <p class="userf-footer-note">{{ __('يمكنك دائمًا تعديل الصلاحيات لاحقًا من شاشة التعديل. الصلاحيات المحددة هنا ستكون مباشرة على المستخدم بالإضافة إلى أي صلاحيات موروثة.') }}</p>

                        <div class="userf-footer-actions">
                            <button type="submit" class="userf-btn">
                                <i class="bi bi-check2-circle"></i>
                                {{ __('حفظ المستخدم') }}
                            </button>
                            <a href="{{ route('users.index') }}" class="userf-btn-muted">
                                <i class="bi bi-x-circle"></i>
                                {{ __('إلغاء') }}
                            </a>
                        </div>
                    </div>
                </form>
            </section>

            <aside class="userf-side-stack">
                <section class="userf-mini-card">
                    <h3 class="userf-mini-title">
                        <i class="bi bi-lightbulb"></i>
                        {{ __('إرشادات سريعة') }}
                    </h3>
                    <div class="userf-tip-list">
                        <div class="userf-tip-item">
                            <i class="bi bi-1-circle"></i>
                            <div>{{ __('اجعل الصلاحيات المباشرة فقط لما هو استثنائي أو خاص بهذا المستخدم، واترك الصلاحيات العامة على مستوى الإدارة.') }}</div>
                        </div>
                        <div class="userf-tip-item">
                            <i class="bi bi-2-circle"></i>
                            <div>{{ __('إذا كان المستخدم مرتبطًا لاحقًا بمساهم فسيستمر أيضًا في وراثة صلاحيات الإدارات المرتبطة بذلك المساهم.') }}</div>
                        </div>
                        <div class="userf-tip-item">
                            <i class="bi bi-3-circle"></i>
                            <div>{{ __('يفضّل استخدام بريد إلكتروني واضح لأن هذا الحقل هو وسيلة تعريف أساسية للحساب.') }}</div>
                        </div>
                    </div>
                </section>

                <section class="userf-mini-card">
                    <h3 class="userf-mini-title">
                        <i class="bi bi-bar-chart"></i>
                        {{ __('ملخص سريع') }}
                    </h3>
                    <div class="userf-stat-grid">
                        <div class="userf-stat-box">
                            <strong>{{ $departmentsCount }}</strong>
                            <span>{{ __('إدارة متاحة للربط') }}</span>
                        </div>
                        <div class="userf-stat-box">
                            <strong>{{ $permissionsCount }}</strong>
                            <span>{{ __('صلاحية متاحة للتحديد') }}</span>
                        </div>
                        <div class="userf-stat-box">
                            <strong>{{ __('مباشرة') }}</strong>
                            <span>{{ __('صلاحيات مخصصة للمستخدم') }}</span>
                        </div>
                        <div class="userf-stat-box">
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

@include('users.partials.form-scripts')
