@extends('layouts.app')

@section('title', __('عرض الاستطلاع'))

@include('polls.partials.ui-styles')

@php
    $hasQuestions = $poll->questions->count() > 0;
    $totalVotes = $poll->pollOptions->sum('votes');
    $targetedUsersCount = $poll->referencedUsers->count();
    $userHasVoted = auth()->check() ? $poll->pollAnswers->contains(fn ($answer) => $answer->user_id === auth()->id()) : false;
    $canVote = auth()->check() && ! $hasQuestions && $poll->isCurrentlyActive() && ! $userHasVoted && $poll->pollOptions->count() > 0;

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
                        <i class="bi bi-ui-radios-grid"></i>
                        {{ __('تفاصيل الاستطلاع') }} #{{ $poll->id }}
                    </span>
                    <h1 class="poll-title">{{ $poll->question }}</h1>
                    <div class="poll-meta-row">
                        <span class="poll-status-badge {{ $status['class'] }}">
                            <i class="bi {{ $status['icon'] }}"></i>
                            {{ $status['label'] }}
                        </span>
                        <span class="poll-chip"><i class="bi bi-hand-thumbs-up-fill"></i>{{ number_format($totalVotes) }} {{ __('صوت') }}</span>
                        <span class="poll-chip"><i class="bi bi-list-check"></i>{{ number_format($poll->pollOptions->count()) }} {{ __('خيار') }}</span>
                        <span class="poll-chip"><i class="bi bi-people-fill"></i>{{ number_format($targetedUsersCount) }} {{ __('مشارك مستهدف') }}</span>
                    </div>
                </div>

                <div class="poll-hero-actions">
                    <a href="{{ route('polls.edit', $poll) }}" class="poll-btn">
                        <i class="bi bi-pencil-square"></i>
                        {{ __('تعديل') }}
                    </a>
                    <a href="{{ route('polls.results', $poll) }}" class="poll-btn-muted">
                        <i class="bi bi-bar-chart-fill"></i>
                        {{ __('النتائج') }}
                    </a>
                    <a href="{{ route('polls.index') }}" class="poll-btn-muted">
                        <i class="bi bi-arrow-right-circle"></i>
                        {{ __('العودة') }}
                    </a>
                </div>
            </div>
        </section>

        <section class="poll-stats-grid">
            <article class="poll-stat-card" style="animation-delay: 0.05s;">
                <div class="poll-stat-icon"><i class="bi bi-calendar2-range-fill"></i></div>
                <p class="poll-stat-value">{{ max(1, $poll->start_date->diffInDays($poll->end_date)) }}</p>
                <p class="poll-stat-label">{{ __('مدة الاستطلاع بالأيام') }}</p>
            </article>
            <article class="poll-stat-card" style="animation-delay: 0.11s;">
                <div class="poll-stat-icon"><i class="bi bi-bar-chart-steps"></i></div>
                <p class="poll-stat-value">{{ number_format($poll->questions->count()) }}</p>
                <p class="poll-stat-label">{{ __('عدد الأسئلة المرتبطة') }}</p>
            </article>
            <article class="poll-stat-card" style="animation-delay: 0.17s;">
                <div class="poll-stat-icon"><i class="bi bi-person-badge-fill"></i></div>
                <p class="poll-stat-value">{{ number_format($poll->pollAnswers->count()) }}</p>
                <p class="poll-stat-label">{{ __('عدد المشاركات المسجلة') }}</p>
            </article>
            <article class="poll-stat-card" style="animation-delay: 0.23s;">
                <div class="poll-stat-icon"><i class="bi bi-camera-video-fill"></i></div>
                <p class="poll-stat-value">{{ $poll->zoomMeeting ? __('نعم') : __('لا') }}</p>
                <p class="poll-stat-label">{{ __('مرتبط باجتماع زوم') }}</p>
            </article>
        </section>

        <div class="poll-grid">
            <section class="poll-card">
                <div class="poll-card-header">
                    <div class="poll-card-title-wrap">
                        <span class="poll-card-icon"><i class="bi bi-info-circle-fill"></i></span>
                        <div>
                            <h2 class="poll-card-title">{{ __('المعلومات الأساسية') }}</h2>
                            <p class="poll-card-note">{{ __('عرض مختصر لأهم البيانات التشغيلية والزمنية المرتبطة بالاستطلاع.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="poll-detail-grid">
                    <div class="poll-detail-item">
                        <span class="poll-detail-label">{{ __('المنشئ') }}</span>
                        <div class="poll-detail-value">{{ optional($poll->creator)->name ?? __('غير معروف') }}</div>
                    </div>
                    <div class="poll-detail-item">
                        <span class="poll-detail-label">{{ __('تاريخ الإنشاء') }}</span>
                        <div class="poll-detail-value">{{ optional($poll->created_date)->format('Y-m-d H:i') ?? __('غير متوفر') }}</div>
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
                        <span class="poll-detail-label">{{ __('اجتماع الزوم') }}</span>
                        <div class="poll-detail-value">{{ $poll->zoomMeeting?->title ?? __('غير مرتبط') }}</div>
                    </div>
                    <div class="poll-detail-item">
                        <span class="poll-detail-label">{{ __('نوع النظام') }}</span>
                        <div class="poll-detail-value">{{ $hasQuestions ? __('استطلاع متعدد الأسئلة') : __('استطلاع بخيارات مباشرة') }}</div>
                    </div>
                </div>
            </section>

            <section class="poll-card">
                <div class="poll-card-header">
                    <div class="poll-card-title-wrap">
                        <span class="poll-card-icon"><i class="bi bi-people-fill"></i></span>
                        <div>
                            <h2 class="poll-card-title">{{ __('المشاركون المستهدفون') }}</h2>
                            <p class="poll-card-note">{{ __('المستخدمون المخصص لهم هذا الاستطلاع إن تم تحديدهم.') }}</p>
                        </div>
                    </div>
                </div>

                @if($poll->referencedUsers->count() > 0)
                    <div class="poll-users-grid">
                        @foreach($poll->referencedUsers as $user)
                            <article class="poll-user-card">
                                <div class="poll-user-name">{{ $user->name }}</div>
                                <div class="poll-user-meta">{{ $user->email }}</div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="poll-empty-state">
                        <i class="bi bi-person-lines-fill"></i>
                        <h3>{{ __('لا يوجد تخصيص محدد') }}</h3>
                        <p>{{ __('هذا الاستطلاع غير مخصص لمجموعة مستخدمين بعينها حاليًا.') }}</p>
                    </div>
                @endif
            </section>

            <section class="poll-card full-span">
                <div class="poll-card-header">
                    <div class="poll-card-title-wrap">
                        <span class="poll-card-icon"><i class="bi bi-list-ul"></i></span>
                        <div>
                            <h2 class="poll-card-title">{{ $hasQuestions ? __('هيكل الأسئلة') : __('خيارات الاستطلاع') }}</h2>
                            <p class="poll-card-note">{{ $hasQuestions ? __('هذا الاستطلاع يعمل عبر نظام الأسئلة المتعددة، لذلك يظهر ملخص بنية الأسئلة وعدد الخيارات لكل سؤال.') : __('ملخص فوري يوضح أداء كل خيار بناءً على عدد الأصوات والنسبة الحالية.') }}</p>
                        </div>
                    </div>
                </div>

                @if($hasQuestions)
                    <div class="poll-option-grid">
                        @foreach($poll->questions as $question)
                            <article class="poll-option-card">
                                <div class="poll-option-top">
                                    <span class="poll-option-order">{{ $loop->iteration }}</span>
                                    <div>
                                        <h3 class="poll-option-title">{{ $question->question_text }}</h3>
                                        <div class="poll-option-meta">
                                            {{ $question->question_type === 'multiple' ? __('اختيار متعدد') : __('اختيار واحد') }}
                                            - {{ number_format($question->options->count()) }} {{ __('خيار') }}
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @elseif($poll->pollOptions->count() > 0)
                    <div class="poll-progress-list">
                        @foreach($poll->pollOptions as $option)
                            @php
                                $percentage = $totalVotes > 0 ? ($option->votes / $totalVotes) * 100 : 0;
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
                @else
                    <div class="poll-empty-state">
                        <i class="bi bi-ui-checks"></i>
                        <h3>{{ __('لا توجد خيارات') }}</h3>
                        <p>{{ __('لم يتم العثور على خيارات مرتبطة بهذا الاستطلاع حتى الآن.') }}</p>
                    </div>
                @endif
            </section>

            <section class="poll-card full-span">
                <div class="poll-card-header">
                    <div class="poll-card-title-wrap">
                        <span class="poll-card-icon"><i class="bi bi-check2-square"></i></span>
                        <div>
                            <h2 class="poll-card-title">{{ __('منطقة التصويت') }}</h2>
                            <p class="poll-card-note">{{ __('رسائل واضحة للمستخدم حسب حالة الاستطلاع، مع إبراز خيارات التصويت عندما تكون المشاركة متاحة.') }}</p>
                        </div>
                    </div>
                </div>

                @if($hasQuestions)
                    <div class="poll-vote-state">
                        <i class="bi bi-diagram-3-fill"></i>
                        <h3>{{ __('استطلاع متعدد الأسئلة') }}</h3>
                        <p>{{ __('هذا الاستطلاع يستخدم نظام أسئلة متعدد، لذلك تُعرض التفاصيل التحليلية الكاملة من خلال صفحة النتائج.') }}</p>
                        <div style="margin-top: 18px;">
                            <a href="{{ route('polls.results', $poll) }}" class="poll-btn">
                                <i class="bi bi-bar-chart-fill"></i>
                                {{ __('فتح صفحة النتائج') }}
                            </a>
                        </div>
                    </div>
                @elseif($canVote)
                    <form action="{{ route('polls.vote', $poll) }}" method="POST" id="voteForm">
                        @csrf

                        <div class="poll-vote-list">
                            @foreach($poll->pollOptions as $option)
                                <label class="poll-vote-option">
                                    <input type="radio" name="poll_option_id" value="{{ $option->id }}" required>
                                    <span class="poll-vote-option-body">
                                        <span class="poll-vote-option-title">{{ $option->option_text }}</span>
                                        <span class="poll-vote-option-check"><i class="bi bi-check-lg"></i></span>
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        <div class="poll-footer-actions" style="margin-top: 18px;">
                            <button type="submit" class="poll-btn">
                                <i class="bi bi-check2-circle"></i>
                                {{ __('تسجيل التصويت') }}
                            </button>
                        </div>
                    </form>
                @elseif($userHasVoted)
                    <div class="poll-vote-state">
                        <i class="bi bi-patch-check-fill"></i>
                        <h3>{{ __('تم التصويت مسبقًا') }}</h3>
                        <p>{{ __('تم تسجيل مشاركتك في هذا الاستطلاع بالفعل، ويمكنك متابعة النتائج من الزر المخصص أعلاه.') }}</p>
                    </div>
                @elseif(! auth()->check())
                    <div class="poll-vote-state">
                        <i class="bi bi-person-lock"></i>
                        <h3>{{ __('يلزم تسجيل الدخول') }}</h3>
                        <p>{{ __('يجب تسجيل الدخول أولًا حتى تتمكن من المشاركة في التصويت.') }}</p>
                    </div>
                @elseif($poll->end_date < now())
                    <div class="poll-vote-state">
                        <i class="bi bi-hourglass-bottom"></i>
                        <h3>{{ __('انتهى وقت التصويت') }}</h3>
                        <p>{{ __('فترة التصويت لهذا الاستطلاع انتهت، ويمكنك الرجوع إلى صفحة النتائج للاطلاع على المخرجات النهائية.') }}</p>
                    </div>
                @elseif($poll->start_date > now())
                    <div class="poll-vote-state">
                        <i class="bi bi-clock-history"></i>
                        <h3>{{ __('التصويت لم يبدأ بعد') }}</h3>
                        <p>{{ __('هذا الاستطلاع مجدول للبدء في') }} {{ $poll->start_date->format('Y-m-d H:i') }}.</p>
                    </div>
                @else
                    <div class="poll-vote-state">
                        <i class="bi bi-slash-circle"></i>
                        <h3>{{ __('الاستطلاع غير متاح للتصويت') }}</h3>
                        <p>{{ __('تحقق من حالة الاستطلاع أو من وجود خيارات تصويت صالحة قبل إعادة المحاولة.') }}</p>
                    </div>
                @endif
            </section>

            @if($poll->pollAnswers->count() > 0)
                <section class="poll-card full-span">
                    <div class="poll-card-header">
                        <div class="poll-card-title-wrap">
                            <span class="poll-card-icon"><i class="bi bi-people-fill"></i></span>
                            <div>
                                <h2 class="poll-card-title">{{ __('آخر المشاركات') }}</h2>
                                <p class="poll-card-note">{{ __('عرض سريع لأحدث المشاركين في الاستطلاع وتوقيت التصويت الخاص بهم.') }}</p>
                            </div>
                        </div>
                    </div>

                    <table class="poll-attendees-table">
                        <thead>
                            <tr>
                                <th>{{ __('المشارك') }}</th>
                                <th>{{ __('الخيار') }}</th>
                                <th>{{ __('وقت التصويت') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($poll->pollAnswers->sortByDesc('answer_date')->take(8) as $answer)
                                <tr>
                                    <td>{{ optional($answer->user)->name ?? __('غير معروف') }}</td>
                                    <td>{{ optional($answer->pollOption)->option_text ?? __('غير محدد') }}</td>
                                    <td>{{ optional($answer->answer_date)->format('Y-m-d H:i') ?? __('غير متوفر') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const voteForm = document.getElementById('voteForm');
        if (!voteForm) {
            return;
        }

        voteForm.addEventListener('submit', function (event) {
            const selectedOption = voteForm.querySelector('input[name="poll_option_id"]:checked');
            if (!selectedOption) {
                event.preventDefault();
                window.alert('{{ __('يرجى اختيار خيار قبل تسجيل التصويت.') }}');
                return;
            }

            if (!window.confirm('{{ __('هل أنت متأكد من تسجيل هذا التصويت؟ لا يمكن تعديل التصويت بعد الإرسال.') }}')) {
                event.preventDefault();
            }
        });
    });
</script>
@endpush
