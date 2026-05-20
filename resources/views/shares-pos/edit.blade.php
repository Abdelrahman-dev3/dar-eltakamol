@extends('layouts.app')

@section('title', __('تعديل طلب الشراء'))

@include('shares-pos.partials.styles')

@php
    $recentOffers = $sellShares->take(5);
@endphp

@section('content')
    <div class="st-page">
        <div class="st-shell">
            <section class="st-hero">
                <div class="st-hero-inner">
                    <div>
                        <span class="st-hero-badge">
                            <i class="bi bi-pencil-square"></i>
                            {{ __('تعديل طلب الشراء') }} #{{ $sharesPO->id }}
                        </span>
                        <h1 class="st-hero-title">{{ __('حدّث بيانات طلب الشراء بثقة') }}</h1>
                    </div>

                    <div class="st-hero-actions">
                        <a href="{{ route('shares-pos.show', $sharesPO) }}" class="st-btn st-btn-info">
                            <i class="bi bi-eye-fill"></i>
                            {{ __('عرض الطلب') }}
                        </a>
                        <a href="{{ route('shares-pos.index') }}" class="st-btn st-btn-secondary">
                            <i class="bi bi-arrow-right-circle"></i>
                            {{ __('العودة للطلبات') }}
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
                                <p class="st-card-subtitle">{{ __('يمكن تعديل جميع بيانات الطلب مع الاحتفاظ بسجل سبب التعديل كما هو معمول به في النظام.') }}</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('shares-pos.update', ['shares_po' => $sharesPO->id]) }}" method="POST" data-order-form data-confirm-message="{{ __('هل أنت متأكد من حفظ تعديلات طلب الشراء؟') }}">
                        @csrf
                        @method('PUT')

                        <div class="st-form-grid">
                            <div class="st-form-field">
                                <label for="user_id" class="st-label">{{ __('المساهم') }} <span class="st-required">*</span></label>
                                <select name="user_id" id="user_id" class="st-select" required>
                                    <option value="">{{ __('اختر المساهم') }}</option>
                                    @foreach($contributors as $contributor)
                                        <option value="{{ $contributor->id }}" {{ old('user_id', $sharesPO->user_id) == $contributor->id ? 'selected' : '' }}>
                                            {{ $contributor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="st-form-field">
                                <label for="sale_number" class="st-label">{{ __('رقم العرض') }}</label>
                                <input type="text" name="sale_number" id="sale_number" class="st-input" value="{{ old('sale_number', $sharesPO->sale_number) }}" list="sellSharesNumbers" placeholder="{{ __('اكتب رقم العرض أو اختره من الاقتراحات') }}">
                                <datalist id="sellSharesNumbers">
                                    @foreach($sellShares as $offer)
                                        <option value="{{ $offer->id }}">{{ $offer->seller->name ?? __('مساهم غير معروف') }}</option>
                                    @endforeach
                                </datalist>
                                @error('sale_number')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="st-form-field">
                                <label for="count" class="st-label">{{ __('الكمية') }} <span class="st-required">*</span></label>
                                <input type="number" name="count" id="count" class="st-input" value="{{ old('count', $sharesPO->count) }}" step="0.01" min="0" required placeholder="{{ __('عدد الأسهم المطلوبة') }}">
                                @error('count')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="st-form-field">
                                <label for="amount_per_share" class="st-label">{{ __('السعر لكل سهم') }} <span class="st-required">*</span></label>
                                <input type="number" name="amount_per_share" id="amount_per_share" class="st-input" value="{{ old('amount_per_share', $sharesPO->amount_per_share) }}" step="0.01" min="0" required placeholder="{{ __('أدخل سعر السهم') }}">
                                <span class="st-help">{{ __('يجب ألا يقل سعر السهم عن') }} {{ number_format($stock, 2) }} {{ __('ريال') }}</span>
                                @error('amount_per_share')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="st-form-field">
                                <label for="insert_date" class="st-label">{{ __('تاريخ الإدراج') }} <span class="st-required">*</span></label>
                                <input type="date" name="insert_date" id="insert_date" class="st-input" value="{{ old('insert_date', $sharesPO->insert_date?->format('Y-m-d')) }}" required>
                                @error('insert_date')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="st-form-field">
                                <label for="po_status" class="st-label">{{ __('حالة الطلب') }} <span class="st-required">*</span></label>
                                <select name="po_status" id="po_status" class="st-select" required>
                                    <option value="{{ \App\Models\SharesPO::PO_STATUS_PENDING }}" {{ old('po_status', $sharesPO->po_status) == \App\Models\SharesPO::PO_STATUS_PENDING ? 'selected' : '' }}>{{ __('في الانتظار') }}</option>
                                    <option value="{{ \App\Models\SharesPO::PO_STATUS_REVIEW }}" {{ old('po_status', $sharesPO->po_status) == \App\Models\SharesPO::PO_STATUS_REVIEW ? 'selected' : '' }}>{{ __('قيد المراجعة') }}</option>
                                    <option value="{{ \App\Models\SharesPO::PO_STATUS_COMPLETED }}" {{ old('po_status', $sharesPO->po_status) == \App\Models\SharesPO::PO_STATUS_COMPLETED ? 'selected' : '' }}>{{ __('مكتمل') }}</option>
                                    <option value="{{ \App\Models\SharesPO::PO_STATUS_REJECTED }}" {{ old('po_status', $sharesPO->po_status) == \App\Models\SharesPO::PO_STATUS_REJECTED ? 'selected' : '' }}>{{ __('مرفوض') }}</option>
                                </select>
                                @error('po_status')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="st-form-field full">
                                <label for="line_notes" class="st-label">{{ __('أسباب التعديل') }} <span class="st-required">*</span></label>
                                <textarea name="line_notes" id="line_notes" class="st-textarea" required placeholder="{{ __('اكتب سبب التعديل ليتم حفظه في سجل التعديلات') }}">{{ old('line_notes') }}</textarea>
                                @error('line_notes')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="st-form-field full">
                                <label class="st-label">{{ __('قبول الطلب') }}</label>
                                <label class="st-chip" style="justify-content: space-between; cursor: pointer;">
                                    <span style="display: inline-flex; align-items: center; gap: 0.5rem;">
                                        <i class="bi bi-check-circle-fill"></i>
                                        {{ __('الطلب مقبول') }}
                                    </span>
                                    <input type="checkbox" name="accept" value="1" {{ old('accept', $sharesPO->accept) ? 'checked' : '' }}>
                                </label>
                            </div>
                        </div>

                        <div class="st-form-footer">
                            <p class="st-form-footer-note">{{ __('أي تعديل على السعر أو الكمية سينعكس مباشرة على القيمة الإجمالية للطلب داخل القوائم وصفحة العرض.') }}</p>

                            <div class="st-inline-actions">
                                <button type="submit" class="st-btn st-btn-primary">
                                    <i class="bi bi-check2-circle"></i>
                                    {{ __('حفظ التعديلات') }}
                                </button>
                                <a href="{{ route('shares-pos.show', $sharesPO) }}" class="st-btn st-btn-info">
                                    <i class="bi bi-eye-fill"></i>
                                    {{ __('عرض') }}
                                </a>
                                <a href="{{ route('shares-pos.index') }}" class="st-btn st-btn-secondary">
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
                            <div class="st-summary-icon"><i class="bi bi-bar-chart-fill"></i></div>
                            <p class="st-summary-value" id="calc-count">{{ number_format($sharesPO->count, 2) }}</p>
                            <p class="st-summary-label">{{ __('الكمية الحالية') }}</p>
                        </article>
                        <article class="st-summary-card">
                            <div class="st-summary-icon"><i class="bi bi-cash-stack"></i></div>
                            <p class="st-summary-value" id="calc-total">{{ number_format($sharesPO->total_amount, 2) }}</p>
                            <p class="st-summary-label">{{ __('القيمة الحالية') }} {{ __('ريال') }}</p>
                        </article>
                    </section>

                    <section class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-title-wrap">
                                <span class="st-card-icon"><i class="bi bi-clock-history"></i></span>
                                <div>
                                    <h2 class="st-card-title">{{ __('سجل زمني') }}</h2>
                                    <p class="st-card-subtitle">{{ __('تواريخ مهمة مرتبطة بإنشاء الطلب وآخر تحديث عليه.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="st-info-list">
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('تاريخ الإنشاء') }}</span>
                                <div class="st-info-value">{{ $sharesPO->created_at->format('Y-m-d H:i') }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('آخر تحديث') }}</span>
                                <div class="st-info-value">{{ $sharesPO->updated_at->format('Y-m-d H:i') }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('الحالة الحالية') }}</span>
                                <div class="st-info-value">@include('shares-pos.partials.status-badge', ['status' => $sharesPO->po_status])</div>
                            </div>
                        </div>
                    </section>

                    <section class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-title-wrap">
                                <span class="st-card-icon"><i class="bi bi-tags-fill"></i></span>
                                <div>
                                    <h2 class="st-card-title">{{ __('عروض بيع متاحة') }}</h2>
                                    <p class="st-card-subtitle">{{ __('اقتراحات سريعة في حال رغبت في تعديل الربط الحالي.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="st-info-list">
                            @forelse($recentOffers as $offer)
                                <div class="st-info-item">
                                    <span class="st-info-label">{{ __('العرض') }} #{{ $offer->id }}</span>
                                    <div class="st-info-value">
                                        {{ $offer->seller->name ?? __('مساهم غير معروف') }}
                                        <span class="st-help" style="display: block;">
                                            {{ number_format($offer->count, 0) }} {{ __('سهم') }} | {{ number_format($offer->amount_per_share, 2) }} {{ __('ريال') }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="st-note-box">
                                    <i class="bi bi-info-circle-fill"></i>
                                    <div>{{ __('لا توجد عروض بيع متاحة حاليًا للربط.') }}</div>
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
            const form = document.querySelector('[data-order-form]');
            const countInput = document.getElementById('count');
            const priceInput = document.getElementById('amount_per_share');
            const countPreview = document.getElementById('calc-count');
            const totalPreview = document.getElementById('calc-total');

            function updatePreview() {
                const count = parseFloat(countInput.value || 0);
                const price = parseFloat(priceInput.value || 0);

                countPreview.textContent = count.toFixed(2);
                totalPreview.textContent = (count * price).toFixed(2);
            }

            countInput.addEventListener('input', updatePreview);
            priceInput.addEventListener('input', updatePreview);

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
