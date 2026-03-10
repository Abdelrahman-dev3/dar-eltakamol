@extends('layouts.app')

@section('title', __('المساهمين'))

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2>{{ __('المساهمين') }}</h2>
        
        <p>
            <a href="{{ route('contributors.create') }}" class="btn btn-success btn-sm">
                <i class="fa fa-plus"></i> {{ __('إضافة مساهم جديد') }}
            </a>
        </p>
        
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>{{ __('الاسم') }}</th>
                        <th>{{ __('رقم الهوية') }}</th>
                        <th>{{ __('المنصب') }}</th>
                        <th>{{ __('عدد الأسهم') }}</th>
                        <th>{{ __('الإجراءات') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contributors as $contributor)
                        <tr>
                            <td>{{ $contributor->name }}</td>
                            <td>{{ $contributor->id_number }}</td>
                            <td>{{ $contributor->position }}</td>
                            <td>{{ number_format($contributor->share_count_cr ?? 0) }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('contributors.edit', $contributor) }}" class="btn btn-warning btn-xs">
                                        <i class="fa fa-edit"></i> {{ __('تعديل') }}
                                    </a>
                                    <a href="{{ route('contributors.show', $contributor) }}" class="btn btn-info btn-xs">
                                        <i class="fa fa-eye"></i> {{ __('تفاصيل') }}
                                    </a>
                                    <form action="{{ route('contributors.destroy', $contributor) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('{{ __('هل أنت متأكد من الحذف؟') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs">
                                            <i class="fa fa-trash"></i> {{ __('حذف') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">{{ __('لا توجد بيانات') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $contributors->links() }}
    </div>
</div>
@endsection
