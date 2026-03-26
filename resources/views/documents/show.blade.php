@extends('layouts.app')

@php
    $extension = strtoupper($document->file_extension ?: '-');
    $fileType = $document->file_type ?: __('غير محدد');
    $uploadedAt = $document->created_at;
    $updatedAt = $document->updated_at;
    $meetingName = optional($document->meeting)->name;
    $meetingDate = optional($document->meeting?->date)->format('Y-m-d');
@endphp

@section('title', __('عرض تفاصيل الملف'))

@push('styles')
<style>
    .doc-show-page {
        padding: 10px 0 30px;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .doc-show-page button,
    .doc-show-page a {
        font: inherit;
    }

    .doc-show-shell {
        display: flex;
        flex-direction: column;
        gap: 22px;
    }

    .doc-show-hero,
    .doc-show-card,
    .doc-show-stat-card {
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.07);
    }

    .doc-show-hero {
        padding: 28px;
        background:
            radial-gradient(circle at top right, rgba(196, 168, 90, 0.28), transparent 30%),
            linear-gradient(135deg, #fff8ed 0%, #ffffff 44%, #f5ecde 100%);
    }

    .doc-show-hero-inner {
        display: grid;
        grid-template-columns: minmax(0, 1.6fr) minmax(280px, 0.9fr);
        gap: 20px;
        align-items: center;
    }

    .doc-show-profile {
        display: flex;
        align-items: center;
        gap: 18px;
        flex-wrap: wrap;
    }

    .doc-show-avatar {
        width: 108px;
        height: 108px;
        border-radius: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary-color), #d4b066);
        color: #fff;
        font-size: 2.4rem;
        box-shadow: 0 20px 36px rgba(170, 134, 63, 0.22);
        flex-shrink: 0;
    }

    .doc-show-badge,
    .doc-show-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-radius: 999px;
        font-weight: 800;
    }

    .doc-show-badge {
        margin-bottom: 14px;
        padding: 8px 14px;
        background: rgba(170, 134, 63, 0.12);
        color: var(--primary-color);
    }

    .doc-show-title {
        margin: 0;
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 900;
        color: var(--text-primary);
    }

    .doc-show-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 16px;
    }

    .doc-show-chip {
        padding: 10px 14px;
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(170, 134, 63, 0.12);
        color: var(--text-primary);
    }

    .doc-show-actions {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .doc-show-btn,
    .doc-show-btn-muted,
    .doc-show-btn-danger {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        padding: 13px 18px;
        border-radius: 18px;
        text-decoration: none !important;
        border: 1px solid transparent;
        font-size: 1rem;
        font-weight: 800;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .doc-show-btn {
        background: linear-gradient(135deg, var(--primary-color), #c49b48);
        color: #fff !important;
        box-shadow: 0 16px 28px rgba(170, 134, 63, 0.24);
    }

    .doc-show-btn-muted {
        background: rgba(255, 255, 255, 0.9);
        color: var(--text-primary) !important;
        border-color: rgba(170, 134, 63, 0.14);
    }

    .doc-show-btn-danger {
        background: rgba(220, 38, 38, 0.08);
        color: var(--danger-color) !important;
        border-color: rgba(220, 38, 38, 0.16);
    }

    .doc-show-btn:hover,
    .doc-show-btn-muted:hover,
    .doc-show-btn-danger:hover {
        transform: translateY(-2px);
    }

    .doc-show-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }

    .doc-show-stat-card {
        padding: 20px;
    }

    .doc-show-stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(170, 134, 63, 0.12);
        color: var(--primary-color);
        font-size: 1.3rem;
        margin-bottom: 14px;
    }

    .doc-show-stat-value {
        margin: 0;
        font-size: 1.9rem;
        font-weight: 900;
        color: var(--text-primary);
        word-break: break-word;
    }

    .doc-show-stat-label {
        margin: 6px 0 0;
        color: var(--text-secondary);
        font-weight: 700;
    }

    .doc-show-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .doc-show-card {
        padding: 24px;
    }

    .doc-show-card.full-width {
        grid-column: 1 / -1;
    }

    .doc-show-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .doc-show-card-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0;
        font-size: 1.3rem;
        font-weight: 900;
        color: var(--text-primary);
    }

    .doc-show-card-title i {
        width: 46px;
        height: 46px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(170, 134, 63, 0.12);
        color: var(--primary-color);
    }

    .doc-show-card-note {
        color: var(--text-secondary);
        font-size: 0.95rem;
        line-height: 1.8;
    }

    .doc-detail-list {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .doc-detail-item {
        padding: 16px;
        border-radius: 20px;
        background: rgba(248, 250, 252, 0.92);
        border: 1px solid rgba(170, 134, 63, 0.10);
    }

    .doc-detail-label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-secondary);
        font-size: 0.92rem;
        font-weight: 700;
    }

    .doc-detail-value {
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 800;
        word-break: break-word;
    }

    .doc-detail-code {
        display: inline-flex;
        padding: 6px 10px;
        border-radius: 12px;
        background: rgba(170, 134, 63, 0.10);
        color: var(--primary-color);
        word-break: break-all;
    }

    .doc-file-card {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
        padding: 20px;
        border-radius: 24px;
        background: rgba(248, 250, 252, 0.94);
        border: 1px solid rgba(170, 134, 63, 0.10);
    }

    .doc-file-card-main {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        min-width: 0;
    }

    .doc-file-card-icon {
        width: 56px;
        height: 56px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(170, 134, 63, 0.12);
        color: var(--primary-color);
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .doc-file-card-name {
        margin: 0;
        color: var(--text-primary);
        font-size: 1.08rem;
        font-weight: 900;
        word-break: break-word;
    }

    .doc-file-card-meta,
    .doc-file-card-desc {
        color: var(--text-secondary);
        line-height: 1.8;
        font-size: 0.96rem;
    }

    .doc-file-card-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        flex-shrink: 0;
    }

    .doc-file-card-actions a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 14px;
        border: 1px solid transparent;
        text-decoration: none !important;
        font-weight: 800;
        background: rgba(170, 134, 63, 0.10);
        color: var(--primary-color);
    }

    html[data-theme="dark"] .doc-show-hero {
        background:
            radial-gradient(circle at top right, rgba(141, 110, 43, 0.28), transparent 30%),
            linear-gradient(135deg, rgba(17, 24, 39, 0.98) 0%, rgba(15, 23, 42, 0.98) 44%, rgba(30, 41, 59, 0.98) 100%);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 24px 52px rgba(2, 6, 23, 0.34);
    }

    html[data-theme="dark"] .doc-show-card,
    html[data-theme="dark"] .doc-show-stat-card,
    html[data-theme="dark"] .doc-file-card {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 18px 38px rgba(2, 6, 23, 0.28);
    }

    html[data-theme="dark"] .doc-detail-item,
    html[data-theme="dark"] .doc-show-chip,
    html[data-theme="dark"] .doc-detail-code {
        background: rgba(15, 23, 42, 0.78);
        border-color: rgba(148, 163, 184, 0.12);
    }

    html[data-theme="dark"] .doc-show-badge {
        background: rgba(141, 110, 43, 0.16);
        color: #f6deb0;
    }

    html[data-theme="dark"] .doc-show-btn-muted {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.14);
        color: var(--text-primary) !important;
    }

    html[data-theme="dark"] .doc-show-btn-muted:hover,
    html[data-theme="dark"] .doc-file-card-actions a:hover {
        color: #f6deb0 !important;
        border-color: rgba(141, 110, 43, 0.26);
    }

    @media (max-width: 1399px) {
        .doc-show-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 1199px) {
        .doc-show-hero-inner,
        .doc-show-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .doc-show-stats,
        .doc-detail-list {
            grid-template-columns: 1fr;
        }

        .doc-show-hero,
        .doc-show-card,
        .doc-show-stat-card {
            padding: 20px;
            border-radius: 24px;
        }

        .doc-file-card {
            flex-direction: column;
        }

        .doc-file-card-actions {
            width: 100%;
        }

        .doc-file-card-actions a {
            flex: 1 1 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid doc-show-page">
    <div class="doc-show-shell">
        <section class="doc-show-hero">
            <div class="doc-show-hero-inner">
                <div>
                    <div class="doc-show-profile">
                        <div class="doc-show-avatar">
                            <i class="fa {{ $document->file_icon }}"></i>
                        </div>

                        <div>
                            <span class="doc-show-badge">
                                <i class="bi bi-folder2-open"></i>
                                {{ __('ملف') }} #{{ $document->id }}
                            </span>
                            <h1 class="doc-show-title">{{ $document->name }}</h1>
                            <div class="doc-show-meta">
                                <span class="doc-show-chip"><i class="bi bi-file-earmark-text"></i>{{ $extension }}</span>
                                <span class="doc-show-chip"><i class="bi bi-hdd"></i>{{ $document->file_size_human }}</span>
                                <span class="doc-show-chip"><i class="bi bi-calendar-check"></i>{{ $uploadedAt->format('Y-m-d H:i') }}</span>
                                <span class="doc-show-chip"><i class="bi bi-people"></i>{{ $meetingName ?: __('بدون اجتماع') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="doc-show-actions">
                    <a href="{{ route('documents.edit', $document) }}" class="doc-show-btn">
                        <i class="bi bi-pencil-square"></i>
                        {{ __('تعديل الملف') }}
                    </a>
                    <a href="{{ route('documents.download', $document) }}" target="_blank" rel="noopener noreferrer" class="doc-show-btn-muted">
                        <i class="bi bi-download"></i>
                        {{ __('تحميل الملف') }}
                    </a>
                    <a href="{{ route('documents.index') }}" class="doc-show-btn-muted">
                        <i class="bi bi-arrow-right-circle"></i>
                        {{ __('العودة للقائمة') }}
                    </a>
                    <form action="{{ route('documents.destroy', $document) }}" method="POST" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا الملف؟ سيتم حذف الملف نهائيًا.') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="doc-show-btn-danger">
                            <i class="bi bi-trash3"></i>
                            {{ __('حذف الملف') }}
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <section class="doc-show-stats">
            <div class="doc-show-stat-card">
                <span class="doc-show-stat-icon"><i class="bi bi-filetype-txt"></i></span>
                <p class="doc-show-stat-value">{{ $extension }}</p>
                <p class="doc-show-stat-label">{{ __('امتداد الملف') }}</p>
            </div>
            <div class="doc-show-stat-card">
                <span class="doc-show-stat-icon"><i class="bi bi-hdd-stack"></i></span>
                <p class="doc-show-stat-value">{{ $document->file_size_human }}</p>
                <p class="doc-show-stat-label">{{ __('حجم الملف') }}</p>
            </div>
            <div class="doc-show-stat-card">
                <span class="doc-show-stat-icon"><i class="bi bi-calendar2-plus"></i></span>
                <p class="doc-show-stat-value">{{ $uploadedAt->diffForHumans() }}</p>
                <p class="doc-show-stat-label">{{ __('وقت الإضافة') }}</p>
            </div>
            <div class="doc-show-stat-card">
                <span class="doc-show-stat-icon"><i class="bi bi-people-fill"></i></span>
                <p class="doc-show-stat-value">{{ $meetingName ?: __('لا يوجد') }}</p>
                <p class="doc-show-stat-label">{{ __('الاجتماع المرتبط') }}</p>
            </div>
        </section>

        <div class="doc-show-grid">
            <section class="doc-show-card">
                <div class="doc-show-card-head">
                    <h2 class="doc-show-card-title"><i class="bi bi-info-circle"></i>{{ __('المعلومات الأساسية') }}</h2>
                    <span class="doc-show-card-note">{{ __('ملخص سريع لبيانات الملف الأساسية كما تظهر داخل النظام.') }}</span>
                </div>

                <div class="doc-detail-list">
                    <div class="doc-detail-item">
                        <span class="doc-detail-label">{{ __('رقم الملف') }}</span>
                        <div class="doc-detail-value">#{{ $document->id }}</div>
                    </div>
                    <div class="doc-detail-item">
                        <span class="doc-detail-label">{{ __('اسم الملف') }}</span>
                        <div class="doc-detail-value">{{ $document->name }}</div>
                    </div>
                    <div class="doc-detail-item">
                        <span class="doc-detail-label">{{ __('الاسم الأصلي') }}</span>
                        <div class="doc-detail-value">{{ $document->original_filename }}</div>
                    </div>
                    <div class="doc-detail-item">
                        <span class="doc-detail-label">{{ __('نوع الملف') }}</span>
                        <div class="doc-detail-value">{{ $fileType }}</div>
                    </div>
                    <div class="doc-detail-item">
                        <span class="doc-detail-label">{{ __('تاريخ الإضافة') }}</span>
                        <div class="doc-detail-value">{{ $uploadedAt->format('Y-m-d H:i:s') }}</div>
                    </div>
                    <div class="doc-detail-item">
                        <span class="doc-detail-label">{{ __('آخر تحديث') }}</span>
                        <div class="doc-detail-value">{{ $updatedAt->format('Y-m-d H:i:s') }}</div>
                    </div>
                </div>
            </section>

            <section class="doc-show-card">
                <div class="doc-show-card-head">
                    <h2 class="doc-show-card-title"><i class="bi bi-link-45deg"></i>{{ __('الربط والوصول') }}</h2>
                    <span class="doc-show-card-note">{{ __('معلومات الربط الحالية ومسار التخزين للوصول الإداري والتنظيم الأفضل.') }}</span>
                </div>

                <div class="doc-detail-list">
                    <div class="doc-detail-item">
                        <span class="doc-detail-label">{{ __('الاجتماع المرتبط') }}</span>
                        <div class="doc-detail-value">{{ $meetingName ?: __('بدون اجتماع') }}</div>
                    </div>
                    <div class="doc-detail-item">
                        <span class="doc-detail-label">{{ __('تاريخ الاجتماع') }}</span>
                        <div class="doc-detail-value">{{ $meetingDate ?: __('غير متوفر') }}</div>
                    </div>
                    <div class="doc-detail-item">
                        <span class="doc-detail-label">{{ __('الامتداد') }}</span>
                        <div class="doc-detail-value">{{ $extension }}</div>
                    </div>
                    <div class="doc-detail-item">
                        <span class="doc-detail-label">{{ __('الحجم') }}</span>
                        <div class="doc-detail-value">{{ $document->file_size_human }}</div>
                    </div>
                    <div class="doc-detail-item" style="grid-column: 1 / -1;">
                        <span class="doc-detail-label">{{ __('المسار التخزيني') }}</span>
                        <div class="doc-detail-value">
                            <span class="doc-detail-code">{{ $document->file_path }}</span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="doc-show-card full-width">
                <div class="doc-show-card-head">
                    <h2 class="doc-show-card-title"><i class="bi bi-file-earmark-arrow-down"></i>{{ __('بطاقة الملف') }}</h2>
                    <span class="doc-show-card-note">{{ __('بطاقة مركزة تعرض الملف الحالي مع أهم التفاصيل وخيارات الوصول السريع.') }}</span>
                </div>

                <article class="doc-file-card">
                    <div class="doc-file-card-main">
                        <span class="doc-file-card-icon">
                            <i class="fa {{ $document->file_icon }}"></i>
                        </span>
                        <div>
                            <h3 class="doc-file-card-name">{{ $document->original_filename }}</h3>
                            <p class="doc-file-card-meta">
                                {{ __('الاسم المعروض') }}: {{ $document->name }}<br>
                                {{ __('نوع الملف') }}: {{ $fileType }}<br>
                                {{ __('الامتداد') }}: {{ $extension }}<br>
                                {{ __('الحجم') }}: {{ $document->file_size_human }}<br>
                                {{ __('الاجتماع') }}: {{ $meetingName ?: __('بدون اجتماع') }}
                            </p>
                            <p class="doc-file-card-desc">{{ __('يمكنك تنزيل الملف مباشرة أو الانتقال إلى صفحة التعديل لتحديث الاسم أو استبدال الملف أو تعديل الارتباط بالاجتماع.') }}</p>
                        </div>
                    </div>

                    <div class="doc-file-card-actions">
                        <a href="{{ route('documents.download', $document) }}" target="_blank" rel="noopener noreferrer">
                            <i class="bi bi-download"></i>
                            {{ __('تنزيل الملف') }}
                        </a>
                        <a href="{{ route('documents.edit', $document) }}">
                            <i class="bi bi-pencil-square"></i>
                            {{ __('تعديل الملف') }}
                        </a>
                    </div>
                </article>
            </section>
        </div>
    </div>
</div>
@endsection
