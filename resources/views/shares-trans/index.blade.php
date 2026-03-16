@extends('layouts.app')

@section('title', __('معاملات الأسهم'))

@include('shares-trans.partials.styles')

@php
    $transactionsCollection = $sharesTrans->getCollection();
    $pageTransactionsCount = $transactionsCollection->count();
    $approvedCount = $transactionsCollection->where('posted', true)->count();
    $pendingCount = $pageTransactionsCount - $approvedCount;
    $pageLinesCount = $transactionsCollection->sum(fn ($transaction) => $transaction->shareTransLines->count());
@endphp

@section('content')
    <div class="st-page">
        <div class="st-shell">
            <section class="st-hero">
                <div class="st-hero-inner">
                    <div>
                        <span class="st-hero-badge">
                            <i class="bi bi-stars"></i>
                            {{ __('لوحة إدارة المعاملات') }}
                        </span>
                        <h1 class="st-hero-title">{{ __('معاملات الأسهم') }}</h1>
                    </div>

                    <div class="st-hero-actions">
                        <a href="{{ route('shares-trans.create') }}" class="st-btn st-btn-primary">
                            <i class="bi bi-plus-circle-fill"></i>
                            {{ __('إضافة معاملة جديدة') }}
                        </a>
                        <a href="{{ route('dashboard') }}" class="st-btn st-btn-secondary">
                            <i class="bi bi-grid-1x2-fill"></i>
                            {{ __('العودة للوحة التحكم') }}
                        </a>
                    </div>
                </div>
            </section>

            <section class="st-stat-grid">
                <article class="st-stat-card" style="animation-delay: 0.05s;">
                    <div class="st-stat-icon">
                        <i class="bi bi-journal-check"></i>
                    </div>
                    <p class="st-stat-value">{{ number_format($sharesTrans->total()) }}</p>
                    <p class="st-stat-label">{{ __('إجمالي المعاملات') }}</p>
                </article>

                <article class="st-stat-card" style="animation-delay: 0.12s;">
                    <div class="st-stat-icon">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                    <p class="st-stat-value">{{ number_format($approvedCount) }}</p>
                    <p class="st-stat-label">{{ __('المعاملات المعتمدة في الصفحة الحالية') }}</p>
                </article>

                <article class="st-stat-card" style="animation-delay: 0.19s;">
                    <div class="st-stat-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <p class="st-stat-value">{{ number_format($pendingCount) }}</p>
                    <p class="st-stat-label">{{ __('المعاملات غير المعتمدة في الصفحة الحالية') }}</p>
                </article>

                <article class="st-stat-card" style="animation-delay: 0.26s;">
                    <div class="st-stat-icon">
                        <i class="bi bi-list-check"></i>
                    </div>
                    <p class="st-stat-value">{{ number_format($pageLinesCount) }}</p>
                    <p class="st-stat-label">{{ __('إجمالي التفاصيل الظاهرة') }}</p>
                </article>
            </section>

            <section class="st-toolbar">
                <div class="st-search">
                    <i class="bi bi-search"></i>
                    <input
                        type="search"
                        id="sharesTransSearch"
                        placeholder="{{ __('ابحث برقم المعاملة أو النوع أو الملاحظات...') }}"
                        autocomplete="off"
                    >
                </div>

                <div class="st-chip-row">
                    <span class="st-chip">
                        <i class="bi bi-eye-fill"></i>
                        <span id="visibleTransactionsCount">{{ $pageTransactionsCount }}</span>
                        {{ __('نتيجة ظاهرة') }}
                    </span>
                    <span class="st-chip">
                        <i class="bi bi-collection-fill"></i>
                        {{ __('صفحة') }} {{ $sharesTrans->currentPage() }} / {{ $sharesTrans->lastPage() }}
                    </span>
                </div>
            </section>

            <section class="st-list-card">
                @if($pageTransactionsCount > 0)
                    <div class="st-list-head">
                        <div>{{ __('المعاملة') }}</div>
                        <div>{{ __('التاريخ') }}</div>
                        <div>{{ __('النوع') }}</div>
                        <div>{{ __('الحالة') }}</div>
                        <div>{{ __('الإجراءات') }}</div>
                    </div>

                    <div class="st-list-body" id="sharesTransList">
                        @foreach($sharesTrans as $sharesTran)
                            @php
                                $searchableText = implode(' ', [
                                    $sharesTran->id,
                                    $sharesTran->getTransTypeText(),
                                    $sharesTran->notes,
                                    $sharesTran->date?->format('Y-m-d'),
                                ]);
                            @endphp

                            <article
                                class="st-row shares-trans-search-item"
                                data-search="{{ mb_strtolower($searchableText) }}"
                                style="animation-delay: {{ 0.05 + ($loop->index * 0.04) }}s;"
                            >
                                <div class="st-row-main">
                                    <div class="st-row-avatar">
                                        <i class="bi bi-arrow-left-right"></i>
                                    </div>

                                    <div style="min-width: 0;">
                                        <h3 class="st-row-title">{{ __('معاملة') }} #{{ $sharesTran->id }}</h3>
                                        <div class="st-row-meta">
                                            @include('shares-trans.partials.type-badge', ['type' => $sharesTran->trans_type])
                                            <span class="st-chip">
                                                <i class="bi bi-list-ul"></i>
                                                {{ $sharesTran->shareTransLines->count() }} {{ __('تفصيل') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="st-field">
                                    <span class="st-field-label">{{ __('التاريخ') }}</span>
                                    <span class="st-field-value">{{ $sharesTran->date?->format('Y-m-d') ?? __('غير متوفر') }}</span>
                                </div>

                                <div class="st-field">
                                    <span class="st-field-label">{{ __('النوع') }}</span>
                                    <span class="st-field-value">
                                        @include('shares-trans.partials.type-badge', ['type' => $sharesTran->trans_type])
                                    </span>
                                </div>

                                <div class="st-field">
                                    <span class="st-field-label">{{ __('الحالة') }}</span>
                                    <span class="st-field-value">
                                        @include('shares-trans.partials.status-badge', ['posted' => $sharesTran->posted])
                                    </span>
                                </div>

                                <div class="st-action-group">
                                    <a href="{{ route('shares-trans.show', $sharesTran) }}" class="st-icon-btn st-icon-btn-info" title="{{ __('عرض') }}">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('shares-trans.edit', $sharesTran) }}" class="st-icon-btn st-icon-btn-warning" title="{{ __('تعديل') }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    @if(!$sharesTran->posted)
                                        <form action="{{ route('shares-trans.post', $sharesTran) }}" method="POST" style="display: inline-flex;">
                                            @csrf
                                            <button type="submit" class="st-icon-btn st-icon-btn-success" title="{{ __('اعتماد') }}" data-confirm="{{ __('هل أنت متأكد من اعتماد هذه المعاملة؟') }}">
                                                <i class="bi bi-patch-check-fill"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('shares-trans.destroy', $sharesTran) }}" method="POST" style="display: inline-flex;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="st-icon-btn st-icon-btn-danger" title="{{ __('حذف') }}" data-confirm="{{ __('هل أنت متأكد من حذف هذه المعاملة؟') }}">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                </div>

                                <div class="st-field" style="grid-column: 1 / -1;">
                                    <span class="st-field-label">{{ __('الملاحظات') }}</span>
                                    <span class="st-field-value {{ $sharesTran->notes ? '' : 'muted' }}">
                                        {{ $sharesTran->notes ? \Illuminate\Support\Str::limit($sharesTran->notes, 180) : __('لا توجد ملاحظات مضافة لهذه المعاملة.') }}
                                    </span>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="st-empty" id="sharesTransEmptyState">
                        <div class="st-empty-icon">
                            <i class="bi bi-search-heart"></i>
                        </div>
                        <h3>{{ __('لا توجد نتائج مطابقة') }}</h3>
                        <p>{{ __('جرّب البحث برقم معاملة مختلف أو بتاريخ أو نوع معاملة للوصول بسرعة إلى السجل المطلوب.') }}</p>
                    </div>

                    <div class="st-pagination" id="sharesTransPagination">
                        {{ $sharesTrans->links() }}
                    </div>
                @else
                    <div class="st-empty show">
                        <div class="st-empty-icon">
                            <i class="bi bi-folder-x"></i>
                        </div>
                        <h3>{{ __('لا توجد معاملات أسهم بعد') }}</h3>
                        <p>{{ __('ابدأ بإضافة أول معاملة لتظهر هنا بشكل منظم ومتناسق مع بقية النظام.') }}</p>
                        <div style="margin-top: 1rem;">
                            <a href="{{ route('shares-trans.create') }}" class="st-btn st-btn-primary">
                                <i class="bi bi-plus-circle-fill"></i>
                                {{ __('إضافة أول معاملة') }}
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
            const searchInput = document.getElementById('sharesTransSearch');
            const rows = Array.from(document.querySelectorAll('.shares-trans-search-item'));
            const visibleCount = document.getElementById('visibleTransactionsCount');
            const emptyState = document.getElementById('sharesTransEmptyState');
            const pagination = document.getElementById('sharesTransPagination');

            function normalize(value) {
                return (value || '').toString().toLowerCase().trim();
            }

            document.querySelectorAll('[data-confirm]').forEach(function (element) {
                element.addEventListener('click', function (event) {
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
