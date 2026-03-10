@extends('layouts.app')

@section('title', __('تفاصيل معاملات الأسهم'))

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    {{ __('تفاصيل معاملات الأسهم') }}
                    <div class="pull-left">
                        <a href="{{ route('share-trans-lines.create') }}" class="btn btn-success btn-sm">
                            <i class="fa fa-plus"></i> {{ __('إضافة تفاصيل جديدة') }}
                        </a>
                    </div>
                </h3>
            </div>
            <div class="panel-body">
                @if($shareTransLines->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('المساهم') }}</th>
                                    <th>{{ __('رقم المعاملة') }}</th>
                                    <th>{{ __('الخصم') }}</th>
                                    <th>{{ __('الدائن') }}</th>
                                    <th>{{ __('سعر السهم') }}</th>
                                    <th>{{ __('ملاحظات السطر') }}</th>
                                    <th>{{ __('حالة الاعتماد') }}</th>
                                    <th>{{ __('الإجراءات') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shareTransLines as $line)
                                    <tr>
                                        <td>{{ $line->contributor->name ?? __('غير معروف') }}</td>
                                        <td>{{ $line->trans_id }}</td>
                                        <td>{{ number_format($line->count_debit, 2) }}</td>
                                        <td>{{ number_format($line->count_credit, 2) }}</td>
                                        <td>{{ number_format($line->amount_per_share, 2) }} {{ __('ريال') }}</td>
                                        <td>{{ $line->line_notes }}</td>
                                        <td>
                                            @if($line->posted)
                                                <span class="label label-success">{{ __('معتمد') }}</span>
                                            @else
                                                <span class="label label-warning">{{ __('غير معتمد') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('share-trans-lines.show', $line) }}" class="btn btn-info btn-xs">
                                                    <i class="fa fa-eye"></i> {{ __('عرض') }}
                                                </a>
                                                <a href="{{ route('share-trans-lines.edit', $line) }}" class="btn btn-warning btn-xs">
                                                    <i class="fa fa-edit"></i> {{ __('تعديل') }}
                                                </a>
                                                <button type="button" class="btn btn-{{ $line->posted ? 'danger' : 'success' }} btn-xs toggle-posted" 
                                                        data-line-id="{{ $line->id }}" 
                                                        data-posted="{{ $line->posted }}">
                                                    <i class="fa fa-{{ $line->posted ? 'ban' : 'check' }}"></i> 
                                                    {{ $line->posted ? __('إلغاء الاعتماد') : __('اعتماد') }}
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="info">
                                    <td colspan="2"><strong>{{ __('المجموع') }}</strong></td>
                                    <td><strong>{{ number_format($shareTransLines->sum('count_debit'), 2) }}</strong></td>
                                    <td><strong>{{ number_format($shareTransLines->sum('count_credit'), 2) }}</strong></td>
                                    <td colspan="4"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{ $shareTransLines->links() }}
                @else
                    <div class="alert alert-info text-center">
                        <h4>{{ __('لا توجد تفاصيل معاملات') }}</h4>
                        <p>{{ __('لم يتم إنشاء أي تفاصيل معاملات بعد.') }}</p>
                            <a href="{{ route('share-trans-lines.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> {{ __('إنشاء أول تفصيل') }}
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
    $('.toggle-posted').click(function() {
        const lineId = $(this).data('line-id');
        const isPosted = $(this).data('posted');
        
        if (confirm(isPosted ? 
            '{{ __("هل تريد إلغاء اعتماد هذا السطر؟") }}' : 
            '{{ __("هل تريد اعتماد هذا السطر؟") }}')) {
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.ajax({
                url: '/share-trans-lines/' + lineId + '/toggle-posted',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    alert('{{ __("حدث خطأ أثناء تحديث حالة الاعتماد.") }}');
                }
            });
        }
    });
});
</script>
@endpush
