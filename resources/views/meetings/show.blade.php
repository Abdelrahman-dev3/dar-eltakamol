@extends('layouts.app')

@php
    $inviteesCount = $meeting->users->count();
    $attachmentsCount = $meeting->attachments->count();
    $linkedPollsCount = $meeting->polls->count();
    $isToday = $meeting->date->isToday();
    $isUpcoming = $meeting->date->isFuture() && ! $isToday;
    $statusText = $isToday ? __('اليوم') : ($isUpcoming ? __('قادم') : __('منتهٍ'));
    $statusClass = $isToday ? 'info' : ($isUpcoming ? 'success' : 'danger');
    $daysLabel = $isToday
        ? __('اليوم')
        : ($isUpcoming ? __('متبقي بالأيام') : __('منذ الاجتماع'));
    $daysValue = $isToday ? 0 : abs(now()->diffInDays($meeting->date, false));
@endphp

@section('title', __('عرض تفاصيل الاجتماع'))

@push('styles')
<style>
    .meeting-show-page {
        padding: 10px 0 30px;
        color: var(--text-primary);
        font-size: 1rem;
    }
    .meeting-show-shell { display: flex; flex-direction: column; gap: 22px; }
    .meeting-show-page button,
    .meeting-show-page a {
        font: inherit;
    }
    .meeting-show-hero,
    .meeting-show-card,
    .meeting-show-stat-card {
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.07);
    }
    .meeting-show-hero {
        padding: 28px;
        background:
            radial-gradient(circle at top right, rgba(196, 168, 90, 0.28), transparent 30%),
            linear-gradient(135deg, #fff8ed 0%, #ffffff 44%, #f5ecde 100%);
    }
    .meeting-show-hero-inner {
        display: grid;
        grid-template-columns: minmax(0, 1.6fr) minmax(280px, 0.9fr);
        gap: 20px;
        align-items: center;
    }
    .meeting-show-profile { display: flex; align-items: center; gap: 18px; flex-wrap: wrap; }
    .meeting-show-avatar {
        width: 108px;
        height: 108px;
        border-radius: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary-color), #d4b066);
        color: #fff;
        font-size: 2.5rem;
        box-shadow: 0 20px 36px rgba(170, 134, 63, 0.22);
    }
    .meeting-show-badge,
    .meeting-show-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-radius: 999px;
        font-weight: 800;
    }
    .meeting-show-badge {
        margin-bottom: 14px;
        padding: 8px 14px;
        background: rgba(170, 134, 63, 0.12);
        color: var(--primary-color);
    }
    .meeting-show-title {
        margin: 0;
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 900;
        color: var(--text-primary);
    }
    .meeting-show-meta { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 16px; }
    .meeting-show-chip {
        padding: 10px 14px;
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(170, 134, 63, 0.12);
        color: var(--text-primary);
    }
    .meeting-show-chip.success { background: rgba(5, 150, 105, 0.10); color: var(--success-color); border-color: rgba(5, 150, 105, 0.18); }
    .meeting-show-chip.info { background: rgba(14, 165, 233, 0.10); color: #0284c7; border-color: rgba(14, 165, 233, 0.18); }
    .meeting-show-chip.danger { background: rgba(220, 38, 38, 0.08); color: var(--danger-color); border-color: rgba(220, 38, 38, 0.16); }
    .meeting-show-actions { display: flex; flex-direction: column; gap: 12px; }
    .meeting-show-btn,
    .meeting-show-btn-muted,
    .meeting-show-btn-danger {
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
    .meeting-show-btn { background: linear-gradient(135deg, var(--primary-color), #c49b48); color: #fff !important; box-shadow: 0 16px 28px rgba(170, 134, 63, 0.24); }
    .meeting-show-btn-muted { background: rgba(255, 255, 255, 0.9); color: var(--text-primary) !important; border-color: rgba(170, 134, 63, 0.14); }
    .meeting-show-btn-danger { background: rgba(220, 38, 38, 0.08); color: var(--danger-color) !important; border-color: rgba(220, 38, 38, 0.16); }
    .meeting-show-btn:hover,
    .meeting-show-btn-muted:hover,
    .meeting-show-btn-danger:hover { transform: translateY(-2px); }
    .meeting-show-stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; }
    .meeting-show-stat-card { padding: 20px; }
    .meeting-show-stat-icon {
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
    .meeting-show-stat-value { margin: 0; font-size: 1.9rem; font-weight: 900; color: var(--text-primary); }
    .meeting-show-stat-label { margin: 6px 0 0; color: var(--text-secondary); font-weight: 700; }
    .meeting-show-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; }
    .meeting-show-card { padding: 24px; }
    .meeting-show-card.full-width { grid-column: 1 / -1; }
    .meeting-show-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }
    .meeting-show-card-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0;
        font-size: 1.3rem;
        font-weight: 900;
        color: var(--text-primary);
    }
    .meeting-show-card-title i {
        width: 46px;
        height: 46px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(170, 134, 63, 0.12);
        color: var(--primary-color);
    }
    .meeting-show-card-note { color: var(--text-secondary); font-size: 0.95rem; line-height: 1.8; }
    .meeting-detail-list { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
    .meeting-detail-item {
        padding: 16px;
        border-radius: 20px;
        background: rgba(248, 250, 252, 0.92);
        border: 1px solid rgba(170, 134, 63, 0.10);
    }
    .meeting-detail-label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-secondary);
        font-size: 0.92rem;
        font-weight: 700;
    }
    .meeting-detail-value {
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 800;
        word-break: break-word;
    }
    .meeting-detail-code {
        display: inline-flex;
        padding: 6px 10px;
        border-radius: 12px;
        background: rgba(170, 134, 63, 0.10);
        color: var(--primary-color);
    }
    .meeting-users-grid,
    .meeting-attachments-grid {
        display: grid;
        gap: 16px;
    }
    .meeting-users-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .meeting-attachments-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .meeting-user-card,
    .meeting-attachment-card {
        padding: 18px;
        border-radius: 22px;
        background: rgba(248, 250, 252, 0.94);
        border: 1px solid rgba(170, 134, 63, 0.10);
    }
    .meeting-user-card {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .meeting-user-avatar {
        width: 52px;
        height: 52px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary-color), #cfa75b);
        color: #fff;
        font-size: 1.1rem;
        font-weight: 900;
        flex-shrink: 0;
    }
    .meeting-user-card strong,
    .meeting-attachment-name { display: block; color: var(--text-primary); font-size: 1.02rem; font-weight: 800; }
    .meeting-user-card span,
    .meeting-attachment-meta,
    .meeting-attachment-desc { color: var(--text-secondary); line-height: 1.8; }
    .meeting-attachment-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }
    .meeting-attachment-icon {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(170, 134, 63, 0.12);
        color: var(--primary-color);
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .meeting-attachment-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 12px;
    }
    .meeting-attachment-actions a,
    .meeting-attachment-actions button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 14px;
        border: 1px solid transparent;
        text-decoration: none !important;
        font-weight: 800;
    }
    .meeting-attachment-actions a { background: rgba(170, 134, 63, 0.10); color: var(--primary-color); }
    .meeting-attachment-actions button { background: rgba(220, 38, 38, 0.08); color: var(--danger-color); border-color: rgba(220, 38, 38, 0.12); }
    .meeting-polls-grid {
        display: grid;
        gap: 18px;
    }
    .meeting-poll-card {
        padding: 18px;
        border-radius: 22px;
        background: rgba(248, 250, 252, 0.94);
        border: 1px solid rgba(170, 134, 63, 0.10);
    }
    .meeting-poll-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 14px;
        flex-wrap: wrap;
    }
    .meeting-poll-title {
        margin: 0 0 6px;
        color: var(--text-primary);
        font-size: 1.08rem;
        font-weight: 900;
    }
    .meeting-poll-meta {
        color: var(--text-secondary);
        font-weight: 700;
        line-height: 1.7;
    }
    .meeting-poll-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .meeting-poll-actions a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 14px;
        background: rgba(170, 134, 63, 0.10);
        color: var(--primary-color);
        text-decoration: none !important;
        font-weight: 800;
    }
    .meeting-poll-progress-list {
        display: grid;
        gap: 12px;
    }
    .meeting-poll-progress-head {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 8px;
        color: var(--text-primary);
        font-weight: 800;
    }
    .meeting-poll-progress {
        height: 10px;
        overflow: hidden;
        border-radius: 999px;
        background: rgba(170, 134, 63, 0.12);
    }
    .meeting-poll-progress-bar {
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(135deg, var(--primary-color), #d4b066);
    }
    .meeting-empty-state {
        padding: 24px;
        border-radius: 22px;
        text-align: center;
        color: var(--text-secondary);
        background: rgba(248, 250, 252, 0.9);
        border: 1px dashed rgba(170, 134, 63, 0.18);
    }
    html[data-theme="dark"] .meeting-show-hero {
        background:
            radial-gradient(circle at top right, rgba(141, 110, 43, 0.28), transparent 30%),
            linear-gradient(135deg, rgba(17, 24, 39, 0.98) 0%, rgba(15, 23, 42, 0.98) 44%, rgba(30, 41, 59, 0.98) 100%);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 24px 52px rgba(2, 6, 23, 0.34);
    }
    html[data-theme="dark"] .meeting-show-card,
    html[data-theme="dark"] .meeting-show-stat-card,
    html[data-theme="dark"] .meeting-user-card,
    html[data-theme="dark"] .meeting-attachment-card,
    html[data-theme="dark"] .meeting-poll-card,
    html[data-theme="dark"] .meeting-empty-state {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 18px 38px rgba(2, 6, 23, 0.28);
    }
    html[data-theme="dark"] .meeting-detail-item,
    html[data-theme="dark"] .meeting-show-chip,
    html[data-theme="dark"] .meeting-detail-code {
        background: rgba(15, 23, 42, 0.78);
        border-color: rgba(148, 163, 184, 0.12);
    }
    html[data-theme="dark"] .meeting-show-badge {
        background: rgba(141, 110, 43, 0.16);
        color: #f6deb0;
    }
    html[data-theme="dark"] .meeting-show-chip.success { background: rgba(5, 150, 105, 0.16); color: #6ee7b7; }
    html[data-theme="dark"] .meeting-show-chip.info { background: rgba(14, 165, 233, 0.16); color: #7dd3fc; }
    html[data-theme="dark"] .meeting-show-chip.danger { background: rgba(220, 38, 38, 0.16); color: #fca5a5; }
    html[data-theme="dark"] .meeting-show-btn-muted {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.14);
        color: var(--text-primary) !important;
    }
    html[data-theme="dark"] .meeting-show-btn-muted:hover,
    html[data-theme="dark"] .meeting-attachment-actions a:hover {
        color: #f6deb0 !important;
        border-color: rgba(141, 110, 43, 0.26);
    }
    @media (max-width: 1399px) {
        .meeting-show-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 1199px) {
        .meeting-show-hero-inner,
        .meeting-show-grid,
        .meeting-users-grid,
        .meeting-attachments-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 767px) {
        .meeting-show-stats,
        .meeting-detail-list { grid-template-columns: 1fr; }
        .meeting-show-hero,
        .meeting-show-card,
        .meeting-show-stat-card { padding: 20px; border-radius: 24px; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid meeting-show-page">
    <div class="meeting-show-shell">
        <section class="meeting-show-hero">
            <div class="meeting-show-hero-inner">
                <div>
                    <div class="meeting-show-profile">
                        <div class="meeting-show-avatar">
                            <i class="bi bi-camera-video-fill"></i>
                        </div>

                        <div>
                            <span class="meeting-show-badge">
                                <i class="bi bi-calendar2-event"></i>
                                {{ __('ملف الاجتماع') }} #{{ $meeting->id }}
                            </span>
                            <h1 class="meeting-show-title">{{ $meeting->name }}</h1>
                            <div class="meeting-show-meta">
                                <span class="meeting-show-chip"><i class="bi bi-calendar-check"></i>{{ $meeting->date->format('Y-m-d H:i') }}</span>
                                <span class="meeting-show-chip {{ $statusClass }}"><i class="bi bi-clock"></i>{{ $statusText }}</span>
                                <span class="meeting-show-chip"><i class="bi bi-people"></i>{{ __('المدعوون') }}: {{ $inviteesCount }}</span>
                                <span class="meeting-show-chip"><i class="bi bi-paperclip"></i>{{ __('المرفقات') }}: {{ $attachmentsCount }}</span>
                                <span class="meeting-show-chip"><i class="bi bi-ui-checks-grid"></i>{{ __('الاستطلاعات') }}: {{ $linkedPollsCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="meeting-show-actions">
                    <a href="{{ route('meetings.edit', $meeting) }}" class="meeting-show-btn"><i class="bi bi-pencil-square"></i>{{ __('تعديل الاجتماع') }}</a>
                    <a href="{{ $meeting->url }}" target="_blank" rel="noopener noreferrer" class="meeting-show-btn-muted"><i class="bi bi-box-arrow-up-right"></i>{{ __('فتح رابط الاجتماع') }}</a>
                    <a href="{{ route('meetings.index') }}" class="meeting-show-btn-muted"><i class="bi bi-arrow-right-circle"></i>{{ __('العودة للقائمة') }}</a>
                    <form action="{{ route('meetings.destroy', $meeting) }}" method="POST" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا الاجتماع؟ هذا الإجراء لا يمكن التراجع عنه.') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="meeting-show-btn-danger"><i class="bi bi-trash3"></i>{{ __('حذف الاجتماع') }}</button>
                    </form>
                </div>
            </div>
        </section>

        <section class="meeting-show-stats">
            <div class="meeting-show-stat-card">
                <span class="meeting-show-stat-icon"><i class="bi bi-people-fill"></i></span>
                <p class="meeting-show-stat-value">{{ $inviteesCount }}</p>
                <p class="meeting-show-stat-label">{{ __('إجمالي المدعوين') }}</p>
            </div>
            <div class="meeting-show-stat-card">
                <span class="meeting-show-stat-icon"><i class="bi bi-paperclip"></i></span>
                <p class="meeting-show-stat-value">{{ $attachmentsCount }}</p>
                <p class="meeting-show-stat-label">{{ __('المرفقات المرفوعة') }}</p>
            </div>
            <div class="meeting-show-stat-card">
                <span class="meeting-show-stat-icon"><i class="bi bi-hourglass-split"></i></span>
                <p class="meeting-show-stat-value">{{ $daysValue }}</p>
                <p class="meeting-show-stat-label">{{ $daysLabel }}</p>
            </div>
            <div class="meeting-show-stat-card">
                <span class="meeting-show-stat-icon"><i class="bi bi-clock-history"></i></span>
                <p class="meeting-show-stat-value">{{ $meeting->updated_at->diffForHumans() }}</p>
                <p class="meeting-show-stat-label">{{ __('آخر تحديث') }}</p>
            </div>
        </section>

        <div class="meeting-show-grid">
            <section class="meeting-show-card">
                <div class="meeting-show-card-head">
                    <h2 class="meeting-show-card-title"><i class="bi bi-info-circle"></i>{{ __('المعلومات الأساسية') }}</h2>
                    <span class="meeting-show-card-note">{{ __('بيانات الموعد والرابط والحالة الحالية للاجتماع.') }}</span>
                </div>
                <div class="meeting-detail-list">
                    <div class="meeting-detail-item"><span class="meeting-detail-label">{{ __('رقم الاجتماع') }}</span><div class="meeting-detail-value">#{{ $meeting->id }}</div></div>
                    <div class="meeting-detail-item"><span class="meeting-detail-label">{{ __('اسم الاجتماع') }}</span><div class="meeting-detail-value">{{ $meeting->name }}</div></div>
                    <div class="meeting-detail-item"><span class="meeting-detail-label">{{ __('تاريخ ووقت الاجتماع') }}</span><div class="meeting-detail-value">{{ $meeting->date->format('Y-m-d H:i') }}</div></div>
                    <div class="meeting-detail-item"><span class="meeting-detail-label">{{ __('الحالة') }}</span><div class="meeting-detail-value">{{ $statusText }}</div></div>
                    <div class="meeting-detail-item"><span class="meeting-detail-label">{{ __('تاريخ الإنشاء') }}</span><div class="meeting-detail-value">{{ $meeting->created_at->format('Y-m-d H:i:s') }}</div></div>
                    <div class="meeting-detail-item"><span class="meeting-detail-label">{{ __('آخر تحديث') }}</span><div class="meeting-detail-value">{{ $meeting->updated_at->format('Y-m-d H:i:s') }}</div></div>
                </div>
            </section>

            <section class="meeting-show-card">
                <div class="meeting-show-card-head">
                    <h2 class="meeting-show-card-title"><i class="bi bi-link-45deg"></i>{{ __('الرابط والوصول') }}</h2>
                    <span class="meeting-show-card-note">{{ __('الوصول المباشر للاجتماع مع عرض الرابط كاملًا للمراجعة أو النسخ.') }}</span>
                </div>
                <div class="meeting-detail-list">
                    <div class="meeting-detail-item" style="grid-column: 1 / -1;">
                        <span class="meeting-detail-label">{{ __('رابط الاجتماع') }}</span>
                        <div class="meeting-detail-value">
                            <span class="meeting-detail-code">{{ $meeting->url }}</span>
                        </div>
                    </div>
                    <div class="meeting-detail-item">
                        <span class="meeting-detail-label">{{ __('فتح الرابط') }}</span>
                        <div class="meeting-detail-value">
                            <a href="{{ $meeting->url }}" target="_blank" rel="noopener noreferrer">{{ __('الانتقال للاجتماع الآن') }}</a>
                        </div>
                    </div>
                    <div class="meeting-detail-item">
                        <span class="meeting-detail-label">{{ __('الوصف الزمني') }}</span>
                        <div class="meeting-detail-value">{{ $meeting->date->diffForHumans() }}</div>
                    </div>
                </div>
            </section>

            <section class="meeting-show-card full-width">
                <div class="meeting-show-card-head">
                    <h2 class="meeting-show-card-title"><i class="bi bi-ui-checks-grid"></i>{{ __('نتائج الاستطلاعات المرتبطة') }}</h2>
                    <span class="meeting-show-card-note">{{ __('ملخص سريع لنتائج كل استطلاع مرتبط بهذا الاجتماع مع روابط التفاصيل الكاملة.') }}</span>
                </div>

                @if ($linkedPollsCount > 0)
                    <div class="meeting-polls-grid">
                        @foreach ($meeting->polls as $poll)
                            @php
                                $totalPollVotes = $poll->pollAnswers->count();
                            @endphp
                            <article class="meeting-poll-card">
                                <div class="meeting-poll-head">
                                    <div>
                                        <h3 class="meeting-poll-title">{{ $poll->title ?: $poll->question }}</h3>
                                        <div class="meeting-poll-meta">
                                            {{ __('عدد الأصوات') }}: {{ number_format($totalPollVotes) }}
                                            - {{ __('الخيارات') }}: {{ number_format($poll->pollOptions->count()) }}
                                        </div>
                                    </div>
                                    <div class="meeting-poll-actions">
                                        <a href="{{ route('polls.show', $poll) }}">
                                            <i class="bi bi-eye-fill"></i>
                                            {{ __('تفاصيل الاستطلاع') }}
                                        </a>
                                        <a href="{{ route('polls.results', $poll) }}">
                                            <i class="bi bi-bar-chart-fill"></i>
                                            {{ __('النتائج الكاملة') }}
                                        </a>
                                    </div>
                                </div>

                                @if ($poll->pollOptions->count() > 0)
                                    <div class="meeting-poll-progress-list">
                                        @foreach ($poll->pollOptions as $option)
                                            @php
                                                $optionVotes = $option->votes;
                                                $percentage = $totalPollVotes > 0 ? ($optionVotes / $totalPollVotes) * 100 : 0;
                                            @endphp
                                            <div>
                                                <div class="meeting-poll-progress-head">
                                                    <span>{{ $option->option_text }}</span>
                                                    <span>{{ number_format($optionVotes) }} {{ __('صوت') }} - {{ number_format($percentage, 1) }}%</span>
                                                </div>
                                                <div class="meeting-poll-progress">
                                                    <div class="meeting-poll-progress-bar" style="width: {{ $percentage }}%;"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="meeting-empty-state">
                                        <div>{{ __('لا توجد خيارات تصويت مرتبطة بهذا الاستطلاع حتى الآن.') }}</div>
                                    </div>
                                @endif
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="meeting-empty-state">
                        <i class="bi bi-ui-checks" style="font-size: 2rem; display: inline-block; margin-bottom: 10px; color: var(--primary-color);"></i>
                        <div>{{ __('لا يوجد استطلاع مرتبط بهذا الاجتماع حتى الآن.') }}</div>
                    </div>
                @endif
            </section>

            <section class="meeting-show-card full-width">
                <div class="meeting-show-card-head">
                    <h2 class="meeting-show-card-title"><i class="bi bi-people"></i>{{ __('المدعوون') }}</h2>
                    <span class="meeting-show-card-note">{{ __('قائمة المستخدمين المرتبطين بهذا الاجتماع حاليًا.') }}</span>
                </div>
                @if ($inviteesCount > 0)
                    <div class="meeting-users-grid">
                        @foreach ($meeting->users as $user)
                            <article class="meeting-user-card">
                                <span class="meeting-user-avatar">{{ mb_substr($user->name, 0, 1) }}</span>
                                <div>
                                    <strong>{{ $user->name }}</strong>
                                    <span>{{ $user->email }}</span>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="meeting-empty-state">
                        <i class="bi bi-person-x" style="font-size: 2rem; display: inline-block; margin-bottom: 10px; color: var(--primary-color);"></i>
                        <div>{{ __('لم يتم تحديد مدعوين لهذا الاجتماع حتى الآن.') }}</div>
                    </div>
                @endif
            </section>

            <section class="meeting-show-card full-width">
                <div class="meeting-show-card-head">
                    <h2 class="meeting-show-card-title"><i class="bi bi-folder2-open"></i>{{ __('المرفقات والملفات') }}</h2>
                    <span class="meeting-show-card-note">{{ __('جميع الملفات المرتبطة بالاجتماع مع معلومات الرفع والتنزيل والحذف.') }}</span>
                </div>
                @if ($attachmentsCount > 0)
                    <div class="meeting-attachments-grid">
                        @foreach ($meeting->attachments as $attachment)
                            @php
                                $attachmentIcon = match ($attachment->file_type) {
                                    'image' => 'bi-image',
                                    'pdf' => 'bi-filetype-pdf',
                                    'document' => 'bi-file-earmark-word',
                                    'spreadsheet' => 'bi-file-earmark-excel',
                                    'archive' => 'bi-file-earmark-zip',
                                    default => 'bi-file-earmark-text',
                                };
                            @endphp
                            <article class="meeting-attachment-card">
                                <div class="meeting-attachment-head">
                                    <div style="min-width: 0;">
                                        <span class="meeting-attachment-name">{{ $attachment->file_name }}</span>
                                        <div class="meeting-attachment-meta">{{ $attachment->file_size_human }} - {{ $attachment->created_at->format('Y-m-d H:i') }}</div>
                                    </div>
                                    <span class="meeting-attachment-icon"><i class="bi {{ $attachmentIcon }}"></i></span>
                                </div>

                                <p class="meeting-attachment-desc">{{ $attachment->description ?: __('لا يوجد وصف مضاف لهذا الملف.') }}</p>
                                <div class="meeting-attachment-meta">{{ __('رفع بواسطة') }}: {{ optional($attachment->uploader)->name ?: __('غير محدد') }}</div>

                                <div class="meeting-attachment-actions">
                                    <a href="{{ route('meetings.attachments.download', $attachment) }}">
                                        <i class="bi bi-download"></i>
                                        {{ __('تنزيل') }}
                                    </a>
                                    <form action="{{ route('meetings.attachments.delete', $attachment) }}" method="POST" style="margin: 0;" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا المرفق؟') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">
                                            <i class="bi bi-trash3"></i>
                                            {{ __('حذف') }}
                                        </button>
                                    </form>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="meeting-empty-state">
                        <i class="bi bi-folder2" style="font-size: 2rem; display: inline-block; margin-bottom: 10px; color: var(--primary-color);"></i>
                        <div>{{ __('لا توجد مرفقات لهذا الاجتماع حتى الآن.') }}</div>
                    </div>
                @endif
            </section>
        </div>
    </div>
</div>
@endsection
