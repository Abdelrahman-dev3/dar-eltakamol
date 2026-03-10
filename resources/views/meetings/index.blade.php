@extends('layouts.app')

@section('title', __('الاجتماعات'))

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2>{{ __('الاجتماعات') }}</h2>
        
        <p>
            <a href="{{ route('meetings.create') }}" class="btn btn-success btn-sm">
                <i class="fa fa-plus"></i> {{ __('إضافة اجتماع جديد') }}
            </a>
        </p>
        
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>{{ __('اسم الاجتماع') }}</th>
                        <th>{{ __('الرابط') }}</th>
                        <th>{{ __('التاريخ') }}</th>
                        <th>{{ __('المدعوين') }}</th>
                        <th>{{ __('الإجراءات') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($meetings as $meeting)
                        <tr>
                            <td>{{ $meeting->name }}</td>
                            <td>
                                <a href="{{ $meeting->url }}" target="_blank" class="btn btn-link btn-xs">
                                    <i class="fa fa-external-link"></i> {{ __('فتح الرابط') }}
                                </a>
                            </td>
                            <td>{{ $meeting->date->format('Y-m-d H:i') }}</td>
                            <td>
                                <span class="badge badge-info" style="background-color: #5bc0de; font-size: 1.2rem;">
                                    <i class="fa fa-users"></i> {{ $meeting->users->count() }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('meetings.show', $meeting) }}" class="btn btn-info btn-xs">
                                        <i class="fa fa-eye"></i> {{ __('عرض') }}
                                    </a>
                                    <a href="{{ route('meetings.edit', $meeting) }}" class="btn btn-warning btn-xs">
                                        <i class="fa fa-edit"></i> {{ __('تعديل') }}
                                    </a>
                                    <form action="{{ route('meetings.destroy', $meeting) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا الاجتماع؟') }}');">
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
                            <td colspan="5" class="text-center">{{ __('لا توجد اجتماعات') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $meetings->links() }}
    </div>
</div>
@endsection

