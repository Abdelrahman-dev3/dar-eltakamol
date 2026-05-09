@extends('layouts.app')
@section('title', __('إنشاء عرض بيع'))
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero"><div><h1 class="cp-title">{{ __('إنشاء عرض بيع') }}</h1><p class="cp-subtitle">{{ __('المتاح لك حسب حد البيع السنوي') }}: {{ number_format($availableShares, 2) }} {{ __('سهم') }}</p></div></section>
    <section class="cp-card">
        <form class="cp-form" method="POST" action="{{ route('contributor.sell-offers.store') }}">@csrf
            <div class="cp-field"><label class="cp-label">{{ __('عدد الأسهم') }}</label><input class="cp-input" name="count" type="number" min="1" step="0.01" value="{{ old('count') }}" required>@error('count')<span class="cp-error">{{ $message }}</span>@enderror</div>
            <div class="cp-field"><label class="cp-label">{{ __('سعر السهم') }}</label><input class="cp-input" name="amount_per_share" type="number" min="0.01" step="0.01" value="{{ old('amount_per_share') }}" required>@error('amount_per_share')<span class="cp-error">{{ $message }}</span>@enderror</div>
            <div class="cp-field"><label class="cp-label">{{ __('تاريخ الانتهاء') }}</label><input class="cp-input" name="end_date" type="date" value="{{ old('end_date') }}">@error('end_date')<span class="cp-error">{{ $message }}</span>@enderror</div>
            <div class="cp-field full"><label class="cp-label">{{ __('ملاحظات') }}</label><textarea class="cp-textarea" name="notes">{{ old('notes') }}</textarea>@error('notes')<span class="cp-error">{{ $message }}</span>@enderror</div>
            <div class="cp-actions"><button class="cp-btn cp-btn-primary" type="submit"><i class="bi bi-check2-circle"></i>{{ __('حفظ') }}</button><a class="cp-btn cp-btn-secondary" href="{{ route('contributor.sell-offers') }}">{{ __('إلغاء') }}</a></div>
        </form>
    </section>
</div>
@endsection
