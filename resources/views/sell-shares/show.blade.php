@extends('layouts.app')

@section('title', __('عرض تفاصيل عرض البيع'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('عرض تفاصيل عرض البيع') }} #{{ $sellShare->id }}
                        <div class="pull-left">
                            <a href="{{ route('sell-shares.edit', $sellShare->id) }}" class="btn btn-warning btn-sm">
                                <span class="glyphicon glyphicon-edit"></span> {{ __('تعديل') }}
                            </a>
                            <a href="{{ route('sell-shares.print', $sellShare->id) }}" class="btn btn-info btn-sm">
                                <span class="glyphicon glyphicon-print"></span> {{ __('طباعة') }}
                            </a>
                            <a href="{{ route('sell-shares.index') }}" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('رجوع') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <!-- Sell Share Basic Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h4 class="panel-title">{{ __('معلومات العرض الأساسية') }}</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('رقم العرض') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            {{ $sellShare->id }}
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('المساهم') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            {{ $sellShare->seller->name ?? $sellShare->seller->user->name ?? __('غير معروف') }}
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('عدد الأسهم') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="badge badge-primary">{{ number_format($sellShare->count, 0) }}</span>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('السعر لكل سهم') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>{{ number_format($sellShare->amount_per_share, 2) }} {{ __('ريال') }}</strong>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('المبلغ الإجمالي') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            <h4><strong class="text-success">{{ number_format($sellShare->total_amount, 2) }} {{ __('ريال') }}</strong></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <h4 class="panel-title">{{ __('معلومات إضافية') }}</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('تاريخ الانتهاء') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            @if($sellShare->end_date)
                                                {{ $sellShare->end_date->format('Y-m-d') }}
                                                @if($sellShare->end_date->isFuture())
                                                    <span class="label label-success">{{ __('نشط') }}</span>
                                                @else
                                                    <span class="label label-danger">{{ __('منتهي') }}</span>
                                                @endif
                                            @else
                                                <span class="label label-info">{{ __('بدون انتهاء') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('حالة الإعلان') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            @if($sellShare->ad_status == \App\Models\SellShares::AD_STATUS_INITIAL)
                                                <span class="label label-default">{{ __('مبدئي') }}</span>
                                            @elseif($sellShare->ad_status == \App\Models\SellShares::AD_STATUS_ACTIVE)
                                                <span class="label label-success">{{ __('نشط') }}</span>
                                            @elseif($sellShare->ad_status == \App\Models\SellShares::AD_STATUS_COMPLETED)
                                                <span class="label label-primary">{{ __('مكتمل') }}</span>
                                            @elseif($sellShare->ad_status == \App\Models\SellShares::AD_STATUS_CANCELLED)
                                                <span class="label label-danger">{{ __('ملغي') }}</span>
                                            @else
                                                <span class="label label-default">{{ __('غير محدد') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('تاريخ الإنشاء') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            {{ $sellShare->created_at->format('Y-m-d H:i') }}
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('آخر تحديث') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            {{ $sellShare->updated_at->format('Y-m-d H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sell Share Notes -->
                    @if($sellShare->notes)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-warning">
                                <div class="panel-heading">
                                    <h4 class="panel-title">{{ __('ملاحظات العرض') }}</h4>
                                </div>
                                <div class="panel-body">
                                    <p class="lead">{{ $sellShare->notes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Purchase Orders -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        {{ __('طلبات الشراء المرتبطة') }}
                                        <span class="badge">{{ $sellShare->sharesPOs->count() }}</span>
                                    </h4>
                                </div>
                                <div class="panel-body">
                                    @if($sellShare->sharesPOs->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('رقم طلب الشراء') }}</th>
                                                        <th>{{ __('المشتري') }}</th>
                                                        <th>{{ __('عدد الأسهم') }}</th>
                                                        <th>{{ __('السعر') }}</th>
                                                        <th>{{ __('المبلغ الإجمالي') }}</th>
                                                        <th>{{ __('الحالة') }}</th>
                                                        <th>{{ __('الإجراءات') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($sellShare->sharesPOs as $po)
                                                        <tr>
                                                            <td>{{ $po->id }}</td>
                                                            <td>{{ $po->contributor->name ?? __('غير معروف') }}</td>
                                                            <td>{{ number_format($po->count, 0) }}</td>
                                                            <td>{{ number_format($po->amount_per_share, 2) }} {{ __('ريال') }}</td>
                                                            <td>{{ number_format($po->count * $po->amount_per_share, 2) }} {{ __('ريال') }}</td>
                                                            <td>
                                                                @if($po->accept)
                                                                    <span class="label label-success">{{ __('مقبول') }}</span>
                                                                @else
                                                                    <span class="label label-warning">{{ __('في الانتظار') }}</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('shares-pos.show', $po->id) }}" class="btn btn-info btn-xs">
                                                                    <span class="glyphicon glyphicon-eye-open"></span> {{ __('عرض') }}
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-info text-center">
                                            <h4>{{ __('لا توجد طلبات شراء') }}</h4>
                                            <p>{{ __('لم يتم تقديم أي طلبات شراء لهذا العرض بعد.') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-body text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('sell-shares.edit', $sellShare->id) }}" class="btn btn-warning">
                                            <span class="glyphicon glyphicon-edit"></span> {{ __('تعديل العرض') }}
                                        </a>
                                        
                                        <a href="{{ route('sell-shares.print', $sellShare->id) }}" class="btn btn-info">
                                            <span class="glyphicon glyphicon-print"></span> {{ __('طباعة العرض') }}
                                        </a>
                                        
                                        <a href="{{ route('sell-shares.index') }}" class="btn btn-default">
                                            <span class="glyphicon glyphicon-arrow-right"></span> {{ __('العودة للقائمة') }}
                                        </a>
                                        
                                        <button type="button" class="btn btn-danger" id="delete-sell-share" data-id="{{ $sellShare->id }}">
                                            <span class="glyphicon glyphicon-trash"></span> {{ __('حذف العرض') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
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
    // Delete sell share
    $('#delete-sell-share').click(function() {
        const sellShareId = $(this).data('id');
        if (confirm('{{ __("هل أنت متأكد من حذف هذا العرض؟") }}\n\n{{ __("هذا الإجراء لا يمكن التراجع عنه.") }}')) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/sell-shares/' + sellShareId,
                type: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        window.location.href = '{{ route("sell-shares.index") }}';
                    } else {
                        alert('{{ __("حدث خطأ أثناء حذف العرض.") }}');
                    }
                },
                error: function(xhr) {
                    alert('{{ __("حدث خطأ أثناء حذف العرض.") }}');
                }
            });
        }
    });
});
</script>
@endpush

