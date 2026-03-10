@extends('layouts.app')

@section('title', __('عرض تفاصيل اللائحة'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('عرض تفاصيل اللائحة') }} #{{ $regulation->id }}
                        <div class="pull-left">
                            <a href="{{ route('regulations.edit', $regulation->id) }}" class="btn btn-warning btn-sm">
                                <span class="glyphicon glyphicon-edit"></span> {{ __('تعديل') }}
                            </a>
                            <a href="{{ route('regulations.index') }}" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('رجوع') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">{{ __('معلومات اللائحة') }}</h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>{{ __('رقم اللائحة') }}:</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $regulation->id }}
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    <strong>{{ __('اسم اللائحة') }}:</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $regulation->name }}
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    <strong>{{ __('ملف اللائحة') }}:</strong>
                                </div>
                                <div class="col-md-8">
                                    <div class="file-info">
                                        <i class="fa {{ $regulation->file_icon }} text-primary" style="font-size: 2rem; margin-left: 15px;"></i>
                                        <div>
                                            <strong style="font-size: 1.1rem;">{{ $regulation->original_filename }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                {{ __('الحجم') }}: {{ $regulation->file_size_human }} |
                                                {{ __('النوع') }}: {{ $regulation->file_type ?: __('غير محدد') }} |
                                                {{ __('الامتداد') }}: {{ strtoupper($regulation->file_extension) }}
                                            </small>
                                        </div>
                                    </div>
                                    <br>
                                    <a href="{{ route('regulations.download', $regulation) }}" class="btn btn-primary" target="_blank">
                                        <i class="fa fa-download"></i> {{ __('تحميل الملف') }}
                                    </a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    <strong>{{ __('تاريخ الإضافة') }}:</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $regulation->created_at->format('Y-m-d H:i:s') }}
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    <strong>{{ __('آخر تحديث') }}:</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $regulation->updated_at->format('Y-m-d H:i:s') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-footer">
                        <a href="{{ route('regulations.edit', $regulation->id) }}" class="btn btn-warning">
                            <i class="fa fa-edit"></i> {{ __('تعديل') }}
                        </a>
                        <a href="{{ route('regulations.download', $regulation) }}" class="btn btn-primary" target="_blank">
                            <i class="fa fa-download"></i> {{ __('تحميل الملف') }}
                        </a>
                        <a href="{{ route('regulations.index') }}" class="btn btn-default">
                            <i class="fa fa-arrow-right"></i> {{ __('رجوع') }}
                        </a>
                        <form action="{{ route('regulations.destroy', $regulation) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذه اللائحة؟ سيتم حذف الملف نهائياً.') }}');">
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

