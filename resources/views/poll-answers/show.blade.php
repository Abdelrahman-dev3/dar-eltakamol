@extends('layouts.app')

@section('title', __('عرض التصويت') . ' - ' . ($pollAnswer->user->name ?? __('غير معروف')))

@include('polls.partials.ui-styles')

@php
    $poll = $pollAnswer->poll;
    $pollOption = $pollAnswer->pollOption;
    $user = $pollAnswer->user;
    $pollAnswers = $poll?->pollAnswers ?? collect();
    $totalPollVotes = $pollAnswers->count();
    $selectedOptionVotes = $pollOption?->votes ?? 0;
    $selectedOptionPercentage = $totalPollVotes > 0 ? ($selectedOptionVotes / $totalPollVotes) * 100 : 0;
    $selectedOptionAnswers = $pollOption ? $pollAnswers->where('poll_option_id', $pollOption->id) : collect();
    $recentAnswers = $pollAnswers->sortByDesc('answer_date')->take(6);
@endphp

@section('content')
<div class="poll-page">
    <div class="poll-shell">
        <section class="poll-hero">
            <div class="poll-hero-inner">
                <div>
                    <span class="poll-badge">
                        <i class="bi bi-check2-square"></i>
                        {{ __('تفاصيل التصويت') }} #{{ $pollAnswer->id }}
                    </span>
                    <h1 class="poll-title">{{ $user?->name ?? __('مستخدم غير معروف') }}</h1>
                    <div class="poll-meta-row">
                        <span class="poll-chip"><i class="bi bi-envelope-fill"></i>{{ $user?->email ?? __('بدون بريد إلكتروني') }}</span>
                        <span class="poll-chip"><i class="bi bi-calendar2-check"></i>{{ optional($pollAnswer->answer_date)->format('Y-m-d H:i') ?? __('غير متوفر') }}</span>
                        <span class="poll-chip"><i class="bi bi-list-check"></i>{{ $pollOption?->option_text ?? __('غير محدد') }}</span>
                    </div>
                </div>

                <div class="poll-hero-actions">
                    <a href="{{ route('poll-answers.edit', $pollAnswer) }}" class="poll-btn">
                        <i class="bi bi-pencil-square"></i>
                        {{ __('تعديل') }}
                    </a>
                    <a href="{{ route('poll-answers.index') }}" class="poll-btn-muted">
                        <i class="bi bi-arrow-right-circle"></i>
                        {{ __('العودة للقائمة') }}
                    </a>
                </div>
            </div>
        </section>

        <section class="poll-stats-grid">
            <article class="poll-stat-card" style="animation-delay: 0.05s;">
                <div class="poll-stat-icon"><i class="bi bi-hash"></i></div>
                <p class="poll-stat-value">#{{ $pollAnswer->id }}</p>
                <p class="poll-stat-label">{{ __('رقم التصويت') }}</p>
            </article>
            <article class="poll-stat-card" style="animation-delay: 0.11s;">
                <div class="poll-stat-icon"><i class="bi bi-bar-chart-fill"></i></div>
                <p class="poll-stat-value">{{ number_format($totalPollVotes) }}</p>
                <p class="poll-stat-label">{{ __('إجمالي أصوات الاستطلاع') }}</p>
            </article>
            <article class="poll-stat-card" style="animation-delay: 0.17s;">
                <div class="poll-stat-icon"><i class="bi bi-percent"></i></div>
                <p class="poll-stat-value">{{ number_format($selectedOptionPercentage, 1) }}%</p>
                <p class="poll-stat-label">{{ __('نسبة الخيار المختار') }}</p>
            </article>
            <article class="poll-stat-card" style="animation-delay: 0.23s;">
                <div class="poll-stat-icon"><i class="bi bi-people-fill"></i></div>
                <p class="poll-stat-value">{{ number_format($selectedOptionAnswers->count()) }}</p>
                <p class="poll-stat-label">{{ __('تصويتات على نفس الخيار') }}</p>
            </article>
        </section>

        <div class="poll-grid">
            <section class="poll-card">
                <div class="poll-card-header">
                    <div class="poll-card-title-wrap">
                        <span class="poll-card-icon"><i class="bi bi-person-badge-fill"></i></span>
                        <div>
                            <h2 class="poll-card-title">{{ __('بيانات المستخدم والتصويت') }}</h2>
                            <p class="poll-card-note">{{ __('المعلومات الأساسية المرتبطة بهذا التصويت مع التواريخ الرئيسية الخاصة به.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="poll-detail-grid">
                    <div class="poll-detail-item">
                        <span class="poll-detail-label">{{ __('اسم المستخدم') }}</span>
                        <div class="poll-detail-value">{{ $user?->name ?? __('غير معروف') }}</div>
                    </div>
                    <div class="poll-detail-item">
                        <span class="poll-detail-label">{{ __('البريد الإلكتروني') }}</span>
                        <div class="poll-detail-value">{{ $user?->email ?? __('غير متوفر') }}</div>
                    </div>
                    <div class="poll-detail-item">
                        <span class="poll-detail-label">{{ __('تاريخ التصويت') }}</span>
                        <div class="poll-detail-value">{{ optional($pollAnswer->answer_date)->format('Y-m-d H:i') ?? __('غير متوفر') }}</div>
                    </div>
                    <div class="poll-detail-item">
                        <span class="poll-detail-label">{{ __('تاريخ الإنشاء') }}</span>
                        <div class="poll-detail-value">{{ optional($pollAnswer->created_at)->format('Y-m-d H:i') ?? __('غير متوفر') }}</div>
                    </div>
                    <div class="poll-detail-item">
                        <span class="poll-detail-label">{{ __('آخر تحديث') }}</span>
                        <div class="poll-detail-value">{{ optional($pollAnswer->updated_at)->format('Y-m-d H:i') ?? __('غير متوفر') }}</div>
                    </div>
                    <div class="poll-detail-item">
                        <span class="poll-detail-label">{{ __('حالة الاستطلاع') }}</span>
                        <div class="poll-detail-value">
                            @if($poll?->is_active)
                                {{ __('نشط') }}
                            @elseif($poll)
                                {{ __('غير نشط') }}
                            @else
                                {{ __('غير متوفر') }}
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            <section class="poll-card">
                <div class="poll-card-header">
                    <div class="poll-card-title-wrap">
                        <span class="poll-card-icon"><i class="bi bi-ui-radios-grid"></i></span>
                        <div>
                            <h2 class="poll-card-title">{{ __('الاستطلاع والخيار') }}</h2>
                            <p class="poll-card-note">{{ __('ربط مباشر بين سؤال الاستطلاع والخيار الذي اختاره المستخدم.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="poll-mini-stats">
                    <div class="poll-mini-stat">
                        <span class="poll-mini-label">{{ __('سؤال الاستطلاع') }}</span>
                        <div class="poll-mini-value">{{ $poll ? \Illuminate\Support\Str::limit($poll->question, 140) : __('غير متوفر') }}</div>
                    </div>
                    <div class="poll-mini-stat">
                        <span class="poll-mini-label">{{ __('الخيار المختار') }}</span>
                        <div class="poll-mini-value">{{ $pollOption?->option_text ?? __('غير محدد') }}</div>
                    </div>
                    <div class="poll-mini-stat">
                        <span class="poll-mini-label">{{ __('أصوات هذا الخيار') }}</span>
                        <div class="poll-mini-value">{{ number_format($selectedOptionVotes) }}</div>
                    </div>
                    <div class="poll-mini-stat">
                        <span class="poll-mini-label">{{ __('إجمالي خيارات الاستطلاع') }}</span>
                        <div class="poll-mini-value">{{ number_format($poll?->pollOptions?->count() ?? 0) }}</div>
                    </div>
                </div>

                <div class="poll-footer-actions" style="margin-top: 18px;">
                    @if($poll)
                        <a href="{{ route('polls.show', $poll) }}" class="poll-btn-muted">
                            <i class="bi bi-eye-fill"></i>
                            {{ __('عرض الاستطلاع') }}
                        </a>
                    @endif
                    @if($pollOption)
                        <a href="{{ route('poll-options.show', $pollOption) }}" class="poll-btn-muted">
                            <i class="bi bi-list-check"></i>
                            {{ __('عرض الخيار') }}
                        </a>
                    @endif
                </div>
            </section>

            <section class="poll-card full-span">
                <div class="poll-card-header">
                    <div class="poll-card-title-wrap">
                        <span class="poll-card-icon"><i class="bi bi-bar-chart-steps"></i></span>
                        <div>
                            <h2 class="poll-card-title">{{ __('أداء الخيار داخل الاستطلاع') }}</h2>
                            <p class="poll-card-note">{{ __('قراءة سريعة لموقع هذا الخيار من إجمالي أصوات الاستطلاع الحالي.') }}</p>
                        </div>
                    </div>
                </div>

                @if($poll && $pollOption)
                    <div class="poll-progress-list">
                        <article class="poll-progress-item">
                            <div class="poll-progress-head">
                                <strong>{{ __('نسبة الخيار من إجمالي الأصوات') }}</strong>
                                <span class="poll-progress-values">{{ number_format($selectedOptionPercentage, 1) }}%</span>
                            </div>
                            <div class="poll-progress">
                                <div class="poll-progress-bar" style="width: {{ $selectedOptionPercentage }}%;"></div>
                            </div>
                        </article>
                        <article class="poll-progress-item">
                            <div class="poll-progress-head">
                                <strong>{{ __('عدد الأصوات على الخيار') }}</strong>
                                <span class="poll-progress-values">{{ number_format($selectedOptionVotes) }}</span>
                            </div>
                        </article>
                        <article class="poll-progress-item">
                            <div class="poll-progress-head">
                                <strong>{{ __('إجمالي أصوات الاستطلاع') }}</strong>
                                <span class="poll-progress-values">{{ number_format($totalPollVotes) }}</span>
                            </div>
                        </article>
                    </div>
                @else
                    <div class="poll-empty-state">
                        <i class="bi bi-bar-chart-line-fill"></i>
                        <h3>{{ __('تعذر تحميل إحصائيات هذا السجل') }}</h3>
                        <p>{{ __('بعض العلاقات المرتبطة بهذا التصويت غير متوفرة حاليًا، لذلك لا يمكن عرض مقارنة الأداء بشكل كامل.') }}</p>
                    </div>
                @endif
            </section>

            <section class="poll-card full-span">
                <div class="poll-card-header">
                    <div class="poll-card-title-wrap">
                        <span class="poll-card-icon"><i class="bi bi-clock-history"></i></span>
                        <div>
                            <h2 class="poll-card-title">{{ __('آخر التصويتات داخل الاستطلاع') }}</h2>
                            <p class="poll-card-note">{{ __('نظرة سريعة على أحدث المشاركات داخل نفس الاستطلاع لتسهيل المتابعة والمراجعة.') }}</p>
                        </div>
                    </div>
                </div>

                @if($recentAnswers->count() > 0)
                    <table class="poll-attendees-table">
                        <thead>
                            <tr>
                                <th>{{ __('المستخدم') }}</th>
                                <th>{{ __('الخيار') }}</th>
                                <th>{{ __('تاريخ التصويت') }}</th>
                                <th>{{ __('الإجراء') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAnswers as $answer)
                                <tr>
                                    <td>{{ $answer->user?->name ?? __('غير معروف') }}</td>
                                    <td>{{ $answer->pollOption?->option_text ?? __('غير محدد') }}</td>
                                    <td>{{ optional($answer->answer_date)->format('Y-m-d H:i') ?? __('غير متوفر') }}</td>
                                    <td>
                                        <a href="{{ route('poll-answers.show', $answer) }}" class="poll-btn-muted" style="min-height: 40px; padding: 8px 14px;">
                                            <i class="bi bi-eye-fill"></i>
                                            {{ __('عرض التصويت') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="poll-empty-state">
                        <i class="bi bi-inboxes-fill"></i>
                        <h3>{{ __('لا توجد تصويتات أخرى لعرضها') }}</h3>
                        <p>{{ __('هذه الصفحة لا تحتوي حاليًا على سجل حديث إضافي داخل نفس الاستطلاع.') }}</p>
                    </div>
                @endif
            </section>
        </div>
    </div>
</div>
@endsection
