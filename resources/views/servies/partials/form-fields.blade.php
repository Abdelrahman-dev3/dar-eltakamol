@php
    $isEdit = $isEdit ?? false;
    $serviceModel = $service ?? null;
    $currentName = old('service', $serviceModel?->name ?? '');
@endphp

<div class="servf-field">
    <label for="service">{{ __('اسم الخدمة') }} <span class="text-danger">*</span></label>
    <input type="text" name="service" id="service" class="servf-input"
        value="{{ $currentName }}"
        required maxlength="255"
        placeholder="{{ __('اكتب اسم الخدمة بشكل واضح ومختصر') }}">
    <span class="servf-help">{{ __('مثال: خدمة حجز القاعات أو خدمة متابعة الاشتراكات أو غيرها من الخدمات المستخدمة داخل النظام.') }}</span>
    @error('service')
        <p class="servf-error">{{ $message }}</p>
    @enderror
</div>

<div class="servf-preview">
    <strong>{{ __('المعاينة الحالية:') }}</strong>
    {{ $currentName ?: __('لم يتم إدخال اسم الخدمة بعد.') }}
</div>
