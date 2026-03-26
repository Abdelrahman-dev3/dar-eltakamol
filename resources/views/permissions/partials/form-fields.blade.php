@php
    $isEdit = $isEdit ?? false;
    $permissionModel = $permission ?? null;
    $modules = $modules ?? [];
    $departments = $departments ?? collect();
    $selectedDepartmentIds = collect(old('department_ids', $permissionModel?->departments?->pluck('id')->all() ?? []))
        ->map(fn ($value) => (string) $value)
        ->all();
    $currentCode = old('name', $permissionModel?->name ?? '');
    $currentDisplayName = old('name')
        ? __('سيتم توليد الاسم العربي تلقائيًا بعد الحفظ بناءً على هذا الكود.')
        : ($permissionModel?->display_name ?? __('اكتب الاسم البرمجي للصلاحية ليظهر للمشرف بشكل عربي واضح.'));
@endphp

<div class="permf-section">
    <h3 class="permf-section-title">
        <i class="bi bi-key-fill"></i>
        {{ __('بيانات الصلاحية') }}
    </h3>

    <div class="permf-fields-grid">
        <div class="permf-field">
            <label for="name">{{ __('الاسم البرمجي للصلاحية') }} <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="permf-input"
                value="{{ $currentCode }}"
                required maxlength="255"
                placeholder="bookings.view">
            <span class="permf-help">{{ __('مثال: bookings.view أو meetings.attachments.download') }}</span>
            @error('name')
                <p class="permf-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="permf-field">
            <label for="module">{{ __('الوحدة') }}</label>
            <select name="module" id="module" class="permf-select">
                <option value="">{{ __('اختر الوحدة') }}</option>
                @foreach($modules as $key => $value)
                    <option value="{{ $key }}" {{ old('module', $permissionModel?->module) == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
            <span class="permf-help">{{ __('استخدم الوحدة لتنظيم الصلاحيات داخل القوائم وشاشات الإدارة.') }}</span>
            @error('module')
                <p class="permf-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="permf-field">
            <label for="slug">{{ __('المعرّف التقني') }}</label>
            <input type="text" name="slug" id="slug" class="permf-input"
                value="{{ old('slug', $permissionModel?->slug) }}"
                maxlength="255"
                placeholder="bookings.view">
            <span class="permf-help">{{ __('اختياري في البنية الحالية إذا لم يكن الجدول يحفظ slug بشكل مستقل.') }}</span>
            @error('slug')
                <p class="permf-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="permf-preview">
            <span class="permf-preview-label">{{ __('المعاينة الحالية') }}</span>
            <div class="permf-preview-value">{{ $currentDisplayName }}</div>
            @if($currentCode)
                <code class="permf-preview-code">{{ $currentCode }}</code>
            @endif
        </div>

        <div class="permf-field full-width">
            <label for="description">{{ __('الوصف') }}</label>
            <textarea name="description" id="description" class="permf-textarea" rows="4"
                placeholder="{{ __('أضف وصفًا مختصرًا يوضح متى تُستخدم هذه الصلاحية أو لماذا أُنشئت.') }}">{{ old('description', $permissionModel?->description) }}</textarea>
            <span class="permf-help">{{ __('هذا الوصف يظهر في بعض الشاشات الإدارية لمساعدة المشرفين على فهم الغرض من الصلاحية.') }}</span>
            @error('description')
                <p class="permf-error">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

<div class="permf-section">
    <h3 class="permf-section-title">
        <i class="bi bi-diagram-3-fill"></i>
        {{ __('الإدارات المرتبطة') }}
    </h3>

    <div class="permf-departments">
        <div class="permf-departments-toolbar">
            <div class="permf-search">
                <i class="bi bi-search"></i>
                <input type="search" class="permf-input" data-permission-department-search placeholder="{{ __('ابحث باسم الإدارة أو الشركة...') }}">
            </div>
            <span class="permf-chip">
                <i class="bi bi-building"></i>
                {{ count($selectedDepartmentIds) }} {{ __('إدارة محددة') }}
            </span>
        </div>

        @error('department_ids')
            <p class="permf-error">{{ $message }}</p>
        @enderror
        @error('department_ids.*')
            <p class="permf-error">{{ $message }}</p>
        @enderror

        @if($departments->isNotEmpty())
            <div class="permf-department-grid">
                @foreach($departments as $department)
                    @php
                        $searchText = mb_strtolower($department->name . ' ' . ($department->parent?->name ?? '') . ' ' . $department->full_name);
                    @endphp
                    <label class="permf-department-option" data-department-option data-search="{{ $searchText }}">
                        <input type="checkbox" name="department_ids[]" value="{{ $department->id }}"
                            {{ in_array((string) $department->id, $selectedDepartmentIds, true) ? 'checked' : '' }}>
                        <span>
                            <span class="permf-department-title">{{ $department->name }}</span>
                            <span class="permf-department-subtitle">{{ $department->parent?->name ? __('الشركة: ') . $department->parent->name : __('بدون شركة أم') }}</span>
                        </span>
                    </label>
                @endforeach
            </div>
        @else
            <p class="permf-inline-note">{{ __('لا توجد إدارات متاحة حاليًا للربط. أنشئ الإدارات أولًا من شاشة العضويات.') }}</p>
        @endif
    </div>
</div>
