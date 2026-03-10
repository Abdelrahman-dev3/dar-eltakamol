@extends('layouts.app')

@section('title', __('الاستطلاعات'))

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    {{ __('الاستطلاعات') }}
                    <div class="pull-left">
                        <a href="{{ route('polls.create') }}" class="btn btn-success btn-sm">
                            <i class="fa fa-plus"></i> {{ __('إضافة استطلاع جديد') }}
                        </a>
                    </div>
                </h3>
            </div>
            <div class="panel-body">
                @if($polls->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('السؤال') }}</th>
                                    <th>{{ __('تاريخ البدء') }}</th>
                                    <th>{{ __('تاريخ الانتهاء') }}</th>
                                    <th>{{ __('الحالة') }}</th>
                                    <th>{{ __('عدد الخيارات') }}</th>
                                    <th>{{ __('المنشئ') }}</th>
                                    <th>{{ __('الإجراءات') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($polls as $poll)
                                    <tr>
                                        <td>{{ $poll->question }}</td>
                                        <td>{{ $poll->start_date->format('Y-m-d H:i') }}</td>
                                        <td>{{ $poll->end_date->format('Y-m-d H:i') }}</td>
                                        <td>
                                            @if($poll->is_active)
                                                <span class="label label-success">{{ __('نشط') }}</span>
                                            @else
                                                <span class="label label-default">{{ __('غير نشط') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $poll->pollOptions->count() }}</td>
                                        <td>{{ $poll->creator->name ?? __('غير معروف') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('polls.show', $poll) }}" class="btn btn-info btn-xs">
                                                    <i class="fa fa-eye"></i> {{ __('عرض') }}
                                                </a>
                                                <a href="{{ route('polls.edit', $poll) }}" class="btn btn-warning btn-xs">
                                                    <i class="fa fa-edit"></i> {{ __('تعديل') }}
                                                </a>
                                                <a href="{{ route('polls.results', $poll) }}" class="btn btn-primary btn-xs">
                                                    <i class="fa fa-chart-bar"></i> {{ __('النتائج') }}
                                                </a>
                                                @if($poll->is_active && $poll->end_date > now())
                                                    <a href="{{ route('polls.show', $poll) }}" class="btn btn-success btn-xs">
                                                        <i class="fa fa-check"></i> {{ __('تصويت') }}
                                                    </a>
                                                @endif
                                                <form action="{{ route('polls.destroy', $poll) }}" method="POST" style="display: inline;" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا الاستطلاع؟') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-xs">
                                                        <i class="fa fa-trash"></i> {{ __('حذف') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $polls->links() }}
                @else
                    <div class="alert alert-info text-center">
                        <h4>{{ __('لا توجد استطلاعات') }}</h4>
                        <p>{{ __('لم يتم إنشاء أي استطلاعات بعد.') }}</p>
                        <a href="{{ route('polls.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> {{ __('إنشاء أول استطلاع') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
