@extends('layouts.app')

@section('title', __('معاملات الأسهم'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('معاملات الأسهم') }}
                        <div class="pull-left">
                            <a href="{{ route('shares-trans.create') }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i> {{ __('إضافة معاملة جديدة') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    @if($sharesTrans->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('رقم المعاملة') }}</th>
                                        <th>{{ __('التاريخ') }}</th>
                                        <th>{{ __('نوع المعاملة') }}</th>
                                        <th>{{ __('الملاحظات') }}</th>
                                        <th>{{ __('عدد التفاصيل') }}</th>
                                        <th>{{ __('الحالة') }}</th>
                                        <th>{{ __('الإجراءات') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sharesTrans as $sharesTran)
                                        <tr>
                                            <td>{{ $sharesTran->id }}</td>
                                            <td>{{ $sharesTran->date->format('Y-m-d') }}</td>
                                            <td>
                                                <span class="label label-{{ $sharesTran->trans_type == 1 ? 'success' : ($sharesTran->trans_type == 2 ? 'danger' : 'info') }}">
                                                    {{ $sharesTran->getTransTypeText() }}
                                                </span>
                                            </td>
                                            <td>{{ Str::limit($sharesTran->notes ?? __('لا توجد ملاحظات'), 50) }}</td>
                                            <td>
                                                <span class="badge">{{ $sharesTran->shareTransLines->count() }}</span>
                                            </td>
                                            <td>
                                                @if($sharesTran->posted)
                                                    <span class="label label-success">{{ __('معتمد') }}</span>
                                                @else
                                                    <span class="label label-warning">{{ __('غير معتمد') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('shares-trans.show', $sharesTran) }}" class="btn btn-info btn-xs">
                                                        <i class="fa fa-eye"></i> {{ __('عرض') }}
                                                    </a>
                                                    <a href="{{ route('shares-trans.edit', $sharesTran) }}" class="btn btn-warning btn-xs">
                                                        <i class="fa fa-edit"></i> {{ __('تعديل') }}
                                                    </a>
                                                    @if(!$sharesTran->posted)
                                                        <button type="button" class="btn btn-success btn-xs post-transaction" data-id="{{ $sharesTran->id }}">
                                                            <i class="fa fa-check"></i> {{ __('اعتماد') }}
                                                        </button>
                                                    @endif
                                                    <form action="{{ route('shares-trans.destroy', $sharesTran) }}" method="POST" style="display: inline;" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذه المعاملة؟') }}');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-xs">
                                                            <i class="fa fa-trash"></i> {{ __('حذف') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Statistics -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">{{ __('إحصائيات المعاملات') }}</h4>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <strong>{{ __('إجمالي المعاملات') }}:</strong> {{ $sharesTrans->total() }}
                                            </div>
                                            <div class="col-md-3">
                                                <strong>{{ __('المعتمدة') }}:</strong> {{ $sharesTrans->where('posted', true)->count() }}
                                            </div>
                                            <div class="col-md-3">
                                                <strong>{{ __('غير المعتمدة') }}:</strong> {{ $sharesTrans->where('posted', false)->count() }}
                                            </div>
                                            <div class="col-md-3">
                                                <strong>{{ __('إجمالي التفاصيل') }}:</strong> {{ $sharesTrans->sum(function($tran) { return $tran->shareTransLines->count(); }) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="text-center">
                            {{ $sharesTrans->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <h4>{{ __('لا توجد معاملات أسهم') }}</h4>
                            <p>{{ __('لم يتم إضافة أي معاملات أسهم بعد.') }}</p>
                            <a href="{{ route('shares-trans.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> {{ __('إضافة أول معاملة') }}
                            </a>
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
    $('.post-transaction').click(function() {
        const transactionId = $(this).data('id');
        const button = $(this);
        
        if (confirm('{{ __("هل أنت متأكد من اعتماد هذه المعاملة؟") }}')) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/shares-trans/' + transactionId + '/post',
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
});
</script>
@endpush