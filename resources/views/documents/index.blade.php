@extends('layouts.app')

@section('title', __('الملفات'))

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2>{{ __('الملفات') }}</h2>

        <p>
            <a href="{{ route('documents.create') }}" class="btn btn-success btn-sm">
                <i class="fa fa-plus"></i> {{ __('إضافة ملف جديد') }}
            </a>
        </p>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>{{ __('اسم الملف') }}</th>
                        <th>{{ __('الملف') }}</th>
                        <th>{{ __('حجم الملف') }}</th>
                        <th>{{ __('تاريخ الإضافة') }}</th>
                        <th>{{ __('الإجراءات') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $document)
                        <tr>
                            <td>{{ $document->name }}</td>
                            <td>
                                <div class="file-info">
                                    <i class="fa {{ $document->file_icon }} text-primary" style="font-size: 1.2rem; margin-left: 8px;"></i>
                                    <strong>{{ $document->original_filename }}</strong>
                                </div>
                            </td>
                            <td>{{ $document->file_size_human }}</td>
                            <td>{{ $document->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('documents.show', $document) }}" class="btn btn-info btn-xs" title="{{ __('عرض التفاصيل') }}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('documents.download', $document) }}" class="btn btn-primary btn-xs" title="{{ __('تحميل الملف') }}" target="_blank">
                                        <i class="fa fa-download"></i>
                                    </a>
                                    <a href="{{ route('documents.edit', $document) }}" class="btn btn-warning btn-xs" title="{{ __('تعديل') }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('documents.destroy', $document) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا الملف؟') }}');">
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
                            <td colspan="5" class="text-center">{{ __('لا توجد ملفات') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $documents->links() }}
    </div>
</div>
@endsection

