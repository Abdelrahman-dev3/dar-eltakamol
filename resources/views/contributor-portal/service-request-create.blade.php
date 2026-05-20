@extends('layouts.app')
@section('title', __('طلب خدمة'))
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ __('طلب خدمة') }}</h1>
            <p class="cp-subtitle">{{ __('اختر الخدمة واكتب البيان التفصيلي ليتم تحويل الطلب إلى الإدارة ومتابعته من نفس النافذة.') }}</p>
        </div>
        <div class="cp-actions">
            <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.services') }}"><i class="bi bi-arrow-right"></i>{{ __('رجوع للطلبات') }}</a>
        </div>
    </section>

    <section class="cp-card">
        <form class="cp-form" method="POST" action="{{ route('contributor.services.request.store') }}">
            @csrf

            <div class="cp-field full">
                <label class="cp-label" for="service_id">{{ __('الخدمة') }} <span class="text-danger">*</span></label>
                <select class="cp-select" name="service_id" id="service_id" required>
                    <option value="">{{ __('اختر الخدمة') }}</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" @selected((string) old('service_id') === (string) $service->id)>{{ $service->name }}</option>
                    @endforeach
                </select>
                @error('service_id')<span class="cp-error">{{ $message }}</span>@enderror
            </div>

            <div class="cp-field full">
                <label class="cp-label" for="notes">{{ __('البيان') }}</label>
                <textarea class="cp-textarea" name="notes" id="notes" maxlength="1000" placeholder="{{ __('اكتب تفاصيل الخدمة المطلوبة بوضوح') }}">{{ old('notes') }}</textarea>
                @error('notes')<span class="cp-error">{{ $message }}</span>@enderror
            </div>

            <div class="cp-field full">
                <div class="cp-actions">
                    <button class="cp-btn cp-btn-primary" type="submit"><i class="bi bi-send-fill"></i>{{ __('إرسال الطلب') }}</button>
                    <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.services') }}">{{ __('إلغاء') }}</a>
                </div>
            </div>
        </form>
    </section>
</div>
@endsection
