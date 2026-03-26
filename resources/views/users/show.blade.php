@extends('layouts.app')

@php
    $directPermissions = $user->permissions;
    $inheritedPermissions = $user->inherited_permissions;
    $effectivePermissions = $user->effective_permissions;
@endphp

@section('title', __('عرض تفاصيل المستخدم'))

@push('styles')
<style>
    .user-show-page {
        padding: 10px 0 30px;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .user-show-shell {
        display: flex;
        flex-direction: column;
        gap: 22px;
    }

    .user-show-hero,
    .user-show-card,
    .user-show-stat-card {
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.07);
    }

    .user-show-hero {
        padding: 28px;
        background:
            radial-gradient(circle at top right, rgba(196, 168, 90, 0.28), transparent 30%),
            linear-gradient(135deg, #fff8ed 0%, #ffffff 44%, #f5ecde 100%);
    }

    .user-show-hero-inner {
        display: grid;
        grid-template-columns: minmax(0, 1.6fr) minmax(280px, 0.9fr);
        gap: 20px;
        align-items: center;
    }

    .user-show-profile {
        display: flex;
        align-items: center;
        gap: 18px;
        flex-wrap: wrap;
    }

    .user-show-avatar {
        width: 108px;
        height: 108px;
        border-radius: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary-color), #d4b066);
        color: #fff;
        font-size: 2.4rem;
        box-shadow: 0 20px 36px rgba(170, 134, 63, 0.22);
        flex-shrink: 0;
    }

    .user-show-badge,
    .user-show-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-radius: 999px;
        font-weight: 800;
    }

    .user-show-badge {
        margin-bottom: 14px;
        padding: 8px 14px;
        background: rgba(170, 134, 63, 0.12);
        color: var(--primary-color);
    }

    .user-show-title {
        margin: 0;
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 900;
        color: var(--text-primary);
    }

    .user-show-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 16px;
    }

    .user-show-chip {
        padding: 10px 14px;
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(170, 134, 63, 0.12);
        color: var(--text-primary);
    }

    .user-show-actions {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .user-show-btn,
    .user-show-btn-muted,
    .user-show-btn-danger {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        padding: 13px 18px;
        border-radius: 18px;
        text-decoration: none !important;
        border: 1px solid transparent;
        font-size: 1rem;
        font-weight: 800;
    }

    .user-show-btn {
        background: linear-gradient(135deg, var(--primary-color), #c49b48);
        color: #fff !important;
        box-shadow: 0 16px 28px rgba(170, 134, 63, 0.24);
    }

    .user-show-btn-muted {
        background: rgba(255, 255, 255, 0.9);
        color: var(--text-primary) !important;
        border-color: rgba(170, 134, 63, 0.14);
    }

    .user-show-btn-danger {
        background: rgba(220, 38, 38, 0.08);
        color: var(--danger-color) !important;
        border-color: rgba(220, 38, 38, 0.16);
    }

    .user-show-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }

    .user-show-stat-card {
        padding: 20px;
    }

    .user-show-stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(170, 134, 63, 0.12);
        color: var(--primary-color);
        font-size: 1.3rem;
        margin-bottom: 14px;
    }

    .user-show-stat-value {
        margin: 0;
        font-size: 1.9rem;
        font-weight: 900;
        color: var(--text-primary);
        word-break: break-word;
    }

    .user-show-stat-label {
        margin: 6px 0 0;
        color: var(--text-secondary);
        font-weight: 700;
    }

    .user-show-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .user-show-card {
        padding: 24px;
    }

    .user-show-card.full-width {
        grid-column: 1 / -1;
    }

    .user-show-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .user-show-card-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0;
        font-size: 1.3rem;
        font-weight: 900;
        color: var(--text-primary);
    }

    .user-show-card-title i {
        width: 46px;
        height: 46px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(170, 134, 63, 0.12);
        color: var(--primary-color);
    }

    .user-show-card-note {
        color: var(--text-secondary);
        font-size: 0.95rem;
        line-height: 1.8;
    }

    .user-detail-list {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .user-detail-item {
        padding: 16px;
        border-radius: 20px;
        background: rgba(248, 250, 252, 0.92);
        border: 1px solid rgba(170, 134, 63, 0.10);
    }

    .user-detail-label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-secondary);
        font-size: 0.92rem;
        font-weight: 700;
    }

    .user-detail-value {
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 800;
        word-break: break-word;
    }

    .user-permissions-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .user-permission-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 14px;
        background: rgba(170, 134, 63, 0.10);
        color: var(--primary-color);
        font-size: 0.92rem;
        font-weight: 800;
    }

    .user-empty-note {
        padding: 14px 16px;
        border-radius: 16px;
        background: rgba(248, 250, 252, 0.94);
        color: var(--text-secondary);
        border: 1px dashed rgba(170, 134, 63, 0.16);
    }

    html[data-theme="dark"] .user-show-hero {
        background:
            radial-gradient(circle at top right, rgba(141, 110, 43, 0.28), transparent 30%),
            linear-gradient(135deg, rgba(17, 24, 39, 0.98) 0%, rgba(15, 23, 42, 0.98) 44%, rgba(30, 41, 59, 0.98) 100%);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 24px 52px rgba(2, 6, 23, 0.34);
    }

    html[data-theme="dark"] .user-show-card,
    html[data-theme="dark"] .user-show-stat-card,
    html[data-theme="dark"] .user-permission-chip,
    html[data-theme="dark"] .user-empty-note {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 18px 38px rgba(2, 6, 23, 0.28);
    }

    html[data-theme="dark"] .user-detail-item,
    html[data-theme="dark"] .user-show-chip {
        background: rgba(15, 23, 42, 0.78);
        border-color: rgba(148, 163, 184, 0.12);
    }

    html[data-theme="dark"] .user-show-badge {
        background: rgba(141, 110, 43, 0.16);
        color: #f6deb0;
    }

    @media (max-width: 1399px) {
        .user-show-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 1199px) {
        .user-show-hero-inner,
        .user-show-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .user-show-stats,
        .user-detail-list {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid user-show-page">
    <div class="user-show-shell">
        <section class="user-show-hero">
            <div class="user-show-hero-inner">
                <div>
                    <div class="user-show-profile">
                        <div class="user-show-avatar">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div>
                            <span class="user-show-badge">
                                <i class="bi bi-person-badge-fill"></i>
                                {{ __('مستخدم') }} #{{ $user->id }}
                            </span>
                            <h1 class="user-show-title">{{ $user->name }}</h1>
                            <div class="user-show-meta">
                                <span class="user-show-chip"><i class="bi bi-envelope"></i>{{ $user->email }}</span>
                                <span class="user-show-chip"><i class="bi bi-building"></i>{{ $user->department_names ?: __('بدون إدارة') }}</span>
                                <span class="user-show-chip"><i class="bi bi-key"></i>{{ $effectivePermissions->count() }} {{ __('صلاحية فعّالة') }}</span>
                                <span class="user-show-chip"><i class="bi bi-person-check"></i>{{ optional($user->contributor)->name ?: __('غير مرتبط بمساهم') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="user-show-actions">
                    <a href="{{ route('users.edit', $user) }}" class="user-show-btn">
                        <i class="bi bi-pencil-square"></i>
                        {{ __('تعديل المستخدم') }}
                    </a>
                    <a href="{{ route('users.index') }}" class="user-show-btn-muted">
                        <i class="bi bi-arrow-right-circle"></i>
                        {{ __('العودة للقائمة') }}
                    </a>
                    @if($user->id !== auth()->id())
                        <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا المستخدم؟') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="user-show-btn-danger">
                                <i class="bi bi-trash3"></i>
                                {{ __('حذف المستخدم') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </section>

        <section class="user-show-stats">
            <div class="user-show-stat-card">
                <span class="user-show-stat-icon"><i class="bi bi-key-fill"></i></span>
                <p class="user-show-stat-value">{{ $directPermissions->count() }}</p>
                <p class="user-show-stat-label">{{ __('صلاحيات مباشرة') }}</p>
            </div>
            <div class="user-show-stat-card">
                <span class="user-show-stat-icon"><i class="bi bi-arrow-repeat"></i></span>
                <p class="user-show-stat-value">{{ $inheritedPermissions->count() }}</p>
                <p class="user-show-stat-label">{{ __('صلاحيات موروثة') }}</p>
            </div>
            <div class="user-show-stat-card">
                <span class="user-show-stat-icon"><i class="bi bi-shield-check"></i></span>
                <p class="user-show-stat-value">{{ $effectivePermissions->count() }}</p>
                <p class="user-show-stat-label">{{ __('إجمالي الصلاحيات الفعالة') }}</p>
            </div>
            <div class="user-show-stat-card">
                <span class="user-show-stat-icon"><i class="bi bi-calendar2-plus"></i></span>
                <p class="user-show-stat-value">{{ $user->created_at->format('Y-m-d') }}</p>
                <p class="user-show-stat-label">{{ __('تاريخ الإنشاء') }}</p>
            </div>
        </section>

        <div class="user-show-grid">
            <section class="user-show-card">
                <div class="user-show-card-head">
                    <h2 class="user-show-card-title"><i class="bi bi-info-circle"></i>{{ __('المعلومات الأساسية') }}</h2>
                    <span class="user-show-card-note">{{ __('بيانات الحساب الأساسية والربط الإداري الحالي.') }}</span>
                </div>

                <div class="user-detail-list">
                    <div class="user-detail-item">
                        <span class="user-detail-label">{{ __('الاسم') }}</span>
                        <div class="user-detail-value">{{ $user->name }}</div>
                    </div>
                    <div class="user-detail-item">
                        <span class="user-detail-label">{{ __('البريد الإلكتروني') }}</span>
                        <div class="user-detail-value">{{ $user->email }}</div>
                    </div>
                    <div class="user-detail-item">
                        <span class="user-detail-label">{{ __('رقم الهاتف') }}</span>
                        <div class="user-detail-value">{{ $user->phone ?: __('غير محدد') }}</div>
                    </div>
                    <div class="user-detail-item">
                        <span class="user-detail-label">{{ __('رقم الهوية') }}</span>
                        <div class="user-detail-value">{{ $user->id_number ?: __('غير محدد') }}</div>
                    </div>
                    <div class="user-detail-item">
                        <span class="user-detail-label">{{ __('الإدارات') }}</span>
                        <div class="user-detail-value">{{ $user->department_names ?: __('بدون إدارة') }}</div>
                    </div>
                    <div class="user-detail-item">
                        <span class="user-detail-label">{{ __('الشركات') }}</span>
                        <div class="user-detail-value">{{ $user->company_names ?: __('بدون شركة') }}</div>
                    </div>
                </div>
            </section>

            <section class="user-show-card">
                <div class="user-show-card-head">
                    <h2 class="user-show-card-title"><i class="bi bi-link-45deg"></i>{{ __('الربط والحالة') }}</h2>
                    <span class="user-show-card-note">{{ __('تفاصيل الارتباط بالمساهم والحالة الزمنية للحساب.') }}</span>
                </div>

                <div class="user-detail-list">
                    <div class="user-detail-item">
                        <span class="user-detail-label">{{ __('المساهم المرتبط') }}</span>
                        <div class="user-detail-value">{{ optional($user->contributor)->name ?: __('غير مرتبط بمساهم') }}</div>
                    </div>
                    <div class="user-detail-item">
                        <span class="user-detail-label">{{ __('تاريخ الإنشاء') }}</span>
                        <div class="user-detail-value">{{ $user->created_at->format('Y-m-d H:i:s') }}</div>
                    </div>
                    <div class="user-detail-item">
                        <span class="user-detail-label">{{ __('آخر تحديث') }}</span>
                        <div class="user-detail-value">{{ $user->updated_at->format('Y-m-d H:i:s') }}</div>
                    </div>
                    <div class="user-detail-item">
                        <span class="user-detail-label">{{ __('ملاحظة الصلاحيات') }}</span>
                        <div class="user-detail-value">{{ $inheritedPermissions->isNotEmpty() ? __('لديه صلاحيات موروثة من الإدارات المرتبطة.') : __('لا توجد صلاحيات موروثة حاليًا.') }}</div>
                    </div>
                </div>
            </section>

            <section class="user-show-card full-width">
                <div class="user-show-card-head">
                    <h2 class="user-show-card-title"><i class="bi bi-key-fill"></i>{{ __('الصلاحيات المباشرة') }}</h2>
                    <span class="user-show-card-note">{{ __('الصلاحيات التي تم تحديدها مباشرة لهذا المستخدم من صفحة المستخدمين.') }}</span>
                </div>

                @if($directPermissions->isNotEmpty())
                    <div class="user-permissions-list">
                        @foreach($directPermissions as $permission)
                            <span class="user-permission-chip">
                                <i class="bi bi-shield-lock"></i>
                                {{ $permission->display_name }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <div class="user-empty-note">{{ __('لا توجد صلاحيات مباشرة مضافة لهذا المستخدم.') }}</div>
                @endif
            </section>

            <section class="user-show-card full-width">
                <div class="user-show-card-head">
                    <h2 class="user-show-card-title"><i class="bi bi-arrow-repeat"></i>{{ __('الصلاحيات الموروثة') }}</h2>
                    <span class="user-show-card-note">{{ __('الصلاحيات التي يحصل عليها المستخدم من الإدارات المرتبطة به أو بالمساهم المرتبط به.') }}</span>
                </div>

                @if($inheritedPermissions->isNotEmpty())
                    <div class="user-permissions-list">
                        @foreach($inheritedPermissions as $permission)
                            <span class="user-permission-chip">
                                <i class="bi bi-diagram-2-fill"></i>
                                {{ $permission->display_name }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <div class="user-empty-note">{{ __('لا توجد صلاحيات موروثة لهذا المستخدم حاليًا.') }}</div>
                @endif
            </section>

            <section class="user-show-card full-width">
                <div class="user-show-card-head">
                    <h2 class="user-show-card-title"><i class="bi bi-shield-check"></i>{{ __('الصلاحيات الفعالة') }}</h2>
                    <span class="user-show-card-note">{{ __('الناتج النهائي الذي يجمع بين الصلاحيات المباشرة والموروثة بدون تكرار.') }}</span>
                </div>

                @if($effectivePermissions->isNotEmpty())
                    <div class="user-permissions-list">
                        @foreach($effectivePermissions as $permission)
                            <span class="user-permission-chip">
                                <i class="bi bi-check2-circle"></i>
                                {{ $permission->display_name }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <div class="user-empty-note">{{ __('لا توجد صلاحيات فعالة لهذا المستخدم حتى الآن.') }}</div>
                @endif
            </section>
        </div>
    </div>
</div>
@endsection
