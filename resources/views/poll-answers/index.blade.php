@extends('layouts.app')

@section('title', __('إجابات الاستطلاعات'))

@include('polls.partials.ui-styles')

@php
    $answersCollection = $pollAnswers->getCollection();
    $totalAnswers = $pollAnswers->total();
    $pageAnswersCount = $answersCollection->count();
    $uniqueUsersCount = $answersCollection->pluck('user_id')->filter()->unique()->count();
    $uniquePollsCount = $answersCollection->pluck('poll_id')->filter()->unique()->count();
    $averageAnswers = $uniqueUsersCount > 0 ? $pageAnswersCount / $uniqueUsersCount : 0;
@endphp

@section('content')
<div class="poll-page">
    <div class="poll-shell">
        <section class="poll-hero">
            <div class="poll-hero-inner">
                <div>
                    <span class="poll-badge">
                        <i class="bi bi-check2-square"></i>
                        {{ __('إدارة إجابات الاستطلاعات') }}
                    </span>
                    <h1 class="poll-title">{{ __('إجابات الاستطلاعات') }}</h1>
                </div>

                <div class="poll-hero-actions">
                    <a href="{{ route('poll-answers.create') }}" class="poll-btn">
                        <i class="bi bi-plus-circle-fill"></i>
                        {{ __('إضافة إجابة جديدة') }}
                    </a>
                    <a href="{{ route('polls.index') }}" class="poll-btn-muted">
                        <i class="bi bi-ui-radios-grid"></i>
                        {{ __('العودة للاستطلاعات') }}
                    </a>
                </div>
            </div>
        </section>

        <section class="poll-stats-grid">
            <article class="poll-stat-card" style="animation-delay: 0.05s;">
                <div class="poll-stat-icon"><i class="bi bi-collection-fill"></i></div>
                <p class="poll-stat-value">{{ number_format($totalAnswers) }}</p>
                <p class="poll-stat-label">{{ __('إجمالي الإجابات') }}</p>
            </article>
            <article class="poll-stat-card" style="animation-delay: 0.11s;">
                <div class="poll-stat-icon"><i class="bi bi-people-fill"></i></div>
                <p class="poll-stat-value">{{ number_format($uniqueUsersCount) }}</p>
                <p class="poll-stat-label">{{ __('مستخدمون في الصفحة الحالية') }}</p>
            </article>
            <article class="poll-stat-card" style="animation-delay: 0.17s;">
                <div class="poll-stat-icon"><i class="bi bi-diagram-3-fill"></i></div>
                <p class="poll-stat-value">{{ number_format($uniquePollsCount) }}</p>
                <p class="poll-stat-label">{{ __('استطلاعات ممثلة') }}</p>
            </article>
            <article class="poll-stat-card" style="animation-delay: 0.23s;">
                <div class="poll-stat-icon"><i class="bi bi-bar-chart-steps"></i></div>
                <p class="poll-stat-value">{{ number_format($averageAnswers, 1) }}</p>
                <p class="poll-stat-label">{{ __('متوسط الإجابات لكل مستخدم') }}</p>
            </article>
        </section>

        <section class="poll-list-shell">
            <div class="poll-toolbar">
                <div class="poll-search">
                    <i class="bi bi-search"></i>
                    <input type="search" id="pollAnswersSearch" placeholder="{{ __('ابحث في المستخدم أو الاستطلاع أو الخيار...') }}" autocomplete="off">
                </div>

                <div class="poll-toolbar-meta">
                    <span class="poll-meta-pill">
                        <i class="bi bi-eye-fill"></i>
                        <span id="visibleAnswersCount">{{ $pageAnswersCount }}</span>
                        {{ __('نتيجة ظاهرة') }}
                    </span>
                    <span class="poll-meta-pill">
                        <i class="bi bi-files"></i>
                        {{ __('صفحة') }} {{ $pollAnswers->currentPage() }} / {{ $pollAnswers->lastPage() }}
                    </span>
                </div>
            </div>

            @if($pageAnswersCount > 0)
                <div class="poll-list-body" id="pollAnswersList">
                    @foreach($pollAnswers as $pollAnswer)
                        @php
                            $searchableText = implode(' ', [
                                $pollAnswer->user?->name,
                                $pollAnswer->user?->email,
                                $pollAnswer->poll?->question,
                                $pollAnswer->pollOption?->option_text,
                            ]);
                        @endphp

                        <article class="poll-list-row poll-answer-search-item" data-search="{{ mb_strtolower($searchableText) }}" style="animation-delay: {{ 0.05 + ($loop->index * 0.04) }}s;">
                            <div>
                                <h3 class="poll-main-title">{{ $pollAnswer->user?->name ?? __('غير معروف') }}</h3>
                                <div class="poll-main-subtitle">{{ $pollAnswer->user?->email ?? __('بدون بريد إلكتروني ظاهر') }}</div>
                                <div class="poll-meta-row">
                                    <span class="poll-chip"><i class="bi bi-hash"></i>#{{ $pollAnswer->id }}</span>
                                    <span class="poll-chip"><i class="bi bi-check2-circle"></i>{{ $pollAnswer->pollOption?->option_text ?? __('غير محدد') }}</span>
                                </div>
                            </div>

                            <div class="poll-table-field">
                                <span class="poll-table-label">{{ __('الاستطلاع') }}</span>
                                <span class="poll-table-value">{{ $pollAnswer->poll ? \Illuminate\Support\Str::limit($pollAnswer->poll->question, 90) : __('غير متوفر') }}</span>
                            </div>

                            <div class="poll-table-field">
                                <span class="poll-table-label">{{ __('الخيار') }}</span>
                                <span class="poll-table-value">{{ $pollAnswer->pollOption?->option_text ?? __('غير محدد') }}</span>
                            </div>

                            <div class="poll-table-field">
                                <span class="poll-table-label">{{ __('تاريخ الإجابة') }}</span>
                                <span class="poll-table-value">{{ optional($pollAnswer->answer_date)->format('Y-m-d H:i') ?? __('غير متوفر') }}</span>
                            </div>

                            <div class="poll-table-actions">
                                <a href="{{ route('poll-answers.show', $pollAnswer) }}" class="poll-icon-btn" title="{{ __('عرض') }}"><i class="bi bi-eye-fill"></i></a>
                                <a href="{{ route('poll-answers.edit', $pollAnswer) }}" class="poll-icon-btn" title="{{ __('تعديل') }}"><i class="bi bi-pencil-square"></i></a>
                                <form action="{{ route('poll-answers.destroy', $pollAnswer) }}" method="POST" style="display: inline-flex;" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذه الإجابة؟') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="poll-icon-btn" title="{{ __('حذف') }}" style="color: var(--danger-color);">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="poll-empty-state" id="pollAnswersEmptyState" style="display: none; margin: 12px;">
                    <i class="bi bi-search-heart"></i>
                    <h3>{{ __('لا توجد نتائج مطابقة') }}</h3>
                    <p>{{ __('جرّب البحث باسم المستخدم أو نص الخيار أو جزء من سؤال الاستطلاع.') }}</p>
                </div>

                <div style="padding: 10px 22px 22px;" id="pollAnswersPagination">
                    {{ $pollAnswers->links() }}
                </div>
            @else
                <div class="poll-empty-state" style="margin: 12px;">
                    <i class="bi bi-check2-square"></i>
                    <h3>{{ __('لا توجد إجابات بعد') }}</h3>
                    <p>{{ __('ابدأ بإضافة أول إجابة يدويًا أو انتظر تسجيل التصويتات لتظهر هنا ضمن قائمة منظمة.') }}</p>
                    <div style="margin-top: 18px;">
                        <a href="{{ route('poll-answers.create') }}" class="poll-btn">
                            <i class="bi bi-plus-circle-fill"></i>
                            {{ __('إضافة إجابة جديدة') }}
                        </a>
                    </div>
                </div>
            @endif
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('pollAnswersSearch');
        const rows = Array.from(document.querySelectorAll('.poll-answer-search-item'));
        const visibleCount = document.getElementById('visibleAnswersCount');
        const emptyState = document.getElementById('pollAnswersEmptyState');
        const pagination = document.getElementById('pollAnswersPagination');

        if (!searchInput || rows.length === 0) {
            return;
        }

        const normalize = function (value) {
            return (value || '').toString().toLowerCase().trim();
        };

        const filterRows = function () {
            const query = normalize(searchInput.value);
            let shown = 0;

            rows.forEach(function (row) {
                const haystack = normalize(row.getAttribute('data-search'));
                const matched = query === '' || haystack.indexOf(query) !== -1;
                row.classList.toggle('is-hidden', !matched);

                if (matched) {
                    shown += 1;
                }
            });

            if (visibleCount) {
                visibleCount.textContent = shown;
            }

            if (emptyState) {
                emptyState.style.display = shown === 0 ? 'block' : 'none';
            }

            if (pagination) {
                pagination.style.display = shown === 0 ? 'none' : '';
            }
        };

        searchInput.addEventListener('input', filterRows);
        filterRows();
    });
</script>
@endpush
