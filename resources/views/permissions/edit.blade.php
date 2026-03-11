@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">تعديل الصلاحية: {{ $permission->name }}</h3>
                </div>
                <div class="panel-body">
                    <form action="{{ route('permissions.update', $permission) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name">اسم الصلاحية <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $permission->name) }}" required>
                        </div>

                        <div class="form-group {{ $errors->has('slug') ? 'has-error' : '' }}">
                            <label for="slug">المعرّف (Slug) <span class="text-danger">*</span></label>
                            <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $permission->slug) }}" required>
                        </div>

                        <div class="form-group {{ $errors->has('module') ? 'has-error' : '' }}">
                            <label for="module">الوحدة</label>
                            <select name="module" id="module" class="form-control">
                                <option value="">-- اختر الوحدة --</option>
                                @foreach($modules as $key => $value)
                                    <option value="{{ $key }}" {{ old('module', $permission->module) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                            <label for="description">الوصف</label>
                            <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $permission->description) }}</textarea>
                        </div>

                        <div class="form-group {{ $errors->has('department_ids') ? 'has-error' : '' }}">
                            <label for="department_ids">الإدارات المرتبطة بهذه الصلاحية</label>
                            <select name="department_ids[]" id="department_ids" class="form-control" multiple size="8">
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ in_array($department->id, old('department_ids', $permission->departments->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $department->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('department_ids'))
                                <span class="help-block">{{ $errors->first('department_ids') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> حفظ التغييرات
                            </button>
                            <a href="{{ route('permissions.index') }}" class="btn btn-default">
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
