@extends('layouts.app')

@section('title', __('التعاميم'))

@php
    $circularsCollection = $circulars->getCollection();
    $pageCircularsCount = $circularsCollection->count();
    $pageFilesSize = $circularsCollection->sum(fn ($circular) => (float) ($circular->file_size ?? 0));
@endphp

@push('styles')
<style>
    .cirs-page {
        padding: 8px 0 28px;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .cirs-page button,
    .cirs-page input {
        font: inherit;
    }

    .cirs-shell {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .cirs-hero {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        padding: 30px;
        background:
            radial-gradient(circle at top left, rgba(196, 168, 90, 0.32), transparent 34%),
            linear-gradient(135deg, #fffaf0 0%, #ffffff 48%, #f5efe2 100%);
        border: 1px solid rgba(170, 134, 63, 0.16);
        box-shadow: 0 18px 48px rgba(15, 23, 42, 0.08);
        animation: cirsFadeUp 0.7s ease both;
    }

    .cirs-hero::before,
    .cirs-hero::after {
        content: "";
        position: absolute;
        border-radius: 999px;
        pointer-events: none;
    }

    .cirs-hero::before {
        width: 210px;
        height: 210px;
        top: -100px;
        inset-inline-end: -80px;
        background: rgba(170, 134, 63, 0.10);
    }

    .cirs-hero::after {
        width: 150px;
        height: 150px;
        bottom: -80px;
        inset-inline-start: -40px;
        background: rgba(196, 168, 90, 0.14);
    }

    .cirs-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
    }

    .cirs-eyebrow {
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

    .cirs-title {
        margin: 0;
        font-size: clamp(2rem, 3vw, 2.6rem);
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .cirs-subtitle {
        margin: 10px 0 0;
        max-width: 760px;
        color: var(--text-secondary);
        font-size: 1.12rem;
        line-height: 1.8;
    }

    .cirs-hero-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .cirs-primary-btn,
    .cirs-secondary-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 20px;
        border-radius: 18px;
        text-decoration: none !important;
        font-size: 1.05rem;
        font-weight: 700;
        transition: transform 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease, color 0.25s ease;
    }

    .cirs-primary-btn {
        background: linear-gradient(135deg, var(--primary-color), #c49b48);
        color: #fff;
        box-shadow: 0 18px 28px rgba(170, 134, 63, 0.24);
    }

    .cirs-secondary-btn {
        background: rgba(255, 255, 255, 0.88);
        color: var(--text-primary);
        border: 1px solid rgba(170, 134, 63, 0.16);
    }

    .cirs-primary-btn:hover,
    .cirs-secondary-btn:hover {
        transform: translateY(-2px);
    }

    .cirs-stat-card {
        position: relative;
        overflow: hidden;
        padding: 22px 20px;
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        animation: cirsFadeUp 0.72s ease both;
    }

    .cirs-stat-card::after {
        content: "";
        position: absolute;
        width: 92px;
        height: 92px;
        border-radius: 50%;
        top: -36px;
        inset-inline-end: -24px;
        background: rgba(170, 134, 63, 0.08);
    }

    .cirs-stat-icon {
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

    .cirs-stat-value {
        margin: 0;
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-primary);
    }

    .cirs-stat-label {
        margin: 6px 0 0;
        color: var(--text-secondary);
        font-size: 1.02rem;
        font-weight: 600;
    }

    .cirs-toolbar {
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
        animation: cirsFadeUp 0.82s ease both;
    }

    .cirs-search {
        position: relative;
        flex: 1 1 340px;
    }

    .cirs-search i {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        inset-inline-start: 16px;
        color: var(--text-light);
        font-size: 1.1rem;
    }

    .cirs-search input {
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

    .cirs-search input:focus {
        outline: none;
        border-color: rgba(170, 134, 63, 0.48);
        box-shadow: 0 0 0 4px rgba(170, 134, 63, 0.10);
    }

    .cirs-toolbar-meta {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .cirs-meta-chip {
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

    .cirs-list-card {
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 16px 44px rgba(15, 23, 42, 0.07);
        overflow: hidden;
        animation: cirsFadeUp 0.92s ease both;
    }

    .cirs-list-head,
    .cir-row {
        display: grid;
        grid-template-columns: minmax(250px, 1.8fr) minmax(200px, 1.2fr) minmax(180px, 1fr) minmax(180px, 1fr) minmax(240px, 1.2fr);
        align-items: center;
        gap: 16px;
        padding: 18px 22px;
    }

    .cirs-list-head {
        background: linear-gradient(180deg, #fff9ef 0%, #fcf7ee 100%);
        color: var(--text-secondary);
        font-size: 0.98rem;
        font-weight: 800;
        border-bottom: 1px solid rgba(170, 134, 63, 0.12);
    }

    .cirs-list-body {
        padding: 8px 10px 12px;
    }

    .cir-row {
        margin: 10px 10px 0;
        border-radius: 22px;
        background: #fff;
        border: 1px solid rgba(226, 232, 240, 0.9);
        transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
        animation: cirsFadeUp 0.7s ease both;
    }

    .cir-row:hover {
        transform: translateY(-3px);
        border-color: rgba(170, 134, 63, 0.22);
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.08);
    }

    .cir-row.is-hidden {
        display: none;
    }

    .cir-main {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
    }

    .cir-avatar {
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

    .cir-name {
        margin: 0;
        color: var(--text-primary);
        font-size: 1.16rem;
        font-weight: 800;
        line-height: 1.35;
    }

    .cir-meta-line {
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        color: var(--text-secondary);
        font-size: 0.96rem;
    }

    .cir-badge {
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

    .cir-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-width: 0;
    }

    .cir-field-label {
        color: var(--text-light);
        font-size: 0.86rem;
        font-weight: 700;
    }

    .cir-field-value {
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.5;
        word-break: break-word;
    }

    .cir-field-value.muted {
        color: var(--text-secondary);
        font-weight: 600;
    }

    .cir-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .cir-action-btn {
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
        transition: transform 0.25s ease, opacity 0.25s ease;
    }

    .cir-action-btn:hover {
        transform: translateY(-2px);
        opacity: 0.96;
    }

    .cir-action-btn.view { background: rgba(14, 165, 233, 0.12); color: #0284c7; }
    .cir-action-btn.download { background: rgba(5, 150, 105, 0.12); color: var(--success-color); }
    .cir-action-btn.edit { background: rgba(217, 119, 6, 0.12); color: #b45309; }
    .cir-action-btn.delete { background: rgba(220, 38, 38, 0.10); color: var(--danger-color); }

    .cirs-empty {
        display: none;
        padding: 48px 24px 54px;
        text-align: center;
    }

    .cirs-empty.show {
        display: block;
        animation: cirsFadeUp 0.4s ease both;
    }

    .cirs-empty-icon {
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

    .cirs-empty h3 {
        margin: 0 0 8px;
        color: var(--text-primary);
        font-size: 1.7rem;
        font-weight: 800;
    }

    .cirs-empty p {
        margin: 0;
        color: var(--text-secondary);
        font-size: 1.02rem;
    }

    .cirs-pagination {
        padding: 22px;
        border-top: 1px solid rgba(170, 134, 63, 0.10);
        background: linear-gradient(180deg, rgba(255, 249, 239, 0.35) 0%, rgba(255, 255, 255, 0.92) 100%);
    }

    .cirs-pagination .pagination {
        margin: 0;
    }

    .cirs-pagination .pagination > li > a,
    .cirs-pagination .pagination > li > span {
        border-radius: 12px !important;
        margin: 0 4px;
        border: 1px solid rgba(170, 134, 63, 0.14);
        color: var(--text-primary);
        min-width: 42px;
        text-align: center;
    }

    .cirs-pagination .pagination > .active > span,
    .cirs-pagination .pagination > .active > span:hover,
    .cirs-pagination .pagination > .active > span:focus,
    .cirs-pagination .pagination > .active > a,
    .cirs-pagination .pagination > .active > a:hover,
    .cirs-pagination .pagination > .active > a:focus {
        background: linear-gradient(135deg, var(--primary-color), #c49b48);
        border-color: transparent;
        color: #fff;
    }

    html[data-theme="dark"] .cirs-hero {
        background:
            radial-gradient(circle at top left, rgba(141, 110, 43, 0.28), transparent 34%),
            linear-gradient(135deg, rgba(17, 24, 39, 0.98) 0%, rgba(15, 23, 42, 0.98) 48%, rgba(30, 41, 59, 0.98) 100%);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 24px 52px rgba(2, 6, 23, 0.34);
    }

    html[data-theme="dark"] .cirs-secondary-btn,
    html[data-theme="dark"] .cirs-stat-card,
    html[data-theme="dark"] .cirs-toolbar,
    html[data-theme="dark"] .cirs-list-card,
    html[data-theme="dark"] .cir-row,
    html[data-theme="dark"] .cirs-empty,
    html[data-theme="dark"] .cirs-meta-chip,
    html[data-theme="dark"] .cirs-pagination .pagination > li > a,
    html[data-theme="dark"] .cirs-pagination .pagination > li > span {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.14);
        color: var(--text-primary);
        box-shadow: 0 18px 38px rgba(2, 6, 23, 0.28);
    }

    html[data-theme="dark"] .cirs-list-head,
    html[data-theme="dark"] .cirs-pagination {
        background: rgba(15, 23, 42, 0.78);
        border-color: rgba(148, 163, 184, 0.12);
    }

    html[data-theme="dark"] .cirs-search input {
        background: rgba(15, 23, 42, 0.95);
        border-color: rgba(148, 163, 184, 0.20);
        color: var(--text-primary);
    }

    html[data-theme="dark"] .cirs-eyebrow,
    html[data-theme="dark"] .cir-badge {
        background: rgba(141, 110, 43, 0.16);
        color: #f6deb0;
    }

    @media (max-width: 1199px) {

        .cirs-list-head {
            display: none;
        }

        .cir-row {
            grid-template-columns: 1fr 1fr;
            align-items: flex-start;
        }

        .cir-main,
        .cir-actions {
            grid-column: 1 / -1;
        }

        .cir-actions {
            justify-content: flex-start;
        }
    }

    @media (max-width: 767px) {
        .cirs-page {
            padding: 2px 0 18px;
        }

        .cirs-hero {
            padding: 22px 18px;
            border-radius: 24px;
        }

        .cirs-title {
            font-size: clamp(1.75rem, 7vw, 2rem);
        }

        .cirs-subtitle {
            font-size: 1rem;
        }

        .cirs-primary-btn,
        .cirs-secondary-btn {
            width: 100%;
            justify-content: center;
        }

        .cirs-stats {
            grid-template-columns: 1fr;
        }

        .cirs-toolbar {
            padding: 16px;
        }

        .cirs-toolbar-meta {
            width: 100%;
        }

        .cirs-meta-chip {
            flex: 1 1 100%;
            justify-content: center;
        }

        .cirs-list-body {
            padding: 8px;
        }

        .cir-row {
            grid-template-columns: 1fr;
            padding: 16px;
            margin: 8px 0 0;
        }

        .cir-main {
            align-items: flex-start;
        }

        .cir-avatar {
            width: 50px;
            height: 50px;
            border-radius: 16px;
        }

        .cir-action-btn {
            flex: 1 1 calc(50% - 8px);
            justify-content: center;
        }
    }

    @keyframes cirsFadeUp {
        from {
            opacity: 0;
            transform: translateY(18px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
<div class="cirs-page">
    <div class="cirs-shell">
        <section class="cirs-hero">
            <div class="cirs-hero-inner">
                <div>
                    <span class="cirs-eyebrow">
                        <i class="bi bi-stars"></i>
                        {{ __('لوحة إدارة التعاميم') }}
                    </span>
                    <h1 class="cirs-title">{{ __('التعاميم') }}</h1>
                </div>

                <div class="cirs-hero-actions">
                    <a href="{{ route('circulars.create') }}" class="cirs-primary-btn">
                        <i class="bi bi-megaphone-fill"></i>
                        {{ __('إضافة تعميم جديد') }}
                    </a>
                    <a href="{{ route('dashboard') }}" class="cirs-secondary-btn">
                        <i class="bi bi-grid-1x2-fill"></i>
                        {{ __('العودة إلى لوحة التحكم') }}
                    </a>
                </div>
            </div>
        </section>
        <section class="cirs-toolbar">
            <div class="cirs-search">
                <i class="bi bi-search"></i>
                <input
                    type="search"
                    id="circularsSearch"
                    placeholder="{{ __('ابحث باسم التعميم أو الاسم الأصلي أو الامتداد أو الاجتماع...') }}"
                    autocomplete="off"
                >
            </div>

            <div class="cirs-toolbar-meta">
                <span class="cirs-meta-chip">
                    <i class="bi bi-eye-fill"></i>
                    <span id="visibleCircularsCount">{{ $pageCircularsCount }}</span>
                    {{ __('نتيجة ظاهرة') }}
                </span>
                <span class="cirs-meta-chip">
                    <i class="bi bi-collection-fill"></i>
                    {{ __('صفحة') }} {{ $circulars->currentPage() }} / {{ $circulars->lastPage() }}
                </span>
            </div>
        </section>

        <section class="cirs-list-card">
            @if($pageCircularsCount > 0)
                <div class="cirs-list-head">
                    <div>{{ __('التعميم') }}</div>
                    <div>{{ __('الاسم الأصلي') }}</div>
                    <div>{{ __('الحجم والتاريخ') }}</div>
                    <div>{{ __('الاجتماع المرتبط') }}</div>
                    <div>{{ __('الإجراءات') }}</div>
                </div>

                <div class="cirs-list-body" id="circularsList">
                    @foreach($circulars as $circular)
                        @php
                            $meetingName = optional($circular->meeting)->name ?: __('بدون اجتماع');
                            $searchableText = implode(' ', [
                                $circular->name,
                                $circular->original_filename,
                                $circular->file_type,
                                $circular->file_extension,
                                $meetingName,
                            ]);
                        @endphp

                        <article class="cir-row cir-search-item"
                            data-search="{{ mb_strtolower($searchableText) }}"
                            style="animation-delay: {{ 0.05 + ($loop->index * 0.04) }}s;">
                            <div class="cir-main">
                                <div class="cir-avatar">
                                    <i class="fa {{ $circular->file_icon }}"></i>
                                </div>

                                <div style="min-width: 0;">
                                    <h3 class="cir-name">{{ $circular->name }}</h3>
                                    <div class="cir-meta-line">
                                        <span class="cir-badge">
                                            <i class="bi bi-hash"></i>
                                            #{{ $circular->id }}
                                        </span>
                                        <span class="cir-badge">
                                            <i class="bi bi-file-earmark-text"></i>
                                            {{ strtoupper($circular->file_extension ?: '-') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="cir-field">
                                <span class="cir-field-label">{{ __('الاسم الأصلي') }}</span>
                                <span class="cir-field-value">{{ $circular->original_filename }}</span>
                                <span class="cir-field-value muted">{{ $circular->file_type ?: __('غير محدد') }}</span>
                            </div>

                            <div class="cir-field">
                                <span class="cir-field-label">{{ __('الحجم والتاريخ') }}</span>
                                <span class="cir-field-value">{{ $circular->file_size_human }}</span>
                                <span class="cir-field-value muted">{{ $circular->created_at->format('Y-m-d H:i') }}</span>
                            </div>

                            <div class="cir-field">
                                <span class="cir-field-label">{{ __('الاجتماع المرتبط') }}</span>
                                <span class="cir-field-value">{{ $meetingName }}</span>
                                <span class="cir-field-value muted">
                                    {{ optional($circular->meeting?->date)->format('Y-m-d') ?: __('بدون تاريخ') }}
                                </span>
                            </div>

                            <div class="cir-actions">
                                <a href="{{ route('circulars.show', $circular) }}" class="cir-action-btn view" title="{{ __('تفاصيل') }}">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="{{ route('circulars.download', $circular) }}" target="_blank" rel="noopener noreferrer" class="cir-action-btn download" title="{{ __('تحميل') }}">
                                    <i class="bi bi-download"></i>
                                </a>
                                <a href="{{ route('circulars.edit', $circular) }}" class="cir-action-btn edit" title="{{ __('تعديل') }}">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('circulars.destroy', $circular) }}" method="POST" style="display: inline-flex;" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا التعميم؟') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="cir-action-btn delete" title="{{ __('حذف') }}">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="cirs-empty" id="circularsEmptyState">
                    <div class="cirs-empty-icon">
                        <i class="bi bi-search-heart"></i>
                    </div>
                    <h3>{{ __('لا توجد نتائج مطابقة') }}</h3>
                    <p>{{ __('جرّب البحث باسم مختلف أو جزء من الاسم الأصلي أو الامتداد أو اسم الاجتماع للوصول إلى التعميم المطلوب.') }}</p>
                </div>

                <div class="cirs-pagination">
                    {{ $circulars->links() }}
                </div>
            @else
                <div class="cirs-empty show">
                    <div class="cirs-empty-icon">
                        <i class="bi bi-megaphone"></i>
                    </div>
                    <h3>{{ __('لا توجد تعاميم بعد') }}</h3>
                    <p>{{ __('ابدأ بإضافة أول تعميم ليظهر هنا داخل قائمة منظمة وحديثة تدعم البحث والوصول السريع.') }}</p>
                    <div style="margin-top: 18px;">
                        <a href="{{ route('circulars.create') }}" class="cirs-primary-btn">
                            <i class="bi bi-plus-circle-fill"></i>
                            {{ __('إضافة تعميم جديد') }}
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
        const searchInput = document.getElementById('circularsSearch');
        const rows = Array.from(document.querySelectorAll('.cir-search-item'));
        const visibleCount = document.getElementById('visibleCircularsCount');
        const emptyState = document.getElementById('circularsEmptyState');
        const pagination = document.querySelector('.cirs-pagination');

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
