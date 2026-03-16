@extends('layouts.app')

@section('title', __('العضوية'))

@section('content')
@php
    $collection = $categories->getCollection();
    $companiesCount = $collection->count();
    $departmentsCount = $collection->sum('children_count');
    $contributorsCount = $collection->sum(fn ($company) => $company->children->sum('contributors_count'));
    $permissionsCount = $collection->sum(fn ($company) => $company->children->sum('permissions_count'));
@endphp

@include('categories.partials.theme')

<div class="membership-page">
    <div class="container-fluid membership-shell">
        <section class="membership-hero">
            <span class="membership-kicker">
                <i class="bi bi-diagram-3-fill"></i>
                هيكلة العضوية
            </span>
            <h1 class="membership-title">الشركات</h1>
            <div class="membership-actions">
                <a href="{{ route('categories.create') }}" class="membership-btn">
                    <i class="bi bi-plus-circle-fill"></i>
                    إضافة شركة
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
                <div class="membership-stat-label">المساهمون المرتبطون</div>
                <div class="membership-stat-value">{{ number_format($contributorsCount) }}</div>
            </article>
            <article class="membership-stat" style="animation-delay: 0.26s;">
                <div class="membership-stat-label">إجمالي الصلاحيات المربوطة</div>
                <div class="membership-stat-value">{{ number_format($permissionsCount) }}</div>
            </article>
        </section>

        @if($categories->isEmpty())
            <div class="membership-empty">
                لا توجد شركات حالياً. ابدأ بإضافة شركة جديدة، ثم أضف الإدارات التابعة لها من داخل صفحة عرض الشركة نفسها.
            </div>
        @else
            <section class="membership-grid">
                @foreach($categories as $company)
                    @php
                        $contributorCount = $company->children->sum('contributors_count');
                        $companyPermissionsCount = $company->children->sum('permissions_count');
                    @endphp

                    <article class="membership-card" style="animation-delay: {{ 0.06 + ($loop->index * 0.05) }}s;">
                        <div class="membership-card-head">
                            <div>
                                <span class="membership-badge company">
                                    <i class="bi bi-buildings-fill"></i>
                                    شركة
                                </span>
                                <h3 class="membership-card-title">{{ $company->name }}</h3>
                                <span class="membership-chip">
                                    <i class="bi bi-clock-history"></i>
                                    {{ $company->created_at->format('Y-m-d') }}
                                </span>
                                <div class="membership-chip-row">
                                </div>
                            </div>
                            <div class="membership-card-actions">
                                <a href="{{ route('categories.show', $company) }}" class="membership-icon-btn" title="عرض">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="{{ route('categories.edit', $company) }}" class="membership-icon-btn" title="تعديل">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('categories.destroy', $company) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="membership-icon-btn danger" title="حذف">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="membership-meta">
                            <div class="membership-meta-item">
                                <strong>{{ $company->children_count }}</strong>
                                <span>إدارات</span>
                            </div>
                            <div class="membership-meta-item">
                                <strong>{{ $contributorCount }}</strong>
                                <span>مساهمون</span>
                            </div>
                            <div class="membership-meta-item">
                                <strong>{{ $companyPermissionsCount }}</strong>
                                <span>صلاحيات</span>
                            </div>
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
