@extends('layouts.app')

@section('title', __('تعديل التصويت') . ' - ' . ($pollAnswer->user->name ?? __('غير معروف')))

@include('polls.partials.ui-styles')

@php
    $linkedPoll = $pollAnswer->poll;
    $linkedOption = $pollAnswer->pollOption;
    $linkedUser = $pollAnswer->user;
@endphp

@section('content')
<div class="poll-page">
    <div class="poll-shell">
        <section class="poll-hero">
            <div class="poll-hero-inner">
                <div>
                    <span class="poll-badge">
                        <i class="bi bi-pencil-fill"></i>
                        {{ __('تعديل التصويت') }} #{{ $pollAnswer->id }}
                    </span>
                    <h1 class="poll-title">{{ $linkedUser?->name ?? __('مستخدم غير معروف') }}</h1>
                    <div class="poll-meta-row">
                        <span class="poll-chip"><i class="bi bi-ui-radios-grid"></i>{{ $linkedPoll ? '#' . $linkedPoll->id : __('بدون استطلاع') }}</span>
                        <span class="poll-chip"><i class="bi bi-check2-circle"></i>{{ $linkedOption?->option_text ?? __('بدون خيار') }}</span>
                        <span class="poll-chip"><i class="bi bi-calendar2-check"></i>{{ optional($pollAnswer->answer_date)->format('Y-m-d H:i') ?? __('غير متوفر') }}</span>
                    </div>
                </div>

                <div class="poll-hero-actions">
                    <a href="{{ route('poll-answers.show', $pollAnswer) }}" class="poll-btn">
                        <i class="bi bi-eye-fill"></i>
                        {{ __('عرض') }}
                    </a>
                    <a href="{{ route('poll-answers.index') }}" class="poll-btn-muted">
                        <i class="bi bi-arrow-right-circle"></i>
                        {{ __('العودة') }}
                    </a>
                </div>
            </div>
        </section>

        <div class="poll-grid">
            <section class="poll-card">
                <div class="poll-card-header">
                    <div class="poll-card-title-wrap">
                        <span class="poll-card-icon"><i class="bi bi-sliders2"></i></span>
                        <div>
                            <h2 class="poll-card-title">{{ __('تحديث بيانات التصويت') }}</h2>
                            <p class="poll-card-note">{{ __('يمكنك تعديل الاستطلاع أو الخيار أو المستخدم أو وقت التصويت، مع بقاء التحقق من صحة الربط وتفادي التكرار مفعّلًا.') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('poll-answers.update', $pollAnswer) }}" method="POST" id="pollAnswerEditForm">
                    @csrf
                    @method('PUT')

                    @include('poll-answers.partials.form-fields', ['pollAnswer' => $pollAnswer])

                    <div class="poll-footer-actions" style="margin-top: 22px;">
                        <button type="submit" class="poll-btn">
                            <i class="bi bi-save2-fill"></i>
                            {{ __('حفظ التغييرات') }}
                        </button>
                        <a href="{{ route('poll-answers.show', $pollAnswer) }}" class="poll-btn-muted">
                            <i class="bi bi-eye"></i>
                            {{ __('عرض التصويت') }}
                        </a>
                    </div>
                </form>
            </section>

            <aside class="poll-shell" style="gap: 18px;">
                <section class="poll-card">
                    <div class="poll-card-header">
                        <div class="poll-card-title-wrap">
                            <span class="poll-card-icon"><i class="bi bi-info-circle-fill"></i></span>
                            <div>
                                <h2 class="poll-card-title">{{ __('البيانات الحالية') }}</h2>
                                <p class="poll-card-note">{{ __('مرجع سريع قبل الحفظ حتى تراجع القيم الأصلية بسهولة.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="poll-detail-grid" style="grid-template-columns: 1fr;">
                        <div class="poll-detail-item">
                            <span class="poll-detail-label">{{ __('المستخدم') }}</span>
                            <div class="poll-detail-value">{{ $linkedUser?->name ?? __('غير معروف') }}</div>
                        </div>
                        <div class="poll-detail-item">
                            <span class="poll-detail-label">{{ __('الاستطلاع الحالي') }}</span>
                            <div class="poll-detail-value">{{ $linkedPoll ? \Illuminate\Support\Str::limit($linkedPoll->question, 110) : __('غير متوفر') }}</div>
                        </div>
                        <div class="poll-detail-item">
                            <span class="poll-detail-label">{{ __('الخيار الحالي') }}</span>
                            <div class="poll-detail-value">{{ $linkedOption?->option_text ?? __('غير محدد') }}</div>
                        </div>
                        <div class="poll-detail-item">
                            <span class="poll-detail-label">{{ __('آخر تحديث') }}</span>
                            <div class="poll-detail-value">{{ optional($pollAnswer->updated_at)->format('Y-m-d H:i') ?? __('غير متوفر') }}</div>
                        </div>
                    </div>
                </section>

                <section class="poll-card">
                    <div class="poll-card-header">
                        <div class="poll-card-title-wrap">
                            <span class="poll-card-icon"><i class="bi bi-exclamation-triangle-fill"></i></span>
                            <div>
                                <h2 class="poll-card-title">{{ __('تنبيه') }}</h2>
                                <p class="poll-card-note">{{ __('تغيير الخيار قد يؤثر على عدادات التصويت والنتائج الظاهرة داخل الاستطلاع.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="poll-mini-stats">
                        <div class="poll-mini-stat">
                            <span class="poll-mini-label">{{ __('قبل الحفظ') }}</span>
                            <div class="poll-mini-value">{{ __('راجع أن الخيار المحدد يتبع الاستطلاع نفسه، وأن المستخدم المقصود لم يُسجل تصويتًا آخر داخل نفس الاستطلاع.') }}</div>
                        </div>
                    </div>

                    <div class="poll-footer-actions" style="margin-top: 18px;">
                        @if($linkedPoll)
                            <a href="{{ route('polls.show', $linkedPoll) }}" class="poll-btn-muted">
                                <i class="bi bi-ui-radios-grid"></i>
                                {{ __('عرض الاستطلاع') }}
                            </a>
                        @endif
                        @if($linkedOption)
                            <a href="{{ route('poll-options.show', $linkedOption) }}" class="poll-btn-muted">
                                <i class="bi bi-list-check"></i>
                                {{ __('عرض الخيار') }}
                            </a>
                        @endif
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
        const form = document.getElementById('pollAnswerEditForm');
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
