@extends('layouts.app')

@section('title', __('العضوية'))

@section('content')
@php
    $companiesCount = $categories->getCollection()->whereNull('parent_id')->count();
    $departmentsCount = $categories->getCollection()->whereNotNull('parent_id')->count();
    $membersCount = $categories->getCollection()->sum('users_count');
    $permissionsCount = $categories->getCollection()->sum('permissions_count');
@endphp

@include('categories.partials.theme')

<div class="membership-page">
    <div class="container-fluid membership-shell">
        <section class="membership-hero">
            <span class="membership-kicker">
                <i class="fa fa-sitemap"></i>
                هيكلة العضوية
            </span>
            <h1 class="membership-title">الشركة، الإدارات، ثم الأعضاء</h1>
            <div class="membership-actions">
                <a href="{{ route('categories.create') }}" class="membership-btn">
                    <i class="fa fa-plus"></i>
                    إضافة شركة أو إدارة
                </a>
            </div>
        </section>

        @if(session('success'))
            <div class="membership-alert info">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="membership-alert warning">{{ session('error') }}</div>
        @endif

        <section class="membership-stats">
            <article class="membership-stat" style="animation-delay: 0.05s;">
                <div class="membership-stat-label">الشركات في الصفحة</div>
                <div class="membership-stat-value">{{ number_format($companiesCount) }}</div>
            </article>
            <article class="membership-stat" style="animation-delay: 0.12s;">
                <div class="membership-stat-label">الإدارات في الصفحة</div>
                <div class="membership-stat-value">{{ number_format($departmentsCount) }}</div>
            </article>
            <article class="membership-stat" style="animation-delay: 0.19s;">
                <div class="membership-stat-label">الأعضاء المرتبطون</div>
                <div class="membership-stat-value">{{ number_format($membersCount) }}</div>
            </article>
            <article class="membership-stat" style="animation-delay: 0.26s;">
                <div class="membership-stat-label">إجمالي الصلاحيات المربوطة</div>
                <div class="membership-stat-value">{{ number_format($permissionsCount) }}</div>
            </article>
        </section>

        @if($categories->isEmpty())
            <div class="membership-empty">
                لا توجد بيانات عضوية حالياً. ابدأ بإنشاء الشركة، ثم أضف الإدارات داخلها.
            </div>
        @else
            <section class="membership-grid">
                @foreach($categories as $category)
                    <article class="membership-card" style="animation-delay: {{ 0.06 + ($loop->index * 0.05) }}s;">
                        <div class="membership-card-head">
                            <div>
                                <span class="membership-badge {{ $category->isCompany() ? 'company' : 'department' }}">
                                    <i class="fa {{ $category->isCompany() ? 'fa-building-o' : 'fa-briefcase' }}"></i>
                                    {{ $category->level_label }}
                                </span>
                                <h3 class="membership-card-title">{{ $category->name }}</h3>
                                <div class="membership-card-subtitle">
                                    {{ $category->parent?->name ?? 'السجل الرئيسي في الهيكل' }}
                                </div>
                            </div>
                            <div class="membership-card-actions">
                                <a href="{{ route('categories.show', $category) }}" class="membership-icon-btn" title="عرض">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('categories.edit', $category) }}" class="membership-icon-btn" title="تعديل">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="membership-icon-btn danger" title="حذف">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="membership-meta">
                            <div class="membership-meta-item">
                                <strong>{{ $category->isCompany() ? $category->children_count : 0 }}</strong>
                                <span>إدارات</span>
                            </div>
                            <div class="membership-meta-item">
                                <strong>{{ $category->users_count }}</strong>
                                <span>أعضاء</span>
                            </div>
                            <div class="membership-meta-item">
                                <strong>{{ $category->permissions_count }}</strong>
                                <span>صلاحيات</span>
                            </div>
                        </div>

                        <div class="membership-chip-row">
                            <span class="membership-chip">
                                <i class="fa fa-link"></i>
                                {{ $category->isCompany() ? 'يمكن إضافة إدارات تابعة لها' : 'ترتبط بصلاحيات وأعضاء' }}
                            </span>
                            <span class="membership-chip">
                                <i class="fa fa-clock-o"></i>
                                {{ $category->created_at->format('Y-m-d') }}
                            </span>
                        </div>
                    </article>
                @endforeach
            </section>

            <div class="membership-pagination">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
