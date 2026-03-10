@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('تفاصيل توزيعة الأرباح') }}
                        <div class="pull-left">
                            <a href="{{ route('users-profits.index') }}" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('رجوع') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td><strong>{{ __('المساهم') }}:</strong></td>
                                        <td>{{ $usersProfit->contributor->name ?? $usersProfit->contributor->user->name ?? __('غير معروف') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('نوع الربح') }}:</strong></td>
                                        <td>
                                            {{ $usersProfit->profit->date->format('Y-m-d') ?? __('غير محدد') }} 
                                            @if($usersProfit->profit->end_date)
                                                - {{ $usersProfit->profit->end_date->format('Y-m-d') }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('المبلغ الإجمالي للربح') }}:</strong></td>
                                        <td>{{ number_format($usersProfit->profit->amount ?? 0, 2) }} {{ __('ريال') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('المبلغ المخصص') }}:</strong></td>
                                        <td>{{ number_format($usersProfit->amount, 2) }} {{ __('ريال') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('تاريخ الدفع') }}:</strong></td>
                                        <td>
                                            @if($usersProfit->payment_date)
                                                {{ $usersProfit->payment_date->format('Y-m-d') }}
                                            @else
                                                {{ __('لم يتم الدفع بعد') }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('الحالة') }}:</strong></td>
                                        <td>
                                            @if($usersProfit->is_paid)
                                                <span class="label label-success">{{ __('مدفوع') }}</span>
                                            @else
                                                <span class="label label-warning">{{ __('غير مدفوع') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('تاريخ الإنشاء') }}:</strong></td>
                                        <td>{{ $usersProfit->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('آخر تحديث') }}:</strong></td>
                                        <td>{{ $usersProfit->updated_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="well well-sm">
                                <h4>{{ __('الإجراءات') }}</h4>
                                <div class="btn-group-vertical btn-block">
                                    <a href="{{ route('users-profits.edit', $usersProfit) }}" class="btn btn-warning">
                                        <span class="glyphicon glyphicon-edit"></span> {{ __('تعديل') }}
                                    </a>
                                    @if(!$usersProfit->is_paid)
                                        <button type="button" class="btn btn-success mark-as-paid" data-id="{{ $usersProfit->id }}">
                                            <span class="glyphicon glyphicon-usd"></span> {{ __('تحديد كمدفوع') }}
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-info" onclick="window.print();">
                                        <span class="glyphicon glyphicon-print"></span> {{ __('طباعة') }}
                                    </button>
                                </div>
                                
                                <hr>
                                
                                <div class="form-group">
                                    <form action="{{ route('users-profits.destroy', $usersProfit) }}" method="POST" style="display: inline;" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف توزيعة الأرباح؟') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-block">
                                            <span class="glyphicon glyphicon-trash"></span> {{ __('حذف') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    @if($usersProfit->profit)
                        <div class="row">
                            <div class="col-md-12">
                                <h4>{{ __('معلومات إضافية عن نوع الربح') }}</h4>
                                <div class="alert alert-info">
                                    <strong>{{ __('النوع') }}:</strong> {{ $usersProfit->profit->confirmed ? __('مؤكد') : __('غير مؤكد') }}<br>
                                    <strong>{{ __('تاريخ البداية') }}:</strong> {{ $usersProfit->profit->date->format('Y-m-d') }}<br>
                                    <strong>{{ __('تاريخ النهاية') }}:</strong> {{ $usersProfit->profit->end_date->format('Y-m-d') }}<br>
                                    <strong>{{ __('المبلغ الإجمالي') }}:</strong> {{ number_format($usersProfit->profit->amount, 2) }} {{ __('ريال') }}
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($usersProfit->contributor)
                        <div class="row">
                            <div class="col-md-12">
                                <h4>{{ __('معلومات المساهم') }}</h4>
                                <div class="alert alert-info">
                                    <strong>{{ __('الاسم') }}:</strong> {{ $usersProfit->contributor->name ?? $usersProfit->contributor->user->name ?? __('غير معروف') }}<br>
                                    @if($usersProfit->contributor->id_number)
                                        <strong>{{ __('الهوية الوطنية') }}:</strong> {{ $usersProfit->contributor->id_number ?? __('غير محدد') }}<br>
                                    @endif
                                    @if($usersProfit->contributor->phone_num)
                                        <strong>{{ __('الهاتف') }}:</strong> {{ $usersProfit->contributor->phone_num }}<br>
                                    @endif
                                    @if($usersProfit->contributor->iban)
                                        <strong>{{ __('IBAN') }}:</strong> {{ $usersProfit->contributor->iban }}<br>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.mark-as-paid').click(function() {
        const usersProfitId = $(this).data('id');
        if (confirm('{{ __("هل أنت متأكد من تحديد هذه التوزيعة كمدفوعة؟") }}')) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/users-profits/' + usersProfitId + '/mark-paid',
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('{{ __("حدث خطأ أثناء تحديث حالة الدفع.") }}');
                    }
                },
                error: function(xhr) {
                    alert('{{ __("حدث خطأ أثناء تحديث حالة الدفع.") }}');
                }
            });
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
