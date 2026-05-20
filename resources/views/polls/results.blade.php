@extends('layouts.app')

@section('title', __('نتائج الاستطلاع') . ' - ' . ($poll->title ?? $poll->question))

@include('polls.partials.ui-styles')

@php
    $totalAnswers = $poll->pollAnswers->count();
    $questionsCount = $poll->questions->count();
    $directOptionsCount = $poll->pollOptions->count();
    $targetedUsersCount = $poll->referencedUsers->count();
    $participationBase = $targetedUsersCount > 0 ? $targetedUsersCount : \App\Models\User::count();
    $participationRate = $participationBase > 0 ? ($totalAnswers / $participationBase) * 100 : 0;
    $answerGroupsByUser = $poll->pollAnswers->groupBy('user_id');
    $participantUsers = $poll->referencedUsers->isNotEmpty()
        ? $poll->referencedUsers
        : $poll->pollAnswers->pluck('user')->filter()->unique('id')->values();

    if (! $poll->is_active) {
        $status = ['label' => __('متوقف'), 'class' => 'inactive', 'icon' => 'bi-pause-circle'];
    } elseif ($poll->end_date < now()) {
        $status = ['label' => __('منتهي'), 'class' => 'ended', 'icon' => 'bi-check2-circle'];
    } elseif ($poll->start_date > now()) {
        $status = ['label' => __('قادم'), 'class' => 'upcoming', 'icon' => 'bi-clock-history'];
    } else {
        $status = ['label' => __('نشط الآن'), 'class' => 'active', 'icon' => 'bi-broadcast'];
    }
@endphp

@section('content')
<div class="poll-page">
    <div class="poll-shell">
        <section class="poll-hero">
            <div class="poll-hero-inner">
                <div>
                    <span class="poll-badge">
                        <i class="bi bi-bar-chart-fill"></i>
                        {{ __('نتائج الاستطلاع') }}
                    </span>
                    <h1 class="poll-title">{{ $poll->title ?? $poll->question }}</h1>
                    <div class="poll-meta-row">
                        <span class="poll-status-badge {{ $status['class'] }}">
                            <i class="bi {{ $status['icon'] }}"></i>
                            {{ $status['label'] }}
                        </span>
                        <span class="poll-chip"><i class="bi bi-person-badge-fill"></i>{{ optional($poll->creator)->name ?? __('غير معروف') }}</span>
                        <span class="poll-chip"><i class="bi bi-calendar2-range-fill"></i>{{ $poll->start_date->format('Y-m-d H:i') }} - {{ $poll->end_date->format('Y-m-d H:i') }}</span>
                    </div>
                </div>

                <div class="poll-hero-actions">
                    <a href="{{ route('polls.show', $poll) }}" class="poll-btn">
                        <i class="bi bi-eye-fill"></i>
                        {{ __('عرض الاستطلاع') }}
                    </a>
                    <a href="{{ route('polls.index') }}" class="poll-btn-muted">
                        <i class="bi bi-arrow-right-circle"></i>
                        {{ __('العودة للقائمة') }}
                    </a>
                </div>
            </div>
        </section>

        <section class="poll-stats-grid">
            <article class="poll-stat-card" style="animation-delay: 0.05s;">
                <div class="poll-stat-icon"><i class="bi bi-hand-thumbs-up-fill"></i></div>
                <p class="poll-stat-value">{{ number_format($totalAnswers) }}</p>
                <p class="poll-stat-label">{{ __('إجمالي الأصوات') }}</p>
            </article>
            <article class="poll-stat-card" style="animation-delay: 0.11s;">
                <div class="poll-stat-icon"><i class="bi bi-diagram-3-fill"></i></div>
                <p class="poll-stat-value">{{ number_format($questionsCount > 0 ? $questionsCount : $directOptionsCount) }}</p>
                <p class="poll-stat-label">{{ $questionsCount > 0 ? __('عدد الأسئلة') : __('عدد الخيارات') }}</p>
            </article>
            <article class="poll-stat-card" style="animation-delay: 0.17s;">
                <div class="poll-stat-icon"><i class="bi bi-people-fill"></i></div>
                <p class="poll-stat-value">{{ number_format($targetedUsersCount) }}</p>
                <p class="poll-stat-label">{{ __('المشاركون المستهدفون') }}</p>
            </article>
            <article class="poll-stat-card" style="animation-delay: 0.23s;">
                <div class="poll-stat-icon"><i class="bi bi-percent"></i></div>
                <p class="poll-stat-value">{{ number_format($participationRate, 1) }}%</p>
                <p class="poll-stat-label">{{ __('معدل المشاركة') }}</p>
            </article>
        </section>

        <div class="poll-grid">
            <section class="poll-card">
                <div class="poll-card-header">
                    <div class="poll-card-title-wrap">
                        <span class="poll-card-icon"><i class="bi bi-info-circle-fill"></i></span>
                        <div>
                            <h2 class="poll-card-title">{{ __('نظرة عامة') }}</h2>
                            <p class="poll-card-note">{{ __('معلومات تشغيلية مختصرة لمراجعة سياق الاستطلاع قبل قراءة النتائج التفصيلية.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="poll-detail-grid">
                    <div class="poll-detail-item">
                        <span class="poll-detail-label">{{ __('السؤال الرئيسي') }}</span>
                        <div class="poll-detail-value">{{ $poll->question }}</div>
                    </div>
                    <div class="poll-detail-item">
                        <span class="poll-detail-label">{{ __('اجتماع الزوم') }}</span>
                        <div class="poll-detail-value">{{ $poll->zoomMeeting?->title ?? __('غير مرتبط') }}</div>
                    </div>
                    <div class="poll-detail-item">
                        <span class="poll-detail-label">{{ __('تاريخ البدء') }}</span>
                        <div class="poll-detail-value">{{ $poll->start_date->format('Y-m-d H:i') }}</div>
                    </div>
                    <div class="poll-detail-item">
                        <span class="poll-detail-label">{{ __('تاريخ الانتهاء') }}</span>
                        <div class="poll-detail-value">{{ $poll->end_date->format('Y-m-d H:i') }}</div>
                    </div>
                    <div class="poll-detail-item">
                        <span class="poll-detail-label">{{ __('نوع البنية') }}</span>
                        <div class="poll-detail-value">{{ $questionsCount > 0 ? __('أسئلة متعددة') : __('خيارات مباشرة') }}</div>
                    </div>
                    <div class="poll-detail-item">
                        <span class="poll-detail-label">{{ __('الوصف') }}</span>
                        <div class="poll-detail-value">{{ $poll->description ?: __('لا يوجد وصف إضافي.') }}</div>
                    </div>
                </div>
            </section>

            <section class="poll-card">
                <div class="poll-card-header">
                    <div class="poll-card-title-wrap">
                        <span class="poll-card-icon"><i class="bi bi-speedometer2"></i></span>
                        <div>
                            <h2 class="poll-card-title">{{ __('مؤشرات سريعة') }}</h2>
                            <p class="poll-card-note">{{ __('أرقام موجزة تساعد على تقييم مستوى التفاعل بسرعة.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="poll-mini-stats">
                    <div class="poll-mini-stat">
                        <span class="poll-mini-label">{{ __('الأصوات المسجلة') }}</span>
                        <div class="poll-mini-value">{{ number_format($totalAnswers) }}</div>
                    </div>
                    <div class="poll-mini-stat">
                        <span class="poll-mini-label">{{ __('عدد المشاركين المرجعي') }}</span>
                        <div class="poll-mini-value">{{ number_format($participationBase) }}</div>
                    </div>
                    <div class="poll-mini-stat">
                        <span class="poll-mini-label">{{ __('تاريخ الإنشاء') }}</span>
                        <div class="poll-mini-value">{{ optional($poll->created_date)->format('Y-m-d H:i') ?? __('غير متوفر') }}</div>
                    </div>
                </div>
            </section>

            @if($questionsCount > 0)
                @foreach($poll->questions as $question)
                    @php
                        $questionTotalVotes = $question->total_votes;
                    @endphp
                    <section class="poll-card full-span">
                        <div class="poll-card-header">
                            <div class="poll-card-title-wrap">
                                <span class="poll-card-icon"><i class="bi bi-question-circle-fill"></i></span>
                                <div>
                                    <h2 class="poll-card-title">{{ __('السؤال') }} {{ $loop->iteration }}: {{ $question->question_text }}</h2>
                                    <p class="poll-card-note">
                                        {{ $question->question_type === 'multiple' ? __('هذا السؤال يسمح باختيار أكثر من خيار.') : __('هذا السؤال يسمح باختيار خيار واحد فقط.') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if($question->options->count() > 0)
                            <div class="poll-results-grid">
                                <div class="poll-chart-wrap">
                                    <canvas id="question-chart-{{ $question->id }}"></canvas>
                                </div>
                                <div class="poll-progress-list">
                                    @foreach($question->options as $option)
                                        @php
                                            $optionVotes = $question->answers->where('poll_option_id', $option->id)->count();
                                            $percentage = $questionTotalVotes > 0 ? ($optionVotes / $questionTotalVotes) * 100 : 0;
                                        @endphp
                                        <article class="poll-progress-item">
                                            <div class="poll-progress-head">
                                                <strong>{{ $option->option_text }}</strong>
                                                <span class="poll-progress-values">{{ number_format($optionVotes) }} {{ __('صوت') }} - {{ number_format($percentage, 1) }}%</span>
                                            </div>
                                            <div class="poll-progress">
                                                <div class="poll-progress-bar" style="width: {{ $percentage }}%;"></div>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="poll-empty-state">
                                <i class="bi bi-ui-checks"></i>
                                <h3>{{ __('لا توجد خيارات لهذا السؤال') }}</h3>
                                <p>{{ __('لم يتم العثور على خيارات مرتبطة بهذا السؤال حتى الآن.') }}</p>
                            </div>
                        @endif
                    </section>
                @endforeach
            @else
                <section class="poll-card full-span">
                    <div class="poll-card-header">
                        <div class="poll-card-title-wrap">
                            <span class="poll-card-icon"><i class="bi bi-pie-chart-fill"></i></span>
                            <div>
                                <h2 class="poll-card-title">{{ __('نتائج التصويت المباشر') }}</h2>
                                <p class="poll-card-note">{{ __('عرض بصري ونسبي لجميع خيارات الاستطلاع المباشر.') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($poll->pollOptions->count() > 0)
                        <div class="poll-results-grid">
                            <div class="poll-chart-wrap">
                                <canvas id="poll-chart-{{ $poll->id }}"></canvas>
                            </div>
                            <div class="poll-progress-list">
                                @foreach($poll->pollOptions as $option)
                                    @php
                                        $percentage = $totalAnswers > 0 ? ($option->votes / $totalAnswers) * 100 : 0;
                                    @endphp
                                    <article class="poll-progress-item">
                                        <div class="poll-progress-head">
                                            <strong>{{ $option->option_text }}</strong>
                                            <span class="poll-progress-values">{{ number_format($option->votes) }} {{ __('صوت') }} - {{ number_format($percentage, 1) }}%</span>
                                        </div>
                                        <div class="poll-progress">
                                            <div class="poll-progress-bar" style="width: {{ $percentage }}%;"></div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="poll-empty-state">
                            <i class="bi bi-ui-checks-grid"></i>
                            <h3>{{ __('لا توجد خيارات') }}</h3>
                            <p>{{ __('لم يتم إنشاء خيارات لهذا الاستطلاع بعد.') }}</p>
                        </div>
                    @endif
                </section>
            @endif

            <section class="poll-card full-span">
                <div class="poll-card-header">
                    <div class="poll-card-title-wrap">
                        <span class="poll-card-icon"><i class="bi bi-people-fill"></i></span>
                        <div>
                            <h2 class="poll-card-title">{{ __('تفاصيل المدعوين والتصويت') }}</h2>
                            <p class="poll-card-note">{{ __('يعرض هذا الجدول كل حساب مدعو للاستطلاع، وما إذا صوت، والخيار أو الخيارات التي اختارها مع توقيت التصويت.') }}</p>
                        </div>
                    </div>
                </div>

                @if($participantUsers->count() > 0)
                    <table class="poll-attendees-table">
                        <thead>
                            <tr>
                                <th>{{ __('الحساب المدعو') }}</th>
                                <th>{{ __('حالة التصويت') }}</th>
                                <th>{{ __('ما تم اختياره') }}</th>
                                <th>{{ __('تاريخ التصويت') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($participantUsers as $user)
                                @php
                                    $answers = $answerGroupsByUser->get($user->id, collect());
                                    $latestAnswer = $answers->sortByDesc('answer_date')->first();
                                    $selectedAnswers = $answers->map(function ($answer) {
                                        $optionText = optional($answer->pollOption)->option_text ?? __('غير محدد');
                                        $questionText = optional($answer->question)->question_text;

                                        return $questionText ? $questionText . ': ' . $optionText : $optionText;
                                    })->filter()->implode('، ');
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $user->name }}</strong>
                                        <div class="poll-card-note" style="margin: 4px 0 0;">{{ $user->email }}</div>
                                    </td>
                                    <td>{{ $answers->isNotEmpty() ? __('صوّت') : __('لم يصوت') }}</td>
                                    <td>{{ $selectedAnswers ?: __('لا يوجد تصويت مسجل') }}</td>
                                    <td>{{ optional($latestAnswer?->answer_date)->format('Y-m-d H:i') ?? __('غير متوفر') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="poll-empty-state">
                        <i class="bi bi-person-lines-fill"></i>
                        <h3>{{ __('لا توجد حسابات مدعوة') }}</h3>
                        <p>{{ __('لم يتم العثور على مدعوين أو مصوتين مرتبطين بهذا الاستطلاع حتى الآن.') }}</p>
                    </div>
                @endif
            </section>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Chart === 'undefined') {
            return;
        }

        const computedStyle = getComputedStyle(document.documentElement);
        const textColor = computedStyle.getPropertyValue('--text-primary').trim() || '#1e293b';
        const secondaryColor = computedStyle.getPropertyValue('--text-secondary').trim() || '#64748b';
        const borderColor = computedStyle.getPropertyValue('--border-color').trim() || 'rgba(226, 232, 240, 0.7)';
        const colorPalette = ['#aa863f', '#d3af6a', '#0ea5e9', '#059669', '#d97706', '#dc2626', '#8b5cf6', '#14b8a6'];

        const buildOptions = function (title) {
            return {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: textColor,
                            font: {
                                family: 'Zain, sans-serif',
                                size: 14
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: title,
                        color: textColor,
                        font: {
                            family: 'Zain, sans-serif',
                            size: 18,
                            weight: '700'
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            color: secondaryColor
                        },
                        grid: {
                            color: borderColor
                        }
                    },
                    x: {
                        ticks: {
                            color: secondaryColor
                        },
                        grid: {
                            color: borderColor
                        }
                    }
                }
            };
        };

        @if($questionsCount > 0)
            @foreach($poll->questions as $question)
                @if($question->options->count() > 0)
                    new Chart(document.getElementById('question-chart-{{ $question->id }}'), {
                        type: '{{ $question->question_type === 'multiple' ? 'bar' : 'doughnut' }}',
                        data: {
                            labels: @json($question->options->pluck('option_text')->values()),
                            datasets: [{
                                data: @json($question->options->map(fn ($option) => $question->answers->where('poll_option_id', $option->id)->count())->values()),
                                backgroundColor: colorPalette.slice(0, {{ $question->options->count() }}),
                                borderColor: borderColor,
                                borderWidth: 1.5
                            }]
                        },
                        options: buildOptions(@json(__('السؤال') . ' ' . $loop->iteration))
                    });
                @endif
            @endforeach
        @elseif($poll->pollOptions->count() > 0)
            new Chart(document.getElementById('poll-chart-{{ $poll->id }}'), {
                type: 'doughnut',
                data: {
                    labels: @json($poll->pollOptions->pluck('option_text')->values()),
                    datasets: [{
                        data: @json($poll->pollOptions->pluck('votes')->values()),
                        backgroundColor: colorPalette.slice(0, {{ $poll->pollOptions->count() }}),
                        borderColor: borderColor,
                        borderWidth: 1.5
                    }]
                },
                options: buildOptions(@json(__('نتائج الاستطلاع')))
            });
        @endif
    });
</script>
@endpush
