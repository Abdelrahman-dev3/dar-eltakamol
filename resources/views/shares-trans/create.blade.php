@extends('layouts.app')

@section('title', __('إضافة معاملة أسهم جديدة'))

@include('shares-trans.partials.styles')

@php
    $transactionTypes = [
        ['value' => 1, 'label' => __('شراء'), 'icon' => 'bi bi-bag-check-fill', 'description' => __('معاملات شراء أسهم جديدة من المساهمين')],
        ['value' => 2, 'label' => __('بيع'), 'icon' => 'bi bi-box-arrow-up-left', 'description' => __('معاملات بيع أسهم من قبل المساهمين')],
        ['value' => 3, 'label' => __('تحويل'), 'icon' => 'bi bi-arrow-left-right', 'description' => __('تحويل أسهم بين المساهمين')],
        ['value' => 4, 'label' => __('أرباح'), 'icon' => 'bi bi-coin', 'description' => __('توزيعات أرباح على المساهمين')],
    ];
@endphp

@section('content')
    <div class="st-page">
        <div class="st-shell">
            <section class="st-hero">
                <div class="st-hero-inner">
                    <div>
                        <span class="st-hero-badge">
                            <i class="bi bi-plus-circle-fill"></i>
                            {{ __('إضافة معاملة جديدة') }}
                        </span>
                        <h1 class="st-hero-title">{{ __('أنشئ معاملة أسهم بشكل واضح وسريع') }}</h1>
                        <p class="st-hero-subtitle">
                            {{ __('املأ البيانات الأساسية أولًا، ثم يمكنك الانتقال لاحقًا لإضافة التفاصيل المرتبطة بالمساهمين والمبالغ قبل اعتماد المعاملة.') }}
                        </p>
                    </div>

                    <div class="st-hero-actions">
                        <a href="{{ route('shares-trans.index') }}" class="st-btn st-btn-secondary">
                            <i class="bi bi-arrow-right-circle"></i>
                            {{ __('العودة للمعاملات') }}
                        </a>
                    </div>
                </div>
            </section>

            <div class="st-grid-two">
                <section class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-title-wrap">
                            <span class="st-card-icon">
                                <i class="bi bi-pencil-square"></i>
                            </span>
                            <div>
                                <h2 class="st-card-title">{{ __('بيانات المعاملة') }}</h2>
                                <p class="st-card-subtitle">{{ __('كل الحقول هنا مرتبطة بالمعاملة نفسها، بينما تفاصيل المساهمين تضاف في الخطوة التالية.') }}</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('shares-trans.store') }}" method="POST" data-shares-trans-form data-confirm-message="{{ __('هل أنت متأكد من إنشاء هذه المعاملة؟') }}">
                        @csrf

                        <div class="st-form-grid">
                            <div class="st-form-field">
                                <label for="date" class="st-label">{{ __('تاريخ المعاملة') }} <span class="st-required">*</span></label>
                                <input type="date" name="date" id="date" class="st-input" value="{{ old('date', date('Y-m-d')) }}" required>
                                @error('date')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="st-form-field">
                                <label for="trans_type" class="st-label">{{ __('نوع المعاملة') }} <span class="st-required">*</span></label>
                                <select name="trans_type" id="trans_type" class="st-select" required>
                                    <option value="">{{ __('اختر نوع المعاملة') }}</option>
                                    @foreach($transactionTypes as $type)
                                        <option value="{{ $type['value'] }}" {{ old('trans_type') == (string) $type['value'] ? 'selected' : '' }}>
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
                                    placeholder="{{ __('أدخل ملاحظات حول المعاملة إن وجدت') }}"
                                >{{ old('notes') }}</textarea>
                                <span class="st-help">{{ __('يمكنك إضافة وصف مختصر يسهّل المراجعة لاحقًا مثل سبب المعاملة أو سياقها.') }}</span>
                                @error('notes')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="st-form-footer">
                            <p class="st-form-footer-note">
                                {{ __('سيتم حفظ المعاملة بحالة غير معتمدة، وبعد ذلك يمكنك إضافة التفاصيل ومراجعتها ثم اعتمادها عند الجاهزية.') }}
                            </p>

                            <div class="st-inline-actions">
                                <button type="submit" class="st-btn st-btn-primary">
                                    <i class="bi bi-check2-circle"></i>
                                    {{ __('حفظ المعاملة') }}
                                </button>
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
                                    <i class="bi bi-ui-checks-grid"></i>
                                </span>
                                <div>
                                    <h2 class="st-card-title">{{ __('أنواع المعاملات المتاحة') }}</h2>
                                </div>
                            </div>
                        </div>

                        <div class="st-type-grid">
                            @foreach($transactionTypes as $type)
                                <article class="st-type-card">
                                    <i class="{{ $type['icon'] }}"></i>
                                    <h3>{{ $type['label'] }}</h3>
                                    <p>{{ $type['description'] }}</p>
                                </article>
                            @endforeach
                        </div>
                    </section>

                    <section class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-title-wrap">
                                <span class="st-card-icon">
                                    <i class="bi bi-signpost-split-fill"></i>
                                </span>
                                <div>
                                    <h2 class="st-card-title">{{ __('الخطوات التالية') }}</h2>
                                    <p class="st-card-subtitle">{{ __('تسلسل العمل المقترح بعد حفظ السجل الأساسي.') }}</p>
                                </div>
                            </div>
                        </div>

                        <ol class="st-steps">
                            <li>{{ __('إضافة تفاصيل المعاملة وربط كل سطر بالمساهم المناسب.') }}</li>
                            <li>{{ __('تحديد عدد الأسهم والمبلغ لكل سهم بدقة.') }}</li>
                            <li>{{ __('مراجعة الملاحظات والبيانات قبل الاعتماد النهائي.') }}</li>
                            <li>{{ __('اعتماد المعاملة عندما تصبح جاهزة للتنفيذ.') }}</li>
                        </ol>

                        <div class="st-note-box" style="margin-top: 1rem;">
                            <i class="bi bi-info-circle-fill"></i>
                            <div>{{ __('بعد إنشاء المعاملة ستنتقل بسهولة إلى شاشة العرض أو التعديل لإكمال التفاصيل دون فقدان أي تدرج بصري أو وظيفي.') }}</div>
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

            const suggestions = {
                1: '{{ __("معاملة شراء أسهم جديدة") }}',
                2: '{{ __("معاملة بيع أسهم") }}',
                3: '{{ __("معاملة تحويل أسهم") }}',
                4: '{{ __("معاملة توزيع أرباح") }}'
            };

            if (transType && notesField) {
                transType.addEventListener('change', function () {
                    const selectedType = Number(this.value || 0);

                    if (!notesField.value.trim() && suggestions[selectedType]) {
                        notesField.setAttribute('placeholder', suggestions[selectedType]);
                    }
                });
            }

            if (form) {
                form.addEventListener('submit', function (event) {
                    const message = this.getAttribute('data-confirm-message');

                    if (message && !window.confirm(message)) {
                        event.preventDefault();
                    }
                });
            }
        });
    </script>
@endpush
