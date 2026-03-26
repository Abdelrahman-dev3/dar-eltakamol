@extends('layouts.app')

@section('title', __('الاجتماعات'))

@php
    $meetingsCollection = $meetings->getCollection();
    $totalMeetings = $meetings->total();
    $pageMeetingsCount = $meetingsCollection->count();
    $pageInviteesTotal = $meetingsCollection->sum('users_count');
    $pageAttachmentsTotal = $meetingsCollection->sum('attachments_count');
    $upcomingMeetingsCount = $meetingsCollection->filter(fn ($meeting) => $meeting->date && $meeting->date->isFuture())->count();
@endphp

@push('styles')
<style>
    .meetings-page {
        padding: 8px 0 28px;
        color: var(--text-primary);
        font-size: 1rem;
    }
    .meetings-shell { display: flex; flex-direction: column; gap: 24px; }
    .meetings-hero {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        padding: 30px;
        background:
            radial-gradient(circle at top left, rgba(196, 168, 90, 0.32), transparent 34%),
            linear-gradient(135deg, #fffaf0 0%, #ffffff 48%, #f5efe2 100%);
        border: 1px solid rgba(170, 134, 63, 0.16);
        box-shadow: 0 18px 48px rgba(15, 23, 42, 0.08);
        animation: meetingsFadeUp 0.7s ease both;
    }
    .meetings-hero::before,
    .meetings-hero::after {
        content: "";
        position: absolute;
        border-radius: 999px;
        pointer-events: none;
    }
    .meetings-hero::before {
        width: 210px;
        height: 210px;
        top: -100px;
        inset-inline-end: -80px;
        background: rgba(170, 134, 63, 0.10);
    }
    .meetings-hero::after {
        width: 150px;
        height: 150px;
        bottom: -80px;
        inset-inline-start: -40px;
        background: rgba(196, 168, 90, 0.14);
    }
    .meetings-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
    }
    .meetings-eyebrow {
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
    .meetings-page button,
    .meetings-page input {
        font: inherit;
    }
    .meetings-title {
        margin: 0;
        font-size: clamp(2rem, 3vw, 2.6rem);
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1.2;
    }
    .meetings-subtitle {
        margin: 10px 0 0;
        max-width: 760px;
        color: var(--text-secondary);
        font-size: 1.22rem;
        line-height: 1.8;
    }
    .meetings-hero-actions { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
    .meetings-primary-btn,
    .meetings-secondary-btn {
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
    .meetings-primary-btn {
        background: linear-gradient(135deg, var(--primary-color), #c49b48);
        color: #fff;
        box-shadow: 0 18px 28px rgba(170, 134, 63, 0.24);
    }
    .meetings-secondary-btn {
        background: rgba(255, 255, 255, 0.88);
        color: var(--text-primary);
        border: 1px solid rgba(170, 134, 63, 0.16);
    }
    .meetings-primary-btn:hover,
    .meetings-secondary-btn:hover { transform: translateY(-2px); }
    .meetings-primary-btn:hover { color: #fff; box-shadow: 0 22px 34px rgba(170, 134, 63, 0.28); }
    .meetings-secondary-btn:hover { color: var(--primary-color); background: #fff; }
    .meetings-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }
    .meetings-stat-card {
        position: relative;
        overflow: hidden;
        padding: 22px 20px;
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        animation: meetingsFadeUp 0.72s ease both;
    }
    .meetings-stat-card::after {
        content: "";
        position: absolute;
        width: 92px;
        height: 92px;
        border-radius: 50%;
        top: -36px;
        inset-inline-end: -24px;
        background: rgba(170, 134, 63, 0.08);
    }
    .meetings-stat-icon {
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
    .meetings-stat-value {
        margin: 0;
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-primary);
    }
    .meetings-stat-label {
        margin: 6px 0 0;
        color: var(--text-secondary);
        font-size: 1.05rem;
        font-weight: 600;
    }
    .meetings-toolbar {
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
        animation: meetingsFadeUp 0.82s ease both;
    }
    .meetings-search { position: relative; flex: 1 1 340px; }
    .meetings-search i {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        inset-inline-start: 16px;
        color: var(--text-light);
        font-size: 1.1rem;
    }
    .meetings-search input {
        width: 100%;
        height: 54px;
        padding-inline-start: 46px;
        padding-inline-end: 18px;
        border-radius: 18px;
        border: 1px solid rgba(170, 134, 63, 0.16);
        background: #fff;
        color: var(--text-primary);
        font-size: 1.08rem;
    }
    .meetings-search input:focus {
        outline: none;
        border-color: rgba(170, 134, 63, 0.48);
        box-shadow: 0 0 0 4px rgba(170, 134, 63, 0.10);
    }
    .meetings-toolbar-meta { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .meetings-meta-chip {
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
    .meetings-list-card {
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 16px 44px rgba(15, 23, 42, 0.07);
        overflow: hidden;
        animation: meetingsFadeUp 0.92s ease both;
    }
    .meetings-list-head,
    .meeting-row {
        display: grid;
        grid-template-columns: minmax(280px, 2.2fr) minmax(210px, 1.2fr) minmax(180px, 1fr) minmax(150px, 0.8fr) minmax(250px, 1.3fr);
        align-items: center;
        gap: 16px;
        padding: 18px 22px;
    }
    .meetings-list-head {
        background: linear-gradient(180deg, #fff9ef 0%, #fcf7ee 100%);
        color: var(--text-secondary);
        font-size: 0.98rem;
        font-weight: 800;
        border-bottom: 1px solid rgba(170, 134, 63, 0.12);
    }
    .meetings-list-body { padding: 8px 10px 12px; }
    .meeting-row {
        margin: 10px 10px 0;
        border-radius: 22px;
        background: #fff;
        border: 1px solid rgba(226, 232, 240, 0.9);
        transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
        animation: meetingsFadeUp 0.7s ease both;
    }
    .meeting-row:hover {
        transform: translateY(-3px);
        border-color: rgba(170, 134, 63, 0.22);
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.08);
    }
    .meeting-row.is-hidden { display: none; }
    .meeting-main { display: flex; align-items: center; gap: 14px; min-width: 0; }
    .meeting-avatar {
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
        font-weight: 800;
        box-shadow: 0 12px 24px rgba(170, 134, 63, 0.18);
    }
    .meeting-name {
        margin: 0;
        color: var(--text-primary);
        font-size: 1.16rem;
        font-weight: 800;
        line-height: 1.35;
    }
    .meeting-meta-line {
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        color: var(--text-secondary);
        font-size: 0.96rem;
    }
    .meeting-badge {
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
    .meeting-badge.success { background: rgba(5, 150, 105, 0.10); color: var(--success-color); }
    .meeting-badge.info { background: rgba(14, 165, 233, 0.12); color: #0284c7; }
    .meeting-badge.danger { background: rgba(220, 38, 38, 0.10); color: var(--danger-color); }
    .meeting-field { display: flex; flex-direction: column; gap: 6px; min-width: 0; }
    .meeting-field-label {
        color: var(--text-light);
        font-size: 0.86rem;
        font-weight: 700;
    }
    .meeting-field-value {
        color: var(--text-primary);
        font-size: 1.04rem;
        font-weight: 700;
        line-height: 1.5;
        word-break: break-word;
    }
    .meeting-field-value.muted { color: var(--text-secondary); font-weight: 600; }
    .meeting-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: flex-end;
        flex-wrap: wrap;
    }
    .meeting-action-btn {
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
    .meeting-action-btn:hover { transform: translateY(-2px); opacity: 0.96; }
    .meeting-action-btn.view { background: rgba(14, 165, 233, 0.12); color: #0284c7; }
    .meeting-action-btn.open { background: rgba(5, 150, 105, 0.12); color: var(--success-color); }
    .meeting-action-btn.edit { background: rgba(217, 119, 6, 0.12); color: #b45309; }
    .meeting-action-btn.delete { background: rgba(220, 38, 38, 0.10); color: var(--danger-color); }
    .meetings-empty {
        display: none;
        padding: 48px 24px 54px;
        text-align: center;
    }
    .meetings-empty.show { display: block; animation: meetingsFadeUp 0.4s ease both; }
    .meetings-empty-icon {
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
    .meetings-empty h3 {
        margin: 0 0 8px;
        color: var(--text-primary);
        font-size: 1.7rem;
        font-weight: 800;
    }
    .meetings-empty p { margin: 0; color: var(--text-secondary); font-size: 1.06rem; }
    .meetings-pagination {
        padding: 22px;
        border-top: 1px solid rgba(170, 134, 63, 0.10);
        background: linear-gradient(180deg, rgba(255, 249, 239, 0.35) 0%, rgba(255, 255, 255, 0.92) 100%);
    }
    .meetings-pagination .pagination { margin: 0; }
    .meetings-pagination .pagination > li > a,
    .meetings-pagination .pagination > li > span {
        border-radius: 12px !important;
        margin: 0 4px;
        border: 1px solid rgba(170, 134, 63, 0.14);
        color: var(--text-primary);
        min-width: 42px;
        text-align: center;
    }
    .meetings-pagination .pagination > .active > span,
    .meetings-pagination .pagination > .active > span:hover,
    .meetings-pagination .pagination > .active > span:focus,
    .meetings-pagination .pagination > .active > a,
    .meetings-pagination .pagination > .active > a:hover,
    .meetings-pagination .pagination > .active > a:focus {
        background: linear-gradient(135deg, var(--primary-color), #c49b48);
        border-color: transparent;
        color: #fff;
    }
    html[data-theme="dark"] .meetings-hero {
        background:
            radial-gradient(circle at top left, rgba(141, 110, 43, 0.28), transparent 34%),
            linear-gradient(135deg, rgba(17, 24, 39, 0.98) 0%, rgba(15, 23, 42, 0.98) 48%, rgba(30, 41, 59, 0.98) 100%);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 24px 52px rgba(2, 6, 23, 0.34);
    }
    html[data-theme="dark"] .meetings-primary-btn { color: #fff; }
    html[data-theme="dark"] .meetings-secondary-btn,
    html[data-theme="dark"] .meetings-stat-card,
    html[data-theme="dark"] .meetings-toolbar,
    html[data-theme="dark"] .meetings-list-card,
    html[data-theme="dark"] .meeting-row,
    html[data-theme="dark"] .meetings-empty,
    html[data-theme="dark"] .meetings-meta-chip,
    html[data-theme="dark"] .meetings-pagination .pagination > li > a,
    html[data-theme="dark"] .meetings-pagination .pagination > li > span {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.14);
        color: var(--text-primary);
        box-shadow: 0 18px 38px rgba(2, 6, 23, 0.28);
    }
    html[data-theme="dark"] .meetings-list-head,
    html[data-theme="dark"] .meetings-pagination {
        background: rgba(15, 23, 42, 0.78);
        border-color: rgba(148, 163, 184, 0.12);
    }
    html[data-theme="dark"] .meetings-search input {
        background: rgba(15, 23, 42, 0.95);
        border-color: rgba(148, 163, 184, 0.20);
        color: var(--text-primary);
    }
    html[data-theme="dark"] .meetings-search input:focus {
        background: rgba(15, 23, 42, 1);
    }
    html[data-theme="dark"] .meeting-badge,
    html[data-theme="dark"] .meetings-eyebrow {
        background: rgba(141, 110, 43, 0.16);
        color: #f6deb0;
    }
    html[data-theme="dark"] .meeting-badge.success { background: rgba(5, 150, 105, 0.16); color: #6ee7b7; }
    html[data-theme="dark"] .meeting-badge.info { background: rgba(14, 165, 233, 0.16); color: #7dd3fc; }
    html[data-theme="dark"] .meeting-badge.danger { background: rgba(220, 38, 38, 0.16); color: #fca5a5; }
    html[data-theme="dark"] .meetings-secondary-btn:hover,
    html[data-theme="dark"] .meeting-row:hover {
        border-color: rgba(141, 110, 43, 0.24);
    }
    @media (max-width: 1199px) {
        .meetings-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .meetings-list-head { display: none; }
        .meeting-row {
            grid-template-columns: 1fr 1fr;
            align-items: flex-start;
        }
        .meeting-main,
        .meeting-actions { grid-column: 1 / -1; }
        .meeting-actions { justify-content: flex-start; }
    }
    @media (max-width: 767px) {
        .meetings-page { padding: 2px 0 18px; }
        .meetings-hero { padding: 22px 18px; border-radius: 24px; }
        .meetings-title { font-size: clamp(1.75rem, 7vw, 2rem); }
        .meetings-subtitle { font-size: 1.02rem; }
        .meetings-primary-btn,
        .meetings-secondary-btn { width: 100%; justify-content: center; }
        .meetings-stats { grid-template-columns: 1fr; }
        .meetings-toolbar { padding: 16px; }
        .meetings-toolbar-meta { width: 100%; }
        .meetings-meta-chip { flex: 1 1 100%; justify-content: center; }
        .meetings-list-body { padding: 8px; }
        .meeting-row {
            grid-template-columns: 1fr;
            padding: 16px;
            margin: 8px 0 0;
        }
        .meeting-main { align-items: flex-start; }
        .meeting-avatar { width: 50px; height: 50px; border-radius: 16px; }
        .meeting-action-btn { flex: 1 1 calc(50% - 8px); justify-content: center; }
    }
    @keyframes meetingsFadeUp {
        from { opacity: 0; transform: translateY(18px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="meetings-page">
    <div class="meetings-shell">
        <section class="meetings-hero">
            <div class="meetings-hero-inner">
                <div>
                    <span class="meetings-eyebrow">
                        <i class="bi bi-stars"></i>
                        {{ __('لوحة إدارة الاجتماعات') }}
                    </span>
                    <h1 class="meetings-title">{{ __('الاجتماعات') }}</h1>
                    <p class="meetings-subtitle">{{ __('تابع جميع الاجتماعات القادمة والمنتهية من شاشة واحدة حديثة، مع وصول سريع للروابط والمدعوين والمرفقات.') }}</p>
                </div>

                <div class="meetings-hero-actions">
                    <a href="{{ route('meetings.create') }}" class="meetings-primary-btn">
                        <i class="bi bi-calendar-plus-fill"></i>
                        {{ __('إضافة اجتماع جديد') }}
                    </a>
                    <a href="{{ route('dashboard') }}" class="meetings-secondary-btn">
                        <i class="bi bi-grid-1x2-fill"></i>
                        {{ __('العودة إلى لوحة التحكم') }}
                    </a>
                </div>
            </div>
        </section>

        <section class="meetings-stats">
            <article class="meetings-stat-card" style="animation-delay: 0.05s;">
                <div class="meetings-stat-icon"><i class="bi bi-calendar-event-fill"></i></div>
                <p class="meetings-stat-value">{{ number_format($totalMeetings) }}</p>
                <p class="meetings-stat-label">{{ __('إجمالي الاجتماعات') }}</p>
            </article>
            <article class="meetings-stat-card" style="animation-delay: 0.12s;">
                <div class="meetings-stat-icon"><i class="bi bi-clock-history"></i></div>
                <p class="meetings-stat-value">{{ number_format($upcomingMeetingsCount) }}</p>
                <p class="meetings-stat-label">{{ __('اجتماعات قادمة في الصفحة') }}</p>
            </article>
            <article class="meetings-stat-card" style="animation-delay: 0.19s;">
                <div class="meetings-stat-icon"><i class="bi bi-people-fill"></i></div>
                <p class="meetings-stat-value">{{ number_format($pageInviteesTotal) }}</p>
                <p class="meetings-stat-label">{{ __('إجمالي المدعوين في الصفحة') }}</p>
            </article>
            <article class="meetings-stat-card" style="animation-delay: 0.26s;">
                <div class="meetings-stat-icon"><i class="bi bi-paperclip"></i></div>
                <p class="meetings-stat-value">{{ number_format($pageAttachmentsTotal) }}</p>
                <p class="meetings-stat-label">{{ __('المرفقات المرتبطة بالصفحة') }}</p>
            </article>
        </section>

        <section class="meetings-toolbar">
            <div class="meetings-search">
                <i class="bi bi-search"></i>
                <input
                    type="search"
                    id="meetingsSearch"
                    placeholder="{{ __('ابحث باسم الاجتماع أو الرابط أو أسماء المدعوين...') }}"
                    autocomplete="off"
                >
            </div>

            <div class="meetings-toolbar-meta">
                <span class="meetings-meta-chip">
                    <i class="bi bi-eye-fill"></i>
                    <span id="visibleMeetingsCount">{{ $pageMeetingsCount }}</span>
                    {{ __('نتيجة ظاهرة') }}
                </span>
                <span class="meetings-meta-chip">
                    <i class="bi bi-collection-fill"></i>
                    {{ __('صفحة') }} {{ $meetings->currentPage() }} / {{ $meetings->lastPage() }}
                </span>
            </div>
        </section>

        <section class="meetings-list-card">
            @if($pageMeetingsCount > 0)
                <div class="meetings-list-head">
                    <div>{{ __('الاجتماع') }}</div>
                    <div>{{ __('الجدولة') }}</div>
                    <div>{{ __('المدعوون') }}</div>
                    <div>{{ __('المرفقات') }}</div>
                    <div>{{ __('الإجراءات') }}</div>
                </div>

                <div class="meetings-list-body" id="meetingsList">
                    @foreach($meetings as $meeting)
                        @php
                            $inviteeNames = $meeting->users->pluck('name')->filter()->values();
                            $searchableText = implode(' ', [
                                $meeting->name,
                                $meeting->url,
                                $meeting->date?->format('Y-m-d H:i'),
                                $inviteeNames->implode(' '),
                            ]);
                            $statusClass = $meeting->date->isToday()
                                ? 'info'
                                : ($meeting->date->isFuture() ? 'success' : 'danger');
                            $statusText = $meeting->date->isToday()
                                ? __('اليوم')
                                : ($meeting->date->isFuture() ? __('قادم') : __('منتهٍ'));
                        @endphp

                        <article class="meeting-row meeting-search-item" data-search="{{ mb_strtolower($searchableText) }}"
                            style="animation-delay: {{ 0.05 + ($loop->index * 0.04) }}s;">
                            <div class="meeting-main">
                                <div class="meeting-avatar">
                                    <i class="bi bi-camera-video-fill"></i>
                                </div>

                                <div style="min-width: 0;">
                                    <h3 class="meeting-name">{{ $meeting->name }}</h3>
                                    <div class="meeting-meta-line">
                                        <span class="meeting-badge">
                                            <i class="bi bi-hash"></i>
                                            #{{ $meeting->id }}
                                        </span>
                                        <span class="meeting-badge {{ $statusClass }}">
                                            <i class="bi bi-clock"></i>
                                            {{ $statusText }}
                                        </span>
                                        <span class="meeting-badge">
                                            <i class="bi bi-link-45deg"></i>
                                            {{ \Illuminate\Support\Str::limit(parse_url($meeting->url, PHP_URL_HOST) ?: $meeting->url, 24) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="meeting-field">
                                <span class="meeting-field-label">{{ __('موعد الاجتماع') }}</span>
                                <span class="meeting-field-value">{{ $meeting->date->format('Y-m-d H:i') }}</span>
                                <span class="meeting-field-value muted">{{ $meeting->date->diffForHumans() }}</span>
                            </div>

                            <div class="meeting-field">
                                <span class="meeting-field-label">{{ __('المدعوون') }}</span>
                                <span class="meeting-field-value">{{ number_format($meeting->users_count) }}</span>
                                <span class="meeting-field-value muted">
                                    {{ $inviteeNames->isNotEmpty() ? \Illuminate\Support\Str::limit($inviteeNames->implode('، '), 45) : __('لم يتم تحديد مدعوين بعد') }}
                                </span>
                            </div>

                            <div class="meeting-field">
                                <span class="meeting-field-label">{{ __('المرفقات') }}</span>
                                <span class="meeting-field-value">{{ number_format($meeting->attachments_count) }}</span>
                                <span class="meeting-field-value muted">
                                    {{ $meeting->attachments_count > 0 ? __('ملفات مرتبطة بالاجتماع') : __('لا توجد مرفقات') }}
                                </span>
                            </div>

                            <div class="meeting-actions">
                                <a href="{{ route('meetings.show', $meeting) }}" class="meeting-action-btn view" title="{{ __('تفاصيل') }}">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="{{ $meeting->url }}" target="_blank" rel="noopener noreferrer" class="meeting-action-btn open" title="{{ __('فتح الرابط') }}">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                                <a href="{{ route('meetings.edit', $meeting) }}" class="meeting-action-btn edit" title="{{ __('تعديل') }}">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('meetings.destroy', $meeting) }}" method="POST" style="display: inline-flex;" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا الاجتماع؟') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="meeting-action-btn delete" title="{{ __('حذف') }}">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="meetings-empty" id="meetingsEmptyState">
                    <div class="meetings-empty-icon">
                        <i class="bi bi-search-heart"></i>
                    </div>
                    <h3>{{ __('لا توجد نتائج مطابقة') }}</h3>
                    <p>{{ __('جرّب البحث باسم مختلف أو جزء من الرابط أو اسم أحد المدعوين للوصول للاجتماع المطلوب.') }}</p>
                </div>

                <div class="meetings-pagination">
                    {{ $meetings->links() }}
                </div>
            @else
                <div class="meetings-empty show">
                    <div class="meetings-empty-icon">
                        <i class="bi bi-calendar-x-fill"></i>
                    </div>
                    <h3>{{ __('لا توجد اجتماعات بعد') }}</h3>
                    <p>{{ __('ابدأ بإضافة أول اجتماع لتظهر المواعيد والمدعوون والمرفقات هنا بشكل منظم وواضح.') }}</p>
                    <div style="margin-top: 18px;">
                        <a href="{{ route('meetings.create') }}" class="meetings-primary-btn">
                            <i class="bi bi-plus-circle-fill"></i>
                            {{ __('إضافة اجتماع جديد') }}
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
        const searchInput = document.getElementById('meetingsSearch');
        const rows = Array.from(document.querySelectorAll('.meeting-search-item'));
        const visibleCount = document.getElementById('visibleMeetingsCount');
        const emptyState = document.getElementById('meetingsEmptyState');
        const pagination = document.querySelector('.meetings-pagination');

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
