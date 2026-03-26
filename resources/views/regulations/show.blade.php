@extends('layouts.app')

@php
    $extension = strtoupper($regulation->file_extension ?: '-');
    $fileType = $regulation->file_type ?: __('غير محدد');
    $uploadedAt = $regulation->created_at;
    $updatedAt = $regulation->updated_at;
@endphp

@section('title', __('عرض تفاصيل اللائحة'))

@push('styles')
<style>
    .reg-show-page {
        padding: 10px 0 30px;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .reg-show-page button,
    .reg-show-page a {
        font: inherit;
    }

    .reg-show-shell {
        display: flex;
        flex-direction: column;
        gap: 22px;
    }

    .reg-show-hero,
    .reg-show-card,
    .reg-show-stat-card {
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.07);
    }

    .reg-show-hero {
        padding: 28px;
        background:
            radial-gradient(circle at top right, rgba(196, 168, 90, 0.28), transparent 30%),
            linear-gradient(135deg, #fff8ed 0%, #ffffff 44%, #f5ecde 100%);
    }

    .reg-show-hero-inner {
        display: grid;
        grid-template-columns: minmax(0, 1.6fr) minmax(280px, 0.9fr);
        gap: 20px;
        align-items: center;
    }

    .reg-show-profile {
        display: flex;
        align-items: center;
        gap: 18px;
        flex-wrap: wrap;
    }

    .reg-show-avatar {
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

    .reg-show-badge,
    .reg-show-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-radius: 999px;
        font-weight: 800;
    }

    .reg-show-badge {
        margin-bottom: 14px;
        padding: 8px 14px;
        background: rgba(170, 134, 63, 0.12);
        color: var(--primary-color);
    }

    .reg-show-title {
        margin: 0;
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 900;
        color: var(--text-primary);
    }

    .reg-show-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 16px;
    }

    .reg-show-chip {
        padding: 10px 14px;
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(170, 134, 63, 0.12);
        color: var(--text-primary);
    }

    .reg-show-actions {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .reg-show-btn,
    .reg-show-btn-muted,
    .reg-show-btn-danger {
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

    .reg-show-btn {
        background: linear-gradient(135deg, var(--primary-color), #c49b48);
        color: #fff !important;
        box-shadow: 0 16px 28px rgba(170, 134, 63, 0.24);
    }

    .reg-show-btn-muted {
        background: rgba(255, 255, 255, 0.9);
        color: var(--text-primary) !important;
        border-color: rgba(170, 134, 63, 0.14);
    }

    .reg-show-btn-danger {
        background: rgba(220, 38, 38, 0.08);
        color: var(--danger-color) !important;
        border-color: rgba(220, 38, 38, 0.16);
    }

    .reg-show-btn:hover,
    .reg-show-btn-muted:hover,
    .reg-show-btn-danger:hover {
        transform: translateY(-2px);
    }

    .reg-show-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }

    .reg-show-stat-card {
        padding: 20px;
    }

    .reg-show-stat-icon {
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

    .reg-show-stat-value {
        margin: 0;
        font-size: 1.9rem;
        font-weight: 900;
        color: var(--text-primary);
    }

    .reg-show-stat-label {
        margin: 6px 0 0;
        color: var(--text-secondary);
        font-weight: 700;
    }

    .reg-show-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .reg-show-card {
        padding: 24px;
    }

    .reg-show-card.full-width {
        grid-column: 1 / -1;
    }

    .reg-show-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .reg-show-card-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0;
        font-size: 1.3rem;
        font-weight: 900;
        color: var(--text-primary);
    }

    .reg-show-card-title i {
        width: 46px;
        height: 46px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(170, 134, 63, 0.12);
        color: var(--primary-color);
    }

    .reg-show-card-note {
        color: var(--text-secondary);
        font-size: 0.95rem;
        line-height: 1.8;
    }

    .reg-detail-list {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .reg-detail-item {
        padding: 16px;
        border-radius: 20px;
        background: rgba(248, 250, 252, 0.92);
        border: 1px solid rgba(170, 134, 63, 0.10);
    }

    .reg-detail-label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-secondary);
        font-size: 0.92rem;
        font-weight: 700;
    }

    .reg-detail-value {
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 800;
        word-break: break-word;
    }

    .reg-detail-code {
        display: inline-flex;
        padding: 6px 10px;
        border-radius: 12px;
        background: rgba(170, 134, 63, 0.10);
        color: var(--primary-color);
    }

    .reg-file-card {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
        padding: 20px;
        border-radius: 24px;
        background: rgba(248, 250, 252, 0.94);
        border: 1px solid rgba(170, 134, 63, 0.10);
    }

    .reg-file-card-main {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        min-width: 0;
    }

    .reg-file-card-icon {
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

    .reg-file-card-name {
        margin: 0;
        color: var(--text-primary);
        font-size: 1.08rem;
        font-weight: 900;
        word-break: break-word;
    }

    .reg-file-card-meta,
    .reg-file-card-desc {
        color: var(--text-secondary);
        line-height: 1.8;
        font-size: 0.96rem;
    }

    .reg-file-card-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        flex-shrink: 0;
    }

    .reg-file-card-actions a {
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

    html[data-theme="dark"] .reg-show-hero {
        background:
            radial-gradient(circle at top right, rgba(141, 110, 43, 0.28), transparent 30%),
            linear-gradient(135deg, rgba(17, 24, 39, 0.98) 0%, rgba(15, 23, 42, 0.98) 44%, rgba(30, 41, 59, 0.98) 100%);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 24px 52px rgba(2, 6, 23, 0.34);
    }

    html[data-theme="dark"] .reg-show-card,
    html[data-theme="dark"] .reg-show-stat-card,
    html[data-theme="dark"] .reg-file-card {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 18px 38px rgba(2, 6, 23, 0.28);
    }

    html[data-theme="dark"] .reg-detail-item,
    html[data-theme="dark"] .reg-show-chip,
    html[data-theme="dark"] .reg-detail-code {
        background: rgba(15, 23, 42, 0.78);
        border-color: rgba(148, 163, 184, 0.12);
    }

    html[data-theme="dark"] .reg-show-badge {
        background: rgba(141, 110, 43, 0.16);
        color: #f6deb0;
    }

    html[data-theme="dark"] .reg-show-btn-muted {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.14);
        color: var(--text-primary) !important;
    }

    html[data-theme="dark"] .reg-show-btn-muted:hover,
    html[data-theme="dark"] .reg-file-card-actions a:hover {
        color: #f6deb0 !important;
        border-color: rgba(141, 110, 43, 0.26);
    }

    @media (max-width: 1399px) {
        .reg-show-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 1199px) {
        .reg-show-hero-inner,
        .reg-show-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .reg-show-stats,
        .reg-detail-list {
            grid-template-columns: 1fr;
        }

        .reg-show-hero,
        .reg-show-card,
        .reg-show-stat-card {
            padding: 20px;
            border-radius: 24px;
        }

        .reg-file-card {
            flex-direction: column;
        }

        .reg-file-card-actions {
            width: 100%;
        }

        .reg-file-card-actions a {
            flex: 1 1 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid reg-show-page">
    <div class="reg-show-shell">
        <section class="reg-show-hero">
            <div class="reg-show-hero-inner">
                <div>
                    <div class="reg-show-profile">
                        <div class="reg-show-avatar">
                            <i class="fa {{ $regulation->file_icon }}"></i>
                        </div>

                        <div>
                            <span class="reg-show-badge">
                                <i class="bi bi-journal-text"></i>
                                {{ __('ملف اللائحة') }} #{{ $regulation->id }}
                            </span>
                            <h1 class="reg-show-title">{{ $regulation->name }}</h1>
                            <div class="reg-show-meta">
                                <span class="reg-show-chip"><i class="bi bi-file-earmark-text"></i>{{ $extension }}</span>
                                <span class="reg-show-chip"><i class="bi bi-hdd"></i>{{ $regulation->file_size_human }}</span>
                                <span class="reg-show-chip"><i class="bi bi-calendar-check"></i>{{ $uploadedAt->format('Y-m-d H:i') }}</span>
                                <span class="reg-show-chip"><i class="bi bi-clock-history"></i>{{ $updatedAt->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="reg-show-actions">
                    <a href="{{ route('regulations.edit', $regulation) }}" class="reg-show-btn">
                        <i class="bi bi-pencil-square"></i>
                        {{ __('تعديل اللائحة') }}
                    </a>
                    <a href="{{ route('regulations.download', $regulation) }}" target="_blank" rel="noopener noreferrer" class="reg-show-btn-muted">
                        <i class="bi bi-download"></i>
                        {{ __('تحميل الملف') }}
                    </a>
                    <a href="{{ route('regulations.index') }}" class="reg-show-btn-muted">
                        <i class="bi bi-arrow-right-circle"></i>
                        {{ __('العودة للقائمة') }}
                    </a>
                    <form action="{{ route('regulations.destroy', $regulation) }}" method="POST" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذه اللائحة؟ سيتم حذف الملف نهائيًا.') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="reg-show-btn-danger">
                            <i class="bi bi-trash3"></i>
                            {{ __('حذف اللائحة') }}
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <section class="reg-show-stats">
            <div class="reg-show-stat-card">
                <span class="reg-show-stat-icon"><i class="bi bi-filetype-txt"></i></span>
                <p class="reg-show-stat-value">{{ $extension }}</p>
                <p class="reg-show-stat-label">{{ __('امتداد الملف') }}</p>
            </div>
            <div class="reg-show-stat-card">
                <span class="reg-show-stat-icon"><i class="bi bi-hdd-stack"></i></span>
                <p class="reg-show-stat-value">{{ $regulation->file_size_human }}</p>
                <p class="reg-show-stat-label">{{ __('حجم الملف') }}</p>
            </div>
            <div class="reg-show-stat-card">
                <span class="reg-show-stat-icon"><i class="bi bi-calendar2-plus"></i></span>
                <p class="reg-show-stat-value">{{ $uploadedAt->diffForHumans() }}</p>
                <p class="reg-show-stat-label">{{ __('وقت الإضافة') }}</p>
            </div>
            <div class="reg-show-stat-card">
                <span class="reg-show-stat-icon"><i class="bi bi-arrow-repeat"></i></span>
                <p class="reg-show-stat-value">{{ $updatedAt->format('Y-m-d') }}</p>
                <p class="reg-show-stat-label">{{ __('آخر تحديث') }}</p>
            </div>
        </section>

        <div class="reg-show-grid">
            <section class="reg-show-card">
                <div class="reg-show-card-head">
                    <h2 class="reg-show-card-title"><i class="bi bi-info-circle"></i>{{ __('المعلومات الأساسية') }}</h2>
                    <span class="reg-show-card-note">{{ __('بيانات تعريفية سريعة عن اللائحة داخل النظام.') }}</span>
                </div>

                <div class="reg-detail-list">
                    <div class="reg-detail-item">
                        <span class="reg-detail-label">{{ __('رقم اللائحة') }}</span>
                        <div class="reg-detail-value">#{{ $regulation->id }}</div>
                    </div>
                    <div class="reg-detail-item">
                        <span class="reg-detail-label">{{ __('اسم اللائحة') }}</span>
                        <div class="reg-detail-value">{{ $regulation->name }}</div>
                    </div>
                    <div class="reg-detail-item">
                        <span class="reg-detail-label">{{ __('اسم الملف الأصلي') }}</span>
                        <div class="reg-detail-value">{{ $regulation->original_filename }}</div>
                    </div>
                    <div class="reg-detail-item">
                        <span class="reg-detail-label">{{ __('نوع الملف') }}</span>
                        <div class="reg-detail-value">{{ $fileType }}</div>
                    </div>
                    <div class="reg-detail-item">
                        <span class="reg-detail-label">{{ __('تاريخ الإضافة') }}</span>
                        <div class="reg-detail-value">{{ $uploadedAt->format('Y-m-d H:i:s') }}</div>
                    </div>
                    <div class="reg-detail-item">
                        <span class="reg-detail-label">{{ __('آخر تحديث') }}</span>
                        <div class="reg-detail-value">{{ $updatedAt->format('Y-m-d H:i:s') }}</div>
                    </div>
                </div>
            </section>

            <section class="reg-show-card">
                <div class="reg-show-card-head">
                    <h2 class="reg-show-card-title"><i class="bi bi-link-45deg"></i>{{ __('الوصول للملف') }}</h2>
                    <span class="reg-show-card-note">{{ __('استخدم هذه البطاقة للتنزيل المباشر أو مراجعة امتداد الملف ومساره التخزيني.') }}</span>
                </div>

                <div class="reg-detail-list">
                    <div class="reg-detail-item">
                        <span class="reg-detail-label">{{ __('الامتداد') }}</span>
                        <div class="reg-detail-value">{{ $extension }}</div>
                    </div>
                    <div class="reg-detail-item">
                        <span class="reg-detail-label">{{ __('الحجم') }}</span>
                        <div class="reg-detail-value">{{ $regulation->file_size_human }}</div>
                    </div>
                    <div class="reg-detail-item" style="grid-column: 1 / -1;">
                        <span class="reg-detail-label">{{ __('المسار التخزيني') }}</span>
                        <div class="reg-detail-value">
                            <span class="reg-detail-code">{{ $regulation->file_path }}</span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="reg-show-card full-width">
                <div class="reg-show-card-head">
                    <h2 class="reg-show-card-title"><i class="bi bi-file-earmark-arrow-down"></i>{{ __('بطاقة الملف') }}</h2>
                    <span class="reg-show-card-note">{{ __('ملخص الملف الحالي مع وصول مباشر للتنزيل ومراجعة أهم التفاصيل الفنية.') }}</span>
                </div>

                <article class="reg-file-card">
                    <div class="reg-file-card-main">
                        <span class="reg-file-card-icon">
                            <i class="fa {{ $regulation->file_icon }}"></i>
                        </span>
                        <div>
                            <h3 class="reg-file-card-name">{{ $regulation->original_filename }}</h3>
                            <p class="reg-file-card-meta">
                                {{ __('الاسم المعروض') }}: {{ $regulation->name }}<br>
                                {{ __('نوع الملف') }}: {{ $fileType }}<br>
                                {{ __('الامتداد') }}: {{ $extension }}<br>
                                {{ __('الحجم') }}: {{ $regulation->file_size_human }}
                            </p>
                            <p class="reg-file-card-desc">{{ __('يمكن تنزيل الملف مباشرة أو تعديل اسم اللائحة أو استبدال الملف من صفحة التعديل.') }}</p>
                        </div>
                    </div>

                    <div class="reg-file-card-actions">
                        <a href="{{ route('regulations.download', $regulation) }}" target="_blank" rel="noopener noreferrer">
                            <i class="bi bi-download"></i>
                            {{ __('تنزيل الملف') }}
                        </a>
                        <a href="{{ route('regulations.edit', $regulation) }}">
                            <i class="bi bi-pencil-square"></i>
                            {{ __('تعديل اللائحة') }}
                        </a>
                    </div>
                </article>
            </section>
        </div>
    </div>
</div>
@endsection
