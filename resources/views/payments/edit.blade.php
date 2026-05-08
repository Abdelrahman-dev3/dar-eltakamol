@extends('layouts.app')

@section('title', __('تعديل الدفعة'))

@include('payments.partials.styles')

@php
    $recentOrders = $sharesPOs->take(5);
@endphp

@section('content')
    <div class="st-page">
        <div class="st-shell">
            <section class="st-hero">
                <div class="st-hero-inner">
                    <div>
                        <span class="st-hero-badge">
                            <i class="bi bi-pencil-square"></i>
                            {{ __('تعديل الدفعة') }} #{{ $payment->id }}
                        </span>
                        <h1 class="st-hero-title">{{ __('حدّث بيانات الدفعة بثقة') }}</h1>
                        <p class="st-hero-subtitle">
                            {{ __('راجع تاريخ الدفعة ومبلغها ورقم الطلب ومعلومات البنك، ثم احفظ التغييرات ضمن واجهة أوضح وأسرع وأكثر توافقًا مع بقية صفحات النظام الحديثة.') }}
                        </p>
                    </div>

                    <div class="st-hero-actions">
                        <a href="{{ route('payments.show', $payment) }}" class="st-btn st-btn-info">
                            <i class="bi bi-eye-fill"></i>
                            {{ __('عرض الدفعة') }}
                        </a>
                        <a href="{{ route('payments.index') }}" class="st-btn st-btn-secondary">
                            <i class="bi bi-arrow-right-circle"></i>
                            {{ __('العودة للمدفوعات') }}
                        </a>
                    </div>
                </div>
            </section>

            <div class="st-grid-two">
                <section class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-title-wrap">
                            <span class="st-card-icon"><i class="bi bi-sliders"></i></span>
                            <div>
                                <h2 class="st-card-title">{{ __('بيانات قابلة للتعديل') }}</h2>
                                <p class="st-card-subtitle">{{ __('يمكنك تعديل جميع الحقول، مع الاحتفاظ بالربط الحالي بطلب الشراء أو تغييره عند الحاجة.') }}</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('payments.update', $payment) }}" method="POST" data-payment-form data-confirm-message="{{ __('هل أنت متأكد من حفظ تعديلات هذه الدفعة؟') }}">
                        @csrf
                        @method('PUT')

                        <div class="st-form-grid">
                            <div class="st-form-field">
                                <label for="date" class="st-label">{{ __('تاريخ الدفع') }} <span class="st-required">*</span></label>
                                <input type="date" name="date" id="date" class="st-input" value="{{ old('date', $payment->date?->format('Y-m-d')) }}" required>
                                @error('date')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="st-form-field">
                                <label for="amount" class="st-label">{{ __('المبلغ') }} <span class="st-required">*</span></label>
                                <input type="number" name="amount" id="amount" class="st-input" value="{{ old('amount', $payment->amount) }}" step="0.01" min="0" required placeholder="{{ __('أدخل المبلغ') }}">
                                @error('amount')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="st-form-field">
                                <label for="shares_po_number" class="st-label">{{ __('رقم طلب الشراء') }}</label>
                                <input type="text" name="shares_po_number" id="shares_po_number" class="st-input" value="{{ old('shares_po_number', $payment->shares_po_number) }}" list="paymentOrderNumbers" placeholder="{{ __('اكتب رقم الطلب أو اختره من الاقتراحات') }}">
                                <datalist id="paymentOrderNumbers">
                                    @foreach($sharesPOs as $sharesPO)
                                        <option value="{{ $sharesPO->id }}">{{ __('طلب') }} #{{ $sharesPO->id }} - {{ __('عرض') }} #{{ $sharesPO->sale_number }} - {{ $sharesPO->contributor->name ?? __('مساهم غير معروف') }}</option>
                                    @endforeach
                                </datalist>
                                @error('shares_po_number')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="st-form-field">
                                <label for="transfer_document" class="st-label">{{ __('رقم مستند التحويل') }}</label>
                                <input type="text" name="transfer_document" id="transfer_document" class="st-input" value="{{ old('transfer_document', $payment->transfer_document) }}" placeholder="{{ __('أدخل المرجع أو رقم الإيصال') }}">
                                @error('transfer_document')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="st-form-field full">
                                <label for="bank_info" class="st-label">{{ __('معلومات البنك') }}</label>
                                <textarea name="bank_info" id="bank_info" class="st-textarea" placeholder="{{ __('اسم البنك، الحساب، الآيبان أو أي ملاحظات بنكية مرتبطة بالدفعة') }}">{{ old('bank_info', $payment->bank_info) }}</textarea>
                                @error('bank_info')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="st-form-field full">
                                <label class="st-label">{{ __('حالة الدفعة') }}</label>
                                <label class="st-chip" style="justify-content: space-between; cursor: pointer;">
                                    <span style="display: inline-flex; align-items: center; gap: 0.5rem;">
                                        <i class="bi bi-patch-check-fill"></i>
                                        {{ __('الدفعة مؤكدة') }}
                                    </span>
                                    <input type="checkbox" name="confirmed" value="1" {{ old('confirmed', $payment->confirmed) ? 'checked' : '' }}>
                                </label>
                            </div>
                        </div>

                        <div class="st-form-footer">
                            <p class="st-form-footer-note">{{ __('أي تغيير في مبلغ الدفعة أو ربطها بطلب الشراء سيظهر مباشرة في صفحات العرض والقوائم المرتبطة.') }}</p>

                            <div class="st-inline-actions">
                                <button type="submit" class="st-btn st-btn-primary">
                                    <i class="bi bi-check2-circle"></i>
                                    {{ __('حفظ التعديلات') }}
                                </button>
                                <a href="{{ route('payments.show', $payment) }}" class="st-btn st-btn-info">
                                    <i class="bi bi-eye-fill"></i>
                                    {{ __('عرض') }}
                                </a>
                                <a href="{{ route('payments.index') }}" class="st-btn st-btn-secondary">
                                    <i class="bi bi-x-circle"></i>
                                    {{ __('إلغاء') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </section>

                <div class="st-shell">
                    <section class="st-summary-grid" style="grid-template-columns: repeat(2, minmax(0, 1fr));">
                        <article class="st-summary-card">
                            <div class="st-summary-icon"><i class="bi bi-cash-stack"></i></div>
                            <p class="st-summary-value" id="paymentAmountPreview">{{ number_format($payment->amount, 2) }}</p>
                            <p class="st-summary-label">{{ __('المبلغ الحالي') }} {{ __('ريال') }}</p>
                        </article>

                        <article class="st-summary-card">
                            <div class="st-summary-icon"><i class="bi bi-calendar-event-fill"></i></div>
                            <p class="st-summary-value" id="paymentDatePreview">{{ $payment->date?->format('Y-m-d') }}</p>
                            <p class="st-summary-label">{{ __('تاريخ الدفعة') }}</p>
                        </article>
                    </section>

                    <section class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-title-wrap">
                                <span class="st-card-icon"><i class="bi bi-clock-history"></i></span>
                                <div>
                                    <h2 class="st-card-title">{{ __('معلومات زمنية') }}</h2>
                                    <p class="st-card-subtitle">{{ __('سجل زمني مختصر لمتابعة إنشاء الدفعة وآخر تحديث عليها.') }}</p>
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
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('الحالة الحالية') }}</span>
                                <div class="st-info-value">@include('payments.partials.status-badge', ['confirmed' => $payment->confirmed])</div>
                            </div>
                        </div>
                    </section>

                    <section class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-title-wrap">
                                <span class="st-card-icon"><i class="bi bi-link-45deg"></i></span>
                                <div>
                                    <h2 class="st-card-title">{{ __('طلبات شراء متاحة') }}</h2>
                                    <p class="st-card-subtitle">{{ __('اقتراحات سريعة في حال أردت تعديل الربط الحالي.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="st-info-list">
                            @forelse($recentOrders as $sharesPO)
                                <div class="st-info-item">
                                    <span class="st-info-label">{{ __('رقم الطلب') }}</span>
                                    <div class="st-info-value">
                                        {{ $sharesPO->id }}
                                        <span class="st-help" style="display: block;">{{ $sharesPO->contributor->name ?? __('مساهم غير معروف') }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="st-note-box">
                                    <i class="bi bi-info-circle-fill"></i>
                                    <div>{{ __('لا توجد طلبات شراء متاحة حاليًا للربط.') }}</div>
                                </div>
                            @endforelse
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
            const form = document.querySelector('[data-payment-form]');
            const amountInput = document.getElementById('amount');
            const dateInput = document.getElementById('date');
            const amountPreview = document.getElementById('paymentAmountPreview');
            const datePreview = document.getElementById('paymentDatePreview');

            function updatePreview() {
                const amount = parseFloat(amountInput.value || 0);
                amountPreview.textContent = amount.toFixed(2);
                datePreview.textContent = dateInput.value || '{{ $payment->date?->format('Y-m-d') }}';
            }

            if (amountInput) {
                amountInput.addEventListener('input', updatePreview);
            }

            if (dateInput) {
                dateInput.addEventListener('input', updatePreview);
            }

            if (form) {
                form.addEventListener('submit', function (event) {
                    const message = this.getAttribute('data-confirm-message');

                    if (message && !window.confirm(message)) {
                        event.preventDefault();
                    }
                });
            }

            updatePreview();
        });
    </script>
@endpush
