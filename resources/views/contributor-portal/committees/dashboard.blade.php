@extends('layouts.app')
@section('title', __('داشبورد اللجان'))
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ __('بوابة اللجان') }}</h1>
            <p class="cp-subtitle">{{ __('ملخص خاص بعضويتك في اللجان والاجتماعات والاستطلاعات المرتبطة بك.') }}</p>
        </div>
        <div class="cp-actions">
            <a class="cp-btn cp-btn-primary" href="{{ route('contributor.committees.meetings') }}"><i class="bi bi-camera-video-fill"></i>{{ __('اجتماعات اللجان') }}</a>
            <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.committees.polls') }}"><i class="bi bi-ui-checks"></i>{{ __('استطلاعات اللجان') }}</a>
            <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.committees.members') }}"><i class="bi bi-people-fill"></i>{{ __('أعضاء اللجان') }}</a>
        </div>
    </section>

    <section class="cp-grid">
        <article class="cp-stat"><i class="bi bi-diagram-3-fill"></i><p class="cp-stat-value">{{ number_format($committeeMemberships->count()) }}</p><p class="cp-stat-label">{{ __('عضوياتك في اللجان') }}</p></article>
        <article class="cp-stat"><i class="bi bi-people-fill"></i><p class="cp-stat-value">{{ number_format($committeeMembersCount) }}</p><p class="cp-stat-label">{{ __('أعضاء اللجان المشتركة') }}</p></article>
        <article class="cp-stat"><i class="bi bi-camera-video-fill"></i><p class="cp-stat-value">{{ number_format($meetingsCount) }}</p><p class="cp-stat-label">{{ __('اجتماعات مدعو إليها') }}</p></article>
        <article class="cp-stat"><i class="bi bi-hourglass-split"></i><p class="cp-stat-value">{{ number_format($pendingPollsCount) }}</p><p class="cp-stat-label">{{ __('استطلاعات بانتظارك') }}</p></article>
    </section>

    <section class="cp-grid-2">
        <article class="cp-card">
            <h2 class="cp-card-title"><i class="bi bi-person-badge-fill"></i>{{ __('عضوياتك') }}</h2>
            <div class="cp-news-meta">
                <span><i class="bi bi-person-fill"></i>{{ $contributor->name }}</span>
                @foreach($committeeMemberships as $membership)
                    <span><i class="bi bi-patch-check-fill"></i>{{ __($membership) }}</span>
                @endforeach
            </div>
        </article>

        <article class="cp-card">
            <h2 class="cp-card-title"><i class="bi bi-calendar-event-fill"></i>{{ __('الاجتماع القادم') }}</h2>
            @if($nextMeeting)
                <div class="cp-news-item">
                    <div class="cp-news-icon"><i class="bi bi-camera-video-fill"></i></div>
                    <div class="cp-news-body">
                        <h3 class="cp-news-title">{{ $nextMeeting->name }}</h3>
                        <div class="cp-news-meta"><span><i class="bi bi-clock"></i>{{ $nextMeeting->date?->format('Y-m-d H:i') }}</span></div>
                    </div>
                    <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.committees.meetings.show', $nextMeeting) }}">{{ __('تفاصيل') }}</a>
                </div>
            @else
                <div class="cp-empty">{{ __('لا يوجد اجتماع قادم حاليا') }}</div>
            @endif
        </article>
    </section>

    <section class="cp-card">
        <div class="cp-section-head">
            <h2 class="cp-card-title"><i class="bi bi-calendar2-week-fill"></i>{{ __('آخر اجتماعات اللجان') }}</h2>
            <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.committees.meetings') }}">{{ __('عرض الكل') }}</a>
        </div>
        <div class="cp-news-list compact">
            @forelse($latestMeetings as $meeting)
                <article class="cp-news-item">
                    <div class="cp-news-icon"><i class="bi bi-camera-video-fill"></i></div>
                    <div class="cp-news-body">
                        <h3 class="cp-news-title">{{ $meeting->name }}</h3>
                        <div class="cp-news-meta">
                            <span><i class="bi bi-calendar3"></i>{{ $meeting->date?->format('Y-m-d H:i') }}</span>
                            <span><i class="bi bi-paperclip"></i>{{ number_format($meeting->attachments_count) }} {{ __('مرفق') }}</span>
                        </div>
                    </div>
                    <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.committees.meetings.show', $meeting) }}">{{ __('تفاصيل') }}</a>
                </article>
            @empty
                <div class="cp-empty">{{ __('لا توجد اجتماعات لجان مرتبطة بحسابك') }}</div>
            @endforelse
        </div>
    </section>

    <section class="cp-card">
        <div class="cp-section-head">
            <h2 class="cp-card-title"><i class="bi bi-ui-checks"></i>{{ __('آخر استطلاعات اللجان') }}</h2>
            <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.committees.polls') }}">{{ __('عرض الكل') }}</a>
        </div>
        <div class="cp-news-list compact">
            @forelse($latestPolls as $poll)
                @php $answered = $poll->pollAnswers->isNotEmpty(); @endphp
                <article class="cp-news-item">
                    <div class="cp-news-icon"><i class="bi bi-card-checklist"></i></div>
                    <div class="cp-news-body">
                        <h3 class="cp-news-title">{{ $poll->question }}</h3>
                        <div class="cp-news-meta">
                            <span><i class="bi bi-calendar3"></i>{{ $poll->start_date?->format('Y-m-d H:i') }} - {{ $poll->end_date?->format('Y-m-d H:i') }}</span>
                            <span><i class="bi bi-check2-circle"></i>{{ $answered ? __('تمت الإجابة') : __('بانتظار الإجابة') }}</span>
                        </div>
                    </div>
                    <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.polls.show', $poll) }}">{{ $answered ? __('عرض') : __('إجابة') }}</a>
                </article>
            @empty
                <div class="cp-empty">{{ __('لا توجد استطلاعات لجان مرتبطة بحسابك') }}</div>
            @endforelse
        </div>
    </section>
</div>
@endsection
