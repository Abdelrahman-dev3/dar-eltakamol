@extends('layouts.app')
@section('title', __('استطلاعات المجلس'))
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ __('استطلاعات المجلس') }}</h1>
            <p class="cp-subtitle">{{ __('الاستطلاعات الموجهة لك بصفتك عضوا في مجلس الإدارة.') }}</p>
        </div>
        <div class="cp-actions">
            <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.board.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i>{{ __('داشبورد المجلس') }}</a>
        </div>
    </section>

    <section class="cp-card">
        <div class="cp-table-wrap">
            <table class="cp-table">
                <thead>
                    <tr><th>{{ __('العنوان') }}</th><th>{{ __('السؤال') }}</th><th>{{ __('بداية') }}</th><th>{{ __('نهاية') }}</th><th>{{ __('الحالة') }}</th><th>{{ __('الإجابة') }}</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($polls as $poll)
                        @php $answered = $poll->pollAnswers->isNotEmpty(); @endphp
                        <tr>
                            <td>{{ $poll->title }}</td>
                            <td>{{ $poll->question }}</td>
                            <td>{{ $poll->start_date?->format('Y-m-d H:i') }}</td>
                            <td>{{ $poll->end_date?->format('Y-m-d H:i') }}</td>
                            <td><span class="cp-badge">{{ $poll->isCurrentlyActive() ? __('نشط') : __('غير نشط') }}</span></td>
                            <td><span class="cp-badge">{{ $answered ? __('تمت الإجابة') : __('لم تتم الإجابة') }}</span></td>
                            <td><a class="cp-btn cp-btn-secondary" href="{{ route('contributor.polls.show', $poll) }}"><i class="bi bi-ui-checks"></i>{{ $answered ? __('عرض') : __('إجابة') }}</a></td>
                        </tr>
                    @empty
                        <tr><td class="cp-empty" colspan="7">{{ __('لا توجد استطلاعات مجلس موجهة لك حاليا') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $polls->links() }}
    </section>
</div>
@endsection
