@extends('layouts.app')

@section('title', __('الملف الشخصي'))

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    {{ __('الملف الشخصي') }}
                    <div class="pull-left">
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editProfileModal">
                            <span class="glyphicon glyphicon-edit"></span> {{ __('تعديل البيانات') }}
                        </button>
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#changePasswordModal">
                            <span class="glyphicon glyphicon-lock"></span> {{ __('تغيير كلمة المرور') }}
                        </button>
                    </div>
                </h3>
            </div>
            <div class="panel-body">
                <!-- User Basic Info -->
                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('الاسم') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ Auth::user()->name }}
                    </div>
                </div>
                <hr>

                @if(Auth::user()->id_number)
                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('رقم الهوية') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ Auth::user()->id_number }}
                    </div>
                </div>
                <hr>
                @endif

                @if(Auth::user()->phone)
                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('رقم الهاتف') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ Auth::user()->phone }}
                    </div>
                </div>
                <hr>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('البريد الإلكتروني') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ Auth::user()->email }}
                        @if(Auth::user()->email_verified_at)
                            <span class="label label-success">{{ __('محقق') }}</span>
                        @else
                            <span class="label label-warning">{{ __('غير محقق') }}</span>
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('تاريخ الانضمام') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ Auth::user()->created_at->format('Y-m-d H:i') }}
                    </div>
                </div>
                <hr>

                <!-- Contributor Info -->
                @if(Auth::user()->contributor)
                <h4>{{ __('معلومات المساهم') }}</h4>
                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('اسم المساهم') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ Auth::user()->contributor->name ?? __('غير محدد') }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('عدد الأسهم') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ number_format(Auth::user()->contributor->share_count_cr ?? 0, 2) }}
                        @if(Auth::user()->contributor->is_board_member)
                            <span class="label label-success">{{ __('عضو مجلس') }}</span>
                        @endif
                    </div>
                </div>
                <hr>

                @if(Auth::user()->contributor->iban)
                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('أيبان البنك') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ Auth::user()->contributor->iban }}
                    </div>
                </div>
                <hr>
                @endif
                @endif

                <!-- User Groups -->
                <h4>{{ __('المجموعات والصلاحيات') }}</h4>
                @if(Auth::user()->groups->count() > 0)
                    <div class="list-group">
                        @foreach(Auth::user()->groups as $group)
                            <div class="list-group-item">
                                <h5 class="list-group-item-heading">{{ $group->name }}</h5>
                                <p class="list-group-group-item-text">
                                    @foreach($group->roles as $role)
                                        <span class="label label-info">{{ $role->name }}</span>
                                    @endforeach
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-warning">
                        {{ __('لم يتم تعيينك لأي مجموعة بعد.') }}
                    </div>
                @endif

                <!-- User Activities -->
                <h4>{{ __('النشاطات الأخيرة') }}</h4>
                <div class="alert alert-info">
                    {{ __('قريباً: سيكون بإمكانك عرض تاريخ نشاطك في النظام') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
                <h4 class="modal-title">{{ __('تعديل البيانات الشخصية') }}</h4>
            </div>
            <form id="editProfileForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">{{ __('الاسم') }}</label>
                        <input type="text" name="name" id="edit_name" class="form-control" value="{{ Auth::user()->name }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_id_number">{{ __('رقم الهوية') }}</label>
                        <input type="text" name="id_number" id="edit_id_number" class="form-control" value="{{ Auth::user()->id_number }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_phone">{{ __('رقم الهاتف') }}</label>
                        <input type="text" name="phone" id="edit_phone" class="form-control" value="{{ Auth::user()->phone }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_email">{{ __('البريد الإلكتروني') }}</label>
                        <input type="email" name="email" id="edit_email" class="form-control" value="{{ Auth::user()->email }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('إلغاء') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('حفظ التغييرات') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
                <h4 class="modal-title">{{ __('تغيير كلمة المرور') }}</h4>
            </div>
            <form id="changePasswordForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="current_password">{{ __('كلمة المرور الحالية') }}</label>
                        <input type="password" name="current_password" id="current_password" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">{{ __('كلمة المرور الجديدة') }}</label>
                        <input type="password" name="new_password" id="new_password" class="form-control" minlength="8" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">{{ __('تأكيد كلمة المرور') }}</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                    </div>
                    
                    <div class="alert alert-info">
                        <small>{{ __('يجب أن تكون كلمة المرور الجديدة 8 أحرف على الأقل.') }}</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('إلغاء') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('تغيير كلمة المرور') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Edit Profile Form
    $('#editProfileForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $.ajax({
            url: '/profile',
            type: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorMessages = [];
                    
                    for (const field in errors) {
                        errorMessages.push(errors[field][0]);
                    }
                    
                    alert('{{ __("خطأ في البيانات") }}:\n' + errorMessages.join('\n'));
                } else {
                    alert('{{ __("حدث خطأ أثناء تحديث البيانات.") }}');
                }
            }
        });
    });
    
    // Change Password Form
    $('#changePasswordForm').on('submit', function(e) {
        e.preventDefault();
        
        const newPassword = $('#new_password').val();
        const confirmPassword = $('#confirm_password').val();
        
        if (newPassword !== confirmPassword) {
            alert('{{ __("كلمات المرور غير متطابقة.") }}');
            return;
        }
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $.ajax({
            url: '/profile/password',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#changePasswordModal').modal('hide');
                $('#changePasswordForm')[0].reset();
                alert('{{ __("تم تحديث كلمة المرور بنجاح.") }}');
            },
            error: function(xhr) {
                if (xhr.status === 400) {
                    alert(xhr.responseJSON.message || '{{ __("كلمة المرور الحالية غير صحيحة.") }}');
                } else {
                    alert('{{ __("حدث خطأ أثناء تغيير كلمة المرور.") }}');
                }
            }
        });
    });
});
</script>
@endpush