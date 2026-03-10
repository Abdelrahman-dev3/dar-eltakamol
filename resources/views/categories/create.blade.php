@extends('layouts.app')

@section('title', __('إضافة تصنيف جديد'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('إضافة تصنيف جديد') }}
                        <div class="pull-left">
                            <a href="{{ route('categories.index') }}" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('رجوع') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf

                        <div class="form-group @error('name') has-error @enderror">
                            <label for="name">{{ __('اسم التصنيف') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="{{ old('name') }}" required maxlength="255"
                                   placeholder="{{ __('أدخل اسم التصنيف') }}">
                            @error('name')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('parent_id') has-error @enderror">
                            <label for="parent_id">{{ __('التصنيف الرئيسي (اختياري)') }}</label>
                            <select name="parent_id" id="parent_id" class="form-control">
                                <option value="">{{ __('-- تصنيف رئيسي --') }}</option>
                                @foreach($parentCategories as $parentCategory)
                                    <option value="{{ $parentCategory->id }}" {{ old('parent_id') == $parentCategory->id ? 'selected' : '' }}>
                                        {{ $parentCategory->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">{{ __('اتركه فارغاً لإنشاء تصنيف رئيسي جديد') }}</small>
                            @error('parent_id')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> {{ __('حفظ') }}
                            </button>
                            <a href="{{ route('categories.index') }}" class="btn btn-default">
                                <i class="fa fa-times"></i> {{ __('إلغاء') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

