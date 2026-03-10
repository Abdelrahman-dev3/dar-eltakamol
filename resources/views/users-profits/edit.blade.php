@extends('layouts.app')

@section('title', __('تعديل توزيعة الأرباح'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ __('تعديل توزيعة الأرباح') }}</h3>
                </div>
                <div class="panel-body">
                    <form action="{{ route('users-profits.update', $usersProfit->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="contributor_id">{{ __('المساهم') }} <span class="text-danger">*</span></label>
                            <select name="contributor_id" id="contributor_id" class="form-control @error('contributor_id') is-invalid @enderror" required>
                                <option value="">{{ __('اختر المساهم') }}</option>
                                @foreach($contributors as $contributor)
                                    <option value="{{ $contributor->id }}" {{ old('contributor_id', $usersProfit->contributor_id) == $contributor->id ? 'selected' : '' }}>
                                        {{ $contributor->name ?? $contributor->user->name }}
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
                                    <option value="{{ $profit->id }}" {{ old('profits_id', $usersProfit->profits_id) == $profit->id ? 'selected' : '' }}>
                                        {{ $profit->date->format('Y-m-d') }} - {{ number_format($profit->amount, 2) }} {{ __('ريال') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('profits_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="amount">{{ __('المبلغ') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" 
                                       value="{{ old('amount', $usersProfit->amount) }}" step="0.01" min="0" required>
                                <span class="input-group-addon">{{ __('ريال') }}</span>
                            </div>
                            @error('amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="payment_date">{{ __('تاريخ الدفع') }}</label>
                            <input type="date" name="payment_date" id="payment_date" 
                                   class="form-control @error('payment_date') is-invalid @enderror" 
                                   value="{{ old('payment_date', $usersProfit->payment_date ? $usersProfit->payment_date->format('Y-m-d') : '') }}">
                            @error('payment_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
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
                                    <input type="checkbox" name="is_paid" id="is_paid" value="1" 
                                           {{ old('is_paid', $usersProfit->is_paid) ? 'checked' : '' }}> {{ __('مدفوع') }}
                                </label>
                            </div>
                            @error('is_paid')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ __('تحديث') }}</button>
                            <a href="{{ route('users-profits.index') }}" class="btn btn-default">{{ __('إلغاء') }}</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Current Values Summary -->
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">{{ __('ملخص القيم الحالية') }}</h4>
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('المساهم') }}</th>
                                        <th>{{ __('نوع الربح') }}</th>
                                        <th>{{ __('المبلغ') }}</th>
                                        <th>{{ __('تاريخ الدفع') }}</th>
                                        <th>{{ __('الحالة') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $usersProfit->contributor->name ?? $usersProfit->contributor->user->name ?? __('غير معروف') }}</td>
                                        <td>{{ $usersProfit->profit->date->format('Y-m-d') ?? __('غير محدد') }} - {{ number_format($usersProfit->profit->amount ?? 0, 2) }} {{ __('ريال') }}</td>
                                        <td>{{ number_format($usersProfit->amount, 2) }} {{ __('ريال') }}</td>
                                        <td>{{ $usersProfit->payment_date ? $usersProfit->payment_date->format('Y-m-d') : __('غير محدد') }}</td>
                                        <td>
                                            @if($usersProfit->is_paid)
                                                <span class="label label-success">{{ __('مدفوع') }}</span>
                                            @else
                                                <span class="label label-warning">{{ __('غير مدفوع') }}</span>
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
    // Auto-fill payment date when marking as paid
    $('#is_paid').change(function() {
        if ($(this).is(':checked') && $('#payment_date').val() === '') {
            $('#payment_date').val(new Date().toISOString().split('T')[0]);
        }
    });

    // Validate amount against profit type
    $('#profits_id').change(function() {
        const selectedProfit = $(this).find('option:selected');
        if (selectedProfit.val()) {
            // Extract amount from option text (could be enhanced)
            const profitAmount = parseFloat(selectedProfit.text().split(' ')[0]);
            $('#amount').attr('max', profitAmount);
            
            // Show validation message if amount exceeds profit
            $('#amount').on('input', function() {
                const amount = parseFloat($(this).val()) || 0;
                if (amount > profitAmount) {
                    alert('{{ __("المبلغ المحدد يتجاوز المبلغ الإجمالي للربح المتاح") }}');
                }
            });
        }
    });
});
</script>
@endpush
