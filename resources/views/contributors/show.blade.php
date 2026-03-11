@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Storage;

    $documentsCount = $contributor->documents->count();
    $sellSharesCount = $contributor->sellShares->count();
    $sharesPOsCount = $contributor->sharesPOs->count();
    $shareTransactionsCount = $contributor->shareTransLines->count();
    $profitsCount = $contributor->userProfits->count();
    $hasProfilePicture = !empty($contributor->profile_picture);
@endphp

@section('title', __('عرض تفاصيل المساهم'))

@push('styles')
<style>
    .contributor-show-page { padding: 10px 0 30px; }
    .contributor-show-shell { display: flex; flex-direction: column; gap: 24px; }
    .contributor-show-hero {
        position: relative; overflow: hidden; border-radius: 32px; padding: clamp(24px, 3vw, 34px);
        background: radial-gradient(circle at top right, rgba(196, 168, 90, 0.28), transparent 30%), linear-gradient(135deg, #fff8ed 0%, #ffffff 44%, #f5ecde 100%);
        border: 1px solid rgba(170, 134, 63, 0.14); box-shadow: 0 22px 52px rgba(15, 23, 42, 0.08); animation: contributorShowFadeUp 0.72s ease both;
    }
    .contributor-show-hero::before, .contributor-show-hero::after { content: ""; position: absolute; border-radius: 999px; pointer-events: none; }
    .contributor-show-hero::before { width: 240px; height: 240px; top: -130px; inset-inline-end: -70px; background: rgba(170, 134, 63, 0.09); }
    .contributor-show-hero::after { width: 190px; height: 190px; bottom: -110px; inset-inline-start: -50px; background: rgba(196, 168, 90, 0.13); }
    .contributor-show-hero-inner { position: relative; z-index: 1; display: grid; grid-template-columns: minmax(0, 1.5fr) minmax(280px, 0.95fr); gap: 22px; align-items: center; }
    .contributor-show-profile { display: flex; align-items: center; gap: 18px; flex-wrap: wrap; }
    .contributor-show-avatar {
        width: 110px; height: 110px; border-radius: 30px; overflow: hidden; display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, var(--primary-color), #d4b066); color: #fff; font-size: 2.6rem; font-weight: 900;
        box-shadow: 0 20px 36px rgba(170, 134, 63, 0.24); flex-shrink: 0;
    }
    .contributor-show-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .contributor-show-badge {
        display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; border-radius: 999px; background: rgba(170, 134, 63, 0.12);
        color: var(--primary-color); font-size: 0.98rem; font-weight: 800; margin-bottom: 14px;
    }
    .contributor-show-title { margin: 0; color: var(--text-primary); font-size: clamp(2rem, 4vw, 3.1rem); font-weight: 900; line-height: 1.1; }
    .contributor-show-subtitle { margin: 12px 0 0; color: var(--text-secondary); font-size: clamp(1rem, 1.5vw, 1.18rem); line-height: 1.9; max-width: 760px; }
    .contributor-show-meta { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-top: 16px; }
    .contributor-show-chip {
        display: inline-flex; align-items: center; gap: 8px; padding: 10px 14px; border-radius: 999px; background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(170, 134, 63, 0.12); color: var(--text-primary); font-size: 0.96rem; font-weight: 700;
    }
    .contributor-show-chip.board { background: rgba(5, 150, 105, 0.10); color: var(--success-color); border-color: rgba(5, 150, 105, 0.18); }
    .contributor-show-actions { display: flex; flex-direction: column; gap: 12px; align-items: stretch; }
    .contributor-show-btn, .contributor-show-btn-muted, .contributor-show-btn-danger {
        display: inline-flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 13px 18px; border-radius: 18px;
        border: 1px solid transparent; text-decoration: none !important; font-size: 1.02rem; font-weight: 800;
        transition: transform 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease, border-color 0.25s ease, color 0.25s ease;
    }
    .contributor-show-btn { background: linear-gradient(135deg, var(--primary-color), #c49b48); color: #fff !important; box-shadow: 0 18px 30px rgba(170, 134, 63, 0.24); }
    .contributor-show-btn-muted { background: rgba(255, 255, 255, 0.9); color: var(--text-primary) !important; border-color: rgba(170, 134, 63, 0.14); }
    .contributor-show-btn-danger { background: rgba(220, 38, 38, 0.10); color: var(--danger-color) !important; border-color: rgba(220, 38, 38, 0.16); }
    .contributor-show-btn:hover, .contributor-show-btn-muted:hover, .contributor-show-btn-danger:hover { transform: translateY(-2px); }
    .contributor-show-stats { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 16px; }
    .contributor-show-stat-card {
        position: relative; overflow: hidden; border-radius: 24px; padding: 22px 20px; background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.12); box-shadow: 0 16px 36px rgba(15, 23, 42, 0.06); animation: contributorShowFadeUp 0.8s ease both;
    }
    .contributor-show-stat-card::after { content: ""; position: absolute; width: 95px; height: 95px; border-radius: 50%; top: -40px; inset-inline-end: -22px; background: rgba(170, 134, 63, 0.08); }
    .contributor-show-stat-card > * { position: relative; z-index: 1; }
    .contributor-show-stat-icon {
        width: 52px; height: 52px; border-radius: 18px; display: inline-flex; align-items: center; justify-content: center;
        background: rgba(170, 134, 63, 0.12); color: var(--primary-color); font-size: 1.35rem; margin-bottom: 16px;
    }
    .contributor-show-stat-value { margin: 0; color: var(--text-primary); font-size: clamp(1.7rem, 2.3vw, 2.1rem); font-weight: 900; }
    .contributor-show-stat-label { margin: 8px 0 0; color: var(--text-secondary); font-size: 0.98rem; font-weight: 700; }
    .contributor-show-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; align-items: start; }
    .contributor-show-card {
        position: relative; overflow: hidden; border-radius: 28px; padding: 24px; background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.12); box-shadow: 0 18px 38px rgba(15, 23, 42, 0.07); animation: contributorShowFadeUp 0.84s ease both;
    }
    .contributor-show-card::after { content: ""; position: absolute; width: 120px; height: 120px; border-radius: 50%; top: -54px; inset-inline-end: -50px; background: rgba(170, 134, 63, 0.07); pointer-events: none; }
    .contributor-show-card > * { position: relative; z-index: 1; }
    .contributor-show-card.full-width { grid-column: 1 / -1; }
    .contributor-show-card-head { display: flex; align-items: center; justify-content: space-between; gap: 14px; margin-bottom: 18px; flex-wrap: wrap; }
    .contributor-show-card-title { display: flex; align-items: center; gap: 12px; margin: 0; color: var(--text-primary); font-size: 1.35rem; font-weight: 900; }
    .contributor-show-card-title i {
        width: 48px; height: 48px; border-radius: 16px; display: inline-flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, rgba(170, 134, 63, 0.16), rgba(170, 134, 63, 0.06)); color: var(--primary-color); font-size: 1.25rem;
    }
    .contributor-show-card-note { color: var(--text-secondary); font-size: 0.95rem; line-height: 1.8; }
    .contributor-detail-list { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
    .contributor-detail-item { padding: 16px; border-radius: 20px; background: rgba(248, 250, 252, 0.92); border: 1px solid rgba(170, 134, 63, 0.10); }
    .contributor-detail-label { display: block; margin-bottom: 8px; color: var(--text-secondary); font-size: 0.92rem; font-weight: 700; }
    .contributor-detail-value { color: var(--text-primary); font-size: 1.04rem; font-weight: 800; word-break: break-word; }
    .contributor-detail-code { display: inline-flex; align-items: center; padding: 6px 10px; border-radius: 12px; background: rgba(170, 134, 63, 0.10); color: var(--primary-color); font-size: 0.96rem; font-weight: 800; }
    .contributor-documents-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
    .contributor-document-card { padding: 18px; border-radius: 22px; background: rgba(248, 250, 252, 0.94); border: 1px solid rgba(170, 134, 63, 0.10); transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease; }
    .contributor-document-card:hover { transform: translateY(-3px); box-shadow: 0 18px 32px rgba(15, 23, 42, 0.08); border-color: rgba(170, 134, 63, 0.18); }
    .contributor-document-head { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
    .contributor-document-icon {
        width: 48px; height: 48px; border-radius: 16px; display: inline-flex; align-items: center; justify-content: center;
        background: rgba(170, 134, 63, 0.12); color: var(--primary-color); font-size: 1.25rem; flex-shrink: 0;
    }
    .contributor-document-name { margin: 0; color: var(--text-primary); font-size: 1.04rem; font-weight: 800; word-break: break-word; }
    .contributor-document-meta { margin-top: 5px; color: var(--text-secondary); font-size: 0.92rem; }
    .contributor-document-desc { margin: 0 0 14px; color: var(--text-secondary); line-height: 1.8; min-height: 44px; }
    .contributor-document-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .contributor-document-actions a, .contributor-document-actions button {
        display: inline-flex; align-items: center; gap: 8px; padding: 10px 14px; border-radius: 14px; border: 1px solid transparent;
        font-size: 0.92rem; font-weight: 800; text-decoration: none !important; transition: transform 0.25s ease, background-color 0.25s ease, border-color 0.25s ease, color 0.25s ease;
    }
    .contributor-document-actions a { background: rgba(170, 134, 63, 0.10); color: var(--primary-color); }
    .contributor-document-actions button { background: rgba(220, 38, 38, 0.08); color: var(--danger-color); border-color: rgba(220, 38, 38, 0.12); }
    .contributor-document-actions a:hover, .contributor-document-actions button:hover { transform: translateY(-2px); }
    .contributor-activity-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; }
    .contributor-activity-card { padding: 20px; border-radius: 24px; background: rgba(248, 250, 252, 0.94); border: 1px solid rgba(170, 134, 63, 0.10); transition: transform 0.25s ease, box-shadow 0.25s ease; }
    .contributor-activity-card:hover { transform: translateY(-3px); box-shadow: 0 18px 30px rgba(15, 23, 42, 0.08); }
    .contributor-activity-top { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 12px; }
    .contributor-activity-icon { width: 48px; height: 48px; border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; background: rgba(170, 134, 63, 0.12); color: var(--primary-color); font-size: 1.25rem; }
    .contributor-activity-count { color: var(--text-primary); font-size: 2rem; font-weight: 900; line-height: 1; }
    .contributor-activity-label { margin: 0 0 14px; color: var(--text-secondary); font-size: 0.98rem; font-weight: 700; line-height: 1.8; }
    .contributor-activity-link { display: inline-flex; align-items: center; gap: 8px; color: var(--primary-color); font-size: 0.95rem; font-weight: 800; text-decoration: none !important; }
    .contributor-empty-state { padding: 26px; border-radius: 24px; text-align: center; background: rgba(248, 250, 252, 0.9); border: 1px dashed rgba(170, 134, 63, 0.18); color: var(--text-secondary); line-height: 1.9; }
    html[data-theme="dark"] .contributor-show-hero {
        background: radial-gradient(circle at top right, rgba(141, 110, 43, 0.28), transparent 32%), linear-gradient(135deg, rgba(17, 24, 39, 0.98) 0%, rgba(15, 23, 42, 0.98) 55%, rgba(30, 41, 59, 0.98) 100%);
        border-color: rgba(148, 163, 184, 0.14); box-shadow: 0 26px 56px rgba(2, 6, 23, 0.34);
    }
    html[data-theme="dark"] .contributor-show-chip, html[data-theme="dark"] .contributor-show-btn-muted, html[data-theme="dark"] .contributor-show-stat-card, html[data-theme="dark"] .contributor-show-card {
        background: rgba(15, 23, 42, 0.92); border-color: rgba(148, 163, 184, 0.14); box-shadow: 0 18px 38px rgba(2, 6, 23, 0.28);
    }
    html[data-theme="dark"] .contributor-detail-item, html[data-theme="dark"] .contributor-document-card, html[data-theme="dark"] .contributor-activity-card, html[data-theme="dark"] .contributor-empty-state {
        background: rgba(15, 23, 42, 0.76); border-color: rgba(148, 163, 184, 0.12);
    }
    html[data-theme="dark"] .contributor-document-actions a { background: rgba(141, 110, 43, 0.16); }
    @media (max-width: 1399px) { .contributor-show-stats { grid-template-columns: repeat(3, minmax(0, 1fr)); } .contributor-activity-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 1199px) {
        .contributor-show-hero-inner, .contributor-show-grid, .contributor-documents-grid { grid-template-columns: 1fr; }
        .contributor-show-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 767px) {
        .contributor-show-page { padding-top: 0; }
        .contributor-show-hero, .contributor-show-stat-card, .contributor-show-card { border-radius: 24px; padding: 20px; }
        .contributor-show-stats, .contributor-detail-list, .contributor-activity-grid { grid-template-columns: 1fr; }
        .contributor-show-actions { width: 100%; }
    }
    @keyframes contributorShowFadeUp { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endpush

@section('content')
<div class="container-fluid contributor-show-page">
    <div class="contributor-show-shell">
        <section class="contributor-show-hero">
            <div class="contributor-show-hero-inner">
                <div>
                    <div class="contributor-show-profile">
                        <div class="contributor-show-avatar">
                            @if ($hasProfilePicture)
                                <img src="{{ $contributor->profile_picture_url }}" alt="{{ $contributor->name }}">
                            @else
                                {{ $contributor->initials }}
                            @endif
                        </div>

                        <div>
                            <span class="contributor-show-badge">
                                <i class="bi bi-person-lines-fill"></i>
                                {{ __('ملف المساهم') }} #{{ $contributor->id }}
                            </span>
                            <h1 class="contributor-show-title">{{ $contributor->name ?? __('غير محدد') }}</h1>
                            <p class="contributor-show-subtitle">{{ __('عرض مركزي لكل بيانات المساهم والوثائق والأنشطة المرتبطة به، مع تصميم أوضح يساعد على المراجعة والتنقل السريع.') }}</p>
                            <div class="contributor-show-meta">
                                <span class="contributor-show-chip"><i class="bi bi-credit-card-2-front"></i>{{ __('رقم الهوية') }}: {{ $contributor->id_number ?? __('غير محدد') }}</span>
                                <span class="contributor-show-chip"><i class="bi bi-telephone"></i>{{ $contributor->phone_num ?: __('لا يوجد رقم هاتف') }}</span>
                                <span class="contributor-show-chip {{ $contributor->is_board_member ? 'board' : '' }}"><i class="bi {{ $contributor->is_board_member ? 'bi-patch-check-fill' : 'bi-person-dash' }}"></i>{{ $contributor->is_board_member ? __('عضو مجلس إدارة') : __('ليس عضو مجلس إدارة') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="contributor-show-actions">
                    <a href="{{ route('contributors.edit', $contributor->id) }}" class="contributor-show-btn"><i class="bi bi-pencil-square"></i>{{ __('تعديل المساهم') }}</a>
                    <a href="{{ route('contributors.index') }}" class="contributor-show-btn-muted"><i class="bi bi-arrow-right-circle"></i>{{ __('العودة للقائمة') }}</a>
                    <form action="{{ route('contributors.destroy', $contributor->id) }}" method="POST" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا المساهم؟ هذا الإجراء لا يمكن التراجع عنه.') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="contributor-show-btn-danger"><i class="bi bi-trash3"></i>{{ __('حذف المساهم') }}</button>
                    </form>
                </div>
            </div>
        </section>

        <section class="contributor-show-stats">
            <div class="contributor-show-stat-card"><span class="contributor-show-stat-icon"><i class="bi bi-pie-chart-fill"></i></span><p class="contributor-show-stat-value">{{ number_format($contributor->share_count_cr ?? 0, 0) }}</p><p class="contributor-show-stat-label">{{ __('إجمالي الأسهم') }}</p></div>
            <div class="contributor-show-stat-card"><span class="contributor-show-stat-icon"><i class="bi bi-folder2-open"></i></span><p class="contributor-show-stat-value">{{ $documentsCount }}</p><p class="contributor-show-stat-label">{{ __('الوثائق المرفوعة') }}</p></div>
            <div class="contributor-show-stat-card"><span class="contributor-show-stat-icon"><i class="bi bi-shop-window"></i></span><p class="contributor-show-stat-value">{{ $sellSharesCount }}</p><p class="contributor-show-stat-label">{{ __('عروض البيع') }}</p></div>
            <div class="contributor-show-stat-card"><span class="contributor-show-stat-icon"><i class="bi bi-arrow-left-right"></i></span><p class="contributor-show-stat-value">{{ $shareTransactionsCount }}</p><p class="contributor-show-stat-label">{{ __('معاملات الأسهم') }}</p></div>
            <div class="contributor-show-stat-card"><span class="contributor-show-stat-icon"><i class="bi bi-cash-stack"></i></span><p class="contributor-show-stat-value">{{ $profitsCount }}</p><p class="contributor-show-stat-label">{{ __('توزيعات الأرباح') }}</p></div>
        </section>

        <div class="contributor-show-grid">
            <section class="contributor-show-card">
                <div class="contributor-show-card-head">
                    <h2 class="contributor-show-card-title"><i class="bi bi-person-vcard"></i>{{ __('المعلومات الأساسية') }}</h2>
                    <span class="contributor-show-card-note">{{ __('الهوية ووسائل التواصل والصفة الحالية.') }}</span>
                </div>
                <div class="contributor-detail-list">
                    <div class="contributor-detail-item"><span class="contributor-detail-label">{{ __('رقم المساهم') }}</span><div class="contributor-detail-value">#{{ $contributor->id }}</div></div>
                    <div class="contributor-detail-item"><span class="contributor-detail-label">{{ __('الاسم') }}</span><div class="contributor-detail-value">{{ $contributor->name ?? __('غير محدد') }}</div></div>
                    <div class="contributor-detail-item"><span class="contributor-detail-label">{{ __('رقم الهوية') }}</span><div class="contributor-detail-value">{{ $contributor->id_number ?? __('غير محدد') }}</div></div>
                    <div class="contributor-detail-item"><span class="contributor-detail-label">{{ __('رقم الهاتف') }}</span><div class="contributor-detail-value">{{ $contributor->phone_num ?: __('غير محدد') }}</div></div>
                    <div class="contributor-detail-item"><span class="contributor-detail-label">{{ __('المنصب') }}</span><div class="contributor-detail-value">{{ $contributor->position ?: __('غير محدد') }}</div></div>
                    <div class="contributor-detail-item"><span class="contributor-detail-label">{{ __('حالة مجلس الإدارة') }}</span><div class="contributor-detail-value">{{ $contributor->is_board_member ? __('عضو مجلس إدارة') : __('غير عضو مجلس إدارة') }}</div></div>
                </div>
            </section>

            <section class="contributor-show-card">
                <div class="contributor-show-card-head">
                    <h2 class="contributor-show-card-title"><i class="bi bi-bank"></i>{{ __('المعلومات المالية') }}</h2>
                    <span class="contributor-show-card-note">{{ __('الأسهم والبيانات البنكية ومعلومات الوصول المؤقتة.') }}</span>
                </div>
                <div class="contributor-detail-list">
                    <div class="contributor-detail-item"><span class="contributor-detail-label">{{ __('عدد الأسهم') }}</span><div class="contributor-detail-value">{{ number_format($contributor->share_count_cr ?? 0, 0) }}</div></div>
                    <div class="contributor-detail-item"><span class="contributor-detail-label">{{ __('اسم البنك') }}</span><div class="contributor-detail-value">{{ $contributor->bank_name ?: __('غير محدد') }}</div></div>
                    <div class="contributor-detail-item"><span class="contributor-detail-label">{{ __('رقم الحساب البنكي') }}</span><div class="contributor-detail-value">{{ $contributor->iban ?: __('غير محدد') }}</div></div>
                    <div class="contributor-detail-item"><span class="contributor-detail-label">{{ __('كلمة المرور المؤقتة') }}</span><div class="contributor-detail-value">@if ($contributor->temp_password)<span class="contributor-detail-code">{{ $contributor->temp_password }}</span>@else {{ __('غير محدد') }} @endif</div></div>
                    <div class="contributor-detail-item"><span class="contributor-detail-label">{{ __('تاريخ الإنشاء') }}</span><div class="contributor-detail-value">{{ $contributor->created_at->format('Y-m-d H:i') }}</div></div>
                    <div class="contributor-detail-item"><span class="contributor-detail-label">{{ __('آخر تحديث') }}</span><div class="contributor-detail-value">{{ $contributor->updated_at->format('Y-m-d H:i') }}</div></div>
                </div>
            </section>

            <section class="contributor-show-card full-width">
                <div class="contributor-show-card-head">
                    <h2 class="contributor-show-card-title"><i class="bi bi-person-check"></i>{{ __('الحساب المرتبط') }}</h2>
                    <span class="contributor-show-card-note">{{ __('بيانات المستخدم المرتبط بالمساهم إن وجدت.') }}</span>
                </div>
                @if ($contributor->user)
                    <div class="contributor-detail-list">
                        <div class="contributor-detail-item"><span class="contributor-detail-label">{{ __('اسم المستخدم') }}</span><div class="contributor-detail-value">{{ $contributor->user->name }}</div></div>
                        <div class="contributor-detail-item"><span class="contributor-detail-label">{{ __('البريد الإلكتروني') }}</span><div class="contributor-detail-value">{{ $contributor->user->email }}</div></div>
                        <div class="contributor-detail-item"><span class="contributor-detail-label">{{ __('تاريخ إنشاء الحساب') }}</span><div class="contributor-detail-value">{{ $contributor->user->created_at->format('Y-m-d H:i') }}</div></div>
                        <div class="contributor-detail-item"><span class="contributor-detail-label">{{ __('آخر دخول') }}</span><div class="contributor-detail-value">{{ $contributor->user->last_login_at ?? __('لم يسجل دخول بعد') }}</div></div>
                    </div>
                @else
                    <div class="contributor-empty-state"><i class="bi bi-person-x" style="font-size: 2rem; display: inline-block; margin-bottom: 10px; color: var(--primary-color);"></i><div>{{ __('لا يوجد حساب مستخدم مرتبط بهذا المساهم حتى الآن.') }}</div></div>
                @endif
            </section>

            <section class="contributor-show-card full-width">
                <div class="contributor-show-card-head">
                    <h2 class="contributor-show-card-title"><i class="bi bi-folder2-open"></i>{{ __('الوثائق والملفات') }}</h2>
                    <span class="contributor-show-card-note">{{ __('إدارة الملفات المرتبطة بالمساهم مع وصول مباشر للتحميل والمعاينة.') }}</span>
                </div>
                @if ($documentsCount > 0)
                    <div class="contributor-documents-grid">
                        @foreach ($contributor->documents as $document)
                            <article class="contributor-document-card">
                                <div class="contributor-document-head">
                                    <span class="contributor-document-icon"><i class="bi {{ $document->isImage() ? 'bi-image' : 'bi-file-earmark-text' }}"></i></span>
                                    <div>
                                        <h3 class="contributor-document-name">{{ $document->file_name }}</h3>
                                        <div class="contributor-document-meta">{{ $document->file_size_human }} · {{ $document->created_at->format('Y-m-d H:i') }}</div>
                                    </div>
                                </div>
                                <p class="contributor-document-desc">{{ $document->description ?: __('لا يوجد وصف مضاف لهذا الملف.') }}</p>
                                <div class="contributor-document-meta" style="margin-bottom: 14px;">{{ __('الرافع') }}: {{ optional($document->uploader)->name ?: __('غير محدد') }}</div>
                                <div class="contributor-document-actions">
                                    <a href="{{ route('contributors.documents.download', $document->id) }}" target="_blank"><i class="bi bi-download"></i>{{ __('تحميل') }}</a>
                                    @if ($document->isImage())
                                        <a href="{{ Storage::url($document->file_path) }}" target="_blank"><i class="bi bi-eye"></i>{{ __('عرض') }}</a>
                                    @endif
                                    <form action="{{ route('contributors.documents.delete', $document->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا الملف؟') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"><i class="bi bi-trash3"></i>{{ __('حذف') }}</button>
                                    </form>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="contributor-empty-state"><i class="bi bi-folder2" style="font-size: 2rem; display: inline-block; margin-bottom: 10px; color: var(--primary-color);"></i><div>{{ __('لا توجد وثائق مرفوعة لهذا المساهم حتى الآن.') }}</div></div>
                @endif
            </section>

            <section class="contributor-show-card full-width">
                <div class="contributor-show-card-head">
                    <h2 class="contributor-show-card-title"><i class="bi bi-activity"></i>{{ __('الأنشطة المرتبطة') }}</h2>
                    <span class="contributor-show-card-note">{{ __('روابط سريعة لمراجعة أهم العمليات المرتبطة بملف هذا المساهم.') }}</span>
                </div>
                <div class="contributor-activity-grid">
                    <article class="contributor-activity-card">
                        <div class="contributor-activity-top"><span class="contributor-activity-icon"><i class="bi bi-shop-window"></i></span><span class="contributor-activity-count">{{ $sellSharesCount }}</span></div>
                        <p class="contributor-activity-label">{{ __('عروض البيع المسجلة لهذا المساهم.') }}</p>
                        <a href="{{ route('sell-shares.index', ['user_id' => $contributor->id]) }}" class="contributor-activity-link"><i class="bi bi-arrow-left"></i>{{ __('عرض العروض') }}</a>
                    </article>
                    <article class="contributor-activity-card">
                        <div class="contributor-activity-top"><span class="contributor-activity-icon"><i class="bi bi-bag-check"></i></span><span class="contributor-activity-count">{{ $sharesPOsCount }}</span></div>
                        <p class="contributor-activity-label">{{ __('طلبات الشراء المرتبطة بالملف الحالي.') }}</p>
                        <a href="{{ route('shares-pos.index', ['user_id' => $contributor->id]) }}" class="contributor-activity-link"><i class="bi bi-arrow-left"></i>{{ __('عرض الطلبات') }}</a>
                    </article>
                    <article class="contributor-activity-card">
                        <div class="contributor-activity-top"><span class="contributor-activity-icon"><i class="bi bi-arrow-left-right"></i></span><span class="contributor-activity-count">{{ $shareTransactionsCount }}</span></div>
                        <p class="contributor-activity-label">{{ __('حركة نقل وتداول الأسهم الخاصة بالمساهم.') }}</p>
                        <a href="{{ route('share-trans-lines.index', ['contributor_id' => $contributor->id]) }}" class="contributor-activity-link"><i class="bi bi-arrow-left"></i>{{ __('عرض المعاملات') }}</a>
                    </article>
                    <article class="contributor-activity-card">
                        <div class="contributor-activity-top"><span class="contributor-activity-icon"><i class="bi bi-cash-coin"></i></span><span class="contributor-activity-count">{{ $profitsCount }}</span></div>
                        <p class="contributor-activity-label">{{ __('عمليات الأرباح والتوزيعات المرتبطة بالمساهم.') }}</p>
                        <a href="{{ route('users-profits.index', ['contributor_id' => $contributor->id]) }}" class="contributor-activity-link"><i class="bi bi-arrow-left"></i>{{ __('عرض التوزيعات') }}</a>
                    </article>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
