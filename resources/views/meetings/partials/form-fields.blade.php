@php
    $isEdit = $isEdit ?? false;
    $meeting = $meeting ?? null;
    $users = $users ?? collect();
    $polls = $polls ?? collect();
    $selectedUserIds = old('user_ids', $meeting?->users?->pluck('id')->toArray() ?? []);
    $selectedPollIds = collect(old('poll_ids', $meeting?->polls?->pluck('id')->toArray() ?? []))
        ->map(fn ($id) => (string) $id)
        ->all();
    $attachmentDescriptions = old('attachment_descriptions', []);
    $attachmentRowsCount = max(1, count($attachmentDescriptions));
    $existingAttachments = $meeting?->attachments ?? collect();
    $dateValue = old('date', $meeting?->date?->format('Y-m-d H:i'));
    $audienceScopes = $audienceScopes ?? ['manual' => 'اختيار مستخدمين محددين'];
    $committeeOptions = $committeeOptions ?? [];
    $companies = $companies ?? collect();
    $departments = $departments ?? collect();
    $selectedAudienceScope = old('audience_scope', $meeting?->audience_scope ?? 'manual');
    $selectedAudienceCommittee = old('audience_committee', $meeting?->audience_committee);
    $selectedAudienceCategory = old('audience_category_id', $meeting?->audience_category_id);
@endphp

<div class="meeting-section">
    <h3 class="meeting-section-title">
        <span>
            <i class="bi bi-calendar-event"></i>
            {{ __('البيانات الأساسية') }}
        </span>
    </h3>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group meeting-field @error('name') has-error @enderror">
                <label for="name">{{ __('اسم الاجتماع') }} <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control meeting-input"
                    value="{{ old('name', $meeting->name ?? '') }}" required maxlength="255"
                    placeholder="{{ __('أدخل اسم الاجتماع') }}">
                @error('name')
                    <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group meeting-field @error('date') has-error @enderror">
                <label for="date">{{ __('تاريخ ووقت الاجتماع') }} <span class="text-danger">*</span></label>
                <div class="meeting-date-input-wrap">
                    <input type="text" name="date" id="date" class="form-control meeting-input meeting-date-picker"
                        value="{{ $dateValue }}" required autocomplete="off" dir="ltr"
                        placeholder="{{ __('اختر تاريخ ووقت الاجتماع') }}" data-date-picker>
                    <button type="button" class="meeting-date-trigger" data-date-trigger aria-label="{{ __('فتح التقويم') }}">
                        <i class="bi bi-calendar3"></i>
                    </button>
                </div>
                <p class="meeting-inline-note">{{ __('اختر التاريخ والوقت من تقويم تفاعلي منظم مع عرض أوضح للأيام والساعات.') }}</p>
                @error('date')
                    <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group meeting-field @error('url') has-error @enderror" style="margin-bottom: 0;">
                <label for="url">{{ __('رابط الاجتماع') }} <span class="text-danger">*</span></label>
                <input type="url" name="url" id="url" class="form-control meeting-input"
                    value="{{ old('url', $meeting->url ?? '') }}" required maxlength="500"
                    placeholder="{{ __('أدخل رابط الاجتماع مثل: https://zoom.us/j/123456789') }}">
                <p class="meeting-inline-note">{{ __('أضف الرابط الكامل ليتمكن المستخدمون من الوصول للاجتماع مباشرة من صفحة العرض.') }}</p>
                <div class="meeting-link-preview" data-meeting-link-preview>
                    <i class="bi bi-link-45deg"></i>
                    <span>{{ old('url', $meeting->url ?? __('سيظهر الرابط هنا بعد إدخاله.')) }}</span>
                </div>
                @error('url')
                    <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="meeting-section">
    <h3 class="meeting-section-title">
        <span>
            <i class="bi bi-ui-checks-grid"></i>
            {{ __('الاستطلاعات المرتبطة') }}
        </span>
        <span class="meeting-section-chip">{{ count($selectedPollIds) }}</span>
    </h3>

    <div class="form-group meeting-field @error('poll_ids') has-error @enderror">
        <label for="poll_ids">{{ __('اختر استطلاعات منشأة مسبقاً') }}</label>
        <select name="poll_ids[]" id="poll_ids" class="form-control meeting-input" multiple size="8">
            @forelse ($polls as $poll)
                <option value="{{ $poll->id }}" {{ in_array((string) $poll->id, $selectedPollIds, true) ? 'selected' : '' }}>
                    {{ $poll->title ?: $poll->question }}
                    @if($poll->meeting && optional($meeting)->id !== $poll->meeting_id)
                        - {{ __('مرتبط حالياً بـ') }} {{ $poll->meeting->name }}
                    @endif
                </option>
            @empty
                <option value="" disabled>{{ __('لا توجد استطلاعات متاحة للربط حالياً') }}</option>
            @endforelse
        </select>
        <p class="meeting-inline-note">{{ __('يمكن ربط استطلاع أو أكثر بهذا الاجتماع، وستظهر نتائجها المختصرة داخل صفحة تفاصيل الاجتماع مع رابط للتفاصيل الكاملة.') }}</p>
        @error('poll_ids')
            <span class="help-block">{{ $message }}</span>
        @enderror
        @error('poll_ids.*')
            <span class="help-block">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="meeting-section">
    <h3 class="meeting-section-title">
        <span>
            <i class="bi bi-people-fill"></i>
            {{ __('المدعوون للاجتماع') }}
        </span>
        <span class="meeting-section-chip" id="selectedUsersCount">{{ count($selectedUserIds) }}</span>
    </h3>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group meeting-field @error('audience_scope') has-error @enderror">
                <label for="meeting_audience_scope">{{ __('نطاق المدعوين') }}</label>
                <select name="audience_scope" id="meeting_audience_scope" class="form-control meeting-input" data-audience-scope>
                    @foreach($audienceScopes as $value => $label)
                        <option value="{{ $value }}" {{ $selectedAudienceScope === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <p class="meeting-inline-note">{{ __('اختر التصنيف المطلوب، وسيتم ربط المستخدمين المطابقين بالاجتماع عند الحفظ.') }}</p>
                @error('audience_scope')
                    <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="col-md-12" data-audience-panel="committee">
            <div class="form-group meeting-field @error('audience_committee') has-error @enderror">
                <label for="meeting_audience_committee">{{ __('اللجنة') }}</label>
                <select name="audience_committee" id="meeting_audience_committee" class="form-control meeting-input">
                    <option value="">{{ __('اختر اللجنة') }}</option>
                    @foreach($committeeOptions as $committee)
                        <option value="{{ $committee }}" {{ $selectedAudienceCommittee === $committee ? 'selected' : '' }}>{{ $committee }}</option>
                    @endforeach
                </select>
                @error('audience_committee')
                    <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="col-md-12" data-audience-panel="company">
            <div class="form-group meeting-field @error('audience_category_id') has-error @enderror">
                <label for="meeting_audience_company">{{ __('الشركة أو العضوية الرئيسية') }}</label>
                <select name="audience_category_id" id="meeting_audience_company" class="form-control meeting-input">
                    <option value="">{{ __('اختر الشركة أو العضوية') }}</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ (string) $selectedAudienceCategory === (string) $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                    @endforeach
                </select>
                @error('audience_category_id')
                    <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="col-md-12" data-audience-panel="department">
            <div class="form-group meeting-field @error('audience_category_id') has-error @enderror">
                <label for="meeting_audience_department">{{ __('الإدارة أو التصنيف الفرعي') }}</label>
                <select name="audience_category_id" id="meeting_audience_department" class="form-control meeting-input">
                    <option value="">{{ __('اختر الإدارة أو التصنيف') }}</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ (string) $selectedAudienceCategory === (string) $department->id ? 'selected' : '' }}>
                            {{ $department->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="meeting-attendees-grid" data-audience-panel="manual">
        <div class="form-group meeting-field @error('user_ids') has-error @enderror" style="margin-bottom: 0;">
            <label for="user_ids">{{ __('اختر المستخدمين') }}</label>
            <select name="user_ids[]" id="user_ids" class="form-control meeting-input" multiple size="10" data-users-select>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}"
                        {{ in_array($user->id, $selectedUserIds) ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
            <p class="meeting-inline-note">{{ __('اضغط مع Ctrl أو Cmd لاختيار أكثر من مستخدم. يمكن ترك الحقل فارغًا إذا لم يتم تحديد المدعوين بعد.') }}</p>
            @error('user_ids')
                <span class="help-block">{{ $message }}</span>
            @enderror
            @error('user_ids.*')
                <span class="help-block">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>{{ __('المحدد حاليًا') }}</label>
            <div class="meeting-selected-users" id="selectedUsersPreview" aria-live="polite"></div>
            <div class="meeting-empty-box" id="selectedUsersEmpty" @if(count($selectedUserIds) > 0) style="display: none;" @endif>
                <i class="bi bi-person-dash" style="font-size: 1.6rem; display: inline-block; margin-bottom: 8px; color: var(--primary-color);"></i>
                <div>{{ __('لم يتم اختيار مدعوين لهذا الاجتماع حتى الآن.') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="meeting-section">
    <h3 class="meeting-section-title">
        <span>
            <i class="bi bi-paperclip"></i>
            {{ __('المرفقات') }}
        </span>
        <span class="meeting-section-chip" id="attachmentRowsCount">{{ $attachmentRowsCount }}</span>
    </h3>

    <div class="meeting-attachments-stack" id="attachmentsContainer" data-attachment-rows>
        @for ($i = 0; $i < $attachmentRowsCount; $i++)
            <div class="meeting-attachment-row" data-attachment-row>
                <div class="meeting-attachment-head">
                    <div class="meeting-attachment-label">
                        <i class="bi bi-file-earmark-plus"></i>
                        <span>{{ __('مرفق جديد') }} #{{ $i + 1 }}</span>
                    </div>

                    <button type="button" class="meeting-attachment-remove" data-remove-attachment
                        title="{{ __('حذف هذا الصف') }}" @if($attachmentRowsCount === 1) style="display: none;" @endif>
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group meeting-field @error('attachments.' . $i) has-error @enderror">
                            <label>{{ __('الملف') }}</label>
                            <input type="file" name="attachments[]" class="form-control meeting-input" data-attachment-file>
                            @error('attachments.' . $i)
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group meeting-field" style="margin-bottom: 0;">
                            <label>{{ __('وصف المرفق') }}</label>
                            <input type="text" name="attachment_descriptions[]" class="form-control meeting-input"
                                value="{{ $attachmentDescriptions[$i] ?? '' }}" maxlength="255"
                                placeholder="{{ __('أدخل وصفًا مختصرًا للمرفق إن وجد') }}">
                        </div>
                    </div>
                </div>

                <div class="meeting-file-preview" data-attachment-file-name>{{ __('لم يتم اختيار ملف بعد.') }}</div>
            </div>
        @endfor
    </div>

    <button type="button" class="meeting-form-btn-muted meeting-add-attachment" id="addAttachmentRow">
        <i class="bi bi-plus-circle"></i>
        {{ __('إضافة مرفق آخر') }}
    </button>

    <p class="meeting-inline-note">{{ __('الحد الأقصى لحجم كل ملف 20 ميجابايت. يمكنك إضافة أكثر من مرفق مع وصف مستقل لكل واحد.') }}</p>
    @error('attachments')
        <span class="help-block text-danger">{{ $message }}</span>
    @enderror
    @error('attachment_descriptions')
        <span class="help-block text-danger">{{ $message }}</span>
    @enderror
</div>

@if ($isEdit && $existingAttachments->isNotEmpty())
    <div class="meeting-section">
        <h3 class="meeting-section-title">
            <span>
                <i class="bi bi-folder2-open"></i>
                {{ __('المرفقات الحالية') }}
            </span>
            <span class="meeting-section-chip">{{ $existingAttachments->count() }}</span>
        </h3>

        <div class="meeting-current-attachments">
            @foreach ($existingAttachments as $attachment)
                <article class="meeting-current-attachment">
                    <div class="meeting-current-attachment-head">
                        <div>
                            <h4 class="meeting-current-attachment-name">{{ $attachment->file_name }}</h4>
                            <div class="meeting-current-attachment-meta">
                                {{ $attachment->file_size_human }} - {{ $attachment->created_at->format('Y-m-d H:i') }}
                            </div>
                        </div>

                        <span class="meeting-section-chip">{{ optional($attachment->uploader)->name ?: __('غير محدد') }}</span>
                    </div>

                    <p class="meeting-current-attachment-meta">
                        {{ $attachment->description ?: __('لا يوجد وصف مضاف لهذا المرفق.') }}
                    </p>

                    <div class="meeting-current-attachment-actions">
                        <a href="{{ route('meetings.attachments.download', $attachment) }}">
                            <i class="bi bi-download"></i>
                            {{ __('تنزيل') }}
                        </a>

                        <form action="{{ route('meetings.attachments.delete', $attachment) }}" method="POST" style="margin: 0;" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا المرفق؟') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit">
                                <i class="bi bi-trash3"></i>
                                {{ __('حذف') }}
                            </button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
@endif
