@extends('layouts.app')

@section('title', __('اللوائح'))

@php
    $regulationsCollection = $regulations->getCollection();
    $pageRegulationsCount = $regulationsCollection->count();
    $pageFilesSize = $regulationsCollection->sum(fn ($regulation) => (float) ($regulation->file_size ?? 0));
@endphp

@push('styles')
<style>
    .regs-page {
        padding: 8px 0 28px;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .regs-page button,
    .regs-page input {
        font: inherit;
    }

    .regs-shell {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .regs-hero {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        padding: 30px;
        background:
            radial-gradient(circle at top left, rgba(196, 168, 90, 0.32), transparent 34%),
            linear-gradient(135deg, #fffaf0 0%, #ffffff 48%, #f5efe2 100%);
        border: 1px solid rgba(170, 134, 63, 0.16);
        box-shadow: 0 18px 48px rgba(15, 23, 42, 0.08);
        animation: regsFadeUp 0.7s ease both;
    }

    .regs-hero::before,
    .regs-hero::after {
        content: "";
        position: absolute;
        border-radius: 999px;
        pointer-events: none;
    }

    .regs-hero::before {
        width: 210px;
        height: 210px;
        top: -100px;
        inset-inline-end: -80px;
        background: rgba(170, 134, 63, 0.10);
    }

    .regs-hero::after {
        width: 150px;
        height: 150px;
        bottom: -80px;
        inset-inline-start: -40px;
        background: rgba(196, 168, 90, 0.14);
    }

    .regs-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
    }

    .regs-eyebrow {
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

    .regs-title {
        margin: 0;
        font-size: clamp(2rem, 3vw, 2.6rem);
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .regs-subtitle {
        margin: 10px 0 0;
        max-width: 760px;
        color: var(--text-secondary);
        font-size: 1.12rem;
        line-height: 1.8;
    }

    .regs-hero-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .regs-primary-btn,
    .regs-secondary-btn {
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

    .regs-primary-btn {
        background: linear-gradient(135deg, var(--primary-color), #c49b48);
        color: #fff;
        box-shadow: 0 18px 28px rgba(170, 134, 63, 0.24);
    }

    .regs-secondary-btn {
        background: rgba(255, 255, 255, 0.88);
        color: var(--text-primary);
        border: 1px solid rgba(170, 134, 63, 0.16);
    }

    .regs-primary-btn:hover,
    .regs-secondary-btn:hover {
        transform: translateY(-2px);
    }

    .regs-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }

    .regs-stat-card {
        position: relative;
        overflow: hidden;
        padding: 22px 20px;
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        animation: regsFadeUp 0.72s ease both;
    }

    .regs-stat-card::after {
        content: "";
        position: absolute;
        width: 92px;
        height: 92px;
        border-radius: 50%;
        top: -36px;
        inset-inline-end: -24px;
        background: rgba(170, 134, 63, 0.08);
    }

    .regs-stat-icon {
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

    .regs-stat-value {
        margin: 0;
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-primary);
    }

    .regs-stat-label {
        margin: 6px 0 0;
        color: var(--text-secondary);
        font-size: 1.02rem;
        font-weight: 600;
    }

    .regs-toolbar {
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
        animation: regsFadeUp 0.82s ease both;
    }

    .regs-search {
        position: relative;
        flex: 1 1 340px;
    }

    .regs-search i {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        inset-inline-start: 16px;
        color: var(--text-light);
        font-size: 1.1rem;
    }

    .regs-search input {
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

    .regs-search input:focus {
        outline: none;
        border-color: rgba(170, 134, 63, 0.48);
        box-shadow: 0 0 0 4px rgba(170, 134, 63, 0.10);
    }

    .regs-toolbar-meta {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .regs-meta-chip {
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

    .regs-list-card {
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 16px 44px rgba(15, 23, 42, 0.07);
        overflow: hidden;
        animation: regsFadeUp 0.92s ease both;
    }

    .regs-list-head,
    .reg-row {
        display: grid;
        grid-template-columns: minmax(280px, 2.1fr) minmax(170px, 1fr) minmax(180px, 1fr) minmax(160px, 0.9fr) minmax(240px, 1.2fr);
        align-items: center;
        gap: 16px;
        padding: 18px 22px;
    }

    .regs-list-head {
        background: linear-gradient(180deg, #fff9ef 0%, #fcf7ee 100%);
        color: var(--text-secondary);
        font-size: 0.98rem;
        font-weight: 800;
        border-bottom: 1px solid rgba(170, 134, 63, 0.12);
    }

    .regs-list-body {
        padding: 8px 10px 12px;
    }

    .reg-row {
        margin: 10px 10px 0;
        border-radius: 22px;
        background: #fff;
        border: 1px solid rgba(226, 232, 240, 0.9);
        transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
        animation: regsFadeUp 0.7s ease both;
    }

    .reg-row:hover {
        transform: translateY(-3px);
        border-color: rgba(170, 134, 63, 0.22);
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.08);
    }

    .reg-row.is-hidden {
        display: none;
    }

    .reg-main {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
    }

    .reg-avatar {
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

    .reg-name {
        margin: 0;
        color: var(--text-primary);
        font-size: 1.16rem;
        font-weight: 800;
        line-height: 1.35;
    }

    .reg-meta-line {
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        color: var(--text-secondary);
        font-size: 0.96rem;
    }

    .reg-badge {
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

    .reg-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-width: 0;
    }

    .reg-field-label {
        color: var(--text-light);
        font-size: 0.86rem;
        font-weight: 700;
    }

    .reg-field-value {
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.5;
        word-break: break-word;
    }

    .reg-field-value.muted {
        color: var(--text-secondary);
        font-weight: 600;
    }

    .reg-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .reg-action-btn {
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

    .reg-action-btn:hover {
        transform: translateY(-2px);
        opacity: 0.96;
    }

    .reg-action-btn.view { background: rgba(14, 165, 233, 0.12); color: #0284c7; }
    .reg-action-btn.download { background: rgba(5, 150, 105, 0.12); color: var(--success-color); }
    .reg-action-btn.edit { background: rgba(217, 119, 6, 0.12); color: #b45309; }
    .reg-action-btn.delete { background: rgba(220, 38, 38, 0.10); color: var(--danger-color); }

    .regs-empty {
        display: none;
        padding: 48px 24px 54px;
        text-align: center;
    }

    .regs-empty.show {
        display: block;
        animation: regsFadeUp 0.4s ease both;
    }

    .regs-empty-icon {
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

    .regs-empty h3 {
        margin: 0 0 8px;
        color: var(--text-primary);
        font-size: 1.7rem;
        font-weight: 800;
    }

    .regs-empty p {
        margin: 0;
        color: var(--text-secondary);
        font-size: 1.02rem;
    }

    .regs-pagination {
        padding: 22px;
        border-top: 1px solid rgba(170, 134, 63, 0.10);
        background: linear-gradient(180deg, rgba(255, 249, 239, 0.35) 0%, rgba(255, 255, 255, 0.92) 100%);
    }

    .regs-pagination .pagination {
        margin: 0;
    }

    .regs-pagination .pagination > li > a,
    .regs-pagination .pagination > li > span {
        border-radius: 12px !important;
        margin: 0 4px;
        border: 1px solid rgba(170, 134, 63, 0.14);
        color: var(--text-primary);
        min-width: 42px;
        text-align: center;
    }

    .regs-pagination .pagination > .active > span,
    .regs-pagination .pagination > .active > span:hover,
    .regs-pagination .pagination > .active > span:focus,
    .regs-pagination .pagination > .active > a,
    .regs-pagination .pagination > .active > a:hover,
    .regs-pagination .pagination > .active > a:focus {
        background: linear-gradient(135deg, var(--primary-color), #c49b48);
        border-color: transparent;
        color: #fff;
    }

    html[data-theme="dark"] .regs-hero {
        background:
            radial-gradient(circle at top left, rgba(141, 110, 43, 0.28), transparent 34%),
            linear-gradient(135deg, rgba(17, 24, 39, 0.98) 0%, rgba(15, 23, 42, 0.98) 48%, rgba(30, 41, 59, 0.98) 100%);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 24px 52px rgba(2, 6, 23, 0.34);
    }

    html[data-theme="dark"] .regs-secondary-btn,
    html[data-theme="dark"] .regs-stat-card,
    html[data-theme="dark"] .regs-toolbar,
    html[data-theme="dark"] .regs-list-card,
    html[data-theme="dark"] .reg-row,
    html[data-theme="dark"] .regs-empty,
    html[data-theme="dark"] .regs-meta-chip,
    html[data-theme="dark"] .regs-pagination .pagination > li > a,
    html[data-theme="dark"] .regs-pagination .pagination > li > span {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.14);
        color: var(--text-primary);
        box-shadow: 0 18px 38px rgba(2, 6, 23, 0.28);
    }

    html[data-theme="dark"] .regs-list-head,
    html[data-theme="dark"] .regs-pagination {
        background: rgba(15, 23, 42, 0.78);
        border-color: rgba(148, 163, 184, 0.12);
    }

    html[data-theme="dark"] .regs-search input {
        background: rgba(15, 23, 42, 0.95);
        border-color: rgba(148, 163, 184, 0.20);
        color: var(--text-primary);
    }

    html[data-theme="dark"] .regs-eyebrow,
    html[data-theme="dark"] .reg-badge {
        background: rgba(141, 110, 43, 0.16);
        color: #f6deb0;
    }

    @media (max-width: 1199px) {
        .regs-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .regs-list-head {
            display: none;
        }

        .reg-row {
            grid-template-columns: 1fr 1fr;
            align-items: flex-start;
        }

        .reg-main,
        .reg-actions {
            grid-column: 1 / -1;
        }

        .reg-actions {
            justify-content: flex-start;
        }
    }

    @media (max-width: 767px) {
        .regs-page {
            padding: 2px 0 18px;
        }

        .regs-hero {
            padding: 22px 18px;
            border-radius: 24px;
        }

        .regs-title {
            font-size: clamp(1.75rem, 7vw, 2rem);
        }

        .regs-subtitle {
            font-size: 1rem;
        }

        .regs-primary-btn,
        .regs-secondary-btn {
            width: 100%;
            justify-content: center;
        }

        .regs-stats {
            grid-template-columns: 1fr;
        }

        .regs-toolbar {
            padding: 16px;
        }

        .regs-toolbar-meta {
            width: 100%;
        }

        .regs-meta-chip {
            flex: 1 1 100%;
            justify-content: center;
        }

        .regs-list-body {
            padding: 8px;
        }

        .reg-row {
            grid-template-columns: 1fr;
            padding: 16px;
            margin: 8px 0 0;
        }

        .reg-main {
            align-items: flex-start;
        }

        .reg-avatar {
            width: 50px;
            height: 50px;
            border-radius: 16px;
        }

        .reg-action-btn {
            flex: 1 1 calc(50% - 8px);
            justify-content: center;
        }
    }

    @keyframes regsFadeUp {
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
<div class="regs-page">
    <div class="regs-shell">
        <section class="regs-hero">
            <div class="regs-hero-inner">
                <div>
                    <span class="regs-eyebrow">
                        <i class="bi bi-stars"></i>
                        {{ __('لوحة إدارة اللوائح') }}
                    </span>
                    <h1 class="regs-title">{{ __('اللوائح') }}</h1>
                    <p class="regs-subtitle">{{ __('تابع اللوائح والملفات التنظيمية من شاشة واحدة حديثة، مع بحث سريع وتنزيل مباشر وإدارة أسهل لكل ملف.') }}</p>
                </div>

                <div class="regs-hero-actions">
                    <a href="{{ route('regulations.create') }}" class="regs-primary-btn">
                        <i class="bi bi-journal-plus"></i>
                        {{ __('إضافة لائحة جديدة') }}
                    </a>
                    <a href="{{ route('dashboard') }}" class="regs-secondary-btn">
                        <i class="bi bi-grid-1x2-fill"></i>
                        {{ __('العودة إلى لوحة التحكم') }}
                    </a>
                </div>
            </div>
        </section>
        <section class="regs-toolbar">
            <div class="regs-search">
                <i class="bi bi-search"></i>
                <input
                    type="search"
                    id="regulationsSearch"
                    placeholder="{{ __('ابحث باسم اللائحة أو اسم الملف أو الامتداد...') }}"
                    autocomplete="off"
                >
            </div>

            <div class="regs-toolbar-meta">
                <span class="regs-meta-chip">
                    <i class="bi bi-eye-fill"></i>
                    <span id="visibleRegulationsCount">{{ $pageRegulationsCount }}</span>
                    {{ __('نتيجة ظاهرة') }}
                </span>
                <span class="regs-meta-chip">
                    <i class="bi bi-collection-fill"></i>
                    {{ __('صفحة') }} {{ $regulations->currentPage() }} / {{ $regulations->lastPage() }}
                </span>
            </div>
        </section>

        <section class="regs-list-card">
            @if($pageRegulationsCount > 0)
                <div class="regs-list-head">
                    <div>{{ __('اللائحة') }}</div>
                    <div>{{ __('الملف') }}</div>
                    <div>{{ __('الحجم والتاريخ') }}</div>
                    <div>{{ __('الامتداد') }}</div>
                    <div>{{ __('الإجراءات') }}</div>
                </div>

                <div class="regs-list-body" id="regulationsList">
                    @foreach($regulations as $regulation)
                        @php
                            $extension = strtoupper($regulation->file_extension ?: '-');
                            $searchableText = implode(' ', [
                                $regulation->name,
                                $regulation->original_filename,
                                $regulation->file_type,
                                $regulation->file_extension,
                            ]);
                        @endphp

                        <article class="reg-row reg-search-item"
                            data-search="{{ mb_strtolower($searchableText) }}"
                            style="animation-delay: {{ 0.05 + ($loop->index * 0.04) }}s;">
                            <div class="reg-main">
                                <div class="reg-avatar">
                                    <i class="fa {{ $regulation->file_icon }}"></i>
                                </div>

                                <div style="min-width: 0;">
                                    <h3 class="reg-name">{{ $regulation->name }}</h3>
                                    <div class="reg-meta-line">
                                        <span class="reg-badge">
                                            <i class="bi bi-hash"></i>
                                            #{{ $regulation->id }}
                                        </span>
                                        <span class="reg-badge">
                                            <i class="bi bi-file-earmark-text"></i>
                                            {{ \Illuminate\Support\Str::limit($regulation->original_filename, 26) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="reg-field">
                                <span class="reg-field-label">{{ __('اسم الملف') }}</span>
                                <span class="reg-field-value">{{ $regulation->original_filename }}</span>
                                <span class="reg-field-value muted">{{ $regulation->file_type ?: __('غير محدد') }}</span>
                            </div>

                            <div class="reg-field">
                                <span class="reg-field-label">{{ __('الحجم والتاريخ') }}</span>
                                <span class="reg-field-value">{{ $regulation->file_size_human }}</span>
                                <span class="reg-field-value muted">{{ $regulation->created_at->format('Y-m-d H:i') }}</span>
                            </div>

                            <div class="reg-field">
                                <span class="reg-field-label">{{ __('الامتداد') }}</span>
                                <span class="reg-field-value">{{ $extension }}</span>
                                <span class="reg-field-value muted">{{ $regulation->created_at->diffForHumans() }}</span>
                            </div>

                            <div class="reg-actions">
                                <a href="{{ route('regulations.show', $regulation) }}" class="reg-action-btn view" title="{{ __('تفاصيل') }}">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="{{ route('regulations.download', $regulation) }}" target="_blank" rel="noopener noreferrer" class="reg-action-btn download" title="{{ __('تحميل') }}">
                                    <i class="bi bi-download"></i>
                                </a>
                                <a href="{{ route('regulations.edit', $regulation) }}" class="reg-action-btn edit" title="{{ __('تعديل') }}">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('regulations.destroy', $regulation) }}" method="POST" style="display: inline-flex;" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذه اللائحة؟') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="reg-action-btn delete" title="{{ __('حذف') }}">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="regs-empty" id="regulationsEmptyState">
                    <div class="regs-empty-icon">
                        <i class="bi bi-search-heart"></i>
                    </div>
                    <h3>{{ __('لا توجد نتائج مطابقة') }}</h3>
                    <p>{{ __('جرّب البحث باسم مختلف أو جزء من اسم الملف أو الامتداد للوصول إلى اللائحة المطلوبة.') }}</p>
                </div>

                <div class="regs-pagination">
                    {{ $regulations->links() }}
                </div>
            @else
                <div class="regs-empty show">
                    <div class="regs-empty-icon">
                        <i class="bi bi-journal-x"></i>
                    </div>
                    <h3>{{ __('لا توجد لوائح بعد') }}</h3>
                    <p>{{ __('ابدأ بإضافة أول لائحة لتظهر الملفات التنظيمية هنا بشكل مرتب وحديث.') }}</p>
                    <div style="margin-top: 18px;">
                        <a href="{{ route('regulations.create') }}" class="regs-primary-btn">
                            <i class="bi bi-plus-circle-fill"></i>
                            {{ __('إضافة لائحة جديدة') }}
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
        const searchInput = document.getElementById('regulationsSearch');
        const rows = Array.from(document.querySelectorAll('.reg-search-item'));
        const visibleCount = document.getElementById('visibleRegulationsCount');
        const emptyState = document.getElementById('regulationsEmptyState');
        const pagination = document.querySelector('.regs-pagination');

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
