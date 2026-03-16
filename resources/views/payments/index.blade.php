@extends('layouts.app')

@section('title', __('المدفوعات'))

@include('payments.partials.styles')

@php
    $pagePayments = $payments->getCollection();
    $pageCount = $pagePayments->count();
    $pageConfirmedCount = $pagePayments->where('confirmed', true)->count();
    $pageAmountTotal = $pagePayments->sum(fn ($payment) => (float) $payment->amount);
@endphp

@section('content')
    <div class="st-page">
        <div class="st-shell">
            <section class="st-hero">
                <div class="st-hero-inner">
                    <div>
                        <span class="st-hero-badge">
                            <i class="bi bi-credit-card-2-front-fill"></i>
                            {{ __('إدارة المدفوعات') }}
                        </span>
                        <h1 class="st-hero-title">{{ __('المدفوعات') }}</h1>
                    </div>

                    <div class="st-hero-actions">
                        <a href="{{ route('payments.create') }}" class="st-btn st-btn-primary">
                            <i class="bi bi-plus-circle-fill"></i>
                            {{ __('إضافة دفعة جديدة') }}
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
                    <div class="st-stat-icon"><i class="bi bi-wallet2"></i></div>
                    <p class="st-stat-value">{{ number_format($stats['total_count']) }}</p>
                    <p class="st-stat-label">{{ __('إجمالي الدفعات') }}</p>
                </article>

                <article class="st-stat-card" style="animation-delay: 0.12s;">
                    <div class="st-stat-icon"><i class="bi bi-patch-check-fill"></i></div>
                    <p class="st-stat-value">{{ number_format($stats['confirmed_count']) }}</p>
                    <p class="st-stat-label">{{ __('دفعات مؤكدة') }}</p>
                </article>

                <article class="st-stat-card" style="animation-delay: 0.19s;">
                    <div class="st-stat-icon"><i class="bi bi-hourglass-split"></i></div>
                    <p class="st-stat-value">{{ number_format($stats['pending_count']) }}</p>
                    <p class="st-stat-label">{{ __('دفعات قيد المراجعة') }}</p>
                </article>

                <article class="st-stat-card" style="animation-delay: 0.26s;">
                    <div class="st-stat-icon"><i class="bi bi-cash-coin"></i></div>
                    <p class="st-stat-value">{{ number_format($stats['total_amount'], 2) }}</p>
                    <p class="st-stat-label">{{ __('إجمالي قيمة الدفعات') }} {{ __('ريال') }}</p>
                </article>
            </section>

            <section class="st-toolbar">
                <div class="st-search">
                    <i class="bi bi-search"></i>
                    <input type="search" id="paymentsSearch" placeholder="{{ __('ابحث بالتاريخ أو رقم الطلب أو مرجع التحويل أو معلومات البنك...') }}" autocomplete="off">
                </div>

                <div class="st-chip-row">
                    <span class="st-chip">
                        <i class="bi bi-eye-fill"></i>
                        <span id="visiblePaymentsCount">{{ $pageCount }}</span>
                        {{ __('نتيجة ظاهرة') }}
                    </span>
                    <span class="st-chip">
                        <i class="bi bi-patch-check-fill"></i>
                        {{ number_format($pageConfirmedCount) }} {{ __('مؤكدة في الصفحة') }}
                    </span>
                    <span class="st-chip">
                        <i class="bi bi-cash-stack"></i>
                        {{ number_format($pageAmountTotal, 2) }} {{ __('ريال') }}
                    </span>
                </div>
            </section>

            <section class="st-list-card">
                @if($pageCount > 0)
                    <div class="st-list-head">
                        <div>{{ __('الدفعة') }}</div>
                        <div>{{ __('المبلغ') }}</div>
                        <div>{{ __('الربط') }}</div>
                        <div>{{ __('الحالة') }}</div>
                        <div>{{ __('الإجراءات') }}</div>
                    </div>

                    <div class="st-list-body" id="paymentsList">
                        @foreach($payments as $payment)
                            @php
                                $order = $payment->sharesPO;
                                $searchableText = implode(' ', [
                                    $payment->id,
                                    $payment->date?->format('Y-m-d'),
                                    $payment->shares_po_number,
                                    $payment->transfer_document,
                                    $payment->bank_info,
                                    $order?->contributor?->name,
                                    $payment->confirmed ? __('مؤكد') : __('قيد المراجعة'),
                                ]);
                            @endphp

                            <article class="st-row payments-search-item" data-search="{{ mb_strtolower($searchableText) }}" style="animation-delay: {{ 0.05 + ($loop->index * 0.04) }}s;">
                                <div class="st-row-main">
                                    <div class="st-row-avatar">{{ $payment->date?->format('d') }}</div>
                                    <div style="min-width: 0;">
                                        <h3 class="st-row-title">{{ __('دفعة رقم') }} #{{ $payment->id }}</h3>
                                        <div class="st-row-meta">
                                            <span class="st-chip">
                                                <i class="bi bi-calendar-event-fill"></i>
                                                {{ $payment->date?->format('Y-m-d') }}
                                            </span>
                                            @if($payment->transfer_document)
                                                <span class="st-chip">
                                                    <i class="bi bi-receipt-cutoff"></i>
                                                    {{ $payment->transfer_document }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="st-field">
                                    <span class="st-field-label">{{ __('المبلغ') }}</span>
                                    <span class="st-field-value">{{ number_format($payment->amount, 2) }} {{ __('ريال') }}</span>
                                </div>

                                <div class="st-field">
                                    <span class="st-field-label">{{ __('طلب الشراء') }}</span>
                                    <span class="st-field-value">
                                        {{ $payment->shares_po_number ?: __('غير مرتبط') }}
                                        <span class="st-help" style="display: block;">
                                            {{ $order?->contributor?->name ?: __('لا يوجد مساهم مرتبط حاليًا') }}
                                        </span>
                                    </span>
                                </div>

                                <div class="st-field">
                                    <span class="st-field-label">{{ __('الحالة') }}</span>
                                    <span class="st-field-value">
                                        @include('payments.partials.status-badge', ['confirmed' => $payment->confirmed])
                                    </span>
                                </div>

                                <div class="st-action-group">
                                    <a href="{{ route('payments.show', $payment) }}" class="st-icon-btn st-icon-btn-info" title="{{ __('عرض') }}">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('payments.edit', $payment) }}" class="st-icon-btn st-icon-btn-warning" title="{{ __('تعديل') }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('payments.toggle-confirmed', $payment) }}" method="POST" style="display: inline-flex;">
                                        @csrf
                                        <button type="submit" class="st-icon-btn {{ $payment->confirmed ? 'st-icon-btn-danger' : 'st-icon-btn-success' }}" title="{{ $payment->confirmed ? __('إلغاء التأكيد') : __('تأكيد') }}" data-confirm="{{ $payment->confirmed ? __('هل تريد إلغاء تأكيد هذه الدفعة؟') : __('هل تريد تأكيد هذه الدفعة؟') }}">
                                            <i class="bi {{ $payment->confirmed ? 'bi-ban-fill' : 'bi-patch-check-fill' }}"></i>
                                        </button>
                                    </form>
                                </div>

                                <div class="st-field" style="grid-column: 1 / -1;">
                                    <span class="st-field-label">{{ __('معلومات البنك') }}</span>
                                    <span class="st-field-value {{ $payment->bank_info ? '' : 'muted' }}">
                                        {{ $payment->bank_info ? \Illuminate\Support\Str::limit($payment->bank_info, 160) : __('لا توجد معلومات بنكية مضافة لهذه الدفعة.') }}
                                    </span>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="st-empty" id="paymentsEmptyState">
                        <div class="st-empty-icon"><i class="bi bi-search-heart"></i></div>
                        <h3>{{ __('لا توجد نتائج مطابقة') }}</h3>
                        <p>{{ __('جرّب البحث بتاريخ مختلف أو رقم طلب شراء أو رقم مستند التحويل للوصول إلى الدفعة المطلوبة.') }}</p>
                    </div>

                    <div class="st-pagination" id="paymentsPagination">
                        {{ $payments->links() }}
                    </div>
                @else
                    <div class="st-empty show">
                        <div class="st-empty-icon"><i class="bi bi-wallet-x"></i></div>
                        <h3>{{ __('لا توجد مدفوعات بعد') }}</h3>
                        <p>{{ __('ابدأ بإضافة أول دفعة لتظهر هنا ضمن لوحة حديثة واضحة ومتجاوبة مع الثيم وحجم الخط.') }}</p>
                        <div style="margin-top: 1rem;">
                            <a href="{{ route('payments.create') }}" class="st-btn st-btn-primary">
                                <i class="bi bi-plus-circle-fill"></i>
                                {{ __('إضافة أول دفعة') }}
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
            const searchInput = document.getElementById('paymentsSearch');
            const rows = Array.from(document.querySelectorAll('.payments-search-item'));
            const visibleCount = document.getElementById('visiblePaymentsCount');
            const emptyState = document.getElementById('paymentsEmptyState');
            const pagination = document.getElementById('paymentsPagination');

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
