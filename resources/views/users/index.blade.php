@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title" style="display: inline-block;">إدارة المستخدمين</h3>
                    <a href="{{ route('users.create') }}" class="btn btn-success btn-sm pull-left">
                        <i class="fa fa-plus"></i> إضافة مستخدم جديد
                    </a>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($users->isEmpty())
                        <div class="alert alert-info">
                            لا توجد مستخدمين في النظام حالياً.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الاسم</th>
                                        <th>البريد الإلكتروني</th>
                                        <th>رقم الهاتف</th>
                                        <th>التصنيفات</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone ?? 'غير محدد' }}</td>
                                            <td>
                                                @if($user->categories->isNotEmpty())
                                                    @foreach($user->categories as $category)
                                                        <span class="label label-info">{{ $category->name }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">بدون تصنيف</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <a href="{{ route('users.show', $user) }}" class="btn btn-info btn-xs" title="عرض">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-xs" title="تعديل">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                @if($user->id !== auth()->id())
                                                    <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-xs" title="حذف" onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


