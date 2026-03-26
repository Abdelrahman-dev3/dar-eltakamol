@extends('layouts.app')

@section('title', $category->isCompany() ? __('تفاصيل الشركة') : __('تفاصيل الإدارة'))

@section('content')
@include('categories.partials.theme')

@php
    $departmentContributorsCount = $category->isCompany()
        ? $category->children->sum(fn ($department) => $department->contributors->count())
        : $category->contributors->count();
    $linkedUsersCount = $category->isCompany()
        ? $category->children->sum(fn ($department) => $department->users->count())
        : $category->users->count();
@endphp

<div class="membership-page">
    <div class="container membership-shell">
        <section class="membership-hero">
            <span class="membership-kicker">
                <i class="bi {{ $category->isCompany() ? 'bi-buildings-fill' : 'bi-briefcase-fill' }}"></i>
                {{ $category->isCompany() ? 'شركة' : 'إدارة' }}
            </span>
            <h1 class="membership-title">{{ $category->name }}</h1>
            <p class="membership-subtitle">
                {{ $category->isCompany()
                    ? 'هذه الشركة تمثل المستوى الأول داخل العضويات، ومن هنا يمكنك إدارة الإدارات التابعة لها مع عزل كل شركة بإداراتها ومساهميها.'
                    : 'هذه الإدارة تابعة لشركة محددة، والمساهمون المرتبطون بها يرثون نفس صلاحياتها على حساباتهم المرتبطة.' }}
            </p>
            <div class="membership-actions">
                @if($category->isCompany())
                    <a href="{{ route('categories.create', ['kind' => 'department', 'company_id' => $category->id]) }}" class="membership-btn">
                        <i class="bi bi-plus-circle-fill"></i>
                        إضافة إدارة لهذه الشركة
                    </a>
                @else
                    <a href="{{ route('categories.edit', $category) }}" class="membership-btn">
                        <i class="bi bi-pencil-square"></i>
                        تعديل
                    </a>
                @endif
                <a href="{{ route('categories.index') }}" class="membership-btn-secondary">
                    <i class="bi bi-arrow-right-circle"></i>
                    رجوع
                </a>
            </div>
        </section>

        <section class="membership-stats">
            <article class="membership-stat">
                <div class="membership-stat-label">المساهمون</div>
                <div class="membership-stat-value">{{ $departmentContributorsCount }}</div>
            </article>
            <article class="membership-stat">
                <div class="membership-stat-label">{{ $category->isCompany() ? 'الإدارات التابعة' : 'الشركة الأم' }}</div>
                <div class="membership-stat-value" style="font-size: 1.8rem;">
                    {{ $category->isCompany() ? $category->children->count() : ($category->parent?->name ?? '-') }}
                </div>
            </article>
        </section>

        <section class="membership-section" style="padding: 24px;">
            <div class="membership-section-head">
                <div>
                    <h2 class="membership-section-title">بطاقة المعلومات</h2>
                </div>
            </div>

            <div class="membership-details">
                <div class="membership-detail">
                    <div class="membership-detail-label">الاسم</div>
                    <div class="membership-detail-value">{{ $category->name }}</div>
                </div>
                <div class="membership-detail">
                    <div class="membership-detail-label">يتبع لـ</div>
                    <div class="membership-detail-value">{{ $category->parent?->name ?? 'المستوى الرئيسي' }}</div>
                </div>
                <div class="membership-detail">
                    <div class="membership-detail-label">تاريخ الإنشاء</div>
                    <div class="membership-detail-value">{{ $category->created_at->format('Y-m-d') }}</div>
                </div>
                <div class="membership-detail">
                    <div class="membership-detail-label">آخر تحديث</div>
                    <div class="membership-detail-value">{{ $category->updated_at->format('Y-m-d') }}</div>
                </div>
            </div>
        </section>

        @if($category->isCompany())
            <section class="membership-section" style="padding: 24px; margin-top: 18px;">
                <div class="membership-section-head">
                    <div>
                        <h2 class="membership-section-title">إدارات الشركة</h2>
                    </div>
                    <a href="{{ route('categories.create', ['kind' => 'department', 'company_id' => $category->id]) }}" class="membership-btn">
                        <i class="bi bi-plus-circle-fill"></i>
                        إضافة إدارة
                    </a>
                </div>

                @if($category->children->isNotEmpty())
                    <div class="membership-list">
                        @foreach($category->children as $department)
                            <article class="membership-list-item" style="animation-delay: {{ 0.08 + ($loop->index * 0.05) }}s;">
                                <div class="membership-list-top">
                                    <div>
                                        <h3 class="membership-list-name">{{ $department->name }}</h3>
                                        <div class="membership-list-sub">إدارة تابعة لهذه الشركة فقط، وصلاحياتها تنتقل للمساهمين المرتبطين بها.</div>
                                    </div>
                                    <a href="{{ route('categories.show', $department) }}" class="membership-btn-muted">
                                        <i class="bi bi-arrow-left-circle"></i>
                                        فتح الإدارة
                                    </a>
                                </div>
                                <div class="membership-chip-row">
                                    <span class="membership-chip"><i class="bi bi-people-fill"></i> {{ $department->contributors->count() }} مساهم</span>
                                    <span class="membership-chip"><i class="bi bi-person-badge-fill"></i> {{ $department->users->count() }} مستخدم</span>
                                    <span class="membership-chip"><i class="bi bi-key-fill"></i> {{ $department->permissions->count() }} صلاحية</span>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="membership-empty">
                        لا توجد إدارات تابعة لهذه الشركة حتى الآن.
                        <div style="margin-top: 16px;">
                            <a href="{{ route('categories.create', ['kind' => 'department', 'company_id' => $category->id]) }}" class="membership-btn">
                                <i class="bi bi-plus-circle-fill"></i>
                                إنشاء أول إدارة
                            </a>
                        </div>
                    </div>
                @endif
            </section>
        @else
            <div class="membership-columns">
                <section class="membership-section" style="padding: 24px;">
                    <div class="membership-section-head">
                        <div>
                            <h2 class="membership-section-title">الصلاحيات المرتبطة</h2>
                            <div class="membership-card-subtitle">هذه الصلاحيات تنتقل لحسابات المساهمين المرتبطين بهذه الإدارة.</div>
                        </div>
                    </div>

                    @if($category->permissions->isNotEmpty())
                        <div class="membership-list">
                            @foreach($category->permissions as $permission)
                                <article class="membership-list-item" style="animation-delay: {{ 0.08 + ($loop->index * 0.05) }}s;">
                                    <div class="membership-list-name">{{ $permission->display_name }}</div>
                                    <div class="membership-list-sub">صلاحية مرتبطة بهذه الإدارة وتنتقل للحسابات التابعة لها.</div>
                                    <div class="membership-chip-row">
                                        <span class="membership-chip"><i class="bi bi-grid-fill"></i> {{ $permission->module_display }}</span>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="membership-empty">لا توجد صلاحيات مرتبطة بهذه الإدارة.</div>
                    @endif
                </section>

                <section class="membership-section" style="padding: 24px;">
                    <div class="membership-section-head">
                        <div>
                            <h2 class="membership-section-title">مساهمو الإدارة</h2>
                            <div class="membership-card-subtitle">يمكن أن ينتمي المساهم لأكثر من إدارة داخل نفس الشركة.</div>
                        </div>
                    </div>

                    @if($category->contributors->isNotEmpty())
                        <div class="membership-list">
                            @foreach($category->contributors as $contributor)
                                <article class="membership-list-item" style="animation-delay: {{ 0.08 + ($loop->index * 0.05) }}s;">
                                    <div class="membership-list-top">
                                        <div>
                                            <h3 class="membership-list-name">{{ $contributor->name }}</h3>
                                            <div class="membership-list-sub">{{ $contributor->id_number ?: 'بدون رقم هوية' }}</div>
                                        </div>
                                        <a href="{{ route('contributors.show', $contributor) }}" class="membership-btn-muted">
                                            <i class="bi bi-arrow-left-circle"></i>
                                            ملف المساهم
                                        </a>
                                    </div>
                                    <div class="membership-chip-row">
                                        <span class="membership-chip"><i class="bi bi-telephone-fill"></i> {{ $contributor->phone_num ?: 'غير محدد' }}</span>
                                        <span class="membership-chip"><i class="bi bi-pie-chart-fill"></i> {{ number_format($contributor->share_count_cr ?? 0, 0) }} سهم</span>
                                        @if($contributor->user)
                                            <span class="membership-chip"><i class="bi bi-person-badge-fill"></i> {{ $contributor->user->name }}</span>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="membership-empty">لا يوجد مساهمون مربوطون بهذه الإدارة.</div>
                    @endif
                </section>
            </div>

            <section class="membership-section" style="padding: 24px; margin-top: 18px;">
                <div class="membership-section-head">
                    <div>
                        <h2 class="membership-section-title">المستخدمون المرتبطون</h2>
                        <div class="membership-card-subtitle">حسابات النظام المرتبطة بهذه الإدارة، مع المساهم المرتبط إن وجد.</div>
                    </div>
                </div>

                @if($category->users->isNotEmpty())
                    <div class="membership-list">
                        @foreach($category->users as $user)
                            <article class="membership-list-item" style="animation-delay: {{ 0.08 + ($loop->index * 0.05) }}s;">
                                <div class="membership-list-name">{{ $user->name }}</div>
                                <div class="membership-list-sub">{{ $user->email }}</div>
                                <div class="membership-chip-row">
                                    <span class="membership-chip"><i class="bi bi-telephone-fill"></i> {{ $user->phone ?? 'غير محدد' }}</span>
                                    <span class="membership-chip"><i class="bi bi-person-badge-fill"></i> {{ optional($user->contributor)->name ?? 'غير مرتبط بمساهم' }}</span>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="membership-empty">لا يوجد مستخدمون مربوطون بهذه الإدارة.</div>
                @endif
            </section>
        @endif
    </div>
</div>
@endsection
