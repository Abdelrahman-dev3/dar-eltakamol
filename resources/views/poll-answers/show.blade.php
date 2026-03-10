@extends('layouts.app')

@section('title', __('عرض إجابة الاستطلاع') . ' - ' . ($pollAnswer->user->name ?? __('غير معروف')))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('عرض إجابة الاستطلاع') }}
                        <div class="pull-left">
                            <a href="{{ route('poll-answers.edit', $pollAnswer) }}" class="btn btn-warning btn-sm">
                                <span class="glyphicon glyphicon-edit"></span> {{ __('تعديل') }}
                            </a>
                            <a href="{{ route('poll-answers.index') }}" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('العودة') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4><strong>{{ __('المستخدم:') }}</strong> 
                                <a href="{{ route('contributors.show', $pollAnswer->user->id) }}" class="text-primary">
                                    {{ $pollAnswer->user->name ?? __('غير معروف') }}
                                </a>
                            </h4>
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>{{ __('الاستطلاع:') }}</strong> 
                                        <a href="{{ route('polls.show', $pollAnswer->poll_id) }}" class="text-primary">
                                            {{ $pollAnswer->poll->question ?? __('غير محدد') }}
                                        </a>
                                    </p>
                                    <p><strong>{{ __('الخيار المختار:') }}</strong> 
                                        <span class="label label-info" style="font-size: 14px;">
                                            {{ $pollAnswer->pollOption->option_text ?? __('غير محدد') }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>{{ __('تاريخ الإجابة:') }}</strong> {{ $pollAnswer->answer_date->format('Y-m-d H:i:s') }}</p>
                                    <p><strong>{{ __('تاريخ الإنشاء:') }}</strong> {{ $pollAnswer->created_at->format('Y-m-d H:i:s') }}</p>
                                    <p><strong>{{ __('آخر تحديث:') }}</strong> {{ $pollAnswer->updated_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h4 class="panel-title">{{ __('معلومات الإجابة') }}</h4>
                                </div>
                                <div class="panel-body">
                                    <p><strong>{{ __('رقم الإجابة:') }}</strong> #{{ $pollAnswer->id }}</p>
                                    <p><strong>{{ __('المستخدم:') }}</strong> {{ $pollAnswer->user->name ?? __('غير معروف') }}</p>
                                    <p><strong>{{ __('البريد الإلكتروني:') }}</strong> {{ $pollAnswer->user->email ?? __('غير محدد') }}</p>
                                    @if($pollAnswer->poll)
                                        <p><strong>{{ __('حالة الاستطلاع:') }}</strong> 
                                            @if($pollAnswer->poll->is_active)
                                                <span class="label label-success">{{ __('نشط') }}</span>
                                            @else
                                                <span class="label label-default">{{ __('غير نشط') }}</span>
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($pollAnswer->poll)
                        <hr>
                        <h4>{{ __('تفاصيل الاستطلاع') }}</h4>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5 class="panel-title">{{ __('معلومات الاستطلاع') }}</h5>
                                    </div>
                                    <div class="panel-body">
                                        <p><strong>{{ __('السؤال:') }}</strong> {{ $pollAnswer->poll->question }}</p>
                                        @if($pollAnswer->poll->start_date)
                                            <p><strong>{{ __('تاريخ البدء:') }}</strong> {{ $pollAnswer->poll->start_date->format('Y-m-d H:i') }}</p>
                                        @endif
                                        @if($pollAnswer->poll->end_date)
                                            <p><strong>{{ __('تاريخ الانتهاء:') }}</strong> {{ $pollAnswer->poll->end_date->format('Y-m-d H:i') }}</p>
                                        @endif
                                        <p><strong>{{ __('الحالة:') }}</strong> 
                                            @if($pollAnswer->poll->is_active)
                                                <span class="label label-success">{{ __('نشط') }}</span>
                                            @else
                                                <span class="label label-default">{{ __('غير نشط') }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5 class="panel-title">{{ __('إحصائيات الاستطلاع') }}</h5>
                                    </div>
                                    <div class="panel-body">
                                        @if($pollAnswer->poll->pollAnswers)
                                            <p><strong>{{ __('إجمالي الأصوات:') }}</strong> {{ $pollAnswer->poll->pollAnswers->count() }}</p>
                                        @endif
                                        @if($pollAnswer->poll->pollOptions)
                                            <p><strong>{{ __('عدد الخيارات:') }}</strong> {{ $pollAnswer->poll->pollOptions->count() }}</p>
                                        @endif
                                        @if($pollAnswer->pollOption && $pollAnswer->poll->pollAnswers)
                                            @php
                                                $totalVotes = $pollAnswer->poll->pollAnswers->count();
                                                $optionVotes = $pollAnswer->pollOption->votes;
                                                $percentage = $totalVotes > 0 ? ($optionVotes / $totalVotes) * 100 : 0;
                                            @endphp
                                            <p><strong>{{ __('أصوات الخيار المختار:') }}</strong> {{ number_format($optionVotes) }}</p>
                                            <p><strong>{{ __('نسبة الخيار:') }}</strong> {{ number_format($percentage, 1) }}%</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($pollAnswer->pollOption)
                        <hr>
                        <h4>{{ __('تفاصيل الخيار المختار') }}</h4>
                        
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5><strong>{{ __('نص الخيار:') }}</strong> {{ $pollAnswer->pollOption->option_text }}</h5>
                                        <p><strong>{{ __('عدد الأصوات:') }}</strong> 
                                            <span class="badge badge-primary" style="font-size: 16px;">{{ number_format($pollAnswer->pollOption->votes) }}</span>
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <a href="{{ route('poll-options.show', $pollAnswer->pollOption->id) }}" class="btn btn-info">
                                            <span class="glyphicon glyphicon-eye-open"></span> {{ __('عرض الخيار') }}
                                        </a>
                                    </div>
                                </div>
                                
                                @if($pollAnswer->poll && $pollAnswer->poll->pollAnswers)
                                    @php
                                        $totalVotes = $pollAnswer->poll->pollAnswers->count();
                                        $optionVotes = $pollAnswer->pollOption->votes;
                                        $percentage = $totalVotes > 0 ? ($optionVotes / $totalVotes) * 100 : 0;
                                    @endphp
                                    
                                    <div class="progress" style="height: 25px; margin-top: 15px;">
                                        <div class="progress-bar progress-bar-info" role="progressbar" 
                                             style="width: {{ $percentage }}%" 
                                             aria-valuenow="{{ $percentage }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            {{ number_format($percentage, 1) }}% ({{ number_format($optionVotes) }} {{ __('صوت') }})
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="well">
                        <div class="row">
                            <div class="col-md-3">
                                <a href="{{ route('poll-answers.edit', $pollAnswer) }}" class="btn btn-warning btn-block">
                                    <span class="glyphicon glyphicon-edit"></span> {{ __('تعديل الإجابة') }}
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('polls.show', $pollAnswer->poll_id) }}" class="btn btn-info btn-block">
                                    <span class="glyphicon glyphicon-eye-open"></span> {{ __('عرض الاستطلاع') }}
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('poll-options.show', $pollAnswer->poll_option_id) }}" class="btn btn-success btn-block">
                                    <span class="glyphicon glyphicon-list"></span> {{ __('عرض الخيار') }}
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('poll-answers.index') }}" class="btn btn-default btn-block">
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
