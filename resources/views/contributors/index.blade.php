@extends('layouts.app')

@section('title', __('المساهمين'))

@php
    $contributorsCollection = $contributors->getCollection();
    $totalContributors = $contributors->total();
    $pageContributorsCount = $contributorsCollection->count();
    $pageSharesTotal = $contributorsCollection->sum(fn ($contributor) => (float) ($contributor->share_count_cr ?? 0));
    $boardMembersCount = $contributorsCollection->where('is_board_member', true)->count();
    $averageShares = $pageContributorsCount > 0 ? $pageSharesTotal / $pageContributorsCount : 0;
@endphp

@push('styles')
<style>
    .contributors-page {
        padding: 8px 0 28px;
    }

    .contributors-shell {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .contributors-hero {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        padding: 30px;
        background:
            radial-gradient(circle at top left, rgba(196, 168, 90, 0.32), transparent 34%),
            linear-gradient(135deg, #fffaf0 0%, #ffffff 48%, #f5efe2 100%);
        border: 1px solid rgba(170, 134, 63, 0.16);
        box-shadow: 0 18px 48px rgba(15, 23, 42, 0.08);
        animation: contributorFadeUp 0.7s ease both;
    }

    .contributors-hero::before,
    .contributors-hero::after {
        content: "";
        position: absolute;
        border-radius: 999px;
        pointer-events: none;
    }

    .contributors-hero::before {
        width: 210px;
        height: 210px;
        top: -100px;
        inset-inline-end: -80px;
        background: rgba(170, 134, 63, 0.10);
    }

    .contributors-hero::after {
        width: 150px;
        height: 150px;
        bottom: -80px;
        inset-inline-start: -40px;
        background: rgba(196, 168, 90, 0.14);
    }

    .contributors-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
    }

    .contributors-eyebrow {
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

    .contributors-title {
        margin: 0;
        font-size: 2.6rem;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .contributors-subtitle {
        margin: 10px 0 0;
        max-width: 760px;
        color: var(--text-secondary);
        font-size: 1.22rem;
        line-height: 1.8;
    }

    .contributors-hero-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .contributors-primary-btn,
    .contributors-secondary-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 20px;
        border-radius: 18px;
        text-decoration: none !important;
        font-size: 1.12rem;
        font-weight: 700;
        transition: transform 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease, color 0.25s ease;
    }

    .contributors-primary-btn {
        background: linear-gradient(135deg, var(--primary-color), #c49b48);
        color: #fff;
        box-shadow: 0 18px 28px rgba(170, 134, 63, 0.24);
    }

    .contributors-secondary-btn {
        background: rgba(255, 255, 255, 0.88);
        color: var(--text-primary);
        border: 1px solid rgba(170, 134, 63, 0.16);
    }

    .contributors-primary-btn:hover,
    .contributors-secondary-btn:hover {
        transform: translateY(-2px);
    }

    .contributors-primary-btn:hover {
        color: #fff;
        box-shadow: 0 22px 34px rgba(170, 134, 63, 0.28);
    }

    .contributors-secondary-btn:hover {
        color: var(--primary-color);
        background: #fff;
    }

    .contributors-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }

    .contributors-stat-card {
        position: relative;
        overflow: hidden;
        padding: 22px 20px;
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        animation: contributorFadeUp 0.72s ease both;
    }

    .contributors-stat-card::after {
        content: "";
        position: absolute;
        width: 92px;
        height: 92px;
        border-radius: 50%;
        top: -36px;
        inset-inline-end: -24px;
        background: rgba(170, 134, 63, 0.08);
    }

    .contributors-stat-icon {
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

    .contributors-stat-value {
        margin: 0;
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-primary);
    }

    .contributors-stat-label {
        margin: 6px 0 0;
        color: var(--text-secondary);
        font-size: 1.05rem;
        font-weight: 600;
    }

    .contributors-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        padding: 18px 20px;
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.94);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 12px 32px rgba(15, 23, 42, 0.05);
        animation: contributorFadeUp 0.82s ease both;
    }

    .contributors-search {
        position: relative;
        flex: 1 1 340px;
    }

    .contributors-search i {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        inset-inline-start: 16px;
        color: var(--text-light);
        font-size: 1.1rem;
    }

    .contributors-search input {
        width: 100%;
        height: 54px;
        padding-inline-start: 46px;
        padding-inline-end: 18px;
        border-radius: 18px;
        border: 1px solid rgba(170, 134, 63, 0.16);
        background: #fff;
        color: var(--text-primary);
        font-size: 1.08rem;
        transition: border-color 0.25s ease, box-shadow 0.25s ease;
    }

    .contributors-search input:focus {
        outline: none;
        border-color: rgba(170, 134, 63, 0.48);
        box-shadow: 0 0 0 4px rgba(170, 134, 63, 0.10);
    }

    .contributors-toolbar-meta {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .contributors-meta-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 44px;
        padding: 10px 14px;
        border-radius: 14px;
        background: #f8f5ed;
        border: 1px solid rgba(170, 134, 63, 0.14);
        color: var(--text-secondary);
        font-size: 0.98rem;
        font-weight: 700;
    }

    .contributors-list-card {
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 16px 44px rgba(15, 23, 42, 0.07);
        overflow: hidden;
        animation: contributorFadeUp 0.92s ease both;
    }

    .contributors-list-head,
    .contributor-row {
        display: grid;
        grid-template-columns: minmax(280px, 2.2fr) minmax(150px, 1.1fr) minmax(170px, 1.2fr) minmax(140px, 0.9fr) minmax(220px, 1.4fr);
        align-items: center;
        gap: 16px;
        padding: 18px 22px;
    }

    .contributors-list-head {
        background: linear-gradient(180deg, #fff9ef 0%, #fcf7ee 100%);
        color: var(--text-secondary);
        font-size: 0.98rem;
        font-weight: 800;
        border-bottom: 1px solid rgba(170, 134, 63, 0.12);
    }

    .contributors-list-body {
        padding: 8px 10px 12px;
    }

    .contributor-row {
        margin: 10px 10px 0;
        border-radius: 22px;
        background: #fff;
        border: 1px solid rgba(226, 232, 240, 0.9);
        transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
        animation: contributorFadeUp 0.7s ease both;
    }

    .contributor-row:hover {
        transform: translateY(-3px);
        border-color: rgba(170, 134, 63, 0.22);
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.08);
    }

    .contributor-row.is-hidden {
        display: none;
    }

    .contributor-main {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
    }

    .contributor-avatar {
        width: 56px;
        height: 56px;
        border-radius: 18px;
        flex-shrink: 0;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary-color), #cba55c);
        color: #fff;
        font-size: 1.2rem;
        font-weight: 800;
        box-shadow: 0 12px 24px rgba(170, 134, 63, 0.18);
    }

    .contributor-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .contributor-name {
        margin: 0;
        color: var(--text-primary);
        font-size: 1.16rem;
        font-weight: 800;
        line-height: 1.35;
    }

    .contributor-meta-line {
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        color: var(--text-secondary);
        font-size: 0.96rem;
    }

    .contributor-badge {
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

    .contributor-badge.board {
        background: rgba(5, 150, 105, 0.10);
        color: var(--success-color);
    }

    .contributor-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-width: 0;
    }

    .contributor-field-label {
        color: var(--text-light);
        font-size: 0.86rem;
        font-weight: 700;
    }

    .contributor-field-value {
        color: var(--text-primary);
        font-size: 1.04rem;
        font-weight: 700;
        line-height: 1.5;
        word-break: break-word;
    }

    .contributor-field-value.muted {
        color: var(--text-secondary);
        font-weight: 600;
    }

    .contributor-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .contributor-action-btn {
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
        transition: transform 0.25s ease, box-shadow 0.25s ease, opacity 0.25s ease;
    }

    .contributor-action-btn:hover {
        transform: translateY(-2px);
        opacity: 0.96;
    }

    .contributor-action-btn.view {
        background: rgba(14, 165, 233, 0.12);
        color: #0284c7;
    }

    .contributor-action-btn.edit {
        background: rgba(217, 119, 6, 0.12);
        color: #b45309;
    }

    .contributor-action-btn.delete {
        background: rgba(220, 38, 38, 0.10);
        color: var(--danger-color);
    }

    .contributors-empty {
        display: none;
        padding: 48px 24px 54px;
        text-align: center;
    }

    .contributors-empty.show {
        display: block;
        animation: contributorFadeUp 0.4s ease both;
    }

    .contributors-empty-icon {
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

    .contributors-empty h3 {
        margin: 0 0 8px;
        color: var(--text-primary);
        font-size: 1.7rem;
        font-weight: 800;
    }

    .contributors-empty p {
        margin: 0;
        color: var(--text-secondary);
        font-size: 1.06rem;
    }

    .contributors-pagination {
        padding: 22px;
        border-top: 1px solid rgba(170, 134, 63, 0.10);
        background: linear-gradient(180deg, rgba(255, 249, 239, 0.35) 0%, rgba(255, 255, 255, 0.92) 100%);
    }

    .contributors-pagination .pagination {
        margin: 0;
    }

    .contributors-pagination .pagination > li > a,
    .contributors-pagination .pagination > li > span {
        border-radius: 12px !important;
        margin: 0 4px;
        border: 1px solid rgba(170, 134, 63, 0.14);
        color: var(--text-primary);
        min-width: 42px;
        text-align: center;
    }

    .contributors-pagination .pagination > .active > span,
    .contributors-pagination .pagination > .active > span:hover,
    .contributors-pagination .pagination > .active > span:focus,
    .contributors-pagination .pagination > .active > a,
    .contributors-pagination .pagination > .active > a:hover,
    .contributors-pagination .pagination > .active > a:focus {
        background: linear-gradient(135deg, var(--primary-color), #c49b48);
        border-color: transparent;
        color: #fff;
    }

    @keyframes contributorFadeUp {
        from {
            opacity: 0;
            transform: translateY(18px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 1199px) {
        .contributors-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .contributors-list-head {
            display: none;
        }

        .contributor-row {
            grid-template-columns: 1fr 1fr;
            align-items: flex-start;
        }

        .contributor-main,
        .contributor-actions {
            grid-column: 1 / -1;
        }

        .contributor-actions {
            justify-content: flex-start;
        }
    }

    @media (max-width: 767px) {
        .contributors-page {
            padding: 2px 0 18px;
        }

        .contributors-hero {
            padding: 22px 18px;
            border-radius: 24px;
        }

        .contributors-title {
            font-size: 2rem;
        }

        .contributors-subtitle {
            font-size: 1.02rem;
        }

        .contributors-primary-btn,
        .contributors-secondary-btn {
            width: 100%;
            justify-content: center;
        }

        .contributors-stats {
            grid-template-columns: 1fr;
        }

        .contributors-toolbar {
            padding: 16px;
        }

        .contributors-toolbar-meta {
            width: 100%;
        }

        .contributors-meta-chip {
            flex: 1 1 100%;
            justify-content: center;
        }

        .contributors-list-body {
            padding: 8px;
        }

        .contributor-row {
            grid-template-columns: 1fr;
            padding: 16px;
            margin: 8px 0 0;
        }

        .contributor-main {
            align-items: flex-start;
        }

        .contributor-avatar {
            width: 50px;
            height: 50px;
            border-radius: 16px;
        }

        .contributor-action-btn {
            flex: 1 1 calc(50% - 8px);
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="contributors-page">
    <div class="contributors-shell">
        <section class="contributors-hero">
            <div class="contributors-hero-inner">
                <div>
                    <span class="contributors-eyebrow">
                        <i class="bi bi-stars"></i>
                        {{ __('لوحة إدارة المساهمين') }}
                    </span>
                    <h1 class="contributors-title">{{ __('المساهمين') }}</h1>
                </div>

                <div class="contributors-hero-actions">
                    <a href="{{ route('contributors.create') }}" class="contributors-primary-btn">
                        <i class="bi bi-person-plus-fill"></i>
                        {{ __('إضافة مساهم جديد') }}
                    </a>
                    <a href="{{ route('dashboard') }}" class="contributors-secondary-btn">
                        <i class="bi bi-grid-1x2-fill"></i>
                        {{ __('العودة إلى لوحة التحكم') }}
                    </a>
                </div>
            </div>
        </section>

        <section class="contributors-stats">
            <article class="contributors-stat-card" style="animation-delay: 0.05s;">
                <div class="contributors-stat-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <p class="contributors-stat-value">{{ number_format($totalContributors) }}</p>
                <p class="contributors-stat-label">{{ __('إجمالي المساهمين') }}</p>
            </article>

            <article class="contributors-stat-card" style="animation-delay: 0.12s;">
                <div class="contributors-stat-icon">
                    <i class="bi bi-bar-chart-line-fill"></i>
                </div>
                <p class="contributors-stat-value">{{ number_format($pageSharesTotal, 0) }}</p>
                <p class="contributors-stat-label">{{ __('إجمالي أسهم الصفحة الحالية') }}</p>
            </article>

            <article class="contributors-stat-card" style="animation-delay: 0.19s;">
                <div class="contributors-stat-icon">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
                <p class="contributors-stat-value">{{ number_format($boardMembersCount) }}</p>
                <p class="contributors-stat-label">{{ __('أعضاء مجلس الإدارة في الصفحة') }}</p>
            </article>

            <article class="contributors-stat-card" style="animation-delay: 0.26s;">
                <div class="contributors-stat-icon">
                    <i class="bi bi-pie-chart-fill"></i>
                </div>
                <p class="contributors-stat-value">{{ number_format($averageShares, 0) }}</p>
                <p class="contributors-stat-label">{{ __('متوسط الأسهم في الصفحة') }}</p>
            </article>
        </section>

        <section class="contributors-toolbar">
            <div class="contributors-search">
                <i class="bi bi-search"></i>
                <input
                    type="search"
                    id="contributorsSearch"
                    placeholder="{{ __('ابحث بالاسم أو رقم الهوية أو المنصب...') }}"
                    autocomplete="off"
                >
            </div>

            <div class="contributors-toolbar-meta">
                <span class="contributors-meta-chip">
                    <i class="bi bi-eye-fill"></i>
                    <span id="visibleContributorsCount">{{ $pageContributorsCount }}</span>
                    {{ __('نتيجة ظاهرة') }}
                </span>
                <span class="contributors-meta-chip">
                    <i class="bi bi-collection-fill"></i>
                    {{ __('صفحة') }} {{ $contributors->currentPage() }} / {{ $contributors->lastPage() }}
                </span>
            </div>
        </section>

        <section class="contributors-list-card">
            @if($pageContributorsCount > 0)
                <div class="contributors-list-head">
                    <div>{{ __('المساهم') }}</div>
                    <div>{{ __('رقم الهوية') }}</div>
                    <div>{{ __('المنصب') }}</div>
                    <div>{{ __('عدد الأسهم') }}</div>
                    <div>{{ __('الإجراءات') }}</div>
                </div>

                <div class="contributors-list-body" id="contributorsList">
                    @foreach($contributors as $contributor)
                        @php
                            $searchableText = implode(' ', [
                                $contributor->name,
                                $contributor->id_number,
                                $contributor->position,
                                $contributor->phone_num,
                            ]);
                        @endphp

                        <article
                            class="contributor-row contributor-search-item"
                            data-search="{{ mb_strtolower($searchableText) }}"
                            style="animation-delay: {{ 0.05 + ($loop->index * 0.04) }}s;"
                        >
                            <div class="contributor-main">
                                <div class="contributor-avatar">
                                    @if($contributor->profile_picture)
                                        <img src="{{ $contributor->profile_picture_url }}" alt="{{ $contributor->name }}">
                                    @else
                                        {{ $contributor->initials }}
                                    @endif
                                </div>

                                <div style="min-width: 0;">
                                    <h3 class="contributor-name">{{ $contributor->name }}</h3>
                                    <div class="contributor-meta-line">
                                        <span class="contributor-badge">
                                            <i class="bi bi-hash"></i>
                                            #{{ $contributor->id }}
                                        </span>
                                        @if($contributor->is_board_member)
                                            <span class="contributor-badge board">
                                                <i class="bi bi-patch-check-fill"></i>
                                                {{ __('عضو مجلس إدارة') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="contributor-field">
                                <span class="contributor-field-label">{{ __('رقم الهوية') }}</span>
                                <span class="contributor-field-value">{{ $contributor->id_number ?: __('غير متوفر') }}</span>
                            </div>

                            <div class="contributor-field">
                                <span class="contributor-field-label">{{ __('المنصب') }}</span>
                                <span class="contributor-field-value {{ $contributor->position ? '' : 'muted' }}">
                                    {{ $contributor->position ?: __('غير محدد') }}
                                </span>
                            </div>

                            <div class="contributor-field">
                                <span class="contributor-field-label">{{ __('عدد الأسهم') }}</span>
                                <span class="contributor-field-value">{{ number_format($contributor->share_count_cr ?? 0) }}</span>
                            </div>

                            <div class="contributor-actions">
                                <a href="{{ route('contributors.show', $contributor) }}" class="contributor-action-btn view" title="{{ __('تفاصيل') }}">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="{{ route('contributors.edit', $contributor) }}" class="contributor-action-btn edit" title="{{ __('تعديل') }}">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('contributors.destroy', $contributor) }}" method="POST" style="display: inline-flex;" onsubmit="return confirm('{{ __('هل أنت متأكد من الحذف؟') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="contributor-action-btn delete" title="{{ __('حذف') }}">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="contributors-empty" id="contributorsEmptyState">
                    <div class="contributors-empty-icon">
                        <i class="bi bi-search-heart"></i>
                    </div>
                    <h3>{{ __('لا توجد نتائج مطابقة') }}</h3>
                    <p>{{ __('جرّب كتابة اسم مختلف أو رقم هوية أو منصب للعثور على المساهم المطلوب.') }}</p>
                </div>

                <div class="contributors-pagination">
                    {{ $contributors->links() }}
                </div>
            @else
                <div class="contributors-empty show">
                    <div class="contributors-empty-icon">
                        <i class="bi bi-person-x-fill"></i>
                    </div>
                    <h3>{{ __('لا توجد بيانات مساهمين بعد') }}</h3>
                    <p>{{ __('ابدأ بإضافة أول مساهم لتظهر البيانات هنا بشكل منظم واحترافي.') }}</p>
                    <div style="margin-top: 18px;">
                        <a href="{{ route('contributors.create') }}" class="contributors-primary-btn">
                            <i class="bi bi-plus-circle-fill"></i>
                            {{ __('إضافة مساهم جديد') }}
                        </a>
                    </div>
                </div>
            @endif
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('contributorsSearch');
        const rows = Array.from(document.querySelectorAll('.contributor-search-item'));
        const visibleCount = document.getElementById('visibleContributorsCount');
        const emptyState = document.getElementById('contributorsEmptyState');
        const pagination = document.querySelector('.contributors-pagination');

        if (!searchInput || rows.length === 0) {
            return;
        }

        function normalize(value) {
            return (value || '')
                .toString()
                .toLowerCase()
                .trim();
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
