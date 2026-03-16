@extends('layouts.app')

@section('title', __('عروض البيع'))

@include('sell-shares.partials.styles')

@php
    $offersCollection = $sellShares->getCollection();
    $pageOffersCount = $offersCollection->count();
    $activeOffersCount = $offersCollection->where('ad_status', \App\Models\SellShares::AD_STATUS_ACTIVE)->count();
    $completedOffersCount = $offersCollection->where('ad_status', \App\Models\SellShares::AD_STATUS_COMPLETED)->count();
    $pageSharesCount = $offersCollection->sum(fn ($offer) => (float) $offer->count);
    $purchaseOrdersCount = $offersCollection->sum(fn ($offer) => $offer->sharesPOs->count());
@endphp

@section('content')
    <div class="ss-page">
        <div class="ss-shell">
            <section class="ss-hero">
                <div class="ss-hero-inner">
                    <div>
                        <span class="ss-badge-top">
                            <i class="bi bi-tags-fill"></i>
                            {{ __('إدارة عروض البيع') }}
                        </span>
                        <h1 class="ss-title">{{ __('عروض البيع') }}</h1>
                    </div>

                    <div class="ss-actions">
                        @if($canCreate)
                            <a href="{{ route('sell-shares.create') }}" class="ss-btn ss-btn-primary">
                                <i class="bi bi-plus-circle-fill"></i>
                                {{ __('إضافة عرض جديد') }}
                            </a>
                        @endif
                        <a href="{{ route('dashboard') }}" class="ss-btn ss-btn-secondary">
                            <i class="bi bi-grid-1x2-fill"></i>
                            {{ __('العودة للوحة التحكم') }}
                        </a>
                    </div>
                </div>
            </section>

            <section class="ss-stat-grid">
                <article class="ss-stat-card" style="animation-delay: 0.05s;">
                    <div class="ss-stat-icon"><i class="bi bi-collection-fill"></i></div>
                    <p class="ss-stat-value">{{ number_format($sellShares->total()) }}</p>
                    <p class="ss-stat-label">{{ __('إجمالي عروض البيع') }}</p>
                </article>
                <article class="ss-stat-card" style="animation-delay: 0.12s;">
                    <div class="ss-stat-icon"><i class="bi bi-check-circle-fill"></i></div>
                    <p class="ss-stat-value">{{ number_format($activeOffersCount) }}</p>
                    <p class="ss-stat-label">{{ __('العروض النشطة في الصفحة الحالية') }}</p>
                </article>
                <article class="ss-stat-card" style="animation-delay: 0.19s;">
                    <div class="ss-stat-icon"><i class="bi bi-patch-check-fill"></i></div>
                    <p class="ss-stat-value">{{ number_format($completedOffersCount) }}</p>
                    <p class="ss-stat-label">{{ __('العروض المكتملة في الصفحة الحالية') }}</p>
                </article>
                <article class="ss-stat-card" style="animation-delay: 0.26s;">
                    <div class="ss-stat-icon"><i class="bi bi-bar-chart-line-fill"></i></div>
                    <p class="ss-stat-value">{{ number_format($pageSharesCount, 0) }}</p>
                    <p class="ss-stat-label">{{ __('إجمالي الأسهم المعروضة في الصفحة') }}</p>
                </article>
            </section>

            <section class="ss-toolbar">
                <div class="ss-search">
                    <i class="bi bi-search"></i>
                    <input type="search" id="sellSharesSearch" placeholder="{{ __('ابحث باسم المساهم أو رقم العرض أو الملاحظات...') }}" autocomplete="off">
                </div>

                <div class="ss-chip-row">
                    <span class="ss-chip">
                        <i class="bi bi-eye-fill"></i>
                        <span id="visibleSellSharesCount">{{ $pageOffersCount }}</span>
                        {{ __('نتيجة ظاهرة') }}
                    </span>
                    <span class="ss-chip">
                        <i class="bi bi-bag-heart-fill"></i>
                        {{ number_format($purchaseOrdersCount) }} {{ __('طلب شراء مرتبط') }}
                    </span>
                </div>
            </section>

            <section class="ss-list-card">
                @if($pageOffersCount > 0)
                    <div class="ss-list-head">
                        <div>{{ __('العرض') }}</div>
                        <div>{{ __('الكمية') }}</div>
                        <div>{{ __('السعر') }}</div>
                        <div>{{ __('الحالة') }}</div>
                        <div>{{ __('الإجراءات') }}</div>
                    </div>

                    <div class="ss-list-body" id="sellSharesList">
                        @foreach($sellShares as $sellShare)
                            @php
                                $sellerName = $sellShare->seller->name ?? $sellShare->seller->user->name ?? __('غير معروف');
                                $searchableText = implode(' ', [
                                    $sellShare->id,
                                    $sellerName,
                                    $sellShare->notes,
                                    $sellShare->getAdStatusText(),
                                    $sellShare->end_date?->format('Y-m-d'),
                                ]);
                            @endphp

                            <article class="ss-row sell-shares-search-item" data-search="{{ mb_strtolower($searchableText) }}" style="animation-delay: {{ 0.05 + ($loop->index * 0.04) }}s;">
                                <div class="ss-row-main">
                                    <div class="ss-row-avatar">
                                        {{ mb_substr($sellerName, 0, 1, 'UTF-8') }}
                                    </div>

                                    <div style="min-width: 0;">
                                        <h3 class="ss-row-title">{{ $sellerName }}</h3>
                                        <div class="ss-row-meta">
                                            <span class="ss-chip">
                                                <i class="bi bi-hash"></i>
                                                #{{ $sellShare->id }}
                                            </span>
                                            @include('sell-shares.partials.status-badge', ['status' => $sellShare->ad_status])
                                        </div>
                                    </div>
                                </div>

                                <div class="ss-field">
                                    <span class="ss-field-label">{{ __('الكمية') }}</span>
                                    <span class="ss-field-value">{{ number_format($sellShare->count, 0) }} {{ __('سهم') }}</span>
                                </div>

                                <div class="ss-field">
                                    <span class="ss-field-label">{{ __('السعر لكل سهم') }}</span>
                                    <span class="ss-field-value">{{ number_format($sellShare->amount_per_share, 2) }} {{ __('ريال') }}</span>
                                </div>

                                <div class="ss-field">
                                    <span class="ss-field-label">{{ __('الحالة') }}</span>
                                    <span class="ss-field-value">@include('sell-shares.partials.status-badge', ['status' => $sellShare->ad_status])</span>
                                </div>

                                <div class="ss-actions">
                                    <a href="{{ route('sell-shares.show', $sellShare) }}" class="ss-icon-btn ss-icon-btn-info" title="{{ __('تفاصيل') }}">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>

                                    @if($sellShare->ad_status == \App\Models\SellShares::AD_STATUS_INITIAL)
                                        <a href="{{ route('sell-shares.edit', $sellShare) }}" class="ss-icon-btn ss-icon-btn-warning" title="{{ __('تعديل') }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('sell-shares.destroy', $sellShare) }}" method="POST" style="display: inline-flex;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="ss-icon-btn ss-icon-btn-danger" title="{{ __('حذف') }}" data-confirm="{{ __('هل أنت متأكد من الحذف؟') }}">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('sell-shares.print', $sellShare) }}" class="ss-icon-btn ss-icon-btn-secondary" title="{{ __('طباعة') }}">
                                        <i class="bi bi-printer-fill"></i>
                                    </a>
                                </div>

                                <div class="ss-field" style="grid-column: 1 / -1;">
                                    <span class="ss-field-label">{{ __('معلومات إضافية') }}</span>
                                    <span class="ss-field-value {{ $sellShare->notes ? '' : 'muted' }}">
                                        {{ $sellShare->notes ? \Illuminate\Support\Str::limit($sellShare->notes, 180) : __('لا توجد ملاحظات على هذا العرض.') }}
                                    </span>
                                    <span class="ss-help">
                                        {{ __('طلبات الشراء المرتبطة') }}: {{ $sellShare->sharesPOs->count() }}
                                        @if($sellShare->end_date)
                                            | {{ __('ينتهي في') }} {{ $sellShare->end_date->format('Y-m-d') }}
                                        @endif
                                    </span>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="ss-empty" id="sellSharesEmptyState">
                        <div class="ss-empty-icon"><i class="bi bi-search-heart"></i></div>
                        <h3>{{ __('لا توجد نتائج مطابقة') }}</h3>
                        <p>{{ __('جرّب البحث باسم مساهم مختلف أو رقم عرض أو ملاحظة للوصول إلى السجل المطلوب.') }}</p>
                    </div>

                    <div class="ss-pagination" id="sellSharesPagination">
                        {{ $sellShares->links() }}
                    </div>
                @else
                    <div class="ss-empty show">
                        <div class="ss-empty-icon"><i class="bi bi-inboxes-fill"></i></div>
                        <h3>{{ __('لا توجد عروض بيع بعد') }}</h3>
                        <p>{{ __('بمجرد إضافة أول عرض سيظهر هنا بتصميم منظم وواضح مع أهم البيانات والإجراءات.') }}</p>
                        @if($canCreate)
                            <div style="margin-top: 1rem;">
                                <a href="{{ route('sell-shares.create') }}" class="ss-btn ss-btn-primary">
                                    <i class="bi bi-plus-circle-fill"></i>
                                    {{ __('إضافة أول عرض') }}
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('sellSharesSearch');
            const rows = Array.from(document.querySelectorAll('.sell-shares-search-item'));
            const visibleCount = document.getElementById('visibleSellSharesCount');
            const emptyState = document.getElementById('sellSharesEmptyState');
            const pagination = document.getElementById('sellSharesPagination');

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
