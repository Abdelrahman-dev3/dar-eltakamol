@php
    $isEdit = $isEdit ?? false;
    $document = $document ?? null;
    $meetings = $meetings ?? collect();
    $selectedMeetingId = old('meeting_id', $document?->meeting_id);
@endphp

<div class="doc-section">
    <h3 class="doc-section-title">
        <i class="bi bi-file-earmark-text"></i>
        {{ __('البيانات الأساسية') }}
    </h3>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group doc-field @error('name') has-error @enderror">
                <label for="name">
                    {{ $isEdit ? __('اسم الملف') : __('الاسم العام') }}
                    @if($isEdit)
                        <span class="text-danger">*</span>
                    @endif
                </label>
                <input type="text" name="name" id="name" class="form-control doc-input"
                    value="{{ old('name', $document->name ?? '') }}"
                    maxlength="255"
                    @if($isEdit) required @endif
                    placeholder="{{ $isEdit ? __('أدخل اسم الملف') : __('اختياري: اتركه فارغًا لاستخدام أسماء الملفات الأصلية') }}">
                <p class="doc-inline-note">
                    @if($isEdit)
                        {{ __('حدّث الاسم الظاهر للمستند داخل القوائم وصفحة العرض.') }}
                    @else
                        {{ __('إذا رفعت عدة ملفات وتركته فارغًا فسيتم استخدام اسم كل ملف تلقائيًا. وإذا كتبت اسمًا فسيُستخدم لكل الملفات المرفوعة في هذه الدفعة.') }}
                    @endif
                </p>
                @error('name')
                    <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group doc-field @error('meeting_id') has-error @enderror">
                <label for="meeting_id">{{ __('ربط باجتماع') }}</label>
                <select name="meeting_id" id="meeting_id" class="form-control doc-input">
                    <option value="">{{ __('-- بدون اجتماع --') }}</option>
                    @foreach($meetings as $meeting)
                        <option value="{{ $meeting->id }}" {{ (string) $selectedMeetingId === (string) $meeting->id ? 'selected' : '' }}>
                            {{ $meeting->name }} - {{ optional($meeting->date)->format('Y-m-d') }}
                        </option>
                    @endforeach
                </select>
                <p class="doc-inline-note">{{ __('يمكنك ربط المستند باجتماع محدد لتسهيل الرجوع إليه من الملفات المرتبطة بالاجتماعات.') }}</p>
                @error('meeting_id')
                    <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
</div>

@if($isEdit)
    <div class="doc-section">
        <h3 class="doc-section-title">
            <i class="bi bi-file-earmark-check"></i>
            {{ __('الملف الحالي') }}
        </h3>

        <article class="doc-current-file">
            <div class="doc-file-meta">
                <span class="doc-file-icon">
                    <i class="fa {{ $document->file_icon }}"></i>
                </span>
                <div>
                    <strong>{{ $document->original_filename }}</strong>
                    <div class="doc-current-file-text">
                        {{ __('الحجم') }}: {{ $document->file_size_human }}<br>
                        {{ __('آخر تحديث') }}: {{ $document->updated_at->format('Y-m-d H:i') }}
                    </div>
                </div>
            </div>

            <div class="doc-current-file-actions">
                <a href="{{ route('documents.download', $document) }}" target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-download"></i>
                    {{ __('تحميل الملف الحالي') }}
                </a>
            </div>
        </article>
    </div>

    <div class="doc-section">
        <h3 class="doc-section-title">
            <i class="bi bi-arrow-repeat"></i>
            {{ __('استبدال الملف') }}
        </h3>

        <div class="doc-upload-zone">
            <div class="form-group doc-field @error('file') has-error @enderror" style="margin-bottom: 0;">
                <label for="file">{{ __('ملف جديد اختياري') }}</label>
                <input type="file" name="file" id="file" class="form-control doc-input" data-doc-single-file
                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif,.zip,.rar,.7z">
                <p class="doc-inline-note">{{ __('اترك الحقل فارغًا إذا كنت تريد الاحتفاظ بالملف الحالي. الحد الأقصى 50 ميجابايت.') }}</p>
                @error('file')
                    <span class="help-block">{{ $message }}</span>
                @enderror
                <div class="doc-files-list" id="docSingleFilePreview" aria-live="polite"></div>
            </div>
        </div>
    </div>
@else
    <div class="doc-section">
        <h3 class="doc-section-title">
            <i class="bi bi-cloud-arrow-up"></i>
            {{ __('رفع الملفات') }}
        </h3>

        <div class="doc-upload-zone">
            <div class="form-group doc-field @error('files') has-error @enderror" style="margin-bottom: 0;">
                <label for="files">{{ __('الملفات') }} <span class="text-danger">*</span></label>
                <input type="file" name="files[]" id="files" class="form-control doc-input" multiple required data-doc-files
                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif,.zip,.rar,.7z">
                <p class="doc-inline-note">{{ __('يمكنك اختيار أكثر من ملف دفعة واحدة. الأنواع المسموحة تشمل المستندات والصور والأرشيف.') }}</p>
                @error('files')
                    <span class="help-block">{{ $message }}</span>
                @enderror
                @error('files.*')
                    <span class="help-block">{{ $message }}</span>
                @enderror
                <div class="doc-files-list" id="docFilesPreview" aria-live="polite"></div>
            </div>
        </div>
    </div>
@endif
