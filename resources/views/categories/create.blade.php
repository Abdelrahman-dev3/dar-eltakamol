@extends('layouts.app')

@section('title', __('إضافة شركة أو إدارة'))

@section('content')
@include('categories.partials.theme')

@php
    $selectedKind = old('kind', $selectedKind ?? 'company');
    $selectedCompanyId = old('parent_id', $selectedCompanyId ?? null);
@endphp

<div class="membership-page">
    <div class="container membership-shell">
        <section class="membership-hero">
            <span class="membership-kicker">
                <i class="bi bi-stars"></i>
                بناء الهيكل
            </span>
            <h1 class="membership-title">إضافة شركة أو إدارة</h1>
            <p class="membership-subtitle">
                ابدأ بالشركات كمستوى أول، ثم أضف الإدارات تحت كل شركة. المساهمون يرتبطون لاحقًا بالإدارات التابعة لنفس الشركة.
            </p>
            <div class="membership-actions">
                <a href="{{ route('categories.index') }}" class="membership-btn-secondary">
                    <i class="bi bi-arrow-right-circle"></i>
                    العودة إلى العضوية
                </a>
            </div>
        </section>

        <section class="membership-panel">
            <div class="membership-panel-head">
                <h2 class="membership-section-title">بيانات السجل</h2>
            </div>
            <div class="membership-panel-body">
                <form action="{{ route('categories.store') }}" method="POST" class="membership-form-grid" data-category-form>
                    @csrf

                    <div class="membership-field">
                        <label for="kind">نوع السجل <span class="text-danger">*</span></label>
                        <select name="kind" id="kind" class="membership-select" required data-category-kind>
                            <option value="company" {{ $selectedKind === 'company' ? 'selected' : '' }}>شركة</option>
                            <option value="department" {{ $selectedKind === 'department' ? 'selected' : '' }}>إدارة</option>
                        </select>
                    </div>

                    <div class="membership-field">
                        <label for="name">الاسم <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="membership-input"
                               value="{{ old('name') }}" required maxlength="255"
                               placeholder="مثال: إدارة التسويق">
                        @error('name')
                            <span class="membership-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="membership-field" data-department-only style="{{ $selectedKind === 'department' ? '' : 'display:none;' }}">
                        <label for="parent_id">الشركة التابعة لها الإدارة <span class="text-danger">*</span></label>
                        <select name="parent_id" id="parent_id" class="membership-select">
                            <option value="">اختر الشركة</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ (string) $selectedCompanyId === (string) $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="membership-help">
                            سيتم إنشاء الإدارة داخل الشركة المختارة فقط، ولا يمكن إنشاء مستوى أعمق من الإدارة.
                        </span>
                        @error('parent_id')
                            <span class="membership-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="membership-field" data-department-only style="{{ $selectedKind === 'department' ? '' : 'display:none;' }}">
                        <label for="permission_ids">الصلاحيات الخاصة بهذه الإدارة</label>
                            <select name="permission_ids[]" id="permission_ids" class="membership-select" multiple size="8">
                                @foreach($permissions as $permission)
                                    <option value="{{ $permission->id }}" {{ in_array($permission->id, old('permission_ids', [])) ? 'selected' : '' }}>
                                        {{ $permission->display_name }}{{ $permission->module ? ' - ' . $permission->module_display : '' }}
                                    </option>
                                @endforeach
                            </select>
                        <span class="membership-help">
                            أي مساهم يرتبط بهذه الإدارة ويملك حساب مستخدم مرتبطًا به سيرث نفس صلاحيات هذه الإدارة تلقائيًا.
                        </span>
                    </div>

                    <div class="membership-note" data-company-only style="{{ $selectedKind === 'company' ? '' : 'display:none;' }}">
                        سيتم إنشاء الشركة كمستوى أول داخل العضويات، وبعدها يمكنك إضافة أكثر من إدارة وربط المساهمين بهذه الإدارات.
                    </div>

                    @if($companies->isEmpty())
                        <div class="membership-alert warning">
                            لا توجد شركات حتى الآن. يمكنك إنشاء شركة الآن، ثم ستتمكن من إضافة الإدارات التابعة لها.
                        </div>
                    @endif

                    <div class="membership-actions-bar">
                        <button type="submit" class="membership-btn">
                            <i class="bi bi-check2-circle"></i>
                            حفظ
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const kindField = document.querySelector('[data-category-kind]');
        const departmentBlocks = Array.from(document.querySelectorAll('[data-department-only]'));
        const companyBlocks = Array.from(document.querySelectorAll('[data-company-only]'));

        if (!kindField) {
            return;
        }

        function toggleBlocks() {
            const isDepartment = kindField.value === 'department';

            departmentBlocks.forEach(function (block) {
                block.style.display = isDepartment ? '' : 'none';
            });

            companyBlocks.forEach(function (block) {
                block.style.display = isDepartment ? 'none' : '';
            });
        }

        kindField.addEventListener('change', toggleBlocks);
        toggleBlocks();
    });
</script>
@endpush
