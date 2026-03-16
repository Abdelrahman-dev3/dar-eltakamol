@extends('layouts.app')

@section('title', __('عرض طلب الشراء'))

@include('shares-pos.partials.styles')

@php
    $buyerName = $sharesPO->contributor->name ?? __('غير معروف');
    $offer = $sharesPO->sellShare;
@endphp

@section('content')
    <div class="st-page">
        <div class="st-shell">
            <section class="st-hero">
                <div class="st-hero-inner">
                    <div>
                        <span class="st-hero-badge">
                            <i class="bi bi-file-earmark-text-fill"></i>
                            {{ __('عرض الطلب') }} #{{ $sharesPO->id }}
                        </span>
                        <h1 class="st-hero-title">{{ __('تفاصيل طلب الشراء') }}</h1>

                        <div class="st-chip-row" style="margin-top: 1rem;">
                            @include('shares-pos.partials.accept-badge', ['accept' => $sharesPO->accept])
                            @include('shares-pos.partials.status-badge', ['status' => $sharesPO->po_status])
                            <span class="st-chip">
                                <i class="bi bi-person-fill"></i>
                                {{ $buyerName }}
                            </span>
                        </div>
                    </div>

                    <div class="st-hero-actions">
                        <a href="{{ route('shares-pos.edit', $sharesPO) }}" class="st-btn st-btn-warning">
                            <i class="bi bi-pencil-square"></i>
                            {{ __('تعديل') }}
                        </a>
                        <a href="{{ route('shares-pos.index') }}" class="st-btn st-btn-secondary">
                            <i class="bi bi-arrow-right-circle"></i>
                            {{ __('العودة للطلبات') }}
                        </a>
                    </div>
                </div>
            </section>

            <section class="st-summary-grid">
                <article class="st-summary-card" style="animation-delay: 0.05s;">
                    <div class="st-summary-icon"><i class="bi bi-bar-chart-fill"></i></div>
                    <p class="st-summary-value">{{ number_format($sharesPO->count, 2) }}</p>
                    <p class="st-summary-label">{{ __('عدد الأسهم') }}</p>
                </article>
                <article class="st-summary-card" style="animation-delay: 0.12s;">
                    <div class="st-summary-icon"><i class="bi bi-cash-stack"></i></div>
                    <p class="st-summary-value">{{ number_format($sharesPO->total_amount, 2) }}</p>
                    <p class="st-summary-label">{{ __('القيمة الإجمالية') }} {{ __('ريال') }}</p>
                </article>
                <article class="st-summary-card" style="animation-delay: 0.19s;">
                    <div class="st-summary-icon"><i class="bi bi-link-45deg"></i></div>
                    <p class="st-summary-value">{{ $sharesPO->sale_number ?: __('بدون ربط') }}</p>
                    <p class="st-summary-label">{{ __('رقم العرض المرتبط') }}</p>
                </article>
                <article class="st-summary-card" style="animation-delay: 0.26s;">
                    <div class="st-summary-icon"><i class="bi bi-calendar-event-fill"></i></div>
                    <p class="st-summary-value">{{ $sharesPO->insert_date?->format('Y-m-d') }}</p>
                    <p class="st-summary-label">{{ __('تاريخ الإدراج') }}</p>
                </article>
            </section>

            <div class="st-grid-two">
                <div class="st-shell">
                    <section class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-title-wrap">
                                <span class="st-card-icon"><i class="bi bi-info-circle-fill"></i></span>
                                <div>
                                    <h2 class="st-card-title">{{ __('المعلومات الأساسية') }}</h2>
                                    <p class="st-card-subtitle">{{ __('البيانات الرئيسية لهذا الطلب بشكل واضح ومباشر.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="st-info-list">
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('رقم الطلب') }}</span>
                                <div class="st-info-value">#{{ $sharesPO->id }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('المساهم') }}</span>
                                <div class="st-info-value">{{ $buyerName }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('رقم العرض') }}</span>
                                <div class="st-info-value">{{ $sharesPO->sale_number ?: __('غير محدد') }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('الكمية') }}</span>
                                <div class="st-info-value">{{ number_format($sharesPO->count, 2) }} {{ __('سهم') }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('السعر لكل سهم') }}</span>
                                <div class="st-info-value">{{ number_format($sharesPO->amount_per_share, 2) }} {{ __('ريال') }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('القبول') }}</span>
                                <div class="st-info-value">@include('shares-pos.partials.accept-badge', ['accept' => $sharesPO->accept])</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('حالة الطلب') }}</span>
                                <div class="st-info-value">@include('shares-pos.partials.status-badge', ['status' => $sharesPO->po_status])</div>
                            </div>
                        </div>
                    </section>

                    @if($offer)
                        <section class="st-card">
                            <div class="st-card-header">
                                <div class="st-card-title-wrap">
                                    <span class="st-card-icon"><i class="bi bi-tags-fill"></i></span>
                                    <div>
                                        <h2 class="st-card-title">{{ __('عرض البيع المرتبط') }}</h2>
                                        <p class="st-card-subtitle">{{ __('تفاصيل مختصرة للعرض المرتبط بهذا الطلب.') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="st-info-list">
                                <div class="st-info-item">
                                    <span class="st-info-label">{{ __('رقم العرض') }}</span>
                                    <div class="st-info-value">#{{ $offer->id }}</div>
                                </div>
                                <div class="st-info-item">
                                    <span class="st-info-label">{{ __('البائع') }}</span>
                                    <div class="st-info-value">{{ $offer->seller->name ?? __('غير معروف') }}</div>
                                </div>
                                <div class="st-info-item">
                                    <span class="st-info-label">{{ __('عدد الأسهم المعروضة') }}</span>
                                    <div class="st-info-value">{{ number_format($offer->count, 0) }} {{ __('سهم') }}</div>
                                </div>
                                <div class="st-info-item">
                                    <span class="st-info-label">{{ __('سعر العرض') }}</span>
                                    <div class="st-info-value">{{ number_format($offer->amount_per_share, 2) }} {{ __('ريال') }}</div>
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
                                    <p class="st-card-subtitle">{{ __('تواريخ مهمة مرتبطة بإنشاء الطلب وآخر تحديث عليه.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="st-info-list">
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('تاريخ الإدراج') }}</span>
                                <div class="st-info-value">{{ $sharesPO->insert_date?->format('Y-m-d') }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('تاريخ الإنشاء') }}</span>
                                <div class="st-info-value">{{ $sharesPO->created_at->format('Y-m-d H:i') }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('آخر تحديث') }}</span>
                                <div class="st-info-value">{{ $sharesPO->updated_at->format('Y-m-d H:i') }}</div>
                            </div>
                        </div>
                    </section>

                    <section class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-title-wrap">
                                <span class="st-card-icon"><i class="bi bi-lightning-charge-fill"></i></span>
                                <div>
                                    <h2 class="st-card-title">{{ __('إجراءات الطلب') }}</h2>
                                    <p class="st-card-subtitle">{{ __('وصول سريع إلى التعديل أو تغيير القبول أو حذف الطلب.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="st-inline-actions" style="width: 100%;">
                            <a href="{{ route('shares-pos.edit', $sharesPO) }}" class="st-btn st-btn-warning">
                                <i class="bi bi-pencil-square"></i>
                                {{ __('تعديل') }}
                            </a>

                            <form action="{{ route('shares-pos.toggle-accept', $sharesPO) }}" method="POST" style="width: 100%;">
                                @csrf
                                <button type="submit" class="st-btn {{ $sharesPO->accept ? 'st-btn-danger' : 'st-btn-success' }}" style="width: 100%;" data-confirm="{{ $sharesPO->accept ? __('هل تريد رفض طلب الشراء؟') : __('هل تريد قبول طلب الشراء؟') }}">
                                    <i class="bi {{ $sharesPO->accept ? 'bi-ban-fill' : 'bi-check-circle-fill' }}"></i>
                                    {{ $sharesPO->accept ? __('رفض الطلب') : __('قبول الطلب') }}
                                </button>
                            </form>

                            <a href="{{ route('shares-pos.index') }}" class="st-btn st-btn-secondary">
                                <i class="bi bi-list-ul"></i>
                                {{ __('قائمة الطلبات') }}
                            </a>

                            <form action="{{ route('shares-pos.destroy', $sharesPO) }}" method="POST" style="width: 100%;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="st-btn st-btn-danger" style="width: 100%;" data-confirm="{{ __('هل أنت متأكد من حذف طلب الشراء؟') }}">
                                    <i class="bi bi-trash-fill"></i>
                                    {{ __('حذف الطلب') }}
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
