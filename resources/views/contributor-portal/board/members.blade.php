@extends('layouts.app')
@section('title', __('أعضاء مجلس الإدارة'))
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ __('أعضاء مجلس الإدارة') }}</h1>
            <p class="cp-subtitle">{{ __('قائمة أعضاء المجلس وبيانات التواصل والعضويات الإدارية المسجلة لهم.') }}</p>
        </div>
        <div class="cp-actions">
            <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.board.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i>{{ __('داشبورد المجلس') }}</a>
        </div>
    </section>

    <section class="cp-card">
        <div class="cp-member-grid">
            @forelse($members as $member)
                <article class="cp-member-card">
                    <div class="cp-member-head">
                        <div class="cp-avatar">
                            @if($member->profile_picture)
                                <img src="{{ $member->profile_picture_url }}" alt="{{ $member->name }}">
                            @else
                                {{ $member->initials }}
                            @endif
                        </div>
                        <div>
                            <h2 class="cp-member-name">{{ $member->name }}</h2>
                            <div class="cp-member-sub">{{ $member->position ?: __('عضو مجلس إدارة') }}</div>
                        </div>
                    </div>

                    <div class="cp-news-meta">
                        @if($member->phone_num)
                            <span><i class="bi bi-telephone-fill"></i>{{ $member->phone_num }}</span>
                        @endif
                        @if($member->user?->email)
                            <span><i class="bi bi-envelope-fill"></i>{{ $member->user->email }}</span>
                        @endif
                        @foreach($member->membership_labels as $label)
                            <span><i class="bi bi-patch-check-fill"></i>{{ __($label) }}</span>
                        @endforeach
                    </div>
                </article>
            @empty
                <div class="cp-empty">{{ __('لا يوجد أعضاء مجلس إدارة مسجلون حاليا') }}</div>
            @endforelse
        </div>

        {{ $members->links() }}
    </section>
</div>
@endsection
