@extends('layouts.app')
@section('title', __('اجتماعات اللجان'))
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ __('اجتماعات اللجان') }}</h1>
            <p class="cp-subtitle">{{ __('الاجتماعات التي تمت دعوتك إليها بصفتك عضوا في إحدى اللجان.') }}</p>
        </div>
        <div class="cp-actions">
            <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.committees.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i>{{ __('داشبورد اللجان') }}</a>
        </div>
    </section>

    <section class="cp-card">
        <div class="cp-table-wrap">
            <table class="cp-table">
                <thead>
                    <tr><th>{{ __('الاجتماع') }}</th><th>{{ __('التاريخ') }}</th><th>{{ __('المرفقات') }}</th><th>{{ __('الرابط') }}</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($meetings as $meeting)
                        <tr>
                            <td><a href="{{ route('contributor.committees.meetings.show', $meeting) }}">{{ $meeting->name }}</a></td>
                            <td>{{ $meeting->date?->format('Y-m-d H:i') }}</td>
                            <td><span class="cp-badge">{{ number_format($meeting->attachments_count) }} {{ __('مرفق') }}</span></td>
                            <td>
                                @if($meeting->url)
                                    <a class="cp-btn cp-btn-secondary" href="{{ $meeting->url }}" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-left"></i>{{ __('فتح') }}</a>
                                @else
                                    <span class="cp-badge">{{ __('غير متاح') }}</span>
                                @endif
                            </td>
                            <td><a class="cp-btn cp-btn-primary" href="{{ route('contributor.committees.meetings.show', $meeting) }}"><i class="bi bi-eye-fill"></i>{{ __('تفاصيل') }}</a></td>
                        </tr>
                    @empty
                        <tr><td class="cp-empty" colspan="5">{{ __('لا توجد اجتماعات لجان مدعو إليها حاليا') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $meetings->links() }}
    </section>
</div>
@endsection
