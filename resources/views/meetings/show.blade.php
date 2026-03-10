@extends('layouts.app')

@section('title', __('عرض تفاصيل الاجتماع'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('عرض تفاصيل الاجتماع') }} #{{ $meeting->id }}
                        <div class="pull-left">
                            <a href="{{ route('meetings.edit', $meeting->id) }}" class="btn btn-warning btn-sm">
                                <span class="glyphicon glyphicon-edit"></span> {{ __('تعديل') }}
                            </a>
                            <a href="{{ route('meetings.index') }}" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('رجوع') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">{{ __('معلومات الاجتماع') }}</h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>{{ __('رقم الاجتماع') }}:</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $meeting->id }}
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    <strong>{{ __('اسم الاجتماع') }}:</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $meeting->name }}
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    <strong>{{ __('رابط الاجتماع') }}:</strong>
                                </div>
                                <div class="col-md-8">
                                    <a href="{{ $meeting->url }}" target="_blank" class="btn btn-primary btn-sm">
                                        <i class="fa fa-external-link"></i> {{ __('فتح الرابط') }}
                                    </a>
                                    <br><br>
                                    <code style="word-break: break-all;">{{ $meeting->url }}</code>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    <strong>{{ __('تاريخ ووقت الاجتماع') }}:</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $meeting->date->format('Y-m-d H:i') }}
                                    <br>
                                    <small class="text-muted">
                                        {{ $meeting->date->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    <strong>{{ __('تاريخ الإنشاء') }}:</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $meeting->created_at->format('Y-m-d H:i:s') }}
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    <strong>{{ __('آخر تحديث') }}:</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $meeting->updated_at->format('Y-m-d H:i:s') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($meeting->users->count() > 0)
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-users"></i> {{ __('المستخدمين المدعوين') }} 
                                <span class="badge">{{ $meeting->users->count() }}</span>
                            </h4>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('الاسم') }}</th>
                                            <th>{{ __('البريد الإلكتروني') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($meeting->users as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> {{ __('لم يتم تحديد مستخدمين لهذا الاجتماع') }}
                    </div>
                    @endif

                    @if($meeting->attachments->count() > 0)
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-paperclip"></i> {{ __('مرفقات الاجتماع') }} 
                                <span class="badge">{{ $meeting->attachments->count() }}</span>
                            </h4>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="35%">{{ __('اسم الملف') }}</th>
                                            <th width="25%">{{ __('الوصف') }}</th>
                                            <th width="10%">{{ __('الحجم') }}</th>
                                            <th width="15%">{{ __('رفع بواسطة') }}</th>
                                            <th width="10%">{{ __('إجراءات') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($meeting->attachments as $index => $attachment)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <i class="fa {{ $attachment->file_icon }} text-primary"></i>
                                                    {{ $attachment->file_name }}
                                                </td>
                                                <td>
                                                    {{ $attachment->description ?? __('بدون وصف') }}
                                                </td>
                                                <td>
                                                    <small>{{ $attachment->file_size_human }}</small>
                                                </td>
                                                <td>
                                                    @if($attachment->uploader)
                                                        <small>{{ $attachment->uploader->name }}</small>
                                                    @else
                                                        <small class="text-muted">{{ __('غير معروف') }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('meetings.attachments.download', $attachment) }}" 
                                                       class="btn btn-sm btn-info" title="{{ __('تنزيل') }}">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                    <form action="{{ route('meetings.attachments.delete', $attachment) }}" 
                                                          method="POST" style="display: inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                                title="{{ __('حذف') }}"
                                                                onclick="return confirm('{{ __('هل أنت متأكد من حذف هذا المرفق؟') }}')">
                                                            <i class="fa fa-trash"></i>
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
                    @else
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> {{ __('لا توجد مرفقات لهذا الاجتماع') }}
                    </div>
                    @endif

                    <div class="panel-footer">
                        <a href="{{ route('meetings.edit', $meeting->id) }}" class="btn btn-warning">
                            <i class="fa fa-edit"></i> {{ __('تعديل') }}
                        </a>
                        <a href="{{ route('meetings.index') }}" class="btn btn-default">
                            <i class="fa fa-arrow-right"></i> {{ __('رجوع') }}
                        </a>
                        <form action="{{ route('meetings.destroy', $meeting) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا الاجتماع؟') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fa fa-trash"></i> {{ __('حذف') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

