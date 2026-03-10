@extends('layouts.app')

@section('title', __('طلبات الشراء'))

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    {{ __('طلبات الشراء') }}
                    <div class="pull-left">
                        <a href="{{ route('shares-pos.create') }}" class="btn btn-success btn-sm">
                            <i class="fa fa-plus"></i> {{ __('إضافة طلب شراء جديد') }}
                        </a>
                    </div>
                </h3>
            </div>
            <div class="panel-body">
                @if($sharesPOs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('المستخدم') }}</th>
                                    <th>{{ __('رقم العرض') }}</th>
                                    <th>{{ __('الكمية') }}</th>
                    <th>{{ __('السعر لكل سهم') }}</th>
                                    <th>{{ __('القبول') }}</th>
                                    <th>{{ __('تاريخ الإدراج') }}</th>
                                    <th>{{ __('حالة الطلب') }}</th>
                                    <th>{{ __('الإجراءات') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sharesPOs as $po)
                                    <tr>
                                        <td>{{ $po->contributor->name ?? __('غير معروف') }}</td>
                                        <td>{{ $po->sale_number }}</td>
                                        <td>{{ number_format($po->count, 2) }}</td>
                                        <td>{{ number_format($po->amount_per_share, 2) }} {{ __('ريال') }}</td>
                                        <td>
                                            @if($po->accept)
                                                <span class="label label-success">{{ __('مقبول') }}</span>
                                            @else
                                                <span class="label label-danger">{{ __('غير مقبول') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $po->insert_date->format('Y-m-d') }}</td>
                                        <td>
                                            @switch($po->po_status)
                                                @case(0)
                                                    <span class="label label-warning">{{ __('في الانتظار') }}</span>
                                                    @break
                                                @case(1)
                                                    <span class="label label-info">{{ __('قيد المراجعة') }}</span>
                                                    @break
                                                @case(2)
                                                    <span class="label label-success">{{ __('مُكتمل') }}</span>
                                                    @break
                                                @default
                                                    <span class="label label-default">{{ __('غير محدد') }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('shares-pos.show', $po) }}" class="btn btn-info btn-xs">
                                                    <i class="fa fa-eye"></i> {{ __('عرض') }}
                                                </a>
                                                <a href="{{ route('shares-pos.edit', $po) }}" class="btn btn-warning btn-xs">
                                                    <i class="fa fa-edit"></i> {{ __('تعديل') }}
                                                </a>
                                                <button type="button" class="btn btn-{{ $po->accept ? 'danger' : 'success' }} btn-xs toggle-accept" 
                                                        data-po-id="{{ $po->id }}" 
                                                        data-accept="{{ $po->accept }}">
                                                    <i class="fa fa-{{ $po->accept ? 'ban' : 'check' }}"></i> 
                                                    {{ $po->accept ? __('رفض') : __('قبول') }}
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="info">
                                    <td colspan="2"><strong>{{ __('المجموع') }}</strong></td>
                                    <td><strong>{{ number_format($sharesPOs->sum('count'), 2) }}</strong></td>
                                    <td><strong>{{ number_format($sharesPOs->avg('amount_per_share'), 2) }} {{ __('ريال') }}</strong></td>
                                    <td colspan="4"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{ $sharesPOs->links() }}
                @else
                    <div class="alert alert-info text-center">
                        <h4>{{ __('لا توجد طلبات شراء') }}</h4>
                        <p>{{ __('لم يتم إنشاء أي طلبات شراء بعد.') }}</p>
                            <a href="{{ route('shares-pos.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> {{ __('إنشاء أول طلب') }}
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
    $('.toggle-accept').click(function() {
        const poId = $(this).data('po-id')->data('po-id');
        const isAccept = $(this).data('accept');
        
        if (confirm(isAccept ? 
            '{{ __("هل تريد رفض هذا الطلب؟") }}' : 
            '{{ __("هل تريد قبول هذا الطلب؟") }}')) {
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.ajax({
                url: '/shares-pos/' + poId + '/toggle-accept',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    alert('{{ __("حدث خطأ أثناء تحديث حالة الطلب.") }}');
                }
            });
        }
    });
});
</script>
@endpush
