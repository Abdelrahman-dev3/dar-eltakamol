@extends('layouts.app')

@php
    $extension = strtoupper($circular->file_extension ?: '-');
    $fileType = $circular->file_type ?: __('غير محدد');
    $uploadedAt = $circular->created_at;
    $updatedAt = $circular->updated_at;
    $recipientsCount = $circular->recipients->count();
    $recipientNames = $circular->recipients->pluck('name')->filter()->values();
@endphp

@section('title', __('عرض تفاصيل التعميم'))

@push('styles')
<style>
    .cir-show-page {
        padding: 10px 0 30px;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .cir-show-page button,
    .cir-show-page a {
        font: inherit;
    }

    .cir-show-shell {
        display: flex;
        flex-direction: column;
        gap: 22px;
    }

    .cir-show-hero,
    .cir-show-card,
    .cir-show-stat-card {
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.07);
    }

    .cir-show-hero {
        padding: 28px;
        background:
            radial-gradient(circle at top right, rgba(196, 168, 90, 0.28), transparent 30%),
            linear-gradient(135deg, #fff8ed 0%, #ffffff 44%, #f5ecde 100%);
    }

    .cir-show-hero-inner {
        display: grid;
        grid-template-columns: minmax(0, 1.6fr) minmax(280px, 0.9fr);
        gap: 20px;
        align-items: center;
    }

    .cir-show-profile {
        display: flex;
        align-items: center;
        gap: 18px;
        flex-wrap: wrap;
    }

    .cir-show-avatar {
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

    .cir-show-badge,
    .cir-show-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-radius: 999px;
        font-weight: 800;
    }

    .cir-show-badge {
        margin-bottom: 14px;
        padding: 8px 14px;
        background: rgba(170, 134, 63, 0.12);
        color: var(--primary-color);
    }

    .cir-show-title {
        margin: 0;
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 900;
        color: var(--text-primary);
    }

    .cir-show-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 16px;
    }

    .cir-show-chip {
        padding: 10px 14px;
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(170, 134, 63, 0.12);
        color: var(--text-primary);
    }

    .cir-show-actions {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .cir-show-btn,
    .cir-show-btn-muted,
    .cir-show-btn-danger {
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

    .cir-show-btn {
        background: linear-gradient(135deg, var(--primary-color), #c49b48);
        color: #fff !important;
        box-shadow: 0 16px 28px rgba(170, 134, 63, 0.24);
    }

    .cir-show-btn-muted {
        background: rgba(255, 255, 255, 0.9);
        color: var(--text-primary) !important;
        border-color: rgba(170, 134, 63, 0.14);
    }

    .cir-show-btn-danger {
        background: rgba(220, 38, 38, 0.08);
        color: var(--danger-color) !important;
        border-color: rgba(220, 38, 38, 0.16);
    }

    .cir-show-btn:hover,
    .cir-show-btn-muted:hover,
    .cir-show-btn-danger:hover {
        transform: translateY(-2px);
    }

    .cir-show-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }

    .cir-show-stat-card {
        padding: 20px;
    }

    .cir-show-stat-icon {
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

    .cir-show-stat-value {
        margin: 0;
        font-size: 1.9rem;
        font-weight: 900;
        color: var(--text-primary);
        word-break: break-word;
    }

    .cir-show-stat-label {
        margin: 6px 0 0;
        color: var(--text-secondary);
        font-weight: 700;
    }

    .cir-show-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .cir-show-card {
        padding: 24px;
    }

    .cir-show-card.full-width {
        grid-column: 1 / -1;
    }

    .cir-show-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .cir-show-card-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0;
        font-size: 1.3rem;
        font-weight: 900;
        color: var(--text-primary);
    }

    .cir-show-card-title i {
        width: 46px;
        height: 46px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(170, 134, 63, 0.12);
        color: var(--primary-color);
    }

    .cir-show-card-note {
        color: var(--text-secondary);
        font-size: 0.95rem;
        line-height: 1.8;
    }

    .cir-detail-list {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .cir-detail-item {
        padding: 16px;
        border-radius: 20px;
        background: rgba(248, 250, 252, 0.92);
        border: 1px solid rgba(170, 134, 63, 0.10);
    }

    .cir-detail-label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-secondary);
        font-size: 0.92rem;
        font-weight: 700;
    }

    .cir-detail-value {
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 800;
        word-break: break-word;
    }

    .cir-detail-code {
        display: inline-flex;
        padding: 6px 10px;
        border-radius: 12px;
        background: rgba(170, 134, 63, 0.10);
        color: var(--primary-color);
        word-break: break-all;
    }

    .cir-file-card {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
        padding: 20px;
        border-radius: 24px;
        background: rgba(248, 250, 252, 0.94);
        border: 1px solid rgba(170, 134, 63, 0.10);
    }

    .cir-file-card-main {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        min-width: 0;
    }

    .cir-file-card-icon {
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

    .cir-file-card-name {
        margin: 0;
        color: var(--text-primary);
        font-size: 1.08rem;
        font-weight: 900;
        word-break: break-word;
    }

    .cir-file-card-meta,
    .cir-file-card-desc {
        color: var(--text-secondary);
        line-height: 1.8;
        font-size: 0.96rem;
    }

    .cir-file-card-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        flex-shrink: 0;
    }

    .cir-file-card-actions a {
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

    html[data-theme="dark"] .cir-show-hero {
        background:
            radial-gradient(circle at top right, rgba(141, 110, 43, 0.28), transparent 30%),
            linear-gradient(135deg, rgba(17, 24, 39, 0.98) 0%, rgba(15, 23, 42, 0.98) 44%, rgba(30, 41, 59, 0.98) 100%);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 24px 52px rgba(2, 6, 23, 0.34);
    }

    html[data-theme="dark"] .cir-show-card,
    html[data-theme="dark"] .cir-show-stat-card,
    html[data-theme="dark"] .cir-file-card {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 18px 38px rgba(2, 6, 23, 0.28);
    }

    html[data-theme="dark"] .cir-detail-item,
    html[data-theme="dark"] .cir-show-chip,
    html[data-theme="dark"] .cir-detail-code {
        background: rgba(15, 23, 42, 0.78);
        border-color: rgba(148, 163, 184, 0.12);
    }

    html[data-theme="dark"] .cir-show-badge {
        background: rgba(141, 110, 43, 0.16);
        color: #f6deb0;
    }

    html[data-theme="dark"] .cir-show-btn-muted {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.14);
        color: var(--text-primary) !important;
    }

    html[data-theme="dark"] .cir-show-btn-muted:hover,
    html[data-theme="dark"] .cir-file-card-actions a:hover {
        color: #f6deb0 !important;
        border-color: rgba(141, 110, 43, 0.26);
    }

    @media (max-width: 1399px) {
        .cir-show-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 1199px) {
        .cir-show-hero-inner,
        .cir-show-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .cir-show-stats,
        .cir-detail-list {
            grid-template-columns: 1fr;
        }

        .cir-show-hero,
        .cir-show-card,
        .cir-show-stat-card {
            padding: 20px;
            border-radius: 24px;
        }

        .cir-file-card {
            flex-direction: column;
        }

        .cir-file-card-actions {
            width: 100%;
        }

        .cir-file-card-actions a {
            flex: 1 1 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid cir-show-page">
    <div class="cir-show-shell">
        <section class="cir-show-hero">
            <div class="cir-show-hero-inner">
                <div>
                    <div class="cir-show-profile">
                        <div class="cir-show-avatar">
                            <i class="fa {{ $circular->file_icon }}"></i>
                        </div>

                        <div>
                            <span class="cir-show-badge">
                                <i class="bi bi-megaphone-fill"></i>
                                {{ __('تعميم') }} #{{ $circular->id }}
                            </span>
                            <h1 class="cir-show-title">{{ $circular->name }}</h1>
                            <div class="cir-show-meta">
                                <span class="cir-show-chip"><i class="bi bi-file-earmark-text"></i>{{ $extension }}</span>
                                <span class="cir-show-chip"><i class="bi bi-hdd"></i>{{ $circular->file_size_human }}</span>
                                <span class="cir-show-chip"><i class="bi bi-calendar-check"></i>{{ $uploadedAt->format('Y-m-d H:i') }}</span>
                                <span class="cir-show-chip"><i class="bi bi-people"></i>{{ number_format($recipientsCount) }} {{ __('مستلم') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="cir-show-actions">
                    <a href="{{ route('circulars.edit', $circular) }}" class="cir-show-btn">
                        <i class="bi bi-pencil-square"></i>
                        {{ __('تعديل التعميم') }}
                    </a>
                    <a href="{{ route('circulars.download', $circular) }}" target="_blank" rel="noopener noreferrer" class="cir-show-btn-muted">
                        <i class="bi bi-download"></i>
                        {{ __('تحميل الملف') }}
                    </a>
                    <a href="{{ route('circulars.index') }}" class="cir-show-btn-muted">
                        <i class="bi bi-arrow-right-circle"></i>
                        {{ __('العودة للقائمة') }}
                    </a>
                    <form action="{{ route('circulars.destroy', $circular) }}" method="POST" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا التعميم؟ سيتم حذف الملف نهائيًا.') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="cir-show-btn-danger">
                            <i class="bi bi-trash3"></i>
                            {{ __('حذف التعميم') }}
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <section class="cir-show-stats">
            <div class="cir-show-stat-card">
                <span class="cir-show-stat-icon"><i class="bi bi-filetype-txt"></i></span>
                <p class="cir-show-stat-value">{{ $extension }}</p>
                <p class="cir-show-stat-label">{{ __('امتداد الملف') }}</p>
            </div>
            <div class="cir-show-stat-card">
                <span class="cir-show-stat-icon"><i class="bi bi-hdd-stack"></i></span>
                <p class="cir-show-stat-value">{{ $circular->file_size_human }}</p>
                <p class="cir-show-stat-label">{{ __('حجم الملف') }}</p>
            </div>
            <div class="cir-show-stat-card">
                <span class="cir-show-stat-icon"><i class="bi bi-calendar2-plus"></i></span>
                <p class="cir-show-stat-value">{{ $uploadedAt->diffForHumans() }}</p>
                <p class="cir-show-stat-label">{{ __('وقت الإضافة') }}</p>
            </div>
            <div class="cir-show-stat-card">
                <span class="cir-show-stat-icon"><i class="bi bi-people-fill"></i></span>
                <p class="cir-show-stat-value">{{ number_format($recipientsCount) }}</p>
                <p class="cir-show-stat-label">{{ __('المستلمون') }}</p>
            </div>
        </section>

        <div class="cir-show-grid">
            <section class="cir-show-card">
                <div class="cir-show-card-head">
                    <h2 class="cir-show-card-title"><i class="bi bi-info-circle"></i>{{ __('المعلومات الأساسية') }}</h2>
                    <span class="cir-show-card-note">{{ __('ملخص سريع لبيانات التعميم الأساسية كما تظهر داخل النظام.') }}</span>
                </div>

                <div class="cir-detail-list">
                    <div class="cir-detail-item">
                        <span class="cir-detail-label">{{ __('رقم التعميم') }}</span>
                        <div class="cir-detail-value">#{{ $circular->id }}</div>
                    </div>
                    <div class="cir-detail-item">
                        <span class="cir-detail-label">{{ __('اسم التعميم') }}</span>
                        <div class="cir-detail-value">{{ $circular->name }}</div>
                    </div>
                    <div class="cir-detail-item">
                        <span class="cir-detail-label">{{ __('الاسم الأصلي') }}</span>
                        <div class="cir-detail-value">{{ $circular->original_filename }}</div>
                    </div>
                    <div class="cir-detail-item">
                        <span class="cir-detail-label">{{ __('نوع الملف') }}</span>
                        <div class="cir-detail-value">{{ $fileType }}</div>
                    </div>
                    <div class="cir-detail-item">
                        <span class="cir-detail-label">{{ __('تاريخ الإضافة') }}</span>
                        <div class="cir-detail-value">{{ $uploadedAt->format('Y-m-d H:i:s') }}</div>
                    </div>
                    <div class="cir-detail-item">
                        <span class="cir-detail-label">{{ __('آخر تحديث') }}</span>
                        <div class="cir-detail-value">{{ $updatedAt->format('Y-m-d H:i:s') }}</div>
                    </div>
                </div>
            </section>

            <section class="cir-show-card">
                <div class="cir-show-card-head">
                    <h2 class="cir-show-card-title"><i class="bi bi-people"></i>{{ __('الجمهور والوصول') }}</h2>
                    <span class="cir-show-card-note">{{ __('معلومات الجمهور المستهدف ومسار التخزين للوصول الإداري والتنظيم الأفضل.') }}</span>
                </div>

                <div class="cir-detail-list">
                    <div class="cir-detail-item">
                        <span class="cir-detail-label">{{ __('عدد المستلمين') }}</span>
                        <div class="cir-detail-value">{{ number_format($recipientsCount) }}</div>
                    </div>
                    <div class="cir-detail-item">
                        <span class="cir-detail-label">{{ __('المستلمون') }}</span>
                        <div class="cir-detail-value">{{ $recipientNames->isNotEmpty() ? $recipientNames->take(8)->implode('، ') : __('لم يتم اختيار مستلمين') }}</div>
                    </div>
                    <div class="cir-detail-item">
                        <span class="cir-detail-label">{{ __('الامتداد') }}</span>
                        <div class="cir-detail-value">{{ $extension }}</div>
                    </div>
                    <div class="cir-detail-item">
                        <span class="cir-detail-label">{{ __('الحجم') }}</span>
                        <div class="cir-detail-value">{{ $circular->file_size_human }}</div>
                    </div>
                    <div class="cir-detail-item" style="grid-column: 1 / -1;">
                        <span class="cir-detail-label">{{ __('المسار التخزيني') }}</span>
                        <div class="cir-detail-value">
                            <span class="cir-detail-code">{{ $circular->file_path }}</span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="cir-show-card full-width">
                <div class="cir-show-card-head">
                    <h2 class="cir-show-card-title"><i class="bi bi-file-earmark-arrow-down"></i>{{ __('بطاقة الملف') }}</h2>
                    <span class="cir-show-card-note">{{ __('بطاقة مركزة تعرض ملف التعميم الحالي مع أهم التفاصيل وخيارات الوصول السريع.') }}</span>
                </div>

                <article class="cir-file-card">
                    <div class="cir-file-card-main">
                        <span class="cir-file-card-icon">
                            <i class="fa {{ $circular->file_icon }}"></i>
                        </span>
                        <div>
                            <h3 class="cir-file-card-name">{{ $circular->original_filename }}</h3>
                            <p class="cir-file-card-meta">
                                {{ __('الاسم المعروض') }}: {{ $circular->name }}<br>
                                {{ __('نوع الملف') }}: {{ $fileType }}<br>
                                {{ __('الامتداد') }}: {{ $extension }}<br>
                                {{ __('الحجم') }}: {{ $circular->file_size_human }}<br>
                                {{ __('المستلمون') }}: {{ number_format($recipientsCount) }}
                            </p>
                            <p class="cir-file-card-desc">{{ __('يمكنك تنزيل الملف مباشرة أو الانتقال إلى صفحة التعديل لتحديث الاسم أو استبدال الملف أو تعديل الجمهور المستهدف.') }}</p>
                        </div>
                    </div>

                    <div class="cir-file-card-actions">
                        <a href="{{ route('circulars.download', $circular) }}" target="_blank" rel="noopener noreferrer">
                            <i class="bi bi-download"></i>
                            {{ __('تنزيل الملف') }}
                        </a>
                        <a href="{{ route('circulars.edit', $circular) }}">
                            <i class="bi bi-pencil-square"></i>
                            {{ __('تعديل التعميم') }}
                        </a>
                    </div>
                </article>
            </section>
        </div>
    </div>
</div>
@endsection
