@extends('layouts.app')

@section('title', __('تعديل الاستطلاع'))

@include('polls.partials.ui-styles')

@php
    $totalVotes = $poll->pollOptions->sum('votes');
    $targetedUsersCount = $poll->referencedUsers->count();

    if (! $poll->is_active) {
        $status = ['label' => __('متوقف'), 'class' => 'inactive', 'icon' => 'bi-pause-circle'];
    } elseif ($poll->end_date < now()) {
        $status = ['label' => __('منتهي'), 'class' => 'ended', 'icon' => 'bi-check2-circle'];
    } elseif ($poll->start_date > now()) {
        $status = ['label' => __('قادم'), 'class' => 'upcoming', 'icon' => 'bi-clock-history'];
    } else {
        $status = ['label' => __('نشط الآن'), 'class' => 'active', 'icon' => 'bi-broadcast'];
    }
@endphp

@section('content')
<div class="poll-page">
    <div class="poll-shell">
        <section class="poll-hero">
            <div class="poll-hero-inner">
                <div>
                    <span class="poll-badge">
                        <i class="bi bi-pencil-fill"></i>
                        {{ __('تعديل الاستطلاع') }} #{{ $poll->id }}
                    </span>
                    <h1 class="poll-title">{{ __('مراجعة دقيقة للبيانات قبل اعتماد التحديث') }}</h1>
                    <div class="poll-meta-row">
                        <span class="poll-status-badge {{ $status['class'] }}">
                            <i class="bi {{ $status['icon'] }}"></i>
                            {{ $status['label'] }}
                        </span>
                        <span class="poll-chip">
                            <i class="bi bi-hand-thumbs-up-fill"></i>
                            {{ number_format($totalVotes) }} {{ __('صوت') }}
                        </span>
                    </div>
                </div>

                <div class="poll-hero-actions">
                    <a href="{{ route('polls.show', $poll) }}" class="poll-btn">
                        <i class="bi bi-eye-fill"></i>
                        {{ __('عرض الاستطلاع') }}
                    </a>
                    <a href="{{ route('polls.index') }}" class="poll-btn-muted">
                        <i class="bi bi-arrow-right-circle"></i>
                        {{ __('العودة للقائمة') }}
                    </a>
                </div>
            </div>
        </section>

        <div class="poll-form-layout">
            <div class="poll-shell" style="gap: 22px;">
                <section class="poll-card">
                    <div class="poll-card-header">
                        <div class="poll-card-title-wrap">
                            <span class="poll-card-icon"><i class="bi bi-sliders2"></i></span>
                            <div>
                                <h2 class="poll-card-title">{{ __('تحديث بيانات الاستطلاع') }}</h2>
                                <p class="poll-card-note">{{ __('حدّث السؤال والفترة الزمنية والربط بالمشاركين واجتماع الزوم من نفس النموذج.') }}</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('polls.update', $poll) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @include('polls.partials.form-core-fields', ['poll' => $poll])

                        <div class="poll-footer-actions" style="margin-top: 22px;">
                            <button type="submit" class="poll-btn">
                                <i class="bi bi-save2-fill"></i>
                                {{ __('حفظ التعديلات') }}
                            </button>
                            <a href="{{ route('polls.show', $poll) }}" class="poll-btn-muted">
                                <i class="bi bi-eye"></i>
                                {{ __('عرض') }}
                            </a>
                        </div>
                    </form>
                </section>

                <section class="poll-card">
                    <div class="poll-card-header">
                        <div class="poll-card-title-wrap">
                            <span class="poll-card-icon"><i class="bi bi-list-check"></i></span>
                            <div>
                                <h2 class="poll-card-title">{{ __('إدارة الخيارات الحالية') }}</h2>
                                <p class="poll-card-note">{{ __('أي تعديل على نصوص الخيارات أو حذفها سيؤثر مباشرة على عرض النتائج الحالية للاستطلاع.') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($poll->pollOptions->count() > 0)
                        <div class="poll-option-list">
                            @foreach($poll->pollOptions as $option)
                                <article class="poll-option-card">
                                    <div class="poll-option-top">
                                        <span class="poll-option-order">{{ $loop->iteration }}</span>
                                        <div>
                                            <h3 class="poll-option-title">{{ __('الخيار') }}</h3>
                                            <div class="poll-option-meta">{{ number_format($option->votes) }} {{ __('صوت مسجل لهذا الخيار') }}</div>
                                        </div>
                                    </div>

                                    <form action="{{ route('poll-options.update', $option) }}" method="POST" class="poll-option-stack">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="poll_id" value="{{ $poll->id }}">
                                        <input type="hidden" name="return_to_poll" value="1">

                                        <div class="poll-option-input-row">
                                            <input type="text" name="option_text" class="poll-input" value="{{ $option->option_text }}" required>
                                            <button type="submit" class="poll-btn-muted">
                                                <i class="bi bi-check2"></i>
                                                {{ __('حفظ') }}
                                            </button>
                                        </div>
                                    </form>

                                    <form action="{{ route('poll-options.destroy', $option) }}" method="POST" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا الخيار؟ سيتم حذف الأصوات المرتبطة به أيضًا.') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="return_to_poll" value="1">

                                        <button type="submit" class="poll-btn-danger">
                                            <i class="bi bi-trash3-fill"></i>
                                            {{ __('حذف الخيار') }}
                                        </button>
                                    </form>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="poll-empty-state">
                            <i class="bi bi-ui-checks-grid"></i>
                            <h3>{{ __('لا توجد خيارات مضافة') }}</h3>
                            <p>{{ __('يمكنك إضافة خيارات جديدة من النموذج التالي ليصبح الاستطلاع جاهزًا للتصويت.') }}</p>
                        </div>
                    @endif
                </section>

                <section class="poll-card">
                    <div class="poll-card-header">
                        <div class="poll-card-title-wrap">
                            <span class="poll-card-icon"><i class="bi bi-plus-square-fill"></i></span>
                            <div>
                                <h2 class="poll-card-title">{{ __('إضافة خيار جديد') }}</h2>
                                <p class="poll-card-note">{{ __('أضف خيارًا إضافيًا دون مغادرة الصفحة الحالية.') }}</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('poll-options.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="poll_id" value="{{ $poll->id }}">
                        <input type="hidden" name="return_to_poll" value="1">

                        <div class="poll-field">
                            <label for="new_option_text">{{ __('نص الخيار الجديد') }}</label>
                            <div class="poll-option-input-row">
                                <input type="text" id="new_option_text" name="option_text" class="poll-input" placeholder="{{ __('اكتب نص الخيار الجديد') }}" required>
                                <button type="submit" class="poll-btn">
                                    <i class="bi bi-plus-circle"></i>
                                    {{ __('إضافة') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </section>
            </div>

            <aside class="poll-shell" style="gap: 18px;">
                <section class="poll-card">
                    <div class="poll-card-header">
                        <div class="poll-card-title-wrap">
                            <span class="poll-card-icon"><i class="bi bi-speedometer2"></i></span>
                            <div>
                                <h2 class="poll-card-title">{{ __('ملخص سريع') }}</h2>
                                <p class="poll-card-note">{{ __('أهم المؤشرات المرتبطة بهذا الاستطلاع أثناء التعديل.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="poll-detail-grid" style="grid-template-columns: 1fr;">
                        <div class="poll-detail-item">
                            <span class="poll-detail-label">{{ __('عدد الخيارات') }}</span>
                            <div class="poll-detail-value">{{ number_format($poll->pollOptions->count()) }}</div>
                        </div>
                        <div class="poll-detail-item">
                            <span class="poll-detail-label">{{ __('إجمالي الأصوات') }}</span>
                            <div class="poll-detail-value">{{ number_format($totalVotes) }}</div>
                        </div>
                        <div class="poll-detail-item">
                            <span class="poll-detail-label">{{ __('المشاركون المستهدفون') }}</span>
                            <div class="poll-detail-value">{{ number_format($targetedUsersCount) }}</div>
                        </div>
                        <div class="poll-detail-item">
                            <span class="poll-detail-label">{{ __('المنشئ') }}</span>
                            <div class="poll-detail-value">{{ optional($poll->creator)->name ?? __('غير معروف') }}</div>
                        </div>
                    </div>
                </section>

                <section class="poll-card">
                    <div class="poll-card-header">
                        <div class="poll-card-title-wrap">
                            <span class="poll-card-icon"><i class="bi bi-clock-history"></i></span>
                            <div>
                                <h2 class="poll-card-title">{{ __('الحالة الزمنية') }}</h2>
                                <p class="poll-card-note">{{ __('تواريخ الإنشاء والبدء والانتهاء لمراجعة الحالة الحالية بسرعة.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="poll-mini-stats">
                        <div class="poll-mini-stat">
                            <span class="poll-mini-label">{{ __('تاريخ الإنشاء') }}</span>
                            <div class="poll-mini-value">{{ optional($poll->created_date)->format('Y-m-d H:i') ?? __('غير متوفر') }}</div>
                        </div>
                        <div class="poll-mini-stat">
                            <span class="poll-mini-label">{{ __('تاريخ البدء') }}</span>
                            <div class="poll-mini-value">{{ $poll->start_date->format('Y-m-d H:i') }}</div>
                        </div>
                        <div class="poll-mini-stat">
                            <span class="poll-mini-label">{{ __('تاريخ الانتهاء') }}</span>
                            <div class="poll-mini-value">{{ $poll->end_date->format('Y-m-d H:i') }}</div>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</div>
@endsection
