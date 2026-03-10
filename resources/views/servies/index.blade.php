@extends('layouts.app')

@section('title', __('الخدمات'))

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2>{{ __('الخدمات') }}</h2>
        <p>
            <a href="{{ route('servies.create') }}" class="btn btn-primary">{{ __('إضافة خدمة جديد') }}</a>
        </p>

        <div class="table-responsive">
            <table class="table table-striped table-bordered" style="text-align: center;">
                <thead>
                    <tr>
                        <th style="text-align: center;">#</th>
                        <th style="text-align: center;">{{ __('الاسم') }}</th>
                        <th style="text-align: center;">{{ __('تاريخ الانشاء') }}</th>
                        <th style="text-align: center;">{{ __('اخر تعديل') }}</th>
                        <th style="text-align: center;">{{ __('الإجراءات') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sercies as $servie)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $servie->name }}</td>
                            <td>{{ $servie->created_at->format('d/m/y') }}</td>
                            <td>{{ $servie->updated_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('servies.edit',$servie->id) }}" style="padding: 8px 14px;" class="btn btn-sm btn-primary">{{ __('تعديل') }}</a>
                                <form action="{{ route('servies.destroy',$servie->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="padding: 8px 14px;" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('هل أنت متأكد من الحذف؟') }}')">
                                        {{ __('حذف') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('لا توجد خدمات متاحة') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </div>
</div>
@endsection
