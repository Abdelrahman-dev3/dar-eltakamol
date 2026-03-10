@extends('layouts.app')

@section('title', __('عرض خيار الاستطلاع') . ' - ' . $pollOption->option_text)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('عرض خيار الاستطلاع') }}
                        <div class="pull-left">
                            <a href="{{ route('poll-options.edit', $pollOption) }}" class="btn btn-warning btn-sm">
                                <span class="glyphicon glyphicon-edit"></span> {{ __('تعديل') }}
                            </a>
                            <a href="{{ route('poll-options.index') }}" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('العودة') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4><strong>{{ __('نص الخيار:') }}</strong> {{ $pollOption->option_text }}</h4>
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>{{ __('الاستطلاع:') }}</strong> 
                                        <a href="{{ route('polls.show', $pollOption->poll_id) }}" class="text-primary">
                                            {{ $pollOption->poll->question ?? __('غير محدد') }}
                                        </a>
                                    </p>
                                    <p><strong>{{ __('عدد الأصوات:') }}</strong> 
                                        <span class="badge badge-primary" style="font-size: 14px;">{{ number_format($pollOption->votes) }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>{{ __('تاريخ الإنشاء:') }}</strong> {{ $pollOption->created_at->format('Y-m-d H:i:s') }}</p>
                                    <p><strong>{{ __('آخر تحديث:') }}</strong> {{ $pollOption->updated_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h4 class="panel-title">{{ __('إحصائيات الخيار') }}</h4>
                                </div>
                                <div class="panel-body">
                                    <p><strong>{{ __('إجمالي الأصوات:') }}</strong> {{ number_format($pollOption->votes) }}</p>
                                    @if($pollOption->poll && $pollOption->poll->pollAnswers)
                                        @php
                                            $totalPollVotes = $pollOption->poll->pollAnswers->count();
                                            $percentage = $totalPollVotes > 0 ? ($pollOption->votes / $totalPollVotes) * 100 : 0;
                                        @endphp
                                        <p><strong>{{ __('النسبة من إجمالي الأصوات:') }}</strong> {{ number_format($percentage, 1) }}%</p>
                                        <p><strong>{{ __('إجمالي أصوات الاستطلاع:') }}</strong> {{ number_format($totalPollVotes) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($pollOption->poll && $pollOption->poll->pollAnswers)
                        <hr>
                        <h4>{{ __('تفاصيل الأصوات') }}</h4>
                        
                        @php
                            $totalPollVotes = $pollOption->poll->pollAnswers->count();
                            $percentage = $totalPollVotes > 0 ? ($pollOption->votes / $totalPollVotes) * 100 : 0;
                        @endphp
                        
                        <div class="progress" style="height: 30px; margin-bottom: 20px;">
                            <div class="progress-bar progress-bar-info" role="progressbar" 
                                 style="width: {{ $percentage }}%" 
                                 aria-valuenow="{{ $percentage }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                {{ number_format($percentage, 1) }}% ({{ number_format($pollOption->votes) }} {{ __('صوت') }})
                            </div>
                        </div>
                        
                        @if($pollOption->poll->pollAnswers->where('poll_option_id', $pollOption->id)->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('المستخدم') }}</th>
                                            <th>{{ __('تاريخ التصويت') }}</th>
                                            <th>{{ __('الإجراءات') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pollOption->poll->pollAnswers->where('poll_option_id', $pollOption->id) as $answer)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('contributors.show', $answer->user->id) }}" class="text-primary">
                                                        {{ $answer->user->name ?? __('غير معروف') }}
                                                    </a>
                                                </td>
                                                <td>{{ $answer->answer_date->format('Y-m-d H:i:s') }}</td>
                                                <td>
                                                    <a href="{{ route('poll-answers.show', $answer->id) }}" class="btn btn-info btn-xs">
                                                        <span class="glyphicon glyphicon-eye-open"></span> {{ __('عرض') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <h5>{{ __('لا توجد أصوات بعد') }}</h5>
                                <p>{{ __('لم يتم التصويت لهذا الخيار بعد.') }}</p>
                            </div>
                        @endif
                    @endif

                    <div class="well">
                        <div class="row">
                            <div class="col-md-4">
                                <a href="{{ route('poll-options.edit', $pollOption) }}" class="btn btn-warning btn-block">
                                    <span class="glyphicon glyphicon-edit"></span> {{ __('تعديل الخيار') }}
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('polls.show', $pollOption->poll_id) }}" class="btn btn-info btn-block">
                                    <span class="glyphicon glyphicon-eye-open"></span> {{ __('عرض الاستطلاع') }}
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('poll-options.index') }}" class="btn btn-default btn-block">
                                    <span class="glyphicon glyphicon-arrow-right"></span> {{ __('العودة للقائمة') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
