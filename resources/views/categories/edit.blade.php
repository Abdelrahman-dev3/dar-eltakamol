@extends('layouts.app')

@section('title', $category->isCompany() ? __('تعديل الشركة') : __('تعديل الإدارة'))

@section('content')
@include('categories.partials.theme')

<div class="membership-page">
    <div class="container membership-shell">
        <section class="membership-hero">
            <span class="membership-kicker">
                <i class="bi bi-pencil-square"></i>
                تحديث العضوية
            </span>
            <h1 class="membership-title">{{ $category->isCompany() ? 'تعديل الشركة' : 'تعديل الإدارة' }}</h1>
            <p class="membership-subtitle">
                {{ $category->isCompany()
                    ? 'يمكنك تحديث اسم الشركة بصفتها المستوى الأول داخل الهيكل التنظيمي.'
                    : 'يمكنك تحديث اسم الإدارة أو نقلها إلى شركة أخرى، مع ضبط الصلاحيات المرتبطة بها.' }}
            </p>
            <div class="membership-actions">
                <a href="{{ route('categories.show', $category) }}" class="membership-btn-secondary">
                    <i class="bi bi-eye-fill"></i>
                    عرض التفاصيل
                </a>
                <a href="{{ route('categories.index') }}" class="membership-btn-secondary">
                    <i class="bi bi-arrow-right-circle"></i>
                    رجوع
                </a>
            </div>
        </section>

        <section class="membership-panel">
            <div class="membership-panel-head">
                <h2 class="membership-section-title">{{ $category->isCompany() ? 'بيانات الشركة' : 'بيانات الإدارة' }}</h2>
            </div>
            <div class="membership-panel-body">
                <form action="{{ route('categories.update', $category) }}" method="POST" class="membership-form-grid">
                    @csrf
                    @method('PUT')

                    <div class="membership-field">
                        <label for="name">{{ $category->isCompany() ? 'اسم الشركة' : 'اسم الإدارة' }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="membership-input"
                               value="{{ old('name', $category->name) }}" required maxlength="255">
                        @error('name')
                            <span class="membership-error">{{ $message }}</span>
                        @enderror
                    </div>

                    @if($category->isDepartment())
                        <div class="membership-field">
                            <label for="parent_id">الشركة التابعة لها الإدارة <span class="text-danger">*</span></label>
                            <select name="parent_id" id="parent_id" class="membership-select" required>
                                <option value="">اختر الشركة</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}"
                                        {{ (string) old('parent_id', $category->parent_id) === (string) $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <span class="membership-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="membership-field">
                            <label for="permission_ids">الصلاحيات الخاصة بهذه الإدارة</label>
                            <select name="permission_ids[]" id="permission_ids" class="membership-select" multiple size="8">
                                @foreach($permissions as $permission)
                                    <option value="{{ $permission->id }}"
                                        {{ in_array($permission->id, old('permission_ids', $category->permissions->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $permission->display_name }}{{ $permission->module ? ' - ' . $permission->module_display : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="membership-help">سيتم تطبيق هذه الصلاحيات على أعضاء ومساهمي هذه الإدارة فقط.</span>
                        </div>
                    @else
                        <div class="membership-note">
                            تظل الشركة في المستوى الأول، وتبقى الإدارات والمساهمون مرتبطة بها بشكل غير مباشر من خلال الإدارات التابعة لها.
                        </div>
                    @endif

                    <div class="membership-actions-bar">
                        <button type="submit" class="membership-btn">
                            <i class="bi bi-check2-circle"></i>
                            تحديث
                        </button>
                        <a href="{{ route('categories.index') }}" class="membership-btn-muted">
                            <i class="bi bi-x-circle"></i>
                            إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection
