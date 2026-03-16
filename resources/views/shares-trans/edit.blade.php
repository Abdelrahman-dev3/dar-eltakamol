@extends('layouts.app')

@section('title', __('تعديل معاملة الأسهم'))

@include('shares-trans.partials.styles')

@php
    $transactionTypes = [
        ['value' => 1, 'label' => __('شراء')],
        ['value' => 2, 'label' => __('بيع')],
        ['value' => 3, 'label' => __('تحويل')],
        ['value' => 4, 'label' => __('أرباح')],
    ];
    $isPosted = (bool) $shares_tran->posted;
    $detailsCount = $shares_tran->shareTransLines->count();
@endphp

@section('content')
    <div class="st-page">
        <div class="st-shell">
            <section class="st-hero">
                <div class="st-hero-inner">
                    <div>
                        <span class="st-hero-badge">
                            <i class="bi bi-pencil-square"></i>
                            {{ __('تحديث معاملة') }} #{{ $shares_tran->id }}
                        </span>
                        <h1 class="st-hero-title">{{ __('راجع بيانات المعاملة وعدّلها بثقة') }}</h1>
                    </div>

                    <div class="st-hero-actions">
                        <a href="{{ route('shares-trans.show', $shares_tran) }}" class="st-btn st-btn-info">
                            <i class="bi bi-eye-fill"></i>
                            {{ __('عرض المعاملة') }}
                        </a>
                        <a href="{{ route('shares-trans.index') }}" class="st-btn st-btn-secondary">
                            <i class="bi bi-arrow-right-circle"></i>
                            {{ __('العودة للمعاملات') }}
                        </a>
                    </div>
                </div>
            </section>

            <section class="st-summary-grid">
                <article class="st-summary-card" style="animation-delay: 0.05s;">
                    <div class="st-summary-icon">
                        <i class="bi bi-calendar-event-fill"></i>
                    </div>
                    <p class="st-summary-value">{{ $shares_tran->date?->format('Y-m-d') ?? __('غير متوفر') }}</p>
                    <p class="st-summary-label">{{ __('تاريخ المعاملة') }}</p>
                </article>

                <article class="st-summary-card" style="animation-delay: 0.12s;">
                    <div class="st-summary-icon">
                        <i class="bi bi-collection-fill"></i>
                    </div>
                    <p class="st-summary-value">{{ number_format($detailsCount) }}</p>
                    <p class="st-summary-label">{{ __('عدد التفاصيل المرتبطة') }}</p>
                </article>

                <article class="st-summary-card" style="animation-delay: 0.19s;">
                    <div class="st-summary-icon">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                    <div style="margin: 0 0 0.45rem;">
                        @include('shares-trans.partials.type-badge', ['type' => $shares_tran->trans_type])
                    </div>
                    <p class="st-summary-label">{{ __('نوع المعاملة') }}</p>
                </article>

                <article class="st-summary-card" style="animation-delay: 0.26s;">
                    <div class="st-summary-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div style="margin: 0 0 0.45rem;">
                        @include('shares-trans.partials.status-badge', ['posted' => $shares_tran->posted])
                    </div>
                    <p class="st-summary-label">{{ __('حالة المعاملة') }}</p>
                </article>
            </section>

            <div class="st-grid-two">
                <section class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-title-wrap">
                            <span class="st-card-icon">
                                <i class="bi bi-sliders"></i>
                            </span>
                            <div>
                                <h2 class="st-card-title">{{ __('نموذج التعديل') }}</h2>
                                <p class="st-card-subtitle">{{ __('يمكنك تحديث البيانات الأساسية هنا، أما التفاصيل المرتبطة بالمساهمين فتدار من شاشة التفاصيل الخاصة بالمعاملة.') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($isPosted)
                        <div class="st-banner is-warning" style="margin-bottom: 1.1rem;">
                            <i class="bi bi-lock-fill"></i>
                            <div>{{ __('هذه المعاملة معتمدة بالفعل، لذلك تم تعطيل التعديل المباشر عليها للحفاظ على سلامة البيانات.') }}</div>
                        </div>
                    @endif

                    <form action="{{ route('shares-trans.update', $shares_tran) }}" method="POST" data-shares-trans-form data-confirm-message="{{ __('هل أنت متأكد من حفظ التغييرات؟') }}">
                        @csrf
                        @method('PUT')

                        <div class="st-form-grid">
                            <div class="st-form-field">
                                <label for="date" class="st-label">{{ __('تاريخ المعاملة') }} <span class="st-required">*</span></label>
                                <input
                                    type="date"
                                    name="date"
                                    id="date"
                                    class="st-input"
                                    value="{{ old('date', $shares_tran->date?->format('Y-m-d')) }}"
                                    required
                                    @disabled($isPosted)
                                >
                                @error('date')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="st-form-field">
                                <label for="trans_type" class="st-label">{{ __('نوع المعاملة') }} <span class="st-required">*</span></label>
                                <select name="trans_type" id="trans_type" class="st-select" required @disabled($isPosted)>
                                    <option value="">{{ __('اختر نوع المعاملة') }}</option>
                                    @foreach($transactionTypes as $type)
                                        <option value="{{ $type['value'] }}" {{ old('trans_type', $shares_tran->trans_type) == $type['value'] ? 'selected' : '' }}>
                                            {{ $type['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('trans_type')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="st-form-field full">
                                <label for="notes" class="st-label">{{ __('ملاحظات') }}</label>
                                <textarea
                                    name="notes"
                                    id="notes"
                                    class="st-textarea"
                                    placeholder="{{ __('أدخل ملاحظات إضافية حول المعاملة') }}"
                                    @disabled($isPosted)
                                >{{ old('notes', $shares_tran->notes) }}</textarea>
                                <span class="st-help">{{ __('هذا الوصف يظهر في صفحة العرض ويساعد في تتبع سبب أو سياق المعاملة.') }}</span>
                                @error('notes')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="st-form-field full">
                                <label for="line_notes" class="st-label">{{ __('سبب التعديل') }} <span class="st-required">*</span></label>
                                <textarea
                                    name="line_notes"
                                    id="line_notes"
                                    class="st-textarea"
                                    placeholder="{{ __('اكتب سبب التعديل الذي قمت به') }}"
                                    {{ $isPosted ? 'disabled' : '' }}
                                >{{ old('line_notes') }}</textarea>
                                <span class="st-help">{{ __('يتم استخدام هذا الحقل في سجل التعديلات حتى يكون سبب التغيير واضحًا للمراجعة لاحقًا.') }}</span>
                                @error('line_notes')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="st-form-footer">
                            <p class="st-form-footer-note">
                                {{ __('آخر تحديث على هذه المعاملة كان بتاريخ') }} {{ $shares_tran->updated_at?->format('Y-m-d H:i') }}.
                            </p>

                            <div class="st-inline-actions">
                                @if(!$isPosted)
                                    <button type="submit" class="st-btn st-btn-primary">
                                        <i class="bi bi-check2-circle"></i>
                                        {{ __('حفظ التغييرات') }}
                                    </button>
                                @else
                                    <button type="button" class="st-btn st-btn-secondary" disabled>
                                        <i class="bi bi-lock-fill"></i>
                                        {{ __('المعاملة معتمدة') }}
                                    </button>
                                @endif

                                <a href="{{ route('shares-trans.show', $shares_tran) }}" class="st-btn st-btn-info">
                                    <i class="bi bi-eye-fill"></i>
                                    {{ __('عرض') }}
                                </a>
                                <a href="{{ route('shares-trans.index') }}" class="st-btn st-btn-secondary">
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
                                <span class="st-card-icon">
                                    <i class="bi bi-info-circle-fill"></i>
                                </span>
                                <div>
                                    <h2 class="st-card-title">{{ __('معلومات سريعة') }}</h2>
                                    <p class="st-card-subtitle">{{ __('ملخص مختصر يفيدك أثناء التعديل دون الحاجة للانتقال بعيدًا عن النموذج.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="st-info-list">
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('رقم المعاملة') }}</span>
                                <div class="st-info-value">#{{ $shares_tran->id }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('نوع المعاملة الحالي') }}</span>
                                <div class="st-info-value">@include('shares-trans.partials.type-badge', ['type' => $shares_tran->trans_type])</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('الحالة') }}</span>
                                <div class="st-info-value">@include('shares-trans.partials.status-badge', ['posted' => $shares_tran->posted])</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('تاريخ الإنشاء') }}</span>
                                <div class="st-info-value">{{ $shares_tran->created_at?->format('Y-m-d H:i') ?? __('غير متوفر') }}</div>
                            </div>
                        </div>
                    </section>

                    <section class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-title-wrap">
                                <span class="st-card-icon">
                                    <i class="bi bi-list-check"></i>
                                </span>
                                <div>
                                    <h2 class="st-card-title">{{ __('تفاصيل المعاملة') }}</h2>
                                    <p class="st-card-subtitle">{{ __('يمكنك الانتقال مباشرة لإدارة تفاصيل السطور المرتبطة بهذه المعاملة.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="st-note-box">
                            <i class="bi bi-clipboard-data-fill"></i>
                            <div>
                                {{ __('يوجد لهذه المعاملة') }} <strong>{{ number_format($detailsCount) }}</strong> {{ __('تفصيل') }}.
                                @if($detailsCount > 0)
                                    {{ __('يمكنك مراجعتها أو تعديلها من شاشة تفاصيل المعاملة.') }}
                                @else
                                    {{ __('لم تتم إضافة أي تفاصيل بعد، ويمكنك البدء من الشاشة المخصصة لذلك.') }}
                                @endif
                            </div>
                        </div>

                        <div class="st-inline-actions" style="margin-top: 1rem;">
                            @if($detailsCount > 0)
                                <a href="{{ route('share-trans-lines.index', ['trans_id' => $shares_tran->id]) }}" class="st-btn st-btn-primary">
                                    <i class="bi bi-list-ul"></i>
                                    {{ __('عرض التفاصيل') }}
                                </a>
                            @else
                                <a href="{{ route('share-trans-lines.create', ['trans_id' => $shares_tran->id]) }}" class="st-btn st-btn-success">
                                    <i class="bi bi-plus-circle-fill"></i>
                                    {{ __('إضافة تفاصيل') }}
                                </a>
                            @endif
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
            const form = document.querySelector('[data-shares-trans-form]');
            const transType = document.getElementById('trans_type');
            const notesField = document.getElementById('notes');
            const isPosted = @json($isPosted);

            const suggestions = {
                1: '{{ __("معاملة شراء أسهم جديدة") }}',
                2: '{{ __("معاملة بيع أسهم") }}',
                3: '{{ __("معاملة تحويل أسهم") }}',
                4: '{{ __("معاملة توزيع أرباح") }}'
            };

            if (transType && notesField && !isPosted) {
                transType.addEventListener('change', function () {
                    const selectedType = Number(this.value || 0);

                    if (!notesField.value.trim() && suggestions[selectedType]) {
                        notesField.setAttribute('placeholder', suggestions[selectedType]);
                    }
                });
            }

            if (form) {
                form.addEventListener('submit', function (event) {
                    if (isPosted) {
                        event.preventDefault();
                        return;
                    }

                    const message = this.getAttribute('data-confirm-message');

                    if (message && !window.confirm(message)) {
                        event.preventDefault();
                    }
                });
            }
        });
    </script>
@endpush
