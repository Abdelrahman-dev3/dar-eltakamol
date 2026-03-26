@extends('layouts.app')

@section('title', __('تعديل المستخدم'))

@include('users.partials.form-styles')

@section('content')
<div class="container-fluid userf-page">
    <div class="userf-shell">
        <section class="userf-hero">
            <div class="userf-hero-inner">
                <div>
                    <span class="userf-badge">
                        <i class="bi bi-pencil-fill"></i>
                        {{ __('تعديل المستخدم') }} #{{ $user->id }}
                    </span>
                    <h1 class="userf-title">{{ __('حدّث بيانات الحساب وصلاحياته المباشرة من واجهة أوضح وأكثر تنظيمًا') }}</h1>
                    <p class="userf-subtitle">
                        {{ __('يمكنك تعديل بيانات الدخول، تحديث الربط الإداري، ومراجعة الصلاحيات المباشرة والموروثة للمستخدم في نفس الشاشة.') }}
                    </p>
                </div>

                <div class="userf-actions">
                    <a href="{{ route('users.show', $user) }}" class="userf-btn">
                        <i class="bi bi-eye"></i>
                        {{ __('عرض المستخدم') }}
                    </a>
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
                        <span class="userf-panel-icon"><i class="bi bi-sliders2"></i></span>
                        <div>
                            <h2 class="userf-panel-title">{{ __('تحديث بيانات المستخدم') }}</h2>
                            <p class="userf-panel-subtitle">{{ __('راجع الصلاحيات الحالية للمستخدم، ثم عدّل ما يحتاجه فقط مع الاحتفاظ بالصلاحيات الموروثة من الإدارات.') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('users.update', $user) }}" method="POST"
                    data-user-form
                    data-confirm-message="{{ __('هل أنت متأكد من حفظ تعديلات هذا المستخدم؟') }}"
                    data-name-label="{{ __('اسم المستخدم') }}"
                    data-email-label="{{ __('البريد الإلكتروني') }}">
                    @csrf
                    @method('PUT')

                    @include('users.partials.form-fields', [
                        'isEdit' => true,
                        'user' => $user,
                        'departments' => $departments,
                        'permissions' => $permissions,
                        'moduleLabels' => $moduleLabels,
                    ])

                    <div class="userf-footer">
                        <p class="userf-footer-note">{{ __('الصلاحيات المباشرة المحفوظة هنا تخص المستخدم نفسه، بينما تستمر الصلاحيات الموروثة بالظهور تلقائيًا من الإدارات المرتبطة.') }}</p>

                        <div class="userf-footer-actions">
                            <button type="submit" class="userf-btn">
                                <i class="bi bi-save2"></i>
                                {{ __('حفظ التغييرات') }}
                            </button>
                            <a href="{{ route('users.show', $user) }}" class="userf-btn-muted">
                                <i class="bi bi-eye"></i>
                                {{ __('عرض') }}
                            </a>
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
                        <i class="bi bi-person-badge"></i>
                        {{ __('ملخص سريع') }}
                    </h3>
                    <div class="userf-stat-grid">
                        <div class="userf-stat-box">
                            <strong>#{{ $user->id }}</strong>
                            <span>{{ __('رقم المستخدم') }}</span>
                        </div>
                        <div class="userf-stat-box">
                            <strong>{{ $user->permissions->count() }}</strong>
                            <span>{{ __('صلاحية مباشرة') }}</span>
                        </div>
                        <div class="userf-stat-box">
                            <strong>{{ $user->inherited_permissions->count() }}</strong>
                            <span>{{ __('صلاحية موروثة') }}</span>
                        </div>
                        <div class="userf-stat-box">
                            <strong>{{ $user->effective_permissions->count() }}</strong>
                            <span>{{ __('إجمالي الصلاحيات') }}</span>
                        </div>
                    </div>
                </section>

                <section class="userf-mini-card">
                    <h3 class="userf-mini-title">
                        <i class="bi bi-clock-history"></i>
                        {{ __('حالة الحساب') }}
                    </h3>
                    <div class="userf-meta-list">
                        <div class="userf-meta-item">
                            <i class="bi bi-calendar-plus"></i>
                            <div>{{ __('أُنشئ في') }}: {{ $user->created_at->format('Y-m-d H:i') }}</div>
                        </div>
                        <div class="userf-meta-item">
                            <i class="bi bi-arrow-repeat"></i>
                            <div>{{ __('آخر تحديث') }}: {{ $user->updated_at->format('Y-m-d H:i') }}</div>
                        </div>
                        <div class="userf-meta-item">
                            <i class="bi bi-building"></i>
                            <div>{{ __('الإدارة الحالية') }}: {{ $user->department_names ?: __('بدون إدارة') }}</div>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</div>
@endsection

@include('users.partials.form-scripts')
