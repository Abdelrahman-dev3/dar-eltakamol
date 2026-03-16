@extends('layouts.app')

@section('title', __('تفاصيل معاملات الأسهم'))

@include('shares-trans.partials.styles')

@php
    $linesCollection = $shareTransLines->getCollection();
    $pageLinesCount = $linesCollection->count();
    $approvedLinesCount = $linesCollection->where('posted', true)->count();
    $pendingLinesCount = $pageLinesCount - $approvedLinesCount;
    $pageDebitTotal = $linesCollection->sum(fn ($line) => (float) $line->count_debit);
    $pageCreditTotal = $linesCollection->sum(fn ($line) => (float) $line->count_credit);
@endphp

@section('content')
    <div class="st-page">
        <div class="st-shell">
            <section class="st-hero">
                <div class="st-hero-inner">
                    <div>
                        <span class="st-hero-badge">
                            <i class="bi bi-list-stars"></i>
                            {{ __('إدارة تفاصيل المعاملات') }}
                        </span>
                        <h1 class="st-hero-title">{{ __('تفاصيل معاملات الأسهم') }}</h1>
                        @if($selectedTransId)
                            <div class="st-chip-row" style="margin-top: 1rem;">
                                <span class="st-chip">
                                    <i class="bi bi-funnel-fill"></i>
                                    {{ __('تصفية حسب المعاملة') }} #{{ $selectedTransId }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="st-hero-actions">
                        <a href="{{ route('share-trans-lines.create', $selectedTransId ? ['trans_id' => $selectedTransId] : []) }}" class="st-btn st-btn-primary">
                            <i class="bi bi-plus-circle-fill"></i>
                            {{ __('إضافة تفصيل جديد') }}
                        </a>

                        @if($selectedTransId)
                            <a href="{{ route('shares-trans.show', $selectedTransId) }}" class="st-btn st-btn-info">
                                <i class="bi bi-eye-fill"></i>
                                {{ __('عرض المعاملة') }}
                            </a>
                        @endif

                        <a href="{{ route('dashboard') }}" class="st-btn st-btn-secondary">
                            <i class="bi bi-grid-1x2-fill"></i>
                            {{ __('العودة للوحة التحكم') }}
                        </a>
                    </div>
                </div>
            </section>

            <section class="st-stat-grid">
                <article class="st-stat-card" style="animation-delay: 0.05s;">
                    <div class="st-stat-icon"><i class="bi bi-table"></i></div>
                    <p class="st-stat-value">{{ number_format($shareTransLines->total()) }}</p>
                    <p class="st-stat-label">{{ __('إجمالي السطور') }}</p>
                </article>

                <article class="st-stat-card" style="animation-delay: 0.12s;">
                    <div class="st-stat-icon"><i class="bi bi-patch-check-fill"></i></div>
                    <p class="st-stat-value">{{ number_format($approvedLinesCount) }}</p>
                    <p class="st-stat-label">{{ __('السطور المعتمدة في الصفحة الحالية') }}</p>
                </article>

                <article class="st-stat-card" style="animation-delay: 0.19s;">
                    <div class="st-stat-icon"><i class="bi bi-arrow-down-left-circle-fill"></i></div>
                    <p class="st-stat-value">{{ number_format($pageDebitTotal, 2) }}</p>
                    <p class="st-stat-label">{{ __('إجمالي الخصم الظاهر') }}</p>
                </article>

                <article class="st-stat-card" style="animation-delay: 0.26s;">
                    <div class="st-stat-icon"><i class="bi bi-arrow-up-right-circle-fill"></i></div>
                    <p class="st-stat-value">{{ number_format($pageCreditTotal, 2) }}</p>
                    <p class="st-stat-label">{{ __('إجمالي الدائن الظاهر') }}</p>
                </article>
            </section>

            <section class="st-toolbar">
                <div class="st-search">
                    <i class="bi bi-search"></i>
                    <input type="search" id="shareTransLinesSearch" placeholder="{{ __('ابحث باسم المساهم أو رقم المعاملة أو الملاحظات...') }}" autocomplete="off">
                </div>

                <div class="st-chip-row">
                    <span class="st-chip">
                        <i class="bi bi-eye-fill"></i>
                        <span id="visibleLinesCount">{{ $pageLinesCount }}</span>
                        {{ __('نتيجة ظاهرة') }}
                    </span>
                    <span class="st-chip">
                        <i class="bi bi-hourglass-split"></i>
                        {{ number_format($pendingLinesCount) }} {{ __('قيد المراجعة') }}
                    </span>
                </div>
            </section>

            <section class="st-list-card">
                @if($pageLinesCount > 0)
                    <div class="st-list-head">
                        <div>{{ __('السطر') }}</div>
                        <div>{{ __('المعاملة') }}</div>
                        <div>{{ __('الحركة') }}</div>
                        <div>{{ __('الحالة') }}</div>
                        <div>{{ __('الإجراءات') }}</div>
                    </div>

                    <div class="st-list-body" id="shareTransLinesList">
                        @foreach($shareTransLines as $line)
                            @php
                                $direction = (float) $line->count_debit > 0 ? __('خصم') : ((float) $line->count_credit > 0 ? __('دائن') : __('غير محدد'));
                                $effectiveShares = max((float) $line->count_debit, (float) $line->count_credit);
                                $lineAmount = $effectiveShares * (float) $line->amount_per_share;
                                $transaction = $line->sharesTrans;
                                $searchableText = implode(' ', [
                                    $line->id,
                                    $line->trans_id,
                                    $line->contributor->name ?? '',
                                    $line->line_notes,
                                    $direction,
                                    $transaction?->getTransTypeText(),
                                ]);
                            @endphp

                            <article class="st-row share-trans-lines-search-item" data-search="{{ mb_strtolower($searchableText) }}" style="animation-delay: {{ 0.05 + ($loop->index * 0.04) }}s;">
                                <div class="st-row-main">
                                    <div class="st-row-avatar">
                                        {{ $line->contributor ? mb_substr($line->contributor->name, 0, 1, 'UTF-8') : '#' }}
                                    </div>

                                    <div style="min-width: 0;">
                                        <h3 class="st-row-title">{{ $line->contributor->name ?? __('غير معروف') }}</h3>
                                        <div class="st-row-meta">
                                            <span class="st-chip">
                                                <i class="bi bi-hash"></i>
                                                #{{ $line->id }}
                                            </span>
                                            <span class="st-chip">
                                                <i class="bi bi-arrow-left-right"></i>
                                                {{ $direction }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="st-field">
                                    <span class="st-field-label">{{ __('المعاملة') }}</span>
                                    <span class="st-field-value">
                                        #{{ $line->trans_id }}
                                        @if($transaction)
                                            <span class="st-help" style="display: block;">{{ $transaction->getTransTypeText() }}</span>
                                        @endif
                                    </span>
                                </div>

                                <div class="st-field">
                                    <span class="st-field-label">{{ __('الحركة') }}</span>
                                    <span class="st-field-value">
                                        {{ number_format($effectiveShares, 2) }} {{ __('سهم') }}
                                        <span class="st-help" style="display: block;">{{ number_format($lineAmount, 2) }} {{ __('ريال') }}</span>
                                    </span>
                                </div>

                                <div class="st-field">
                                    <span class="st-field-label">{{ __('الحالة') }}</span>
                                    <span class="st-field-value">
                                        @include('shares-trans.partials.status-badge', ['posted' => $line->posted])
                                    </span>
                                </div>

                                <div class="st-action-group">
                                    <a href="{{ route('share-trans-lines.show', $line) }}" class="st-icon-btn st-icon-btn-info" title="{{ __('عرض') }}">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('share-trans-lines.edit', $line) }}" class="st-icon-btn st-icon-btn-warning" title="{{ __('تعديل') }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('share-trans-lines.toggle-posted', $line) }}" method="POST" style="display: inline-flex;">
                                        @csrf
                                        <button type="submit" class="st-icon-btn {{ $line->posted ? 'st-icon-btn-danger' : 'st-icon-btn-success' }}" title="{{ $line->posted ? __('إلغاء الاعتماد') : __('اعتماد') }}" data-confirm="{{ $line->posted ? __('هل تريد إلغاء اعتماد هذا السطر؟') : __('هل تريد اعتماد هذا السطر؟') }}">
                                            <i class="bi {{ $line->posted ? 'bi-ban-fill' : 'bi-patch-check-fill' }}"></i>
                                        </button>
                                    </form>
                                </div>

                                <div class="st-field" style="grid-column: 1 / -1;">
                                    <span class="st-field-label">{{ __('ملاحظات السطر') }}</span>
                                    <span class="st-field-value {{ $line->line_notes ? '' : 'muted' }}">
                                        {{ $line->line_notes ?: __('لا توجد ملاحظات مرتبطة بهذا السطر.') }}
                                    </span>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="st-empty" id="shareTransLinesEmptyState">
                        <div class="st-empty-icon"><i class="bi bi-search-heart"></i></div>
                        <h3>{{ __('لا توجد نتائج مطابقة') }}</h3>
                        <p>{{ __('جرّب البحث باسم مساهم مختلف أو رقم معاملة أو نوع حركة للوصول إلى السطر المطلوب.') }}</p>
                    </div>

                    <div class="st-pagination" id="shareTransLinesPagination">
                        {{ $shareTransLines->links() }}
                    </div>
                @else
                    <div class="st-empty show">
                        <div class="st-empty-icon"><i class="bi bi-journal-x"></i></div>
                        <h3>{{ __('لا توجد تفاصيل معاملات بعد') }}</h3>
                        <p>{{ __('ابدأ بإضافة أول سطر ليظهر هنا بشكل واضح ومتناسق مع بقية صفحات المعاملات.') }}</p>
                        <div style="margin-top: 1rem;">
                            <a href="{{ route('share-trans-lines.create', $selectedTransId ? ['trans_id' => $selectedTransId] : []) }}" class="st-btn st-btn-primary">
                                <i class="bi bi-plus-circle-fill"></i>
                                {{ __('إضافة أول تفصيل') }}
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
            const searchInput = document.getElementById('shareTransLinesSearch');
            const rows = Array.from(document.querySelectorAll('.share-trans-lines-search-item'));
            const visibleCount = document.getElementById('visibleLinesCount');
            const emptyState = document.getElementById('shareTransLinesEmptyState');
            const pagination = document.getElementById('shareTransLinesPagination');

            function normalize(value) {
                return (value || '').toString().toLowerCase().trim();
            }

            document.querySelectorAll('[data-confirm]').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    const message = this.getAttribute('data-confirm');

                    if (message && !window.confirm(message)) {
                        event.preventDefault();
                    }
                });
            });

            if (searchInput && rows.length > 0) {
                function filterRows() {
                    const query = normalize(searchInput.value);
                    let shown = 0;

                    rows.forEach(function (row) {
                        const haystack = normalize(row.getAttribute('data-search'));
                        const match = query === '' || haystack.indexOf(query) !== -1;

                        row.classList.toggle('is-hidden', !match);

                        if (match) {
                            shown += 1;
                        }
                    });

                    if (visibleCount) {
                        visibleCount.textContent = shown;
                    }

                    if (emptyState) {
                        emptyState.classList.toggle('show', shown === 0);
                    }

                    if (pagination) {
                        pagination.style.display = shown === 0 ? 'none' : '';
                    }
                }

                searchInput.addEventListener('input', filterRows);
                filterRows();
            }
        });
    </script>
@endpush
