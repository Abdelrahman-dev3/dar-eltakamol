@extends('layouts.app')

@section('title', __('إضافة نوع ربح جديد'))

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('إضافة نوع ربح جديد') }}</h3>
            </div>
            <div class="panel-body">
                <form action="{{ route('profits.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="date">{{ __('تاريخ الربح') }} <span class="text-danger">*</span></label>
                        <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', date('Y-m-d')) }}" required>
                        @error('date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="end_date">{{ __('تاريخ انتهاء التوزع') }} <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', date('Y-m-d', strtotime('+1 year'))) }}" required>
                        @error('end_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="amount">{{ __('المبلغ الإجمالي') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" step="0.01" min="0" required>
                            <span class="input-group-addon">{{ __('ريال') }}</span>
                        </div>
                        @error('amount')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="confirmed" value="1" {{ old('confirmed') ? 'checked' : '' }}>
                                {{ __('تأكيد الربح') }}
                            </label>
                        </div>
                        <small class="text-muted">{{ __('يمكن تأكيد الربح لاحقاً من خلال قائمة الأرباح.') }}</small>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <span class="glyphicon glyphicon-save"></span> {{ __('حفظ نوع الربح') }}
                        </button>
                        <a href="{{ route('profits.index') }}" class="btn btn-default">
                            <span class="glyphicon glyphicon-arrow-right"></span> {{ __('إلغاء') }}
                        </a>
                    </div>
                </form>

                <div class="alert alert-info">
                    <h5>{{ __('ملاحظة') }}:</h5>
                    <ul>
                        <li>{{ __('سيتم إنشاء نوع ربح جديد يمكن توزيعه على المستخدمين لاحقاً.') }}</li>
                        <li>{{ __('يمكن تحديد مبلغ إجمالي ليتم توزيعه على المستخدمين بناءً على حصصهم.') }}</li>
                        <li>{{ __('يجب تأكيد الربح قبل إمكانية سداده للمستخدمين.') }}</li>
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
    // Set default end date to one year from now
    const today = new Date();
    const nextYear = new Date(today.getFullYear() + 1, today.getMonth(), today.getDate());
    
    $('#end_date').val(nextYear.toISOString().split('T')[0]);
    
    // Format amount input
    $('#amount').on('blur', function() {
        const value = parseFloat($(this).val());
        if (!isNaN(value)) {
            $(this).val(value.toFixed(2));
        }
    });
});
</script>
@endpush
