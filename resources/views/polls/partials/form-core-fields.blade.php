@php
    $pollModel = $poll ?? null;
    $selectedReferencedUsers = collect(old(
        'referenced_users',
        $pollModel ? $pollModel->referencedUsers->pluck('id')->all() : []
    ))->map(fn ($id) => (string) $id)->all();
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

    <div class="poll-field {{ $errors->has('referenced_users') ? 'has-error' : '' }}">
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
        <div class="poll-help-text">{{ __('يمكنك اختيار أكثر من مستخدم لتخصيص الاستطلاع لهم. استخدم Ctrl أو Cmd للاختيار المتعدد.') }}</div>
        @error('referenced_users')
            <div class="poll-error">{{ $message }}</div>
        @enderror
        @error('referenced_users.*')
            <div class="poll-error">{{ $message }}</div>
        @enderror
    </div>
</section>
