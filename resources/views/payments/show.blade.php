@extends('layouts.app')

@section('title', __('عرض الدفعة'))

@include('payments.partials.styles')

@php
    $order = $payment->sharesPO;
    $orderTotal = $order ? ((float) $order->count * (float) $order->amount_per_share) : 0;
@endphp

@section('content')
    <div class="st-page">
        <div class="st-shell">
            <section class="st-hero">
                <div class="st-hero-inner">
                    <div>
                        <span class="st-hero-badge">
                            <i class="bi bi-file-earmark-text-fill"></i>
                            {{ __('عرض الدفعة') }} #{{ $payment->id }}
                        </span>
                        <h1 class="st-hero-title">{{ __('تفاصيل الدفعة المالية') }}</h1>
                        <p class="st-hero-subtitle">
                            {{ __('صفحة عرض حديثة توضّح قيمة الدفعة وحالتها وربطها بطلب الشراء ومعلومات البنك ومرجع التحويل مع إجراءات سريعة في نفس المكان.') }}
                        </p>

                        <div class="st-chip-row" style="margin-top: 1rem;">
                            @include('payments.partials.status-badge', ['confirmed' => $payment->confirmed])
                            <span class="st-chip">
                                <i class="bi bi-calendar-event-fill"></i>
                                {{ $payment->date?->format('Y-m-d') }}
                            </span>
                            @if($payment->shares_po_number)
                                <span class="st-chip">
                                    <i class="bi bi-link-45deg"></i>
                                    {{ __('طلب') }} {{ $payment->shares_po_number }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="st-hero-actions">
                        <a href="{{ route('payments.edit', $payment) }}" class="st-btn st-btn-warning">
                            <i class="bi bi-pencil-square"></i>
                            {{ __('تعديل') }}
                        </a>
                        <a href="{{ route('payments.index') }}" class="st-btn st-btn-secondary">
                            <i class="bi bi-arrow-right-circle"></i>
                            {{ __('العودة للمدفوعات') }}
                        </a>
                    </div>
                </div>
            </section>

            <section class="st-summary-grid">
                <article class="st-summary-card" style="animation-delay: 0.05s;">
                    <div class="st-summary-icon"><i class="bi bi-cash-stack"></i></div>
                    <p class="st-summary-value">{{ number_format($payment->amount, 2) }}</p>
                    <p class="st-summary-label">{{ __('المبلغ') }} {{ __('ريال') }}</p>
                </article>
                <article class="st-summary-card" style="animation-delay: 0.12s;">
                    <div class="st-summary-icon"><i class="bi bi-link-45deg"></i></div>
                    <p class="st-summary-value">{{ $payment->shares_po_number ?: __('غير مرتبط') }}</p>
                    <p class="st-summary-label">{{ __('رقم طلب الشراء') }}</p>
                </article>
                <article class="st-summary-card" style="animation-delay: 0.19s;">
                    <div class="st-summary-icon"><i class="bi bi-receipt-cutoff"></i></div>
                    <p class="st-summary-value">{{ $payment->transfer_document ?: __('غير محدد') }}</p>
                    <p class="st-summary-label">{{ __('مرجع التحويل') }}</p>
                </article>
                <article class="st-summary-card" style="animation-delay: 0.26s;">
                    <div class="st-summary-icon"><i class="bi bi-patch-check-fill"></i></div>
                    <p class="st-summary-value">{{ $payment->confirmed ? __('مؤكد') : __('مراجعة') }}</p>
                    <p class="st-summary-label">{{ __('حالة الدفعة') }}</p>
                </article>
                <article class="st-summary-card" style="animation-delay: 0.33s;">
                    <div class="st-summary-icon"><i class="bi bi-arrow-left-right"></i></div>
                    <p class="st-summary-value">{{ number_format((float) $payment->transferable_shares, 2) }}</p>
                    <p class="st-summary-label">الأسهم القابلة للنقل من هذه الدفعة</p>
                </article>
            </section>

            <div class="st-grid-two">
                <div class="st-shell">
                    <section class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-title-wrap">
                                <span class="st-card-icon"><i class="bi bi-info-circle-fill"></i></span>
                                <div>
                                    <h2 class="st-card-title">{{ __('معلومات الدفعة') }}</h2>
                                    <p class="st-card-subtitle">{{ __('البيانات الأساسية المرتبطة بهذه الدفعة المالية.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="st-info-list">
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('رقم الدفعة') }}</span>
                                <div class="st-info-value">#{{ $payment->id }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('تاريخ الدفع') }}</span>
                                <div class="st-info-value">{{ $payment->date?->format('Y-m-d') }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('المبلغ') }}</span>
                                <div class="st-info-value">{{ number_format($payment->amount, 2) }} {{ __('ريال') }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('رقم طلب الشراء') }}</span>
                                <div class="st-info-value">{{ $payment->shares_po_number ?: __('غير محدد') }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('رقم مستند التحويل') }}</span>
                                <div class="st-info-value">{{ $payment->transfer_document ?: __('غير محدد') }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('الحالة') }}</span>
                                <div class="st-info-value">@include('payments.partials.status-badge', ['confirmed' => $payment->confirmed])</div>
                            </div>
                        </div>
                    </section>

                    <section class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-title-wrap">
                                <span class="st-card-icon"><i class="bi bi-bank"></i></span>
                                <div>
                                    <h2 class="st-card-title">{{ __('معلومات البنك') }}</h2>
                                    <p class="st-card-subtitle">{{ __('أي تفاصيل بنكية مرتبطة بعملية التحويل أو الاستلام.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="st-note-box">
                            <i class="bi bi-bank2"></i>
                            <div style="white-space: pre-line;">
                                {{ $payment->bank_info ?: __('لا توجد معلومات بنكية مضافة لهذه الدفعة.') }}
                            </div>
                        </div>
                    </section>

                    @if($order)
                        <section class="st-card">
                            <div class="st-card-header">
                                <div class="st-card-title-wrap">
                                    <span class="st-card-icon"><i class="bi bi-diagram-3-fill"></i></span>
                                    <div>
                                        <h2 class="st-card-title">{{ __('طلب الشراء المرتبط') }}</h2>
                                        <p class="st-card-subtitle">{{ __('تفاصيل سريعة للطلب المرتبط بهذه الدفعة إن كان الربط موجودًا.') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="st-info-list">
                                <div class="st-info-item">
                                    <span class="st-info-label">{{ __('رقم الطلب') }}</span>
                                    <div class="st-info-value">{{ $order->sale_number }}</div>
                                </div>
                                <div class="st-info-item">
                                    <span class="st-info-label">{{ __('المساهم') }}</span>
                                    <div class="st-info-value">{{ $order->contributor->name ?? __('غير معروف') }}</div>
                                </div>
                                <div class="st-info-item">
                                    <span class="st-info-label">{{ __('الكمية') }}</span>
                                    <div class="st-info-value">{{ number_format($order->count, 2) }} {{ __('سهم') }}</div>
                                </div>
                                <div class="st-info-item">
                                    <span class="st-info-label">{{ __('سعر السهم') }}</span>
                                    <div class="st-info-value">{{ number_format($order->amount_per_share, 2) }} {{ __('ريال') }}</div>
                                </div>
                                <div class="st-info-item">
                                    <span class="st-info-label">{{ __('قيمة الطلب التقديرية') }}</span>
                                    <div class="st-info-value">{{ number_format($orderTotal, 2) }} {{ __('ريال') }}</div>
                                </div>
                                <div class="st-info-item">
                                    <span class="st-info-label">{{ __('حالة القبول') }}</span>
                                    <div class="st-info-value">
                                        <span class="st-badge {{ $order->accept ? 'st-badge-success' : 'st-badge-danger' }}">
                                            <i class="bi {{ $order->accept ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                            {{ $order->accept ? __('مقبول') : __('غير مقبول') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </section>
                    @endif
                </div>

                <div class="st-shell">
                    <section class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-title-wrap">
                                <span class="st-card-icon"><i class="bi bi-clock-history"></i></span>
                                <div>
                                    <h2 class="st-card-title">{{ __('معلومات زمنية') }}</h2>
                                    <p class="st-card-subtitle">{{ __('تاريخ الإنشاء وآخر تحديث لهذه الدفعة.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="st-info-list">
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('تاريخ الإنشاء') }}</span>
                                <div class="st-info-value">{{ $payment->created_at->format('Y-m-d H:i') }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('آخر تحديث') }}</span>
                                <div class="st-info-value">{{ $payment->updated_at->format('Y-m-d H:i') }}</div>
                            </div>
                        </div>
                    </section>

                    <section class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-title-wrap">
                                <span class="st-card-icon"><i class="bi bi-lightning-charge-fill"></i></span>
                                <div>
                                    <h2 class="st-card-title">{{ __('إجراءات الدفعة') }}</h2>
                                    <p class="st-card-subtitle">{{ __('وصول سريع إلى التعديل أو تغيير الحالة أو حذف الدفعة.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="st-inline-actions" style="width: 100%;">
                            <a href="{{ route('payments.edit', $payment) }}" class="st-btn st-btn-warning">
                                <i class="bi bi-pencil-square"></i>
                                {{ __('تعديل') }}
                            </a>

                            <form action="{{ route('payments.toggle-confirmed', $payment) }}" method="POST" style="width: 100%;">
                                @csrf
                                <button type="submit" class="st-btn {{ $payment->confirmed ? 'st-btn-danger' : 'st-btn-success' }}" style="width: 100%;" data-confirm="{{ $payment->confirmed ? __('هل تريد إلغاء تأكيد هذه الدفعة؟') : __('هل تريد تأكيد هذه الدفعة؟') }}">
                                    <i class="bi {{ $payment->confirmed ? 'bi-ban-fill' : 'bi-patch-check-fill' }}"></i>
                                    {{ $payment->confirmed ? __('إلغاء التأكيد') : __('تأكيد الدفعة') }}
                                </button>
                            </form>

                            <a href="{{ route('payments.index') }}" class="st-btn st-btn-secondary">
                                <i class="bi bi-list-ul"></i>
                                {{ __('قائمة المدفوعات') }}
                            </a>

                            <form action="{{ route('payments.destroy', $payment) }}" method="POST" style="width: 100%;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="st-btn st-btn-danger" style="width: 100%;" data-confirm="{{ __('هل أنت متأكد من حذف هذه الدفعة؟') }}">
                                    <i class="bi bi-trash-fill"></i>
                                    {{ __('حذف الدفعة') }}
                                </button>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-confirm]').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    const message = this.getAttribute('data-confirm');

                    if (message && !window.confirm(message)) {
                        event.preventDefault();
                    }
                });
            });
        });
    </script>
@endpush
