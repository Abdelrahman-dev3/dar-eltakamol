@extends('layouts.app')

@section('title', __('تعديل الصلاحية'))

@php
    $linkedDepartmentsCount = $permission->departments->count();
@endphp

@include('permissions.partials.form-styles')

@section('content')
<div class="container-fluid permf-page">
    <div class="permf-shell">
        <section class="permf-hero">
            <div class="permf-hero-inner">
                <div>
                    <span class="permf-badge">
                        <i class="bi bi-pencil-square"></i>
                        {{ __('تعديل الصلاحية') }}
                    </span>
                    <h1 class="permf-title">{{ $permission->display_name }}</h1>
                    <p class="permf-subtitle">{{ __('حدّث الاسم البرمجي أو الوحدة أو الإدارات المرتبطة، وسيستمر عرض الصلاحية بالعربي في الواجهات الإدارية مع الاحتفاظ بالمرجع التقني عند الحاجة.') }}</p>
                </div>

                <div class="permf-actions">
                    <a href="{{ route('permissions.show', $permission) }}" class="permf-btn-muted">
                        <i class="bi bi-eye-fill"></i>
                        {{ __('عرض التفاصيل') }}
                    </a>
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
                        <span class="permf-panel-icon"><i class="bi bi-shield-check"></i></span>
                        <div>
                            <h2 class="permf-panel-title">{{ __('تحديث بيانات الصلاحية') }}</h2>
                            <p class="permf-panel-subtitle">{{ __('راجع الكود البرمجي والوحدة والوصف ثم حدّث الإدارات التي يجب أن ترث هذه الصلاحية أو ترتبط بها داخل النظام.') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('permissions.update', $permission) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('permissions.partials.form-fields', [
                        'isEdit' => true,
                        'permission' => $permission,
                        'modules' => $modules,
                        'departments' => $departments,
                    ])

                    <div class="permf-footer">
                        <p class="permf-footer-note">{{ __('أي تعديل هنا سيؤثر على عرض الصلاحية للمشرفين وعلى الإدارات المرتبطة بها، لذلك يفضّل الحفاظ على نمط تسمية ثابت وواضح.') }}</p>

                        <div class="permf-footer-actions">
                            <button type="submit" class="permf-btn">
                                <i class="bi bi-check2-circle"></i>
                                {{ __('حفظ التغييرات') }}
                            </button>
                            <a href="{{ route('permissions.show', $permission) }}" class="permf-btn-muted">
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
                        <i class="bi bi-info-circle"></i>
                        {{ __('ملخص الصلاحية') }}
                    </h3>
                    <div class="permf-stat-grid">
                        <div class="permf-stat-box">
                            <strong>#{{ $permission->id }}</strong>
                            <span>{{ __('رقم الصلاحية') }}</span>
                        </div>
                        <div class="permf-stat-box">
                            <strong>{{ $linkedDepartmentsCount }}</strong>
                            <span>{{ __('إدارة مرتبطة') }}</span>
                        </div>
                        <div class="permf-stat-box">
                            <strong>{{ $permission->module_display }}</strong>
                            <span>{{ __('الوحدة الحالية') }}</span>
                        </div>
                        <div class="permf-stat-box">
                            <strong>{{ $permission->created_at->format('Y-m-d') }}</strong>
                            <span>{{ __('تاريخ الإنشاء') }}</span>
                        </div>
                    </div>
                </section>

                <section class="permf-mini-card">
                    <h3 class="permf-mini-title">
                        <i class="bi bi-lightbulb"></i>
                        {{ __('تنبيه') }}
                    </h3>
                    <div class="permf-tip-list">
                        <div class="permf-tip-item">
                            <i class="bi bi-check2-circle"></i>
                            <div>{{ __('الاسم العربي الظاهر في النظام يتم اشتقاقه من الاسم البرمجي، لذلك أي تعديل في الكود سيغيّر العرض العربي تلقائيًا.') }}</div>
                        </div>
                        <div class="permf-tip-item">
                            <i class="bi bi-check2-circle"></i>
                            <div>{{ __('إذا كانت الصلاحية مستخدمة داخل إدارات أو مستخدمين، فالأفضل تعديلها بحذر للحفاظ على اتساق الصلاحيات داخل النظام.') }}</div>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</div>
@endsection

@include('permissions.partials.form-scripts')
