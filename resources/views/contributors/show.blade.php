@extends('layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', __('عرض تفاصيل المساهم'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('عرض تفاصيل المساهم') }} #{{ $contributor->id }}
                        <div class="pull-left">
                            <a href="{{ route('contributors.edit', $contributor->id) }}" class="btn btn-warning btn-sm">
                                <span class="glyphicon glyphicon-edit"></span> {{ __('تعديل') }}
                            </a>
                            <a href="{{ route('contributors.index') }}" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('رجوع') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <!-- Basic Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h4 class="panel-title">{{ __('المعلومات الأساسية') }}</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('رقم المساهم') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            {{ $contributor->id }}
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('الاسم') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            {{ $contributor->name ?? $contributor->user->name ?? __('غير محدد') }}
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('رقم الهوية') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            {{ $contributor->id_number ?? __('غير محدد') }}
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('رقم الهاتف') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            {{ $contributor->phone_num ?? __('غير محدد') }}
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('المنصب') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            {{ $contributor->position ?? __('غير محدد') }}
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('الصورة الشخصية') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            @if($contributor->profile_picture)
                                                <img src="{{ $contributor->profile_picture_url }}" alt="{{ $contributor->name }}" 
                                                     style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #ddd;">
                                            @else
                                                <div style="width: 100px; height: 100px; border-radius: 8px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 32px; font-weight: bold;">
                                                    {{ $contributor->initials }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('عضو مجلس إدارة') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            @if($contributor->is_board_member)
                                                <span class="label label-success">{{ __('نعم') }}</span>
                                            @else
                                                <span class="label label-default">{{ __('لا') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <h4 class="panel-title">{{ __('المعلومات المالية') }}</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('عدد الأسهم') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="badge badge-primary">{{ number_format($contributor->share_count_cr ?? 0, 0) }}</span>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('رقم الحساب البنكي') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            {{ $contributor->iban ?? __('غير محدد') }}
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('اسم البنك') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            {{ $contributor->bank_name ?? __('غير محدد') }}
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('كلمة المرور المؤقتة') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            @if($contributor->temp_password)
                                                <code>{{ $contributor->temp_password }}</code>
                                            @else
                                                {{ __('غير محدد') }}
                                            @endif
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('تاريخ الإنشاء') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            {{ $contributor->created_at->format('Y-m-d H:i') }}
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('آخر تحديث') }}:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            {{ $contributor->updated_at->format('Y-m-d H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Account Information -->
                    @if($contributor->user)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-warning">
                                <div class="panel-heading">
                                    <h4 class="panel-title">{{ __('حساب المستخدم المرتبط') }}</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('اسم المستخدم') }}:</strong> {{ $contributor->user->name }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>{{ __('البريد الإلكتروني') }}:</strong> {{ $contributor->user->email }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('تاريخ إنشاء الحساب') }}:</strong> {{ $contributor->user->created_at->format('Y-m-d H:i') }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>{{ __('آخر دخول') }}:</strong> {{ $contributor->user->last_login_at ?? __('لم يسجل دخول بعد') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Documents Section -->
                    @if($contributor->documents->count() > 0)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">{{ __('الوثائق والملفات') }} ({{ $contributor->documents->count() }})</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('اسم الملف') }}</th>
                                                    <th>{{ __('النوع') }}</th>
                                                    <th>{{ __('الحجم') }}</th>
                                                    <th>{{ __('الوصف') }}</th>
                                                    <th>{{ __('تاريخ الرفع') }}</th>
                                                    <th>{{ __('الإجراءات') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($contributor->documents as $document)
                                                <tr>
                                                    <td>
                                                        @if($document->isImage())
                                                            <span class="glyphicon glyphicon-picture text-primary"></span>
                                                        @else
                                                            <span class="glyphicon glyphicon-file text-info"></span>
                                                        @endif
                                                        {{ $document->file_name }}
                                                    </td>
                                                    <td>
                                                        @if($document->file_type === 'image')
                                                            <span class="label label-success">{{ __('صورة') }}</span>
                                                        @else
                                                            <span class="label label-default">{{ __('وثيقة') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $document->file_size_human }}</td>
                                                    <td>{{ $document->description ?? __('لا يوجد وصف') }}</td>
                                                    <td>{{ $document->created_at->format('Y-m-d H:i') }}</td>
                                                    <td>
                                                        <a href="{{ route('contributors.documents.download', $document->id) }}" class="btn btn-sm btn-primary" target="_blank">
                                                            <span class="glyphicon glyphicon-download"></span> {{ __('تحميل') }}
                                                        </a>
                                                        @if($document->isImage())
                                                            <a href="{{ Storage::url($document->file_path) }}" class="btn btn-sm btn-info" target="_blank">
                                                                <span class="glyphicon glyphicon-eye-open"></span> {{ __('عرض') }}
                                                            </a>
                                                        @endif
                                                        <form action="{{ route('contributors.documents.delete', $document->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('{{ __("هل أنت متأكد من حذف هذا الملف؟") }}');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <span class="glyphicon glyphicon-trash"></span> {{ __('حذف') }}
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Related Activities -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h4 class="panel-title">{{ __('الأنشطة المرتبطة') }}</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="panel panel-info">
                                                <div class="panel-body text-center">
                                                    <h3>{{ $contributor->sellShares->count() }}</h3>
                                                    <p>{{ __('عروض البيع') }}</p>
                                                    <a href="{{ route('sell-shares.index', ['user_id' => $contributor->id]) }}" class="btn btn-info btn-sm">
                                                        {{ __('عرض') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="panel panel-success">
                                                <div class="panel-body text-center">
                                                    <h3>{{ $contributor->sharesPOs->count() }}</h3>
                                                    <p>{{ __('طلبات الشراء') }}</p>
                                                    <a href="{{ route('shares-pos.index', ['user_id' => $contributor->id]) }}" class="btn btn-success btn-sm">
                                                        {{ __('عرض') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="panel panel-warning">
                                                <div class="panel-body text-center">
                                                    <h3>{{ $contributor->shareTransLines->count() }}</h3>
                                                    <p>{{ __('معاملات الأسهم') }}</p>
                                                    <a href="{{ route('share-trans-lines.index', ['contributor_id' => $contributor->id]) }}" class="btn btn-warning btn-sm">
                                                        {{ __('عرض') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="panel panel-danger">
                                                <div class="panel-body text-center">
                                                    <h3>{{ $contributor->userProfits->count() }}</h3>
                                                    <p>{{ __('توزيعات الأرباح') }}</p>
                                                    <a href="{{ route('users-profits.index', ['contributor_id' => $contributor->id]) }}" class="btn btn-danger btn-sm">
                                                        {{ __('عرض') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-body text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('contributors.edit', $contributor->id) }}" class="btn btn-warning">
                                            <span class="glyphicon glyphicon-edit"></span> {{ __('تعديل المساهم') }}
                                        </a>
                                        
                                        <a href="{{ route('contributors.index') }}" class="btn btn-default">
                                            <span class="glyphicon glyphicon-arrow-right"></span> {{ __('العودة للقائمة') }}
                                        </a>
                                        
                                        <button type="button" class="btn btn-danger" id="delete-contributor" data-id="{{ $contributor->id }}">
                                            <span class="glyphicon glyphicon-trash"></span> {{ __('حذف المساهم') }}
                                        </button>
                                    </div>
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
    // Delete contributor
    $('#delete-contributor').click(function() {
        const contributorId = $(this).data('id');
        if (confirm('{{ __("هل أنت متأكد من حذف هذا المساهم؟") }}\n\n{{ __("هذا الإجراء لا يمكن التراجع عنه.") }}')) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/contributors/' + contributorId,
                type: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        window.location.href = '{{ route("contributors.index") }}';
                    } else {
                        alert('{{ __("حدث خطأ أثناء حذف المساهم.") }}');
                    }
                },
                error: function(xhr) {
                    alert('{{ __("حدث خطأ أثناء حذف المساهم.") }}');
                }
            });
        }
    });
});
</script>
@endpush
