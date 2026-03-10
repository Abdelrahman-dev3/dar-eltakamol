@extends('layouts.app')

@section('title', __('تعديل الدفعة'))

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('تعديل الدفعة') }}</h3>
            </div>
            <div class="panel-body">
                <form action="{{ route('payments.update', $payment) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date">{{ __('تاريخ الدفع') }} <span class="text-danger">*</span></label>
                                <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', $payment->date->format('Y-m-d')) }}" required>
                                @error('date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="amount">{{ __('المبلغ') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $payment->amount) }}" step="0.01" min="0" required>
                                    <span class="input-group-addon">{{ __('ريال') }}</span>
                                </div>
                                @error('amount')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="shares_po_number">{{ __('رقم طلب الشراء') }}</label>
                        <input type="text" name="shares_po_number" id="shares_po_number" class="form-control @error('shares_po_number') is-invalid @enderror" value="{{ old('shares_po_number', $payment->shares_po_number) }}" placeholder="{{ __('أدخل رقم طلب الشراء إن وجد') }}">
                        @error('shares_po_number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="bank_info">{{ __('معلومات البنك') }}</label>
                        <textarea name="bank_info" id="bank_info" class="form-control @error('bank_info') is-invalid @enderror" rows="3" placeholder="{{ __('أدخل تفاصيل البنك والحساب البنكي') }}">{{ old('bank_info', $payment->bank_info) }}</textarea>
                        @error('bank_info')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="transfer_document">{{ __('رقم مستند التحويل') }}</label>
                        <input type="text" name="transfer_document" id="transfer_document" class="form-control @error('transfer_document') is-invalid @enderror" value="{{ old('transfer_document', $payment->transfer_document) }}" placeholder="{{ __('أدخل رقم أو مرجع مستند التحويل') }}">
                        @error('transfer_document')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="confirmed" value="1" {{ old('confirmed', $payment->confirmed) ? 'checked' : '' }}>
                                {{ __('تأكيد الدفعة') }}
                            </label>
                        </div>
                        <small class="text-muted">{{ __('يمكن تأكيد الدفعة لاحقاً من خلال قائمة المدفوعات.') }}</small>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <span class="glyphicon glyphicon-save"></span> {{ __('حفظ التعديلات') }}
                        </button>
                        <a href="{{ route('payments.show', $payment) }}" class="btn btn-default">
                            <span class="glyphicon glyphicon-eye-open"></span> {{ __('عرض') }}
                        </a>
                        <a href="{{ route('payments.index') }}" class="btn btn-default">
                            <span class="glyphicon glyphicon-arrow-right"></span> {{ __('إلغاء') }}
                        </a>
                    </div>
                </form>

                <div class="alert alert-info">
                    <h5>{{ __('ملاحظة') }}:</h5>
                    <ul>
                        <li>{{ __('يمكن تعديل جميع البيانات إلا اذا كانت الدفعة مؤكدة ومكتملة.') }}</li>
                        <li>{{ __('تاريخ ومبلغ الدفع ضروريان للمعالجة.') }}</li>
                        <li>{{ __('معلومات البنك مهمة لتتبع العمليات.') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment History Panel -->
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">{{ __('تاريخ التعديلات') }}</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('تاريخ الإنشاء') }}:</strong>
                        {{ $payment->created_at->format('Y-m-d H:i') }}
                    </div>
                    <div class="col-md-6">
                        <strong>{{ __('آخر تحديث') }}:</strong>
                        {{ $payment->updated_at->format('Y-m-d H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h4 class="panel-title">{{ __('مقارنة مع الإحصائيات') }}</h4>
            </div>
            <div class="panel-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <h5>{{ __('هذه الدفعة') }}</h5>
                        <p class="text-primary">{{ number_format($payment->amount, 2) }} {{ __('ريال') }}</p>
                    </div>
                    <div class="col-md-4">
                        <h5>{{ __('متوسط المدفوعات') }}</h5>
                        <p class="text-info">{{ number_format(\App\Models\Payment::avg('amount'), 2) }} {{ __('ريال') }}</p>
                    </div>
                    <div class="col-md-4">
                        <h5>{{ __('أكبر دفعة') }}</h5>
                        <p class="text-success">{{ number_format(\App\Models\Payment::max('amount'), 2) }} {{ __('ريال') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Format amount input
    $('#amount').on('blur', function() {
        const value = parseFloat($(this).val());
        if (!isNaN(value)) {
            $(this).val(value.toFixed(2));
        }
    });
    
    // Warn if amount is significantly different
    $('#amount').on('change', function() {
        const currentAmount = parseFloat($(this).val());
        const originalAmount = {{ $payment->amount }};
        const difference = Math.abs(currentAmount - originalAmount);
        
        if (difference > originalAmount * 0.1) { // 10% change
            if (confirm('{{ __("يبدو أن المبلغ تغير بشكل كبير. هل أنت متأكد من المبلغ الجديد؟") }}')) {
                // Continue with the change
            } else {
                $(this).val({{ $payment->amount }});
            }
        }
    });
});
</script>
@endpush
