@extends('layouts.app')

@section('title', __('إجابات الاستطلاعات'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('إجابات الاستطلاعات') }}
                        <div class="pull-left">
                            <a href="{{ route('poll-answers.create') }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i> {{ __('إضافة إجابة جديدة') }}
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
                                    <th>{{ __('رقم الإجابة') }}</th>
                                    <th>{{ __('الاستطلاع') }}</th>
                                    <th>{{ __('الخيار المختار') }}</th>
                                    <th>{{ __('المستخدم') }}</th>
                                    <th>{{ __('تاريخ الإجابة') }}</th>
                                    <th>{{ __('الإجراءات') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pollAnswers as $pollAnswer)
                                    <tr>
                                        <td>{{ $pollAnswer->id }}</td>
                                        <td>
                                            <a href="{{ route('polls.show', $pollAnswer->poll->id) }}" class="text-primary">
                                                {{ Str::limit($pollAnswer->poll->question, 50) }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="label label-info">{{ $pollAnswer->pollOption->option_text }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('contributors.show', $pollAnswer->user->id) }}" class="text-success">
                                                {{ $pollAnswer->user->name }}
                                            </a>
                                        </td>
                                        <td>{{ $pollAnswer->answer_date->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('poll-answers.show', $pollAnswer->id) }}" class="btn btn-info btn-xs">
                                                    <i class="fa fa-eye"></i> {{ __('عرض') }}
                                                </a>
                                                <a href="{{ route('poll-answers.edit', $pollAnswer->id) }}" class="btn btn-warning btn-xs">
                                                    <i class="fa fa-edit"></i> {{ __('تعديل') }}
                                                </a>
                                                <form action="{{ route('poll-answers.destroy', $pollAnswer->id) }}" method="POST" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذه الإجابة؟') }}');" style="display: inline-block;">
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
                                        <td colspan="6" class="text-center">{{ __('لا توجد إجابات استطلاع متاحة.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h4>{{ __('إحصائيات الإجابات') }}</h4>
                            <div class="alert alert-info">
                                <strong>{{ __('إجمالي الإجابات:') }}</strong> {{ $pollAnswers->total() }}<br>
                                <strong>{{ __('إجمالي المستخدمين المشاركين:') }}</strong> {{ \App\Models\PollAnswer::distinct('user_id')->count() }}<br>
                                <strong>{{ __('متوسط الإجابات لكل مستخدم:') }}</strong> {{ $pollAnswers->total() > 0 ? number_format($pollAnswers->total() / \App\Models\PollAnswer::distinct('user_id')->count(), 2) : 0 }}<br>
                            </div>
                        </div>
                    </div>

                    {{ $pollAnswers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

