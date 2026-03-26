@extends('layouts.app')

@section('title', __('المستخدمون'))

@php
    $usersCollection = $users->getCollection();
    $totalUsers = $users->total();
    $pageUsersCount = $usersCollection->count();
    $linkedContributorsCount = $usersCollection->filter(fn ($user) => !is_null($user->contributor))->count();
    $withDepartmentsCount = $usersCollection->filter(fn ($user) => $user->departments->isNotEmpty())->count();
    $withDirectPermissionsCount = $usersCollection->filter(fn ($user) => $user->permissions->isNotEmpty())->count();
@endphp

@push('styles')
<style>
    .users-page {
        padding: 8px 0 28px;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .users-shell {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .users-hero {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        padding: 30px;
        background:
            radial-gradient(circle at top left, rgba(196, 168, 90, 0.32), transparent 34%),
            linear-gradient(135deg, #fffaf0 0%, #ffffff 48%, #f5efe2 100%);
        border: 1px solid rgba(170, 134, 63, 0.16);
        box-shadow: 0 18px 48px rgba(15, 23, 42, 0.08);
    }

    .users-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
    }

    .users-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        padding: 7px 12px;
        border-radius: 999px;
        background: rgba(170, 134, 63, 0.10);
        color: var(--primary-color);
        font-size: 1.05rem;
        font-weight: 700;
    }

    .users-title {
        margin: 0;
        font-size: clamp(2rem, 3vw, 2.6rem);
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .users-subtitle {
        margin: 10px 0 0;
        max-width: 760px;
        color: var(--text-secondary);
        font-size: 1.12rem;
        line-height: 1.8;
    }

    .users-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .users-primary-btn,
    .users-secondary-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 20px;
        border-radius: 18px;
        text-decoration: none !important;
        font-size: 1.05rem;
        font-weight: 700;
        transition: transform 0.25s ease;
    }

    .users-primary-btn {
        background: linear-gradient(135deg, var(--primary-color), #c49b48);
        color: #fff;
        box-shadow: 0 18px 28px rgba(170, 134, 63, 0.24);
    }

    .users-secondary-btn {
        background: rgba(255, 255, 255, 0.88);
        color: var(--text-primary);
        border: 1px solid rgba(170, 134, 63, 0.16);
    }

    .users-primary-btn:hover,
    .users-secondary-btn:hover {
        transform: translateY(-2px);
    }

    .users-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }

    .users-stat-card,
    .users-toolbar,
    .users-list-card {
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
    }

    .users-stat-card {
        padding: 22px 20px;
    }

    .users-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(170, 134, 63, 0.16), rgba(170, 134, 63, 0.06));
        color: var(--primary-color);
        font-size: 1.4rem;
        margin-bottom: 16px;
    }

    .users-stat-value {
        margin: 0;
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-primary);
    }

    .users-stat-label {
        margin: 6px 0 0;
        color: var(--text-secondary);
        font-size: 1.02rem;
        font-weight: 600;
    }

    .users-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        padding: 18px 20px;
    }

    .users-search {
        position: relative;
        flex: 1 1 340px;
    }

    .users-search i {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        inset-inline-start: 16px;
        color: var(--text-light);
        font-size: 1.1rem;
    }

    .users-search input {
        width: 100%;
        height: 54px;
        padding-inline-start: 46px;
        padding-inline-end: 18px;
        border-radius: 18px;
        border: 1px solid rgba(170, 134, 63, 0.16);
        background: #fff;
        color: var(--text-primary);
        font-size: 1.04rem;
    }

    .users-toolbar-meta {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .users-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 44px;
        padding: 10px 14px;
        border-radius: 14px;
        background: #f8f5ed;
        border: 1px solid rgba(170, 134, 63, 0.14);
        color: var(--text-secondary);
        font-size: 0.96rem;
        font-weight: 700;
    }

    .users-list-card {
        overflow: hidden;
    }

    .users-list-head,
    .user-row {
        display: grid;
        grid-template-columns: minmax(260px, 1.8fr) minmax(180px, 1fr) minmax(180px, 1fr) minmax(210px, 1fr) minmax(230px, 1.1fr);
        gap: 16px;
        align-items: center;
        padding: 18px 22px;
    }

    .users-list-head {
        background: linear-gradient(180deg, #fff9ef 0%, #fcf7ee 100%);
        color: var(--text-secondary);
        font-size: 0.98rem;
        font-weight: 800;
        border-bottom: 1px solid rgba(170, 134, 63, 0.12);
    }

    .users-list-body {
        padding: 8px 10px 12px;
    }

    .user-row {
        margin: 10px 10px 0;
        border-radius: 22px;
        background: #fff;
        border: 1px solid rgba(226, 232, 240, 0.9);
        transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
    }

    .user-row:hover {
        transform: translateY(-3px);
        border-color: rgba(170, 134, 63, 0.22);
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.08);
    }

    .user-row.is-hidden {
        display: none;
    }

    .user-main {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
    }

    .user-avatar {
        width: 56px;
        height: 56px;
        border-radius: 18px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary-color), #cba55c);
        color: #fff;
        font-size: 1.2rem;
        box-shadow: 0 12px 24px rgba(170, 134, 63, 0.18);
    }

    .user-name {
        margin: 0;
        color: var(--text-primary);
        font-size: 1.16rem;
        font-weight: 800;
        line-height: 1.35;
    }

    .user-meta-line {
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        color: var(--text-secondary);
        font-size: 0.96rem;
    }

    .user-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        background: rgba(170, 134, 63, 0.10);
        color: var(--primary-color);
        font-size: 0.84rem;
        font-weight: 800;
    }

    .user-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-width: 0;
    }

    .user-field-label {
        color: var(--text-light);
        font-size: 0.86rem;
        font-weight: 700;
    }

    .user-field-value {
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.5;
        word-break: break-word;
    }

    .user-field-value.muted {
        color: var(--text-secondary);
        font-weight: 600;
    }

    .user-permission-stack {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .user-permission-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 10px;
        border-radius: 999px;
        background: rgba(15, 23, 42, 0.06);
        color: var(--text-secondary);
        font-size: 0.85rem;
        font-weight: 700;
    }

    .user-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .user-action-btn {
        min-width: 44px;
        height: 44px;
        padding: 0 14px;
        border: 0;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none !important;
        font-size: 0.95rem;
        font-weight: 800;
    }

    .user-action-btn.view { background: rgba(14, 165, 233, 0.12); color: #0284c7; }
    .user-action-btn.edit { background: rgba(217, 119, 6, 0.12); color: #b45309; }
    .user-action-btn.delete { background: rgba(220, 38, 38, 0.10); color: var(--danger-color); }

    .users-empty {
        display: none;
        padding: 48px 24px 54px;
        text-align: center;
    }

    .users-empty.show {
        display: block;
    }

    .users-empty-icon {
        width: 74px;
        height: 74px;
        margin: 0 auto 16px;
        border-radius: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(170, 134, 63, 0.12);
        color: var(--primary-color);
        font-size: 1.8rem;
    }

    .users-pagination {
        padding: 22px;
        border-top: 1px solid rgba(170, 134, 63, 0.10);
    }

    html[data-theme="dark"] .users-hero {
        background:
            radial-gradient(circle at top left, rgba(141, 110, 43, 0.28), transparent 34%),
            linear-gradient(135deg, rgba(17, 24, 39, 0.98) 0%, rgba(15, 23, 42, 0.98) 48%, rgba(30, 41, 59, 0.98) 100%);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 24px 52px rgba(2, 6, 23, 0.34);
    }

    html[data-theme="dark"] .users-secondary-btn,
    html[data-theme="dark"] .users-stat-card,
    html[data-theme="dark"] .users-toolbar,
    html[data-theme="dark"] .users-list-card,
    html[data-theme="dark"] .user-row,
    html[data-theme="dark"] .users-chip,
    html[data-theme="dark"] .users-empty,
    html[data-theme="dark"] .user-permission-pill {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.14);
        color: var(--text-primary);
        box-shadow: 0 18px 38px rgba(2, 6, 23, 0.28);
    }

    html[data-theme="dark"] .users-list-head,
    html[data-theme="dark"] .users-pagination {
        background: rgba(15, 23, 42, 0.78);
        border-color: rgba(148, 163, 184, 0.12);
    }

    html[data-theme="dark"] .users-search input {
        background: rgba(15, 23, 42, 0.95);
        border-color: rgba(148, 163, 184, 0.20);
        color: var(--text-primary);
    }

    html[data-theme="dark"] .users-eyebrow,
    html[data-theme="dark"] .user-badge {
        background: rgba(141, 110, 43, 0.16);
        color: #f6deb0;
    }

    @media (max-width: 1199px) {
        .users-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .users-list-head {
            display: none;
        }

        .user-row {
            grid-template-columns: 1fr 1fr;
        }

        .user-main,
        .user-actions {
            grid-column: 1 / -1;
        }

        .user-actions {
            justify-content: flex-start;
        }
    }

    @media (max-width: 767px) {
        .users-stats {
            grid-template-columns: 1fr;
        }

        .user-row {
            grid-template-columns: 1fr;
            padding: 16px;
            margin: 8px 0 0;
        }
    }
</style>
@endpush

@section('content')
<div class="users-page">
    <div class="users-shell">
        <section class="users-hero">
            <div class="users-hero-inner">
                <div>
                    <span class="users-eyebrow">
                        <i class="bi bi-stars"></i>
                        {{ __('لوحة إدارة المستخدمين') }}
                    </span>
                    <h1 class="users-title">{{ __('المستخدمون') }}</h1>
                </div>

                <div class="users-actions">
                    <a href="{{ route('users.create') }}" class="users-primary-btn">
                        <i class="bi bi-person-plus-fill"></i>
                        {{ __('إضافة مستخدم جديد') }}
                    </a>
                    <a href="{{ route('dashboard') }}" class="users-secondary-btn">
                        <i class="bi bi-grid-1x2-fill"></i>
                        {{ __('العودة إلى لوحة التحكم') }}
                    </a>
                </div>
            </div>
        </section>

        <section class="users-stats">
            <article class="users-stat-card">
                <span class="users-stat-icon"><i class="bi bi-people-fill"></i></span>
                <p class="users-stat-value">{{ $totalUsers }}</p>
                <p class="users-stat-label">{{ __('إجمالي المستخدمين') }}</p>
            </article>
            <article class="users-stat-card">
                <span class="users-stat-icon"><i class="bi bi-person-check-fill"></i></span>
                <p class="users-stat-value">{{ $linkedContributorsCount }}</p>
                <p class="users-stat-label">{{ __('مستخدمون مرتبطون بمساهمين') }}</p>
            </article>
            <article class="users-stat-card">
                <span class="users-stat-icon"><i class="bi bi-building-fill-gear"></i></span>
                <p class="users-stat-value">{{ $withDepartmentsCount }}</p>
                <p class="users-stat-label">{{ __('مستخدمون مرتبطون بإدارات') }}</p>
            </article>
            <article class="users-stat-card">
                <span class="users-stat-icon"><i class="bi bi-key-fill"></i></span>
                <p class="users-stat-value">{{ $withDirectPermissionsCount }}</p>
                <p class="users-stat-label">{{ __('مستخدمون لديهم صلاحيات مباشرة') }}</p>
            </article>
        </section>

        <section class="users-toolbar">
            <div class="users-search">
                <i class="bi bi-search"></i>
                <input type="search" id="usersSearch" placeholder="{{ __('ابحث بالاسم أو البريد أو الإدارة أو الصلاحية...') }}" autocomplete="off">
            </div>

            <div class="users-toolbar-meta">
                <span class="users-chip">
                    <i class="bi bi-eye-fill"></i>
                    <span id="visibleUsersCount">{{ $pageUsersCount }}</span>
                    {{ __('نتيجة ظاهرة') }}
                </span>
                <span class="users-chip">
                    <i class="bi bi-collection-fill"></i>
                    {{ __('صفحة') }} {{ $users->currentPage() }} / {{ $users->lastPage() }}
                </span>
            </div>
        </section>

        <section class="users-list-card">
            @if($pageUsersCount > 0)
                <div class="users-list-head">
                    <div>{{ __('المستخدم') }}</div>
                    <div>{{ __('الإدارة والشركة') }}</div>
                    <div>{{ __('الحساب والارتباط') }}</div>
                    <div>{{ __('الصلاحيات') }}</div>
                    <div>{{ __('الإجراءات') }}</div>
                </div>

                <div class="users-list-body">
                    @foreach($users as $user)
                        @php
                            $searchableText = implode(' ', [
                                $user->name,
                                $user->email,
                                $user->phone,
                                $user->department_names,
                                $user->company_names,
                                optional($user->contributor)->name,
                                $user->direct_permission_names,
                                $user->effective_permission_names,
                            ]);
                        @endphp
                        <article class="user-row user-search-item" data-search="{{ mb_strtolower($searchableText) }}">
                            <div class="user-main">
                                <div class="user-avatar">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div style="min-width: 0;">
                                    <h3 class="user-name">{{ $user->name }}</h3>
                                    <div class="user-meta-line">
                                        <span class="user-badge"><i class="bi bi-hash"></i>#{{ $user->id }}</span>
                                        <span class="user-badge"><i class="bi bi-envelope"></i>{{ $user->email }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="user-field">
                                <span class="user-field-label">{{ __('الإدارة والشركة') }}</span>
                                <span class="user-field-value">{{ $user->department_names ?: __('بدون إدارة') }}</span>
                                <span class="user-field-value muted">{{ $user->company_names ?: __('بدون شركة') }}</span>
                            </div>

                            <div class="user-field">
                                <span class="user-field-label">{{ __('الحساب والارتباط') }}</span>
                                <span class="user-field-value">{{ $user->phone ?: __('بدون هاتف') }}</span>
                                <span class="user-field-value muted">{{ optional($user->contributor)->name ?: __('غير مرتبط بمساهم') }}</span>
                            </div>

                            <div class="user-field">
                                <span class="user-field-label">{{ __('الصلاحيات') }}</span>
                                <div class="user-permission-stack">
                                    <span class="user-permission-pill"><i class="bi bi-key"></i>{{ __('مباشرة') }}: {{ $user->permissions->count() }}</span>
                                    <span class="user-permission-pill"><i class="bi bi-arrow-repeat"></i>{{ __('موروثة') }}: {{ $user->inherited_permissions->count() }}</span>
                                    <span class="user-permission-pill"><i class="bi bi-shield-check"></i>{{ __('فعّالة') }}: {{ $user->effective_permissions->count() }}</span>
                                </div>
                            </div>

                            <div class="user-actions">
                                <a href="{{ route('users.show', $user) }}" class="user-action-btn view" title="{{ __('عرض') }}">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="{{ route('users.edit', $user) }}" class="user-action-btn edit" title="{{ __('تعديل') }}">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline-flex;" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا المستخدم؟') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="user-action-btn delete" title="{{ __('حذف') }}">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="users-empty" id="usersEmptyState">
                    <div class="users-empty-icon">
                        <i class="bi bi-search-heart"></i>
                    </div>
                    <h3>{{ __('لا توجد نتائج مطابقة') }}</h3>
                    <p>{{ __('جرّب البحث باسم مختلف أو بالبريد أو الإدارة أو اسم الصلاحية للوصول إلى المستخدم المطلوب.') }}</p>
                </div>

                <div class="users-pagination">
                    {{ $users->links() }}
                </div>
            @else
                <div class="users-empty show">
                    <div class="users-empty-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <h3>{{ __('لا يوجد مستخدمون بعد') }}</h3>
                    <p>{{ __('ابدأ بإضافة أول مستخدم ليظهر هنا داخل قائمة حديثة تدعم البحث وعرض الصلاحيات.') }}</p>
                </div>
            @endif
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('usersSearch');
        const rows = Array.from(document.querySelectorAll('.user-search-item'));
        const visibleCount = document.getElementById('visibleUsersCount');
        const emptyState = document.getElementById('usersEmptyState');
        const pagination = document.querySelector('.users-pagination');

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
        filterRows();
    });
</script>
@endpush
