@extends('layouts.app')

@section('title', __('عرض الصلاحية'))

@php
    $departments = $permission->departments;
    $linkedUsersCount = $departments->sum(fn ($department) => $department->users->count());
@endphp

@push('styles')
<style>
    .perm-show-page { padding: 8px 0 28px; color: var(--text-primary); font-size: 1rem; }
    .perm-show-shell { display: flex; flex-direction: column; gap: 24px; }
    .perm-show-hero, .perm-show-card, .perm-show-stat-card { border-radius: 28px; background: rgba(255, 255, 255, 0.96); border: 1px solid rgba(170, 134, 63, 0.14); box-shadow: 0 18px 42px rgba(15, 23, 42, 0.07); }
    .perm-show-hero { padding: 28px; background: radial-gradient(circle at top right, rgba(196, 168, 90, 0.28), transparent 30%), linear-gradient(135deg, #fff8ed 0%, #ffffff 44%, #f5ecde 100%); }
    .perm-show-hero-inner { display: grid; grid-template-columns: minmax(0, 1.6fr) minmax(280px, 0.9fr); gap: 20px; align-items: center; }
    .perm-show-profile { display: flex; align-items: center; gap: 18px; flex-wrap: wrap; }
    .perm-show-avatar { width: 108px; height: 108px; border-radius: 28px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--primary-color), #d4b066); color: #fff; font-size: 2.4rem; box-shadow: 0 20px 36px rgba(170, 134, 63, 0.22); flex-shrink: 0; }
    .perm-show-badge, .perm-show-chip { display: inline-flex; align-items: center; gap: 8px; border-radius: 999px; font-weight: 800; }
    .perm-show-badge { margin-bottom: 14px; padding: 8px 14px; background: rgba(170, 134, 63, 0.12); color: var(--primary-color); }
    .perm-show-title { margin: 0; font-size: clamp(2rem, 4vw, 3rem); font-weight: 900; color: var(--text-primary); }
    .perm-show-meta { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 16px; }
    .perm-show-chip { padding: 10px 14px; background: rgba(255, 255, 255, 0.92); border: 1px solid rgba(170, 134, 63, 0.12); color: var(--text-primary); }
    .perm-show-actions { display: flex; flex-direction: column; gap: 12px; }
    .perm-show-btn, .perm-show-btn-muted, .perm-show-btn-danger { display: inline-flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 13px 18px; border-radius: 18px; text-decoration: none !important; border: 1px solid transparent; font-size: 1rem; font-weight: 800; }
    .perm-show-btn { background: linear-gradient(135deg, var(--primary-color), #c49b48); color: #fff !important; box-shadow: 0 16px 28px rgba(170, 134, 63, 0.24); }
    .perm-show-btn-muted { background: rgba(255, 255, 255, 0.9); color: var(--text-primary) !important; border-color: rgba(170, 134, 63, 0.14); }
    .perm-show-btn-danger { background: rgba(220, 38, 38, 0.08); color: var(--danger-color) !important; border-color: rgba(220, 38, 38, 0.16); }
    .perm-show-stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; }
    .perm-show-stat-card { padding: 20px; }
    .perm-show-stat-icon { width: 50px; height: 50px; border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; background: rgba(170, 134, 63, 0.12); color: var(--primary-color); font-size: 1.3rem; margin-bottom: 14px; }
    .perm-show-stat-value { margin: 0; font-size: 1.9rem; font-weight: 900; color: var(--text-primary); }
    .perm-show-stat-label { margin: 6px 0 0; color: var(--text-secondary); font-weight: 700; }
    .perm-show-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; }
    .perm-show-card { padding: 24px; }
    .perm-show-card.full-width { grid-column: 1 / -1; }
    .perm-show-card-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 16px; }
    .perm-show-card-title { display: flex; align-items: center; gap: 12px; margin: 0; font-size: 1.3rem; font-weight: 900; color: var(--text-primary); }
    .perm-show-card-title i { width: 46px; height: 46px; border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; background: rgba(170, 134, 63, 0.12); color: var(--primary-color); }
    .perm-show-card-note { color: var(--text-secondary); font-size: 0.95rem; line-height: 1.8; }
    .perm-detail-list { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
    .perm-detail-item { padding: 16px; border-radius: 20px; background: rgba(248, 250, 252, 0.92); border: 1px solid rgba(170, 134, 63, 0.1); }
    .perm-detail-label { display: block; margin-bottom: 8px; color: var(--text-secondary); font-size: 0.92rem; font-weight: 700; }
    .perm-detail-value { color: var(--text-primary); font-size: 1rem; font-weight: 800; word-break: break-word; line-height: 1.7; }
    .perm-detail-value code { background: rgba(15, 23, 42, 0.06); color: var(--text-secondary); border-radius: 10px; padding: 6px 10px; display: inline-flex; max-width: 100%; overflow-wrap: anywhere; }
    .perm-linked-list { display: flex; flex-direction: column; gap: 14px; }
    .perm-linked-item { display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap; padding: 16px; border-radius: 20px; background: rgba(248, 250, 252, 0.92); border: 1px solid rgba(170, 134, 63, 0.1); }
    .perm-linked-name { color: var(--text-primary); font-size: 1.02rem; font-weight: 900; }
    .perm-linked-sub { margin-top: 6px; color: var(--text-secondary); line-height: 1.7; }
    .perm-linked-chips { display: flex; gap: 8px; flex-wrap: wrap; }
    .perm-linked-chip { display: inline-flex; align-items: center; gap: 6px; padding: 8px 12px; border-radius: 999px; background: rgba(170, 134, 63, 0.1); color: var(--primary-color); font-size: 0.88rem; font-weight: 800; }
    .perm-empty-note { padding: 14px 16px; border-radius: 16px; background: rgba(248, 250, 252, 0.94); color: var(--text-secondary); border: 1px dashed rgba(170, 134, 63, 0.16); }
    html[data-theme="dark"] .perm-show-hero { background: radial-gradient(circle at top right, rgba(141, 110, 43, 0.28), transparent 30%), linear-gradient(135deg, rgba(17, 24, 39, 0.98) 0%, rgba(15, 23, 42, 0.98) 44%, rgba(30, 41, 59, 0.98) 100%); border-color: rgba(148, 163, 184, 0.14); box-shadow: 0 24px 52px rgba(2, 6, 23, 0.34); }
    html[data-theme="dark"] .perm-show-card, html[data-theme="dark"] .perm-show-stat-card, html[data-theme="dark"] .perm-empty-note, html[data-theme="dark"] .perm-detail-item, html[data-theme="dark"] .perm-linked-item, html[data-theme="dark"] .perm-show-btn-muted { background: rgba(15, 23, 42, 0.92); border-color: rgba(148, 163, 184, 0.14); box-shadow: 0 18px 38px rgba(2, 6, 23, 0.28); }
    html[data-theme="dark"] .perm-show-chip { background: rgba(15, 23, 42, 0.78); border-color: rgba(148, 163, 184, 0.12); }
    html[data-theme="dark"] .perm-show-badge, html[data-theme="dark"] .perm-linked-chip { background: rgba(141, 110, 43, 0.16); color: #f6deb0; }
    html[data-theme="dark"] .perm-detail-value code { background: rgba(148, 163, 184, 0.12); color: var(--text-secondary); }
    @media (max-width: 1399px) { .perm-show-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 1199px) { .perm-show-hero-inner, .perm-show-grid { grid-template-columns: 1fr; } }
    @media (max-width: 767px) { .perm-show-stats, .perm-detail-list { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="container-fluid perm-show-page">
    <div class="perm-show-shell">
        <section class="perm-show-hero">
            <div class="perm-show-hero-inner">
                <div>
                    <div class="perm-show-profile">
                        <div class="perm-show-avatar">
                            <i class="bi bi-key-fill"></i>
                        </div>
                        <div>
                            <span class="perm-show-badge">
                                <i class="bi bi-shield-lock-fill"></i>
                                {{ __('صلاحية') }} #{{ $permission->id }}
                            </span>
                            <h1 class="perm-show-title">{{ $permission->display_name }}</h1>
                            <div class="perm-show-meta">
                                <span class="perm-show-chip"><i class="bi bi-grid-fill"></i>{{ $permission->module_display }}</span>
                                <span class="perm-show-chip"><i class="bi bi-diagram-3-fill"></i>{{ $departments->count() }} {{ __('إدارة مرتبطة') }}</span>
                                <span class="perm-show-chip"><i class="bi bi-people-fill"></i>{{ $linkedUsersCount }} {{ __('مستخدمًا متأثرًا') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="perm-show-actions">
                    <a href="{{ route('permissions.edit', $permission) }}" class="perm-show-btn">
                        <i class="bi bi-pencil-square"></i>
                        {{ __('تعديل الصلاحية') }}
                    </a>
                    <a href="{{ route('permissions.index') }}" class="perm-show-btn-muted">
                        <i class="bi bi-arrow-right-circle"></i>
                        {{ __('العودة إلى القائمة') }}
                    </a>
                    <form action="{{ route('permissions.destroy', $permission) }}" method="POST" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذه الصلاحية؟') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="perm-show-btn-danger">
                            <i class="bi bi-trash3"></i>
                            {{ __('حذف الصلاحية') }}
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <section class="perm-show-stats">
            <div class="perm-show-stat-card">
                <span class="perm-show-stat-icon"><i class="bi bi-diagram-3-fill"></i></span>
                <p class="perm-show-stat-value">{{ $departments->count() }}</p>
                <p class="perm-show-stat-label">{{ __('إدارات مرتبطة') }}</p>
            </div>
            <div class="perm-show-stat-card">
                <span class="perm-show-stat-icon"><i class="bi bi-people-fill"></i></span>
                <p class="perm-show-stat-value">{{ $linkedUsersCount }}</p>
                <p class="perm-show-stat-label">{{ __('مستخدمون داخل هذه الإدارات') }}</p>
            </div>
            <div class="perm-show-stat-card">
                <span class="perm-show-stat-icon"><i class="bi bi-calendar2-plus"></i></span>
                <p class="perm-show-stat-value">{{ $permission->created_at->format('Y-m-d') }}</p>
                <p class="perm-show-stat-label">{{ __('تاريخ الإنشاء') }}</p>
            </div>
            <div class="perm-show-stat-card">
                <span class="perm-show-stat-icon"><i class="bi bi-upc-scan"></i></span>
                <p class="perm-show-stat-value">#{{ $permission->id }}</p>
                <p class="perm-show-stat-label">{{ __('المعرّف الداخلي') }}</p>
            </div>
        </section>

        <div class="perm-show-grid">
            <section class="perm-show-card">
                <div class="perm-show-card-head">
                    <h2 class="perm-show-card-title"><i class="bi bi-info-circle"></i>{{ __('معلومات الصلاحية') }}</h2>
                    <span class="perm-show-card-note">{{ __('البيانات الأساسية التي تساعد المشرف على فهم الصلاحية وكيف تظهر داخل النظام.') }}</span>
                </div>

                <div class="perm-detail-list">
                    <div class="perm-detail-item">
                        <span class="perm-detail-label">{{ __('الاسم العربي') }}</span>
                        <div class="perm-detail-value">{{ $permission->display_name }}</div>
                    </div>
                    <div class="perm-detail-item">
                        <span class="perm-detail-label">{{ __('الاسم البرمجي') }}</span>
                        <div class="perm-detail-value"><code>{{ $permission->name }}</code></div>
                    </div>
                    <div class="perm-detail-item">
                        <span class="perm-detail-label">{{ __('المعرّف') }}</span>
                        <div class="perm-detail-value"><code>{{ $permission->slug }}</code></div>
                    </div>
                    <div class="perm-detail-item">
                        <span class="perm-detail-label">{{ __('الوحدة') }}</span>
                        <div class="perm-detail-value">{{ $permission->module_display }}</div>
                    </div>
                    <div class="perm-detail-item" style="grid-column: 1 / -1;">
                        <span class="perm-detail-label">{{ __('الوصف') }}</span>
                        <div class="perm-detail-value">{{ $permission->description ?: __('لا يوجد وصف مضاف لهذه الصلاحية حاليًا.') }}</div>
                    </div>
                </div>
            </section>

            <section class="perm-show-card">
                <div class="perm-show-card-head">
                    <h2 class="perm-show-card-title"><i class="bi bi-diagram-2-fill"></i>{{ __('الأثر الإداري') }}</h2>
                    <span class="perm-show-card-note">{{ __('ملخص سريع يوضح كيف تنتقل هذه الصلاحية داخل الإدارات المرتبطة بها.') }}</span>
                </div>

                <div class="perm-detail-list">
                    <div class="perm-detail-item">
                        <span class="perm-detail-label">{{ __('عدد الإدارات') }}</span>
                        <div class="perm-detail-value">{{ $departments->count() }}</div>
                    </div>
                    <div class="perm-detail-item">
                        <span class="perm-detail-label">{{ __('عدد المستخدمين داخلها') }}</span>
                        <div class="perm-detail-value">{{ $linkedUsersCount }}</div>
                    </div>
                    <div class="perm-detail-item" style="grid-column: 1 / -1;">
                        <span class="perm-detail-label">{{ __('ملاحظة') }}</span>
                        <div class="perm-detail-value">{{ $departments->isNotEmpty() ? __('هذه الصلاحية مرتبطة بإدارات، وبالتالي تظهر ضمن صلاحياتها الموروثة أو المرتبطة حسب منطق النظام.') : __('لا توجد إدارات مرتبطة بهذه الصلاحية حاليًا، لذلك لن تنتقل إداريًا حتى يتم ربطها.') }}</div>
                    </div>
                </div>
            </section>

            <section class="perm-show-card full-width">
                <div class="perm-show-card-head">
                    <h2 class="perm-show-card-title"><i class="bi bi-building-fill-gear"></i>{{ __('الإدارات المرتبطة') }}</h2>
                    <span class="perm-show-card-note">{{ __('كل إدارة مرتبطة هنا يمكن فتحها مباشرة لمراجعة بياناتها والمستخدمين المرتبطين بها.') }}</span>
                </div>

                @if($departments->isNotEmpty())
                    <div class="perm-linked-list">
                        @foreach($departments as $department)
                            <article class="perm-linked-item">
                                <div>
                                    <div class="perm-linked-name">
                                        <a href="{{ route('categories.show', $department) }}">{{ $department->name }}</a>
                                    </div>
                                    <div class="perm-linked-sub">
                                        {{ __('الشركة: ') }}{{ $department->parent?->name ?? __('غير محددة') }}
                                    </div>
                                </div>

                                <div class="perm-linked-chips">
                                    <span class="perm-linked-chip"><i class="bi bi-people-fill"></i>{{ $department->users->count() }} {{ __('مستخدم') }}</span>
                                    <span class="perm-linked-chip"><i class="bi bi-building"></i>{{ $department->full_name }}</span>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="perm-empty-note">{{ __('لا توجد إدارات مرتبطة بهذه الصلاحية حتى الآن.') }}</div>
                @endif
            </section>
        </div>
    </div>
</div>
@endsection
