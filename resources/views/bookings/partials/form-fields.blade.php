@php
    $isEdit = $isEdit ?? false;
    $bookingModel = $booking ?? null;
    $services = $services ?? collect();
    $users = $users ?? collect();
    $selectedServiceId = old('service_id', $bookingModel?->service_id);
    $selectedUserId = old('user_id', $bookingModel?->user_id);
    $selectedDate = old('date', optional($bookingModel?->booking_date)->format('Y-m-d'));
    $selectedTime = old('time', optional($bookingModel?->booking_time)->format('H:i'));
    $selectedNotes = old('notes', $bookingModel?->notes ?? '');
    $serviceName = optional($services->firstWhere('id', (int) $selectedServiceId))->name;
    $userName = optional($users->firstWhere('id', (int) $selectedUserId))->name;
@endphp

<div class="bookf-fields-grid">
    <div class="bookf-field">
        <label for="service_id">{{ __('الخدمة') }} <span class="text-danger">*</span></label>
        <select name="service_id" id="service_id" class="bookf-select" required>
            <option value="">{{ __('اختر الخدمة') }}</option>
            @foreach($services as $service)
                <option value="{{ $service->id }}" @selected((string) $selectedServiceId === (string) $service->id)>{{ $service->name }}</option>
            @endforeach
        </select>
        <span class="bookf-help">{{ __('اختر الخدمة التي سيتم الحجز عليها من القائمة الحالية.') }}</span>
        @error('service_id')
            <p class="bookf-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="bookf-field">
        <label for="user_id">{{ __('طالب الخدمة') }} <span class="text-danger">*</span></label>
        <select name="user_id" id="user_id" class="bookf-select" required>
            <option value="">{{ __('اختر طالب الخدمة') }}</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" @selected((string) $selectedUserId === (string) $user->id)>{{ $user->name }}</option>
            @endforeach
        </select>
        <span class="bookf-help">{{ __('اختر المستخدم المرتبط بهذا الحجز من المستخدمين المسجلين في النظام.') }}</span>
        @error('user_id')
            <p class="bookf-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="bookf-field">
        <label for="date">{{ __('التاريخ') }} <span class="text-danger">*</span></label>
        <input type="text" id="date" name="date" class="bookf-input" value="{{ $selectedDate }}" required>
        @error('date')
            <p class="bookf-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="bookf-field">
        <label for="time">{{ __('الوقت') }} <span class="text-danger">*</span></label>
        <input type="text" id="time" name="time" class="bookf-input" value="{{ $selectedTime }}" required>
        @error('time')
            <p class="bookf-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="bookf-field full-width">
        <label for="notes">{{ __('البيان') }}</label>
        <textarea name="notes" id="notes" class="bookf-textarea" rows="4" placeholder="{{ __('اكتب تفاصيل الخدمة المطلوبة أو أي بيان متعلق بها.') }}">{{ $selectedNotes }}</textarea>
        <span class="bookf-help">{{ __('هذا الحقل اختياري ويستخدم لتوضيح تفاصيل طلب الخدمة.') }}</span>
        @error('notes')
            <p class="bookf-error">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="bookf-preview">
    <strong>{{ __('ملخص الحجز الحالي') }}</strong>
    <span>{{ __('الخدمة: ') }}{{ $serviceName ?: __('غير محددة') }}</span>
    <span>{{ __('طالب الخدمة: ') }}{{ $userName ?: __('غير محدد') }}</span>
    <span>{{ __('التاريخ: ') }}{{ $selectedDate ?: __('غير محدد') }}</span>
    <span>{{ __('الوقت: ') }}{{ $selectedTime ?: __('غير محدد') }}</span>
</div>
