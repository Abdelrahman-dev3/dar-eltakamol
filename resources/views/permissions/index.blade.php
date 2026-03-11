@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title" style="display: inline-block;">إدارة الصلاحيات</h3>
                    <a href="{{ route('permissions.create') }}" class="btn btn-success btn-sm pull-left">
                        <i class="fa fa-plus"></i> إضافة صلاحية جديدة
                    </a>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($permissions->isEmpty())
                        <div class="alert alert-info">لا توجد صلاحيات في النظام حالياً.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>اسم الصلاحية</th>
                                        <th>المعرّف</th>
                                        <th>الوحدة</th>
                                        <th>الإدارات المرتبطة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($permissions as $permission)
                                        <tr>
                                            <td>{{ $permission->id }}</td>
                                            <td>{{ $permission->name }}</td>
                                            <td><code>{{ $permission->slug }}</code></td>
                                            <td>{{ $permission->module ?? 'عام' }}</td>
                                            <td>
                                                @if($permission->departments->isNotEmpty())
                                                    @foreach($permission->departments->take(3) as $department)
                                                        <span class="label label-info" style="margin: 2px;">{{ $department->full_name }}</span>
                                                    @endforeach
                                                    @if($permission->departments->count() > 3)
                                                        <span class="text-muted">+{{ $permission->departments->count() - 3 }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">بدون إدارات</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('permissions.show', $permission) }}" class="btn btn-info btn-xs" title="عرض">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-warning btn-xs" title="تعديل">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('permissions.destroy', $permission) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-xs" title="حذف" onclick="return confirm('هل أنت متأكد من حذف هذه الصلاحية؟')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center">
                            {{ $permissions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
