@extends('layouts.app')

@section('title', __('عرض تفاصيل التصنيف'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('عرض تفاصيل التصنيف') }} #{{ $category->id }}
                        <div class="pull-left">
                            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">
                                <span class="glyphicon glyphicon-edit"></span> {{ __('تعديل') }}
                            </a>
                            <a href="{{ route('categories.index') }}" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('رجوع') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">{{ __('معلومات التصنيف') }}</h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>{{ __('رقم التصنيف') }}:</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $category->id }}
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    <strong>{{ __('اسم التصنيف') }}:</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $category->name }}
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    <strong>{{ __('عدد المستخدمين') }}:</strong>
                                </div>
                                <div class="col-md-8">
                                    <span class="badge badge-info">
                                        <i class="fa fa-users"></i> {{ $category->users_count }}
                                    </span>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    <strong>{{ __('تاريخ الإضافة') }}:</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $category->created_at->format('Y-m-d H:i:s') }}
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    <strong>{{ __('آخر تحديث') }}:</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $category->updated_at->format('Y-m-d H:i:s') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($category->users->count() > 0)
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-users"></i> {{ __('المستخدمين في هذا التصنيف') }}
                                <span class="badge">{{ $category->users->count() }}</span>
                            </h4>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('الاسم') }}</th>
                                            <th>{{ __('البريد الإلكتروني') }}</th>
                                            <th>{{ __('رقم الهاتف') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($category->users as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->phone ?? __('غير محدد') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> {{ __('لا يوجد مستخدمين في هذا التصنيف') }}
                    </div>
                    @endif

                    <div class="panel-footer">
                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning">
                            <i class="fa fa-edit"></i> {{ __('تعديل') }}
                        </a>
                        <a href="{{ route('categories.index') }}" class="btn btn-default">
                            <i class="fa fa-arrow-right"></i> {{ __('رجوع') }}
                        </a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا التصنيف؟') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fa fa-trash"></i> {{ __('حذف') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

