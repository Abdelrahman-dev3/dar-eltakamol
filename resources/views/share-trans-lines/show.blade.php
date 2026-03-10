@extends('layouts.app')

@section('title', __('عرض تفصيل معاملة الأسهم'))

@section('content')
<div resource
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    {{ __('عرض تفصيل معاملة الأسهم') }}
                    <div class="pull-left">
                        <a href="{{ route('share-trans-lines.edit', $shareTransLine) }}" class="btn btn-warning btn-sm">
                            <span class="glyphicon glyphicon-edit"></span> {{ __('تعديل') }}
                        </a>
                        <a href="{{ route('share-trans-lines.index') }}" class="btn btn-default btn-sm">
                            <span class="glyphicon glyphicon-arrow-right"></span> {{ __('العودة') }}
                        </a>
                    </div>
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('اسم المساهم') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        @if($shareTransLine->contributor)
                            <span class="label label-primary" style="font-size: 1.1em;">
                                {{ $shareTransLine->contributor->name }}
                            </span>
                        @else
                            <span class="text-danger">{{ __('غير محدد') }}</span>
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('المعاملة الأساسية') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ $shareTransLine->sharesTrans->shares_count ?? __('غير محدد') }} {{ __('سهم') }}
                        @if($shareTransLine->sharesTrans)
                            <br><small>{{ $shareTransLine->sharesTrans->created_at->format('Y-m-d H:i') }}</small>
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('الخصم') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ number_format($shareTransLine->count_debit, 2) }} {{ __('سهم') }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('الدائن') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ number_format($shareTransLine->count_credit, 2) }} {{ __('سهم') }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('سعر السهم') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ number_format($shareTransLine->amount_per_share, 2) }} {{ __('ريال') }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('الحالة') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        @if($shareTransLine->posted)
                            <span class="label label-success">{{ __('معتمد') }}</span>
                        @else
                            <span class="label label-warning">{{ __('غير معتمد') }}</span>
                        @endif
                    </div>
                </div>
                <hr>

                @if($shareTransLine->line_notes)
                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('ملاحظات السطر') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ $shareTransLine->line_notes }}
                    </div>
                </div>
                <hr>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('تاريخ الإنشاء') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ $shareTransLine->created_at->format('Y-m-d H:i') }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('تاريخ آخر تحديث') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ $shareTransLine->updated_at->format('Y-m-d H:i') }}
                    </div>
                </div>

                <!-- Summary Card -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h4 class="panel-title">{{ __('ملخص المعاملة') }}</h4>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        <h4>{{ number_format(max($shareTransLine->count_debit, $shareTransLine->count_credit), 2) }}</h4>
                                        <p>{{ __('عدد الأسهم') }}</p>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <h4>{{ number_format($shareTransLine->amount_per_share, 2) }}</h4>
                                        <p>{{ __('سعر السهم') }}</p>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <h4 class="text-success">{{ number_format(max($shareTransLine->count_debit, $shareTransLine->count_credit) * $shareTransLine->amount_per_share, 2) }}</h4>
                                        <p>{{ __('المبلغ الإجمالي') }}</p>
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
#endif

<!-- Action Buttons -->
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">{{ __('الإجراءات') }}</h4>
            </div>
            <div class="panel-body text-center">
                <div class="btn-group">
                    <a href="{{ route('share-trans-lines.edit', $shareTransLine) }}" class="btn btn-warning">
                        <span class="glyphicon glyphicon-edit"></span> {{ __('تعديل') }}
                    </a>
                    
                    @if(!$shareTransLine->posted)
                        <button type="button" class="btn btn-success" id="approveBtn">
                            <span class="glyphicon glyphicon-ok"></span> {{ __('اعتماد') }}
                        </button>
                    @else
                        <button type="button" class="btn btn-danger" id="disapproveBtn">
                            <span class="glyphicon glyphicon-ban"></span> {{ __('إلغاء الاعتماد') }}
                        </button>
                    @endif
                    
                    <a href="{{ route('share-trans-lines.index') }}" class="btn btn-default">
                        <span class="glyphicon glyphicon-list"></span> {{ __('قائمة التفاصيل') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Approve transaction line
    $('#approveBtn').click(function() {
        if (confirm('{{ __("هل أنت متأكد من اعتماد هذا السطر؟") }}')) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.ajax({
                url: '/share-trans-lines/' + {{ $shareTransLine->id }} + '/toggle-posted',
                type: 'POST',
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    alert('{{ __("حدث خطأ أثناء اعتماد السطر.") }}');
                }
            });
        }
    });
    
    // Disapprove transaction line
    $('#disapproveBtn').click(function() {
        if (confirm('{{ __("هل أنت متأكد من إلغاء اعتماد هذا السطر؟") }}')) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.ajax({
                url: '/share-trans-lines/' + {{ $shareTransLine->id }} + '/toggle-posted',
                type: 'POST',
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    alert('{{ __("حدث خطأ أثناء إلغاء اعتماد السطر.") }}');
                }
            });
        }
    });
});
</script>
@endpush
