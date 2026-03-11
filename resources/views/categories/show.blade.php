@extends('layouts.app')

@section('title', $category->isCompany() ? __('تفاصيل الشركة') : __('تفاصيل الإدارة'))

@section('content')
@include('categories.partials.theme')

<div class="membership-page">
    <div class="container membership-shell">
        <section class="membership-hero">
            <span class="membership-kicker">
                <i class="fa {{ $category->isCompany() ? 'fa-building-o' : 'fa-briefcase' }}"></i>
                {{ $category->level_label }}
            </span>
            <h1 class="membership-title">{{ $category->name }}</h1>
            <p class="membership-subtitle">
                {{ $category->isCompany() ? 'هذه هي القمة التنظيمية للعضوية. من هنا يمكنك متابعة الإدارات التابعة وعدد أعضائها وصلاحياتها.' : 'هذه الإدارة مرتبطة مباشرة بالشركة، وتعرض هنا الصلاحيات المفعلة والأعضاء المرتبطين بها.' }}
            </p>
            <div class="membership-actions">
                <a href="{{ route('categories.edit', $category) }}" class="membership-btn">
                    <i class="fa fa-pencil"></i>
                    تعديل
                </a>
                <a href="{{ route('categories.index') }}" class="membership-btn-secondary">
                    <i class="fa fa-arrow-right"></i>
                    رجوع
                </a>
            </div>
        </section>

        <section class="membership-stats">
            <article class="membership-stat">
                <div class="membership-stat-label">المستوى</div>
                <div class="membership-stat-value" style="font-size: 24px;">{{ $category->level_label }}</div>
            </article>
            <article class="membership-stat">
                <div class="membership-stat-label">الأعضاء</div>
                <div class="membership-stat-value">{{ $category->users->count() }}</div>
            </article>
            <article class="membership-stat">
                <div class="membership-stat-label">الصلاحيات</div>
                <div class="membership-stat-value">{{ $category->permissions->count() }}</div>
            </article>
            <article class="membership-stat">
                <div class="membership-stat-label">{{ $category->isCompany() ? 'الإدارات التابعة' : 'الشركة الأم' }}</div>
                <div class="membership-stat-value" style="font-size: 24px;">
                    {{ $category->isCompany() ? $category->children->count() : ($category->parent?->name ?? '-') }}
                </div>
            </article>
        </section>

        <section class="membership-section" style="padding: 24px;">
            <div class="membership-section-head">
                <div>
                    <h2 class="membership-section-title">بطاقة المعلومات</h2>
                    <div class="membership-card-subtitle">تفاصيل مختصرة تساعد على قراءة الحالة التنظيمية بسرعة.</div>
                </div>
            </div>

            <div class="membership-details">
                <div class="membership-detail">
                    <div class="membership-detail-label">الاسم</div>
                    <div class="membership-detail-value">{{ $category->name }}</div>
                </div>
                <div class="membership-detail">
                    <div class="membership-detail-label">تابع لـ</div>
                    <div class="membership-detail-value">{{ $category->parent?->name ?? 'السجل الرئيسي' }}</div>
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
                        <h2 class="membership-section-title">الإدارات التابعة</h2>
                        <div class="membership-card-subtitle">كل إدارة هنا تمثل مستوى مستقل في الصلاحيات وربط الأعضاء.</div>
                    </div>
                </div>

                @if($category->children->isNotEmpty())
                    <div class="membership-list">
                        @foreach($category->children as $department)
                            <article class="membership-list-item" style="animation-delay: {{ 0.08 + ($loop->index * 0.05) }}s;">
                                <div class="membership-list-top">
                                    <div>
                                        <h3 class="membership-list-name">{{ $department->name }}</h3>
                                        <div class="membership-list-sub">إدارة تابعة للشركة ويمكن ربط الأعضاء والصلاحيات بها مباشرة.</div>
                                    </div>
                                    <a href="{{ route('categories.show', $department) }}" class="membership-btn-muted">
                                        <i class="fa fa-arrow-left"></i>
                                        فتح الإدارة
                                    </a>
                                </div>
                                <div class="membership-chip-row">
                                    <span class="membership-chip"><i class="fa fa-users"></i> {{ $department->users->count() }} عضو</span>
                                    <span class="membership-chip"><i class="fa fa-key"></i> {{ $department->permissions->count() }} صلاحية</span>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="membership-empty">لا توجد إدارات تابعة لهذه الشركة حتى الآن.</div>
                @endif
            </section>
        @else
            <div class="membership-columns">
                <section class="membership-section" style="padding: 24px;">
                    <div class="membership-section-head">
                        <div>
                            <h2 class="membership-section-title">الصلاحيات المرتبطة</h2>
                            <div class="membership-card-subtitle">الصلاحيات المفعلة لهذه الإدارة حالياً.</div>
                        </div>
                    </div>

                    @if($category->permissions->isNotEmpty())
                        <div class="membership-list">
                            @foreach($category->permissions as $permission)
                                <article class="membership-list-item" style="animation-delay: {{ 0.08 + ($loop->index * 0.05) }}s;">
                                    <div class="membership-list-name">{{ $permission->name }}</div>
                                    <div class="membership-list-sub"><code>{{ $permission->slug }}</code></div>
                                    <div class="membership-chip-row">
                                        <span class="membership-chip"><i class="fa fa-cube"></i> {{ $permission->module ?? 'عام' }}</span>
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
                            <h2 class="membership-section-title">أعضاء الإدارة</h2>
                            <div class="membership-card-subtitle">المستخدمون المرتبطون بهذه الإدارة والمساهم المرتبط بكل مستخدم إن وجد.</div>
                        </div>
                    </div>

                    @if($category->users->isNotEmpty())
                        <div class="membership-list">
                            @foreach($category->users as $user)
                                <article class="membership-list-item" style="animation-delay: {{ 0.08 + ($loop->index * 0.05) }}s;">
                                    <div class="membership-list-name">{{ $user->name }}</div>
                                    <div class="membership-list-sub">{{ $user->email }}</div>
                                    <div class="membership-chip-row">
                                        <span class="membership-chip"><i class="fa fa-phone"></i> {{ $user->phone ?? 'غير محدد' }}</span>
                                        <span class="membership-chip"><i class="fa fa-user-circle-o"></i> {{ optional($user->contributor)->name ?? 'غير مرتبط بمساهم' }}</span>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="membership-empty">لا يوجد أعضاء مربوطون بهذه الإدارة.</div>
                    @endif
                </section>
            </div>
        @endif
    </div>
</div>
@endsection
