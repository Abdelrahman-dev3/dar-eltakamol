@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">إضافة صلاحية جديدة</h3>
                </div>
                <div class="panel-body">
                    <form action="{{ route('permissions.store') }}" method="POST">
                        @csrf

                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name">اسم الصلاحية <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control" 
                                   value="{{ old('name') }}" 
                                   placeholder="مثال: إضافة مساهم جديد"
                                   required>
                            @if ($errors->has('name'))
                                <span class="help-block">{{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('slug') ? 'has-error' : '' }}">
                            <label for="slug">المعرّف (Slug) <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="slug" 
                                   id="slug" 
                                   class="form-control" 
                                   value="{{ old('slug') }}" 
                                   placeholder="مثال: contributors.create"
                                   required>
                            @if ($errors->has('slug'))
                                <span class="help-block">{{ $errors->first('slug') }}</span>
                            @endif
                            <span class="help-block">استخدم صيغة: module.action (مثال: contributors.create, users.edit)</span>
                        </div>

                        <div class="form-group {{ $errors->has('module') ? 'has-error' : '' }}">
                            <label for="module">الوحدة</label>
                            <select name="module" id="module" class="form-control">
                                <option value="">-- اختر الوحدة --</option>
                                @foreach($modules as $key => $value)
                                    <option value="{{ $key }}" {{ old('module') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('module'))
                                <span class="help-block">{{ $errors->first('module') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                            <label for="description">الوصف</label>
                            <textarea name="description" 
                                      id="description" 
                                      class="form-control" 
                                      rows="3" 
                                      placeholder="وصف مختصر للصلاحية">{{ old('description') }}</textarea>
                            @if ($errors->has('description'))
                                <span class="help-block">{{ $errors->first('description') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('category_ids') ? 'has-error' : '' }}">
                            <label for="category_ids">التصنيفات المسموح لها بهذه الصلاحية</label>
                            <select name="category_ids[]" 
                                    id="category_ids" 
                                    class="form-control" 
                                    multiple 
                                    size="8">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ in_array($category->id, old('category_ids', [])) ? 'selected' : '' }}>
                                        {{ $category->full_name }}
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
                                <i class="fa fa-save"></i> حفظ الصلاحية
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


