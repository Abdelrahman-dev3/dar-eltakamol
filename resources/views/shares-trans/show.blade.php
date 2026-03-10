@extends('layouts.app')

@section('title', __('عرض معاملة الأسهم'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('عرض معاملة الأسهم') }} #{{ $shares_tran->id }}
                        <div class="pull-left">
                            <a href="{{ route('shares-trans.edit', $shares_tran->id) }}" class="btn btn-warning btn-sm">
                                <span class="glyphicon glyphicon-edit"></span> {{ __('تعديل') }}
                            </a>
                            <a href="{{ route('shares-trans.index') }}" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('رجوع') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <!-- Transaction Basic Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h4 class="panel-title">{{ __('معلومات المعاملة الأساسية') }}</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('رقم المعاملة') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            {{ $shares_tran->id }}
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('تاريخ المعاملة') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            {{ $shares_tran->date->format('Y-m-d') }}
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('نوع المعاملة') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            @if($shares_tran->trans_type == 1)
                                                <span class="label label-success">{{ __('شراء') }}</span>
                                            @elseif($shares_tran->trans_type == 2)
                                                <span class="label label-danger">{{ __('بيع') }}</span>
                                            @elseif($shares_tran->trans_type == 3)
                                                <span class="label label-info">{{ __('تحويل') }}</span>
                                            @elseif($shares_tran->trans_type == 4)
                                                <span class="label label-warning">{{ __('أرباح') }}</span>
                                            @else
                                                <span class="label label-default">{{ __('غير محدد') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('الحالة') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            @if($shares_tran->posted)
                                                <span class="label label-success">{{ __('معتمد') }}</span>
                                            @else
                                                <span class="label label-warning">{{ __('غير معتمد') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('عدد التفاصيل') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="badge">{{ $shares_tran->shareTransLines->count() }}</span>
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
                                            <strong>{{ __('تاريخ الإنشاء') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            {{ $shares_tran->created_at->format('Y-m-d H:i') }}
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('آخر تحديث') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            {{ $shares_tran->updated_at->format('Y-m-d H:i') }}
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('المدة منذ الإنشاء') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            {{ $shares_tran->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('المدة منذ آخر تحديث') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            {{ $shares_tran->updated_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Notes -->
                    @if($shares_tran->notes)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-warning">
                                <div class="panel-heading">
                                    <h4 class="panel-title">{{ __('ملاحظات المعاملة') }}</h4>
                                </div>
                                <div class="panel-body">
                                    <p class="lead">{{ $shares_tran->notes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Transaction Lines Details -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        {{ __('تفاصيل المعاملة') }}
                                        <div class="pull-left">
                                            @if($shares_tran->shareTransLines->count() > 0)
                                                <a href="{{ route('share-trans-lines.index', ['trans_id' => $shares_tran->id]) }}" class="btn btn-info btn-xs">
                                                    <span class="glyphicon glyphicon-list"></span> {{ __('عرض جميع التفاصيل') }}
                                                </a>
                                            @else
                                                <a href="{{ route('share-trans-lines.create', ['trans_id' => $shares_tran->id]) }}" class="btn btn-success btn-xs">
                                                    <span class="glyphicon glyphicon-plus"></span> {{ __('إضافة تفاصيل') }}
                                                </a>
                                            @endif
                                        </div>
                                    </h4>
                                </div>
                                <div class="panel-body">
                                    @if($shares_tran->shareTransLines->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('المساهم') }}</th>
                                                        <th>{{ __('عدد الأسهم المدين') }}</th>
                                                        <th>{{ __('عدد الأسهم الدائن') }}</th>
                                                        <th>{{ __('صافي الأسهم') }}</th>
                                                        <th>{{ __('المبلغ لكل سهم') }}</th>
                                                        <th>{{ __('المبلغ الإجمالي') }}</th>
                                                        <th>{{ __('الحالة') }}</th>
                                                        <th>{{ __('الإجراءات') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $totalShares = 0;
                                                        $totalAmount = 0;
                                                    @endphp
                                                    @foreach($shares_tran->shareTransLines as $line)
                                                        @php
                                                            $netShares = $line->count_debit - $line->count_credit;
                                                            $lineAmount = $line->amount_per_share * $netShares;
                                                            $totalShares += $netShares;
                                                            $totalAmount += $lineAmount;
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                {{ $line->contributor->name ?? $line->contributor->user->name ?? __('غير معروف') }}
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge badge-success">{{ number_format($line->count_debit, 0) }}</span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge badge-danger">{{ number_format($line->count_credit, 0) }}</span>
                                                            </td>
                                                            <td class="text-center">
                                                                <strong class="{{ $netShares >= 0 ? 'text-success' : 'text-danger' }}">
                                                                    {{ number_format($netShares, 0) }}
                                                                </strong>
                                                            </td>
                                                            <td class="text-right">
                                                                {{ number_format($line->amount_per_share, 2) }} {{ __('ريال') }}
                                                            </td>
                                                            <td class="text-right">
                                                                <strong>{{ number_format($lineAmount, 2) }} {{ __('ريال') }}</strong>
                                                            </td>
                                                            <td class="text-center">
                                                                @if($line->posted)
                                                                    <span class="label label-success">{{ __('معتمد') }}</span>
                                                                @else
                                                                    <span class="label label-warning">{{ __('غير معتمد') }}</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="btn-group">
                                                                    <a href="{{ route('share-trans-lines.show', $line->id) }}" class="btn btn-info btn-xs">
                                                                        <span class="glyphicon glyphicon-eye-open"></span>
                                                                    </a>
                                                                    <a href="{{ route('share-trans-lines.edit', $line->id) }}" class="btn btn-warning btn-xs">
                                                                        <span class="glyphicon glyphicon-edit"></span>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr class="info">
                                                        <th colspan="3">{{ __('المجموع') }}</th>
                                                        <th class="text-center">
                                                            <strong>{{ number_format($totalShares, 0) }}</strong>
                                                        </th>
                                                        <th></th>
                                                        <th class="text-right">
                                                            <strong>{{ number_format($totalAmount, 2) }} {{ __('ريال') }}</strong>
                                                        </th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-warning text-center">
                                            <h4>{{ __('لا توجد تفاصيل للمعاملة') }}</h4>
                                            <p>{{ __('لم يتم إضافة أي تفاصيل لهذه المعاملة بعد.') }}</p>
                                            <a href="{{ route('share-trans-lines.create', ['trans_id' => $shares_tran->id]) }}" class="btn btn-success">
                                                <span class="glyphicon glyphicon-plus"></span> {{ __('إضافة تفاصيل المعاملة') }}
                                            </a>
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
                                        <a href="{{ route('shares-trans.edit', $shares_tran->id) }}" class="btn btn-warning">
                                            <span class="glyphicon glyphicon-edit"></span> {{ __('تعديل المعاملة') }}
                                        </a>
                                        
                                        @if(!$shares_tran->posted)
                                            <button type="button" class="btn btn-success" id="approve-transaction" data-id="{{ $shares_tran->id }}">
                                                <span class="glyphicon glyphicon-ok"></span> {{ __('اعتماد المعاملة') }}
                                            </button>
                                        @endif
                                        
                                        <a href="{{ route('shares-trans.index') }}" class="btn btn-default">
                                            <span class="glyphicon glyphicon-arrow-right"></span> {{ __('العودة للقائمة') }}
                                        </a>
                                        
                                        <button type="button" class="btn btn-danger" id="delete-transaction" data-id="{{ $shares_tran->id }}">
                                            <span class="glyphicon glyphicon-trash"></span> {{ __('حذف المعاملة') }}
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
    // Approve transaction
    $('#approve-transaction').click(function() {
        const transId = $(this).data('id');
        if (confirm('{{ __("هل أنت متأكد من اعتماد هذه المعاملة؟") }}')) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/shares-trans/' + transId + '/post',
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('{{ __("حدث خطأ أثناء اعتماد المعاملة.") }}');
                    }
                },
                error: function(xhr) {
                    alert('{{ __("حدث خطأ أثناء اعتماد المعاملة.") }}');
                }
            });
        }
    });

    // Delete transaction
    $('#delete-transaction').click(function() {
        const transId = $(this).data('id');
        if (confirm('{{ __("هل أنت متأكد من حذف هذه المعاملة؟") }}\n\n{{ __("هذا الإجراء لا يمكن التراجع عنه.") }}')) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/shares-trans/' + transId,
                type: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        window.location.href = '{{ route("shares-trans.index") }}';
                    } else {
                        alert('{{ __("حدث خطأ أثناء حذف المعاملة.") }}');
                    }
                },
                error: function(xhr) {
                    alert('{{ __("حدث خطأ أثناء حذف المعاملة.") }}');
                }
            });
        }
    });
});
</script>
@endpush
