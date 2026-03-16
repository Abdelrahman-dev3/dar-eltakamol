@extends('layouts.app')

@section('title', __('تعديل تفصيل معاملة الأسهم'))

@include('shares-trans.partials.styles')

@php
    $isPosted = (bool) $shareTransLine->posted;
    $transaction = $shareTransLine->sharesTrans;
    $effectiveShares = max((float) $shareTransLine->count_debit, (float) $shareTransLine->count_credit);
    $effectiveTotal = $effectiveShares * (float) $shareTransLine->amount_per_share;
@endphp

@section('content')
    <div class="st-page">
        <div class="st-shell">
            <section class="st-hero">
                <div class="st-hero-inner">
                    <div>
                        <span class="st-hero-badge">
                            <i class="bi bi-pencil-square"></i>
                            {{ __('تعديل تفصيل') }} #{{ $shareTransLine->id }}
                        </span>
                        <h1 class="st-hero-title">{{ __('حدّث بيانات السطر المرتبط بالمعاملة') }}</h1>
                    </div>

                    <div class="st-hero-actions">
                        <a href="{{ route('share-trans-lines.show', $shareTransLine) }}" class="st-btn st-btn-info">
                            <i class="bi bi-eye-fill"></i>
                            {{ __('عرض السطر') }}
                        </a>
                        @if($transaction)
                            <a href="{{ route('shares-trans.show', $transaction) }}" class="st-btn st-btn-secondary">
                                <i class="bi bi-arrow-left-right"></i>
                                {{ __('عرض المعاملة') }}
                            </a>
                        @endif
                    </div>
                </div>
            </section>

            <section class="st-summary-grid">
                <article class="st-summary-card" style="animation-delay: 0.05s;">
                    <div class="st-summary-icon"><i class="bi bi-person-fill"></i></div>
                    <p class="st-summary-value">{{ $shareTransLine->contributor->name ?? __('غير معروف') }}</p>
                    <p class="st-summary-label">{{ __('المساهم') }}</p>
                </article>
                <article class="st-summary-card" style="animation-delay: 0.12s;">
                    <div class="st-summary-icon"><i class="bi bi-list-ol"></i></div>
                    <p class="st-summary-value">{{ number_format($effectiveShares, 2) }}</p>
                    <p class="st-summary-label">{{ __('عدد الأسهم الفعلي') }}</p>
                </article>
                <article class="st-summary-card" style="animation-delay: 0.19s;">
                    <div class="st-summary-icon"><i class="bi bi-cash-stack"></i></div>
                    <p class="st-summary-value">{{ number_format($effectiveTotal, 2) }}</p>
                    <p class="st-summary-label">{{ __('القيمة الحالية') }} {{ __('ريال') }}</p>
                </article>
                <article class="st-summary-card" style="animation-delay: 0.26s;">
                    <div class="st-summary-icon"><i class="bi bi-shield-check"></i></div>
                    <div style="margin: 0 0 0.45rem;">
                        @include('shares-trans.partials.status-badge', ['posted' => $shareTransLine->posted])
                    </div>
                    <p class="st-summary-label">{{ __('حالة السطر') }}</p>
                </article>
            </section>

            <div class="st-grid-two">
                <section class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-title-wrap">
                            <span class="st-card-icon"><i class="bi bi-sliders"></i></span>
                            <div>
                                <h2 class="st-card-title">{{ __('نموذج التعديل') }}</h2>
                                <p class="st-card-subtitle">{{ __('يمكنك تحديث بيانات السطر وحالته، مع تسجيل سبب التعديل بشكل واضح.') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($isPosted)
                        <div class="st-banner is-warning" style="margin-bottom: 1rem;">
                            <i class="bi bi-lock-fill"></i>
                            <div>{{ __('هذا السطر معتمد حاليًا، لذلك يفضّل مراجعته بعناية قبل إجراء أي تعديل عليه.') }}</div>
                        </div>
                    @endif

                    <form action="{{ route('share-trans-lines.update', $shareTransLine) }}" method="POST" data-line-form data-confirm-message="{{ __('هل أنت متأكد من حفظ التغييرات؟') }}">
                        @csrf
                        @method('PUT')

                        <div class="st-form-grid">
                            <div class="st-form-field">
                                <label for="trans_id" class="st-label">{{ __('رقم المعاملة') }} <span class="st-required">*</span></label>
                                <select name="trans_id" id="trans_id" class="st-select" required>
                                    <option value="">{{ __('اختر المعاملة') }}</option>
                                    @foreach($sharesTrans as $item)
                                        <option value="{{ $item->id }}" {{ (string) old('trans_id', $shareTransLine->trans_id) === (string) $item->id ? 'selected' : '' }}>
                                            #{{ $item->id }} - {{ $item->date?->format('Y-m-d') }} - {{ $item->getTransTypeText() }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('trans_id')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="st-form-field">
                                <label for="contributor_id" class="st-label">{{ __('المساهم') }} <span class="st-required">*</span></label>
                                <select name="contributor_id" id="contributor_id" class="st-select" required>
                                    <option value="">{{ __('اختر المساهم') }}</option>
                                    @foreach($contributors as $contributor)
                                        <option value="{{ $contributor->id }}" {{ (string) old('contributor_id', $shareTransLine->contributor_id) === (string) $contributor->id ? 'selected' : '' }}>
                                            {{ $contributor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('contributor_id')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="st-form-field">
                                <label for="count_debit" class="st-label">{{ __('الخصم') }}</label>
                                <input type="number" name="count_debit" id="count_debit" class="st-input" value="{{ old('count_debit', $shareTransLine->count_debit) }}" step="0.01" min="0">
                                @error('count_debit')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="st-form-field">
                                <label for="count_credit" class="st-label">{{ __('الدائن') }}</label>
                                <input type="number" name="count_credit" id="count_credit" class="st-input" value="{{ old('count_credit', $shareTransLine->count_credit) }}" step="0.01" min="0">
                                @error('count_credit')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="st-form-field">
                                <label for="amount_per_share" class="st-label">{{ __('سعر السهم') }} <span class="st-required">*</span></label>
                                <input type="number" name="amount_per_share" id="amount_per_share" class="st-input" value="{{ old('amount_per_share', $shareTransLine->amount_per_share) }}" step="0.01" min="0" required>
                                @error('amount_per_share')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="st-form-field">
                                <label class="st-label">{{ __('حالة السطر') }}</label>
                                <label class="st-chip" style="justify-content: space-between; cursor: pointer;">
                                    <span style="display: inline-flex; align-items: center; gap: 0.5rem;">
                                        <i class="bi bi-patch-check-fill"></i>
                                        {{ __('اعتماد السطر') }}
                                    </span>
                                    <input type="checkbox" name="posted" value="1" {{ old('posted', $shareTransLine->posted) ? 'checked' : '' }}>
                                </label>
                            </div>

                            <div class="st-form-field full">
                                <label for="line_notes" class="st-label">{{ __('ملاحظات السطر') }}</label>
                                <textarea name="line_notes" id="line_notes" class="st-textarea" placeholder="{{ __('أضف أي ملاحظات مرتبطة بهذا السطر') }}">{{ old('line_notes', $shareTransLine->line_notes) }}</textarea>
                                @error('line_notes')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="st-form-field full">
                                <label for="line_notes_2" class="st-label">{{ __('سبب التعديل') }} <span class="st-required">*</span></label>
                                <textarea name="line_notes_2" id="line_notes_2" class="st-textarea" placeholder="{{ __('اكتب سبب التعديل الذي قمت به') }}">{{ old('line_notes_2') }}</textarea>
                                @error('line_notes_2')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="st-form-footer">
                            <p class="st-form-footer-note">{{ __('آخر تحديث على هذا السطر كان بتاريخ') }} {{ $shareTransLine->updated_at->format('Y-m-d H:i') }}.</p>

                            <div class="st-inline-actions">
                                <button type="submit" class="st-btn st-btn-primary">
                                    <i class="bi bi-check2-circle"></i>
                                    {{ __('حفظ التغييرات') }}
                                </button>
                                <a href="{{ route('share-trans-lines.show', $shareTransLine) }}" class="st-btn st-btn-info">
                                    <i class="bi bi-eye-fill"></i>
                                    {{ __('عرض') }}
                                </a>
                                <a href="{{ route('share-trans-lines.index') }}" class="st-btn st-btn-secondary">
                                    <i class="bi bi-x-circle"></i>
                                    {{ __('إلغاء') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </section>

                <div class="st-shell">
                    <section class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-title-wrap">
                                <span class="st-card-icon"><i class="bi bi-calculator-fill"></i></span>
                                <div>
                                    <h2 class="st-card-title">{{ __('الحسابات المتوقعة') }}</h2>
                                    <p class="st-card-subtitle">{{ __('ملخص فوري يعكس القيم الحالية أثناء التعديل.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="st-summary-grid" style="grid-template-columns: repeat(2, minmax(0, 1fr));">
                            <article class="st-summary-card">
                                <div class="st-summary-icon"><i class="bi bi-hash"></i></div>
                                <p class="st-summary-value" id="calc-shares">{{ number_format($effectiveShares, 2) }}</p>
                                <p class="st-summary-label">{{ __('عدد الأسهم الفعلي') }}</p>
                            </article>
                            <article class="st-summary-card">
                                <div class="st-summary-icon"><i class="bi bi-cash-stack"></i></div>
                                <p class="st-summary-value" id="calc-total">{{ number_format($effectiveTotal, 2) }}</p>
                                <p class="st-summary-label">{{ __('الإجمالي') }} {{ __('ريال') }}</p>
                            </article>
                        </div>

                        <div class="st-info-list" style="margin-top: 1rem;">
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('اتجاه الحركة') }}</span>
                                <div class="st-info-value" id="calc-direction">
                                    {{ (float) $shareTransLine->count_debit > 0 ? __('خصم') : ((float) $shareTransLine->count_credit > 0 ? __('دائن') : __('غير محدد')) }}
                                </div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('سعر السهم') }}</span>
                                <div class="st-info-value"><span id="calc-price">{{ number_format($shareTransLine->amount_per_share, 2) }}</span> {{ __('ريال') }}</div>
                            </div>
                        </div>
                    </section>

                    <section class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-title-wrap">
                                <span class="st-card-icon"><i class="bi bi-info-circle-fill"></i></span>
                                <div>
                                    <h2 class="st-card-title">{{ __('ملاحظات تحرير') }}</h2>
                                    <p class="st-card-subtitle">{{ __('تنبيهات مختصرة قبل حفظ التغييرات.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="st-note-box">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <div>{{ __('لا يمكن إدخال قيم موجبة في الخصم والدائن معًا داخل نفس السطر.') }}</div>
                        </div>
                        <div class="st-note-box" style="margin-top: 0.85rem;">
                            <i class="bi bi-journal-check"></i>
                            <div>{{ __('سبب التعديل مطلوب لتوثيق التغيير ضمن شاشة سجل التعديلات في النظام.') }}</div>
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
            const form = document.querySelector('[data-line-form]');
            const debitInput = document.getElementById('count_debit');
            const creditInput = document.getElementById('count_credit');
            const priceInput = document.getElementById('amount_per_share');
            const calcShares = document.getElementById('calc-shares');
            const calcPrice = document.getElementById('calc-price');
            const calcTotal = document.getElementById('calc-total');
            const calcDirection = document.getElementById('calc-direction');

            function updateCalculations() {
                const debit = parseFloat(debitInput.value) || 0;
                const credit = parseFloat(creditInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const shares = Math.max(debit, credit);
                const total = shares * price;

                calcShares.textContent = shares.toFixed(2);
                calcPrice.textContent = price.toFixed(2);
                calcTotal.textContent = total.toFixed(2);

                if (debit > 0) {
                    calcDirection.textContent = '{{ __("خصم") }}';
                } else if (credit > 0) {
                    calcDirection.textContent = '{{ __("دائن") }}';
                } else {
                    calcDirection.textContent = '{{ __("غير محدد") }}';
                }
            }

            debitInput.addEventListener('input', function () {
                if (this.value) {
                    creditInput.value = '';
                }
                updateCalculations();
            });

            creditInput.addEventListener('input', function () {
                if (this.value) {
                    debitInput.value = '';
                }
                updateCalculations();
            });

            priceInput.addEventListener('input', updateCalculations);

            if (form) {
                form.addEventListener('submit', function (event) {
                    const message = this.getAttribute('data-confirm-message');

                    if (message && !window.confirm(message)) {
                        event.preventDefault();
                    }
                });
            }

            updateCalculations();
        });
    </script>
@endpush
