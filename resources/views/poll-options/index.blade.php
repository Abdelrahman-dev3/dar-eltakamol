@extends('layouts.app')

@section('title', __('خيارات الاستطلاعات'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('خيارات الاستطلاعات') }}
                        <div class="pull-left">
                            <a href="{{ route('poll-options.create') }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i> {{ __('إضافة خيار جديد') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('رقم الخيار') }}</th>
                                    <th>{{ __('الاستطلاع') }}</th>
                                    <th>{{ __('نص الخيار') }}</th>
                                    <th>{{ __('عدد الأصوات') }}</th>
                                    <th>{{ __('تاريخ الإنشاء') }}</th>
                                    <th>{{ __('الإجراءات') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pollOptions as $pollOption)
                                    <tr>
                                        <td>{{ $pollOption->id }}</td>
                                        <td>
                                            <a href="{{ route('polls.show', $pollOption->poll->id) }}" class="text-primary">
                                                {{ Str::limit($pollOption->poll->question, 50) }}
                                            </a>
                                        </td>
                                        <td>{{ $pollOption->option_text }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ $pollOption->votes }}</span>
                                        </td>
                                        <td>{{ $pollOption->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('poll-options.show', $pollOption->id) }}" class="btn btn-info btn-xs">
                                                    <i class="fa fa-eye"></i> {{ __('عرض') }}
                                                </a>
                                                <a href="{{ route('poll-options.edit', $pollOption->id) }}" class="btn btn-warning btn-xs">
                                                    <i class="fa fa-edit"></i> {{ __('تعديل') }}
                                                </a>
                                                <form action="{{ route('poll-options.destroy', $pollOption->id) }}" method="POST" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا الخيار؟') }}');" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-xs">
                                                        <i class="fa fa-trash"></i> {{ __('حذف') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">{{ __('لا توجد خيارات استطلاع متاحة.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h4>{{ __('إحصائيات الخيارات') }}</h4>
                            <div class="alert alert-info">
                                <strong>{{ __('إجمالي الخيارات:') }}</strong> {{ $pollOptions->total() }}<br>
                                <strong>{{ __('إجمالي الأصوات:') }}</strong> {{ \App\Models\PollOption::sum('votes') }}<br>
                                <strong>{{ __('متوسط الأصوات لكل خيار:') }}</strong> {{ $pollOptions->total() > 0 ? number_format(\App\Models\PollOption::sum('votes') / $pollOptions->total(), 2) : 0 }}<br>
                            </div>
                        </div>
                    </div>

                    {{ $pollOptions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
