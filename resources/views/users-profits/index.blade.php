@extends('layouts.app')

@section('title', __('أرباح المستخدمين'))

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    {{ __('أرباح المستخدمين') }}
                    <div class="pull-left">
                        <a href="{{ route('users-profits.create') }}" class="btn btn-success btn-sm">
                            <i class="fa fa-plus"></i> {{ __('إضافة توزيعة جديدة') }}
                        </a>
                    </div>
                </h3>
            </div>
            <div class="panel-body">
                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <form method="GET" class="form-inline">
                            <div class="form-group">
                                <label for="profit_id">{{ __('نوع الربح') }}:</label>
                                <select name="profit_id" id="profit_id" class="form-control">
                                    <option value="">{{ __('جميع الأنواع') }}</option>
                                    @foreach(\App\Models\Profit::all() as $profit)
                                        <option value="{{ $profit->id }}" {{ request('profit_id') == $profit->id ? 'selected' : '' }}>
                                            {{ $profit->date->format('Y-m-d') }} - {{ number_format($profit->amount, 2) }} {{ __('ريال') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status">{{ __('الحالة') }}:</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">{{ __('جميع الحالات') }}</option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>{{ __('مدفوع') }}</option>
                                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>{{ __('غير مدفوع') }}</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i> {{ __('بحث') }}
                            </button>
                            <a href="{{ route('users-profits.index') }}" class="btn btn-default">
                                <i class="fa fa-refresh"></i> {{ __('إعادة تعيين') }}
                            </a>
                        </form>
                    </div>
                </div>

                @if($usersProfits->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('المستخدم') }}</th>
                                    <th>{{ __('اسم المستخدم') }}</th>
                                    <th>{{ __('نوع الربح') }}</th>
                                    <th>{{ __('تاريخ الربح') }}</th>
                                    <th>{{ __('المبلغ المخصص') }}</th>
                                    <th>{{ __('الحالة') }}</th>
                                    <th>{{ __('الإجراءات') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usersProfits as $userProfit)
                                    <tr>
                                        <td>{{ $userProfit->contributor->user->name ?? __('غير معروف') }}</td>
                                        <td>{{ $userProfit->contributor->name ?? __('غير معروف') }}</td>
                                        <td>{{ $userProfit->profit->date->format('Y-m-d') }}</td>
                                        <td>{{ $userProfit->profit->date->format('Y-m-d') }}</td>
                                        <td>{{ number_format($userProfit->amount, 2) }} {{ __('ريال') }}</td>
                                        <td>
                                            @if($userProfit->is_paid ?? false)
                                                <span class="label label-success">{{ __('مدفوع') }}</span>
                                            @else
                                                <span class="label label-warning">{{ __('غير مدفوع') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('users-profits.show', $userProfit) }}" class="btn btn-info btn-xs">
                                                    <i class="fa fa-eye"></i> {{ __('عرض') }}
                                                </a>
                                                <a href="{{ route('users-profits.edit', $userProfit) }}" class="btn btn-warning btn-xs">
                                                    <i class="fa fa-edit"></i> {{ __('تعديل') }}
                                                </a>
                                                @if(!($userProfit->is_paid ?? false))
                                                    <button type="button" class="btn btn-success btn-xs mark-paid" 
                                                            data-user-profit-id="{{ $userProfit->id }}">
                                                        <i class="fa fa-check"></i> {{ __('دفع') }}
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-danger btn-xs mark-unpaid" 
                                                            data-user-profit-id="{{ $userProfit->id }}">
                                                        <i class="fa fa-times"></i> {{ __('إلغاء الدفع') }}
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="info">
                                    <td colspan="4"><strong>{{ __('المجموع') }}</strong></td>
                                    <td><strong>{{ number_format($usersProfits->sum('amount'), 2) }} {{ __('ريال') }}</strong></td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{ $usersProfits->appends(request()->query())->links() }}
                @else
                    <div class="alert alert-info text-center">
                        <h4>{{ __('لا توجد توزيعات أرباح') }}</h4>
                        <p>{{ __('لم يتم إنشاء أي توزيعات أرباح بعد.') }}</p>
                            <a href="{{ route('users-profits.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> {{ __('إنشاء أول توزيعة') }}
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
    // Mark as paid
    $('.mark-paid').click(function() {
        const userProfitId = $(this).data('user-profit-id');
        
        if (confirm('{{ __("هل أنت متأكد من تسجيل هذا الربح كمُدفوع؟") }}')) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.ajax({
                url: '/users-profits/' + userProfitId + '/mark-paid',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    alert('{{ __("حدث خطأ أثناء تحديث حالة الدفع.") }}');
                }
            });
        }
    });
    
    // Mark as unpaid
    $('.mark-unpaid').click(function() {
        const userProfitId = $(this).data('user-profit-id');
        
        if (confirm('{{ __("هل أنت متأكد من إلغاء تسجيل هذا الربح كمُدفوع؟") }}')) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.ajax({
                url: '/users-profits/' + userProfitId + '/mark-paid',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    mark_as_paid: false
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    alert('{{ __("حدث خطأ أثناء تحديث حالة الدفع.") }}');
                }
            });
        }
    });
});
</script>
@endpush
