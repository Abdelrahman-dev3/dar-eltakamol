@extends('layouts.app')

@section('title', __('عرض خيار الاستطلاع') . ' - ' . $pollOption->option_text)

@include('polls.partials.ui-styles')

@php
    $poll = $pollOption->poll;
    $answersForOption = $poll ? $poll->pollAnswers->where('poll_option_id', $pollOption->id)->sortByDesc('answer_date') : collect();
    $totalPollVotes = $poll ? $poll->pollAnswers->count() : 0;
    $percentage = $totalPollVotes > 0 ? ($pollOption->votes / $totalPollVotes) * 100 : 0;
@endphp

@section('content')
<div class="poll-page">
    <div class="poll-shell">
        <section class="poll-hero">
            <div class="poll-hero-inner">
                <div>
                    <span class="poll-badge">
                        <i class="bi bi-check2-square"></i>
                        {{ __('تفاصيل خيار الاستطلاع') }} #{{ $pollOption->id }}
                    </span>
                    <h1 class="poll-title">{{ $pollOption->option_text }}</h1>
                    <div class="poll-meta-row">
                        <span class="poll-chip"><i class="bi bi-hand-thumbs-up-fill"></i>{{ number_format($pollOption->votes) }} {{ __('صوت') }}</span>
                        <span class="poll-chip"><i class="bi bi-percent"></i>{{ number_format($percentage, 1) }}%</span>
                        <span class="poll-chip"><i class="bi bi-ui-radios-grid"></i>{{ $poll ? '#' . $poll->id : __('غير مرتبط') }}</span>
                    </div>
                </div>

                <div class="poll-hero-actions">
                    <a href="{{ route('poll-options.edit', $pollOption) }}" class="poll-btn">
                        <i class="bi bi-pencil-square"></i>
                        {{ __('تعديل') }}
                    </a>
                    <a href="{{ route('poll-options.index') }}" class="poll-btn-muted">
                        <i class="bi bi-arrow-right-circle"></i>
                        {{ __('العودة للقائمة') }}
                    </a>
                </div>
            </div>
        </section>

        <section class="poll-stats-grid">
            <article class="poll-stat-card" style="animation-delay: 0.05s;">
                <div class="poll-stat-icon"><i class="bi bi-hand-thumbs-up-fill"></i></div>
                <p class="poll-stat-value">{{ number_format($pollOption->votes) }}</p>
                <p class="poll-stat-label">{{ __('أصوات هذا الخيار') }}</p>
            </article>
            <article class="poll-stat-card" style="animation-delay: 0.11s;">
                <div class="poll-stat-icon"><i class="bi bi-people-fill"></i></div>
                <p class="poll-stat-value">{{ number_format($answersForOption->count()) }}</p>
                <p class="poll-stat-label">{{ __('عدد المشاركات المرتبطة') }}</p>
            </article>
            <article class="poll-stat-card" style="animation-delay: 0.17s;">
                <div class="poll-stat-icon"><i class="bi bi-percent"></i></div>
                <p class="poll-stat-value">{{ number_format($percentage, 1) }}%</p>
                <p class="poll-stat-label">{{ __('من إجمالي الاستطلاع') }}</p>
            </article>
            <article class="poll-stat-card" style="animation-delay: 0.23s;">
                <div class="poll-stat-icon"><i class="bi bi-calendar2-check-fill"></i></div>
                <p class="poll-stat-value">{{ optional($pollOption->created_at)->format('Y-m-d') ?? '--' }}</p>
                <p class="poll-stat-label">{{ __('تاريخ الإنشاء') }}</p>
            </article>
        </section>

        <div class="poll-grid">
            <section class="poll-card">
                <div class="poll-card-header">
                    <div class="poll-card-title-wrap">
                        <span class="poll-card-icon"><i class="bi bi-info-circle-fill"></i></span>
                        <div>
                            <h2 class="poll-card-title">{{ __('المعلومات الأساسية') }}</h2>
                            <p class="poll-card-note">{{ __('نظرة عامة على النص والارتباط الزمني والإداري لهذا الخيار.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="poll-detail-grid">
                    <div class="poll-detail-item">
                        <span class="poll-detail-label">{{ __('نص الخيار') }}</span>
                        <div class="poll-detail-value">{{ $pollOption->option_text }}</div>
                    </div>
                    <div class="poll-detail-item">
                        <span class="poll-detail-label">{{ __('الاستطلاع المرتبط') }}</span>
                        <div class="poll-detail-value">{{ $poll ? \Illuminate\Support\Str::limit($poll->question, 110) : __('غير مرتبط') }}</div>
                    </div>
                    <div class="poll-detail-item">
                        <span class="poll-detail-label">{{ __('تاريخ الإنشاء') }}</span>
                        <div class="poll-detail-value">{{ optional($pollOption->created_at)->format('Y-m-d H:i') ?? __('غير متوفر') }}</div>
                    </div>
                    <div class="poll-detail-item">
                        <span class="poll-detail-label">{{ __('آخر تحديث') }}</span>
                        <div class="poll-detail-value">{{ optional($pollOption->updated_at)->format('Y-m-d H:i') ?? __('غير متوفر') }}</div>
                    </div>
                </div>
            </section>

            <section class="poll-card">
                <div class="poll-card-header">
                    <div class="poll-card-title-wrap">
                        <span class="poll-card-icon"><i class="bi bi-bar-chart-fill"></i></span>
                        <div>
                            <h2 class="poll-card-title">{{ __('أداء الخيار') }}</h2>
                            <p class="poll-card-note">{{ __('مؤشر بصري يوضّح وزن هذا الخيار داخل الاستطلاع المرتبط.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="poll-progress-list">
                    <article class="poll-progress-item">
                        <div class="poll-progress-head">
                            <strong>{{ __('نسبة الخيار من إجمالي الأصوات') }}</strong>
                            <span class="poll-progress-values">{{ number_format($percentage, 1) }}%</span>
                        </div>
                        <div class="poll-progress">
                            <div class="poll-progress-bar" style="width: {{ $percentage }}%;"></div>
                        </div>
                    </article>
                    <article class="poll-progress-item">
                        <div class="poll-progress-head">
                            <strong>{{ __('إجمالي أصوات الاستطلاع') }}</strong>
                            <span class="poll-progress-values">{{ number_format($totalPollVotes) }}</span>
                        </div>
                    </article>
                </div>
            </section>

            <section class="poll-card full-span">
                <div class="poll-card-header">
                    <div class="poll-card-title-wrap">
                        <span class="poll-card-icon"><i class="bi bi-box-arrow-up-left"></i></span>
                        <div>
                            <h2 class="poll-card-title">{{ __('الارتباط بالاستطلاع') }}</h2>
                            <p class="poll-card-note">{{ __('انتقال سريع إلى صفحة الاستطلاع أو النتائج المرتبطة بهذا الخيار.') }}</p>
                        </div>
                    </div>
                </div>

                @if($poll)
                    <div class="poll-footer-actions">
                        <a href="{{ route('polls.show', $poll) }}" class="poll-btn-muted">
                            <i class="bi bi-eye-fill"></i>
                            {{ __('عرض الاستطلاع') }}
                        </a>
                        <a href="{{ route('polls.results', $poll) }}" class="poll-btn-muted">
                            <i class="bi bi-bar-chart-fill"></i>
                            {{ __('نتائج الاستطلاع') }}
                        </a>
                    </div>
                @else
                    <div class="poll-empty-state">
                        <i class="bi bi-link-45deg"></i>
                        <h3>{{ __('لا يوجد استطلاع مرتبط') }}</h3>
                        <p>{{ __('هذا الخيار غير مرتبط حاليًا باستطلاع ظاهر داخل النظام.') }}</p>
                    </div>
                @endif
            </section>

            <section class="poll-card full-span">
                <div class="poll-card-header">
                    <div class="poll-card-title-wrap">
                        <span class="poll-card-icon"><i class="bi bi-people-fill"></i></span>
                        <div>
                            <h2 class="poll-card-title">{{ __('تفاصيل المشاركين') }}</h2>
                            <p class="poll-card-note">{{ __('المستخدمون الذين اختاروا هذا الخيار مع توقيت التصويت وروابط المتابعة.') }}</p>
                        </div>
                    </div>
                </div>

                @if($answersForOption->count() > 0)
                    <table class="poll-attendees-table">
                        <thead>
                            <tr>
                                <th>{{ __('المستخدم') }}</th>
                                <th>{{ __('تاريخ التصويت') }}</th>
                                <th>{{ __('الإجراء') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($answersForOption as $answer)
                                <tr>
                                    <td>{{ optional($answer->user)->name ?? __('غير معروف') }}</td>
                                    <td>{{ optional($answer->answer_date)->format('Y-m-d H:i') ?? __('غير متوفر') }}</td>
                                    <td>
                                        <a href="{{ route('poll-answers.show', $answer) }}" class="poll-btn-muted" style="min-height: 40px; padding: 8px 14px;">
                                            <i class="bi bi-eye-fill"></i>
                                            {{ __('عرض الإجابة') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="poll-empty-state">
                        <i class="bi bi-person-x-fill"></i>
                        <h3>{{ __('لا توجد أصوات بعد') }}</h3>
                        <p>{{ __('لم يتم اختيار هذا الخيار من قبل أي مستخدم حتى الآن.') }}</p>
                    </div>
                @endif
            </section>
        </div>
    </div>
</div>
@endsection
