@extends('layouts.app')

@section('title', __('الاستطلاعات'))

@include('polls.partials.ui-styles')

@php
    $pollsCollection = $polls->getCollection();
    $totalPolls = $polls->total();
    $pagePollsCount = $pollsCollection->count();
    $activePollsCount = $pollsCollection->filter(fn ($poll) => $poll->isCurrentlyActive())->count();
    $upcomingPollsCount = $pollsCollection->filter(fn ($poll) => $poll->is_active && $poll->start_date > now())->count();
    $pageVotesTotal = $pollsCollection->sum(fn ($poll) => $poll->pollOptions->sum('votes'));

    $resolveStatus = function ($poll) {
        if (! $poll->is_active) {
            return ['label' => __('متوقف'), 'class' => 'inactive', 'icon' => 'bi-pause-circle'];
        }

        if ($poll->end_date < now()) {
            return ['label' => __('منتهي'), 'class' => 'ended', 'icon' => 'bi-check2-circle'];
        }

        if ($poll->start_date > now()) {
            return ['label' => __('قادم'), 'class' => 'upcoming', 'icon' => 'bi-clock-history'];
        }

        return ['label' => __('نشط الآن'), 'class' => 'active', 'icon' => 'bi-broadcast'];
    };
@endphp

@section('content')
<div class="poll-page">
    <div class="poll-shell">
        <section class="poll-hero">
            <div class="poll-hero-inner">
                <div>
                    <span class="poll-badge">
                        <i class="bi bi-bar-chart-line-fill"></i>
                        {{ __('لوحة إدارة الاستطلاعات') }}
                    </span>
                    <h1 class="poll-title">{{ __('الاستطلاعات') }}</h1>
                </div>

                <div class="poll-hero-actions">
                    <a href="{{ route('polls.create') }}" class="poll-btn">
                        <i class="bi bi-plus-circle-fill"></i>
                        {{ __('إضافة استطلاع جديد') }}
                    </a>
                    <a href="{{ route('dashboard') }}" class="poll-btn-muted">
                        <i class="bi bi-grid-1x2-fill"></i>
                        {{ __('العودة للوحة التحكم') }}
                    </a>
                </div>
            </div>
        </section>

        <section class="poll-stats-grid">
            <article class="poll-stat-card" style="animation-delay: 0.05s;">
                <div class="poll-stat-icon"><i class="bi bi-collection-fill"></i></div>
                <p class="poll-stat-value">{{ number_format($totalPolls) }}</p>
                <p class="poll-stat-label">{{ __('إجمالي الاستطلاعات') }}</p>
            </article>
            <article class="poll-stat-card" style="animation-delay: 0.11s;">
                <div class="poll-stat-icon"><i class="bi bi-broadcast-pin"></i></div>
                <p class="poll-stat-value">{{ number_format($activePollsCount) }}</p>
                <p class="poll-stat-label">{{ __('نشطة في الصفحة الحالية') }}</p>
            </article>
            <article class="poll-stat-card" style="animation-delay: 0.17s;">
                <div class="poll-stat-icon"><i class="bi bi-calendar2-plus-fill"></i></div>
                <p class="poll-stat-value">{{ number_format($upcomingPollsCount) }}</p>
                <p class="poll-stat-label">{{ __('استطلاعات قادمة') }}</p>
            </article>
            <article class="poll-stat-card" style="animation-delay: 0.23s;">
                <div class="poll-stat-icon"><i class="bi bi-hand-thumbs-up-fill"></i></div>
                <p class="poll-stat-value">{{ number_format($pageVotesTotal) }}</p>
                <p class="poll-stat-label">{{ __('إجمالي الأصوات في الصفحة') }}</p>
            </article>
        </section>

        <section class="poll-list-shell">
            <div class="poll-toolbar">
                <div class="poll-search">
                    <i class="bi bi-search"></i>
                    <input type="search" id="pollsSearch" placeholder="{{ __('ابحث في السؤال أو اسم المنشئ أو تاريخ الاستطلاع...') }}" autocomplete="off">
                </div>

                <div class="poll-toolbar-meta">
                    <span class="poll-meta-pill">
                        <i class="bi bi-eye-fill"></i>
                        <span id="visiblePollsCount">{{ $pagePollsCount }}</span>
                        {{ __('نتيجة ظاهرة') }}
                    </span>
                    <span class="poll-meta-pill">
                        <i class="bi bi-files"></i>
                        {{ __('صفحة') }} {{ $polls->currentPage() }} / {{ $polls->lastPage() }}
                    </span>
                </div>
            </div>

            @if($pagePollsCount > 0)
                <div class="poll-list-body" id="pollsList">
                    @foreach($polls as $poll)
                        @php
                            $status = $resolveStatus($poll);
                            $optionsCount = $poll->pollOptions->count();
                            $votesCount = $poll->pollOptions->sum('votes');
                            $searchableText = implode(' ', [
                                $poll->question,
                                optional($poll->creator)->name,
                                $poll->start_date?->format('Y-m-d H:i'),
                                $poll->end_date?->format('Y-m-d H:i'),
                            ]);
                        @endphp

                        <article class="poll-list-row poll-search-item" data-search="{{ mb_strtolower($searchableText) }}" style="animation-delay: {{ 0.05 + ($loop->index * 0.04) }}s;">
                            <div>
                                <h3 class="poll-main-title">{{ \Illuminate\Support\Str::limit($poll->question, 110) }}</h3>
                                <div class="poll-main-subtitle">{{ __('المنشئ') }}: {{ optional($poll->creator)->name ?? __('غير معروف') }}</div>
                                <div class="poll-meta-row">
                                    <span class="poll-status-badge {{ $status['class'] }}">
                                        <i class="bi {{ $status['icon'] }}"></i>
                                        {{ $status['label'] }}
                                    </span>
                                    <span class="poll-chip">
                                        <i class="bi bi-people-fill"></i>
                                        {{ number_format($votesCount) }} {{ __('صوت') }}
                                    </span>
                                </div>
                            </div>

                            <div class="poll-table-field">
                                <span class="poll-table-label">{{ __('الفترة') }}</span>
                                <span class="poll-table-value">
                                    {{ $poll->start_date->format('Y-m-d H:i') }}<br>
                                    {{ __('إلى') }} {{ $poll->end_date->format('Y-m-d H:i') }}
                                </span>
                            </div>

                            <div class="poll-table-field">
                                <span class="poll-table-label">{{ __('الخيارات') }}</span>
                                <span class="poll-table-value">{{ number_format($optionsCount) }}</span>
                            </div>

                            <div class="poll-table-field">
                                <span class="poll-table-label">{{ __('المشاركون المستهدفون') }}</span>
                                <span class="poll-table-value">{{ number_format($poll->referencedUsers->count()) }}</span>
                            </div>

                            <div class="poll-table-actions">
                                <a href="{{ route('polls.show', $poll) }}" class="poll-icon-btn" title="{{ __('عرض') }}"><i class="bi bi-eye-fill"></i></a>
                                <a href="{{ route('polls.edit', $poll) }}" class="poll-icon-btn" title="{{ __('تعديل') }}"><i class="bi bi-pencil-square"></i></a>
                                <a href="{{ route('polls.results', $poll) }}" class="poll-icon-btn" title="{{ __('النتائج') }}"><i class="bi bi-bar-chart-fill"></i></a>
                                <form action="{{ route('polls.destroy', $poll) }}" method="POST" style="display: inline-flex;" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا الاستطلاع؟') }}');">
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

                <div class="poll-empty-state" id="pollsEmptyState" style="display: none; margin: 12px;">
                    <i class="bi bi-search-heart"></i>
                    <h3>{{ __('لا توجد نتائج مطابقة') }}</h3>
                    <p>{{ __('جرّب تغيير عبارة البحث أو استخدام جزء من السؤال أو اسم المنشئ للوصول إلى الاستطلاع المطلوب.') }}</p>
                </div>

                <div style="padding: 10px 22px 22px;" id="pollsPagination">
                    {{ $polls->links() }}
                </div>
            @else
                <div class="poll-empty-state" style="margin: 12px;">
                    <i class="bi bi-ui-checks-grid"></i>
                    <h3>{{ __('لا توجد استطلاعات بعد') }}</h3>
                    <p>{{ __('ابدأ بإنشاء أول استطلاع لتظهر هنا كل الاستطلاعات بشكل منظم وواضح مع النتائج والتفاعل.') }}</p>
                    <div style="margin-top: 18px;">
                        <a href="{{ route('polls.create') }}" class="poll-btn">
                            <i class="bi bi-plus-circle-fill"></i>
                            {{ __('إنشاء أول استطلاع') }}
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
        const searchInput = document.getElementById('pollsSearch');
        const rows = Array.from(document.querySelectorAll('.poll-search-item'));
        const visibleCount = document.getElementById('visiblePollsCount');
        const emptyState = document.getElementById('pollsEmptyState');
        const pagination = document.getElementById('pollsPagination');

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
