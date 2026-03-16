@extends('layouts.app')

@section('title', __('إضافة عرض بيع أسهم'))

@include('sell-shares.partials.styles')

@section('content')
    <div class="ss-page">
        <div class="ss-shell">
            <section class="ss-hero">
                <div class="ss-hero-inner">
                    <div>
                        <span class="ss-badge-top">
                            <i class="bi bi-plus-circle-fill"></i>
                            {{ __('إضافة عرض بيع') }}
                        </span>
                        <h1 class="ss-title">{{ __('أنشئ عرض بيع واضح وجاهز للمراجعة') }}</h1>
                        <p class="ss-subtitle">
                            {{ __('اختر المساهم وحدد الكمية والسعر وتاريخ الانتهاء إن رغبت، مع ملخص فوري للقيمة الإجمالية ومؤشرات الأسهم المتاحة للبيع.') }}
                        </p>
                    </div>

                    <div class="ss-actions">
                        <a href="{{ route('sell-shares.index') }}" class="ss-btn ss-btn-secondary">
                            <i class="bi bi-arrow-right-circle"></i>
                            {{ __('العودة للعروض') }}
                        </a>
                    </div>
                </div>
            </section>

            <div class="ss-grid-two">
                <section class="ss-card">
                    <div class="ss-card-header">
                        <div class="ss-card-title-wrap">
                            <span class="ss-card-icon"><i class="bi bi-pencil-square"></i></span>
                            <div>
                                <h2 class="ss-card-title">{{ __('بيانات العرض') }}</h2>
                                <p class="ss-card-subtitle">{{ __('نموذج متجاوب يحافظ على وضوح الحقول مع تكبير وتصغير الخط من الإعدادات العامة.') }}</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('sell-shares.store') }}" method="POST" data-sell-share-form data-confirm-message="{{ __('هل أنت متأكد من إنشاء عرض البيع؟') }}">
                        @csrf

                        <div class="ss-form-grid">
                            <div class="ss-form-field">
                                <label for="user_id" class="ss-label">{{ __('المساهم') }} <span class="ss-required">*</span></label>
                                <select name="user_id" id="user_id" class="ss-select" required>
                                    <option value="">{{ __('اختر المساهم') }}</option>
                                    @foreach($contributors as $contributor)
                                        <option value="{{ $contributor->id }}" {{ old('user_id') == $contributor->id ? 'selected' : '' }}>
                                            {{ $contributor->name ?? $contributor->user->name ?? __('غير معروف') }}
                                            @if($contributor->share_count_cr)
                                                ({{ number_format($contributor->share_count_cr, 0) }} {{ __('سهم') }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <span class="ss-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="ss-form-field">
                                <label for="count" class="ss-label">{{ __('عدد الأسهم المراد بيعها') }} <span class="ss-required">*</span></label>
                                <input type="number" name="count" id="count" class="ss-input" value="{{ old('count') }}" min="1" step="0.01" required placeholder="{{ __('أدخل عدد الأسهم') }}">
                                @error('count')
                                    <span class="ss-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="ss-form-field">
                                <label for="amount_per_share" class="ss-label">{{ __('السعر لكل سهم') }} <span class="ss-required">*</span></label>
                                <input type="number" name="amount_per_share" id="amount_per_share" class="ss-input" value="{{ old('amount_per_share') }}" min="0.01" step="0.01" required placeholder="{{ __('أدخل السعر لكل سهم') }}">
                                @error('amount_per_share')
                                    <span class="ss-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="ss-form-field">
                                <label for="end_date" class="ss-label">{{ __('تاريخ انتهاء العرض') }}</label>
                                <input type="date" name="end_date" id="end_date" class="ss-input" value="{{ old('end_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                <span class="ss-help">{{ __('إذا لم تحدد تاريخ انتهاء فسيبقى العرض نشطًا حتى يتم تحديث حالته أو إلغاؤه.') }}</span>
                                @error('end_date')
                                    <span class="ss-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="ss-form-field full">
                                <label for="notes" class="ss-label">{{ __('ملاحظات') }}</label>
                                <textarea name="notes" id="notes" class="ss-textarea" placeholder="{{ __('أدخل أي ملاحظات إضافية حول عرض البيع') }}">{{ old('notes') }}</textarea>
                                <span class="ss-help">{{ __('وصف مختصر يساعد في توضيح شروط العرض أو ملاحظاته للمراجعة لاحقًا.') }}</span>
                                @error('notes')
                                    <span class="ss-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <input type="hidden" name="input_count" id="input_count" value="">

                        <div class="ss-form-footer">
                            <p class="ss-form-footer-note">
                                {{ __('سيتم إنشاء العرض بحالة مبدئية، ويمكن تعديله لاحقًا قبل انتقاله إلى حالة أخرى.') }}
                            </p>

                            <div class="ss-inline-actions">
                                <button type="submit" class="ss-btn ss-btn-primary">
                                    <i class="bi bi-check2-circle"></i>
                                    {{ __('حفظ العرض') }}
                                </button>
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
                                <span class="ss-card-icon"><i class="bi bi-bar-chart-steps"></i></span>
                                <div>
                                    <h2 class="ss-card-title">{{ __('الأسهم المتاحة والملخص') }}</h2>
                                    <p class="ss-card-subtitle">{{ __('يتحدث هذا الملخص مباشرة عند اختيار المساهم أو تعديل الكمية والسعر.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="ss-availability-grid" id="contributor-info">
                            <div class="ss-availability-card">
                                <span class="ss-availability-label">{{ __('الأسهم المتاحة للبيع') }}</span>
                                <span class="ss-availability-value" id="available-shares-value">0</span>
                            </div>
                            <div class="ss-availability-card">
                                <span class="ss-availability-label">{{ __('إجمالي الأسهم') }}</span>
                                <span class="ss-availability-value" id="total-shares-value">0</span>
                            </div>
                        </div>

                        <div class="ss-note-box success" style="margin-top: 1rem;">
                            <i class="bi bi-cash-coin"></i>
                            <div>
                                <strong>{{ __('القيمة الإجمالية الحالية') }}:</strong>
                                <span id="display-total">0.00</span> {{ __('ريال') }}
                            </div>
                        </div>

                        <div class="ss-grid-three" style="margin-top: 1rem;">
                            <div class="ss-info-item">
                                <span class="ss-info-label">{{ __('عدد الأسهم') }}</span>
                                <div class="ss-info-value" id="display-count">0</div>
                            </div>
                            <div class="ss-info-item">
                                <span class="ss-info-label">{{ __('السعر لكل سهم') }}</span>
                                <div class="ss-info-value"><span id="display-price">0.00</span> {{ __('ريال') }}</div>
                            </div>
                            <div class="ss-info-item">
                                <span class="ss-info-label">{{ __('حالة الإدراج') }}</span>
                                <div class="ss-info-value muted">{{ __('سيبدأ العرض بحالة مبدئية') }}</div>
                            </div>
                        </div>
                    </section>

                    <section class="ss-card">
                        <div class="ss-card-header">
                            <div class="ss-card-title-wrap">
                                <span class="ss-card-icon"><i class="bi bi-info-circle-fill"></i></span>
                                <div>
                                    <h2 class="ss-card-title">{{ __('ملاحظات مهمة') }}</h2>
                                    <p class="ss-card-subtitle">{{ __('إرشادات مختصرة قبل نشر العرض داخل النظام.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="ss-note-box">
                            <i class="bi bi-check-circle-fill"></i>
                            <div>{{ __('تأكد أن عدد الأسهم المطلوب لا يتجاوز المتاح للبيع حسب سياسة النظام الحالية.') }}</div>
                        </div>
                        <div class="ss-note-box warning" style="margin-top: 0.85rem;">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <div>{{ __('السعر لكل سهم يجب أن يكون منطقيًا وواضحًا لأن المشتري سيراه عند استعراض العرض.') }}</div>
                        </div>
                        <div class="ss-note-box success" style="margin-top: 0.85rem;">
                            <i class="bi bi-stars"></i>
                            <div>{{ __('تم تصميم الصفحة لتبقى مريحة على الجوال وسطح المكتب ومع أوضاع الثيم المختلفة.') }}</div>
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
            const userSelect = document.getElementById('user_id');
            const countInput = document.getElementById('count');
            const priceInput = document.getElementById('amount_per_share');
            const form = document.querySelector('[data-sell-share-form]');
            const inputCount = document.getElementById('input_count');
            const availableSharesValue = document.getElementById('available-shares-value');
            const totalSharesValue = document.getElementById('total-shares-value');
            const displayCount = document.getElementById('display-count');
            const displayPrice = document.getElementById('display-price');
            const displayTotal = document.getElementById('display-total');

            function updateSummary() {
                const count = parseFloat(countInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const total = count * price;

                displayCount.textContent = count.toLocaleString();
                displayPrice.textContent = price.toFixed(2);
                displayTotal.textContent = total.toFixed(2);
            }

            function resetContributorInfo() {
                availableSharesValue.textContent = '0';
                totalSharesValue.textContent = '0';
                inputCount.value = '';
                countInput.removeAttribute('max');
            }

            function loadContributorInfo(userId) {
                if (!userId) {
                    resetContributorInfo();
                    return;
                }

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
                        resetContributorInfo();
                    });
            }

            if (userSelect) {
                userSelect.addEventListener('change', function () {
                    loadContributorInfo(this.value);
                });
            }

            [countInput, priceInput].forEach(function (input) {
                if (input) {
                    input.addEventListener('input', updateSummary);
                }
            });

            if (form) {
                form.addEventListener('submit', function (event) {
                    const count = parseFloat(countInput.value);
                    const price = parseFloat(priceInput.value);
                    const userId = userSelect.value;
                    const message = this.getAttribute('data-confirm-message');

                    if (!userId || !count || count <= 0 || !price || price <= 0) {
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

            loadContributorInfo(userSelect.value);
            updateSummary();
        });
    </script>
@endpush
