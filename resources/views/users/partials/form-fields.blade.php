@php
    $isEdit = $isEdit ?? false;
    $userModel = $user ?? null;
    $departments = $departments ?? collect();
    $permissions = $permissions ?? collect();
    $moduleLabels = $moduleLabels ?? [];
    $selectedDepartmentId = old('department_id', optional($userModel?->department)->id);
    $selectedPermissionIds = collect(old('permission_ids', $userModel?->permissions?->pluck('id')->all() ?? []))
        ->map(fn ($value) => (string) $value)
        ->all();
    $permissionGroups = $permissions->groupBy(fn ($permission) => $permission->module ?: 'general');
    $inheritedPermissions = $userModel?->inherited_permissions ?? collect();
@endphp

<div class="userf-section">
    <h3 class="userf-section-title">
        <i class="bi bi-person-vcard"></i>
        {{ __('البيانات الأساسية') }}
    </h3>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group userf-field @error('name') has-error @enderror">
                <label for="name">{{ __('الاسم') }} <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control userf-input"
                    value="{{ old('name', $userModel->name ?? '') }}"
                    required maxlength="255"
                    placeholder="{{ __('أدخل اسم المستخدم') }}">
                @error('name')
                    <p class="userf-help-block">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group userf-field @error('email') has-error @enderror">
                <label for="email">{{ __('البريد الإلكتروني') }} <span class="text-danger">*</span></label>
                <input type="email" name="email" id="email" class="form-control userf-input"
                    value="{{ old('email', $userModel->email ?? '') }}"
                    required maxlength="255"
                    placeholder="{{ __('name@example.com') }}">
                @error('email')
                    <p class="userf-help-block">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group userf-field @error('phone') has-error @enderror">
                <label for="phone">{{ __('رقم الهاتف') }}</label>
                <input type="text" name="phone" id="phone" class="form-control userf-input"
                    value="{{ old('phone', $userModel->phone ?? '') }}"
                    maxlength="15"
                    placeholder="{{ __('اختياري') }}">
                @error('phone')
                    <p class="userf-help-block">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group userf-field @error('id_number') has-error @enderror">
                <label for="id_number">{{ __('رقم الهوية') }}</label>
                <input type="text" name="id_number" id="id_number" class="form-control userf-input"
                    value="{{ old('id_number', $userModel->id_number ?? '') }}"
                    maxlength="20"
                    placeholder="{{ __('اختياري') }}">
                @error('id_number')
                    <p class="userf-help-block">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="userf-section">
    <h3 class="userf-section-title">
        <i class="bi bi-shield-lock"></i>
        {{ $isEdit ? __('تحديث كلمة المرور') : __('كلمة المرور') }}
    </h3>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group userf-field @error('password') has-error @enderror">
                <label for="password">
                    {{ $isEdit ? __('كلمة المرور الجديدة') : __('كلمة المرور') }}
                    @unless($isEdit)
                        <span class="text-danger">*</span>
                    @endunless
                </label>
                <input type="password" name="password" id="password" class="form-control userf-input"
                    @unless($isEdit) required @endunless
                    placeholder="{{ $isEdit ? __('اتركه فارغًا إن لم ترغب في التغيير') : __('8 أحرف على الأقل') }}">
                @error('password')
                    <p class="userf-help-block">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group userf-field">
                <label for="password_confirmation">
                    {{ $isEdit ? __('تأكيد كلمة المرور الجديدة') : __('تأكيد كلمة المرور') }}
                    @unless($isEdit)
                        <span class="text-danger">*</span>
                    @endunless
                </label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control userf-input"
                    @unless($isEdit) required @endunless
                    placeholder="{{ __('أعد إدخال كلمة المرور') }}">
            </div>
        </div>
    </div>
</div>

<div class="userf-section">
    <h3 class="userf-section-title">
        <i class="bi bi-diagram-3"></i>
        {{ __('الربط الإداري') }}
    </h3>

    <div class="form-group userf-field @error('department_id') has-error @enderror">
        <label for="department_id">{{ __('الإدارة') }}</label>
        <select name="department_id" id="department_id" class="form-control userf-input">
            <option value="">{{ __('-- اختر الإدارة --') }}</option>
            @foreach($departments as $department)
                <option value="{{ $department->id }}" {{ (string) $selectedDepartmentId === (string) $department->id ? 'selected' : '' }}>
                    {{ $department->name }}{{ $department->parent ? ' - ' . $department->parent->name : '' }}
                </option>
            @endforeach
        </select>
        @error('department_id')
            <p class="userf-help-block">{{ $message }}</p>
        @enderror
        @if($departments->isEmpty())
            <p class="userf-inline-note text-warning">{{ __('لا توجد إدارات حاليًا. أنشئ الشركة ثم الإدارة من صفحة العضوية أولًا.') }}</p>
        @elseif($isEdit && $userModel->contributor && $userModel->contributor->departments->isNotEmpty())
            <p class="userf-inline-note">{{ __('هذا المستخدم مرتبط بمساهم، لذا ستظل الإدارات الموروثة من ملف المساهم محفوظة له تلقائيًا.') }}</p>
        @else
            <p class="userf-inline-note">{{ __('يمكنك ربط المستخدم بإدارة واحدة، وستورث له صلاحيات الإدارة تلقائيًا من خلال صلاحيات العضوية الحالية.') }}</p>
        @endif
    </div>
</div>

<div class="userf-section">
    <h3 class="userf-section-title">
        <i class="bi bi-shield-check"></i>
        {{ __('الصلاحيات المباشرة للمستخدم') }}
    </h3>

    <div class="userf-permissions-toolbar">
        <div class="userf-permissions-search">
            <i class="bi bi-search"></i>
            <input type="search" class="form-control userf-input" data-user-permission-search placeholder="{{ __('ابحث داخل الصلاحيات أو الوحدات...') }}">
        </div>
        <span class="userf-chip">
            <i class="bi bi-key-fill"></i>
            {{ count($selectedPermissionIds) }} {{ __('صلاحية محددة') }}
        </span>
    </div>

    @error('permission_ids')
        <p class="userf-help-block">{{ $message }}</p>
    @enderror
    @error('permission_ids.*')
        <p class="userf-help-block">{{ $message }}</p>
    @enderror

    @if($permissionGroups->isNotEmpty())
        <div class="userf-permissions-grid">
            @foreach($permissionGroups as $module => $modulePermissions)
                @php
                    $moduleLabel = $moduleLabels[$module] ?? ($module ?: __('عام'));
                @endphp
                <article class="userf-permission-card" data-permission-card data-search="{{ mb_strtolower($moduleLabel . ' ' . $modulePermissions->pluck('name')->implode(' ') . ' ' . $modulePermissions->pluck('display_name')->implode(' ')) }}">
                    <div class="userf-permission-head">
                        <h4 class="userf-permission-title">{{ $moduleLabel }}</h4>
                        <span class="userf-permission-count">{{ $modulePermissions->count() }} {{ __('صلاحية') }}</span>
                    </div>

                    <div class="userf-permission-list">
                        @foreach($modulePermissions as $permission)
                            <label class="userf-permission-option">
                                <input type="checkbox" name="permission_ids[]" value="{{ $permission->id }}"
                                    {{ in_array((string) $permission->id, $selectedPermissionIds, true) ? 'checked' : '' }}>
                                <span>
                                    <span class="userf-permission-name">{{ $permission->display_name }}</span>
                                    <span class="userf-permission-meta">
                                        @if($permission->description)
                                            {{ $permission->description }}
                                        @endif
                                    </span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </article>
            @endforeach
        </div>
    @else
        <div class="userf-inherited-empty">{{ __('لا توجد صلاحيات معرفة في النظام بعد. يمكنك إضافتها من قسم الصلاحيات.') }}</div>
    @endif
</div>

<div class="userf-section">
    <h3 class="userf-section-title">
        <i class="bi bi-diagram-2-fill"></i>
        {{ __('الصلاحيات الموروثة') }}
    </h3>

    @if($inheritedPermissions->isNotEmpty())
        <div class="userf-inherited-list">
            @foreach($inheritedPermissions as $permission)
                <span class="userf-chip">
                    <i class="bi bi-arrow-repeat"></i>
                    {{ $permission->display_name }}
                </span>
            @endforeach
        </div>
        <p class="userf-inline-note">{{ __('هذه الصلاحيات تأتي من الإدارات المرتبطة بالمستخدم أو بالمساهم المرتبط به، وتُضاف تلقائيًا فوق الصلاحيات المباشرة المحددة هنا.') }}</p>
    @else
        <div class="userf-inherited-empty">{{ __('لا توجد صلاحيات موروثة حاليًا لهذا المستخدم.') }}</div>
    @endif
</div>
