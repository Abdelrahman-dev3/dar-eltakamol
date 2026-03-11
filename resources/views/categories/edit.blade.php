@extends('layouts.app')

@section('title', $category->isCompany() ? __('تعديل الشركة') : __('تعديل الإدارة'))

@section('content')
@include('categories.partials.theme')

<div class="membership-page">
    <div class="container membership-shell">
        <section class="membership-hero">
            <span class="membership-kicker">
                <i class="fa fa-pencil-square-o"></i>
                تحديث العضوية
            </span>
            <h1 class="membership-title">{{ $category->isCompany() ? 'تعديل الشركة' : 'تعديل الإدارة' }}</h1>
            <p class="membership-subtitle">
                {{ $category->isCompany() ? 'يمكنك تعديل اسم الشركة فقط، لأنها تمثل المستوى الأعلى الثابت في الهيكل.' : 'يمكنك تعديل اسم الإدارة وتحديث الصلاحيات التي سيتحكم بها أعضاء هذه الإدارة.' }}
            </p>
            <div class="membership-actions">
                <a href="{{ route('categories.show', $category) }}" class="membership-btn-secondary">
                    <i class="fa fa-eye"></i>
                    عرض التفاصيل
                </a>
                <a href="{{ route('categories.index') }}" class="membership-btn-secondary">
                    <i class="fa fa-arrow-right"></i>
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
                            <label>الشركة</label>
                            <div class="membership-readonly">{{ $category->parent?->name }}</div>
                        </div>

                        <div class="membership-field">
                            <label for="permission_ids">الصلاحيات الخاصة بهذه الإدارة</label>
                            <select name="permission_ids[]" id="permission_ids" class="membership-select" multiple size="8">
                                @foreach($permissions as $permission)
                                    <option value="{{ $permission->id }}"
                                        {{ in_array($permission->id, old('permission_ids', $category->permissions->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $permission->name }}{{ $permission->module ? ' - ' . $permission->module : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('permission_ids')
                                <span class="membership-error">{{ $message }}</span>
                            @enderror
                        </div>
                    @else
                        <div class="membership-note">
                            الشركة هي المستوى الأول فقط، بينما المستخدمون والصلاحيات يرتبطون بالإدارات التابعة لها.
                        </div>
                    @endif

                    <div class="membership-actions-bar">
                        <button type="submit" class="membership-btn">
                            <i class="fa fa-save"></i>
                            تحديث
                        </button>
                        <a href="{{ route('categories.index') }}" class="membership-btn-muted">
                            <i class="fa fa-times"></i>
                            إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection
