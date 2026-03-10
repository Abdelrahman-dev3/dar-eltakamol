@extends('layouts.app')

@section('title', __('تعديل طلب الشراء'))

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('تعديل طلب الشراء') }}</h3>
            </div>
            <div class="panel-body">
                <form action="{{ route('shares-pos.update', ['shares_po' => $sharesPO->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="user_id">{{ __('المساهم') }} <span class="text-danger">*</span></label>
                        <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                            <option value="">{{ __('اختر المساهم') }}</option>
                            @foreach($contributors as $contributor)
                                <option value="{{ $contributor->id }}" {{ old('user_id', $sharesPO->user_id) == $contributor->id ? 'selected' : '' }}>
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
                        <input type="text" name="sale_number" id="sale_number" class="form-control @error('sale_number') is-invalid @enderror" value="{{ old('sale_number', $sharesPO->sale_number) }}" placeholder="{{ __('أدخل رقم العرض إن وجد') }}">
                        @error('sale_number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="count">{{ __('الكمية') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="count" id="count" class="form-control @error('count') is-invalid @enderror" value="{{ old('count', $sharesPO->count) }}" step="0.01" min="0" required>
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
                                    <input type="number" name="amount_per_share" id="amount_per_share" class="form-control @error('amount_per_share') is-invalid @enderror" value="{{ old('amount_per_share', $sharesPO->amount_per_share) }}" step="0.01" min="0" required>
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
                                <input type="date" name="insert_date" id="insert_date" class="form-control @error('insert_date') is-invalid @enderror" value="{{ old('insert_date', $sharesPO->insert_date->format('Y-m-d')) }}" required>
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
                                    <option value="0" {{ old('po_status', $sharesPO->po_status) == '0' ? 'selected' : '' }}>{{ __('في الانتظار') }}</option>
                                    <option value="1" {{ old('po_status', $sharesPO->po_status) == '1' ? 'selected' : '' }}>{{ __('قيد المراجعة') }}</option>
                                    <option value="2" {{ old('po_status', $sharesPO->po_status) == '2' ? 'selected' : '' }}>{{ __('مُكتمل') }}</option>
                                </select>
                                @error('po_status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                        <div class="form-group">
                            <label for="line_notes">{{ __('اسباب التعديل') }}</label>
                            <textarea name="line_notes" id="line_notes" class="form-control @error('line_notes') is-invalid @enderror" rows="3">{{ old('line_notes') }}</textarea>
                            @error('line_notes')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="accept" value="1" {{ old('accept', $sharesPO->accept) ? 'checked' : '' }}>
                                {{ __('قبول الطلب') }}
                            </label>
                        </div>
                        <small class="text-muted">{{ __('يمكن قبول الطلب لاحقاً من خلال قائمة الطلبات.') }}</small>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <span class="glyphicon glyphicon-save"></span> {{ __('حفظ التعديلات') }}
                        </button>
                        <a href="{{ route('shares-pos.show', $sharesPO) }}" class="btn btn-default">
                            <span class="glyphicon glyphicon-eye-open"></span> {{ __('عرض') }}
                        </a>
                        <a href="{{ route('shares-pos.index') }}" class="btn btn-default">
                            <span class="glyphicon glyphicon-arrow-right"></span> {{ __('إلغاء') }}
                        </a>
                    </div>
                </form>

                <div class="alert alert-info">
                    <h5>{{ __('ملاحظة') }}:</h5>
                    <ul>
                        <li>{{ __('يمكن تعديل جميع البيانات فيما عدا حالة الطلبات المُكتملة.') }}</li>
                        <li>{{ __('الكمية والسعر ضروريان للمعالجة.') }}</li>
                        <li>{{ __('حالة الطلب تصف المرحلة الحالية.') }}</li>
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
                <h4 class="panel-title">{{ __('حساب المجموع المحدث') }}</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <h5>{{ __('الكمية الحالية') }}</h5>
                        <p id="calc-count">{{ number_format($sharesPO->count, 2) }}</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <h5>{{ __('السعر الحالي') }}</h5>
                        <p id="calc-price">{{ number_format($sharesPO->amount_per_share, 2) }} ريال</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <h5>{{ __('المجموع المحدث') }}</h5>
                        <p id="calc-total" class="text-success">{{ number_format($sharesPO->count * $sharesPO->amount_per_share, 2) }} ريال</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Tracking -->
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">{{ __('تاريخ التغييرات') }}</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('تاريخ الإنشاء') }}:</strong>
                        {{ $sharesPO->created_at->format('Y-m-d H:i') }}
                    </div>
                    <div class="col-md-6">
                        <strong>{{ __('آخر تحديث') }}:</strong>
                        {{ $sharesPO->updated_at->format('Y-m-d H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Comparison Panel -->
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h4 class="panel-title">{{ __('مقارنة مع الطلبات الأخرى') }}</h4>
            </div>
            <div class="panel-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <h5>{{ __('متوسط الكمية') }}</h5>
                        <p class="text-info">{{ number_format(\App\Models\SharesPO::avg('count'), 2) }} سهم</p>
                    </div>
                    <div class="col-md-3">
                        <h5>{{ __('متوسط السعر') }}</h5>
                        <p class="text-warning">{{ number_format(\App\Models\SharesPO::avg('amount_per_share'), 2) }} ريال</p>
                    </div>
                    <div class="col-md-3">
                        <h5>{{ __('أكبر كمية') }}</h5>
                        <p class="text-success">{{ number_format(\App\Models\SharesPO::max('count'), 2) }} سهم</p>
                    </div>
                    <div class="col-md-3">
                        <h5>{{ __('أعلى سعر') }}</h5>
                        <p class="text-danger">{{ number_format(\App\Models\SharesPO::max('amount_per_share'), 2) }} ريال</p>
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
        const match = parseFloat($('#count').val()) || 0;
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
    
    // Format inputs on blur
    $('#count, #amount_per_share').on('blur', function() {
        const value = parseFloat($(this).val());
        if (!isNaN(value)) {
            $(this).val(value.toFixed(2));
            updateCalculations();
        }
    });
    
    // Initial calculation
    updateCalculations();
    
    // Warn if significant changes
    $('#count, #amount_per_share').on('change', function() {
        const originalCount = {{ $sharesPO->count }};
        const originalPrice = {{ $sharesPO->amount_per_share }};
        const currentCount = parseFloat($('#count').val()) || originalCount;
        const currentPrice = parseFloat($('#amount_per_share').val()) || originalPrice;
        
        const totalChange = Math.abs((currentCount * currentPrice) - (originalCount * originalPrice));
        const percentChange = totalChange / (originalCount * originalPrice) * 100;
        
        if (percentChange > 10) {
            const message = '{{ __("يبدو أن التغيير كبير") }}' + ' {0}%{{ __(". هل تريد تأكيد القيم الجديدة؟") }}'.format(percentChange.toFixed(1));
            
            if (!confirm(message)) {
                $(this).val($(this).attr('name') === 'count' ? {{ $sharesPO->count }} : {{ $sharesPO->amount_per_share }});
                updateCalculations();
            }
        }
    });
    
    // String format helper
    String.prototype.format = function() {
        let formatted = this;
        for (let i = 0; i < arguments.length; i++) {
            formatted = formatted.replace('{0}', arguments[i]);
        }
        return formatted;
    };
});
</script>
@endpush