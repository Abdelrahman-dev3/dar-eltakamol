@extends('layouts.app')

@section('title', __('حركات المساهمين'))

@include('shares-trans.partials.styles')

@php
    $movementsCollection = $movements->getCollection();
    $pageMovementsCount = $movementsCollection->count();
    $pageSharesTotal = $movementsCollection->sum(fn ($movement) => (float) $movement->shares_count);
@endphp

@section('content')
    <div class="st-page">
        <div class="st-shell">
            <section class="st-hero">
                <div class="st-hero-inner">
                    <div>
                        <span class="st-hero-badge">
                            <i class="bi bi-people-fill"></i>
                            {{ __('إدارة المساهمين') }}
                        </span>
                        <h1 class="st-hero-title">{{ __('حركات المساهمين') }}</h1>
                        <p class="st-hero-subtitle">
                            {{ __('عرض سجل الحركات السابقة وإضافة حركات بيع أو شراء أو مناقلة أو توزيعات من حساب الادمن فقط.') }}
                        </p>
                    </div>

                    <div class="st-hero-actions">
                        <a href="{{ route('contributor-movements.create') }}" class="st-btn st-btn-primary">
                            <i class="bi bi-plus-circle-fill"></i>
                            {{ __('إضافة حركة للمساهم') }}
                        </a>
                        <a href="{{ route('contributors.index') }}" class="st-btn st-btn-secondary">
                            <i class="bi bi-arrow-right-circle"></i>
                            {{ __('العودة للمساهمين') }}
                        </a>
                    </div>
                </div>
            </section>

            <section class="st-stat-grid">
                <article class="st-stat-card">
                    <div class="st-stat-icon"><i class="bi bi-journal-check"></i></div>
                    <p class="st-stat-value">{{ number_format($movements->total()) }}</p>
                    <p class="st-stat-label">{{ __('إجمالي الحركات') }}</p>
                </article>
                <article class="st-stat-card">
                    <div class="st-stat-icon"><i class="bi bi-eye-fill"></i></div>
                    <p class="st-stat-value">{{ number_format($pageMovementsCount) }}</p>
                    <p class="st-stat-label">{{ __('حركات الصفحة الحالية') }}</p>
                </article>
                <article class="st-stat-card">
                    <div class="st-stat-icon"><i class="bi bi-layers-fill"></i></div>
                    <p class="st-stat-value">{{ number_format($pageSharesTotal, 2) }}</p>
                    <p class="st-stat-label">{{ __('إجمالي الأسهم الظاهرة') }}</p>
                </article>
                <article class="st-stat-card">
                    <div class="st-stat-icon"><i class="bi bi-patch-check-fill"></i></div>
                    <p class="st-stat-value">{{ number_format($movementsCollection->whereNotNull('shares_trans_id')->count()) }}</p>
                    <p class="st-stat-label">{{ __('حركات مرتبطة بمعاملة أسهم') }}</p>
                </article>
            </section>

            <section class="st-toolbar">
                <div class="st-search">
                    <i class="bi bi-search"></i>
                    <input type="search" id="contributorMovementsSearch" placeholder="{{ __('ابحث باسم المساهم أو النوع أو الوصف...') }}" autocomplete="off">
                </div>
                <div class="st-chip-row">
                    <span class="st-chip">
                        <i class="bi bi-eye-fill"></i>
                        <span id="visibleMovementsCount">{{ $pageMovementsCount }}</span>
                        {{ __('نتيجة ظاهرة') }}
                    </span>
                    <span class="st-chip">
                        <i class="bi bi-collection-fill"></i>
                        {{ __('صفحة') }} {{ $movements->currentPage() }} / {{ $movements->lastPage() }}
                    </span>
                </div>
            </section>

            <section class="st-list-card">
                @if($pageMovementsCount > 0)
                    <div class="st-list-head">
                        <div>{{ __('الحركة') }}</div>
                        <div>{{ __('من') }}</div>
                        <div>{{ __('إلى') }}</div>
                        <div>{{ __('الأسهم') }}</div>
                        <div>{{ __('الإجراءات') }}</div>
                    </div>

                    <div class="st-list-body" id="contributorMovementsList">
                        @foreach($movements as $movement)
                            @php
                                $sharesCount = (float) $movement->shares_count;
                                $amountPerShare = (float) $movement->amount_per_share;
                                $totalAmount = $sharesCount * $amountPerShare;
                                $searchableText = implode(' ', [
                                    $movement->id,
                                    $movement->getMovementTypeText(),
                                    $movement->description,
                                    $movement->fromContributor?->name,
                                    $movement->toContributor?->name,
                                ]);
                            @endphp

                            <article class="st-row contributor-movement-search-item" data-search="{{ mb_strtolower($searchableText) }}">
                                <div class="st-row-main">
                                    <div class="st-row-avatar">
                                        <i class="bi bi-arrow-left-right"></i>
                                    </div>
                                    <div style="min-width: 0;">
                                        <h3 class="st-row-title">{{ __('حركة') }} #{{ $movement->id }}</h3>
                                        <div class="st-row-meta">
                                            @include('shares-trans.partials.type-badge', ['type' => $movement->movement_type])
                                            @include('shares-trans.partials.status-badge', ['posted' => true])
                                            <span class="st-chip">{{ $movement->date?->format('Y-m-d') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="st-field">
                                    <span class="st-field-label">{{ __('من') }}</span>
                                    <span class="st-field-value {{ $movement->fromContributor ? '' : 'muted' }}">
                                        {{ $movement->fromContributor?->name ?? __('غير محدد') }}
                                        @if(!is_null($movement->from_balance_before))
                                            <span class="st-help" style="display: block;">
                                                {{ number_format((float) $movement->from_balance_before, 2) }} -> {{ number_format((float) $movement->from_balance_after, 2) }}
                                            </span>
                                        @endif
                                    </span>
                                </div>

                                <div class="st-field">
                                    <span class="st-field-label">{{ __('إلى') }}</span>
                                    <span class="st-field-value {{ $movement->toContributor ? '' : 'muted' }}">
                                        {{ $movement->toContributor?->name ?? __('غير محدد') }}
                                        @if(!is_null($movement->to_balance_before))
                                            <span class="st-help" style="display: block;">
                                                {{ number_format((float) $movement->to_balance_before, 2) }} -> {{ number_format((float) $movement->to_balance_after, 2) }}
                                            </span>
                                        @endif
                                    </span>
                                </div>

                                <div class="st-field">
                                    <span class="st-field-label">{{ __('الأسهم والقيمة') }}</span>
                                    <span class="st-field-value">
                                        {{ number_format($sharesCount, 2) }} {{ __('سهم') }}
                                        <span class="st-help" style="display: block;">
                                            {{ number_format($amountPerShare, 2) }} x {{ number_format($sharesCount, 2) }} = {{ number_format($totalAmount, 2) }}
                                        </span>
                                    </span>
                                </div>

                                <div class="st-action-group">
                                    @if($movement->sharesTrans)
                                    <a href="{{ route('shares-trans.show', $movement->sharesTrans) }}" class="st-icon-btn st-icon-btn-info" title="{{ __('عرض') }}">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    @endif
                                </div>

                                <div class="st-field" style="grid-column: 1 / -1;">
                                    <span class="st-field-label">{{ __('الوصف') }}</span>
                                    <span class="st-field-value {{ $movement->description ? '' : 'muted' }}">
                                        {{ $movement->description ?: __('لا يوجد وصف مضاف لهذه الحركة.') }}
                                    </span>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="st-empty" id="contributorMovementsEmptyState">
                        <div class="st-empty-icon"><i class="bi bi-search-heart"></i></div>
                        <h3>{{ __('لا توجد نتائج مطابقة') }}</h3>
                        <p>{{ __('جرّب البحث باسم مساهم آخر أو نوع حركة مختلف.') }}</p>
                    </div>

                    <div class="st-pagination" id="contributorMovementsPagination">
                        {{ $movements->links() }}
                    </div>
                @else
                    <div class="st-empty show">
                        <div class="st-empty-icon"><i class="bi bi-journal-x"></i></div>
                        <h3>{{ __('لا توجد حركات مساهمين بعد') }}</h3>
                        <p>{{ __('ابدأ بإضافة أول حركة للمساهم ليظهر السجل هنا.') }}</p>
                    </div>
                @endif
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('contributorMovementsSearch');
            const rows = Array.from(document.querySelectorAll('.contributor-movement-search-item'));
            const visibleCount = document.getElementById('visibleMovementsCount');
            const emptyState = document.getElementById('contributorMovementsEmptyState');
            const pagination = document.getElementById('contributorMovementsPagination');

            if (!searchInput || rows.length === 0) {
                return;
            }

            function normalize(value) {
                return (value || '').toString().toLowerCase().trim();
            }

            function filterRows() {
                const query = normalize(searchInput.value);
                let shown = 0;

                rows.forEach(function (row) {
                    const match = query === '' || normalize(row.getAttribute('data-search')).indexOf(query) !== -1;
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
        });
    </script>
@endpush
