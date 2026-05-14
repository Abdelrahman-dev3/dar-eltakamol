@php
    $isEdit = $isEdit ?? false;
    $regulation = $regulation ?? null;
    $users = $users ?? collect();
    $audienceScopes = $audienceScopes ?? ['manual' => 'اختيار مستخدمين محددين'];
    $committeeOptions = $committeeOptions ?? [];
    $companies = $companies ?? collect();
    $departments = $departments ?? collect();
    $selectedRecipientUsers = collect(old(
        'recipient_users',
        $regulation ? $regulation->recipients->pluck('id')->all() : []
    ))->map(fn ($id) => (string) $id)->all();
    $selectedAudienceScope = old('audience_scope', 'manual');
    $selectedAudienceCommittee = old('audience_committee');
    $selectedAudienceCategory = old('audience_category_id');
@endphp

<div class="reg-section">
    <h3 class="reg-section-title">
        <i class="bi bi-journal-richtext"></i>
        {{ __('البيانات الأساسية') }}
    </h3>

    <div class="form-group reg-field @error('name') has-error @enderror" style="margin-bottom: 0;">
        <label for="name">
            {{ $isEdit ? __('اسم اللائحة') : __('اسم اللائحة العام') }}
            @if($isEdit)
                <span class="text-danger">*</span>
            @endif
        </label>
        <input type="text" name="name" id="name" class="form-control reg-input"
            value="{{ old('name', $regulation->name ?? '') }}"
            maxlength="255"
            @if($isEdit) required @endif
            placeholder="{{ $isEdit ? __('أدخل اسم اللائحة') : __('اختياري: اتركه فارغًا لاستخدام اسم الملف تلقائيًا') }}">
        <p class="reg-inline-note">
            @if($isEdit)
                {{ __('حدّث اسم اللائحة كما سيظهر في القوائم وواجهة العرض.') }}
            @else
                {{ __('إذا رفعت عدة ملفات وتركت الاسم فارغًا فسيتم استخدام اسم كل ملف تلقائيًا. وإذا كتبت اسمًا فسيُستخدم لكل الملفات المرفوعة في هذه الدفعة.') }}
            @endif
        </p>
        @error('name')
            <span class="help-block">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="reg-section">
    <h3 class="reg-section-title">
        <i class="bi bi-people"></i>
        {{ __('الجمهور المستهدف') }}
    </h3>

    <div class="form-group reg-field @error('audience_scope') has-error @enderror">
        <label for="reg_audience_scope">{{ __('اللائحة موجهة لمن؟') }}</label>
        <select name="audience_scope" id="reg_audience_scope" class="form-control reg-input" data-audience-scope>
            @foreach($audienceScopes as $value => $label)
                <option value="{{ $value }}" {{ $selectedAudienceScope === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <p class="reg-inline-note">{{ __('اختر الجمهور المطلوب، وسيتم حفظ المستخدمين المطابقين مع اللائحة عند الحفظ.') }}</p>
        @error('audience_scope')
            <span class="help-block">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group reg-field @error('audience_committee') has-error @enderror" data-audience-panel="committee">
        <label for="reg_audience_committee">{{ __('اللجنة') }}</label>
        <select name="audience_committee" id="reg_audience_committee" class="form-control reg-input">
            <option value="">{{ __('اختر اللجنة') }}</option>
            @foreach($committeeOptions as $committee)
                <option value="{{ $committee }}" {{ $selectedAudienceCommittee === $committee ? 'selected' : '' }}>{{ $committee }}</option>
            @endforeach
        </select>
        @error('audience_committee')
            <span class="help-block">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group reg-field @error('audience_category_id') has-error @enderror" data-audience-panel="company">
        <label for="reg_audience_company">{{ __('الشركة أو العضوية الرئيسية') }}</label>
        <select name="audience_category_id" id="reg_audience_company" class="form-control reg-input">
            <option value="">{{ __('اختر الشركة أو العضوية') }}</option>
            @foreach($companies as $company)
                <option value="{{ $company->id }}" {{ (string) $selectedAudienceCategory === (string) $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
            @endforeach
        </select>
        @error('audience_category_id')
            <span class="help-block">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group reg-field @error('audience_category_id') has-error @enderror" data-audience-panel="department">
        <label for="reg_audience_department">{{ __('الإدارة أو التصنيف الفرعي') }}</label>
        <select name="audience_category_id" id="reg_audience_department" class="form-control reg-input">
            <option value="">{{ __('اختر الإدارة أو التصنيف') }}</option>
            @foreach($departments as $department)
                <option value="{{ $department->id }}" {{ (string) $selectedAudienceCategory === (string) $department->id ? 'selected' : '' }}>
                    {{ $department->full_name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group reg-field @error('recipient_users') has-error @enderror" data-audience-panel="manual">
        <label for="reg_recipient_users">{{ __('المستخدمون المحددون') }}</label>
        <select name="recipient_users[]" id="reg_recipient_users" class="form-control reg-input" multiple>
            @forelse($users as $user)
                <option value="{{ $user->id }}" {{ in_array((string) $user->id, $selectedRecipientUsers, true) ? 'selected' : '' }}>
                    {{ $user->name }} ({{ $user->email }})
                </option>
            @empty
                <option value="" disabled>{{ __('لا يوجد مستخدمون متاحون') }}</option>
            @endforelse
        </select>
        <p class="reg-inline-note">{{ __('يستخدم هذا الحقل فقط عند اختيار "اختيار مستخدمين محددين".') }}</p>
        @error('recipient_users')
            <span class="help-block">{{ $message }}</span>
        @enderror
        @error('recipient_users.*')
            <span class="help-block">{{ $message }}</span>
        @enderror
    </div>
</div>

@if($isEdit)
    <div class="reg-section">
        <h3 class="reg-section-title">
            <i class="bi bi-file-earmark-check"></i>
            {{ __('الملف الحالي') }}
        </h3>

        <article class="reg-current-file">
            <div class="reg-file-meta">
                <span class="reg-file-icon">
                    <i class="fa {{ $regulation->file_icon }}"></i>
                </span>
                <div>
                    <strong>{{ $regulation->original_filename }}</strong>
                    <div class="reg-current-file-text">
                        {{ __('الحجم') }}: {{ $regulation->file_size_human }}<br>
                        {{ __('آخر تحديث') }}: {{ $regulation->updated_at->format('Y-m-d H:i') }}
                    </div>
                </div>
            </div>

            <div class="reg-current-file-actions">
                <a href="{{ route('regulations.download', $regulation) }}" target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-download"></i>
                    {{ __('تحميل الملف الحالي') }}
                </a>
            </div>
        </article>
    </div>

    <div class="reg-section">
        <h3 class="reg-section-title">
            <i class="bi bi-arrow-repeat"></i>
            {{ __('استبدال الملف') }}
        </h3>

        <div class="reg-upload-zone">
            <div class="form-group reg-field @error('file') has-error @enderror" style="margin-bottom: 0;">
                <label for="file">{{ __('ملف جديد اختياري') }}</label>
                <input type="file" name="file" id="file" class="form-control reg-input" data-reg-single-file
                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif,.zip,.rar,.7z">
                <p class="reg-inline-note">{{ __('اترك الحقل فارغًا إذا كنت تريد الاحتفاظ بالملف الحالي. الحد الأقصى 50 ميجابايت.') }}</p>
                @error('file')
                    <span class="help-block">{{ $message }}</span>
                @enderror
                <div class="reg-files-list" id="regSingleFilePreview" aria-live="polite"></div>
            </div>
        </div>
    </div>
@else
    <div class="reg-section">
        <h3 class="reg-section-title">
            <i class="bi bi-cloud-arrow-up"></i>
            {{ __('رفع الملفات') }}
        </h3>

        <div class="reg-upload-zone">
            <div class="form-group reg-field @error('files') has-error @enderror" style="margin-bottom: 0;">
                <label for="files">{{ __('ملفات اللوائح') }} <span class="text-danger">*</span></label>
                <input type="file" name="files[]" id="files" class="form-control reg-input" multiple required data-reg-files
                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif,.zip,.rar,.7z">
                <p class="reg-inline-note">{{ __('يمكنك رفع أكثر من ملف دفعة واحدة. الأنواع المسموحة: PDF وWord وExcel وPowerPoint والصور والنصوص والأرشيف.') }}</p>
                @error('files')
                    <span class="help-block">{{ $message }}</span>
                @enderror
                @error('files.*')
                    <span class="help-block">{{ $message }}</span>
                @enderror
                <div class="reg-files-list" id="regFilesPreview" aria-live="polite"></div>
            </div>
        </div>
    </div>
@endif
