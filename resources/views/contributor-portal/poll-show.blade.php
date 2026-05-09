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
    </section>
</div>
@endsection
