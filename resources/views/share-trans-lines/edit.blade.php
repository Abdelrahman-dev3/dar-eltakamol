@extends('layouts.app')

@section('title', __('تعديل تفصيل معاملة الأسهم'))

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('تعديل تفصيل معاملة الأسهم') }}</h3>
            </div>
            <div class="panel-body">
                <form action="{{ route('share-trans-lines.update', $shareTransLine) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="trans_id">{{ __('رقم المعاملة') }} <span class="text-danger">*</span></label>
                        <input type="text" name="trans_id" id="trans_id" class="form-control @error('trans_id') is-invalid @enderror" value="{{ old('trans_id', $shareTransLine->trans_id) }}" required>
                        @error('trans_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="contributor_id">{{ __('المساهم') }} <span class="text-danger">*</span></label>
                        <select name="contributor_id" id="contributor_id" class="form-control @error('contributor_id') is-invalid @enderror" required>
                            <option value="">{{ __('اختر المساهم') }}</option>
                            @foreach(\App\Models\Contributor::all() as $contributor)
                                <option value="{{ $contributor->id }}" {{ old('contributor_id', $shareTransLine->contributor_id) == $contributor->id ? 'selected' : '' }}>
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
                            <input type="number" name="count_debit" id="count_debit" class="form-control @error('count_debit') is-invalid @enderror" value="{{ old('count_debit', $shareTransLine->count_debit) }}" step="0.01" min="0">
                            <span class="input-group-addon">{{ __('سهما') }}</span>
                        </div>
                        @error('count_debit')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="count_credit">{{ __('الدائن') }}</label>
                        <div class="input-group">
                            <input type="number" name="count_credit" id="count_credit" class="form-control @error('count_credit') is-invalid @enderror" value="{{ old('count_credit', $shareTransLine->count_credit) }}" step="0.01" min="0">
                            <span class="input-group-addon">{{ __('سهما') }}</span>
                        </div>
                        @error('count_credit')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="amount_per_share">{{ __('سعر السهم') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="amount_per_share" id="amount_per_share" class="form-control @error('amount_per_share') is-invalid @enderror" value="{{ old('amount_per_share', $shareTransLine->amount_per_share) }}" step="0.01" min="0" required>
                            <span class="input-group-addon">{{ __('ريال') }}</span>
                        </div>
                        @error('amount_per_share')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="line_notes">{{ __('ملاحظات السطر') }}</label>
                        <textarea name="line_notes" id="line_notes" class="form-control @error('line_notes') is-invalid @enderror" rows="3">{{ old('line_notes', $shareTransLine->line_notes) }}</textarea>
                        @error('line_notes')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="line_notes_2">{{ __('اسباب التعديل') }}</label>
                        <textarea name="line_notes_2" id="line_notes_2" class="form-control @error('line_notes_2') is-invalid @enderror" rows="3">{{ old('line_notes_2', $shareTransLine->line_notes_2) }}</textarea>
                        @error('line_notes_2')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="posted" value="1" {{ old('posted', $shareTransLine->posted) ? 'checked' : '' }}>
                                {{ __('اعتماد السطر حاليا') }}
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <span class="glyphicon glyphicon-save"></span> {{ __('حفظ التعديلات') }}
                        </button>
                        <a href="{{ route('share-trans-lines.show', $shareTransLine) }}" class="btn btn-default">
                            <span class="glyphicon glyphicon-eye-open"></span> {{ __('عرض') }}
                        </a>
                        <a href="{{ route('share-trans-lines.index') }}" class="btn btn-default">
                            <span class="glyphicon glyphicon-arrow-right"></span> {{ __('إلغاء') }}
                        </a>
                    </div>
                </form>

                <div class="alert alert-info">
                    <h5>{{ __('ملاحظة') }}:</h5>
                    <ul>
                        <li>{{ __('يمكن تعديل جميع البيانات إلا اذا كان السطر معتمداً.') }}</li>
                        <li>{{ __('يجب عدم تحديد الخصم والدائن في نفس الوقت.') }}</li>
                        <li>{{ __('سعر السهم مطلوب لحساب المجموع.') }}</li>
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
                <h4 class="panel-title">{{ __('الحسابات المتوقعة') }}</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <h5>{{ __('عدد الأسهم') }}</h5>
                        <p id="calc-shares">0</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <h5>{{ __('سعر السهم') }}</h5>
                        <p id="calc-price">0.00 ريال</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <h5>{{ __('المجموع') }}</h5>
                        <p id="calc-total" class="text-success">0.00 ريال</p>
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
        const debit = parseFloat($('#count_debit').val()) || 0;
        const credit = parseFloat($('#count_credit').val()) || 0;
        const pricePerShare = parseFloat($('#amount_per_share').val()) || 0;
        
        let totalShares = Math.max(debit, credit);
        let totalAmount = totalShares * pricePerShare;
        
        $('#calc-shares').text(totalShares.toFixed(2));
        $('#calc-price').text(pricePerShare.toFixed(2) + ' ريال');
        $('#calc-total').text(totalAmount.toFixed(2) + ' ريال');
    }
    
    // Update calculations on input change
    $('#count_debit, #count_credit, #amount_per_share').on('input', function() {
        updateCalculations();
    });
    
    // Initial calculation
    updateCalculations();
    
    // Prevent both debit and credit from being filled
    $('#count_debit').on('input', function() {
        if ($(this).val()) {
            $('#count_credit').val('');
        }
        updateCalculations();
    });
    
    $('#count_credit').on('input', function() {
        if ($(this).val()) {
            $('#count_debit').val('');
        }
        updateCalculations();
    });
});
</script>
@endpush
