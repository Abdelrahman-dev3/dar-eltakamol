@extends('layouts.app')

@section('title', __('تعديل عرض البيع'))

@include('sell-shares.partials.styles')

@php
    $sellerName = $sellShare->seller->name ?? $sellShare->seller->user->name ?? __('غير معروف');
    $isLocked = in_array($sellShare->ad_status, [\App\Models\SellShares::AD_STATUS_COMPLETED, \App\Models\SellShares::AD_STATUS_CANCELLED], true);
@endphp

@section('content')
    <div class="ss-page">
        <div class="ss-shell">
            <section class="ss-hero">
                <div class="ss-hero-inner">
                    <div>
                        <span class="ss-badge-top">
                            <i class="bi bi-pencil-square"></i>
                            {{ __('تعديل عرض') }} #{{ $sellShare->id }}
                        </span>
                        <h1 class="ss-title">{{ __('راجع بيانات العرض قبل الحفظ') }}</h1>
                    </div>

                    <div class="ss-actions">
                        <a href="{{ route('sell-shares.show', $sellShare) }}" class="ss-btn ss-btn-info">
                            <i class="bi bi-eye-fill"></i>
                            {{ __('عرض العرض') }}
                        </a>
                        <a href="{{ route('sell-shares.index') }}" class="ss-btn ss-btn-secondary">
                            <i class="bi bi-arrow-right-circle"></i>
                            {{ __('العودة للعروض') }}
                        </a>
                    </div>
                </div>
            </section>

            <section class="ss-summary-grid">
                <article class="ss-summary-card" style="animation-delay: 0.05s;">
                    <div class="ss-summary-icon"><i class="bi bi-person-badge-fill"></i></div>
                    <p class="ss-summary-value">{{ $sellerName }}</p>
                    <p class="ss-summary-label">{{ __('المساهم') }}</p>
                </article>
                <article class="ss-summary-card" style="animation-delay: 0.12s;">
                    <div class="ss-summary-icon"><i class="bi bi-bar-chart-fill"></i></div>
                    <p class="ss-summary-value">{{ number_format($sellShare->count, 0) }}</p>
                    <p class="ss-summary-label">{{ __('عدد الأسهم الحالية') }}</p>
                </article>
                <article class="ss-summary-card" style="animation-delay: 0.19s;">
                    <div class="ss-summary-icon"><i class="bi bi-cash-stack"></i></div>
                    <p class="ss-summary-value">{{ number_format($sellShare->total_amount, 2) }}</p>
                    <p class="ss-summary-label">{{ __('القيمة الحالية') }} {{ __('ريال') }}</p>
                </article>
                <article class="ss-summary-card" style="animation-delay: 0.26s;">
                    <div class="ss-summary-icon"><i class="bi bi-shield-check"></i></div>
                    <div style="margin: 0 0 0.45rem;">
                        @include('sell-shares.partials.status-badge', ['status' => $sellShare->ad_status])
                    </div>
                    <p class="ss-summary-label">{{ __('حالة العرض') }}</p>
                </article>
            </section>

            <div class="ss-grid-two">
                <section class="ss-card">
                    <div class="ss-card-header">
                        <div class="ss-card-title-wrap">
                            <span class="ss-card-icon"><i class="bi bi-sliders"></i></span>
                            <div>
                                <h2 class="ss-card-title">{{ __('نموذج التعديل') }}</h2>
                                <p class="ss-card-subtitle">{{ __('يمكنك تعديل بيانات العرض الأساسية هنا مع تسجيل سبب التغيير في السجل.') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($isLocked)
                        <div class="ss-note-box warning" style="margin-bottom: 1rem;">
                            <i class="bi bi-lock-fill"></i>
                            <div>{{ __('هذا العرض في حالة لا تسمح بالتعديل حاليًا، لذلك تم تعطيل الحقول للحفاظ على سلامة البيانات.') }}</div>
                        </div>
                    @endif

                    <form action="{{ route('sell-shares.update', $sellShare) }}" method="POST" data-sell-share-form data-confirm-message="{{ __('هل أنت متأكد من حفظ التغييرات؟') }}">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="user_id" id="user_id" value="{{ $sellShare->seller->id }}">
                        <input type="hidden" name="input_count" id="input_count" value="">

                        <div class="ss-form-grid">
                            <div class="ss-form-field">
                                <label class="ss-label">{{ __('المساهم') }}</label>
                                <div class="ss-note-box">
                                    <i class="bi bi-person-fill"></i>
                                    <div>
                                        <strong>{{ $sellerName }}</strong><br>
                                        <span class="ss-help">{{ __('لا يمكن تغيير المساهم بعد إنشاء العرض.') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="ss-form-field">
                                <label class="ss-label">{{ __('حالة العرض الحالية') }}</label>
                                <div class="ss-note-box success">
                                    <i class="bi bi-flag-fill"></i>
                                    <div>@include('sell-shares.partials.status-badge', ['status' => $sellShare->ad_status])</div>
                                </div>
                            </div>

                            <div class="ss-form-field">
                                <label for="count" class="ss-label">{{ __('عدد الأسهم المراد بيعها') }} <span class="ss-required">*</span></label>
                                <input type="number" name="count" id="count" class="ss-input" value="{{ old('count', $sellShare->count) }}" min="1" step="0.01" required placeholder="{{ __('أدخل عدد الأسهم') }}" @disabled($isLocked)>
                                @error('count')
                                    <span class="ss-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="ss-form-field">
                                <label for="amount_per_share" class="ss-label">{{ __('السعر لكل سهم') }} <span class="ss-required">*</span></label>
                                <input type="number" name="amount_per_share" id="amount_per_share" class="ss-input" value="{{ old('amount_per_share', $sellShare->amount_per_share) }}" min="0.01" step="0.01" required placeholder="{{ __('أدخل السعر لكل سهم') }}" @disabled($isLocked)>
                                @error('amount_per_share')
                                    <span class="ss-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="ss-form-field">
                                <label for="end_date" class="ss-label">{{ __('تاريخ انتهاء العرض') }}</label>
                                <input type="date" name="end_date" id="end_date" class="ss-input" value="{{ old('end_date', $sellShare->end_date ? $sellShare->end_date->format('Y-m-d') : '') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" @disabled($isLocked)>
                                @error('end_date')
                                    <span class="ss-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="ss-form-field full">
                                <label for="notes" class="ss-label">{{ __('ملاحظات') }}</label>
                                <textarea name="notes" id="notes" class="ss-textarea" placeholder="{{ __('أدخل ملاحظات إضافية حول عرض البيع') }}" @disabled($isLocked)>{{ old('notes', $sellShare->notes) }}</textarea>
                                @error('notes')
                                    <span class="ss-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="ss-form-field full">
                                <label for="line_notes" class="ss-label">{{ __('سبب التعديل') }} <span class="ss-required">*</span></label>
                                <textarea name="line_notes" id="line_notes" class="ss-textarea" placeholder="{{ __('اكتب سبب التعديل الذي قمت به') }}" {{ $isLocked ? 'disabled' : '' }}>{{ old('line_notes') }}</textarea>
                                <span class="ss-help">{{ __('هذا الحقل مطلوب لتوثيق سبب التعديل في سجل التغييرات.') }}</span>
                                @error('line_notes')
                                    <span class="ss-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="ss-form-footer">
                            <p class="ss-form-footer-note">
                                {{ __('آخر تحديث على العرض كان بتاريخ') }} {{ $sellShare->updated_at->format('Y-m-d H:i') }}.
                            </p>

                            <div class="ss-inline-actions">
                                @if(!$isLocked)
                                    <button type="submit" class="ss-btn ss-btn-primary">
                                        <i class="bi bi-check2-circle"></i>
                                        {{ __('حفظ التغييرات') }}
                                    </button>
                                @else
                                    <button type="button" class="ss-btn ss-btn-secondary" disabled>
                                        <i class="bi bi-lock-fill"></i>
                                        {{ __('التعديل غير متاح') }}
                                    </button>
                                @endif
                                <a href="{{ route('sell-shares.show', $sellShare) }}" class="ss-btn ss-btn-info">
                                    <i class="bi bi-eye-fill"></i>
                                    {{ __('عرض') }}
                                </a>
                                <a href="{{ route('sell-shares.index') }}" class="ss-btn ss-btn-secondary">
                                    <i class="bi bi-x-circle"></i>
                                    {{ __('إلغاء') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </section>

                <div class="ss-shell">
                    <section class="ss-card">
                        <div class="ss-card-header">
                            <div class="ss-card-title-wrap">
                                <span class="ss-card-icon"><i class="bi bi-clipboard-data-fill"></i></span>
                                <div>
                                    <h2 class="ss-card-title">{{ __('الأسهم والملخص') }}</h2>
                                    <p class="ss-card-subtitle">{{ __('إحصاءات مباشرة مرتبطة بنفس العرض الحالي.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="ss-availability-grid">
                            <div class="ss-availability-card">
                                <span class="ss-availability-label">{{ __('الأسهم المتاحة للبيع') }}</span>
                                <span class="ss-availability-value" id="available-shares-value">0</span>
                            </div>
                            <div class="ss-availability-card">
                                <span class="ss-availability-label">{{ __('إجمالي الأسهم') }}</span>
                                <span class="ss-availability-value" id="total-shares-value">0</span>
                            </div>
                        </div>

                        <div class="ss-grid-three" style="margin-top: 1rem;">
                            <div class="ss-info-item">
                                <span class="ss-info-label">{{ __('عدد الأسهم') }}</span>
                                <div class="ss-info-value" id="display-count">{{ number_format($sellShare->count, 0) }}</div>
                            </div>
                            <div class="ss-info-item">
                                <span class="ss-info-label">{{ __('السعر لكل سهم') }}</span>
                                <div class="ss-info-value"><span id="display-price">{{ number_format($sellShare->amount_per_share, 2) }}</span> {{ __('ريال') }}</div>
                            </div>
                            <div class="ss-info-item">
                                <span class="ss-info-label">{{ __('القيمة الإجمالية') }}</span>
                                <div class="ss-info-value"><span id="display-total">{{ number_format($sellShare->total_amount, 2) }}</span> {{ __('ريال') }}</div>
                            </div>
                        </div>
                    </section>

                    <section class="ss-card">
                        <div class="ss-card-header">
                            <div class="ss-card-title-wrap">
                                <span class="ss-card-icon"><i class="bi bi-clock-history"></i></span>
                                <div>
                                    <h2 class="ss-card-title">{{ __('معلومات إضافية') }}</h2>
                                    <p class="ss-card-subtitle">{{ __('تفاصيل زمنية وحالة الإدراج للمراجعة السريعة.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="ss-info-list">
                            <div class="ss-info-item">
                                <span class="ss-info-label">{{ __('تاريخ الإنشاء') }}</span>
                                <div class="ss-info-value">{{ $sellShare->created_at->format('Y-m-d H:i') }}</div>
                            </div>
                            <div class="ss-info-item">
                                <span class="ss-info-label">{{ __('آخر تحديث') }}</span>
                                <div class="ss-info-value">{{ $sellShare->updated_at->format('Y-m-d H:i') }}</div>
                            </div>
                            <div class="ss-info-item">
                                <span class="ss-info-label">{{ __('تاريخ الإدراج') }}</span>
                                <div class="ss-info-value">{{ $sellShare->insert_date->format('Y-m-d H:i') }}</div>
                            </div>
                            <div class="ss-info-item">
                                <span class="ss-info-label">{{ __('الانتهاء') }}</span>
                                <div class="ss-info-value {{ $sellShare->end_date ? '' : 'muted' }}">
                                    {{ $sellShare->end_date ? $sellShare->end_date->format('Y-m-d') : __('بدون تاريخ انتهاء') }}
                                </div>
                            </div>
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
            const userId = document.getElementById('user_id').value;
            const countInput = document.getElementById('count');
            const priceInput = document.getElementById('amount_per_share');
            const form = document.querySelector('[data-sell-share-form]');
            const inputCount = document.getElementById('input_count');
            const availableSharesValue = document.getElementById('available-shares-value');
            const totalSharesValue = document.getElementById('total-shares-value');
            const displayCount = document.getElementById('display-count');
            const displayPrice = document.getElementById('display-price');
            const displayTotal = document.getElementById('display-total');
            const isLocked = @json($isLocked);

            function updateSummary() {
                const count = parseFloat(countInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                displayCount.textContent = count.toLocaleString();
                displayPrice.textContent = price.toFixed(2);
                displayTotal.textContent = (count * price).toFixed(2);
            }

            function loadContributorInfo() {
                fetch(`/contributors/share/${userId}`)
                    .then(function (response) {
                        if (!response.ok) {
                            throw new Error('Fetch failed');
                        }

                        return response.json();
                    })
                    .then(function (data) {
                        const available = Number(data.available_shares ?? 0);
                        const total = Number(data.total_shares ?? 0);

                        availableSharesValue.textContent = available.toLocaleString();
                        totalSharesValue.textContent = total.toLocaleString();
                        inputCount.value = available;
                        countInput.setAttribute('max', available);
                    })
                    .catch(function () {
                        availableSharesValue.textContent = '0';
                        totalSharesValue.textContent = '0';
                        inputCount.value = '';
                    });
            }

            [countInput, priceInput].forEach(function (input) {
                if (input) {
                    input.addEventListener('input', updateSummary);
                }
            });

            if (form) {
                form.addEventListener('submit', function (event) {
                    if (isLocked) {
                        event.preventDefault();
                        return;
                    }

                    const count = parseFloat(countInput.value);
                    const price = parseFloat(priceInput.value);
                    const message = this.getAttribute('data-confirm-message');

                    if (!count || count <= 0 || !price || price <= 0) {
                        return;
                    }

                    if (message && !window.confirm(
                        message + '\n\n' +
                        '{{ __("عدد الأسهم") }}: ' + count + '\n' +
                        '{{ __("السعر لكل سهم") }}: ' + price.toFixed(2) + ' {{ __("ريال") }}\n' +
                        '{{ __("المبلغ الإجمالي") }}: ' + (count * price).toFixed(2) + ' {{ __("ريال") }}'
                    )) {
                        event.preventDefault();
                    }
                });
            }

            loadContributorInfo();
            updateSummary();
        });
    </script>
@endpush
