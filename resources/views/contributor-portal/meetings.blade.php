@extends('layouts.app')
@section('title', __('الاجتماعات'))
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero"><div><h1 class="cp-title">{{ __('الاجتماعات') }}</h1><p class="cp-subtitle">{{ __('الاجتماعات التي تمت دعوتك إليها من قبل الإدارة.') }}</p></div></section>
    <section class="cp-card">
        <div class="cp-table-wrap"><table class="cp-table"><thead><tr><th>{{ __('الاجتماع') }}</th><th>{{ __('التاريخ') }}</th><th>{{ __('الرابط') }}</th></tr></thead><tbody>
            @forelse($meetings as $meeting)
                <tr><td>{{ $meeting->name }}</td><td>{{ $meeting->date?->format('Y-m-d H:i') }}</td><td>@if($meeting->url)<a class="cp-btn cp-btn-secondary" href="{{ $meeting->url }}" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-left"></i>{{ __('فتح') }}</a>@else<span class="cp-badge">{{ __('غير متاح') }}</span>@endif</td></tr>
            @empty
                <tr><td class="cp-empty" colspan="3">{{ __('لا توجد اجتماعات مدعو إليها حاليا') }}</td></tr>
            @endforelse
        </tbody></table></div>
        {{ $meetings->links() }}
    </section>
</div>
@endsection
