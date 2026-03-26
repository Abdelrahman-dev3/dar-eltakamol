@extends('layouts.app')

@section('title', __('تعديل خيار الاستطلاع') . ' - ' . $pollOption->option_text)

@include('polls.partials.ui-styles')

@php
    $linkedPoll = $pollOption->poll;
@endphp

@section('content')
<div class="poll-page">
    <div class="poll-shell">
        <section class="poll-hero">
            <div class="poll-hero-inner">
                <div>
                    <span class="poll-badge">
                        <i class="bi bi-pencil-fill"></i>
                        {{ __('تعديل خيار الاستطلاع') }} #{{ $pollOption->id }}
                    </span>
                    <h1 class="poll-title">{{ $pollOption->option_text }}</h1>
                    <div class="poll-meta-row">
                        <span class="poll-chip"><i class="bi bi-hand-thumbs-up-fill"></i>{{ number_format($pollOption->votes) }} {{ __('صوت') }}</span>
                        <span class="poll-chip"><i class="bi bi-ui-radios-grid"></i>{{ $linkedPoll ? '#' . $linkedPoll->id : __('غير مرتبط') }}</span>
                    </div>
                </div>

                <div class="poll-hero-actions">
                    <a href="{{ route('poll-options.show', $pollOption) }}" class="poll-btn">
                        <i class="bi bi-eye-fill"></i>
                        {{ __('عرض') }}
                    </a>
                    <a href="{{ route('poll-options.index') }}" class="poll-btn-muted">
                        <i class="bi bi-arrow-right-circle"></i>
                        {{ __('العودة') }}
                    </a>
                </div>
            </div>
        </section>

        <div class="poll-form-layout">
            <section class="poll-card">
                <div class="poll-card-header">
                    <div class="poll-card-title-wrap">
                        <span class="poll-card-icon"><i class="bi bi-sliders2"></i></span>
                        <div>
                            <h2 class="poll-card-title">{{ __('تحديث بيانات الخيار') }}</h2>
                            <p class="poll-card-note">{{ __('النموذج نفسه يحافظ على نفس لغة التصميم الحديثة ويعرض الحقول الأساسية بشكل أوضح وأسهل للمراجعة.') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('poll-options.update', $pollOption) }}" method="POST" id="pollOptionEditForm">
                    @csrf
                    @method('PUT')

                    @include('poll-options.partials.form-fields', ['pollOption' => $pollOption])

                    <div class="poll-footer-actions" style="margin-top: 22px;">
                        <button type="submit" class="poll-btn">
                            <i class="bi bi-save2-fill"></i>
                            {{ __('حفظ التغييرات') }}
                        </button>
                        <a href="{{ route('poll-options.show', $pollOption) }}" class="poll-btn-muted">
                            <i class="bi bi-eye"></i>
                            {{ __('عرض') }}
                        </a>
                    </div>
                </form>
            </section>

            <aside class="poll-shell" style="gap: 18px;">
                <section class="poll-card">
                    <div class="poll-card-header">
                        <div class="poll-card-title-wrap">
                            <span class="poll-card-icon"><i class="bi bi-speedometer2"></i></span>
                            <div>
                                <h2 class="poll-card-title">{{ __('ملخص الخيار') }}</h2>
                                <p class="poll-card-note">{{ __('مؤشرات سريعة مرتبطة بالخيار الحالي.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="poll-detail-grid" style="grid-template-columns: 1fr;">
                        <div class="poll-detail-item">
                            <span class="poll-detail-label">{{ __('الاستطلاع الحالي') }}</span>
                            <div class="poll-detail-value">{{ $linkedPoll ? \Illuminate\Support\Str::limit($linkedPoll->question, 110) : __('غير مرتبط') }}</div>
                        </div>
                        <div class="poll-detail-item">
                            <span class="poll-detail-label">{{ __('عدد الأصوات') }}</span>
                            <div class="poll-detail-value">{{ number_format($pollOption->votes) }}</div>
                        </div>
                        <div class="poll-detail-item">
                            <span class="poll-detail-label">{{ __('تاريخ الإنشاء') }}</span>
                            <div class="poll-detail-value">{{ optional($pollOption->created_at)->format('Y-m-d H:i') ?? __('غير متوفر') }}</div>
                        </div>
                        <div class="poll-detail-item">
                            <span class="poll-detail-label">{{ __('آخر تحديث') }}</span>
                            <div class="poll-detail-value">{{ optional($pollOption->updated_at)->format('Y-m-d H:i') ?? __('غير متوفر') }}</div>
                        </div>
                    </div>
                </section>

                @if($linkedPoll)
                    <section class="poll-card">
                        <div class="poll-card-header">
                            <div class="poll-card-title-wrap">
                                <span class="poll-card-icon"><i class="bi bi-box-arrow-up-left"></i></span>
                                <div>
                                    <h2 class="poll-card-title">{{ __('روابط سريعة') }}</h2>
                                    <p class="poll-card-note">{{ __('انتقال مباشر للاستطلاع المرتبط أو شاشة النتائج.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="poll-footer-actions">
                            <a href="{{ route('polls.show', $linkedPoll) }}" class="poll-btn-muted">
                                <i class="bi bi-eye-fill"></i>
                                {{ __('عرض الاستطلاع') }}
                            </a>
                            <a href="{{ route('polls.results', $linkedPoll) }}" class="poll-btn-muted">
                                <i class="bi bi-bar-chart-fill"></i>
                                {{ __('نتائج الاستطلاع') }}
                            </a>
                        </div>
                    </section>
                @endif
            </aside>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const optionText = document.getElementById('option_text');
        const votes = document.getElementById('votes');
        const form = document.getElementById('pollOptionEditForm');
        optionText?.focus();

        form?.addEventListener('submit', function (event) {
            const pollId = document.getElementById('poll_id');

            if (!pollId?.value) {
                event.preventDefault();
                window.alert('{{ __('يرجى اختيار الاستطلاع أولًا.') }}');
                pollId?.focus();
                return;
            }

            if (!optionText?.value.trim()) {
                event.preventDefault();
                window.alert('{{ __('يرجى إدخال نص الخيار.') }}');
                optionText?.focus();
                return;
            }

            if (votes && Number(votes.value || 0) < 0) {
                event.preventDefault();
                window.alert('{{ __('لا يمكن أن يكون عدد الأصوات أقل من صفر.') }}');
                votes.focus();
                return;
            }
        });
    });
</script>
@endpush
