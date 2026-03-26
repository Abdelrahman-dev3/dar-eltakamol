@extends('layouts.app')

@section('title', __('خيارات الاستطلاعات'))

@include('polls.partials.ui-styles')

@php
    $optionsCollection = $pollOptions->getCollection();
    $totalOptions = $pollOptions->total();
    $pageOptionsCount = $optionsCollection->count();
    $pageVotesTotal = $optionsCollection->sum('votes');
    $linkedPollsCount = $optionsCollection->pluck('poll_id')->filter()->unique()->count();
    $averageVotes = $pageOptionsCount > 0 ? $pageVotesTotal / $pageOptionsCount : 0;
@endphp

@section('content')
<div class="poll-page">
    <div class="poll-shell">
        <section class="poll-hero">
            <div class="poll-hero-inner">
                <div>
                    <span class="poll-badge">
                        <i class="bi bi-list-check"></i>
                        {{ __('إدارة خيارات الاستطلاعات') }}
                    </span>
                    <h1 class="poll-title">{{ __('خيارات الاستطلاعات') }}</h1>
                </div>

                <div class="poll-hero-actions">
                    <a href="{{ route('poll-options.create') }}" class="poll-btn">
                        <i class="bi bi-plus-circle-fill"></i>
                        {{ __('إضافة خيار جديد') }}
                    </a>
                    <a href="{{ route('polls.index') }}" class="poll-btn-muted">
                        <i class="bi bi-ui-radios-grid"></i>
                        {{ __('الانتقال للاستطلاعات') }}
                    </a>
                </div>
            </div>
        </section>

        <section class="poll-stats-grid">
            <article class="poll-stat-card" style="animation-delay: 0.05s;">
                <div class="poll-stat-icon"><i class="bi bi-collection-fill"></i></div>
                <p class="poll-stat-value">{{ number_format($totalOptions) }}</p>
                <p class="poll-stat-label">{{ __('إجمالي الخيارات') }}</p>
            </article>
            <article class="poll-stat-card" style="animation-delay: 0.11s;">
                <div class="poll-stat-icon"><i class="bi bi-hand-thumbs-up-fill"></i></div>
                <p class="poll-stat-value">{{ number_format($pageVotesTotal) }}</p>
                <p class="poll-stat-label">{{ __('إجمالي أصوات الصفحة') }}</p>
            </article>
            <article class="poll-stat-card" style="animation-delay: 0.17s;">
                <div class="poll-stat-icon"><i class="bi bi-diagram-3-fill"></i></div>
                <p class="poll-stat-value">{{ number_format($linkedPollsCount) }}</p>
                <p class="poll-stat-label">{{ __('استطلاعات مرتبطة') }}</p>
            </article>
            <article class="poll-stat-card" style="animation-delay: 0.23s;">
                <div class="poll-stat-icon"><i class="bi bi-bar-chart-steps"></i></div>
                <p class="poll-stat-value">{{ number_format($averageVotes, 1) }}</p>
                <p class="poll-stat-label">{{ __('متوسط الأصوات') }}</p>
            </article>
        </section>

        <section class="poll-list-shell">
            <div class="poll-toolbar">
                <div class="poll-search">
                    <i class="bi bi-search"></i>
                    <input type="search" id="pollOptionsSearch" placeholder="{{ __('ابحث في نص الخيار أو الاستطلاع أو اسم المنشئ...') }}" autocomplete="off">
                </div>

                <div class="poll-toolbar-meta">
                    <span class="poll-meta-pill">
                        <i class="bi bi-eye-fill"></i>
                        <span id="visibleOptionsCount">{{ $pageOptionsCount }}</span>
                        {{ __('نتيجة ظاهرة') }}
                    </span>
                    <span class="poll-meta-pill">
                        <i class="bi bi-files"></i>
                        {{ __('صفحة') }} {{ $pollOptions->currentPage() }} / {{ $pollOptions->lastPage() }}
                    </span>
                </div>
            </div>

            @if($pageOptionsCount > 0)
                <div class="poll-list-body" id="pollOptionsList">
                    @foreach($pollOptions as $pollOption)
                        @php
                            $poll = $pollOption->poll;
                            $searchableText = implode(' ', [
                                $pollOption->option_text,
                                $poll?->question,
                                $poll?->creator?->name,
                            ]);
                        @endphp

                        <article class="poll-list-row poll-option-search-item" data-search="{{ mb_strtolower($searchableText) }}" style="animation-delay: {{ 0.05 + ($loop->index * 0.04) }}s;">
                            <div>
                                <h3 class="poll-main-title">{{ $pollOption->option_text }}</h3>
                                <div class="poll-main-subtitle">
                                    {{ __('الاستطلاع') }}:
                                    {{ $poll ? \Illuminate\Support\Str::limit($poll->question, 90) : __('غير مرتبط') }}
                                </div>
                                <div class="poll-meta-row">
                                    <span class="poll-chip"><i class="bi bi-hash"></i>#{{ $pollOption->id }}</span>
                                    <span class="poll-chip"><i class="bi bi-hand-thumbs-up-fill"></i>{{ number_format($pollOption->votes) }} {{ __('صوت') }}</span>
                                </div>
                            </div>

                            <div class="poll-table-field">
                                <span class="poll-table-label">{{ __('المنشئ') }}</span>
                                <span class="poll-table-value">{{ $poll?->creator?->name ?? __('غير معروف') }}</span>
                            </div>

                            <div class="poll-table-field">
                                <span class="poll-table-label">{{ __('تاريخ الإنشاء') }}</span>
                                <span class="poll-table-value">{{ optional($pollOption->created_at)->format('Y-m-d H:i') ?? __('غير متوفر') }}</span>
                            </div>

                            <div class="poll-table-field">
                                <span class="poll-table-label">{{ __('الاستطلاع المرتبط') }}</span>
                                <span class="poll-table-value">{{ $poll ? '#' . $poll->id : __('غير متوفر') }}</span>
                            </div>

                            <div class="poll-table-actions">
                                <a href="{{ route('poll-options.show', $pollOption) }}" class="poll-icon-btn" title="{{ __('عرض') }}"><i class="bi bi-eye-fill"></i></a>
                                <a href="{{ route('poll-options.edit', $pollOption) }}" class="poll-icon-btn" title="{{ __('تعديل') }}"><i class="bi bi-pencil-square"></i></a>
                                @if($poll)
                                    <a href="{{ route('polls.show', $poll) }}" class="poll-icon-btn" title="{{ __('عرض الاستطلاع') }}"><i class="bi bi-box-arrow-up-left"></i></a>
                                @endif
                                <form action="{{ route('poll-options.destroy', $pollOption) }}" method="POST" style="display: inline-flex;" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا الخيار؟') }}');">
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

                <div class="poll-empty-state" id="pollOptionsEmptyState" style="display: none; margin: 12px;">
                    <i class="bi bi-search-heart"></i>
                    <h3>{{ __('لا توجد نتائج مطابقة') }}</h3>
                    <p>{{ __('جرّب تعديل البحث باستخدام نص الخيار أو جزء من سؤال الاستطلاع أو اسم المنشئ.') }}</p>
                </div>

                <div style="padding: 10px 22px 22px;" id="pollOptionsPagination">
                    {{ $pollOptions->links() }}
                </div>
            @else
                <div class="poll-empty-state" style="margin: 12px;">
                    <i class="bi bi-ui-radios-grid"></i>
                    <h3>{{ __('لا توجد خيارات بعد') }}</h3>
                    <p>{{ __('ابدأ بإضافة أول خيار تصويت ليظهر هنا ضمن قائمة منظمة وحديثة.') }}</p>
                    <div style="margin-top: 18px;">
                        <a href="{{ route('poll-options.create') }}" class="poll-btn">
                            <i class="bi bi-plus-circle-fill"></i>
                            {{ __('إضافة خيار جديد') }}
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
        const searchInput = document.getElementById('pollOptionsSearch');
        const rows = Array.from(document.querySelectorAll('.poll-option-search-item'));
        const visibleCount = document.getElementById('visibleOptionsCount');
        const emptyState = document.getElementById('pollOptionsEmptyState');
        const pagination = document.getElementById('pollOptionsPagination');

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
