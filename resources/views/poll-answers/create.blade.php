@extends('layouts.app')

@section('title', __('إضافة تصويت جديد'))

@include('polls.partials.ui-styles')

@section('content')
<div class="poll-page">
    <div class="poll-shell">
        <section class="poll-hero">
            <div class="poll-hero-inner">
                <div>
                    <span class="poll-badge">
                        <i class="bi bi-plus-circle-fill"></i>
                        {{ __('إضافة تصويت جديد') }}
                    </span>
                    <h1 class="poll-title">{{ __('سجّل تصويتًا جديدًا داخل الاستطلاع المناسب') }}</h1>
                </div>

                <div class="poll-hero-actions">
                    <a href="{{ route('poll-answers.index') }}" class="poll-btn-muted">
                        <i class="bi bi-arrow-right-circle"></i>
                        {{ __('العودة للتصويتات') }}
                    </a>
                </div>
            </div>
        </section>

        <div class="poll-grid">
            <section class="poll-card">
                <div class="poll-card-header">
                    <div class="poll-card-title-wrap">
                        <span class="poll-card-icon"><i class="bi bi-pencil-square"></i></span>
                        <div>
                            <h2 class="poll-card-title">{{ __('نموذج إضافة التصويت') }}</h2>
                            <p class="poll-card-note">{{ __('الحقول مرتبة بشكل مباشر: الاستطلاع أولًا، ثم الخيار التابع له، ثم المستخدم ووقت التصويت.') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('poll-answers.store') }}" method="POST" id="pollAnswerCreateForm">
                    @csrf

                    @include('poll-answers.partials.form-fields', ['pollAnswer' => null])

                    <div class="poll-footer-actions" style="margin-top: 22px;">
                        <button type="submit" class="poll-btn">
                            <i class="bi bi-check2-circle"></i>
                            {{ __('حفظ التصويت') }}
                        </button>
                        <a href="{{ route('poll-answers.index') }}" class="poll-btn-muted">
                            <i class="bi bi-x-circle"></i>
                            {{ __('إلغاء') }}
                        </a>
                    </div>
                </form>
            </section>

            <aside class="poll-shell" style="gap: 18px;">
                <section class="poll-card">
                    <div class="poll-card-header">
                        <div class="poll-card-title-wrap">
                            <span class="poll-card-icon"><i class="bi bi-lightbulb-fill"></i></span>
                            <div>
                                <h2 class="poll-card-title">{{ __('ملاحظات مهمة') }}</h2>
                                <p class="poll-card-note">{{ __('هذه الإرشادات تقلل أخطاء الإدخال وتسرّع المراجعة.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="poll-mini-stats">
                        <div class="poll-mini-stat">
                            <span class="poll-mini-label">{{ __('الربط الصحيح') }}</span>
                            <div class="poll-mini-value">{{ __('بعد اختيار الاستطلاع سيتم إظهار الخيارات التابعة له فقط حتى لا يتم حفظ اختيار غير مطابق.') }}</div>
                        </div>
                        <div class="poll-mini-stat">
                            <span class="poll-mini-label">{{ __('تصويت واحد لكل مستخدم') }}</span>
                            <div class="poll-mini-value">{{ __('النظام يتحقق من عدم تكرار تصويت نفس المستخدم داخل نفس الاستطلاع.') }}</div>
                        </div>
                        <div class="poll-mini-stat">
                            <span class="poll-mini-label">{{ __('تاريخ التصويت') }}</span>
                            <div class="poll-mini-value">{{ __('يمكنك إبقاء الوقت الحالي أو إدخال تاريخ سابق عند ترحيل بيانات أو تصحيح سجلات قديمة.') }}</div>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('pollAnswerCreateForm');
        const pollSelect = document.getElementById('poll_id');
        const optionSelect = document.getElementById('poll_option_id');
        const userSelect = document.getElementById('user_id');

        const filterOptions = function () {
            const selectedPollId = pollSelect?.value || '';
            const options = Array.from(optionSelect?.options || []);

            options.forEach(function (option) {
                if (!option.value) {
                    option.hidden = false;
                    return;
                }

                const belongsToPoll = !selectedPollId || option.dataset.pollId === selectedPollId;
                option.hidden = !belongsToPoll;

                if (!belongsToPoll && option.selected) {
                    option.selected = false;
                }
            });

            const selectedOption = optionSelect?.selectedOptions?.[0];
            if (selectedOption && selectedOption.dataset.pollId !== selectedPollId) {
                optionSelect.value = '';
            }
        };

        pollSelect?.addEventListener('change', filterOptions);
        filterOptions();
        pollSelect?.focus();

        form?.addEventListener('submit', function (event) {
            const selectedOption = optionSelect?.selectedOptions?.[0];

            if (!pollSelect?.value) {
                event.preventDefault();
                window.alert('{{ __('يرجى اختيار الاستطلاع أولًا.') }}');
                pollSelect?.focus();
                return;
            }

            if (!optionSelect?.value) {
                event.preventDefault();
                window.alert('{{ __('يرجى اختيار الخيار المرتبط بالاستطلاع.') }}');
                optionSelect?.focus();
                return;
            }

            if (selectedOption && selectedOption.dataset.pollId !== pollSelect.value) {
                event.preventDefault();
                window.alert('{{ __('الخيار المحدد لا ينتمي إلى الاستطلاع المختار.') }}');
                optionSelect?.focus();
                return;
            }

            if (!userSelect?.value) {
                event.preventDefault();
                window.alert('{{ __('يرجى اختيار المستخدم.') }}');
                userSelect?.focus();
            }
        });
    });
</script>
@endpush
