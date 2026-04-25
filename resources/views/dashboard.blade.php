@extends('layouts.app')

@section('title', __('لوحة التحكم'))

@php
    $currentUser = auth()->user();
    $quickLinks = [
        [
            'title' => __('المساهمين'),
            'description' => __('إدارة بيانات المساهمين والملفات والحسابات المرتبطة بهم.'),
            'route' => route('contributors.index'),
            'button' => __('عرض المساهمين'),
            'icon' => 'bi-people-fill',
            'accent' => 'gold',
            'permission_routes' => ['contributors.index'],
        ],
        [
            'title' => __('عروض البيع'),
            'description' => __('متابعة العروض الحالية وتنظيم حركة بيع الأسهم بسهولة.'),
            'route' => route('sell-shares.index'),
            'button' => __('عرض العروض'),
            'icon' => 'bi-bag-check-fill',
            'accent' => 'emerald',
            'permission_routes' => ['sell-shares.index'],
        ],
        [
            'title' => __('معاملات الأسهم'),
            'description' => __('الوصول السريع إلى معاملات التحويل والشراء والسجل التشغيلي.'),
            'route' => route('shares-trans.index'),
            'button' => __('عرض المعاملات'),
            'icon' => 'bi-arrow-left-right',
            'accent' => 'sky',
            'permission_routes' => ['shares-trans.index'],
        ],
    ];

    $quickLinks = collect($quickLinks)
        ->filter(fn ($link) => \App\Support\RoutePermissionMap::userCanAccess($currentUser, $link['permission_routes'] ?? []))
        ->values()
        ->all();

    $stats = [
        [
            'label' => __('إجمالي المساهمين'),
            'value' => number_format($contributorsCount ?? 0),
            'icon' => 'bi-people-fill',
            'accent' => 'gold',
        ],
        [
            'label' => __('عروض البيع النشطة'),
            'value' => number_format($sellSharesCount ?? 0),
            'icon' => 'bi-shop-window',
            'accent' => 'emerald',
        ],
        [
            'label' => __('إجمالي المعاملات'),
            'value' => number_format($transactionsCount ?? 0),
            'icon' => 'bi-bar-chart-steps',
            'accent' => 'sky',
        ],
        [
            'label' => __('إجمالي الأسهم'),
            'value' => number_format($totalShares ?? 0),
            'icon' => 'bi-pie-chart-fill',
            'accent' => 'amber',
        ],
    ];
@endphp

@push('styles')
<style>
    .dashboard-page {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .dashboard-hero {
        position: relative;
        overflow: hidden;
        border-radius: 30px;
        padding: clamp(22px, 3vw, 34px);
        background:
            radial-gradient(circle at top right, rgba(196, 168, 90, 0.28), transparent 30%),
            linear-gradient(135deg, #fff8ec 0%, #ffffff 46%, #f4ecdd 100%);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 20px 48px rgba(15, 23, 42, 0.08);
        animation: dashboardFadeUp 0.7s ease both;
    }

    .dashboard-hero::before,
    .dashboard-hero::after {
        content: "";
        position: absolute;
        border-radius: 999px;
        pointer-events: none;
    }

    .dashboard-hero::before {
        width: 220px;
        height: 220px;
        top: -120px;
        inset-inline-end: -60px;
        background: rgba(170, 134, 63, 0.10);
    }

    .dashboard-hero::after {
        width: 170px;
        height: 170px;
        bottom: -90px;
        inset-inline-start: -60px;
        background: rgba(196, 168, 90, 0.12);
    }

    .dashboard-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        flex-wrap: wrap;
    }

    .dashboard-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(170, 134, 63, 0.12);
        color: var(--primary-color);
        font-size: clamp(0.92rem, 1vw, 1rem);
        font-weight: 800;
        margin-bottom: 14px;
    }

    .dashboard-title {
        margin: 0;
        color: var(--text-primary);
        font-size: clamp(2rem, 4vw, 3.4rem);
        font-weight: 900;
        line-height: 1.15;
    }

    .dashboard-subtitle {
        margin: 12px 0 0;
        max-width: 760px;
        color: var(--text-secondary);
        font-size: clamp(1rem, 1.5vw, 1.28rem);
        line-height: 1.85;
    }

    .dashboard-hero-stats {
        display: grid;
        grid-template-columns: repeat(2, minmax(130px, 1fr));
        gap: 12px;
        min-width: min(320px, 100%);
    }

    .dashboard-mini-stat {
        padding: 16px 18px;
        border-radius: 22px;
        background: rgba(255, 255, 255, 0.88);
        border: 1px solid rgba(170, 134, 63, 0.12);
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        animation: dashboardFloat 4.2s ease-in-out infinite;
    }

    .dashboard-mini-stat:nth-child(2) {
        animation-delay: 0.7s;
    }

    .dashboard-mini-stat:nth-child(3) {
        animation-delay: 1.4s;
    }

    .dashboard-mini-stat:nth-child(4) {
        animation-delay: 2.1s;
    }

    .dashboard-mini-label {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text-secondary);
        font-size: 0.92rem;
        font-weight: 700;
    }

    .dashboard-mini-value {
        margin-top: 10px;
        color: var(--text-primary);
        font-size: clamp(1.5rem, 2vw, 2rem);
        font-weight: 900;
        line-height: 1;
    }

    .dashboard-quick-links {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .dashboard-link-card {
        position: relative;
        overflow: hidden;
        border-radius: 26px;
        padding: 22px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.12);
        box-shadow: 0 16px 38px rgba(15, 23, 42, 0.07);
        transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
        animation: dashboardFadeUp 0.75s ease both;
    }

    .dashboard-link-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 24px 44px rgba(15, 23, 42, 0.10);
        border-color: rgba(170, 134, 63, 0.2);
    }

    .dashboard-link-card::after {
        content: "";
        position: absolute;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        top: -48px;
        inset-inline-end: -20px;
        opacity: 0.18;
        background: currentColor;
        pointer-events: none;
    }

    .dashboard-link-card.gold { color: #aa863f; }
    .dashboard-link-card.emerald { color: #059669; }
    .dashboard-link-card.sky { color: #0284c7; }

    .dashboard-link-icon {
        width: 60px;
        height: 60px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 20px;
        background: color-mix(in srgb, currentColor 14%, white);
        font-size: 1.6rem;
        margin-bottom: 18px;
    }

    .dashboard-link-title {
        margin: 0;
        color: var(--text-primary);
        font-size: clamp(1.18rem, 1.6vw, 1.42rem);
        font-weight: 800;
    }

    .dashboard-link-description {
        margin: 10px 0 18px;
        color: var(--text-secondary);
        font-size: clamp(0.95rem, 1.2vw, 1.02rem);
        line-height: 1.8;
        min-height: 54px;
    }

    .dashboard-link-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 46px;
        padding: 10px 16px;
        border-radius: 14px;
        text-decoration: none !important;
        font-size: 1rem;
        font-weight: 800;
        color: #fff !important;
        background: linear-gradient(135deg, rgb(121 119 119), color-mix(in srgb, currentColor 78%, #111));
        box-shadow: 0 14px 24px color-mix(in srgb, currentColor 28%, transparent);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .dashboard-link-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 28px color-mix(in srgb, currentColor 34%, transparent);
    }

    .dashboard-section {
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.12);
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.07);
        overflow: hidden;
        animation: dashboardFadeUp 0.9s ease both;
    }

    .dashboard-section-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 24px 24px 0;
        flex-wrap: wrap;
    }

    .dashboard-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
        color: var(--text-primary);
        font-size: clamp(1.3rem, 2vw, 1.7rem);
        font-weight: 900;
    }

    .dashboard-section-text {
        margin: 8px 24px 0;
        color: var(--text-secondary);
        font-size: clamp(0.96rem, 1.2vw, 1.04rem);
        line-height: 1.8;
    }

    .dashboard-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        padding: 24px;
    }

    .dashboard-stat-card {
        position: relative;
        overflow: hidden;
        min-height: 190px;
        border-radius: 24px;
        padding: 22px;
        color: #fff;
        animation: dashboardFadeUp 0.8s ease both;
    }

    .dashboard-stat-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at top right, rgba(255, 255, 255, 0.22), transparent 28%),
            linear-gradient(180deg, rgba(255, 255, 255, 0.06), transparent 40%);
        pointer-events: none;
    }

    .dashboard-stat-card.gold {
        background: linear-gradient(135deg, #aa863f, #8c6a2c);
    }

    .dashboard-stat-card.emerald {
        background: linear-gradient(135deg, #059669, #047857);
    }

    .dashboard-stat-card.sky {
        background: linear-gradient(135deg, #0284c7, #0369a1);
    }

    .dashboard-stat-card.amber {
        background: linear-gradient(135deg, #d97706, #b45309);
    }

    .dashboard-stat-icon {
        width: 58px;
        height: 58px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.16);
        font-size: 1.55rem;
        margin-bottom: 18px;
    }

    .dashboard-stat-label {
        margin: 0;
        font-size: clamp(0.96rem, 1.2vw, 1.05rem);
        font-weight: 700;
        opacity: 0.92;
    }

    .dashboard-stat-value {
        margin: 12px 0 0;
        font-size: clamp(2.2rem, 3vw, 3.35rem);
        font-weight: 900;
        line-height: 1;
        letter-spacing: -0.02em;
    }

    .dashboard-stat-wave {
        position: absolute;
        inset-inline-end: -12px;
        bottom: -16px;
        font-size: 4.2rem;
        opacity: 0.12;
        transform: rotate(-12deg);
    }

    html[data-theme="dark"] .dashboard-hero {
        background:
            radial-gradient(circle at top right, rgba(213, 179, 106, 0.18), transparent 30%),
            linear-gradient(135deg, #111827 0%, #0f172a 46%, #1e293b 100%);
        border-color: rgba(148, 163, 184, 0.16);
        box-shadow: 0 22px 48px rgba(2, 6, 23, 0.35);
    }

    html[data-theme="dark"] .dashboard-mini-stat,
    html[data-theme="dark"] .dashboard-link-card,
    html[data-theme="dark"] .dashboard-section {
        background: rgba(15, 23, 42, 0.88);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 16px 36px rgba(2, 6, 23, 0.28);
    }

    html[data-theme="dark"] .dashboard-badge {
        background: rgba(213, 179, 106, 0.12);
        color: #f7d58e;
    }

    html[data-theme="dark"] .dashboard-link-card:hover {
        border-color: rgba(213, 179, 106, 0.24);
        box-shadow: 0 22px 42px rgba(2, 6, 23, 0.34);
    }

    html[data-theme="dark"] .dashboard-link-icon {
        background: color-mix(in srgb, currentColor 22%, rgba(15, 23, 42, 0.72));
    }

    html[data-theme="dark"] .dashboard-link-btn {
        box-shadow: 0 14px 24px rgba(2, 6, 23, 0.34);
    }

    html[data-theme="dark"] .dashboard-link-btn:hover {
        box-shadow: 0 18px 28px rgba(2, 6, 23, 0.42);
    }

    html[data-theme="dark"] .dashboard-stat-card.gold {
        background: linear-gradient(135deg, #c49b48, #8b6a2a);
    }

    html[data-theme="dark"] .dashboard-stat-card.emerald {
        background: linear-gradient(135deg, #059669, #065f46);
    }

    html[data-theme="dark"] .dashboard-stat-card.sky {
        background: linear-gradient(135deg, #0284c7, #075985);
    }

    html[data-theme="dark"] .dashboard-stat-card.amber {
        background: linear-gradient(135deg, #d97706, #92400e);
    }

    @keyframes dashboardFadeUp {
        from {
            opacity: 0;
            transform: translateY(18px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes dashboardFloat {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-4px);
        }
    }

    @media (max-width: 1199px) {
        .dashboard-quick-links {
            grid-template-columns: 1fr;
        }

        .dashboard-stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767px) {
        .dashboard-page {
            gap: 18px;
        }

        .dashboard-hero {
            border-radius: 24px;
        }

        .dashboard-hero-inner {
            flex-direction: column;
            align-items: stretch;
        }

        .dashboard-hero-stats {
            grid-template-columns: 1fr 1fr;
            min-width: 100%;
        }

        .dashboard-link-card,
        .dashboard-stat-card,
        .dashboard-section {
            border-radius: 22px;
        }

        .dashboard-section-head,
        .dashboard-stats-grid,
        .dashboard-section-text {
            padding-left: 18px;
            padding-right: 18px;
        }

        .dashboard-stats-grid {
            grid-template-columns: 1fr;
            padding-top: 18px;
            gap: 14px;
        }

        .dashboard-link-description {
            min-height: 0;
        }

        .dashboard-link-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-page">
    <section class="dashboard-hero">
        <div class="dashboard-hero-inner">
            <div>
                <span class="dashboard-badge">
                    <i class="bi bi-stars"></i>
                    {{ __('لوحة إدارة دار التكامل') }}
                </span>
                <h1 class="dashboard-title">{{ __('مرحباً بك في لوحة التحكم') }}</h1>
            </div>

            <div class="dashboard-hero-stats">
                @foreach($stats as $stat)
                    <div class="dashboard-mini-stat">
                        <div class="dashboard-mini-label">
                            <i class="bi {{ $stat['icon'] }}"></i>
                            {{ $stat['label'] }}
                        </div>
                        <div class="dashboard-mini-value">{{ $stat['value'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="dashboard-quick-links">
        @foreach($quickLinks as $index => $link)
            <article class="dashboard-link-card {{ $link['accent'] }}" style="animation-delay: {{ 0.08 + ($index * 0.06) }}s;">
                <div class="dashboard-link-icon">
                    <i class="bi {{ $link['icon'] }}"></i>
                </div>
                <h2 class="dashboard-link-title">{{ $link['title'] }}</h2>
                <p class="dashboard-link-description">{{ $link['description'] }}</p>
                <a href="{{ $link['route'] }}" class="dashboard-link-btn">
                    <i class="bi bi-arrow-up-left-circle-fill"></i>
                    {{ $link['button'] }}
                </a>
            </article>
        @endforeach
    </section>

    <section class="dashboard-section">
        <div class="dashboard-section-head">
            <h2 class="dashboard-section-title">
                <i class="bi bi-bar-chart-line-fill"></i>
                {{ __('إحصائيات سريعة') }}
            </h2>
        </div>
        <p class="dashboard-section-text">
            {{ __('ملخص لحظي لأهم مؤشرات النظام لمساعدتك على متابعة نشاط المساهمين والعروض والمعاملات وإجمالي الأسهم.') }}
        </p>

        <div class="dashboard-stats-grid">
            @foreach($stats as $index => $stat)
                <article class="dashboard-stat-card {{ $stat['accent'] }}" style="animation-delay: {{ 0.16 + ($index * 0.06) }}s;">
                    <div class="dashboard-stat-icon">
                        <i class="bi {{ $stat['icon'] }}"></i>
                    </div>
                    <p class="dashboard-stat-label">{{ $stat['label'] }}</p>
                    <p class="dashboard-stat-value">{{ $stat['value'] }}</p>
                    <div class="dashboard-stat-wave">
                        <i class="bi {{ $stat['icon'] }}"></i>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
</div>
@endsection
