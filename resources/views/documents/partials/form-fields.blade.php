@php
    $isEdit = $isEdit ?? false;
    $document = $document ?? null;
    $meetings = $meetings ?? collect();
    $users = $users ?? collect();
    $audienceScopes = $audienceScopes ?? ['manual' => 'اختيار مستخدمين محددين'];
    $committeeOptions = $committeeOptions ?? [];
    $companies = $companies ?? collect();
    $departments = $departments ?? collect();
    $selectedMeetingId = old('meeting_id', $document?->meeting_id);
    $selectedRecipientUsers = collect(old(
        'recipient_users',
        $document ? $document->recipients->pluck('id')->all() : []
    ))->map(fn ($id) => (string) $id)->all();
    $selectedAudienceScope = old('audience_scope', 'manual');
    $selectedAudienceCommittee = old('audience_committee');
    $selectedAudienceCategory = old('audience_category_id');
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

<div class="doc-section">
    <h3 class="doc-section-title">
        <i class="bi bi-people"></i>
        {{ __('الجمهور المستهدف') }}
    </h3>

    <div class="form-group doc-field @error('audience_scope') has-error @enderror">
        <label for="doc_audience_scope">{{ __('الملف موجه لمن؟') }}</label>
        <select name="audience_scope" id="doc_audience_scope" class="form-control doc-input" data-audience-scope>
            @foreach($audienceScopes as $value => $label)
                <option value="{{ $value }}" {{ $selectedAudienceScope === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <p class="doc-inline-note">{{ __('اختر الجمهور المطلوب، وسيتم حفظ المستخدمين المطابقين مع الملف عند الحفظ.') }}</p>
        @error('audience_scope')
            <span class="help-block">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group doc-field @error('audience_committee') has-error @enderror" data-audience-panel="committee">
        <label for="doc_audience_committee">{{ __('اللجنة') }}</label>
        <select name="audience_committee" id="doc_audience_committee" class="form-control doc-input">
            <option value="">{{ __('اختر اللجنة') }}</option>
            @foreach($committeeOptions as $committee)
                <option value="{{ $committee }}" {{ $selectedAudienceCommittee === $committee ? 'selected' : '' }}>{{ $committee }}</option>
            @endforeach
        </select>
        @error('audience_committee')
            <span class="help-block">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group doc-field @error('audience_category_id') has-error @enderror" data-audience-panel="company">
        <label for="doc_audience_company">{{ __('الشركة أو العضوية الرئيسية') }}</label>
        <select name="audience_category_id" id="doc_audience_company" class="form-control doc-input">
            <option value="">{{ __('اختر الشركة أو العضوية') }}</option>
            @foreach($companies as $company)
                <option value="{{ $company->id }}" {{ (string) $selectedAudienceCategory === (string) $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
            @endforeach
        </select>
        @error('audience_category_id')
            <span class="help-block">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group doc-field @error('audience_category_id') has-error @enderror" data-audience-panel="department">
        <label for="doc_audience_department">{{ __('الإدارة أو التصنيف الفرعي') }}</label>
        <select name="audience_category_id" id="doc_audience_department" class="form-control doc-input">
            <option value="">{{ __('اختر الإدارة أو التصنيف') }}</option>
            @foreach($departments as $department)
                <option value="{{ $department->id }}" {{ (string) $selectedAudienceCategory === (string) $department->id ? 'selected' : '' }}>
                    {{ $department->full_name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group doc-field @error('recipient_users') has-error @enderror" data-audience-panel="manual">
        <label for="doc_recipient_users">{{ __('المستخدمون المحددون') }}</label>
        <select name="recipient_users[]" id="doc_recipient_users" class="form-control doc-input" multiple>
            @forelse($users as $user)
                <option value="{{ $user->id }}" {{ in_array((string) $user->id, $selectedRecipientUsers, true) ? 'selected' : '' }}>
                    {{ $user->name }} ({{ $user->email }})
                </option>
            @empty
                <option value="" disabled>{{ __('لا يوجد مستخدمون متاحون') }}</option>
            @endforelse
        </select>
        <p class="doc-inline-note">{{ __('يستخدم هذا الحقل فقط عند اختيار "اختيار مستخدمين محددين".') }}</p>
        @error('recipient_users')
            <span class="help-block">{{ $message }}</span>
        @enderror
        @error('recipient_users.*')
            <span class="help-block">{{ $message }}</span>
        @enderror
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
