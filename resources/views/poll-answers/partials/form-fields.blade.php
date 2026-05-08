@php
    $answerModel = $pollAnswer ?? null;
@endphp

<section class="poll-form-section">
    <h2 class="poll-section-title">
        <i class="bi bi-ui-radios-grid"></i>
        {{ __('الاستطلاع والخيار') }}
    </h2>

    <div class="poll-field {{ $errors->has('poll_id') ? 'has-error' : '' }}">
        <label for="poll_id">{{ __('الاستطلاع') }} <span class="text-danger">*</span></label>
        <select name="poll_id" id="poll_id" class="poll-select" required>
            <option value="">{{ __('اختر الاستطلاع') }}</option>
            @foreach($polls as $poll)
                <option value="{{ $poll->id }}" {{ (string) old('poll_id', $answerModel->poll_id ?? '') === (string) $poll->id ? 'selected' : '' }}>
                    {{ \Illuminate\Support\Str::limit($poll->question, 90) }}
                    @if($poll->creator)
                        - {{ $poll->creator->name }}
                    @endif
                </option>
            @endforeach
        </select>
        <div class="poll-help-text">{{ __('اختر الاستطلاع أولًا حتى يتم تصفية الخيارات بشكل صحيح في الحقل التالي.') }}</div>
        @error('poll_id')
            <div class="poll-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="poll-field {{ $errors->has('poll_option_id') ? 'has-error' : '' }}">
        <label for="poll_option_id">{{ __('الخيار المختار') }} <span class="text-danger">*</span></label>
        <select name="poll_option_id" id="poll_option_id" class="poll-select" required>
            <option value="">{{ __('اختر الخيار') }}</option>
            @foreach($pollOptions as $option)
                <option
                    value="{{ $option->id }}"
                    data-poll-id="{{ $option->poll_id }}"
                    {{ (string) old('poll_option_id', $answerModel->poll_option_id ?? '') === (string) $option->id ? 'selected' : '' }}
                >
                    {{ $option->option_text }}
                    @if($option->poll)
                        - {{ \Illuminate\Support\Str::limit($option->poll->question, 70) }}
                    @endif
                </option>
            @endforeach
        </select>
        @error('poll_option_id')
            <div class="poll-error">{{ $message }}</div>
        @enderror
    </div>
</section>

<section class="poll-form-section">
    <h2 class="poll-section-title">
        <i class="bi bi-person-check-fill"></i>
        {{ __('بيانات التصويت') }}
    </h2>

    <div class="poll-field {{ $errors->has('user_id') ? 'has-error' : '' }}">
        <label for="user_id">{{ __('المستخدم') }} <span class="text-danger">*</span></label>
        <select name="user_id" id="user_id" class="poll-select" required>
            <option value="">{{ __('اختر المستخدم') }}</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ (string) old('user_id', $answerModel->user_id ?? '') === (string) $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                    @if($user->email)
                        ({{ $user->email }})
                    @endif
                </option>
            @endforeach
        </select>
        @error('user_id')
            <div class="poll-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="poll-field {{ $errors->has('answer_date') ? 'has-error' : '' }}">
        <label for="answer_date">{{ __('تاريخ التصويت') }}</label>
        <input
            type="datetime-local"
            name="answer_date"
            id="answer_date"
            class="poll-input"
            value="{{ old('answer_date', optional($answerModel?->answer_date ?? now())->format('Y-m-d\TH:i')) }}"
        >
        <div class="poll-help-text">{{ __('يمكنك ترك الوقت الحالي أو تعديله عند إدخال بيانات سابقة أو مراجعتها.') }}</div>
        @error('answer_date')
            <div class="poll-error">{{ $message }}</div>
        @enderror
    </div>
</section>
