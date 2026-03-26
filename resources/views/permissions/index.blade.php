@extends('layouts.app')

@section('title', __('الصلاحيات'))

@php
    $permissionsCollection = $permissions->getCollection();
    $totalPermissions = $permissions->total();
    $pagePermissionsCount = $permissionsCollection->count();
    $linkedDepartmentsCount = $permissionsCollection->sum(fn ($permission) => $permission->departments->count());
    $moduleCount = $permissionsCollection->pluck('module_display')->filter()->unique()->count();
@endphp

@push('styles')
<style>
    .perm-page { padding: 8px 0 28px; color: var(--text-primary); font-size: 1rem; }
    .perm-shell { display: flex; flex-direction: column; gap: 24px; }
    .perm-hero { position: relative; overflow: hidden; border-radius: 28px; padding: 30px; background: radial-gradient(circle at top left, rgba(196, 168, 90, 0.32), transparent 34%), linear-gradient(135deg, #fffaf0 0%, #ffffff 48%, #f5efe2 100%); border: 1px solid rgba(170, 134, 63, 0.16); box-shadow: 0 18px 48px rgba(15, 23, 42, 0.08); }
    .perm-hero-inner { position: relative; z-index: 1; display: flex; align-items: center; justify-content: space-between; gap: 18px; flex-wrap: wrap; }
    .perm-badge { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 12px; padding: 8px 14px; border-radius: 999px; background: rgba(170, 134, 63, 0.1); color: var(--primary-color); font-size: 1rem; font-weight: 800; }
    .perm-title { margin: 0; font-size: clamp(2rem, 3vw, 2.7rem); font-weight: 900; color: var(--text-primary); line-height: 1.2; }
    .perm-subtitle { margin: 12px 0 0; max-width: 780px; color: var(--text-secondary); font-size: 1.06rem; line-height: 1.9; }
    .perm-actions { display: flex; gap: 12px; flex-wrap: wrap; }
    .perm-primary-btn, .perm-secondary-btn, .perm-action-btn { display: inline-flex; align-items: center; justify-content: center; gap: 10px; border: 0; text-decoration: none !important; font-weight: 800; transition: transform 0.25s ease, box-shadow 0.25s ease; }
    .perm-primary-btn, .perm-secondary-btn { min-height: 52px; padding: 14px 20px; border-radius: 18px; font-size: 1rem; }
    .perm-primary-btn { background: linear-gradient(135deg, var(--primary-color), #c49b48); color: #fff !important; box-shadow: 0 18px 28px rgba(170, 134, 63, 0.24); }
    .perm-secondary-btn { background: rgba(255, 255, 255, 0.9); color: var(--text-primary) !important; border: 1px solid rgba(170, 134, 63, 0.16); }
    .perm-primary-btn:hover, .perm-secondary-btn:hover, .perm-action-btn:hover { transform: translateY(-2px); }
    .perm-stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; }
    .perm-stat-card, .perm-toolbar, .perm-list-card { border-radius: 24px; background: rgba(255, 255, 255, 0.96); border: 1px solid rgba(170, 134, 63, 0.14); box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06); }
    .perm-stat-card { padding: 22px 20px; }
    .perm-stat-icon { width: 52px; height: 52px; border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; background: linear-gradient(135deg, rgba(170, 134, 63, 0.16), rgba(170, 134, 63, 0.06)); color: var(--primary-color); font-size: 1.4rem; margin-bottom: 16px; }
    .perm-stat-value { margin: 0; font-size: 2rem; font-weight: 900; color: var(--text-primary); }
    .perm-stat-label { margin: 6px 0 0; color: var(--text-secondary); font-size: 1rem; font-weight: 700; }
    .perm-toolbar { display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; padding: 18px 20px; }
    .perm-search { position: relative; flex: 1 1 340px; }
    .perm-search i { position: absolute; top: 50%; inset-inline-start: 16px; transform: translateY(-50%); color: var(--text-light); font-size: 1rem; }
    .perm-search input { width: 100%; height: 54px; padding-inline-start: 46px; padding-inline-end: 18px; border-radius: 18px; border: 1px solid rgba(170, 134, 63, 0.16); background: #fff; color: var(--text-primary); font-size: 1rem; }
    .perm-toolbar-meta { display: flex; gap: 10px; flex-wrap: wrap; }
    .perm-chip { display: inline-flex; align-items: center; gap: 8px; min-height: 44px; padding: 10px 14px; border-radius: 14px; background: #f8f5ed; border: 1px solid rgba(170, 134, 63, 0.14); color: var(--text-secondary); font-size: 0.96rem; font-weight: 700; }
    .perm-list-card { overflow: hidden; }
    .perm-list-head, .perm-row { display: grid; grid-template-columns: minmax(280px, 1.6fr) minmax(170px, 0.9fr) minmax(210px, 1fr) minmax(220px, 0.9fr) minmax(230px, 1fr); gap: 16px; align-items: center; padding: 18px 22px; }
    .perm-list-head { background: linear-gradient(180deg, #fff9ef 0%, #fcf7ee 100%); color: var(--text-secondary); font-size: 0.98rem; font-weight: 800; border-bottom: 1px solid rgba(170, 134, 63, 0.12); }
    .perm-list-body { padding: 8px 10px 12px; }
    .perm-row { margin: 10px 10px 0; border-radius: 22px; background: #fff; border: 1px solid rgba(226, 232, 240, 0.9); transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease; }
    .perm-row:hover { transform: translateY(-3px); border-color: rgba(170, 134, 63, 0.22); box-shadow: 0 18px 34px rgba(15, 23, 42, 0.08); }
    .perm-row.is-hidden { display: none; }
    .perm-main { display: flex; align-items: flex-start; gap: 14px; min-width: 0; }
    .perm-avatar { width: 54px; height: 54px; border-radius: 18px; display: inline-flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--primary-color), #cba55c); color: #fff; font-size: 1.2rem; box-shadow: 0 12px 24px rgba(170, 134, 63, 0.18); flex-shrink: 0; }
    .perm-name { margin: 0; color: var(--text-primary); font-size: 1.12rem; font-weight: 900; line-height: 1.5; }
    .perm-subline { margin-top: 6px; color: var(--text-secondary); font-size: 0.93rem; line-height: 1.7; overflow-wrap: anywhere; }
    .perm-field { display: flex; flex-direction: column; gap: 6px; min-width: 0; }
    .perm-field-label { color: var(--text-light); font-size: 0.86rem; font-weight: 700; }
    .perm-field-value { color: var(--text-primary); font-size: 1rem; font-weight: 700; line-height: 1.6; word-break: break-word; }
    .perm-field-value code { background: rgba(15, 23, 42, 0.06); color: var(--text-secondary); border-radius: 10px; padding: 6px 10px; display: inline-flex; max-width: 100%; overflow-wrap: anywhere; }
    .perm-department-stack { display: flex; flex-wrap: wrap; gap: 8px; }
    .perm-department-pill { display: inline-flex; align-items: center; gap: 6px; padding: 7px 10px; border-radius: 999px; background: rgba(170, 134, 63, 0.1); color: var(--primary-color); font-size: 0.85rem; font-weight: 800; }
    .perm-actions-stack { display: flex; align-items: center; justify-content: flex-end; gap: 8px; flex-wrap: wrap; }
    .perm-action-btn { min-width: 44px; height: 44px; padding: 0 14px; border-radius: 14px; font-size: 0.95rem; }
    .perm-action-btn.view { background: rgba(14, 165, 233, 0.12); color: #0284c7; }
    .perm-action-btn.edit { background: rgba(217, 119, 6, 0.12); color: #b45309; }
    .perm-action-btn.delete { background: rgba(220, 38, 38, 0.1); color: var(--danger-color); }
    .perm-empty { display: none; padding: 48px 24px 54px; text-align: center; }
    .perm-empty.show { display: block; }
    .perm-empty-icon { width: 74px; height: 74px; margin: 0 auto 16px; border-radius: 22px; display: flex; align-items: center; justify-content: center; background: rgba(170, 134, 63, 0.12); color: var(--primary-color); font-size: 1.8rem; }
    .perm-pagination { padding: 22px; border-top: 1px solid rgba(170, 134, 63, 0.1); }
    html[data-theme="dark"] .perm-hero { background: radial-gradient(circle at top left, rgba(141, 110, 43, 0.28), transparent 34%), linear-gradient(135deg, rgba(17, 24, 39, 0.98) 0%, rgba(15, 23, 42, 0.98) 48%, rgba(30, 41, 59, 0.98) 100%); border-color: rgba(148, 163, 184, 0.14); box-shadow: 0 24px 52px rgba(2, 6, 23, 0.34); }
    html[data-theme="dark"] .perm-secondary-btn, html[data-theme="dark"] .perm-stat-card, html[data-theme="dark"] .perm-toolbar, html[data-theme="dark"] .perm-list-card, html[data-theme="dark"] .perm-row, html[data-theme="dark"] .perm-chip, html[data-theme="dark"] .perm-empty { background: rgba(15, 23, 42, 0.92); border-color: rgba(148, 163, 184, 0.14); color: var(--text-primary); box-shadow: 0 18px 38px rgba(2, 6, 23, 0.28); }
    html[data-theme="dark"] .perm-list-head, html[data-theme="dark"] .perm-pagination { background: rgba(15, 23, 42, 0.78); border-color: rgba(148, 163, 184, 0.12); }
    html[data-theme="dark"] .perm-search input { background: rgba(15, 23, 42, 0.95); border-color: rgba(148, 163, 184, 0.2); color: var(--text-primary); }
    html[data-theme="dark"] .perm-badge, html[data-theme="dark"] .perm-department-pill { background: rgba(141, 110, 43, 0.16); color: #f6deb0; }
    html[data-theme="dark"] .perm-field-value code { background: rgba(148, 163, 184, 0.12); color: var(--text-secondary); }
    @media (max-width: 1279px) { .perm-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); } .perm-list-head { display: none; } .perm-row { grid-template-columns: 1fr 1fr; } .perm-main, .perm-actions-stack { grid-column: 1 / -1; } .perm-actions-stack { justify-content: flex-start; } }
    @media (max-width: 767px) { .perm-stats { grid-template-columns: 1fr; } .perm-row { grid-template-columns: 1fr; padding: 16px; margin: 8px 0 0; } }
</style>
@endpush

@section('content')
<div class="perm-page">
    <div class="perm-shell">
        <section class="perm-hero">
            <div class="perm-hero-inner">
                <div>
                    <span class="perm-badge">
                        <i class="bi bi-shield-lock-fill"></i>
                        {{ __('إدارة الصلاحيات') }}
                    </span>
                    <h1 class="perm-title">{{ __('الصلاحيات') }}</h1>
                </div>

                <div class="perm-actions">
                    <a href="{{ route('permissions.create') }}" class="perm-primary-btn">
                        <i class="bi bi-plus-circle-fill"></i>
                        {{ __('إضافة صلاحية جديدة') }}
                    </a>
                    <a href="{{ route('dashboard') }}" class="perm-secondary-btn">
                        <i class="bi bi-grid-1x2-fill"></i>
                        {{ __('العودة إلى لوحة التحكم') }}
                    </a>
                </div>
            </div>
        </section>

        <section class="perm-stats">
            <article class="perm-stat-card">
                <span class="perm-stat-icon"><i class="bi bi-key-fill"></i></span>
                <p class="perm-stat-value">{{ $totalPermissions }}</p>
                <p class="perm-stat-label">{{ __('إجمالي الصلاحيات') }}</p>
            </article>
            <article class="perm-stat-card">
                <span class="perm-stat-icon"><i class="bi bi-grid-fill"></i></span>
                <p class="perm-stat-value">{{ $moduleCount }}</p>
                <p class="perm-stat-label">{{ __('وحدات ظاهرة في الصفحة') }}</p>
            </article>
            <article class="perm-stat-card">
                <span class="perm-stat-icon"><i class="bi bi-diagram-3-fill"></i></span>
                <p class="perm-stat-value">{{ $linkedDepartmentsCount }}</p>
                <p class="perm-stat-label">{{ __('إجمالي الروابط مع الإدارات') }}</p>
            </article>
            <article class="perm-stat-card">
                <span class="perm-stat-icon"><i class="bi bi-collection-fill"></i></span>
                <p class="perm-stat-value">{{ $pagePermissionsCount }}</p>
                <p class="perm-stat-label">{{ __('عناصر الصفحة الحالية') }}</p>
            </article>
        </section>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <section class="perm-toolbar">
            <div class="perm-search">
                <i class="bi bi-search"></i>
                <input type="search" id="permissionsSearch" placeholder="{{ __('ابحث باسم الصلاحية أو الكود أو الوحدة أو الإدارة...') }}" autocomplete="off">
            </div>

            <div class="perm-toolbar-meta">
                <span class="perm-chip">
                    <i class="bi bi-eye-fill"></i>
                    <span id="visiblePermissionsCount">{{ $pagePermissionsCount }}</span>
                    {{ __('نتيجة ظاهرة') }}
                </span>
                <span class="perm-chip">
                    <i class="bi bi-collection-fill"></i>
                    {{ __('صفحة') }} {{ $permissions->currentPage() }} / {{ $permissions->lastPage() }}
                </span>
            </div>
        </section>

        <section class="perm-list-card">
            @if($pagePermissionsCount > 0)
                <div class="perm-list-head">
                    <div>{{ __('الصلاحية') }}</div>
                    <div>{{ __('الوحدة') }}</div>
                    <div>{{ __('المعرّف') }}</div>
                    <div>{{ __('الإدارات') }}</div>
                    <div>{{ __('الإجراءات') }}</div>
                </div>

                <div class="perm-list-body">
                    @foreach($permissions as $permission)
                        @php
                            $departmentsText = $permission->departments->pluck('full_name')->implode(' ');
                            $searchableText = implode(' ', [
                                $permission->display_name,
                                $permission->name,
                                $permission->slug,
                                $permission->module_display,
                                $departmentsText,
                                $permission->description,
                            ]);
                        @endphp
                        <article class="perm-row perm-search-item" data-search="{{ mb_strtolower($searchableText) }}">
                            <div class="perm-main">
                                <div class="perm-avatar">
                                    <i class="bi bi-key-fill"></i>
                                </div>
                                <div style="min-width: 0;">
                                    <h3 class="perm-name">{{ $permission->display_name }}</h3>
                                    <div class="perm-subline"><code>{{ $permission->name }}</code></div>
                                    @if($permission->description)
                                        <div class="perm-subline">{{ $permission->description }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="perm-field">
                                <span class="perm-field-label">{{ __('الوحدة') }}</span>
                                <span class="perm-field-value">{{ $permission->module_display }}</span>
                            </div>

                            <div class="perm-field">
                                <span class="perm-field-label">{{ __('المعرّف') }}</span>
                                <span class="perm-field-value"><code>{{ $permission->slug }}</code></span>
                            </div>

                            <div class="perm-field">
                                <span class="perm-field-label">{{ __('الإدارات المرتبطة') }}</span>
                                @if($permission->departments->isNotEmpty())
                                    <div class="perm-department-stack">
                                        @foreach($permission->departments->take(3) as $department)
                                            <span class="perm-department-pill"><i class="bi bi-building"></i>{{ $department->name }}</span>
                                        @endforeach
                                        @if($permission->departments->count() > 3)
                                            <span class="perm-department-pill">+{{ $permission->departments->count() - 3 }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="perm-field-value">{{ __('بدون إدارات مرتبطة') }}</span>
                                @endif
                            </div>

                            <div class="perm-actions-stack">
                                <a href="{{ route('permissions.show', $permission) }}" class="perm-action-btn view" title="{{ __('عرض') }}">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="{{ route('permissions.edit', $permission) }}" class="perm-action-btn edit" title="{{ __('تعديل') }}">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('permissions.destroy', $permission) }}" method="POST" style="display: inline-flex;" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذه الصلاحية؟') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="perm-action-btn delete" title="{{ __('حذف') }}">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="perm-empty" id="permissionsEmptyState">
                    <div class="perm-empty-icon">
                        <i class="bi bi-search-heart"></i>
                    </div>
                    <h3>{{ __('لا توجد نتائج مطابقة') }}</h3>
                    <p>{{ __('جرّب البحث باسم عربي أو بالكود البرمجي أو باسم الوحدة أو الإدارة للوصول إلى الصلاحية المطلوبة.') }}</p>
                </div>

                <div class="perm-pagination">
                    {{ $permissions->links() }}
                </div>
            @else
                <div class="perm-empty show">
                    <div class="perm-empty-icon">
                        <i class="bi bi-key"></i>
                    </div>
                    <h3>{{ __('لا توجد صلاحيات بعد') }}</h3>
                    <p>{{ __('ابدأ بإضافة أول صلاحية لتظهر هنا داخل قائمة حديثة تدعم البحث وعرض الإدارات المرتبطة بها.') }}</p>
                </div>
            @endif
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('permissionsSearch');
        const rows = Array.from(document.querySelectorAll('.perm-search-item'));
        const visibleCount = document.getElementById('visiblePermissionsCount');
        const emptyState = document.getElementById('permissionsEmptyState');
        const pagination = document.querySelector('.perm-pagination');

        if (!searchInput || rows.length === 0) {
            return;
        }

        function normalize(value) {
            return (value || '').toString().toLowerCase().trim();
        }

        function filterRows() {
            const query = normalize(searchInput.value);
            let shown = 0;

            rows.forEach(function (row) {
                const haystack = normalize(row.getAttribute('data-search'));
                const match = query === '' || haystack.indexOf(query) !== -1;
                row.classList.toggle('is-hidden', !match);

                if (match) {
                    shown += 1;
                }
            });

            if (visibleCount) {
                visibleCount.textContent = shown;
            }

            if (emptyState) {
                emptyState.classList.toggle('show', shown === 0);
            }

            if (pagination) {
                pagination.style.display = shown === 0 ? 'none' : '';
            }
        }

        searchInput.addEventListener('input', filterRows);
    });
</script>
@endpush
