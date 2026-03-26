@extends('layouts.app')

@section('title', __('إضافة استطلاع جديد'))

@include('polls.partials.ui-styles')

@php
    $options = collect(old('options', ['', '']))->values();

    while ($options->count() < 2) {
        $options->push('');
    }
@endphp

@section('content')
<div class="poll-page">
    <div class="poll-shell">
        <section class="poll-hero">
            <div class="poll-hero-inner">
                <div>
                    <span class="poll-badge">
                        <i class="bi bi-plus-circle-fill"></i>
                        {{ __('إضافة استطلاع جديد') }}
                    </span>
                    <h1 class="poll-title">{{ __('ابنِ استطلاعًا واضحًا وسهل القراءة من أول خطوة') }}</h1>
                </div>

                <div class="poll-hero-actions">
                    <a href="{{ route('polls.index') }}" class="poll-btn-muted">
                        <i class="bi bi-arrow-right-circle"></i>
                        {{ __('العودة للاستطلاعات') }}
                    </a>
                </div>
            </div>
        </section>
        <div class="poll-form-layout">
            <section class="poll-card">
                <div class="poll-card-header">
                    <div class="poll-card-title-wrap">
                        <span class="poll-card-icon"><i class="bi bi-pencil-square"></i></span>
                        <div>
                            <h2 class="poll-card-title">{{ __('نموذج إنشاء الاستطلاع') }}</h2>
                        </div>
                    </div>
                </div>

                <form action="{{ route('polls.store') }}" method="POST">
                    @csrf

                    @include('polls.partials.form-core-fields', ['poll' => null])

                    <section class="poll-option-builder">
                        <div class="poll-option-builder-head">
                            <div>
                                <h2 class="poll-section-title" style="margin-bottom: 0;">
                                    <i class="bi bi-list-check"></i>
                                    {{ __('خيارات الاستطلاع') }}
                                </h2>
                                <div class="poll-help-text">{{ __('أضف خيارين على الأقل. يمكنك إضافة المزيد أو حذف غير الضروري قبل الحفظ.') }}</div>
                            </div>

                            <button type="button" class="poll-btn-muted" id="addOptionBtn">
                                <i class="bi bi-plus-circle"></i>
                                {{ __('إضافة خيار') }}
                            </button>
                        </div>

                        @error('options')
                            <div class="poll-error" style="margin-bottom: 14px;">{{ $message }}</div>
                        @enderror

                        <div class="poll-option-list" id="optionsList">
                            @foreach($options as $index => $option)
                                <article class="poll-option-card option-item">
                                    <div class="poll-option-top">
                                        <span class="poll-option-order">{{ $index + 1 }}</span>
                                        <div>
                                            <h3 class="poll-option-title">{{ __('خيار التصويت') }}</h3>
                                            <div class="poll-option-meta">{{ __('سيظهر هذا النص للمصوتين داخل صفحة العرض.') }}</div>
                                        </div>
                                    </div>

                                    <div class="poll-option-input-row">
                                        <input type="text" name="options[]" class="poll-input" value="{{ $option }}" placeholder="{{ __('اكتب نص الخيار') }}" required>
                                        <button type="button" class="poll-btn-danger remove-option-btn">
                                            <i class="bi bi-dash-circle"></i>
                                            {{ __('حذف') }}
                                        </button>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>

                    <div class="poll-footer-actions" style="margin-top: 22px;">
                        <button type="submit" class="poll-btn">
                            <i class="bi bi-check2-circle"></i>
                            {{ __('حفظ الاستطلاع') }}
                        </button>
                        <a href="{{ route('polls.index') }}" class="poll-btn-muted">
                            <i class="bi bi-x-circle"></i>
                            {{ __('إلغاء') }}
                        </a>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const optionsList = document.getElementById('optionsList');
        const addOptionBtn = document.getElementById('addOptionBtn');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        const createOptionCard = function (index, value) {
            const article = document.createElement('article');
            article.className = 'poll-option-card option-item';
            article.innerHTML = `
                <div class="poll-option-top">
                    <span class="poll-option-order">${index}</span>
                    <div>
                        <h3 class="poll-option-title">{{ __('خيار التصويت') }}</h3>
                        <div class="poll-option-meta">{{ __('سيظهر هذا النص للمصوتين داخل صفحة العرض.') }}</div>
                    </div>
                </div>
                <div class="poll-option-input-row">
                    <input type="text" name="options[]" class="poll-input" value="${value || ''}" placeholder="{{ __('اكتب نص الخيار') }}" required>
                    <button type="button" class="poll-btn-danger remove-option-btn">
                        <i class="bi bi-dash-circle"></i>
                        {{ __('حذف') }}
                    </button>
                </div>
            `;

            return article;
        };

        const refreshOptionIndexes = function () {
            const items = Array.from(optionsList.querySelectorAll('.option-item'));
            items.forEach(function (item, index) {
                const order = item.querySelector('.poll-option-order');
                if (order) {
                    order.textContent = index + 1;
                }
            });

            items.forEach(function (item) {
                const removeButton = item.querySelector('.remove-option-btn');
                if (removeButton) {
                    removeButton.disabled = items.length <= 2;
                }
            });
        };

        addOptionBtn?.addEventListener('click', function () {
            const nextIndex = optionsList.querySelectorAll('.option-item').length + 1;
            optionsList.appendChild(createOptionCard(nextIndex, ''));
            refreshOptionIndexes();
        });

        optionsList?.addEventListener('click', function (event) {
            const button = event.target.closest('.remove-option-btn');
            if (!button) {
                return;
            }

            const items = optionsList.querySelectorAll('.option-item');
            if (items.length <= 2) {
                return;
            }

            button.closest('.option-item')?.remove();
            refreshOptionIndexes();
        });

        const formatDate = function (date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');

            return `${year}-${month}-${day}T${hours}:${minutes}`;
        };

        if (startDateInput && !startDateInput.value) {
            startDateInput.value = formatDate(new Date());
        }

        if (endDateInput && !endDateInput.value) {
            endDateInput.value = formatDate(new Date(Date.now() + (7 * 24 * 60 * 60 * 1000)));
        }

        refreshOptionIndexes();
    });
</script>
@endpush
