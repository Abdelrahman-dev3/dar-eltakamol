@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Storage;

    $documentsCount = $contributor->documents->count();
    $sellSharesCount = $contributor->sellShares->count();
    $sharesPOsCount = $contributor->sharesPOs->count();
    $shareTransactionsCount = $contributor->shareTransLines->count();
    $profitsCount = $contributor->userProfits->count();
    $hasProfilePicture = !empty($contributor->profile_picture);
    $companyNames = $contributor->departments->pluck('parent.name')->filter()->unique()->values();
    $departmentNames = $contributor->departments->pluck('name')->filter()->values();
    $managedCompanyNames = $contributor->managedCompanies->pluck('name')->filter()->values();
    $membershipLabels = collect($contributor->membership_labels);
@endphp

@section('title', __('عرض تفاصيل المساهم'))

@push('styles')
<style>
    .contributor-show-page { padding: 10px 0 30px; }
    .contributor-show-shell { display: flex; flex-direction: column; gap: 22px; }
    .contributor-show-hero,
    .contributor-show-card,
    .contributor-show-stat-card {
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.07);
    }
    .contributor-show-hero {
        padding: 28px;
        background:
            radial-gradient(circle at top right, rgba(196, 168, 90, 0.28), transparent 30%),
            linear-gradient(135deg, #fff8ed 0%, #ffffff 44%, #f5ecde 100%);
    }
    .contributor-show-hero-inner {
        display: grid;
        grid-template-columns: minmax(0, 1.6fr) minmax(280px, 0.9fr);
        gap: 20px;
        align-items: center;
    }
    .contributor-show-profile { display: flex; align-items: center; gap: 18px; flex-wrap: wrap; }
    .contributor-show-avatar {
        width: 108px; height: 108px; border-radius: 28px; overflow: hidden;
        display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, var(--primary-color), #d4b066);
        color: #fff; font-size: 2.5rem; font-weight: 900;
        box-shadow: 0 20px 36px rgba(170, 134, 63, 0.22);
    }
    .contributor-show-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .contributor-show-badge,
    .contributor-show-chip,
    .contributor-doc-chip {
        display: inline-flex; align-items: center; gap: 8px;
        border-radius: 999px; font-weight: 800;
    }
    .contributor-show-badge {
        margin-bottom: 14px; padding: 8px 14px;
        background: rgba(170, 134, 63, 0.12); color: var(--primary-color);
    }
    .contributor-show-title { margin: 0; font-size: clamp(2rem, 4vw, 3rem); font-weight: 900; color: var(--text-primary); }
    .contributor-show-meta { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 16px; }
    .contributor-show-chip {
        padding: 10px 14px;
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(170, 134, 63, 0.12);
        color: var(--text-primary);
    }
    .contributor-show-chip.board { background: rgba(5, 150, 105, 0.10); color: var(--success-color); border-color: rgba(5, 150, 105, 0.18); }
    .contributor-show-chip.committee { background: rgba(37, 99, 235, 0.10); color: #1d4ed8; border-color: rgba(37, 99, 235, 0.18); }
    .contributor-show-actions { display: flex; flex-direction: column; gap: 12px; }
    .contributor-show-btn,
    .contributor-show-btn-muted,
    .contributor-show-btn-danger {
        display: inline-flex; align-items: center; justify-content: center; gap: 10px;
        width: 100%; padding: 13px 18px; border-radius: 18px; text-decoration: none !important;
        border: 1px solid transparent; font-size: 1rem; font-weight: 800;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .contributor-show-btn { background: linear-gradient(135deg, var(--primary-color), #c49b48); color: #fff !important; box-shadow: 0 16px 28px rgba(170, 134, 63, 0.24); }
    .contributor-show-btn-muted { background: rgba(255, 255, 255, 0.9); color: var(--text-primary) !important; border-color: rgba(170, 134, 63, 0.14); }
    .contributor-show-btn-danger { background: rgba(220, 38, 38, 0.08); color: var(--danger-color) !important; border-color: rgba(220, 38, 38, 0.16); }
    .contributor-show-btn:hover,
    .contributor-show-btn-muted:hover,
    .contributor-show-btn-danger:hover { transform: translateY(-2px); }
    .contributor-show-stats { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 16px; }
    .contributor-show-stat-card { padding: 20px; }
    .contributor-show-stat-icon {
        width: 50px; height: 50px; border-radius: 16px; display: inline-flex; align-items: center; justify-content: center;
        background: rgba(170, 134, 63, 0.12); color: var(--primary-color); font-size: 1.3rem; margin-bottom: 14px;
    }
    .contributor-show-stat-value { margin: 0; font-size: 1.9rem; font-weight: 900; color: var(--text-primary); }
    .contributor-show-stat-label { margin: 6px 0 0; color: var(--text-secondary); font-weight: 700; }
    .contributor-show-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; }
    .contributor-show-card { padding: 24px; }
    .contributor-show-card.full-width { grid-column: 1 / -1; }
    .contributor-show-card-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 16px; }
    .contributor-show-card-title { display: flex; align-items: center; gap: 12px; margin: 0; font-size: 1.3rem; font-weight: 900; color: var(--text-primary); }
    .contributor-show-card-title i {
        width: 46px; height: 46px; border-radius: 16px; display: inline-flex; align-items: center; justify-content: center;
        background: rgba(170, 134, 63, 0.12); color: var(--primary-color);
    }
    .contributor-show-card-note { color: var(--text-secondary); font-size: 0.95rem; line-height: 1.8; }
    .contributor-detail-list { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
    .contributor-detail-item { padding: 16px; border-radius: 20px; background: rgba(248, 250, 252, 0.92); border: 1px solid rgba(170, 134, 63, 0.10); }
    .contributor-detail-label { display: block; margin-bottom: 8px; color: var(--text-secondary); font-size: 0.92rem; font-weight: 700; }
    .contributor-detail-value { color: var(--text-primary); font-size: 1rem; font-weight: 800; word-break: break-word; }
    .contributor-detail-code { display: inline-flex; padding: 6px 10px; border-radius: 12px; background: rgba(170, 134, 63, 0.10); color: var(--primary-color); }
    .contributor-documents-grid,
    .contributor-activity-grid { display: grid; gap: 16px; }
    .contributor-documents-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .contributor-activity-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
    .contributor-document-card,
    .contributor-activity-card {
        padding: 18px; border-radius: 22px; background: rgba(248, 250, 252, 0.94);
        border: 1px solid rgba(170, 134, 63, 0.10);
    }
    .contributor-document-head,
    .contributor-activity-top { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 12px; }
    .contributor-document-name { margin: 0; font-size: 1.02rem; font-weight: 800; color: var(--text-primary); }
    .contributor-document-meta,
    .contributor-document-desc,
    .contributor-activity-label { color: var(--text-secondary); line-height: 1.8; }
    .contributor-document-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 12px; }
    .contributor-document-actions a,
    .contributor-document-actions button,
    .contributor-activity-link {
        display: inline-flex; align-items: center; gap: 8px; text-decoration: none !important; font-weight: 800;
    }
    .contributor-document-actions a,
    .contributor-document-actions button {
        padding: 10px 14px; border-radius: 14px; border: 1px solid transparent;
    }
    .contributor-document-actions a { background: rgba(170, 134, 63, 0.10); color: var(--primary-color); }
    .contributor-document-actions button { background: rgba(220, 38, 38, 0.08); color: var(--danger-color); border-color: rgba(220, 38, 38, 0.12); }
    .contributor-activity-count { font-size: 2rem; font-weight: 900; color: var(--text-primary); }
    .contributor-activity-link { color: var(--primary-color); }
    .contributor-empty-state {
        padding: 24px; border-radius: 22px; text-align: center; color: var(--text-secondary);
        background: rgba(248, 250, 252, 0.9); border: 1px dashed rgba(170, 134, 63, 0.18);
    }
    @media (max-width: 1399px) {
        .contributor-show-stats { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .contributor-activity-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 1199px) {
        .contributor-show-hero-inner,
        .contributor-show-grid,
        .contributor-documents-grid { grid-template-columns: 1fr; }
        .contributor-show-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 767px) {
        .contributor-show-stats,
        .contributor-detail-list,
        .contributor-activity-grid { grid-template-columns: 1fr; }
        .contributor-show-hero,
        .contributor-show-card,
        .contributor-show-stat-card { padding: 20px; border-radius: 24px; }
    }
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
                            <div class="contributor-show-meta">
                                <span class="contributor-show-chip"><i class="bi bi-credit-card-2-front"></i>{{ __('رقم الهوية') }}: {{ $contributor->id_number ?? __('غير محدد') }}</span>
                                <span class="contributor-show-chip"><i class="bi bi-telephone"></i>{{ $contributor->phone_num ?: __('لا يوجد رقم هاتف') }}</span>
                                <span class="contributor-show-chip"><i class="bi bi-building"></i>{{ $companyNames->isNotEmpty() ? $companyNames->implode('، ') : __('بدون شركة مرتبطة') }}</span>
                                @forelse($membershipLabels as $membershipLabel)
                                    <span class="contributor-show-chip {{ $membershipLabel === \App\Models\Contributor::BOARD_MEMBERSHIP_LABEL ? 'board' : 'committee' }}"><i class="bi {{ $membershipLabel === \App\Models\Contributor::BOARD_MEMBERSHIP_LABEL ? 'bi-patch-check-fill' : 'bi-people-fill' }}"></i>{{ __($membershipLabel) }}</span>
                                @empty
                                    <span class="contributor-show-chip"><i class="bi bi-person-dash"></i>{{ __('لا توجد عضويات إشرافية') }}</span>
                                @endforelse
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
            <div class="contributor-show-stat-card"><span class="contributor-show-stat-icon"><i class="bi bi-diagram-3"></i></span><p class="contributor-show-stat-value">{{ $departmentNames->count() }}</p><p class="contributor-show-stat-label">{{ __('الإدارات المرتبط بها') }}</p></div>
            <div class="contributor-show-stat-card"><span class="contributor-show-stat-icon"><i class="bi bi-folder2-open"></i></span><p class="contributor-show-stat-value">{{ $documentsCount }}</p><p class="contributor-show-stat-label">{{ __('الوثائق المرفوعة') }}</p></div>
            <div class="contributor-show-stat-card"><span class="contributor-show-stat-icon"><i class="bi bi-arrow-left-right"></i></span><p class="contributor-show-stat-value">{{ $shareTransactionsCount }}</p><p class="contributor-show-stat-label">{{ __('معاملات الأسهم') }}</p></div>
            <div class="contributor-show-stat-card"><span class="contributor-show-stat-icon"><i class="bi bi-cash-stack"></i></span><p class="contributor-show-stat-value">{{ $profitsCount }}</p><p class="contributor-show-stat-label">{{ __('توزيعات الأرباح') }}</p></div>
        </section>

        <div class="contributor-show-grid">
            <section class="contributor-show-card">
                <div class="contributor-show-card-head">
                    <h2 class="contributor-show-card-title"><i class="bi bi-person-vcard"></i>{{ __('المعلومات الأساسية') }}</h2>
                    <span class="contributor-show-card-note">{{ __('الهوية ووسائل التواصل والانتماء التنظيمي الحالي.') }}</span>
                </div>
                <div class="contributor-detail-list">
                    <div class="contributor-detail-item"><span class="contributor-detail-label">{{ __('رقم المساهم') }}</span><div class="contributor-detail-value">#{{ $contributor->id }}</div></div>
                    <div class="contributor-detail-item"><span class="contributor-detail-label">{{ __('الاسم') }}</span><div class="contributor-detail-value">{{ $contributor->name ?? __('غير محدد') }}</div></div>
                    <div class="contributor-detail-item"><span class="contributor-detail-label">{{ __('رقم الهوية') }}</span><div class="contributor-detail-value">{{ $contributor->id_number ?? __('غير محدد') }}</div></div>
                    <div class="contributor-detail-item"><span class="contributor-detail-label">{{ __('رقم الهاتف') }}</span><div class="contributor-detail-value">{{ $contributor->phone_num ?: __('غير محدد') }}</div></div>
                    <div class="contributor-detail-item"><span class="contributor-detail-label">{{ __('المنصب') }}</span><div class="contributor-detail-value">{{ $contributor->position ?: __('غير محدد') }}</div></div>
                    <div class="contributor-detail-item"><span class="contributor-detail-label">{{ __('الشركة') }}</span><div class="contributor-detail-value">{{ $companyNames->isNotEmpty() ? $companyNames->implode('، ') : __('غير محددة') }}</div></div>
                    <div class="contributor-detail-item"><span class="contributor-detail-label">{{ __('الإدارات') }}</span><div class="contributor-detail-value">{{ $departmentNames->isNotEmpty() ? $departmentNames->implode('، ') : __('غير مرتبط بإدارات بعد') }}</div></div>
                    <div class="contributor-detail-item"><span class="contributor-detail-label">{{ __('الشركات التي يديرها') }}</span><div class="contributor-detail-value">{{ $managedCompanyNames->isNotEmpty() ? $managedCompanyNames->implode('، ') : __('لا يدير أي شركة حاليا') }}</div></div>
                    <div class="contributor-detail-item"><span class="contributor-detail-label">{{ __('العضويات الإشرافية') }}</span><div class="contributor-detail-value">{{ $membershipLabels->isNotEmpty() ? $membershipLabels->implode('، ') : __('لا توجد عضويات إشرافية') }}</div></div>
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
                                    <div>
                                        <h3 class="contributor-document-name">{{ $document->file_name }}</h3>
                                        <div class="contributor-document-meta">{{ $document->file_size_human }} · {{ $document->created_at->format('Y-m-d H:i') }}</div>
                                    </div>
                                    <span class="contributor-doc-chip">
                                        <i class="bi {{ $document->isImage() ? 'bi-image' : 'bi-file-earmark-text' }}"></i>
                                    </span>
                                </div>
                                <p class="contributor-document-desc">{{ $document->description ?: __('لا يوجد وصف مضاف لهذا الملف.') }}</p>
                                <div class="contributor-document-meta">{{ __('الرافع') }}: {{ optional($document->uploader)->name ?: __('غير محدد') }}</div>
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
                        <div class="contributor-activity-top"><span class="contributor-show-stat-icon"><i class="bi bi-shop-window"></i></span><span class="contributor-activity-count">{{ $sellSharesCount }}</span></div>
                        <p class="contributor-activity-label">{{ __('عروض البيع المسجلة لهذا المساهم.') }}</p>
                        <a href="{{ route('sell-shares.index', ['user_id' => $contributor->id]) }}" class="contributor-activity-link"><i class="bi bi-arrow-left"></i>{{ __('عرض العروض') }}</a>
                    </article>
                    <article class="contributor-activity-card">
                        <div class="contributor-activity-top"><span class="contributor-show-stat-icon"><i class="bi bi-bag-check"></i></span><span class="contributor-activity-count">{{ $sharesPOsCount }}</span></div>
                        <p class="contributor-activity-label">{{ __('طلبات الشراء المرتبطة بالملف الحالي.') }}</p>
                        <a href="{{ route('shares-pos.index', ['user_id' => $contributor->id]) }}" class="contributor-activity-link"><i class="bi bi-arrow-left"></i>{{ __('عرض الطلبات') }}</a>
                    </article>
                    <article class="contributor-activity-card">
                        <div class="contributor-activity-top"><span class="contributor-show-stat-icon"><i class="bi bi-arrow-left-right"></i></span><span class="contributor-activity-count">{{ $shareTransactionsCount }}</span></div>
                        <p class="contributor-activity-label">{{ __('حركة نقل وتداول الأسهم الخاصة بالمساهم.') }}</p>
                        <a href="{{ route('share-trans-lines.index', ['contributor_id' => $contributor->id]) }}" class="contributor-activity-link"><i class="bi bi-arrow-left"></i>{{ __('عرض المعاملات') }}</a>
                    </article>
                    <article class="contributor-activity-card">
                        <div class="contributor-activity-top"><span class="contributor-show-stat-icon"><i class="bi bi-cash-coin"></i></span><span class="contributor-activity-count">{{ $profitsCount }}</span></div>
                        <p class="contributor-activity-label">{{ __('عمليات الأرباح والتوزيعات المرتبطة بالمساهم.') }}</p>
                        <a href="{{ route('users-profits.index', ['contributor_id' => $contributor->id]) }}" class="contributor-activity-link"><i class="bi bi-arrow-left"></i>{{ __('عرض التوزيعات') }}</a>
                    </article>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
