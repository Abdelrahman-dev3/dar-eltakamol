@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">تفاصيل الصلاحية</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th width="30%">اسم الصلاحية</th>
                                <td>{{ $permission->name }}</td>
                            </tr>
                            <tr>
                                <th>المعرّف</th>
                                <td><code>{{ $permission->slug }}</code></td>
                            </tr>
                            <tr>
                                <th>الوحدة</th>
                                <td>{{ $permission->module ?? 'عام' }}</td>
                            </tr>
                            <tr>
                                <th>الوصف</th>
                                <td>{{ $permission->description ?? 'لا يوجد وصف' }}</td>
                            </tr>
                            <tr>
                                <th>تاريخ الإنشاء</th>
                                <td>{{ $permission->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        </tbody>
                    </table>

                    @if($permission->departments->isNotEmpty())
                        <h4 style="margin-top: 30px;">الإدارات المرتبطة</h4>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>اسم الإدارة</th>
                                        <th>الشركة</th>
                                        <th>عدد الأعضاء</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($permission->departments as $department)
                                        <tr>
                                            <td>
                                                <a href="{{ route('categories.show', $department) }}">{{ $department->name }}</a>
                                            </td>
                                            <td>{{ $department->parent?->name ?? '-' }}</td>
                                            <td>{{ $department->users->count() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info" style="margin-top: 20px;">
                            <i class="fa fa-info-circle"></i>
                            لا توجد إدارات مرتبطة بهذه الصلاحية
                        </div>
                    @endif

                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-12">
                            <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-warning">
                                <i class="fa fa-edit"></i> تعديل
                            </a>
                            <a href="{{ route('permissions.index') }}" class="btn btn-default">
                                <i class="fa fa-arrow-right"></i> العودة للقائمة
                            </a>
                            <form action="{{ route('permissions.destroy', $permission) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذه الصلاحية؟')">
                                    <i class="fa fa-trash"></i> حذف
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
