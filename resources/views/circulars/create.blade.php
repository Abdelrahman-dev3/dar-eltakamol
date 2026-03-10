@extends('layouts.app')

@section('title', __('إضافة تعميم جديد'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('إضافة تعميم جديد') }}
                        <div class="pull-left">
                            <a href="{{ route('circulars.index') }}" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('رجوع') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <form action="{{ route('circulars.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group @error('name') has-error @enderror">
                            <label for="name">{{ __('اسم التعميم') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="{{ old('name') }}" required maxlength="255"
                                   placeholder="{{ __('أدخل اسم التعميم') }}">
                            @error('name')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('file') has-error @enderror">
                            <label for="file">{{ __('ملف التعميم') }} <span class="text-danger">*</span></label>
                            <input type="file" name="file" id="file" class="form-control" required
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif,.zip,.rar">
                            <small class="text-muted">
                                {{ __('الحد الأقصى لحجم الملف: 50 ميجابايت') }}<br>
                                {{ __('الأنواع المسموحة: PDF, Word, Excel, PowerPoint, صور، نصوص، أرشيف') }}
                            </small>
                            @error('file')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> {{ __('حفظ') }}
                            </button>
                            <a href="{{ route('circulars.index') }}" class="btn btn-default">
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

