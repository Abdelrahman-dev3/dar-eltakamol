@extends('layouts.app')

@section('title', __('إضافة طلب شراء جديد'))

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('إضافة طلب شراء جديد') }}</h3>
            </div>
            <div class="panel-body">
                <form action="{{ route('shares-pos.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="user_id">{{ __('المساهم') }} <span class="text-danger">*</span></label>
                        <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                            <option value="">{{ __('اختر المساهم') }}</option>
                            @foreach($contributors as $contributor)
                                <option value="{{ $contributor->id }}" {{ old('user_id') == $contributor->id ? 'selected' : '' }}>
                                    {{ $contributor->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="sale_number">{{ __('رقم العرض') }}</label>
                        <input type="text" name="sale_number" id="sale_number" class="form-control @error('sale_number') is-invalid @enderror" value="{{ old('sale_number') }}" placeholder="{{ __('أدخل رقم العرض إن وجد') }}">
                        @error('sale_number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="count">{{ __('الكمية') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="count" id="count" class="form-control @error('count') is-invalid @enderror" value="{{ old('count') }}" step="0.01" min="0" required>
                                    <span class="input-group-addon">{{ __('سهم') }}</span>
                                </div>
                                @error('count')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div>
                                    <label for="amount_per_share">{{ __('السعر لكل سهم') }} <span class="text-danger">*</span></label>
                                    <span>( {{ __('يجب الا يقل سعر السهم عن')}} {{ $stock }} {{ __('ريال') }} )</span>
                                </div>
                                <div class="input-group">
                                    <input type="number" name="amount_per_share" id="amount_per_share" class="form-control @error('amount_per_share') is-invalid @enderror" value="{{ old('amount_per_share') }}" step="0.01" min="0" required>
                                    <span class="input-group-addon">{{ __('ريال') }}</span>
                                </div>
                                @error('amount_per_share')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="insert_date">{{ __('تاريخ الإدراج') }} <span class="text-danger">*</span></label>
                                <input type="date" name="insert_date" id="insert_date" class="form-control @error('insert_date') is-invalid @enderror" value="{{ old('insert_date', date('Y-m-d')) }}" required>
                                @error('insert_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="po_status">{{ __('حالة الطلب') }} <span class="text-danger">*</span></label>
                                <select name="po_status" id="po_status" class="form-control @error('po_status') is-invalid @enderror" required>
                                    <option value="">{{ __('اختر الحالة') }}</option>
                                    <option value="0" {{ old('po_status', '0') == '0' ? 'selected' : '' }}>{{ __('في الانتظار') }}</option>
                                    <option value="1" {{ old('po_status') == '1' ? 'selected' : '' }}>{{ __('قيد المراجعة') }}</option>
                                    <option value="2" {{ old('po_status') == '2' ? 'selected' : '' }}>{{ __('مُكتمل') }}</option>
                                </select>
                                @error('po_status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="accept" value="1" {{ old('accept') ? 'checked' : '' }}>
                                {{ __('قبول الطلب') }}
                            </label>
                        </div>
                        <small class="text-muted">{{ __('يمكن قبول الطلب لاحقاً من خلال قائمة الطلبات.') }}</small>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <span class="glyphicon glyphicon-save"></span> {{ __('حفظ طلب الشراء') }}
                        </button>
                        <a href="{{ route('shares-pos.index') }}" class="btn btn-default">
                            <span class="glyphicon glyphicon-arrow-right"></span> {{ __('إلغاء') }}
                        </a>
                    </div>
                </form>

                <div class="alert alert-info">
                    <h5>{{ __('ملاحظة') }}:</h5>
                    <ul>
                        <li>{{ __('المساهم، الكمية، السعر، والتاريخ مطلوبة.') }}</li>
                        <li>{{ __('رقم العرض اختياري ولكنه مهم للربط.') }}</li>
                        <li>{{ __('حالة الطلب تبدأ بـ "في الانتظار" عادةً.') }}</li>
                        <li>{{ __('يمكن قبول الطلب بعد المراجعة.') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Calculation Summary -->
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h4 class="panel-title">{{ __('حساب المجموع المتوقع') }}</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <h5>{{ __('الكمية') }}</h5>
                        <p id="calc-count">0</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <h5>{{ __('السعر لكل سهم') }}</h5>
                        <p id="calc-price">0.00 ريال</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <h5>{{ __('المجموع الإجمالي') }}</h5>
                        <p id="calc-total" class="text-success">0.00 ريال</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats Panel -->
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h4 class="panel-title">{{ __('إحصائيات الطلبات') }}</h4>
            </div>
            <div class="panel-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <h5>{{ __('الطلبات المقبولة') }}</h5>
                        <p class="text-success">{{ number_format(\App\Models\SharesPO::where('accept', true)->sum('count'), 2) }} {{ __('سهم') }}</p>
                    </div>
                    <div class="col-md-3">
                        <h5>{{ __('الطلبات غير المقبولة') }}</h5>
                        <p class="text-warning">{{ number_format(\App\Models\SharesPO::where('accept', false)->sum('count'), 2) }} {{ __('سهم') }}</p>
                    </div>
                    <div class="col-md-3">
                        <h5>{{ __('متوسط سعر السهم') }}</h5>
                        <p class="text-info">{{ number_format(\App\Models\SharesPO::avg('amount_per_share'), 2) }} {{ __('ريال') }}</p>
                    </div>
                    <div class="col-md-3">
                        <h5>{{ __('إجمالي قيمة الطلبات') }}</h5>
                        <p class="text-primary">{{ number_format(\App\Models\SharesPO::sum(\DB::raw('count * amount_per_share')), 2) }} {{ __('ريال') }}</p>
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
    // Calculate and display totals
    function updateCalculations() {
        const count = parseFloat($('#count').val()) || 0;
        const pricePerShare = parseFloat($('#amount_per_share').val()) || 0;
        const totalAmount = count * pricePerShare;
        
        $('#calc-count').text(count.toFixed(2));
        $('#calc-price').text(pricePerShare.toFixed(2) + ' ريال');
        $('#calc-total').text(totalAmount.toFixed(2) + ' ريال');
    }
    
    // Update calculations on input change
    $('#count, #amount_per_share').on('input', function() {
        updateCalculations();
    });
    
    // Initial calculation
    updateCalculations();
    
    // Format inputs on blur
    $('#count, #amount_per_share').on('blur', function() {
        const value = parseFloat($(this).val());
        if (!isNaN(value)) {
            $(this).val(value.toFixed(2));
            updateCalculations();
        }
    });
});
</script>
@endpush
