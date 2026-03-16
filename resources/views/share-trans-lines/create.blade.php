@extends('layouts.app')

@section('title', __('إضافة تفصيل معاملة أسهم جديد'))

@include('shares-trans.partials.styles')

@section('content')
    <div class="st-page">
        <div class="st-shell">
            <section class="st-hero">
                <div class="st-hero-inner">
                    <div>
                        <span class="st-hero-badge">
                            <i class="bi bi-node-plus-fill"></i>
                            {{ __('إضافة تفصيل جديد') }}
                        </span>
                        <h1 class="st-hero-title">{{ __('أضف سطرًا جديدًا داخل معاملة الأسهم') }}</h1>
                        <p class="st-hero-subtitle">
                            {{ __('اختر المعاملة والمساهم وحدد الخصم أو الدائن مع سعر السهم، وسيتم تحديث الملخص الحسابي مباشرة داخل الصفحة بشكل متجاوب مع الثيم وحجم الخط.') }}
                        </p>
                    </div>

                    <div class="st-hero-actions">
                        @if($selectedTransId)
                            <a href="{{ route('shares-trans.show', $selectedTransId) }}" class="st-btn st-btn-info">
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

            <div class="st-grid-two">
                <section class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-title-wrap">
                            <span class="st-card-icon"><i class="bi bi-pencil-square"></i></span>
                            <div>
                                <h2 class="st-card-title">{{ __('بيانات السطر') }}</h2>
                                <p class="st-card-subtitle">{{ __('أدخل بيانات سطر المعاملة بدقة. لا يمكن تعبئة الخصم والدائن معًا في نفس السطر.') }}</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('share-trans-lines.store') }}" method="POST" data-line-form data-confirm-message="{{ __('هل أنت متأكد من حفظ تفصيل المعاملة؟') }}">
                        @csrf

                        <div class="st-form-grid">
                            <div class="st-form-field">
                                <label for="trans_id" class="st-label">{{ __('رقم المعاملة') }} <span class="st-required">*</span></label>
                                <select name="trans_id" id="trans_id" class="st-select" required>
                                    <option value="">{{ __('اختر المعاملة') }}</option>
                                    @foreach($sharesTrans as $transaction)
                                        <option value="{{ $transaction->id }}" {{ (string) old('trans_id', $selectedTransId) === (string) $transaction->id ? 'selected' : '' }}>
                                            #{{ $transaction->id }} - {{ $transaction->date?->format('Y-m-d') }} - {{ $transaction->getTransTypeText() }}
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
                                        <option value="{{ $contributor->id }}" {{ old('contributor_id') == $contributor->id ? 'selected' : '' }}>
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
                                <input type="number" name="count_debit" id="count_debit" class="st-input" value="{{ old('count_debit') }}" step="0.01" min="0" placeholder="{{ __('عدد الأسهم المخصومة') }}">
                                @error('count_debit')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="st-form-field">
                                <label for="count_credit" class="st-label">{{ __('الدائن') }}</label>
                                <input type="number" name="count_credit" id="count_credit" class="st-input" value="{{ old('count_credit') }}" step="0.01" min="0" placeholder="{{ __('عدد الأسهم الدائنة') }}">
                                @error('count_credit')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="st-form-field">
                                <label for="amount_per_share" class="st-label">{{ __('سعر السهم') }} <span class="st-required">*</span></label>
                                <input type="number" name="amount_per_share" id="amount_per_share" class="st-input" value="{{ old('amount_per_share') }}" step="0.01" min="0" required placeholder="{{ __('أدخل سعر السهم') }}">
                                @error('amount_per_share')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="st-form-field">
                                <label class="st-label">{{ __('حالة السطر') }}</label>
                                <label class="st-chip" style="justify-content: space-between; cursor: pointer;">
                                    <span style="display: inline-flex; align-items: center; gap: 0.5rem;">
                                        <i class="bi bi-patch-check-fill"></i>
                                        {{ __('اعتماد السطر مباشرة') }}
                                    </span>
                                    <input type="checkbox" name="posted" value="1" {{ old('posted') ? 'checked' : '' }}>
                                </label>
                            </div>

                            <div class="st-form-field full">
                                <label for="line_notes" class="st-label">{{ __('ملاحظات السطر') }}</label>
                                <textarea name="line_notes" id="line_notes" class="st-textarea" placeholder="{{ __('أضف أي ملاحظة توضيحية مرتبطة بهذا السطر') }}">{{ old('line_notes') }}</textarea>
                                @error('line_notes')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="st-form-footer">
                            <p class="st-form-footer-note">{{ __('يفضّل مراجعة نوع الحركة وقيمتها قبل الحفظ، خاصة عند الربط مع معاملة معتمدة أو قيد المراجعة.') }}</p>

                            <div class="st-inline-actions">
                                <button type="submit" class="st-btn st-btn-primary">
                                    <i class="bi bi-check2-circle"></i>
                                    {{ __('حفظ السطر') }}
                                </button>
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
                                    <h2 class="st-card-title">{{ __('الملخص الحسابي') }}</h2>
                                    <p class="st-card-subtitle">{{ __('حساب فوري لعدد الأسهم الفعّال وقيمة السطر الإجمالية.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="st-summary-grid" style="grid-template-columns: repeat(2, minmax(0, 1fr));">
                            <article class="st-summary-card">
                                <div class="st-summary-icon"><i class="bi bi-hash"></i></div>
                                <p class="st-summary-value" id="calc-shares">0.00</p>
                                <p class="st-summary-label">{{ __('عدد الأسهم الفعلي') }}</p>
                            </article>
                            <article class="st-summary-card">
                                <div class="st-summary-icon"><i class="bi bi-cash-stack"></i></div>
                                <p class="st-summary-value" id="calc-total">0.00</p>
                                <p class="st-summary-label">{{ __('الإجمالي') }} {{ __('ريال') }}</p>
                            </article>
                        </div>

                        <div class="st-info-list" style="margin-top: 1rem;">
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('نوع الحركة الحالي') }}</span>
                                <div class="st-info-value" id="calc-direction">{{ __('لم يتم تحديده بعد') }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('سعر السهم') }}</span>
                                <div class="st-info-value"><span id="calc-price">0.00</span> {{ __('ريال') }}</div>
                            </div>
                        </div>
                    </section>

                    <section class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-title-wrap">
                                <span class="st-card-icon"><i class="bi bi-info-circle-fill"></i></span>
                                <div>
                                    <h2 class="st-card-title">{{ __('تنبيهات مهمة') }}</h2>
                                    <p class="st-card-subtitle">{{ __('إرشادات سريعة للحفاظ على صحة بيانات السطر.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="st-note-box">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <div>{{ __('يمكنك إدخال قيمة في الخصم أو الدائن فقط، وليس الاثنين معًا.') }}</div>
                        </div>
                        <div class="st-note-box" style="margin-top: 0.85rem;">
                            <i class="bi bi-link-45deg"></i>
                            <div>{{ __('اختيار المعاملة الصحيحة مهم لأن هذا السطر سيظهر داخل شاشة المعاملة الأم مباشرة.') }}</div>
                        </div>
                        <div class="st-note-box" style="margin-top: 0.85rem;">
                            <i class="bi bi-stars"></i>
                            <div>{{ __('تم ضبط التصميم ليبقى منسجمًا مع صفحات المعاملات الأساسية في أوضاع الثيم المختلفة.') }}</div>
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

            function calculateTotal() {
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
                    calcDirection.textContent = '{{ __("لم يتم تحديده بعد") }}';
                }
            }

            debitInput.addEventListener('input', function () {
                if (this.value) {
                    creditInput.value = '';
                }
                calculateTotal();
            });

            creditInput.addEventListener('input', function () {
                if (this.value) {
                    debitInput.value = '';
                }
                calculateTotal();
            });

            priceInput.addEventListener('input', calculateTotal);

            if (form) {
                form.addEventListener('submit', function (event) {
                    const message = this.getAttribute('data-confirm-message');

                    if (message && !window.confirm(message)) {
                        event.preventDefault();
                    }
                });
            }

            calculateTotal();
        });
    </script>
@endpush
