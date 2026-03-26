@php
    $isEdit = $isEdit ?? false;
    $circular = $circular ?? null;
    $meetings = $meetings ?? collect();
    $selectedMeetingId = old('meeting_id', $circular?->meeting_id);
@endphp

<div class="cir-section">
    <h3 class="cir-section-title">
        <i class="bi bi-megaphone"></i>
        {{ __('البيانات الأساسية') }}
    </h3>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group cir-field @error('name') has-error @enderror">
                <label for="name">
                    {{ $isEdit ? __('اسم التعميم') : __('الاسم العام') }}
                    @if($isEdit)
                        <span class="text-danger">*</span>
                    @endif
                </label>
                <input type="text" name="name" id="name" class="form-control cir-input"
                    value="{{ old('name', $circular->name ?? '') }}"
                    maxlength="255"
                    @if($isEdit) required @endif
                    placeholder="{{ $isEdit ? __('أدخل اسم التعميم') : __('اختياري: اتركه فارغًا لاستخدام اسم الملف الأصلي') }}">
                <p class="cir-inline-note">
                    @if($isEdit)
                        {{ __('حدّث الاسم المعروض للتعميم داخل القائمة وصفحة العرض مع الاحتفاظ بوضوح الملف المرتبط.') }}
                    @else
                        {{ __('إذا رفعت عدة ملفات وتركت الاسم فارغًا فسيتم استخدام اسم كل ملف تلقائيًا. وإذا كتبت اسمًا فسيُستخدم لكل الملفات في هذه الدفعة.') }}
                    @endif
                </p>
                @error('name')
                    <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group cir-field @error('meeting_id') has-error @enderror">
                <label for="meeting_id">{{ __('ربط باجتماع') }}</label>
                <select name="meeting_id" id="meeting_id" class="form-control cir-input">
                    <option value="">{{ __('-- بدون اجتماع --') }}</option>
                    @foreach($meetings as $meeting)
                        <option value="{{ $meeting->id }}" {{ (string) $selectedMeetingId === (string) $meeting->id ? 'selected' : '' }}>
                            {{ $meeting->name }} - {{ optional($meeting->date)->format('Y-m-d') }}
                        </option>
                    @endforeach
                </select>
                <p class="cir-inline-note">{{ __('يمكنك ربط التعميم باجتماع محدد لتسهيل الرجوع إليه من الملفات أو الاجتماعات المرتبطة.') }}</p>
                @error('meeting_id')
                    <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
</div>

@if($isEdit)
    <div class="cir-section">
        <h3 class="cir-section-title">
            <i class="bi bi-file-earmark-check"></i>
            {{ __('الملف الحالي') }}
        </h3>

        <article class="cir-current-file">
            <div class="cir-file-meta">
                <span class="cir-file-icon">
                    <i class="fa {{ $circular->file_icon }}"></i>
                </span>
                <div>
                    <strong>{{ $circular->original_filename }}</strong>
                    <div class="cir-current-file-text">
                        {{ __('الحجم') }}: {{ $circular->file_size_human }}<br>
                        {{ __('آخر تحديث') }}: {{ $circular->updated_at->format('Y-m-d H:i') }}
                    </div>
                </div>
            </div>

            <div class="cir-current-file-actions">
                <a href="{{ route('circulars.download', $circular) }}" target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-download"></i>
                    {{ __('تحميل الملف الحالي') }}
                </a>
            </div>
        </article>
    </div>

    <div class="cir-section">
        <h3 class="cir-section-title">
            <i class="bi bi-arrow-repeat"></i>
            {{ __('استبدال الملف') }}
        </h3>

        <div class="cir-upload-zone">
            <div class="form-group cir-field @error('file') has-error @enderror" style="margin-bottom: 0;">
                <label for="file">{{ __('ملف جديد اختياري') }}</label>
                <input type="file" name="file" id="file" class="form-control cir-input" data-cir-single-file
                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif,.zip,.rar,.7z">
                <p class="cir-inline-note">{{ __('اترك الحقل فارغًا إذا كنت تريد الاحتفاظ بالملف الحالي. الحد الأقصى 50 ميجابايت.') }}</p>
                @error('file')
                    <span class="help-block">{{ $message }}</span>
                @enderror
                <div class="cir-files-list" id="cirSingleFilePreview" aria-live="polite"></div>
            </div>
        </div>
    </div>
@else
    <div class="cir-section">
        <h3 class="cir-section-title">
            <i class="bi bi-cloud-arrow-up"></i>
            {{ __('رفع التعميمات') }}
        </h3>

        <div class="cir-upload-zone">
            <div class="form-group cir-field @error('files') has-error @enderror" style="margin-bottom: 0;">
                <label for="files">{{ __('الملفات') }} <span class="text-danger">*</span></label>
                <input type="file" name="files[]" id="files" class="form-control cir-input" multiple required data-cir-files
                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif,.zip,.rar,.7z">
                <p class="cir-inline-note">{{ __('يمكنك اختيار أكثر من ملف دفعة واحدة. الأنواع المدعومة تشمل المستندات والصور والأرشيف.') }}</p>
                @error('files')
                    <span class="help-block">{{ $message }}</span>
                @enderror
                @error('files.*')
                    <span class="help-block">{{ $message }}</span>
                @enderror
                <div class="cir-files-list" id="cirFilesPreview" aria-live="polite"></div>
            </div>
        </div>
    </div>
@endif
