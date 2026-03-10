@extends('layouts.app')

@section('title', __('تعديل نوع الربح'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('تعديل نوع الربح') }}
                        <div class="pull-left">
                            <a href="{{ route('profits.index') }}" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('رجوع') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <form action="{{ route('profits.update', $profit->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="date">{{ __('تاريخ البداية') }} <span class="text-danger">*</span></label>
                            <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" 
                                   value="{{ old('date', $profit->date->format('Y-m-d')) }}" required>
                            @error('date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="end_date">{{ __('تاريخ النهاية') }} <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" 
                                   value="{{ old('end_date', $profit->end_date->format('Y-m-d')) }}" required>
                            @error('end_date')
                                <div class="text-danger">{{ $message }}.</div>
                            @enderror
                            <small class="text-muted">{{ __('يجب أن يكون تاريخ النهاية بعد تاريخ البداية') }}</small>
                        </div>

                        <div class="form-group">
                            <label for="amount">{{ __('المبلغ الإجمالي') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" 
                                       value="{{ old('amount', $profit->amount) }}" step="0.01" min="0" required>
                                <span class="input-group-addon">{{ __('ريال') }}</span>
                            </div>
                            @error('amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">{{ __('المبلغ الإجمالي المتاح للتوزيع') }}</small>
                        </div>

                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="confirmed" id="confirmed" value="1" 
                                           {{ old('confirmed', $profit->confirmed) ? 'checked' : '' }}> {{ __('مؤكد ونشط') }}
                                </label>
                            </div>
                            @error('confirmed')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">{{ __('يجب تأكيد نوع الربح قبل إمكانية إضافة توزيعات الأرباح') }}</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <span class="glyphicon glyphicon-floppy-disk"></span> {{ __('تحديث نوع الربح') }}
                            </button>
                            <a href="{{ route('profits.index') }}" class="btn btn-default">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('إلغاء') }}
                            </a>
                            <a href="{{ route('profits.show', $profit->id) }}" class="btn btn-info">
                                <span class="glyphicon glyphicon-eye-open"></span> {{ __('عرض التفاصيل') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Current Values Summary -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">{{ __('ملخص القيم الحالية') }}</h4>
                        </div>
                        <div class="panel-body">
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
                                        <td>
                                            @php
                                                $remaining = $profit->amount - $profit->usersProfits()->sum('amount');
                                            @endphp
                                            <span class="{{ $remaining >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ number_format($remaining, 2) }} {{ __('ريال') }}
                                            </span>
                                            @if($remaining < 0)
                                                <br><small class="text-warning">{{ __('(متجاوز)') }}</small>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
    // Date validation - end date must be after start date
    $('#start_date, #end_date').change(function() {
        const startDate = new Date($('#date').val());
        const endDate = new Date($('#end_date').val());
        
        if (endDate <= startDate) {
            $('#end_date')[0].setCustomValidity('{{ __("تاريخ النهاية يجب أن يكون بعد تاريخ البداية") }}');
        } else {
            $('#end_date')[0].setCustomValidity('');
        }
    });

    // Amount validation
    $('#amount').on('input', function() {
        const amount = parseFloat($(this).val()) || 0;
        const currentAmount = {{ $profit->amount }};
        const paidAmount = {{ $profit->usersProfits()->sum('amount') }};
        
        if (amount < paidAmount) {
            $(this)[0].setCustomValidity('{{ __("المبلغ الجديد أقل من المبلغ المدفوع بالفعل") }}');
            $(this).css('border-color', '#a94442');
        } else {
            $(this)[0].setCustomValidity('');
            $(this).css('border-color', '#3c763d');
        }
    });

    // Form submission confirmation
    $('form').on('submit', function(e) {
        const currentConfirmed = {{ $profit->confirmed ? 'true' : 'false' }};
        const newConfirmed = $('#confirmed').is(':checked');
        
        // If changing from confirmed to unconfirmed, warn about impact
        if (currentConfirmed && !newConfirmed) {
            if (!confirm('{{ __("تغيير حالة التأكيد إلى غير مؤكد سيمنع إضافة توزيعات جديدة للأرباح. هل أنت متأكد؟") }}')) {
                e.preventDefault();
                return false;
            }
        }
        
        // Check if amount is being reduced significantly
        const currentAmount = {{ $profit->amount }};
        const newAmount = parseFloat($('#amount').val());
        const paidAmount = {{ $profit->usersProfits()->sum('amount') }};
        
        if (newAmount < paidAmount) {
            alert('{{ __("لا يمكن تقليل المبلغ إلى أقل من المبلغ المدفوع بالفعل") }}');
            e.preventDefault();
            return false;
        }
    });
});
</script>
@endpush

<style>
@media print {
    .panel-heading, .btn, .btn-group {
        display: none !important;
    }
    .container {
        width: 100% !important;
    }
}
</style>
