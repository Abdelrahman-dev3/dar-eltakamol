@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">تفاصيل المستخدم</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">الاسم</th>
                                        <td>{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>البريد الإلكتروني</th>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>رقم الهاتف</th>
                                        <td>{{ $user->phone ?? 'غير محدد' }}</td>
                                    </tr>
                                    <tr>
                                        <th>رقم الهوية</th>
                                        <td>{{ $user->id_number ?? 'غير محدد' }}</td>
                                    </tr>
                                    <tr>
                                        <th>التصنيفات</th>
                                        <td>
                                            @if($user->categories->isNotEmpty())
                                                @foreach($user->categories as $category)
                                                    <span class="label label-info" style="margin: 2px;">
                                                        {{ $category->name }}
                                                        @if($category->parent)
                                                            <small>({{ $category->parent->name }})</small>
                                                        @endif
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">بدون تصنيف</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>تاريخ الإنشاء</th>
                                        <td>{{ $user->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>آخر تحديث</th>
                                        <td>{{ $user->updated_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-12">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                                <i class="fa fa-edit"></i> تعديل
                            </a>
                            <a href="{{ route('users.index') }}" class="btn btn-default">
                                <i class="fa fa-arrow-right"></i> العودة للقائمة
                            </a>
                            @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                                        <i class="fa fa-trash"></i> حذف
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


