@extends('layouts.app')
@section('title', __('أعضاء اللجان'))
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ __('أعضاء اللجان') }}</h1>
            <p class="cp-subtitle">{{ __('الأعضاء الذين يشاركونك عضوية اللجان المسجلة في حسابك.') }}</p>
        </div>
        <div class="cp-actions">
            <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.committees.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i>{{ __('داشبورد اللجان') }}</a>
        </div>
    </section>

    <section class="cp-card">
        <div class="cp-section-head">
            <h2 class="cp-card-title"><i class="bi bi-diagram-3-fill"></i>{{ __('لجانك') }}</h2>
            <div class="cp-news-meta">
                @foreach($committeeMemberships as $membership)
                    <span><i class="bi bi-patch-check-fill"></i>{{ __($membership) }}</span>
                @endforeach
            </div>
        </div>

        <div class="cp-member-grid">
            @forelse($members as $member)
                @php
                    $sharedMemberships = collect($member->committee_memberships ?? [])
                        ->intersect($committeeMemberships)
                        ->values();
                @endphp
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
                            <div class="cp-member-sub">{{ $member->position ?: __('عضو لجنة') }}</div>
                        </div>
                    </div>

                    <div class="cp-news-meta">
                        @if($member->phone_num)
                            <span><i class="bi bi-telephone-fill"></i>{{ $member->phone_num }}</span>
                        @endif
                        @if($member->user?->email)
                            <span><i class="bi bi-envelope-fill"></i>{{ $member->user->email }}</span>
                        @endif
                        @foreach($sharedMemberships as $membership)
                            <span><i class="bi bi-patch-check-fill"></i>{{ __($membership) }}</span>
                        @endforeach
                    </div>
                </article>
            @empty
                <div class="cp-empty">{{ __('لا يوجد أعضاء لجان مشتركة حاليا') }}</div>
            @endforelse
        </div>

        {{ $members->links() }}
    </section>
</div>
@endsection
