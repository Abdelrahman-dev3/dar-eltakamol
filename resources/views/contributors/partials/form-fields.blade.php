@php
    $isEdit = $isEdit ?? false;
    $contributor = $contributor ?? null;
    $companies = $companies ?? collect();
    $departments = $departments ?? collect();
    $currentProfilePicture = $contributor && $contributor->profile_picture ? $contributor->profile_picture_url : null;
    $currentInitials = $contributor ? $contributor->initials : 'م';
    $selectedDepartmentIds = old('department_ids', $contributor?->departments?->pluck('id')->toArray() ?? []);
    $selectedCompanyId = old('company_id', $contributor?->primary_company?->id);
@endphp

<div class="contributor-section">
    <h3 class="contributor-section-title">
        <i class="bi bi-person-vcard"></i>
        {{ __('البيانات الأساسية') }}
    </h3>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group contributor-field @error('id_number') has-error @enderror">
                <label for="id_number">{{ __('رقم الهوية') }} <span class="text-danger">*</span></label>
                <input type="text" name="id_number" id="id_number" class="form-control contributor-input"
                    value="{{ old('id_number', $contributor->id_number ?? '') }}" required maxlength="10"
                    placeholder="{{ __('أدخل رقم الهوية') }}">
                @error('id_number')
                    <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group contributor-field @error('name') has-error @enderror">
                <label for="name">{{ __('الاسم') }} <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control contributor-input"
                    value="{{ old('name', $contributor->name ?? '') }}" required maxlength="100"
                    placeholder="{{ __('أدخل اسم المساهم') }}">
                @error('name')
                    <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group contributor-field @error('phone_num') has-error @enderror">
                <label for="phone_num">{{ __('رقم الهاتف') }}</label>
                <input type="text" name="phone_num" id="phone_num" class="form-control contributor-input"
                    value="{{ old('phone_num', $contributor->phone_num ?? '') }}" maxlength="15"
                    placeholder="{{ __('أدخل رقم الهاتف') }}">
                @error('phone_num')
                    <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group contributor-field @error('position') has-error @enderror">
                <label for="position">{{ __('المنصب') }}</label>
                <input type="text" name="position" id="position" class="form-control contributor-input"
                    value="{{ old('position', $contributor->position ?? '') }}" maxlength="100"
                    placeholder="{{ __('أدخل المنصب أو الصفة') }}">
                @error('position')
                    <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="contributor-section">
    <h3 class="contributor-section-title">
        <i class="bi bi-shield-lock"></i>
        {{ __('الوصول والملكية') }}
    </h3>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group contributor-field @error('temp_password') has-error @enderror">
                <label for="temp_password">{{ __('كلمة المرور المؤقتة') }}</label>
                <input type="text" name="temp_password" id="temp_password" class="form-control contributor-input"
                    value="{{ old('temp_password', $contributor->temp_password ?? '') }}" maxlength="10"
                    placeholder="{{ __('أدخل كلمة مرور مؤقتة') }}">
                <p class="contributor-inline-note">{{ __('يمكن ترك الحقل فارغًا أو النقر عليه لتوليد كلمة مرور سريعة.') }}</p>
                @error('temp_password')
                    <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group contributor-field @error('share_count_cr') has-error @enderror">
                <label for="share_count_cr">{{ __('عدد الأسهم') }}</label>
                <input type="number" name="share_count_cr" id="share_count_cr" class="form-control contributor-input"
                    value="{{ old('share_count_cr', $contributor->share_count_cr ?? '') }}" min="0" step="0.01"
                    placeholder="{{ __('أدخل عدد الأسهم') }}">
                @error('share_count_cr')
                    <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-12">
            <label>{{ __('حالة العضوية') }}</label>
            <div class="contributor-toggle">
                <input type="checkbox" name="is_board_member" id="is_board_member" value="1"
                    {{ old('is_board_member', $contributor->is_board_member ?? false) ? 'checked' : '' }}>
                <div>
                    <strong>{{ __('عضو مجلس إدارة') }}</strong>
                    <span>{{ __('فعّل هذا الخيار إذا كان المساهم ضمن مجلس الإدارة أو له صفة إشرافية.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="contributor-section">
    <h3 class="contributor-section-title">
        <i class="bi bi-bank"></i>
        {{ __('البيانات البنكية') }}
    </h3>
    <div class="row">
        <div class="col-md-7">
            <div class="form-group contributor-field @error('iban') has-error @enderror">
                <label for="iban">{{ __('رقم الحساب البنكي (IBAN)') }}</label>
                <input type="text" name="iban" id="iban" class="form-control contributor-input"
                    value="{{ old('iban', $contributor->iban ?? '') }}" maxlength="24"
                    placeholder="{{ __('أدخل رقم الحساب البنكي') }}">
                @error('iban')
                    <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group contributor-field @error('bank_name') has-error @enderror">
                <label for="bank_name">{{ __('اسم البنك') }}</label>
                <input type="text" name="bank_name" id="bank_name" class="form-control contributor-input"
                    value="{{ old('bank_name', $contributor->bank_name ?? '') }}" maxlength="15"
                    placeholder="{{ __('أدخل اسم البنك') }}">
                @error('bank_name')
                    <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="contributor-section">
    <h3 class="contributor-section-title">
        <i class="bi bi-images"></i>
        {{ __('الصورة والوثائق') }}
    </h3>
    <div class="contributor-upload-grid">
        <div class="contributor-upload-preview">
            @if ($currentProfilePicture)
                <img src="{{ $currentProfilePicture }}" alt="{{ $contributor->name }}" data-profile-preview>
            @else
                <div class="contributor-avatar-fallback" data-profile-fallback>{{ $currentInitials }}</div>
                <img src="" alt="{{ __('معاينة الصورة') }}" data-profile-preview style="display: none;">
            @endif
        </div>

        <div class="contributor-upload-card">
            <div class="form-group contributor-field @error('profile_picture') has-error @enderror">
                <label for="profile_picture">{{ __('الصورة الشخصية') }}</label>
                <input type="file" name="profile_picture" id="profile_picture" class="form-control contributor-input" accept="image/*">
                <p class="contributor-inline-note">{{ __('الصيغ المقبولة: JPG, PNG, GIF وبحد أقصى 2MB.') }}</p>
                @error('profile_picture')
                    <span class="help-block">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group contributor-field">
                <label for="documents">{{ __('الوثائق والملفات') }}</label>
                <input type="file" name="documents[]" id="documents" class="form-control contributor-input" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx">
                <p class="contributor-inline-note">{{ __('يمكن رفع عدة ملفات بصيغ الصور وPDF وWord وExcel بحد أقصى 10MB لكل ملف.') }}</p>
                @error('documents.*')
                    <span class="help-block text-danger">{{ $message }}</span>
                @enderror
                <div id="file-list" class="contributor-files-list" aria-live="polite"></div>
            </div>
        </div>
    </div>
</div>

@if ($isEdit)
    <div class="contributor-section">
        <h3 class="contributor-section-title">
            <i class="bi bi-journal-text"></i>
            {{ __('أسباب التعديل') }}
        </h3>
        <div class="form-group contributor-field @error('line_notes') has-error @enderror" style="margin-bottom: 0;">
            <label for="line_notes">{{ __('سبب تعديل البيانات') }} <span class="text-danger">*</span></label>
            <textarea name="line_notes" id="line_notes" class="form-control contributor-textarea" required
                placeholder="{{ __('اكتب سبب التعديل الذي قمت به') }}">{{ old('line_notes') }}</textarea>
            @error('line_notes')
                <span class="help-block">{{ $message }}</span>
            @enderror
        </div>
    </div>
@endif
