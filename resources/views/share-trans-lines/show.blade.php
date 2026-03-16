@extends('layouts.app')

@section('title', __('عرض تفصيل معاملة الأسهم'))

@include('shares-trans.partials.styles')

@php
    $transaction = $shareTransLine->sharesTrans;
    $contributor = $shareTransLine->contributor;
    $effectiveShares = max((float) $shareTransLine->count_debit, (float) $shareTransLine->count_credit);
    $totalAmount = $effectiveShares * (float) $shareTransLine->amount_per_share;
    $direction = (float) $shareTransLine->count_debit > 0 ? __('خصم') : ((float) $shareTransLine->count_credit > 0 ? __('دائن') : __('غير محدد'));
@endphp

@section('content')
    <div class="st-page">
        <div class="st-shell">
            <section class="st-hero">
                <div class="st-hero-inner">
                    <div>
                        <span class="st-hero-badge">
                            <i class="bi bi-file-earmark-text-fill"></i>
                            {{ __('عرض السطر') }} #{{ $shareTransLine->id }}
                        </span>
                        <h1 class="st-hero-title">{{ __('تفاصيل سطر معاملة الأسهم') }}</h1>
                        <p class="st-hero-subtitle">
                            {{ __('صفحة عرض حديثة وواضحة تبين المساهم والمعاملة المرتبطة واتجاه الحركة وقيمة السطر وحالته، مع وصول مباشر لإجراءات التعديل وتغيير الاعتماد.') }}
                        </p>

                        <div class="st-chip-row" style="margin-top: 1rem;">
                            @include('shares-trans.partials.status-badge', ['posted' => $shareTransLine->posted])
                            <span class="st-chip">
                                <i class="bi bi-arrow-repeat"></i>
                                {{ $direction }}
                            </span>
                            @if($contributor)
                                <span class="st-chip">
                                    <i class="bi bi-person-fill"></i>
                                    {{ $contributor->name }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="st-hero-actions">
                        <a href="{{ route('share-trans-lines.edit', $shareTransLine) }}" class="st-btn st-btn-warning">
                            <i class="bi bi-pencil-square"></i>
                            {{ __('تعديل') }}
                        </a>
                        @if($transaction)
                            <a href="{{ route('shares-trans.show', $transaction) }}" class="st-btn st-btn-info">
                                <i class="bi bi-eye-fill"></i>
                                {{ __('عرض المعاملة') }}
                            </a>
                        @endif
                        <a href="{{ route('share-trans-lines.index') }}" class="st-btn st-btn-secondary">
                            <i class="bi bi-arrow-right-circle"></i>
                            {{ __('العودة للتفاصيل') }}
                        </a>
                    </div>
                </div>
            </section>

            <section class="st-summary-grid">
                <article class="st-summary-card" style="animation-delay: 0.05s;">
                    <div class="st-summary-icon"><i class="bi bi-person-fill"></i></div>
                    <p class="st-summary-value">{{ $contributor->name ?? __('غير محدد') }}</p>
                    <p class="st-summary-label">{{ __('المساهم') }}</p>
                </article>
                <article class="st-summary-card" style="animation-delay: 0.12s;">
                    <div class="st-summary-icon"><i class="bi bi-list-ol"></i></div>
                    <p class="st-summary-value">{{ number_format($effectiveShares, 2) }}</p>
                    <p class="st-summary-label">{{ __('عدد الأسهم الفعلي') }}</p>
                </article>
                <article class="st-summary-card" style="animation-delay: 0.19s;">
                    <div class="st-summary-icon"><i class="bi bi-cash-stack"></i></div>
                    <p class="st-summary-value">{{ number_format($totalAmount, 2) }}</p>
                    <p class="st-summary-label">{{ __('المبلغ الإجمالي') }} {{ __('ريال') }}</p>
                </article>
                <article class="st-summary-card" style="animation-delay: 0.26s;">
                    <div class="st-summary-icon"><i class="bi bi-arrow-left-right"></i></div>
                    <p class="st-summary-value">{{ $direction }}</p>
                    <p class="st-summary-label">{{ __('اتجاه الحركة') }}</p>
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
                                    <p class="st-card-subtitle">{{ __('بيانات السطر وعلاقته بالمعاملة الأم والمساهم المرتبط به.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="st-info-list">
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('رقم السطر') }}</span>
                                <div class="st-info-value">#{{ $shareTransLine->id }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('المساهم') }}</span>
                                <div class="st-info-value">{{ $contributor->name ?? __('غير محدد') }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('المعاملة الأساسية') }}</span>
                                <div class="st-info-value">
                                    @if($transaction)
                                        #{{ $transaction->id }} - {{ $transaction->date?->format('Y-m-d') }} - {{ $transaction->getTransTypeText() }}
                                    @else
                                        {{ __('غير محدد') }}
                                    @endif
                                </div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('الخصم') }}</span>
                                <div class="st-info-value">{{ number_format($shareTransLine->count_debit, 2) }} {{ __('سهم') }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('الدائن') }}</span>
                                <div class="st-info-value">{{ number_format($shareTransLine->count_credit, 2) }} {{ __('سهم') }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('سعر السهم') }}</span>
                                <div class="st-info-value">{{ number_format($shareTransLine->amount_per_share, 2) }} {{ __('ريال') }}</div>
                            </div>
                        </div>
                    </section>

                    @if($shareTransLine->line_notes)
                        <section class="st-card">
                            <div class="st-card-header">
                                <div class="st-card-title-wrap">
                                    <span class="st-card-icon"><i class="bi bi-chat-left-text-fill"></i></span>
                                    <div>
                                        <h2 class="st-card-title">{{ __('ملاحظات السطر') }}</h2>
                                        <p class="st-card-subtitle">{{ __('أي توضيح مضاف لهذا السطر يظهر هنا.') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="st-note-box">
                                <i class="bi bi-quote"></i>
                                <div>{{ $shareTransLine->line_notes }}</div>
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
                                    <p class="st-card-subtitle">{{ __('تواريخ الإنشاء والتحديث الحالية لهذا السطر.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="st-info-list">
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('تاريخ الإنشاء') }}</span>
                                <div class="st-info-value">{{ $shareTransLine->created_at->format('Y-m-d H:i') }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('آخر تحديث') }}</span>
                                <div class="st-info-value">{{ $shareTransLine->updated_at->format('Y-m-d H:i') }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('الحالة') }}</span>
                                <div class="st-info-value">@include('shares-trans.partials.status-badge', ['posted' => $shareTransLine->posted])</div>
                            </div>
                        </div>
                    </section>

                    <section class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-title-wrap">
                                <span class="st-card-icon"><i class="bi bi-lightning-charge-fill"></i></span>
                                <div>
                                    <h2 class="st-card-title">{{ __('إجراءات السطر') }}</h2>
                                    <p class="st-card-subtitle">{{ __('الوصول السريع إلى التعديل أو تبديل حالة الاعتماد أو الرجوع للقائمة.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="st-inline-actions" style="width: 100%;">
                            <a href="{{ route('share-trans-lines.edit', $shareTransLine) }}" class="st-btn st-btn-warning">
                                <i class="bi bi-pencil-square"></i>
                                {{ __('تعديل') }}
                            </a>

                            <form action="{{ route('share-trans-lines.toggle-posted', $shareTransLine) }}" method="POST" style="width: 100%;">
                                @csrf
                                <button type="submit" class="st-btn {{ $shareTransLine->posted ? 'st-btn-danger' : 'st-btn-success' }}" style="width: 100%;" data-confirm="{{ $shareTransLine->posted ? __('هل أنت متأكد من إلغاء اعتماد هذا السطر؟') : __('هل أنت متأكد من اعتماد هذا السطر؟') }}">
                                    <i class="bi {{ $shareTransLine->posted ? 'bi-ban-fill' : 'bi-patch-check-fill' }}"></i>
                                    {{ $shareTransLine->posted ? __('إلغاء الاعتماد') : __('اعتماد السطر') }}
                                </button>
                            </form>

                            <a href="{{ route('share-trans-lines.index') }}" class="st-btn st-btn-secondary">
                                <i class="bi bi-list-ul"></i>
                                {{ __('قائمة التفاصيل') }}
                            </a>
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
