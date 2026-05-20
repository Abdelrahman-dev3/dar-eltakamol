@php
    $pollModel = $poll ?? null;
    $selectedReferencedUsers = collect(old(
        'referenced_users',
        $pollModel ? $pollModel->referencedUsers->pluck('id')->all() : []
    ))->map(fn ($id) => (string) $id)->all();
    $audienceScopes = $audienceScopes ?? ['manual' => 'اختيار مستخدمين محددين'];
    $committeeOptions = $committeeOptions ?? [];
    $companies = $companies ?? collect();
    $departments = $departments ?? collect();
    $meetings = $meetings ?? collect();
    $selectedAudienceScope = old('audience_scope', $pollModel?->audience_scope ?? 'manual');
    $selectedAudienceCommittee = old('audience_committee', $pollModel?->audience_committee);
    $selectedAudienceCategory = old('audience_category_id', $pollModel?->audience_category_id);
    $selectedMeetingId = old('meeting_id', $pollModel?->meeting_id);
@endphp

<section class="poll-form-section">
    <h2 class="poll-section-title">
        <i class="bi bi-chat-square-text"></i>
        {{ __('بيانات الاستطلاع') }}
    </h2>

    <div class="poll-field {{ $errors->has('question') ? 'has-error' : '' }}">
        <label for="question">{{ __('السؤال') }} <span class="text-danger">*</span></label>
        <textarea
            name="question"
            id="question"
            class="poll-textarea"
            rows="4"
            required
        >{{ old('question', $pollModel->question ?? '') }}</textarea>
        <div class="poll-help-text">{{ __('اكتب السؤال بصياغة واضحة ومباشرة حتى تكون النتيجة أسهل في القراءة واتخاذ القرار.') }}</div>
        @error('question')
            <div class="poll-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="poll-field-grid">
        <div class="poll-field {{ $errors->has('start_date') ? 'has-error' : '' }}">
            <label for="start_date">{{ __('تاريخ البدء') }} <span class="text-danger">*</span></label>
            <input
                type="datetime-local"
                name="start_date"
                id="start_date"
                class="poll-input"
                value="{{ old('start_date', optional($pollModel?->start_date)->format('Y-m-d\TH:i')) }}"
                required
            >
            @error('start_date')
                <div class="poll-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="poll-field {{ $errors->has('end_date') ? 'has-error' : '' }}">
            <label for="end_date">{{ __('تاريخ الانتهاء') }} <span class="text-danger">*</span></label>
            <input
                type="datetime-local"
                name="end_date"
                id="end_date"
                class="poll-input"
                value="{{ old('end_date', optional($pollModel?->end_date)->format('Y-m-d\TH:i')) }}"
                required
            >
            @error('end_date')
                <div class="poll-error">{{ $message }}</div>
            @enderror
        </div>
    </div>
</section>

<section class="poll-form-section">
    <h2 class="poll-section-title">
        <i class="bi bi-diagram-3"></i>
        {{ __('الربط والمشاركون') }}
    </h2>

    <div class="poll-field">
        <label>{{ __('حالة الاستطلاع') }}</label>
        <label class="poll-toggle" for="is_active">
            <input
                type="checkbox"
                name="is_active"
                id="is_active"
                value="1"
                {{ old('is_active', $pollModel ? $pollModel->is_active : true) ? 'checked' : '' }}
            >
            <span>
                <strong>{{ __('تفعيل الاستطلاع') }}</strong>
                <span>{{ __('عند التفعيل سيظهر الاستطلاع كاستطلاع قابل للاستخدام وفق تاريخ البدء والانتهاء.') }}</span>
            </span>
        </label>
    </div>

    <div class="poll-field {{ $errors->has('zoom_meeting_id') ? 'has-error' : '' }}">
        <label for="zoom_meeting_id">{{ __('اجتماع الزوم') }}</label>
        <select name="zoom_meeting_id" id="zoom_meeting_id" class="poll-select">
            <option value="">{{ __('اختر اجتماع زوم إن وجد') }}</option>
            @forelse($zoomMeetings as $zoomMeeting)
                <option
                    value="{{ $zoomMeeting->id }}"
                    {{ (string) old('zoom_meeting_id', $pollModel?->zoom_meeting_id ?? '') === (string) $zoomMeeting->id ? 'selected' : '' }}
                >
                    {{ $zoomMeeting->title }}
                    @if($zoomMeeting->meeting_date)
                        - {{ $zoomMeeting->meeting_date->format('Y-m-d H:i') }}
                    @endif
                </option>
            @empty
                <option value="" disabled>{{ __('لا توجد اجتماعات زوم متاحة حاليًا') }}</option>
            @endforelse
        </select>
        @error('zoom_meeting_id')
            <div class="poll-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="poll-field {{ $errors->has('meeting_id') ? 'has-error' : '' }}">
        <label for="meeting_id">{{ __('ربط الاستطلاع باجتماع') }}</label>
        <select name="meeting_id" id="meeting_id" class="poll-select">
            <option value="">{{ __('بدون اجتماع مرتبط') }}</option>
            @forelse($meetings as $meeting)
                <option
                    value="{{ $meeting->id }}"
                    {{ (string) $selectedMeetingId === (string) $meeting->id ? 'selected' : '' }}
                >
                    {{ $meeting->name }}
                    @if($meeting->date)
                        - {{ $meeting->date->format('Y-m-d H:i') }}
                    @endif
                </option>
            @empty
                <option value="" disabled>{{ __('لا توجد اجتماعات متاحة حالياً') }}</option>
            @endforelse
        </select>
        <div class="poll-help-text">{{ __('عند الربط سيظهر ملخص نتائج هذا الاستطلاع داخل صفحة تفاصيل الاجتماع.') }}</div>
        @error('meeting_id')
            <div class="poll-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="poll-field {{ $errors->has('audience_scope') ? 'has-error' : '' }}">
        <label for="poll_audience_scope">{{ __('نطاق المشاركين') }}</label>
        <select name="audience_scope" id="poll_audience_scope" class="poll-select" data-audience-scope>
            @foreach($audienceScopes as $value => $label)
                <option value="{{ $value }}" {{ $selectedAudienceScope === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <div class="poll-help-text">{{ __('اختر التصنيف المطلوب، وسيتم ربط المستخدمين المطابقين بالاستطلاع عند الحفظ.') }}</div>
        @error('audience_scope')
            <div class="poll-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="poll-field {{ $errors->has('audience_committee') ? 'has-error' : '' }}" data-audience-panel="committee">
        <label for="poll_audience_committee">{{ __('اللجنة') }}</label>
        <select name="audience_committee" id="poll_audience_committee" class="poll-select">
            <option value="">{{ __('اختر اللجنة') }}</option>
            @foreach($committeeOptions as $committee)
                <option value="{{ $committee }}" {{ $selectedAudienceCommittee === $committee ? 'selected' : '' }}>{{ $committee }}</option>
            @endforeach
        </select>
        @error('audience_committee')
            <div class="poll-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="poll-field {{ $errors->has('audience_category_id') ? 'has-error' : '' }}" data-audience-panel="company">
        <label for="poll_audience_company">{{ __('الشركة أو العضوية الرئيسية') }}</label>
        <select name="audience_category_id" id="poll_audience_company" class="poll-select">
            <option value="">{{ __('اختر الشركة أو العضوية') }}</option>
            @foreach($companies as $company)
                <option value="{{ $company->id }}" {{ (string) $selectedAudienceCategory === (string) $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
            @endforeach
        </select>
        @error('audience_category_id')
            <div class="poll-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="poll-field {{ $errors->has('audience_category_id') ? 'has-error' : '' }}" data-audience-panel="department">
        <label for="poll_audience_department">{{ __('الإدارة أو التصنيف الفرعي') }}</label>
        <select name="audience_category_id" id="poll_audience_department" class="poll-select">
            <option value="">{{ __('اختر الإدارة أو التصنيف') }}</option>
            @foreach($departments as $department)
                <option value="{{ $department->id }}" {{ (string) $selectedAudienceCategory === (string) $department->id ? 'selected' : '' }}>
                    {{ $department->full_name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="poll-field {{ $errors->has('referenced_users') ? 'has-error' : '' }}" data-audience-panel="manual">
        <label for="referenced_users">{{ __('المستخدمون المشاركون') }}</label>
        <select name="referenced_users[]" id="referenced_users" class="poll-select" multiple>
            @forelse($users as $user)
                <option value="{{ $user->id }}" {{ in_array((string) $user->id, $selectedReferencedUsers, true) ? 'selected' : '' }}>
                    {{ $user->name }} ({{ $user->email }})
                </option>
            @empty
                <option value="" disabled>{{ __('لا يوجد مستخدمون متاحون') }}</option>
            @endforelse
        </select>
        <div class="poll-help-text">{{ __('يستخدم هذا الحقل فقط عند اختيار "اختيار مستخدمين محددين".') }}</div>
        @error('referenced_users')
            <div class="poll-error">{{ $message }}</div>
        @enderror
        @error('referenced_users.*')
            <div class="poll-error">{{ $message }}</div>
        @enderror
    </div>
</section>

@once
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-audience-scope]').forEach(function (scopeSelect) {
            const form = scopeSelect.closest('form');
            const syncAudiencePanels = function () {
                const selectedScope = scopeSelect.value || 'manual';

                form.querySelectorAll('[data-audience-panel]').forEach(function (panel) {
                    const isActive = panel.dataset.audiencePanel === selectedScope;
                    panel.style.display = isActive ? '' : 'none';
                    panel.querySelectorAll('select, input, textarea').forEach(function (input) {
                        input.disabled = !isActive;
                    });
                });
            };

            scopeSelect.addEventListener('change', syncAudiencePanels);
            syncAudiencePanels();
        });
    });
</script>
@endpush
@endonce
