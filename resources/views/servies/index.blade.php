@extends('layouts.app')

@section('title', __('الخدمات'))

@php
    $servicesCollection = collect($sercies);
    $totalServices = $servicesCollection->count();
    $latestService = $servicesCollection->sortByDesc('created_at')->first();
    $updatedTodayCount = $servicesCollection->filter(fn ($service) => optional($service->updated_at)?->isToday())->count();
@endphp

@push('styles')
<style>
    .serv-page {
        padding: 8px 0 28px;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .serv-shell {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .serv-hero {
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

    .serv-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
    }

    .serv-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(170, 134, 63, 0.1);
        color: var(--primary-color);
        font-size: 1rem;
        font-weight: 800;
    }

    .serv-title {
        margin: 0;
        font-size: clamp(2rem, 3vw, 2.7rem);
        font-weight: 900;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .serv-subtitle {
        margin: 12px 0 0;
        max-width: 780px;
        color: var(--text-secondary);
        font-size: 1.06rem;
        line-height: 1.9;
    }

    .serv-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .serv-primary-btn,
    .serv-secondary-btn,
    .serv-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        border: 0;
        text-decoration: none !important;
        font-weight: 800;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .serv-primary-btn,
    .serv-secondary-btn {
        min-height: 52px;
        padding: 14px 20px;
        border-radius: 18px;
        font-size: 1rem;
    }

    .serv-primary-btn {
        background: linear-gradient(135deg, var(--primary-color), #c49b48);
        color: #fff !important;
        box-shadow: 0 18px 28px rgba(170, 134, 63, 0.24);
    }

    .serv-secondary-btn {
        background: rgba(255, 255, 255, 0.9);
        color: var(--text-primary) !important;
        border: 1px solid rgba(170, 134, 63, 0.16);
    }

    .serv-primary-btn:hover,
    .serv-secondary-btn:hover,
    .serv-action-btn:hover {
        transform: translateY(-2px);
    }

    .serv-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }

    .serv-stat-card,
    .serv-toolbar,
    .serv-list-card {
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
    }

    .serv-stat-card {
        padding: 22px 20px;
    }

    .serv-stat-icon {
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

    .serv-stat-value {
        margin: 0;
        font-size: 2rem;
        font-weight: 900;
        color: var(--text-primary);
    }

    .serv-stat-label {
        margin: 6px 0 0;
        color: var(--text-secondary);
        font-size: 1rem;
        font-weight: 700;
    }

    .serv-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        padding: 18px 20px;
    }

    .serv-search {
        position: relative;
        flex: 1 1 340px;
    }

    .serv-search i {
        position: absolute;
        top: 50%;
        inset-inline-start: 16px;
        transform: translateY(-50%);
        color: var(--text-light);
        font-size: 1rem;
    }

    .serv-search input {
        width: 100%;
        height: 54px;
        padding-inline-start: 46px;
        padding-inline-end: 18px;
        border-radius: 18px;
        border: 1px solid rgba(170, 134, 63, 0.16);
        background: #fff;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .serv-toolbar-meta {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .serv-chip {
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

    .serv-list-card {
        overflow: hidden;
    }

    .serv-list-body {
        padding: 14px;
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .serv-card {
        border-radius: 24px;
        background: #fff;
        border: 1px solid rgba(226, 232, 240, 0.92);
        padding: 22px;
        transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
    }

    .serv-card:hover {
        transform: translateY(-3px);
        border-color: rgba(170, 134, 63, 0.22);
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.08);
    }

    .serv-card.is-hidden {
        display: none;
    }

    .serv-card-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 18px;
    }

    .serv-card-main {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        min-width: 0;
    }

    .serv-avatar {
        width: 54px;
        height: 54px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary-color), #cba55c);
        color: #fff;
        font-size: 1.2rem;
        box-shadow: 0 12px 24px rgba(170, 134, 63, 0.18);
        flex-shrink: 0;
    }

    .serv-name {
        margin: 0;
        color: var(--text-primary);
        font-size: 1.15rem;
        font-weight: 900;
        line-height: 1.5;
    }

    .serv-subline {
        margin-top: 6px;
        color: var(--text-secondary);
        font-size: 0.94rem;
        line-height: 1.7;
    }

    .serv-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(170, 134, 63, 0.1);
        color: var(--primary-color);
        font-size: 0.85rem;
        font-weight: 800;
        white-space: nowrap;
    }

    .serv-detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 18px;
    }

    .serv-detail-box {
        border-radius: 18px;
        background: rgba(248, 250, 252, 0.92);
        border: 1px solid rgba(170, 134, 63, 0.1);
        padding: 14px;
    }

    .serv-detail-label {
        display: block;
        color: var(--text-light);
        font-size: 0.84rem;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .serv-detail-value {
        color: var(--text-primary);
        font-size: 0.98rem;
        font-weight: 800;
        line-height: 1.6;
    }

    .serv-card-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .serv-action-btn {
        min-height: 44px;
        padding: 0 14px;
        border-radius: 14px;
        font-size: 0.95rem;
    }

    .serv-action-btn.edit {
        background: rgba(217, 119, 6, 0.12);
        color: #b45309;
    }

    .serv-action-btn.delete {
        background: rgba(220, 38, 38, 0.1);
        color: var(--danger-color);
    }

    .serv-empty {
        display: none;
        padding: 48px 24px 54px;
        text-align: center;
    }

    .serv-empty.show {
        display: block;
    }

    .serv-empty-icon {
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

    html[data-theme="dark"] .serv-hero {
        background:
            radial-gradient(circle at top left, rgba(141, 110, 43, 0.28), transparent 34%),
            linear-gradient(135deg, rgba(17, 24, 39, 0.98) 0%, rgba(15, 23, 42, 0.98) 48%, rgba(30, 41, 59, 0.98) 100%);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 24px 52px rgba(2, 6, 23, 0.34);
    }

    html[data-theme="dark"] .serv-secondary-btn,
    html[data-theme="dark"] .serv-stat-card,
    html[data-theme="dark"] .serv-toolbar,
    html[data-theme="dark"] .serv-list-card,
    html[data-theme="dark"] .serv-card,
    html[data-theme="dark"] .serv-chip,
    html[data-theme="dark"] .serv-detail-box,
    html[data-theme="dark"] .serv-empty {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.14);
        color: var(--text-primary);
        box-shadow: 0 18px 38px rgba(2, 6, 23, 0.28);
    }

    html[data-theme="dark"] .serv-search input {
        background: rgba(15, 23, 42, 0.95);
        border-color: rgba(148, 163, 184, 0.2);
        color: var(--text-primary);
    }

    html[data-theme="dark"] .serv-badge,
    html[data-theme="dark"] .serv-tag {
        background: rgba(141, 110, 43, 0.16);
        color: #f6deb0;
    }

    @media (max-width: 1199px) {
        .serv-stats,
        .serv-list-body {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .serv-detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="serv-page">
    <div class="serv-shell">
        <section class="serv-hero">
            <div class="serv-hero-inner">
                <div>
                    <span class="serv-badge">
                        <i class="bi bi-stars"></i>
                        {{ __('إدارة الخدمات') }}
                    </span>
                    <h1 class="serv-title">{{ __('الخدمات') }}</h1>
                </div>

                <div class="serv-actions">
                    <a href="{{ route('servies.create') }}" class="serv-primary-btn">
                        <i class="bi bi-plus-circle-fill"></i>
                        {{ __('إضافة خدمة جديدة') }}
                    </a>
                    <a href="{{ route('dashboard') }}" class="serv-secondary-btn">
                        <i class="bi bi-grid-1x2-fill"></i>
                        {{ __('العودة إلى لوحة التحكم') }}
                    </a>
                </div>
            </div>
        </section>

        <section class="serv-stats">
            <article class="serv-stat-card">
                <span class="serv-stat-icon"><i class="bi bi-gear-wide-connected"></i></span>
                <p class="serv-stat-value">{{ $totalServices }}</p>
                <p class="serv-stat-label">{{ __('إجمالي الخدمات') }}</p>
            </article>
            <article class="serv-stat-card">
                <span class="serv-stat-icon"><i class="bi bi-plus-square-fill"></i></span>
                <p class="serv-stat-value">{{ $latestService?->created_at?->format('Y-m-d') ?? '--' }}</p>
                <p class="serv-stat-label">{{ __('آخر تاريخ إضافة') }}</p>
            </article>
            <article class="serv-stat-card">
                <span class="serv-stat-icon"><i class="bi bi-arrow-repeat"></i></span>
                <p class="serv-stat-value">{{ $updatedTodayCount }}</p>
                <p class="serv-stat-label">{{ __('عناصر تم تحديثها اليوم') }}</p>
            </article>
            <article class="serv-stat-card">
                <span class="serv-stat-icon"><i class="bi bi-type"></i></span>
                <p class="serv-stat-value">{{ $latestService?->name ?? '--' }}</p>
                <p class="serv-stat-label">{{ __('أحدث خدمة مضافة') }}</p>
            </article>
        </section>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <section class="serv-toolbar">
            <div class="serv-search">
                <i class="bi bi-search"></i>
                <input type="search" id="servicesSearch" placeholder="{{ __('ابحث باسم الخدمة أو بتاريخها...') }}" autocomplete="off">
            </div>

            <div class="serv-toolbar-meta">
                <span class="serv-chip">
                    <i class="bi bi-eye-fill"></i>
                    <span id="visibleServicesCount">{{ $totalServices }}</span>
                    {{ __('نتيجة ظاهرة') }}
                </span>
            </div>
        </section>

        <section class="serv-list-card">
            @if($servicesCollection->isNotEmpty())
                <div class="serv-list-body">
                    @foreach($servicesCollection as $servie)
                        @php
                            $searchableText = implode(' ', [
                                $servie->name,
                                $servie->created_at?->format('Y-m-d'),
                                $servie->updated_at?->format('Y-m-d'),
                                $servie->updated_at?->diffForHumans(),
                            ]);
                        @endphp
                        <article class="serv-card serv-search-item" data-search="{{ mb_strtolower($searchableText) }}">
                            <div class="serv-card-head">
                                <div class="serv-card-main">
                                    <div class="serv-avatar">
                                        <i class="bi bi-gear-fill"></i>
                                    </div>
                                    <div style="min-width: 0;">
                                        <h3 class="serv-name">{{ $servie->name }}</h3>
                                    </div>
                                </div>

                                <span class="serv-tag">
                                    <i class="bi bi-hash"></i>
                                    {{ $servie->id }}
                                </span>
                            </div>

                            <div class="serv-detail-grid">
                                <div class="serv-detail-box">
                                    <span class="serv-detail-label">{{ __('تاريخ الإنشاء') }}</span>
                                    <div class="serv-detail-value">{{ $servie->created_at?->format('Y-m-d') ?? '--' }}</div>
                                </div>
                                <div class="serv-detail-box">
                                    <span class="serv-detail-label">{{ __('آخر تحديث') }}</span>
                                    <div class="serv-detail-value">{{ $servie->updated_at?->diffForHumans() ?? '--' }}</div>
                                </div>
                            </div>

                            <div class="serv-card-actions">
                                <a href="{{ route('servies.edit', $servie->id) }}" class="serv-action-btn edit">
                                    <i class="bi bi-pencil-square"></i>
                                    {{ __('تعديل') }}
                                </a>
                                <form action="{{ route('servies.destroy', $servie->id) }}" method="POST" style="display: inline-flex;" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذه الخدمة؟') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="serv-action-btn delete">
                                        <i class="bi bi-trash3-fill"></i>
                                        {{ __('حذف') }}
                                    </button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="serv-empty" id="servicesEmptyState">
                    <div class="serv-empty-icon">
                        <i class="bi bi-search-heart"></i>
                    </div>
                    <h3>{{ __('لا توجد نتائج مطابقة') }}</h3>
                    <p>{{ __('جرّب البحث باسم مختلف أو راجع التهجئة للوصول إلى الخدمة المطلوبة.') }}</p>
                </div>
            @else
                <div class="serv-empty show">
                    <div class="serv-empty-icon">
                        <i class="bi bi-gear"></i>
                    </div>
                    <h3>{{ __('لا توجد خدمات بعد') }}</h3>
                    <p>{{ __('ابدأ بإضافة أول خدمة لتظهر هنا داخل قائمة حديثة تدعم البحث والإدارة السريعة.') }}</p>
                </div>
            @endif
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('servicesSearch');
        const cards = Array.from(document.querySelectorAll('.serv-search-item'));
        const visibleCount = document.getElementById('visibleServicesCount');
        const emptyState = document.getElementById('servicesEmptyState');

        if (!searchInput || cards.length === 0) {
            return;
        }

        function normalize(value) {
            return (value || '').toString().toLowerCase().trim();
        }

        function filterCards() {
            const query = normalize(searchInput.value);
            let shown = 0;

            cards.forEach(function (card) {
                const haystack = normalize(card.getAttribute('data-search'));
                const match = query === '' || haystack.indexOf(query) !== -1;
                card.classList.toggle('is-hidden', !match);

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
        }

        searchInput.addEventListener('input', filterCards);
    });
</script>
@endpush
