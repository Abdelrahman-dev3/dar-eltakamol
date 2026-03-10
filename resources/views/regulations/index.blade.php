@extends('layouts.app')

@section('title', __('اللوائح'))

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2>{{ __('اللوائح') }}</h2>

        <p>
            <a href="{{ route('regulations.create') }}" class="btn btn-success btn-sm">
                <i class="fa fa-plus"></i> {{ __('إضافة لائحة جديدة') }}
            </a>
        </p>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>{{ __('اسم اللائحة') }}</th>
                        <th>{{ __('الملف') }}</th>
                        <th>{{ __('حجم الملف') }}</th>
                        <th>{{ __('تاريخ الإضافة') }}</th>
                        <th>{{ __('الإجراءات') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($regulations as $regulation)
                        <tr>
                            <td>{{ $regulation->name }}</td>
                            <td>
                                <div class="file-info">
                                    <i class="fa {{ $regulation->file_icon }} text-primary" style="font-size: 1.2rem; margin-left: 8px;"></i>
                                    <strong>{{ $regulation->original_filename }}</strong>
                                </div>
                            </td>
                            <td>{{ $regulation->file_size_human }}</td>
                            <td>{{ $regulation->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('regulations.show', $regulation) }}" class="btn btn-info btn-xs" title="{{ __('عرض التفاصيل') }}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('regulations.download', $regulation) }}" class="btn btn-primary btn-xs" title="{{ __('تحميل الملف') }}" target="_blank">
                                        <i class="fa fa-download"></i>
                                    </a>
                                    <a href="{{ route('regulations.edit', $regulation) }}" class="btn btn-warning btn-xs" title="{{ __('تعديل') }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('regulations.destroy', $regulation) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذه اللائحة؟') }}');">
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
                            <td colspan="5" class="text-center">{{ __('لا توجد لوائح') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $regulations->links() }}
    </div>
</div>
@endsection

