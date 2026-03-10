@extends('layouts.app')

@section('title', __('تعديل بيانات المساهم'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('تعديل بيانات المساهم') }} #{{ $contributor->id }}
                        <div class="pull-left">
                            <a href="{{ route('contributors.show', $contributor->id) }}" class="btn btn-info btn-sm">
                                <span class="glyphicon glyphicon-eye-open"></span> {{ __('عرض') }}
                            </a>
                            <a href="{{ route('contributors.index') }}" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('رجوع') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <form action="{{ route('contributors.update', $contributor->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group @error('name') has-error @enderror">
                            <label for="name">{{ __('الاسم') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" 
                                   value="{{ old('name', $contributor->name) }}" required maxlength="100"
                                   placeholder="{{ __('أدخل اسم المساهم') }}">
                            @error('name')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('id_number') has-error @enderror">
                            <label for="id_number">{{ __('رقم الهوية') }} <span class="text-danger">*</span></label>
                            <input type="text" name="id_number" id="id_number" class="form-control" 
                                   value="{{ old('id_number', $contributor->id_number) }}" required maxlength="10"
                                   placeholder="{{ __('أدخل رقم الهوية') }}">
                            @error('id_number')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('phone_num') has-error @enderror">
                            <label for="phone_num">{{ __('رقم الهاتف') }}</label>
                            <input type="text" name="phone_num" id="phone_num" class="form-control" 
                                   value="{{ old('phone_num', $contributor->phone_num) }}" maxlength="15"
                                   placeholder="{{ __('أدخل رقم الهاتف') }}">
                            @error('phone_num')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('temp_password') has-error @enderror">
                            <label for="temp_password">{{ __('كلمة المرور المؤقتة') }}</label>
                            <input type="text" name="temp_password" id="temp_password" class="form-control" 
                                   value="{{ old('temp_password', $contributor->temp_password) }}" maxlength="10"
                                   placeholder="{{ __('أدخل كلمة مرور مؤقتة') }}">
                            <small class="text-muted">{{ __('يمكن للمساهم تغييرها لاحقاً') }}</small>
                            @error('temp_password')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group @error('iban') has-error @enderror">
                            <label for="iban">{{ __('رقم الحساب البنكي (IBAN)') }}</label>
                            <input type="text" name="iban" id="iban" class="form-control" 
                                   value="{{ old('iban', $contributor->iban) }}" maxlength="24"
                                   placeholder="{{ __('أدخل رقم الحساب البنكي') }}">
                            @error('iban')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('bank_name') has-error @enderror">
                            <label for="bank_name">{{ __('اسم البنك') }}</label>
                            <input type="text" name="bank_name" id="bank_name" class="form-control" 
                                   value="{{ old('bank_name', $contributor->bank_name) }}" maxlength="15"
                                   placeholder="{{ __('أدخل اسم البنك') }}">
                            @error('bank_name')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('position') has-error @enderror">
                            <label for="position">{{ __('المنصب') }}</label>
                            <input type="text" name="position" id="position" class="form-control" 
                                   value="{{ old('position', $contributor->position) }}" maxlength="100"
                                   placeholder="{{ __('أدخل المنصب') }}">
                            @error('position')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('profile_picture') has-error @enderror">
                            <label for="profile_picture">{{ __('الصورة الشخصية (اختياري)') }}</label>
                            @if($contributor->profile_picture)
                                <div style="margin-bottom: 10px;">
                                    <img src="{{ $contributor->profile_picture_url }}" alt="{{ $contributor->name }}" 
                                         style="max-width: 150px; max-height: 150px; border-radius: 8px; border: 2px solid #ddd;">
                                    <p class="text-muted"><small>{{ __('الصورة الحالية') }}</small></p>
                                </div>
                            @endif
                            <input type="file" name="profile_picture" id="profile_picture" class="form-control" accept="image/*">
                            @error('profile_picture')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">{{ __('الصيغ المقبولة: JPG, PNG, GIF. الحد الأقصى: 2MB') }}</small>
                        </div>

                        <div class="form-group @error('share_count_cr') has-error @enderror">
                            <label for="share_count_cr">{{ __('عدد الأسهم') }}</label>
                            <input type="number" name="share_count_cr" id="share_count_cr" class="form-control" 
                                   value="{{ old('share_count_cr', $contributor->share_count_cr) }}" min="0" step="0.01"
                                   placeholder="{{ __('أدخل عدد الأسهم') }}">
                            @error('share_count_cr')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="is_board_member" value="1" 
                                           {{ old('is_board_member', $contributor->is_board_member) ? 'checked' : '' }}>
                                    {{ __('عضو مجلس إدارة') }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="checkbox">
                                <label for="line_notes">{{ __('* اسباب التعديل') }}</label>
                                <textarea name="line_notes" class="form-control" style="width: 680px; height: 136px;"  placeholder="{{ __('اكتب سبب التعديل الذي قمت به') }}"></textarea>
                            </div>
                            <label for="documents">{{ __('إضافة وثائق وملفات جديدة') }} <small class="text-muted">({{ __('اختياري') }})</small></label>
                            <input type="file" name="documents[]" id="documents" class="form-control" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx">
                            <small class="text-muted">{{ __('يمكنك رفع عدة ملفات (صور، PDF، Word، Excel). الحد الأقصى 10MB لكل ملف. (اختياري)') }}</small>
                            @error('documents.*')
                                <span class="help-block text-danger">{{ $message }}</span>
                            @enderror
                            <div id="file-list" class="mt-2"></div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <span class="glyphicon glyphicon-floppy-disk"></span> {{ __('حفظ التغييرات') }}
                            </button>
                            <a href="{{ route('contributors.show', $contributor->id) }}" class="btn btn-info">
                                <span class="glyphicon glyphicon-eye-open"></span> {{ __('عرض') }}
                            </a>
                            <a href="{{ route('contributors.index') }}" class="btn btn-default">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('إلغاء') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Current Information Panel -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">{{ __('المعلومات الحالية') }}</h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>{{ __('تاريخ الإنشاء') }}:</strong> {{ $contributor->created_at->format('Y-m-d H:i') }}<br>
                                    <strong>{{ __('آخر تحديث') }}:</strong> {{ $contributor->updated_at->format('Y-m-d H:i') }}<br>
                                    <strong>{{ __('المدة منذ الإنشاء') }}:</strong> {{ $contributor->created_at->diffForHumans() }}
                                </div>
                                <div class="col-md-6">
                                    @if($contributor->user)
                                        <strong>{{ __('حساب المستخدم المرتبط') }}:</strong> {{ $contributor->user->name }}<br>
                                        <strong>{{ __('البريد الإلكتروني') }}:</strong> {{ $contributor->user->email }}<br>
                                        <strong>{{ __('تاريخ إنشاء الحساب') }}:</strong> {{ $contributor->user->created_at->format('Y-m-d H:i') }}
                                    @else
                                        <strong>{{ __('حساب المستخدم المرتبط') }}:</strong> {{ __('غير مرتبط') }}
                                    @endif
                                </div>
                            </div>
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
        if (!confirm('{{ __("هل أنت متأكد من حفظ التغييرات؟") }}\n\n{{ __("الاسم") }}: ' + name + '\n{{ __("رقم الهوية") }}: ' + idNumber)) {
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
