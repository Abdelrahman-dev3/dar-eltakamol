@extends('layouts.app')

@section('title', __('أنواع الأرباح'))

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    {{ __('أنواع الأرباح') }}
                    <div class="pull-left">
                        <a href="{{ route('profits.create') }}" class="btn btn-success btn-sm">
                            <i class="fa fa-plus"></i> {{ __('إضافة نوع ربح جديد') }}
                        </a>
                    </div>
                </h3>
            </div>
            <div class="panel-body">
                @if($profits->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('تاريخ الربح') }}</th>
                                    <th>{{ __('تاريخ الانتهاء') }}</th>
                                    <th>{{ __('المبلغ') }}</th>
                                    <th>{{ __('الحالة') }}</th>
                                    <th>{{ __('عدد المستخدمين') }}</th>
                                    <th>{{ __('الإجراءات') }}</th>
                                </tr>
                            </thead>
                            <tbody>


@foreach($profits as $profit)
                                <tr>
                                    <td>{{ $profit->date->format('Y-m-d') }}</td>
                                    <td>{{ $profit->end_date->format('Y-m-d') }}</td>
                                    <td>{{ number_format($profit->amount, 2) }} {{ __('ريال') }}</td>
                                    <td>
                                        @if($profit->confirmed)
                                            <span class="label label-success">{{ __('مؤكد') }}</span>
                                        @else
                                            <span class="label label-warning">{{ __('غير مؤكد') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $profit->usersProfits->count() }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('profits.show', $profit) }}" class="btn btn-info btn-xs">
                                                <i class="fa fa-eye"></i> {{ __('عرض') }}
                                            </a>
                                            <a href="{{ route('profits.edit', $profit) }}" class="btn btn-warning btn-xs">
                                                <i class="fa fa-edit"></i> {{ __('تعديل') }}
                                            </a>
                                            <button type="button" class="btn btn-{{ $profit->confirmed ? 'danger' : 'success' }} btn-xs toggle-status" 
                                                    data-profit-id="{{ $profit->id }}" 
                                                    data-confirmed="{{ $profit->confirmed }}">
                                                <i class="fa fa-{{ $profit->confirmed ? 'ban' : 'check' }}"></i> 
                                                {{ $profit->confirmed ? __('إلغاء التأكيد') : __('تأكيد') }}
                                            </button>
                                            <a href="{{ route('users-profits.index', ['profit_id' => $profit->id]) }}" class="btn btn-primary btn-xs">
                                                <i class="fa fa-users"></i> {{ __('المستخدمين') }}
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('إجمالي الأرباح المؤكدة') }}:</strong>
                        {{ number_format($profits->where('confirmed', true)->sum('amount'), 2) }} {{ __('ريال') }}
                    </div>
                    <div class="col-md-6">
                        <strong>{{ __('إجمالي الأرباح غير المؤكدة') }}:</strong>
                        {{ number_format($profits->where('confirmed', false)->sum('amount'), 2) }} {{ __('ريال') }}
                    </div>
                </div>
                
                <div class="text-center">
                    {{ $profits->links() }}
                </div>
            @else
                <div class="alert alert-info text-center">
                    <h4>{{ __('لا توجد أنواع أرباح') }}</h4>
                    <p>{{ __('لم يتم إنشاء أي أنواع أرباح بعد.') }}</p>
                        <a href="{{ route('profits.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> {{ __('إنشاء أول نوع ربح') }}
                        </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.toggle-status').click(function() {
        const profitId = $(this).data('profit-id');
        const isConfirmed = $(this).data('confirmed');
        const newStatus = !isConfirmed;
        
        if (confirm(isConfirmed ? 
            '{{ __("هل تريد إلغاء تأكيد هذا الربح؟") }}' : 
            '{{ __("هل تريد تأكيد هذا الربح؟") }}')) {
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.ajax({
                url: '/profits/' + profitId + '/toggle-active',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    alert('{{ __("حدث خطأ أثناء تحديث حالة الربح.") }}');
                }
            });
        }
    });
});
</script>
@endpush
