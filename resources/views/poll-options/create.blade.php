@extends('layouts.app')

@section('title', __('إضافة خيار استطلاع جديد'))

@include('polls.partials.ui-styles')

@section('content')
<div class="poll-page">
    <div class="poll-shell">
        <section class="poll-hero">
            <div class="poll-hero-inner">
                <div>
                    <span class="poll-badge">
                        <i class="bi bi-plus-circle-fill"></i>
                        {{ __('إضافة خيار استطلاع جديد') }}
                    </span>
                    <h1 class="poll-title">{{ __('أضف خيارًا واضحًا ومناسبًا لسياق الاستطلاع') }}</h1>
                </div>

                <div class="poll-hero-actions">
                    <a href="{{ route('poll-options.index') }}" class="poll-btn-muted">
                        <i class="bi bi-arrow-right-circle"></i>
                        {{ __('العودة للخيارات') }}
                    </a>
                </div>
            </div>
        </section>

        <section class="poll-stats-grid">
            <article class="poll-stat-card" style="animation-delay: 0.05s;">
                <div class="poll-stat-icon"><i class="bi bi-ui-radios-grid"></i></div>
                <p class="poll-stat-value">{{ number_format($polls->count()) }}</p>
                <p class="poll-stat-label">{{ __('استطلاعات متاحة للربط') }}</p>
            </article>
            <article class="poll-stat-card" style="animation-delay: 0.11s;">
                <div class="poll-stat-icon"><i class="bi bi-list-check"></i></div>
                <p class="poll-stat-value">{{ number_format($polls->sum(fn ($poll) => $poll->pollOptions->count())) }}</p>
                <p class="poll-stat-label">{{ __('خيارات حالية داخل الاستطلاعات') }}</p>
            </article>
        </section>

        <div class="poll-form-layout">
            <section class="poll-card">
                <div class="poll-card-header">
                    <div class="poll-card-title-wrap">
                        <span class="poll-card-icon"><i class="bi bi-pencil-square"></i></span>
                        <div>
                            <h2 class="poll-card-title">{{ __('نموذج إضافة الخيار') }}</h2>
                            <p class="poll-card-note">{{ __('أدخل النص واربطه بالاستطلاع المناسب، ويمكنك تحديد عدد أصوات ابتدائي إذا كنت تعدّل بيانات قديمة أو تنقلها.') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('poll-options.store') }}" method="POST" id="pollOptionCreateForm">
                    @csrf

                    @include('poll-options.partials.form-fields', ['pollOption' => null])

                    <div class="poll-footer-actions" style="margin-top: 22px;">
                        <button type="submit" class="poll-btn">
                            <i class="bi bi-check2-circle"></i>
                            {{ __('حفظ الخيار') }}
                        </button>
                        <a href="{{ route('poll-options.index') }}" class="poll-btn-muted">
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
                                <h2 class="poll-card-title">{{ __('نصائح سريعة') }}</h2>
                                <p class="poll-card-note">{{ __('هذه الإرشادات تساعد على جعل الخيار أوضح للمصوّت.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="poll-mini-stats">
                        <div class="poll-mini-stat">
                            <span class="poll-mini-label">{{ __('اختصر النص') }}</span>
                            <div class="poll-mini-value">{{ __('اجعل الخيار مباشرًا ولا تضع أكثر من فكرة داخل خيار واحد.') }}</div>
                        </div>
                        <div class="poll-mini-stat">
                            <span class="poll-mini-label">{{ __('تجنب التكرار') }}</span>
                            <div class="poll-mini-value">{{ __('افحص خيارات الاستطلاع الحالية حتى لا تضيف خيارًا مشابهًا جدًا لخيار موجود.') }}</div>
                        </div>
                        <div class="poll-mini-stat">
                            <span class="poll-mini-label">{{ __('الأصوات الأولية') }}</span>
                            <div class="poll-mini-value">{{ __('استخدمها فقط إذا كان لديك سبب واضح، وإلا ابدأ من الصفر للحفاظ على دقة النتائج.') }}</div>
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
        const optionText = document.getElementById('option_text');
        const form = document.getElementById('pollOptionCreateForm');
        optionText?.focus();

        form?.addEventListener('submit', function (event) {
            const pollId = document.getElementById('poll_id');
            const votes = document.getElementById('votes');

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
            }
        });
    });
</script>
@endpush
