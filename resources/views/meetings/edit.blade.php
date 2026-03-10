@extends('layouts.app')

@section('title', __('تعديل الاجتماع'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('تعديل الاجتماع') }}
                        <div class="pull-left">
                            <a href="{{ route('meetings.index') }}" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('رجوع') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <form action="{{ route('meetings.update', $meeting) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group @error('name') has-error @enderror">
                            <label for="name">{{ __('اسم الاجتماع') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" 
                                   value="{{ old('name', $meeting->name) }}" required maxlength="255"
                                   placeholder="{{ __('أدخل اسم الاجتماع') }}">
                            @error('name')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('url') has-error @enderror">
                            <label for="url">{{ __('رابط الاجتماع') }} <span class="text-danger">*</span></label>
                            <input type="url" name="url" id="url" class="form-control" 
                                   value="{{ old('url', $meeting->url) }}" required maxlength="500"
                                   placeholder="{{ __('أدخل رابط الاجتماع (مثال: https://zoom.us/j/123456789)') }}">
                            @error('url')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('date') has-error @enderror">
                            <label for="date">{{ __('تاريخ ووقت الاجتماع') }} <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="date" id="date" class="form-control" 
                                   value="{{ old('date', $meeting->date->format('Y-m-d\TH:i')) }}" required>
                            @error('date')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('user_ids') has-error @enderror">
                            <label for="user_ids">{{ __('المستخدمين المدعوين للاجتماع') }}</label>
                            <select name="user_ids[]" id="user_ids" class="form-control" multiple size="10">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" 
                                        {{ in_array($user->id, old('user_ids', $meeting->users->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">
                                {{ __('اضغط مع Ctrl (أو Cmd في Mac) لاختيار عدة مستخدمين') }}
                            </small>
                            @error('user_ids')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>{{ __('إضافة مرفقات جديدة') }}</label>
                            <div id="attachments-container">
                                <div class="attachment-item" style="margin-bottom: 15px;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="file" name="attachments[]" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="attachment_descriptions[]" class="form-control" 
                                                   placeholder="{{ __('وصف المرفق (اختياري)') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-info" onclick="addAttachmentField()">
                                <i class="fa fa-plus"></i> {{ __('إضافة مرفق آخر') }}
                            </button>
                            <small class="text-muted display-block">
                                {{ __('الحد الأقصى لحجم كل ملف: 20 ميجابايت') }}
                            </small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> {{ __('تحديث') }}
                            </button>
                            <a href="{{ route('meetings.index') }}" class="btn btn-default">
                                <i class="fa fa-times"></i> {{ __('إلغاء') }}
                            </a>
                        </div>
                    </form>

                    <script>
                        function addAttachmentField() {
                            const container = document.getElementById('attachments-container');
                            const newField = document.createElement('div');
                            newField.className = 'attachment-item';
                            newField.style.marginBottom = '15px';
                            newField.innerHTML = `
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="file" name="attachments[]" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text" name="attachment_descriptions[]" class="form-control" 
                                                   placeholder="{{ __('وصف المرفق (اختياري)') }}">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-danger" onclick="removeAttachmentField(this)">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            `;
                            container.appendChild(newField);
                        }

                        function removeAttachmentField(button) {
                            button.closest('.attachment-item').remove();
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

