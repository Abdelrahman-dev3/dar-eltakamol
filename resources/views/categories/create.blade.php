@extends('layouts.app')

@section('title', $company ? __('إضافة إدارة') : __('إنشاء الشركة'))

@section('content')
@include('categories.partials.theme')

<div class="membership-page">
    <div class="container membership-shell">
        <section class="membership-hero">
            <span class="membership-kicker">
                <i class="fa fa-magic"></i>
                بناء الهيكل
            </span>
            <h1 class="membership-title">{{ $company ? 'إضافة إدارة جديدة' : 'إنشاء الشركة الأساسية' }}</h1>
            <p class="membership-subtitle">
                {{ $company ? 'الإدارة سترتبط بالشركة الحالية مباشرة، ومن هنا يمكنك تحديد الصلاحيات التي ستتحكم في وصول أعضاء هذه الإدارة.' : 'ابدأ بإنشاء الشركة أولاً، وبعدها سيصبح بإمكانك بناء الإدارات داخلها وربط الصلاحيات بكل إدارة.' }}
            </p>
            <div class="membership-actions">
                <a href="{{ route('categories.index') }}" class="membership-btn-secondary">
                    <i class="fa fa-arrow-right"></i>
                    العودة إلى العضوية
                </a>
            </div>
        </section>

        <section class="membership-panel">
            <div class="membership-panel-head">
                <h2 class="membership-section-title">{{ $company ? 'بيانات الإدارة' : 'بيانات الشركة' }}</h2>
            </div>
            <div class="membership-panel-body">
                <form action="{{ route('categories.store') }}" method="POST" class="membership-form-grid">
                    @csrf

                    <div class="membership-field">
                        <label for="name">{{ $company ? 'اسم الإدارة' : 'اسم الشركة' }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="membership-input"
                               value="{{ old('name') }}" required maxlength="255"
                               placeholder="{{ $company ? 'مثال: إدارة شؤون المساهمين' : 'مثال: دار التكامل' }}">
                        @error('name')
                            <span class="membership-error">{{ $message }}</span>
                        @enderror
                    </div>

                    @if($company)
                        <input type="hidden" name="parent_id" value="{{ $company->id }}">

                        <div class="membership-field">
                            <label>الشركة</label>
                            <div class="membership-readonly">{{ $company->name }}</div>
                            <span class="membership-help">سيتم ربط الإدارة بهذه الشركة مباشرة دون إنشاء مستوى إضافي.</span>
                        </div>

                        <div class="membership-field">
                            <label for="permission_ids">الصلاحيات الخاصة بهذه الإدارة</label>
                            <select name="permission_ids[]" id="permission_ids" class="membership-select" multiple size="8">
                                @foreach($permissions as $permission)
                                    <option value="{{ $permission->id }}" {{ in_array($permission->id, old('permission_ids', [])) ? 'selected' : '' }}>
                                        {{ $permission->name }}{{ $permission->module ? ' - ' . $permission->module : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="membership-help">اختر الصلاحيات التي ستحكم وصول أعضاء هذه الإدارة داخل النظام.</span>
                            @error('permission_ids')
                                <span class="membership-error">{{ $message }}</span>
                            @enderror
                        </div>
                    @else
                        <div class="membership-note">
                            سيتم إنشاء الشركة الآن كمستوى أول. بعد الحفظ ستتمكن من إضافة الإدارات أسفلها مباشرة.
                        </div>
                    @endif

                    @error('parent_id')
                        <div class="membership-alert warning">{{ $message }}</div>
                    @enderror

                    <div class="membership-actions-bar">
                        <button type="submit" class="membership-btn">
                            <i class="fa fa-save"></i>
                            حفظ
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
