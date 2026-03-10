@extends('layouts.app')

@section('title', __('التصنيفات'))

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2>{{ __('التصنيفات') }}</h2>

        <p>
            <a href="{{ route('categories.create') }}" class="btn btn-success btn-sm">
                <i class="fa fa-plus"></i> {{ __('إضافة تصنيف جديد') }}
            </a>
        </p>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>{{ __('اسم التصنيف') }}</th>
                        <th>{{ __('عدد المستخدمين') }}</th>
                        <th>{{ __('تاريخ الإضافة') }}</th>
                        <th>{{ __('الإجراءات') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td>
                                <span class="badge badge-info">
                                    <i class="fa fa-users"></i> {{ $category->users_count }}
                                </span>
                            </td>
                            <td>{{ $category->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('categories.show', $category) }}" class="btn btn-info btn-xs" title="{{ __('عرض التفاصيل') }}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning btn-xs" title="{{ __('تعديل') }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا التصنيف؟') }}');">
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
                            <td colspan="4" class="text-center">{{ __('لا توجد تصنيفات') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $categories->links() }}
    </div>
</div>
@endsection

