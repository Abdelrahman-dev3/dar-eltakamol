@extends('layouts.app')

@section('title', __('نتائج الاستطلاع') . ' - ' . ($poll->title ?? $poll->question))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('نتائج الاستطلاع') }}
                        <div class="pull-left">
                            <a href="{{ route('polls.show', $poll) }}" class="btn btn-info btn-sm">
                                <i class="fa fa-eye"></i> {{ __('عرض الاستطلاع') }}
                            </a>
                            <a href="{{ route('polls.index') }}" class="btn btn-default btn-sm">
                                <i class="fa fa-arrow-right"></i> {{ __('العودة') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <!-- Poll Info -->
                    <div class="row">
                        <div class="col-md-8">
                            <h4><strong>{{ __('عنوان الاستطلاع:') }}</strong> {{ $poll->title ?? $poll->question }}</h4>
                            @if($poll->description)
                                <p><strong>{{ __('الوصف:') }}</strong> {{ $poll->description }}</p>
                            @endif
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>{{ __('تاريخ البدء:') }}</strong> {{ $poll->start_date->format('Y-m-d H:i') }}</p>
                                    <p><strong>{{ __('تاريخ الانتهاء:') }}</strong> {{ $poll->end_date->format('Y-m-d H:i') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>{{ __('الحالة:') }}</strong> 
                                        @if($poll->is_active)
                                            <span class="label label-success">{{ __('نشط') }}</span>
                                        @else
                                            <span class="label label-default">{{ __('غير نشط') }}</span>
                                        @endif
                                    </p>
                                    <p><strong>{{ __('النوع:') }}</strong> 
                                        @if($poll->poll_type === 'meeting' && $poll->meeting)
                                            <span class="label label-info">{{ __('مرتبط باجتماع') }}: {{ $poll->meeting->name }}</span>
                                        @else
                                            <span class="label label-primary">{{ __('عام') }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h4 class="panel-title">{{ __('إحصائيات سريعة') }}</h4>
                                </div>
                                <div class="panel-body">
                                    @php
                                        $totalAnswers = $poll->pollAnswers->count();
                                        $totalUsers = \App\Models\User::count();
                                        $participationRate = $totalUsers > 0 ? ($totalAnswers / $totalUsers) * 100 : 0;
                                    @endphp
                                    <p><strong>{{ __('إجمالي الأصوات:') }}</strong> <span class="badge">{{ $totalAnswers }}</span></p>
                                    <p><strong>{{ __('عدد الأسئلة:') }}</strong> <span class="badge">{{ $poll->questions->count() ?: $poll->pollOptions->count() }}</span></p>
                                    <p><strong>{{ __('معدل المشاركة:') }}</strong> 
                                        <span class="badge">{{ number_format($participationRate, 1) }}%</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- New System: Multiple Questions -->
                    @if($poll->questions->count() > 0)
                        <h3 style="margin-bottom: 30px;">{{ __('نتائج الأسئلة') }}</h3>
                        
                        @foreach($poll->questions as $questionIndex => $question)
                            <div class="panel panel-primary" style="margin-bottom: 40px;">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        {{ __('السؤال') }} {{ $questionIndex + 1 }}: {{ $question->question_text }}
                                        <span class="pull-left">
                                            <span class="label label-{{ $question->question_type === 'multiple' ? 'warning' : 'info' }}">
                                                {{ $question->question_type === 'multiple' ? __('اختيار متعدد') : __('اختيار واحد') }}
                                            </span>
                                        </span>
                                    </h4>
                                </div>
                                <div class="panel-body">
                                    @php
                                        $questionTotalVotes = $question->total_votes;
                                    @endphp

                                    @if($question->options->count() > 0)
                                        <div class="row">
                                            <!-- Chart Column -->
                                            <div class="col-md-6">
                                                <canvas id="chart-question-{{ $question->id }}" height="250"></canvas>
                                            </div>
                                            
                                            <!-- Results Table Column -->
                                            <div class="col-md-6">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('الخيار') }}</th>
                                                                <th>{{ __('الأصوات') }}</th>
                                                                <th>{{ __('النسبة') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($question->options as $option)
                                                                @php
                                                                    $optionVotes = $question->answers->where('poll_option_id', $option->id)->count();
                                                                    $percentage = $questionTotalVotes > 0 ? ($optionVotes / $questionTotalVotes) * 100 : 0;
                                                                @endphp
                                                                <tr>
                                                                    <td><strong>{{ $option->option_text }}</strong></td>
                                                                    <td>
                                                                        <span class="badge badge-primary">{{ $optionVotes }}</span>
                                                                    </td>
                                                                    <td>
                                                                        <div class="progress" style="margin-bottom: 0;">
                                                                            <div class="progress-bar progress-bar-info" 
                                                                                 role="progressbar" 
                                                                                 style="width: {{ $percentage }}%">
                                                                                {{ number_format($percentage, 1) }}%
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr class="info">
                                                                <td><strong>{{ __('المجموع') }}</strong></td>
                                                                <td><strong>{{ $questionTotalVotes }}</strong></td>
                                                                <td><strong>100%</strong></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            {{ __('لا توجد خيارات لهذا السؤال') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                    @else
                        <!-- Old System: Single Question with Direct Options -->
                        <h3 style="margin-bottom: 30px;">{{ __('نتائج التصويت') }}</h3>
                        
                        @if($poll->pollOptions->count() > 0)
                            <div class="row">
                                <!-- Chart Column -->
                                <div class="col-md-6">
                                    <canvas id="chart-poll-{{ $poll->id }}" height="300"></canvas>
                                </div>
                                
                                <!-- Results Table Column -->
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('الترتيب') }}</th>
                                                    <th>{{ __('الخيار') }}</th>
                                                    <th>{{ __('عدد الأصوات') }}</th>
                                                    <th>{{ __('النسبة المئوية') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $totalVotes = $poll->pollAnswers->count();
                                                    $rank = 1;
                                                @endphp
                                                @foreach($poll->pollOptions as $option)
                                                    @php
                                                        $percentage = $totalVotes > 0 ? ($option->votes / $totalVotes) * 100 : 0;
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            @if($rank == 1)
                                                                <span class="label label-success">{{ $rank }}</span>
                                                            @elseif($rank == 2)
                                                                <span class="label label-warning">{{ $rank }}</span>
                                                            @elseif($rank == 3)
                                                                <span class="label label-info">{{ $rank }}</span>
                                                            @else
                                                                <span class="label label-default">{{ $rank }}</span>
                                                            @endif
                                                        </td>
                                                        <td><strong>{{ $option->option_text }}</strong></td>
                                                        <td>
                                                            <span class="badge badge-primary">{{ $option->votes }}</span>
                                                        </td>
                                                        <td>
                                                            <div class="progress" style="margin-bottom: 0;">
                                                                <div class="progress-bar 
                                                                    @if($rank == 1) progress-bar-success
                                                                    @elseif($rank == 2) progress-bar-warning
                                                                    @elseif($rank == 3) progress-bar-info
                                                                    @else progress-bar-default
                                                                    @endif" 
                                                                    role="progressbar" 
                                                                    style="width: {{ $percentage }}%">
                                                                    {{ number_format($percentage, 1) }}%
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @php $rank++; @endphp
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="info">
                                                    <td colspan="2"><strong>{{ __('المجموع') }}</strong></td>
                                                    <td><strong>{{ $totalVotes }}</strong></td>
                                                    <td><strong>100%</strong></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <h4>{{ __('لا توجد خيارات') }}</h4>
                                <p>{{ __('لا توجد خيارات لهذا الاستطلاع بعد.') }}</p>
                            </div>
                        @endif
                    @endif

                    <!-- Participants Details -->
                    @if($poll->pollAnswers->count() > 0)
                        <hr>
                        <h3>{{ __('تفاصيل المشاركين') }}</h3>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('المشارك') }}</th>
                                        <th>{{ __('الخيار المختار') }}</th>
                                        <th>{{ __('تاريخ التصويت') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($poll->pollAnswers as $answer)
                                        <tr>
                                            <td>
                                                {{ $answer->user->name ?? __('غير معروف') }}
                                            </td>
                                            <td>
                                                <span class="label label-info">{{ $answer->pollOption->option_text ?? __('غير محدد') }}</span>
                                            </td>
                                            <td>{{ $answer->answer_date->format('Y-m-d H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Charts for new system (multiple questions)
    @if($poll->questions->count() > 0)
        @foreach($poll->questions as $question)
            @if($question->options->count() > 0)
                @php
                    $questionTotalVotes = $question->total_votes;
                    $labels = [];
                    $data = [];
                    $colors = ['#36A2EB', '#4BC0C0', '#FFCE56', '#FF6384', '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'];
                @endphp
                @foreach($question->options as $index => $option)
                    @php
                        $optionVotes = $question->answers->where('poll_option_id', $option->id)->count();
                        $labels[] = $option->option_text;
                        $data[] = $optionVotes;
                    @endphp
                @endforeach
                
                var ctx{{ $question->id }} = document.getElementById('chart-question-{{ $question->id }}').getContext('2d');
                new Chart(ctx{{ $question->id }}, {
                    type: '{{ $question->question_type === 'multiple' ? 'bar' : 'pie' }}',
                    data: {
                        labels: @json($labels),
                        datasets: [{
                            label: '{{ __('عدد الأصوات') }}',
                            data: @json($data),
                            backgroundColor: @json(array_slice($colors, 0, count($labels))),
                            borderColor: '#fff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                rtl: true
                            },
                            title: {
                                display: true,
                                text: '{{ __('السؤال') }} {{ $questionIndex + 1 }}: {{ addslashes($question->question_text) }}'
                            }
                        }
                    }
                });
            @endif
        @endforeach
    @else
        // Chart for old system (single question)
        @if($poll->pollOptions->count() > 0)
            @php
                $labels = [];
                $data = [];
                $colors = ['#36A2EB', '#4BC0C0', '#FFCE56', '#FF6384', '#9966FF', '#FF9F40'];
            @endphp
            @foreach($poll->pollOptions as $index => $option)
                @php
                    $labels[] = $option->option_text;
                    $data[] = $option->votes;
                @endphp
            @endforeach
            
            var ctx{{ $poll->id }} = document.getElementById('chart-poll-{{ $poll->id }}').getContext('2d');
            new Chart(ctx{{ $poll->id }}, {
                type: 'pie',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: '{{ __('عدد الأصوات') }}',
                        data: @json($data),
                        backgroundColor: @json(array_slice($colors, 0, count($labels))),
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            rtl: true
                        },
                        title: {
                            display: true,
                            text: '{{ __('نتائج الاستطلاع') }}'
                        }
                    }
                }
            });
        @endif
    @endif
});
</script>
@endsection
