@extends('layouts.app')

@section('title', __('إضافة ملف جديد'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('إضافة ملف جديد') }}
                        <div class="pull-left">
                            <a href="{{ route('documents.index') }}" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('رجوع') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group @error('name') has-error @enderror">
                            <label for="name">{{ __('الاسم (اختياري)') }}</label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="{{ old('name') }}" maxlength="255"
                                   placeholder="{{ __('سيتم استخدام اسم الملف إذا ترك فارغاً') }}">
                            <small class="text-muted">{{ __('اترك فارغاً لاستخدام أسماء الملفات الأصلية') }}</small>
                            @error('name')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('meeting_id') has-error @enderror">
                            <label for="meeting_id">{{ __('ربط باجتماع (اختياري)') }}</label>
                            <select name="meeting_id" id="meeting_id" class="form-control">
                                <option value="">-- {{ __('بدون اجتماع') }} --</option>
                                @foreach(\App\Models\Meeting::orderBy('date', 'desc')->get() as $meeting)
                                    <option value="{{ $meeting->id }}" {{ old('meeting_id') == $meeting->id ? 'selected' : '' }}>
                                        {{ $meeting->name }} - {{ $meeting->date->format('Y-m-d') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('meeting_id')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('files') has-error @enderror">
                            <label for="files">{{ __('الملفات') }} <span class="text-danger">*</span></label>
                            <input type="file" name="files[]" id="files" class="form-control" required multiple
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif,.zip,.rar">
                            <small class="text-muted">
                                {{ __('يمكنك اختيار ملفات متعددة') }}<br>
                                {{ __('الحد الأقصى لحجم كل ملف: 50 ميجابايت') }}<br>
                                {{ __('الأنواع المسموحة: PDF, Word, Excel, PowerPoint, صور، نصوص، أرشيف') }}
                            </small>
                            @error('files')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                            @error('files.*')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> {{ __('حفظ') }}
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

