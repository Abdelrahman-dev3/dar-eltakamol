@extends('layouts.app')

@section('title', __('تعديل الملف'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('تعديل الملف') }}
                        <div class="pull-left">
                            <a href="{{ route('documents.index') }}" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('رجوع') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <form action="{{ route('documents.update', $document) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group @error('name') has-error @enderror">
                            <label for="name">{{ __('اسم الملف') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="{{ old('name', $document->name) }}" required maxlength="255"
                                   placeholder="{{ __('أدخل اسم الملف') }}">
                            @error('name')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>{{ __('الملف الحالي') }}</label>
                            <div class="well">
                                <div class="file-info">
                                    <i class="fa {{ $document->file_icon }} text-primary" style="font-size: 1.5rem; margin-left: 8px;"></i>
                                    <strong>{{ $document->original_filename }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ __('الحجم') }}: {{ $document->file_size_human }} |
                                        {{ __('التاريخ') }}: {{ $document->created_at->format('Y-m-d H:i') }}
                                    </small>
                                </div>
                                <br>
                                <a href="{{ route('documents.download', $document) }}" class="btn btn-primary btn-sm" target="_blank">
                                    <i class="fa fa-download"></i> {{ __('تحميل الملف الحالي') }}
                                </a>
                            </div>
                        </div>

                        <div class="form-group @error('file') has-error @enderror">
                            <label for="file">{{ __('استبدال الملف (اختياري)') }}</label>
                            <input type="file" name="file" id="file" class="form-control"
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif,.zip,.rar">
                            <small class="text-muted">
                                {{ __('اتركه فارغاً إذا كنت تريد الاحتفاظ بالملف الحالي') }}<br>
                                {{ __('الحد الأقصى لحجم الملف: 50 ميجابايت') }}<br>
                                {{ __('الأنواع المسموحة: PDF, Word, Excel, PowerPoint, صور، نصوص، أرشيف') }}
                            </small>
                            @error('file')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> {{ __('تحديث') }}
                            </button>
                            <a href="{{ route('documents.index') }}" class="btn btn-default">
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

