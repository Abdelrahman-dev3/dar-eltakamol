@extends('layouts.app')

@section('title', __('تعديل التصنيف'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('تعديل التصنيف') }}
                        <div class="pull-left">
                            <a href="{{ route('categories.index') }}" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('رجوع') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <form action="{{ route('categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group @error('name') has-error @enderror">
                            <label for="name">{{ __('اسم التصنيف') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="{{ old('name', $category->name) }}" required maxlength="255"
                                   placeholder="{{ __('أدخل اسم التصنيف') }}">
                            @error('name')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> {{ __('تحديث') }}
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

