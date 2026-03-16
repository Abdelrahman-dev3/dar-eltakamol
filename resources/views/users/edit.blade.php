@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">تعديل المستخدم: {{ $user->name }}</h3>
                </div>
                <div class="panel-body">
                    <form action="{{ route('users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name">الاسم <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            @if ($errors->has('name'))
                                <span class="help-block">{{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                            <label for="email">البريد الإلكتروني <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            @if ($errors->has('email'))
                                <span class="help-block">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                            <label for="password">كلمة المرور الجديدة</label>
                            <input type="password" name="password" id="password" class="form-control">
                            @if ($errors->has('password'))
                                <span class="help-block">{{ $errors->first('password') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">تأكيد كلمة المرور الجديدة</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                        </div>

                        <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                            <label for="phone">رقم الهاتف</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                            @if ($errors->has('phone'))
                                <span class="help-block">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('id_number') ? 'has-error' : '' }}">
                            <label for="id_number">رقم الهوية</label>
                            <input type="text" name="id_number" id="id_number" class="form-control" value="{{ old('id_number', $user->id_number) }}">
                            @if ($errors->has('id_number'))
                                <span class="help-block">{{ $errors->first('id_number') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('department_id') ? 'has-error' : '' }}">
                            <label for="department_id">الإدارة</label>
                            <select name="department_id" id="department_id" class="form-control">
                                <option value="">-- اختر الإدارة --</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ old('department_id', optional($user->department)->id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}{{ $department->parent ? ' - ' . $department->parent->name : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('department_id'))
                                <span class="help-block">{{ $errors->first('department_id') }}</span>
                            @endif
                            @if($user->contributor && $user->contributor->departments->isNotEmpty())
                                <span class="help-block text-info">هذا المستخدم مرتبط بمساهم، لذلك ستُحفظ الإدارات من ملف المساهم وتُورّث له الصلاحيات تلقائيًا.</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> حفظ التغييرات
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-default">
                                <i class="fa fa-times"></i> إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
