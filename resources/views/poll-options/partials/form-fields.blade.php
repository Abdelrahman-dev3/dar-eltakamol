@php
    $optionModel = $pollOption ?? null;
@endphp

<section class="poll-form-section">
    <h2 class="poll-section-title">
        <i class="bi bi-diagram-3-fill"></i>
        {{ __('ربط الخيار بالاستطلاع') }}
    </h2>

    <div class="poll-field {{ $errors->has('poll_id') ? 'has-error' : '' }}">
        <label for="poll_id">{{ __('الاستطلاع') }} <span class="text-danger">*</span></label>
        <select name="poll_id" id="poll_id" class="poll-select" required>
            <option value="">{{ __('اختر الاستطلاع المرتبط') }}</option>
            @foreach($polls as $poll)
                <option value="{{ $poll->id }}" {{ (string) old('poll_id', $optionModel->poll_id ?? '') === (string) $poll->id ? 'selected' : '' }}>
                    {{ \Illuminate\Support\Str::limit($poll->question, 90) }}
                    - {{ __('الخيارات') }}: {{ $poll->pollOptions->count() }}
                    @if($poll->creator)
                        - {{ $poll->creator->name }}
                    @endif
                </option>
            @endforeach
        </select>
        <div class="poll-help-text">{{ __('اختر الاستطلاع الذي سيظهر داخله هذا الخيار للمستخدمين.') }}</div>
        @error('poll_id')
            <div class="poll-error">{{ $message }}</div>
        @enderror
    </div>
</section>

<section class="poll-form-section">
    <h2 class="poll-section-title">
        <i class="bi bi-chat-square-text-fill"></i>
        {{ __('بيانات الخيار') }}
    </h2>

    <div class="poll-field {{ $errors->has('option_text') ? 'has-error' : '' }}">
        <label for="option_text">{{ __('نص الخيار') }} <span class="text-danger">*</span></label>
        <input
            type="text"
            name="option_text"
            id="option_text"
            class="poll-input"
            value="{{ old('option_text', $optionModel->option_text ?? '') }}"
            placeholder="{{ __('اكتب النص الذي سيظهر للمصوّت') }}"
            maxlength="255"
            required
        >
        <div class="poll-help-text">{{ __('يفضّل أن يكون النص مختصرًا وواضحًا وغير متشابه مع بقية الخيارات داخل الاستطلاع.') }}</div>
        @error('option_text')
            <div class="poll-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="poll-field {{ $errors->has('votes') ? 'has-error' : '' }}">
        <label for="votes">{{ __('عدد الأصوات') }}</label>
        <input
            type="number"
            name="votes"
            id="votes"
            class="poll-input"
            value="{{ old('votes', $optionModel->votes ?? 0) }}"
            min="0"
            step="1"
        >
        <div class="poll-help-text">{{ __('اتركه صفرًا للخيارات الجديدة. يمكن استخدامه للمراجعة أو تصحيح البيانات عند الحاجة.') }}</div>
        @error('votes')
            <div class="poll-error">{{ $message }}</div>
        @enderror
    </div>
</section>
