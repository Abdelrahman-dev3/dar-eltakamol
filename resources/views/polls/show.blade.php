@extends('layouts.app')

@section('title', __('عرض الاستطلاع'))

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    {{ __('عرض الاستطلاع') }}
                    <div class="pull-left">
                        <a href="{{ route('polls.edit', $poll) }}" class="btn btn-warning btn-sm">
                            <span class="glyphicon glyphicon-edit"></span> {{ __('تعديل') }}
                        </a>
                        <a href="{{ route('polls.results', $poll) }}" class="btn btn-primary btn-sm">
                            <span class="glyphicon glyphicon-stats"></span> {{ __('النتائج') }}
                        </a>
                        <a href="{{ route('polls.index') }}" class="btn btn-default btn-sm">
                            <span class="glyphicon glyphicon-arrow-right"></span> {{ __('العودة') }}
                        </a>
                    </div>
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('السؤال') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ $poll->question }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('تاريخ البدء') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ $poll->start_date->format('Y-m-d H:i') }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('تاريخ الانتهاء') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ $poll->end_date->format('Y-m-d H:i') }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('الحالة') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        @if($poll->is_active)
                            <span class="label label-success">{{ __('نشط') }}</span>
                        @else
                            <span class="label label-default">{{ __('غير نشط') }}</span>
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('المنشئ') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ $poll->createdBy->name ?? __('غير معروف') }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('تاريخ الإنشاء') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ $poll->created_date->format('Y-m-d H:i') }}
                    </div>
                </div>
                <hr>

                <h4>{{ __('خيارات الاستطلاع') }}</h4>
                @if($poll->pollOptions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('الخيار') }}</th>
                                    <th>{{ __('عدد الأصوات') }}</th>
                                    <th>{{ __('النسبة المئوية') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalVotes = $poll->pollAnswers->count();
                                @endphp
                                @foreach($poll->pollOptions as $option)
                                    @php
                                        $percentage = $totalVotes > 0 ? ($option->votes / $totalVotes) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $option->option_text }}</td>
                                        <td>{{ $option->votes }}</td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%">
                                                    {{ number_format($percentage, 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="info">
                                    <td><strong>{{ __('المجموع') }}</strong></td>
                                    <td><strong>{{ $totalVotes }}</strong></td>
                                    <td><strong>100%</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning">
                        {{ __('لا توجد خيارات لهذا الاستطلاع.') }}
                    </div>
                @endif

                @if($poll->is_active && $poll->end_date > now() && $poll->start_date <= now())
                    <hr>
                    <h4>{{ __('التصويت') }}</h4>
                    @if(Auth::check())
                        @php
                            $userHasVoted = $poll->pollAnswers->where('user_id', Auth::id())->count() > 0;
                        @endphp
                        
                        @if($userHasVoted)
                            <div class="alert alert-info">
                                <span class="glyphicon glyphicon-info-sign"></span>
                                {{ __('لقد قمت بالتصويت في هذا الاستطلاع.') }}
                            </div>
                        @else
                            <form action="{{ route('polls.vote', $poll) }}" method="POST" id="vote-form">
                                @csrf
                                <div class="form-group">
                                    @foreach($poll->pollOptions as $option)
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="poll_option_id" value="{{ $option->id }}" required>
                                                {{ $option->option_text }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="submit" class="btn btn-success">
                                    <span class="glyphicon glyphicon-check"></span> {{ __('تصويت') }}
                                </button>
                            </form>
                        @endif
                    @else
                        <div class="alert alert-warning">
                            {{ __('يجب تسجيل الدخول للتصويت.') }}
                        </div>
                    @endif
                @elseif($poll->end_date <= now())
                    <div class="alert alert-warning">
                        {{ __('انتهى وقت التصويت في هذا الاستطلاع.') }}
                    </div>
                @elseif($poll->start_date > now())
                    <div class="alert alert-info">
                        {{ __('لم يبدأ وقت التصويت في هذا الاستطلاع بعد.') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('#vote-form').on('submit', function(e) {
    e.preventDefault();
    
    if ($('input[name="poll_option_id"]:checked').length === 0) {
        alert('{{ __("يرجى اختيار خيار قبل التصويت.") }}');
        return;
    }
    
    if (confirm('{{ __("هل أنت متأكد من تصويتك؟ لا يمكن تغيير التصويت بعد الإرسال.") }}')) {
        this.submit();
    }
});
</script>
@endpush
