@extends('layouts.app')

@section('title', __('عرض طلب الشراء'))

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    {{ __('عرض طلب الشراء') }}
                    <div class="pull-left">
                        <a href="{{ route('shares-pos.edit', $sharesPO->id) }}" class="btn btn-warning btn-sm">
                            <span class="glyphicon glyphicon-edit"></span> {{ __('تعديل') }}
                        </a>
                        <a href="{{ route('shares-pos.index') }}" class="btn btn-default btn-sm">
                            <span class="glyphicon glyphicon-arrow-right"></span> {{ __('العودة') }}
                        </a>
                    </div>
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('المساهم') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ $sharesPO->contributor->name ?? __('غير معروف') }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('رقم العرض') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ $sharesPO->sale_number ?? __('غير محدد') }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('الكمية') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ number_format($sharesPO->count, 2) }} {{ __('سهم') }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('السعر لكل سهم') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ number_format($sharesPO->amount_per_share, 2) }} {{ __('ريال') }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('الموافقة') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        @if($sharesPO->accept)
                            <span class="label label-success">{{ __('مقبول') }}</span>
                        @else
                            <span class="label label-danger">{{ __('غير مقبول') }}</span>
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('حالة الطلب') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        @switch($sharesPO->po_status)
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
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('تاريخ الإدراج') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ $sharesPO->insert_date->format('Y-m-d') }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('تاريخ الإنشاء') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ $sharesPO->created_at->format('Y-m-d H:i') }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('تاريخ آخر تحديث') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ $sharesPO->updated_at->format('Y-m-d H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Financial Summary Card -->
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h4 class="panel-title">{{ __('ملخص مالي') }}</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <h3>{{ number_format($sharesPO->count, 2) }}</h3>
                        <p>{{ __('عدد الأسهم') }}</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <h3>{{ number_format($sharesPO->amount_per_share, 2) }}</h3>
                        <p>{{ __('سعر لكل سهم') }}</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <h3 class="text-success">{{ number_format($sharesPO->count * $sharesPO->amount_per_share, 2) }}</h3>
                        <p>{{ __('المجموع الإجمالي') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related Sell Share Information -->
@if($sharesPO->sellShare)
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h4 class="panel-title">{{ __('معلومات العرض المرتبط') }}</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('رقم العرض') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ $sharesPO->sellShare->share_count ?? __('غير محدد') }}
                    </div>
                </div>
                
                @if($sharesPO->sellShare->created_at)
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('تاريخ العرض') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ $sharesPO->sellShare->created_at->format('Y-m-d H:i') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<!-- Purchase Order Actions -->
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">{{ __('الإجراءات') }}</h4>
            </div>
            <div class="panel-body text-center">
                <div class="btn-group">
                    <a href="{{ route('shares-pos.edit', $sharesPO->id) }}" class="btn btn-warning">
                        <span class="glyphicon glyphicon-edit"></span> {{ __('تعديل') }}
                    </a>
                    
                    @if(!$sharesPO->accept)
                        <button type="button" class="btn btn-success" id="acceptBtn">
                            <span class="glyphicon glyphicon-ok"></span> {{ __('قبول') }}
                        </button>
                    @else
                        <button type="button" class="btn btn-danger" id="rejectBtn">
                            <span class="glyphicon glyphicon-ban"></span> {{ __('رفض') }}
                        </button>
                    @endif
                    
                    <a href="{{ route('shares-pos.index') }}" class="btn btn-default">
                        <span class="glyphicon glyphicon-list"></span> {{ __('قائمة الطلبات') }}
                    </a>
                    
                    <form action="{{ route('shares-pos.destroy', $sharesPO->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف طلب الشراء؟') }}');">
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
    // Accept purchase order
    $('#acceptBtn').click(function() {
        if (confirm('{{ __("هل أنت متأكد من قبول طلب الشراء؟") }}')) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.ajax({
                url: '/shares-pos/' + {{ $sharesPO->id }} + '/toggle-accept',
                type: 'POST',
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    alert('{{ __("حدث خطأ أثناء قبول الطلب.") }}');
                }
            });
        }
    });
    
    // Reject purchase order
    $('#rejectBtn').click(function() {
        if (confirm('{{ __("هل أنت متأكد من رفض طلب الشراء؟") }}')) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.ajax({
                url: '/shares-pos/' + {{ $sharesPO->id }} + '/toggle-accept',
                type: 'POST',
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    alert('{{ __("حدث خطأ أثناء رفض الطلب.") }}');
                }
            });
        }
    });
});
</script>
@endpush