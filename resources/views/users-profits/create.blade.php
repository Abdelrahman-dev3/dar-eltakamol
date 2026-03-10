@extends('layouts.app')

@section('title', __('إضافة توزيعة أرباح جديدة'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ __('إضافة توزيعة أرباح جديدة') }}</h3>
                </div>
                <div class="panel-body">
                    <form action="{{ route('users-profits.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="contributor_id">{{ __('المساهم') }} <span class="text-danger">*</span></label>
                            <select name="contributor_id" id="contributor_id" class="form-control @error('contributor_id') is-invalid @enderror" required>
                                <option value="">{{ __('اختر المساهم') }}</option>
                                @foreach($contributors as $contributor)
                                    <option value="{{ $contributor->id }}" {{ old('contributor_id') == $contributor->id ? 'selected' : '' }}>
                                        {{ $contributor->name ?? $contributor->user->name ?? __('غير معروف') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('contributor_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="profits_id">{{ __('نوع الربح') }} <span class="text-danger">*</span></label>
                            <select name="profits_id" id="profits_id" class="form-control @error('profits_id') is-invalid @enderror" required>
                                <option value="">{{ __('اختر نوع الربح') }}</option>
                                @foreach($profits as $profit)
                                    <option value="{{ $profit->id }}" {{ old('profits_id') == $profit->id ? 'selected' : '' }}>
                                        {{ $profit->date->format('Y-m-d') }} 
                                        @if($profit->end_date)
                                            - {{ $profit->end_date->format('Y-m-d') }}
                                        @endif
                                         - {{ number_format($profit->amount, 2) }} {{ __('ريال') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('profits_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="amount">{{ __('المبلغ المخصص') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" 
                                       value="{{ old('amount') }}" step="0.01" min="0" required>
                                <span class="input-group-addon">{{ __('ريال') }}</span>
                            </div>
                            @error('amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">{{ __('يجب أن لا يتجاوز هذا المبلغ المبلغ الإجمالي للربح المتاح') }}</small>
                        </div>

                        <div class="form-group">
                            <label for="payment_date">{{ __('تاريخ الدفع') }}</label>
                            <input type="date" name="payment_date" id="payment_date" 
                                   class="form-control @error('payment_date') is-invalid @enderror" 
                                   value="{{ old('payment_date') }}">
                            @error('payment_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">{{ __('اختياري - يمكن تركها فارغة إذا لم يتم الدفع بعد') }}</small>
                        </div>

                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="is_paid" id="is_paid" value="1" 
                                           {{ old('is_paid') ? 'checked' : '' }}> {{ __('تم دفع هذا المبلغ') }}
                                </label>
                            </div>
                            @error('is_paid')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">{{ __('إذا تم تحديد هذا، سيتم تعيين تاريخ الدفع تلقائياً') }}</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <span class="glyphicon glyphicon-plus"></span> {{ __('إضافة توزيعة جديدة') }}
                            </button>
                            <a href="{{ route('users-profits.index') }}" class="btn btn-default">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('إلغاء') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Available Profits Information -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">{{ __('أنواع الأرباح المتاحة') }}</h4>
                        </div>
                        <div class="panel-body">
                            @if($profits->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>{{ __('تاريخ البداية') }}</th>
                                                <th>{{ __('تاريخ النهاية') }}</th>
                                                <th>{{ __('المبلغ الإجمالي') }}</th>
                                                <th>{{ __('الحالة') }}</th>
                                                <th>{{ __('إجمالي المدفوع') }}</th>
                                                <th>{{ __('المتبقي') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($profits as $profit)
                                                <tr>
                                                    <td>{{ $profit->date->format('Y-m-d') }}</td>
                                                    <td>{{ $profit->end_date->format('Y-m-d') }}</td>
                                                    <td>{{ number_format($profit->amount, 2) }} {{ __('ريال') }}</td>
                                                    <td>
                                                        @if($profit->confirmed)
                                                            <span class="label label-success">{{ __('مؤكد') }}</span>
                                                        @else
                                                            <span class="label label-warning">{{ __('غير مؤكد') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ number_format($profit->usersProfits()->sum('amount'), 2) }} {{ __('ريال') }}</td>
                                                    <td>{{ number_format($profit->amount - $profit->usersProfits()->sum('amount'), 2) }} {{ __('ريال') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-warning text-center">
                                    <h4>{{ __('لا توجد أنواع أرباح متاحة') }}</h4>
                                    <p>{{ __('يجب إنشاء نوع ربح مؤكد أولاً قبل إمكانية إضافة توزيعات الأرباح.') }}</p>
                                    <a href="{{ route('profits.create') }}" class="btn btn-primary">
                                        <span class="glyphicon glyphicon-plus"></span> {{ __('إنشاء نوع ربح جديد') }}
                                    </a>
                                </div>
                            @endif
                        </div>
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
    // Auto-fill payment date when marking as paid
    $('#is_paid').change(function() {
        if ($(this).is(':checked') && $('#payment_date').val() === '') {
            $('#payment_date').val(new Date().toISOString().split('T')[0]);
        }
    });

    // Get available amount for selected profit
    $('#profits_id').change(function() {
        const selectedProfit = $(this).find('option:selected');
        if (selectedProfit.val() && selectedProfit.text()) {
            // Extract amount from option text
            const profitAmount = parseFloat(selectedProfit.text().match(/[\d,]+\.?\d*/).pop().replace(',', ''));
            if (!isNaN(profitAmount)) {
                $('#amount').attr('max', profitAmount);
                
                // Show helpful message
                $('#amount').next().next().text('{{ __("أقصى مبلغ متاح:") }} ' + profitAmount.toLocaleString() + ' {{ __("ريال") }}');
                
                // Validate amount against profit when user types
                $('#amount').on('input', function() {
                    const amount = parseFloat($(this).val()) || 0;
                    if (amount > profitAmount) {
                        $(this).css('border-color', '#a94442');
                        $('#amount').next().next().css('color', '#d9534f').text('{{ __("المبلغ يتجاوز المبلغ الإجمالي المتاح") }}');
                    } else {
                        $(this).css('border-color', '#3c763d');
                        $('#amount').next().next().css('color', '#3c763d').text('{{ __("المبلغ صحيح ومتوافق مع نوع الربح المختار") }}');
                    }
                });
            }
        }
    });

    // Form validation before submission
    $('form').on('submit', function(e) {
        const contributor = $('#contributor_id').val();
        const profit = $('#profits_id').val();
        const amount = parseFloat($('#amount').val()) || 0;

        if (!contributor || !profit || amount <= 0) {
            e.preventDefault();
            alert('{{ __("يرجى التأكد من ملء جميع الحقول المطلوبة") }}');
            return false;
        }

        // Check if contributor already has a profit distribution for this profit type
        const profitText = $('#profits_id option:selected').text();
        const isPaid = $('#is_paid').is(':checked');
        
        if (confirm('{{ __("ستقوم بإنشاء توزيعة أرباح جديدة. هل أنت متأكد؟") }}')) {
            if (isPaid && !$('#payment_date').val()) {
                alert('{{ __("يرجى تحديد تاريخ الدفع أو إلغاء تحديد الحالة كمدفوعة") }}');
                e.preventDefault();
                return false;
            }
        } else {
            e.preventDefault();
            return false;
        }
    });
});
</script>
@endpush
