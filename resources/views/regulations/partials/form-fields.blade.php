@php
    $isEdit = $isEdit ?? false;
    $regulation = $regulation ?? null;
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
