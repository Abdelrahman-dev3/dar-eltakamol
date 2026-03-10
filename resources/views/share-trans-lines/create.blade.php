@extends('layouts.app')

@section('title', __('إضافة تفصيل معاملة أسهم جديد'))

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('إضافة تفصيل معاملة أسهم جديد') }}</h3>
            </div>
            <div class="panel-body">
                <form action="{{ route('share-trans-lines.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="trans_id">{{ __('رقم المعاملة') }} <span class="text-danger">*</span></label>
                        <input type="text" name="trans_id" id="trans_id" class="form-control @error('trans_id') is-invalid @enderror" value="{{ old('trans_id') }}" required>
                        @error('trans_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="contributor_id">{{ __('المساهم') }} <span class="text-danger">*</span></label>
                        <select name="contributor_id" id="contributor_id" class="form-control @error('contributor_id') is-invalid @enderror" required>
                            <option value="">{{ __('اختر المساهم') }}</option>
                            @foreach($contributors as $contributor)
                                <option value="{{ $contributor->id }}" {{ old('contributor_id') == $contributor->id ? 'selected' : '' }}>
                                    {{ $contributor->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('contributor_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="count_debit">{{ __('الخصم') }}</label>
                        <div class="input-group">
                            <input type="number" name="count_debit" id="count_debit" class="form-control @error('count_debit') is-invalid @enderror" value="{{ old('count_debit') }}" step="0.01" min="0">
                            <span class="input-group-addon">{{ __('سهما') }}</span>
                        </div>
                        @error('count_debit')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="count_credit">{{ __('الدائن') }}</label>
                        <div class="input-group">
                            <input type="number" name="count_credit" id="count_credit" class="form-control @error('count_credit') is-invalid @enderror" value="{{ old('count_credit') }}" step="0.01" min="0">
                            <span class="input-group-addon">{{ __('سهما') }}</span>
                        </div>
                        @error('count_credit')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="amount_per_share">{{ __('سعر السهم') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="amount_per_share" id="amount_per_share" class="form-control @error('amount_per_share') is-invalid @enderror" value="{{ old('amount_per_share') }}" step="0.01" min="0" required>
                            <span class="input-group-addon">{{ __('ريال') }}</span>
                        </div>
                        @error('amount_per_share')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="line_notes">{{ __('ملاحظات السطر') }}</label>
                        <textarea name="line_notes" id="line_notes" class="form-control @error('line_notes') is-invalid @enderror" rows="3">{{ old('line_notes') }}</textarea>
                        @error('line_notes')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="posted" value="1" {{ old('posted') ? 'checked' : '' }}>
                                {{ __('اعتماد السطر مالح') }}
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <span class="glyphicon glyphicon-save"></span> {{ __('حفظ تفصيل المعاملة') }}
                        </button>
                        <a href="{{ route('share-trans-lines.index') }}" class="btn btn-default">
                            <span class="glyphicon glyphicon-arrow-right"></span> {{ __('إلغاء') }}
                        </a>
                    </div>
                </form>

                <div class="alert alert-info">
                    <h5>{{ __('ملاحظة') }}:</h5>
                    <ul>
                        <li>{{ __('يجب اختيار المعاملة الأساسية أولاً.') }}</li>
                        <li>{{ __('يمكن تحديد الخصم أو الدائن، ولكن لا يجب تحديد كلاهما.') }}</li>
                        <li>{{ __('سعر السهم مطلوب لحساب المجموع.') }}</li>
                        <li>{{ __('لا يمكن الاعتماد الا بعد مراجعة البيانات.') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Calculate total amount when values change
    function calculateTotal() {
        const debit = parseFloat($('#count_debit').val()) || 0;
        const credit = parseFloat($('#count_credit').val()) || 0;
        const pricePerShare = parseFloat($('#amount_per_share').val()) || 0;
        
        let totalShares = Math.max(debit, credit);
        let totalAmount = totalShares * pricePerShare;
        
        if (totalAmount > 0) {
            // Show calculated total
            if (!$('#calculated-total').length) {
                $('.form-group').first().after('<div id="calculated-total" class="alert alert-success"><strong>{{ __("المبلغ المحسوب") }}: <span id="total-amount">0</span> {{ __("ريال") }}</strong></div>');
            }
            $('#total-amount').text(totalAmount.toFixed(2));
        } else {
            $('#calculated-total').remove();
        }
    }
    
    $('#count_debit, #count_credit, #amount_per_share').on('input', calculateTotal);
    
    // Prevent both debit and credit from being filled
    $('#count_debit').on('input', function() {
        if ($(this).val()) {
            $('#count_credit').val('');
        }
    });
    
    $('#count_credit').on('input', function() {
        if ($(this).val()) {
            $('#count_debit').val('');
        }
    });
});
</script>
@endpush
