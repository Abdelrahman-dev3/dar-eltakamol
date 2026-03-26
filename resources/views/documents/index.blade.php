@extends('layouts.app')

@section('title', __('الملفات'))

@php
    $documentsCollection = $documents->getCollection();
    $pageDocumentsCount = $documentsCollection->count();
    $pageFilesSize = $documentsCollection->sum(fn ($document) => (float) ($document->file_size ?? 0));
@endphp

@push('styles')
<style>
    .docs-page {
        padding: 8px 0 28px;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .docs-page button,
    .docs-page input {
        font: inherit;
    }

    .docs-shell {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .docs-hero {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        padding: 30px;
        background:
            radial-gradient(circle at top left, rgba(196, 168, 90, 0.32), transparent 34%),
            linear-gradient(135deg, #fffaf0 0%, #ffffff 48%, #f5efe2 100%);
        border: 1px solid rgba(170, 134, 63, 0.16);
        box-shadow: 0 18px 48px rgba(15, 23, 42, 0.08);
        animation: docsFadeUp 0.7s ease both;
    }

    .docs-hero::before,
    .docs-hero::after {
        content: "";
        position: absolute;
        border-radius: 999px;
        pointer-events: none;
    }

    .docs-hero::before {
        width: 210px;
        height: 210px;
        top: -100px;
        inset-inline-end: -80px;
        background: rgba(170, 134, 63, 0.10);
    }

    .docs-hero::after {
        width: 150px;
        height: 150px;
        bottom: -80px;
        inset-inline-start: -40px;
        background: rgba(196, 168, 90, 0.14);
    }

    .docs-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
    }

    .docs-eyebrow {
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

    .docs-title {
        margin: 0;
        font-size: clamp(2rem, 3vw, 2.6rem);
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .docs-subtitle {
        margin: 10px 0 0;
        max-width: 760px;
        color: var(--text-secondary);
        font-size: 1.12rem;
        line-height: 1.8;
    }

    .docs-hero-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .docs-primary-btn,
    .docs-secondary-btn {
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

    .docs-primary-btn {
        background: linear-gradient(135deg, var(--primary-color), #c49b48);
        color: #fff;
        box-shadow: 0 18px 28px rgba(170, 134, 63, 0.24);
    }

    .docs-secondary-btn {
        background: rgba(255, 255, 255, 0.88);
        color: var(--text-primary);
        border: 1px solid rgba(170, 134, 63, 0.16);
    }

    .docs-primary-btn:hover,
    .docs-secondary-btn:hover {
        transform: translateY(-2px);
    }

    .docs-stat-icon {
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

    .docs-stat-value {
        margin: 0;
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-primary);
    }

    .docs-stat-label {
        margin: 6px 0 0;
        color: var(--text-secondary);
        font-size: 1.02rem;
        font-weight: 600;
    }

    .docs-toolbar {
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
        animation: docsFadeUp 0.82s ease both;
    }

    .docs-search {
        position: relative;
        flex: 1 1 340px;
    }

    .docs-search i {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        inset-inline-start: 16px;
        color: var(--text-light);
        font-size: 1.1rem;
    }

    .docs-search input {
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

    .docs-search input:focus {
        outline: none;
        border-color: rgba(170, 134, 63, 0.48);
        box-shadow: 0 0 0 4px rgba(170, 134, 63, 0.10);
    }

    .docs-toolbar-meta {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .docs-meta-chip {
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

    .docs-list-card {
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 16px 44px rgba(15, 23, 42, 0.07);
        overflow: hidden;
        animation: docsFadeUp 0.92s ease both;
    }

    .docs-list-head,
    .doc-row {
        display: grid;
        grid-template-columns: minmax(250px, 1.8fr) minmax(200px, 1.2fr) minmax(180px, 1fr) minmax(180px, 1fr) minmax(240px, 1.2fr);
        align-items: center;
        gap: 16px;
        padding: 18px 22px;
    }

    .docs-list-head {
        background: linear-gradient(180deg, #fff9ef 0%, #fcf7ee 100%);
        color: var(--text-secondary);
        font-size: 0.98rem;
        font-weight: 800;
        border-bottom: 1px solid rgba(170, 134, 63, 0.12);
    }

    .docs-list-body {
        padding: 8px 10px 12px;
    }

    .doc-row {
        margin: 10px 10px 0;
        border-radius: 22px;
        background: #fff;
        border: 1px solid rgba(226, 232, 240, 0.9);
        transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
        animation: docsFadeUp 0.7s ease both;
    }

    .doc-row:hover {
        transform: translateY(-3px);
        border-color: rgba(170, 134, 63, 0.22);
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.08);
    }

    .doc-row.is-hidden {
        display: none;
    }

    .doc-main {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
    }

    .doc-avatar {
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

    .doc-name {
        margin: 0;
        color: var(--text-primary);
        font-size: 1.16rem;
        font-weight: 800;
        line-height: 1.35;
    }

    .doc-meta-line {
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        color: var(--text-secondary);
        font-size: 0.96rem;
    }

    .doc-badge {
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

    .doc-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-width: 0;
    }

    .doc-field-label {
        color: var(--text-light);
        font-size: 0.86rem;
        font-weight: 700;
    }

    .doc-field-value {
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.5;
        word-break: break-word;
    }

    .doc-field-value.muted {
        color: var(--text-secondary);
        font-weight: 600;
    }

    .doc-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .doc-action-btn {
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

    .doc-action-btn:hover {
        transform: translateY(-2px);
        opacity: 0.96;
    }

    .doc-action-btn.view { background: rgba(14, 165, 233, 0.12); color: #0284c7; }
    .doc-action-btn.download { background: rgba(5, 150, 105, 0.12); color: var(--success-color); }
    .doc-action-btn.edit { background: rgba(217, 119, 6, 0.12); color: #b45309; }
    .doc-action-btn.delete { background: rgba(220, 38, 38, 0.10); color: var(--danger-color); }

    .docs-empty {
        display: none;
        padding: 48px 24px 54px;
        text-align: center;
    }

    .docs-empty.show {
        display: block;
        animation: docsFadeUp 0.4s ease both;
    }

    .docs-empty-icon {
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

    .docs-empty h3 {
        margin: 0 0 8px;
        color: var(--text-primary);
        font-size: 1.7rem;
        font-weight: 800;
    }

    .docs-empty p {
        margin: 0;
        color: var(--text-secondary);
        font-size: 1.02rem;
    }

    .docs-pagination {
        padding: 22px;
        border-top: 1px solid rgba(170, 134, 63, 0.10);
        background: linear-gradient(180deg, rgba(255, 249, 239, 0.35) 0%, rgba(255, 255, 255, 0.92) 100%);
    }

    .docs-pagination .pagination {
        margin: 0;
    }

    .docs-pagination .pagination > li > a,
    .docs-pagination .pagination > li > span {
        border-radius: 12px !important;
        margin: 0 4px;
        border: 1px solid rgba(170, 134, 63, 0.14);
        color: var(--text-primary);
        min-width: 42px;
        text-align: center;
    }

    .docs-pagination .pagination > .active > span,
    .docs-pagination .pagination > .active > span:hover,
    .docs-pagination .pagination > .active > span:focus,
    .docs-pagination .pagination > .active > a,
    .docs-pagination .pagination > .active > a:hover,
    .docs-pagination .pagination > .active > a:focus {
        background: linear-gradient(135deg, var(--primary-color), #c49b48);
        border-color: transparent;
        color: #fff;
    }

    html[data-theme="dark"] .docs-hero {
        background:
            radial-gradient(circle at top left, rgba(141, 110, 43, 0.28), transparent 34%),
            linear-gradient(135deg, rgba(17, 24, 39, 0.98) 0%, rgba(15, 23, 42, 0.98) 48%, rgba(30, 41, 59, 0.98) 100%);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 24px 52px rgba(2, 6, 23, 0.34);
    }

    html[data-theme="dark"] .docs-secondary-btn,
    html[data-theme="dark"] .docs-stat-card,
    html[data-theme="dark"] .docs-toolbar,
    html[data-theme="dark"] .docs-list-card,
    html[data-theme="dark"] .doc-row,
    html[data-theme="dark"] .docs-empty,
    html[data-theme="dark"] .docs-meta-chip,
    html[data-theme="dark"] .docs-pagination .pagination > li > a,
    html[data-theme="dark"] .docs-pagination .pagination > li > span {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.14);
        color: var(--text-primary);
        box-shadow: 0 18px 38px rgba(2, 6, 23, 0.28);
    }

    html[data-theme="dark"] .docs-list-head,
    html[data-theme="dark"] .docs-pagination {
        background: rgba(15, 23, 42, 0.78);
        border-color: rgba(148, 163, 184, 0.12);
    }

    html[data-theme="dark"] .docs-search input {
        background: rgba(15, 23, 42, 0.95);
        border-color: rgba(148, 163, 184, 0.20);
        color: var(--text-primary);
    }

    html[data-theme="dark"] .docs-eyebrow,
    html[data-theme="dark"] .doc-badge {
        background: rgba(141, 110, 43, 0.16);
        color: #f6deb0;
    }

    @media (max-width: 1199px) {

        .docs-list-head {
            display: none;
        }

        .doc-row {
            grid-template-columns: 1fr 1fr;
            align-items: flex-start;
        }

        .doc-main,
        .doc-actions {
            grid-column: 1 / -1;
        }

        .doc-actions {
            justify-content: flex-start;
        }
    }

    @media (max-width: 767px) {
        .docs-page {
            padding: 2px 0 18px;
        }

        .docs-hero {
            padding: 22px 18px;
            border-radius: 24px;
        }

        .docs-title {
            font-size: clamp(1.75rem, 7vw, 2rem);
        }

        .docs-subtitle {
            font-size: 1rem;
        }

        .docs-primary-btn,
        .docs-secondary-btn {
            width: 100%;
            justify-content: center;
        }

        .docs-stats {
            grid-template-columns: 1fr;
        }

        .docs-toolbar {
            padding: 16px;
        }

        .docs-toolbar-meta {
            width: 100%;
        }

        .docs-meta-chip {
            flex: 1 1 100%;
            justify-content: center;
        }

        .docs-list-body {
            padding: 8px;
        }

        .doc-row {
            grid-template-columns: 1fr;
            padding: 16px;
            margin: 8px 0 0;
        }

        .doc-main {
            align-items: flex-start;
        }

        .doc-avatar {
            width: 50px;
            height: 50px;
            border-radius: 16px;
        }

        .doc-action-btn {
            flex: 1 1 calc(50% - 8px);
            justify-content: center;
        }
    }

    @keyframes docsFadeUp {
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
<div class="docs-page">
    <div class="docs-shell">
        <section class="docs-hero">
            <div class="docs-hero-inner">
                <div>
                    <span class="docs-eyebrow">
                        <i class="bi bi-stars"></i>
                        {{ __('لوحة إدارة الملفات') }}
                    </span>
                    <h1 class="docs-title">{{ __('الملفات') }}</h1>
                </div>

                <div class="docs-hero-actions">
                    <a href="{{ route('documents.create') }}" class="docs-primary-btn">
                        <i class="bi bi-folder-plus"></i>
                        {{ __('إضافة ملف جديد') }}
                    </a>
                    <a href="{{ route('dashboard') }}" class="docs-secondary-btn">
                        <i class="bi bi-grid-1x2-fill"></i>
                        {{ __('العودة إلى لوحة التحكم') }}
                    </a>
                </div>
            </div>
        </section>
        <section class="docs-toolbar">
            <div class="docs-search">
                <i class="bi bi-search"></i>
                <input
                    type="search"
                    id="documentsSearch"
                    placeholder="{{ __('ابحث باسم الملف أو الاسم الأصلي أو الامتداد أو الاجتماع...') }}"
                    autocomplete="off"
                >
            </div>

            <div class="docs-toolbar-meta">
                <span class="docs-meta-chip">
                    <i class="bi bi-eye-fill"></i>
                    <span id="visibleDocumentsCount">{{ $pageDocumentsCount }}</span>
                    {{ __('نتيجة ظاهرة') }}
                </span>
                <span class="docs-meta-chip">
                    <i class="bi bi-collection-fill"></i>
                    {{ __('صفحة') }} {{ $documents->currentPage() }} / {{ $documents->lastPage() }}
                </span>
            </div>
        </section>

        <section class="docs-list-card">
            @if($pageDocumentsCount > 0)
                <div class="docs-list-head">
                    <div>{{ __('الملف') }}</div>
                    <div>{{ __('الاسم الأصلي') }}</div>
                    <div>{{ __('الحجم والتاريخ') }}</div>
                    <div>{{ __('الاجتماع المرتبط') }}</div>
                    <div>{{ __('الإجراءات') }}</div>
                </div>

                <div class="docs-list-body" id="documentsList">
                    @foreach($documents as $document)
                        @php
                            $meetingName = optional($document->meeting)->name ?: __('بدون اجتماع');
                            $searchableText = implode(' ', [
                                $document->name,
                                $document->original_filename,
                                $document->file_type,
                                $document->file_extension,
                                $meetingName,
                            ]);
                        @endphp

                        <article class="doc-row doc-search-item"
                            data-search="{{ mb_strtolower($searchableText) }}"
                            style="animation-delay: {{ 0.05 + ($loop->index * 0.04) }}s;">
                            <div class="doc-main">
                                <div class="doc-avatar">
                                    <i class="fa {{ $document->file_icon }}"></i>
                                </div>

                                <div style="min-width: 0;">
                                    <h3 class="doc-name">{{ $document->name }}</h3>
                                    <div class="doc-meta-line">
                                        <span class="doc-badge">
                                            <i class="bi bi-hash"></i>
                                            #{{ $document->id }}
                                        </span>
                                        <span class="doc-badge">
                                            <i class="bi bi-file-earmark-text"></i>
                                            {{ strtoupper($document->file_extension ?: '-') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="doc-field">
                                <span class="doc-field-label">{{ __('الاسم الأصلي') }}</span>
                                <span class="doc-field-value">{{ $document->original_filename }}</span>
                                <span class="doc-field-value muted">{{ $document->file_type ?: __('غير محدد') }}</span>
                            </div>

                            <div class="doc-field">
                                <span class="doc-field-label">{{ __('الحجم والتاريخ') }}</span>
                                <span class="doc-field-value">{{ $document->file_size_human }}</span>
                                <span class="doc-field-value muted">{{ $document->created_at->format('Y-m-d H:i') }}</span>
                            </div>

                            <div class="doc-field">
                                <span class="doc-field-label">{{ __('الاجتماع المرتبط') }}</span>
                                <span class="doc-field-value">{{ $meetingName }}</span>
                                <span class="doc-field-value muted">
                                    {{ optional($document->meeting?->date)->format('Y-m-d') ?: __('بدون تاريخ') }}
                                </span>
                            </div>

                            <div class="doc-actions">
                                <a href="{{ route('documents.show', $document) }}" class="doc-action-btn view" title="{{ __('تفاصيل') }}">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="{{ route('documents.download', $document) }}" target="_blank" rel="noopener noreferrer" class="doc-action-btn download" title="{{ __('تحميل') }}">
                                    <i class="bi bi-download"></i>
                                </a>
                                <a href="{{ route('documents.edit', $document) }}" class="doc-action-btn edit" title="{{ __('تعديل') }}">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('documents.destroy', $document) }}" method="POST" style="display: inline-flex;" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا الملف؟') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="doc-action-btn delete" title="{{ __('حذف') }}">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="docs-empty" id="documentsEmptyState">
                    <div class="docs-empty-icon">
                        <i class="bi bi-search-heart"></i>
                    </div>
                    <h3>{{ __('لا توجد نتائج مطابقة') }}</h3>
                    <p>{{ __('جرّب البحث باسم مختلف أو جزء من الاسم الأصلي أو الامتداد أو اسم الاجتماع للوصول إلى الملف المطلوب.') }}</p>
                </div>

                <div class="docs-pagination">
                    {{ $documents->links() }}
                </div>
            @else
                <div class="docs-empty show">
                    <div class="docs-empty-icon">
                        <i class="bi bi-folder-x"></i>
                    </div>
                    <h3>{{ __('لا توجد ملفات بعد') }}</h3>
                    <p>{{ __('ابدأ بإضافة أول ملف ليظهر هنا داخل قائمة منظمة وحديثة تدعم البحث والوصول السريع.') }}</p>
                    <div style="margin-top: 18px;">
                        <a href="{{ route('documents.create') }}" class="docs-primary-btn">
                            <i class="bi bi-plus-circle-fill"></i>
                            {{ __('إضافة ملف جديد') }}
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
        const searchInput = document.getElementById('documentsSearch');
        const rows = Array.from(document.querySelectorAll('.doc-search-item'));
        const visibleCount = document.getElementById('visibleDocumentsCount');
        const emptyState = document.getElementById('documentsEmptyState');
        const pagination = document.querySelector('.docs-pagination');

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
