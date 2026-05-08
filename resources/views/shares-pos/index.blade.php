@extends('layouts.app')

@section('title', __('طلبات الشراء'))

@include('shares-pos.partials.styles')

@php
    $ordersCollection = $sharesPOs->getCollection();
    $pageOrdersCount = $ordersCollection->count();
    $acceptedOrdersCount = $ordersCollection->where('accept', true)->count();
    $pageSharesCount = $ordersCollection->sum(fn ($order) => (float) $order->count);
    $pageValueTotal = $ordersCollection->sum(fn ($order) => (float) $order->total_amount);
@endphp

@section('content')
    <div class="st-page">
        <div class="st-shell">
            <section class="st-hero">
                <div class="st-hero-inner">
                    <div>
                        <span class="st-hero-badge">
                            <i class="bi bi-cart-check-fill"></i>
                            {{ __('إدارة طلبات الشراء') }}
                        </span>
                        <h1 class="st-hero-title">{{ __('طلبات الشراء') }}</h1>
                    </div>

                    <div class="st-hero-actions">
                        <a href="{{ route('shares-pos.create') }}" class="st-btn st-btn-primary">
                            <i class="bi bi-plus-circle-fill"></i>
                            {{ __('إضافة طلب جديد') }}
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
                    <div class="st-stat-icon"><i class="bi bi-collection-fill"></i></div>
                    <p class="st-stat-value">{{ number_format($stats['total_count']) }}</p>
                    <p class="st-stat-label">{{ __('إجمالي طلبات الشراء') }}</p>
                </article>
                <article class="st-stat-card" style="animation-delay: 0.12s;">
                    <div class="st-stat-icon"><i class="bi bi-check-circle-fill"></i></div>
                    <p class="st-stat-value">{{ number_format($stats['accepted_count']) }}</p>
                    <p class="st-stat-label">{{ __('طلبات مقبولة') }}</p>
                </article>
                <article class="st-stat-card" style="animation-delay: 0.19s;">
                    <div class="st-stat-icon"><i class="bi bi-hourglass-split"></i></div>
                    <p class="st-stat-value">{{ number_format($stats['pending_accept_count']) }}</p>
                    <p class="st-stat-label">{{ __('طلبات بانتظار القبول') }}</p>
                </article>
                <article class="st-stat-card" style="animation-delay: 0.26s;">
                    <div class="st-stat-icon"><i class="bi bi-cash-stack"></i></div>
                    <p class="st-stat-value">{{ number_format($stats['total_value'], 2) }}</p>
                    <p class="st-stat-label">{{ __('إجمالي قيمة الطلبات') }} {{ __('ريال') }}</p>
                </article>
            </section>

            <section class="st-toolbar">
                <div class="st-search">
                    <i class="bi bi-search"></i>
                    <input type="search" id="sharesPOsSearch" placeholder="{{ __('ابحث باسم المساهم أو رقم العرض أو حالة الطلب...') }}" autocomplete="off">
                </div>

                <div class="st-chip-row">
                    <span class="st-chip">
                        <i class="bi bi-eye-fill"></i>
                        <span id="visibleOrdersCount">{{ $pageOrdersCount }}</span>
                        {{ __('نتيجة ظاهرة') }}
                    </span>
                    <span class="st-chip">
                        <i class="bi bi-check-circle-fill"></i>
                        {{ number_format($acceptedOrdersCount) }} {{ __('مقبول في الصفحة') }}
                    </span>
                    <span class="st-chip">
                        <i class="bi bi-bar-chart-fill"></i>
                        {{ number_format($pageSharesCount, 2) }} {{ __('سهم') }}
                    </span>
                </div>
            </section>

            <section class="st-list-card">
                @if($pageOrdersCount > 0)
                    <div class="st-list-head">
                        <div>{{ __('الطلب') }}</div>
                        <div>{{ __('الكمية') }}</div>
                        <div>{{ __('القيمة') }}</div>
                        <div>{{ __('الحالة') }}</div>
                        <div>{{ __('الإجراءات') }}</div>
                    </div>

                    <div class="st-list-body" id="sharesPOsList">
                        @foreach($sharesPOs as $order)
                            @php
                                $buyerName = $order->contributor->name ?? __('غير معروف');
                                $saleNumber = $order->sale_number ?: __('بدون ربط');
                                $searchableText = implode(' ', [
                                    $order->id,
                                    $buyerName,
                                    $saleNumber,
                                    $order->getPoStatusText(),
                                    $order->accept ? __('مقبول') : __('غير مقبول'),
                                    $order->insert_date?->format('Y-m-d'),
                                ]);
                            @endphp

                            <article class="st-row shares-pos-search-item" data-search="{{ mb_strtolower($searchableText) }}" style="animation-delay: {{ 0.05 + ($loop->index * 0.04) }}s;">
                                <div class="st-row-main">
                                    <div class="st-row-avatar">{{ mb_substr($buyerName, 0, 1, 'UTF-8') }}</div>
                                    <div style="min-width: 0;">
                                        <h3 class="st-row-title">{{ $buyerName }}</h3>
                                        <div class="st-row-meta">
                                            <span class="st-chip">
                                                <i class="bi bi-hash"></i>
                                                #{{ $order->id }}
                                            </span>
                                            <span class="st-chip">
                                                <i class="bi bi-link-45deg"></i>
                                                {{ $saleNumber }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="st-field">
                                    <span class="st-field-label">{{ __('الكمية') }}</span>
                                    <span class="st-field-value">{{ number_format($order->count, 2) }} {{ __('سهم') }}</span>
                                </div>

                                <div class="st-field">
                                    <span class="st-field-label">{{ __('القيمة الإجمالية') }}</span>
                                    <span class="st-field-value">{{ number_format($order->total_amount, 2) }} {{ __('ريال') }}</span>
                                </div>

                                <div class="st-field">
                                    <span class="st-field-label">{{ __('الحالة') }}</span>
                                    <span class="st-field-value">
                                        @include('shares-pos.partials.status-badge', ['status' => $order->po_status])
                                    </span>
                                </div>

                                <div class="st-action-group">
                                    <a href="{{ route('shares-pos.show', $order) }}" class="st-icon-btn st-icon-btn-info" title="{{ __('عرض') }}">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('shares-pos.edit', $order) }}" class="st-icon-btn st-icon-btn-warning" title="{{ __('تعديل') }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('shares-pos.toggle-accept', $order) }}" method="POST" style="display: inline-flex;">
                                        @csrf
                                        <button type="submit" class="st-icon-btn {{ $order->accept ? 'st-icon-btn-danger' : 'st-icon-btn-success' }}" title="{{ $order->accept ? __('رفض') : __('قبول') }}" data-confirm="{{ $order->accept ? __('هل تريد رفض هذا الطلب؟') : __('هل تريد قبول هذا الطلب؟') }}">
                                            <i class="bi {{ $order->accept ? 'bi-ban-fill' : 'bi-check-circle-fill' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('shares-pos.mark-default', $order) }}" method="POST" style="display: inline-flex;">
                                        @csrf
                                        <button type="submit" class="st-icon-btn st-icon-btn-danger" title="تسجيل إخلال" data-confirm="سيتم تسجيل إخلال على المشتري، والتكرار يؤدي إلى حظر سنة. هل تريد المتابعة؟">
                                            <i class="bi bi-exclamation-triangle-fill"></i>
                                        </button>
                                    </form>
                                </div>

                                <div class="st-field">
                                    <span class="st-field-label">{{ __('القبول') }}</span>
                                    <span class="st-field-value">@include('shares-pos.partials.accept-badge', ['accept' => $order->accept])</span>
                                </div>

                                <div class="st-field">
                                    <span class="st-field-label">{{ __('سعر السهم') }}</span>
                                    <span class="st-field-value">{{ number_format($order->amount_per_share, 2) }} {{ __('ريال') }}</span>
                                </div>

                                <div class="st-field" style="grid-column: 1 / -1;">
                                    <span class="st-field-label">{{ __('ربط عرض البيع') }}</span>
                                    <span class="st-field-value {{ $order->sellShare ? '' : 'muted' }}">
                                        @if($order->sellShare)
                                            {{ __('عرض بيع') }} #{{ $order->sellShare->id }}
                                            <span class="st-help" style="display: block;">
                                                {{ $order->sellShare->seller->name ?? __('مساهم غير معروف') }} | {{ number_format($order->sellShare->amount_per_share, 2) }} {{ __('ريال') }}
                                            </span>
                                        @else
                                            {{ __('لا يوجد عرض بيع مرتبط بهذا الطلب حاليًا.') }}
                                        @endif
                                    </span>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="st-empty" id="sharesPOsEmptyState">
                        <div class="st-empty-icon"><i class="bi bi-search-heart"></i></div>
                        <h3>{{ __('لا توجد نتائج مطابقة') }}</h3>
                        <p>{{ __('جرّب البحث باسم مساهم آخر أو رقم عرض مختلف أو حالة طلب مختلفة للوصول إلى السجل المطلوب.') }}</p>
                    </div>

                    <div class="st-pagination" id="sharesPOsPagination">
                        {{ $sharesPOs->links() }}
                    </div>
                @else
                    <div class="st-empty show">
                        <div class="st-empty-icon"><i class="bi bi-cart-x-fill"></i></div>
                        <h3>{{ __('لا توجد طلبات شراء بعد') }}</h3>
                        <p>{{ __('بمجرد إضافة أول طلب شراء سيظهر هنا بتصميم حديث وواضح مع أهم البيانات والإجراءات.') }}</p>
                        <div style="margin-top: 1rem;">
                            <a href="{{ route('shares-pos.create') }}" class="st-btn st-btn-primary">
                                <i class="bi bi-plus-circle-fill"></i>
                                {{ __('إضافة أول طلب') }}
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
            const searchInput = document.getElementById('sharesPOsSearch');
            const rows = Array.from(document.querySelectorAll('.shares-pos-search-item'));
            const visibleCount = document.getElementById('visibleOrdersCount');
            const emptyState = document.getElementById('sharesPOsEmptyState');
            const pagination = document.getElementById('sharesPOsPagination');

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
