@extends('layouts.app')
@section('title', __('تقديم طلب شراء'))
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ __('تقديم طلب شراء') }}</h1>
            <p class="cp-subtitle">{{ __('يمكنك تقديم طلب مرتبط بعرض بيع، أو طلب شراء مستقل بدون اختيار عرض.') }}</p>
        </div>
    </section>

    @if($errors->has('trading_period'))
        <section class="cp-card">
            <div class="cp-error">{{ $errors->first('trading_period') }}</div>
        </section>
    @endif

    <section class="cp-card">
        <h2 class="cp-card-title"><i class="bi bi-cart-plus-fill"></i>{{ __('طلب شراء مستقل') }}</h2>
        <form class="cp-form" method="POST" action="{{ route('contributor.purchase-orders.independent.store') }}">
            @csrf
            <div class="cp-field"><label class="cp-label">{{ __('عدد الأسهم') }}</label><input class="cp-input" name="count" type="number" min="0.01" step="0.01" value="{{ old('count') }}" required>@error('count')<span class="cp-error">{{ $message }}</span>@enderror</div>
            <div class="cp-field"><label class="cp-label">{{ __('سعر السهم') }}</label><input class="cp-input" name="amount_per_share" type="number" min="{{ max($stock, 0) }}" step="0.01" value="{{ old('amount_per_share', $stock) }}" required>@error('amount_per_share')<span class="cp-error">{{ $message }}</span>@enderror</div>
            <div class="cp-field full"><label class="cp-label">{{ __('وصف الطلب') }}</label><textarea class="cp-textarea" name="notes" placeholder="{{ __('مثال: أرغب بشراء أسهم عند توفر بائع مناسب') }}">{{ old('notes') }}</textarea>@error('notes')<span class="cp-error">{{ $message }}</span>@enderror</div>
            <div class="cp-actions"><button class="cp-btn cp-btn-primary" type="submit"><i class="bi bi-check2-circle"></i>{{ __('تقديم طلب مستقل') }}</button><a class="cp-btn cp-btn-secondary" href="{{ route('contributor.purchase-orders') }}">{{ __('إلغاء') }}</a></div>
        </form>
    </section>

    <section class="cp-card">
        <h2 class="cp-card-title"><i class="bi bi-link-45deg"></i>{{ __('طلب شراء مرتبط بعرض بيع') }}</h2>
        @unless($canCreateLinkedPurchaseOrder)
            <div class="cp-error" style="margin-bottom: .85rem;">{{ __('لا يمكن تقديم طلب شراء مرتبط بعرض بيع بعد انتهاء الفترة الأولى من التداول.') }}</div>
        @endunless
        <form class="cp-form" method="POST" action="{{ route('contributor.purchase-orders.store') }}">
            @csrf
            <div class="cp-field full">
                <label class="cp-label">{{ __('عرض البيع') }}</label>
                <select class="cp-select" name="sale_number" required>
                    <option value="">{{ __('اختر عرضا') }}</option>
                    @foreach($sellShares as $offer)
                        @php $sellerLabel = $hideSeller ? __('مساهم') : optional($offer->seller)->name; @endphp
                        <option value="{{ $offer->id }}" @selected(old('sale_number') == $offer->id)>#{{ $offer->id }} - {{ $sellerLabel }} - {{ number_format((float)$offer->count, 2) }} {{ __('سهم') }} - {{ number_format((float)$offer->amount_per_share, 2) }}</option>
                    @endforeach
                </select>
                @error('sale_number')<span class="cp-error">{{ $message }}</span>@enderror
            </div>
            <div class="cp-field"><label class="cp-label">{{ __('عدد الأسهم') }}</label><input class="cp-input" name="count" type="number" min="0.01" step="0.01" value="{{ old('count') }}" required>@error('count')<span class="cp-error">{{ $message }}</span>@enderror</div>
            <div class="cp-field"><label class="cp-label">{{ __('سعر السهم') }}</label><input class="cp-input" name="amount_per_share" type="number" min="{{ max($stock, 0) }}" step="0.01" value="{{ old('amount_per_share', $stock) }}" required>@error('amount_per_share')<span class="cp-error">{{ $message }}</span>@enderror</div>
            <div class="cp-actions"><button class="cp-btn cp-btn-secondary" type="submit"><i class="bi bi-check2-circle"></i>{{ __('تقديم طلب مرتبط') }}</button></div>
        </form>
    </section>
</div>
@endsection
