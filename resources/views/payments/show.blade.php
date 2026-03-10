@extends('layouts.app')

@section('title', __('عرض الدفعة'))

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    {{ __('عرض الدفعة') }}
                    <div class="pull-left">
                        <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning btn-sm">
                            <span class="glyphicon glyphicon-edit"></span> {{ __('تعديل') }}
                        </a>
                        <a href="{{ route('payments.index') }}" class="btn btn-default btn-sm">
                            <span class="glyphicon glyphicon-arrow-right"></span> {{ __('العودة') }}
                        </a>
                    </div>
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('تاريخ الدفع') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ $payment->date->format('Y-m-d') }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('المبلغ') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ number_format($payment->amount, 2) }} {{ __('ريال') }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('رقم طلب الشراء') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ $payment->shares_po_number ?? __('غير محدد') }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('معلومات البنك') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        @if($payment->bank_info)
                            <div style="white-space: pre-line;">{{ $payment->bank_info }}</div>
                        @else
                            {{ __('غير محدد') }}
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('رقم مستند التحويل') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ $payment->transfer_document ?? __('غير محدد') }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('الحالة') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        @if($payment->confirmed)
                            <span class="label label-success">{{ __('مؤكد') }}</span>
                        @else
                            <span class="label label-warning">{{ __('غير مؤكد') }}</span>
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('تاريخ الإنشاء') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ $payment->created_at->format('Y-m-d H:i') }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('تاريخ آخر تحديث') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ $payment->updated_at->format('Y-m-d H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related Purchase Order Information -->
@if($payment->shares_po_number && $payment->sharesPO)
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h4 class="panel-title">{{ __('معلومات طلب الشراء المرتبط') }}</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('اسم المستخدم') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ $payment->sharesPO->contributor->name ?? __('غير معروف') }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('رقم العرض') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ $payment->sharesPO->sale_number }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('الكمية') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ number_format($payment->sharesPO->count, 2) }} {{ __('سهم') }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('السعر لكل سهم') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ number_format($payment->sharesPO->amount_per_share, 2) }} {{ __('ريال') }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('الحالة') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        @if($payment->sharesPO->accept)
                            <span class="label label-success">{{ __('مقبول') }}</span>
                        @else
                            <span class="label label-danger">{{ __('غير مقبول') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Payment Actions -->
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">{{ __('الإجراءات') }}</h4>
            </div>
            <div class="panel-body text-center">
                <div class="btn-group">
                    <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning">
                        <span class="glyphicon icon-edit"></span> {{ __('تعديل') }}
                    </a>
                    
                    @if(!$payment->confirmed)
                        <button type="button" class="btn btn-success" id="confirmBtn">
                            <span class="glyphicon glyphicon-ok"></span> {{ __('تأكيد') }}
                        </button>
                    @else
                        <button type="button" class="btn btn-danger" id="unconfirmBtn">
                            <span class="glyphicon glyphicon-ban"></span> {{ __('إلغاء التأكيد') }}
                        </button>
                    @endif
                    
                    <a href="{{ route('payments.index') }}" class="btn btn-default">
                        <span class="glyphicon glyphicon-list"></span> {{ __('قائمة المدفوعات') }}
                    </a>
                    
                    <form action="{{ route('payments.destroy', $payment) }}" method="POST" style="display: inline;" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذه الدفعة؟') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <span class="glyphicon glyphicon-trash"></span> {{ __('حذف') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Confirm payment
    $('#confirmBtn').click(function() {
        if (confirm('{{ __("%هل أنت متأكد من تأكيد هذه الدفعة؟") }}')) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.ajax({
                url: '/payments/' + {{ $payment->id }} + '/toggle-confirmed',
                type: 'POST',
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    alert('{{ __("حدث خطأ أثناء تأكيد الدفعة.") }}');
                }
            });
        }
    });
    
    // Unconfirm payment
    $('#unconfirmBtn').click(function() {
        if (confirm('{{ __("هل أنت متأكد من إلغاء تأكيد هذه الدفعة؟") }}')) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.ajax({
                url: '/payments/' + {{ $payment->id }} + '/toggle-confirmed',
                type: 'POST',
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    alert('{{ __("حدث خطأ أثناء إلغاء تأكيد الدفعة.") }}');
                }
            });
        }
    });
});
</script>
@endpush
