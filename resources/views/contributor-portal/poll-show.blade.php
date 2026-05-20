@extends('layouts.app')
@section('title', __('إجابة الاستطلاع'))
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ $poll->question }}</h1>
            <p class="cp-subtitle">{{ __('اختر إجابة واحدة ثم أرسل التصويت. لا يمكن تعديل الإجابة بعد الإرسال.') }}</p>
        </div>
        <span class="cp-badge">{{ $poll->isCurrentlyActive() ? __('نشط') : __('غير نشط') }}</span>
    </section>

    <section class="cp-card">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

        @if($canVote)
            <form method="POST" action="{{ route('contributor.polls.vote', $poll) }}">
                @csrf
                <div class="cp-grid-2">
                    @foreach($poll->pollOptions as $option)
                        <label class="cp-card" style="cursor:pointer; box-shadow:none;">
                            <input type="radio" name="poll_option_id" value="{{ $option->id }}" required>
                            <strong>{{ $option->option_text }}</strong>
                        </label>
                    @endforeach
                </div>
                @error('poll_option_id')<span class="cp-error">{{ $message }}</span>@enderror
                <div class="cp-actions" style="margin-top:1rem;">
                    <button class="cp-btn cp-btn-primary" type="submit"><i class="bi bi-check2-circle"></i>{{ __('إرسال الإجابة') }}</button>
                    <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.polls') }}">{{ __('رجوع') }}</a>
                </div>
            </form>
        @elseif($userHasVoted)
            <div class="cp-empty">
                <i class="bi bi-patch-check-fill"></i>
                <p>{{ __('تم تسجيل إجابتك على هذا الاستطلاع مسبقا.') }}</p>
                <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.polls') }}">{{ __('رجوع') }}</a>
            </div>
        @else
            <div class="cp-empty">
                <i class="bi bi-info-circle-fill"></i>
                <p>{{ __('التصويت غير متاح حاليا لهذا الاستطلاع.') }}</p>
                <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.polls') }}">{{ __('رجوع') }}</a>
            </div>
        @endif

        @php
            $totalVotes = $poll->pollOptions->sum('votes');
        @endphp

        <div style="margin-top: 1.5rem;">
            <h2 class="cp-section-title">{{ __('نتائج الاستطلاع') }}</h2>

            @if($poll->pollOptions->isNotEmpty())
                <div class="cp-grid-2">
                    @foreach($poll->pollOptions as $option)
                        @php
                            $percentage = $totalVotes > 0 ? ($option->votes / $totalVotes) * 100 : 0;
                        @endphp
                        <article class="cp-card" style="box-shadow:none;">
                            <strong>{{ $option->option_text }}</strong>
                            <div style="display:flex; justify-content:space-between; gap:1rem; margin-top:.6rem;">
                                <span>{{ number_format($option->votes) }} {{ __('صوت') }}</span>
                                <span>{{ number_format($percentage, 1) }}%</span>
                            </div>
                            <div style="height:10px; border-radius:999px; background:rgba(170,134,63,.14); overflow:hidden; margin-top:.6rem;">
                                <div style="height:100%; width:{{ $percentage }}%; background:var(--primary-color); border-radius:inherit;"></div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="cp-empty">
                    <i class="bi bi-bar-chart"></i>
                    <p>{{ __('لا توجد نتائج متاحة لهذا الاستطلاع حتى الآن.') }}</p>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
