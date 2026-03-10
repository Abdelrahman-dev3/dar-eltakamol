@extends('layouts.app')

@section('title', __('المدفوعات'))

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    {{ __('المدفوعات') }}
                    <div class="pull-left">
                        <a href="{{ route('payments.create') }}" class="btn btn-success btn-sm">
                            <i class="fa fa-plus"></i> {{ __('إضافة دفعة جديدة') }}
                        </a>
                    </div>
                </h3>
            </div>
            <div class="panel-body">
                @if($payments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('تاريخ الدفع') }}</th>
                                    <th>{{ __('المبلغ') }}</th>
                                    <th>{{ __('رقم طلب الشراء') }}</th>
                                    <th>{{ __('معلومات البنك') }}</th>
                                    <th>{{ __('الحالة') }}</th>
                                    <th>{{ __('الإجراءات') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->date->format('Y-m-d') }}</td>
                                        <td>{{ number_format($payment->amount, 2) }} {{ __('ريال') }}</td>
                                        <td>{{ $payment->shares_po_number }}</td>
                                        <td>{{ $payment->bank_info }}</td>
                                        <td>
                                            @if($payment->confirmed)
                                                <span class="label label-success">{{ __('مؤكد') }}</span>
                                            @else
                                                <span class="label label-warning">{{ __('غير مؤكد') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('payments.show', $payment) }}" class="btn btn-info btn-xs">
                                                    <i class="fa fa-eye"></i> {{ __('عرض') }}
                                                </a>
                                                <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning btn-xs">
                                                    <i class="fa fa-edit"></i> {{ __('تعديل') }}
                                                </a>
                                                <button type="button" class="btn btn-{{ $payment->confirmed ? 'danger' : 'success' }} btn-xs toggle-confirmed" 
                                                        data-payment-id="{{ $payment->id }}" 
                                                        data-confirmed="{{ $payment->confirmed }}">
                                                    <i class="fa fa-{{ $payment->confirmed ? 'ban' : 'check' }}"></i> 
                                                    {{ $payment->confirmed ? __('إلغاء التأكيد') : __('تأكيد') }}
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="info">
                                    <td><strong>{{ __('المجموع') }}</strong></td>
                                    <td><strong>{{ number_format($payments->sum('amount'), 2) }} {{ __('ريال') }}</strong></td>
                                    <td colspan="4"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{ $payments->links() }}
                @else
                    <div class="alert alert-info text-center">
                        <h4>{{ __('لا توجد مدفوعات') }}</h4>
                        <p>{{ __('لم يتم إنشاء أي مدفوعات بعد.') }}</p>
                        <a href="{{ route('payments.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> {{ __('إنشاء أول دفعة') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.toggle-confirmed').click(function() {
        const paymentId = $(this).data('payment-id');
        const isConfirmed = $(this).data('confirmed');
        
        if (confirm(isConfirmed ? 
            '{{ __("هل تريد إلغاء تأكيد هذه الدفعة؟") }}' : 
            '{{ __("هل تريد تأكيد هذه الدفعة؟") }}')) {
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.ajax({
                url: '/payments/' + paymentId + '/toggle-confirmed',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    alert('{{ __("حدث خطأ أثناء تحديث حالة الدفعة.") }}');
                }
            });
        }
    });
});
</script>
@endpush
