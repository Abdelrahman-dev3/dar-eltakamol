@extends('layouts.app')

@section('title', __('إضافة مساهم جديد'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('إضافة مساهم جديد') }}
                        <div class="pull-left">
                            <a href="{{ route('contributors.index') }}" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('رجوع') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <form action="{{ route('contributors.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group @error('name') has-error @enderror">
                            <label for="name">{{ __('الاسم') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" 
                                   value="{{ old('name') }}" required maxlength="100"
                                   placeholder="{{ __('أدخل اسم المساهم') }}">
                            @error('name')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('id_number') has-error @enderror">
                            <label for="id_number">{{ __('رقم الهوية') }} <span class="text-danger">*</span></label>
                            <input type="text" name="id_number" id="id_number" class="form-control" 
                                   value="{{ old('id_number') }}" required maxlength="10"
                                   placeholder="{{ __('أدخل رقم الهوية') }}">
                            @error('id_number')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('phone_num') has-error @enderror">
                            <label for="phone_num">{{ __('رقم الهاتف') }}</label>
                            <input type="text" name="phone_num" id="phone_num" class="form-control" 
                                   value="{{ old('phone_num') }}" maxlength="15"
                                   placeholder="{{ __('أدخل رقم الهاتف') }}">
                            @error('phone_num')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('temp_password') has-error @enderror">
                            <label for="temp_password">{{ __('كلمة المرور المؤقتة') }}</label>
                            <input type="text" name="temp_password" id="temp_password" class="form-control" 
                                   value="{{ old('temp_password') }}" maxlength="10"
                                   placeholder="{{ __('أدخل كلمة مرور مؤقتة') }}">
                            <small class="text-muted">{{ __('يمكن للمساهم تغييرها لاحقاً') }}</small>
                            @error('temp_password')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('iban') has-error @enderror">
                            <label for="iban">{{ __('رقم الحساب البنكي (IBAN)') }}</label>
                            <input type="text" name="iban" id="iban" class="form-control" 
                                   value="{{ old('iban') }}" maxlength="24"
                                   placeholder="{{ __('أدخل رقم الحساب البنكي') }}">
                            @error('iban')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('bank_name') has-error @enderror">
                            <label for="bank_name">{{ __('اسم البنك') }}</label>
                            <input type="text" name="bank_name" id="bank_name" class="form-control" 
                                   value="{{ old('bank_name') }}" maxlength="15"
                                   placeholder="{{ __('أدخل اسم البنك') }}">
                            @error('bank_name')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('position') has-error @enderror">
                            <label for="position">{{ __('المنصب') }}</label>
                            <input type="text" name="position" id="position" class="form-control" 
                                   value="{{ old('position') }}" maxlength="100"
                                   placeholder="{{ __('أدخل المنصب') }}">
                            @error('position')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('profile_picture') has-error @enderror">
                            <label for="profile_picture">{{ __('الصورة الشخصية (اختياري)') }}</label>
                            <input type="file" name="profile_picture" id="profile_picture" class="form-control" accept="image/*">
                            @error('profile_picture')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">{{ __('الصيغ المقبولة: JPG, PNG, GIF. الحد الأقصى: 2MB') }}</small>
                        </div>

                        <div class="form-group @error('share_count_cr') has-error @enderror">
                            <label for="share_count_cr">{{ __('عدد الأسهم') }}</label>
                            <input type="number" name="share_count_cr" id="share_count_cr" class="form-control" 
                                   value="{{ old('share_count_cr') }}" min="0" step="0.01"
                                   placeholder="{{ __('أدخل عدد الأسهم') }}">
                            @error('share_count_cr')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="is_board_member" value="1" 
                                           {{ old('is_board_member') ? 'checked' : '' }}>
                                    {{ __('عضو مجلس إدارة') }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="documents">{{ __('الوثائق والملفات') }} <small class="text-muted">({{ __('اختياري') }})</small></label>
                            <input type="file" name="documents[]" id="documents" class="form-control" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx">
                            <small class="text-muted">{{ __('يمكنك رفع عدة ملفات (صور، PDF، Word، Excel). الحد الأقصى 10MB لكل ملف. (اختياري)') }}</small>
                            @error('documents.*')
                                <span class="help-block text-danger">{{ $message }}</span>
                            @enderror
                            <div id="file-list" class="mt-2"></div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <span class="glyphicon glyphicon-floppy-disk"></span> {{ __('حفظ المساهم') }}
                            </button>
                            <a href="{{ route('contributors.index') }}" class="btn btn-default">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('إلغاء') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Information Panel -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">{{ __('معلومات إضافية') }}</h4>
                        </div>
                        <div class="panel-body">
                            <ul>
                                <li><strong>{{ __('الاسم') }}:</strong> {{ __('مطلوب - اسم المساهم الكامل') }}</li>
                                <li><strong>{{ __('رقم الهوية') }}:</strong> {{ __('مطلوب - يجب أن يكون فريد') }}</li>
                                <li><strong>{{ __('رقم الهاتف') }}:</strong> {{ __('اختياري - للتواصل') }}</li>
                                <li><strong>{{ __('كلمة المرور المؤقتة') }}:</strong> {{ __('اختياري - يمكن تغييرها لاحقاً') }}</li>
                                <li><strong>{{ __('ربط بحساب مستخدم') }}:</strong> {{ __('اختياري - لربط المساهم بحساب موجود') }}</li>
                                <li><strong>{{ __('رقم الحساب البنكي') }}:</strong> {{ __('اختياري - للتحويلات المالية') }}</li>
                                <li><strong>{{ __('اسم البنك') }}:</strong> {{ __('اختياري - اسم البنك') }}</li>
                                <li><strong>{{ __('المهنة') }}:</strong> {{ __('اختياري - مهنة المساهم') }}</li>
                                <li><strong>{{ __('عدد الأسهم') }}:</strong> {{ __('اختياري - عدد الأسهم المملوكة') }}</li>
                                <li><strong>{{ __('عضو مجلس إدارة') }}:</strong> {{ __('اختياري - تحديد إذا كان عضو مجلس إدارة') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation
    $('form').on('submit', function(e) {
        const name = $('#name').val().trim();
        const idNumber = $('#id_number').val().trim();
        
        if (!name) {
            alert('{{ __("يرجى إدخال اسم المساهم") }}');
            e.preventDefault();
            return false;
        }
        
        if (!idNumber) {
            alert('{{ __("يرجى إدخال رقم الهوية") }}');
            e.preventDefault();
            return false;
        }
        
        // Confirm before submitting
        if (!confirm('{{ __("هل أنت متأكد من إضافة هذا المساهم؟") }}\n\n{{ __("الاسم") }}: ' + name + '\n{{ __("رقم الهوية") }}: ' + idNumber)) {
            e.preventDefault();
            return false;
        }
    });

    // Auto-generate temp password
    $('#temp_password').on('focus', function() {
        if (!$(this).val()) {
            const randomPassword = Math.random().toString(36).substring(2, 8);
            $(this).val(randomPassword);
        }
    });

    // Display selected files
    $('#documents').on('change', function() {
        const files = this.files;
        const fileList = $('#file-list');
        fileList.empty();
        
        if (files.length > 0) {
            let html = '<ul class="list-unstyled">';
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                html += '<li><span class="glyphicon glyphicon-file"></span> ' + file.name + ' <small class="text-muted">(' + fileSize + ' MB)</small></li>';
            }
            html += '</ul>';
            fileList.html(html);
        }
    });
});
</script>
@endpush
