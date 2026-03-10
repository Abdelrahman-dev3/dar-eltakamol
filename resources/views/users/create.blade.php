@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">إضافة مستخدم جديد</h3>
                </div>
                <div class="panel-body">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf

                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name">الاسم <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control" 
                                   value="{{ old('name') }}" 
                                   required>
                            @if ($errors->has('name'))
                                <span class="help-block">{{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                            <label for="email">البريد الإلكتروني <span class="text-danger">*</span></label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="form-control" 
                                   value="{{ old('email') }}" 
                                   required>
                            @if ($errors->has('email'))
                                <span class="help-block">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                            <label for="password">كلمة المرور <span class="text-danger">*</span></label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="form-control" 
                                   required>
                            @if ($errors->has('password'))
                                <span class="help-block">{{ $errors->first('password') }}</span>
                            @endif
                            <span class="help-block">يجب أن تكون كلمة المرور 8 أحرف على الأقل</span>
                        </div>

                        <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                            <label for="password_confirmation">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   class="form-control" 
                                   required>
                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">{{ $errors->first('password_confirmation') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                            <label for="phone">رقم الهاتف</label>
                            <input type="text" 
                                   name="phone" 
                                   id="phone" 
                                   class="form-control" 
                                   value="{{ old('phone') }}">
                            @if ($errors->has('phone'))
                                <span class="help-block">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('id_number') ? 'has-error' : '' }}">
                            <label for="id_number">رقم الهوية</label>
                            <input type="text" 
                                   name="id_number" 
                                   id="id_number" 
                                   class="form-control" 
                                   value="{{ old('id_number') }}">
                            @if ($errors->has('id_number'))
                                <span class="help-block">{{ $errors->first('id_number') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('category_ids') ? 'has-error' : '' }}">
                            <label for="category_ids">التصنيفات</label>
                            <select name="category_ids[]" 
                                    id="category_ids" 
                                    class="form-control" 
                                    multiple 
                                    size="5">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ in_array($category->id, old('category_ids', [])) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                        @if($category->parent)
                                            (فرع من: {{ $category->parent->name }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('category_ids'))
                                <span class="help-block">{{ $errors->first('category_ids') }}</span>
                            @endif
                            <span class="help-block">
                                <i class="fa fa-info-circle"></i>
                                اضغط مع الاستمرار على Ctrl (Windows) أو Command (Mac) لتحديد عدة تصنيفات
                            </span>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> حفظ المستخدم
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


