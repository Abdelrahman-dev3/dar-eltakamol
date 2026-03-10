@extends('layouts.app')

@section('title', __('التعاميم'))

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2>{{ __('التعاميم') }}</h2>

        <p>
            <a href="{{ route('circulars.create') }}" class="btn btn-success btn-sm">
                <i class="fa fa-plus"></i> {{ __('إضافة تعميم جديد') }}
            </a>
        </p>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>{{ __('اسم التعميم') }}</th>
                        <th>{{ __('الملف') }}</th>
                        <th>{{ __('حجم الملف') }}</th>
                        <th>{{ __('تاريخ الإضافة') }}</th>
                        <th>{{ __('الإجراءات') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($circulars as $circular)
                        <tr>
                            <td>{{ $circular->name }}</td>
                            <td>
                                <div class="file-info">
                                    <i class="fa {{ $circular->file_icon }} text-primary" style="font-size: 1.2rem; margin-left: 8px;"></i>
                                    <strong>{{ $circular->original_filename }}</strong>
                                </div>
                            </td>
                            <td>{{ $circular->file_size_human }}</td>
                            <td>{{ $circular->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('circulars.show', $circular) }}" class="btn btn-info btn-xs" title="{{ __('عرض التفاصيل') }}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('circulars.download', $circular) }}" class="btn btn-primary btn-xs" title="{{ __('تحميل الملف') }}" target="_blank">
                                        <i class="fa fa-download"></i>
                                    </a>
                                    <a href="{{ route('circulars.edit', $circular) }}" class="btn btn-warning btn-xs" title="{{ __('تعديل') }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('circulars.destroy', $circular) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا التعميم؟') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs" title="{{ __('حذف') }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">{{ __('لا توجد تعاميم') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $circulars->links() }}
    </div>
</div>
@endsection

