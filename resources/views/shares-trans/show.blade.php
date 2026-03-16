@extends('layouts.app')

@section('title', __('عرض معاملة الأسهم'))

@include('shares-trans.partials.styles')

@php
    $lines = $shares_tran->shareTransLines;
    $detailsCount = $lines->count();
    $approvedLinesCount = $lines->where('posted', true)->count();
    $netShares = $lines->sum(fn ($line) => (float) $line->count_debit - (float) $line->count_credit);
    $netAmount = $lines->sum(fn ($line) => ((float) $line->count_debit - (float) $line->count_credit) * (float) $line->amount_per_share);
@endphp

@section('content')
    <div class="st-page">
        <div class="st-shell">
            <section class="st-hero">
                <div class="st-hero-inner">
                    <div>
                        <span class="st-hero-badge">
                            <i class="bi bi-file-earmark-text-fill"></i>
                            {{ __('عرض المعاملة') }} #{{ $shares_tran->id }}
                        </span>
                        <h1 class="st-hero-title">{{ __('تفاصيل معاملة الأسهم') }}</h1>
                        <div class="st-chip-row" style="margin-top: 1rem;">
                            @include('shares-trans.partials.type-badge', ['type' => $shares_tran->trans_type])
                            @include('shares-trans.partials.status-badge', ['posted' => $shares_tran->posted])
                            <span class="st-chip">
                                <i class="bi bi-list-check"></i>
                                {{ $detailsCount }} {{ __('تفصيل') }}
                            </span>
                        </div>
                    </div>

                    <div class="st-hero-actions">
                        <a href="{{ route('shares-trans.edit', $shares_tran) }}" class="st-btn st-btn-warning">
                            <i class="bi bi-pencil-square"></i>
                            {{ __('تعديل') }}
                        </a>
                        <a href="{{ route('shares-trans.index') }}" class="st-btn st-btn-secondary">
                            <i class="bi bi-arrow-right-circle"></i>
                            {{ __('العودة للقائمة') }}
                        </a>
                    </div>
                </div>
            </section>

            <section class="st-summary-grid">
                <article class="st-summary-card" style="animation-delay: 0.05s;">
                    <div class="st-summary-icon">
                        <i class="bi bi-list-ol"></i>
                    </div>
                    <p class="st-summary-value">{{ number_format($detailsCount) }}</p>
                    <p class="st-summary-label">{{ __('إجمالي التفاصيل') }}</p>
                </article>

                <article class="st-summary-card" style="animation-delay: 0.12s;">
                    <div class="st-summary-icon">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                    <p class="st-summary-value">{{ number_format($approvedLinesCount) }}</p>
                    <p class="st-summary-label">{{ __('التفاصيل المعتمدة') }}</p>
                </article>

                <article class="st-summary-card" style="animation-delay: 0.19s;">
                    <div class="st-summary-icon">
                        <i class="bi bi-bar-chart-line-fill"></i>
                    </div>
                    <p class="st-summary-value">{{ number_format($netShares, 0) }}</p>
                    <p class="st-summary-label">{{ __('صافي الأسهم') }}</p>
                </article>

                <article class="st-summary-card" style="animation-delay: 0.26s;">
                    <div class="st-summary-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <p class="st-summary-value">{{ number_format($netAmount, 2) }}</p>
                    <p class="st-summary-label">{{ __('صافي القيمة') }} {{ __('ريال') }}</p>
                </article>
            </section>

            <div class="st-grid-two">
                <div class="st-shell">
                    <section class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-title-wrap">
                                <span class="st-card-icon">
                                    <i class="bi bi-info-circle-fill"></i>
                                </span>
                                <div>
                                    <h2 class="st-card-title">{{ __('المعلومات الأساسية') }}</h2>
                                    <p class="st-card-subtitle">{{ __('تفاصيل المعاملة الرئيسية التي تساعدك على التعرف عليها ومراجعة حالتها بسرعة.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="st-info-list">
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('رقم المعاملة') }}</span>
                                <div class="st-info-value">#{{ $shares_tran->id }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('تاريخ المعاملة') }}</span>
                                <div class="st-info-value">{{ $shares_tran->date?->format('Y-m-d') ?? __('غير متوفر') }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('نوع المعاملة') }}</span>
                                <div class="st-info-value">@include('shares-trans.partials.type-badge', ['type' => $shares_tran->trans_type])</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('الحالة') }}</span>
                                <div class="st-info-value">@include('shares-trans.partials.status-badge', ['posted' => $shares_tran->posted])</div>
                            </div>
                        </div>
                    </section>

                    @if($shares_tran->notes)
                        <section class="st-card">
                            <div class="st-card-header">
                                <div class="st-card-title-wrap">
                                    <span class="st-card-icon">
                                        <i class="bi bi-chat-left-text-fill"></i>
                                    </span>
                                    <div>
                                        <h2 class="st-card-title">{{ __('ملاحظات المعاملة') }}</h2>
                                        <p class="st-card-subtitle">{{ __('أي وصف أو سياق مضاف لهذه المعاملة يظهر هنا بشكل واضح.') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="st-note-box">
                                <i class="bi bi-quote"></i>
                                <div>{{ $shares_tran->notes }}</div>
                            </div>
                        </section>
                    @endif

                    <section class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-title-wrap">
                                <span class="st-card-icon">
                                    <i class="bi bi-table"></i>
                                </span>
                                <div>
                                    <h2 class="st-card-title">{{ __('تفاصيل المعاملة') }}</h2>
                                    <p class="st-card-subtitle">{{ __('عرض تفصيلي للسطور المرتبطة بهذه المعاملة مع صافي الأسهم والقيمة لكل سطر.') }}</p>
                                </div>
                            </div>

                            <div class="st-inline-actions">
                                @if($detailsCount > 0)
                                    <a href="{{ route('share-trans-lines.index', ['trans_id' => $shares_tran->id]) }}" class="st-btn st-btn-secondary">
                                        <i class="bi bi-list-ul"></i>
                                        {{ __('عرض جميع التفاصيل') }}
                                    </a>
                                @else
                                    <a href="{{ route('share-trans-lines.create', ['trans_id' => $shares_tran->id]) }}" class="st-btn st-btn-success">
                                        <i class="bi bi-plus-circle-fill"></i>
                                        {{ __('إضافة تفاصيل') }}
                                    </a>
                                @endif
                            </div>
                        </div>

                        @if($detailsCount > 0)
                            <div class="st-table-wrap">
                                <div class="st-table-scroll">
                                    <table class="st-table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('المساهم') }}</th>
                                                <th class="st-text-center">{{ __('مدين') }}</th>
                                                <th class="st-text-center">{{ __('دائن') }}</th>
                                                <th class="st-text-center">{{ __('الصافي') }}</th>
                                                <th class="st-text-right">{{ __('السعر') }}</th>
                                                <th class="st-text-right">{{ __('الإجمالي') }}</th>
                                                <th class="st-text-center">{{ __('الحالة') }}</th>
                                                <th class="st-text-center">{{ __('الإجراءات') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($lines as $line)
                                                @php
                                                    $lineNetShares = (float) $line->count_debit - (float) $line->count_credit;
                                                    $lineAmount = $lineNetShares * (float) $line->amount_per_share;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <strong>{{ $line->contributor->name ?? $line->contributor->user->name ?? __('غير معروف') }}</strong>
                                                    </td>
                                                    <td class="st-text-center">{{ number_format($line->count_debit, 0) }}</td>
                                                    <td class="st-text-center">{{ number_format($line->count_credit, 0) }}</td>
                                                    <td class="st-text-center">{{ number_format($lineNetShares, 0) }}</td>
                                                    <td class="st-text-right">{{ number_format($line->amount_per_share, 2) }} {{ __('ريال') }}</td>
                                                    <td class="st-text-right">{{ number_format($lineAmount, 2) }} {{ __('ريال') }}</td>
                                                    <td class="st-text-center">
                                                        @include('shares-trans.partials.status-badge', ['posted' => $line->posted])
                                                    </td>
                                                    <td class="st-text-center">
                                                        <div class="st-inline-actions" style="justify-content: center;">
                                                            <a href="{{ route('share-trans-lines.show', $line) }}" class="st-icon-btn st-icon-btn-info" title="{{ __('عرض') }}">
                                                                <i class="bi bi-eye-fill"></i>
                                                            </a>
                                                            <a href="{{ route('share-trans-lines.edit', $line) }}" class="st-icon-btn st-icon-btn-warning" title="{{ __('تعديل') }}">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3">{{ __('المجموع') }}</th>
                                                <th class="st-text-center">{{ number_format($netShares, 0) }}</th>
                                                <th></th>
                                                <th class="st-text-right">{{ number_format($netAmount, 2) }} {{ __('ريال') }}</th>
                                                <th colspan="2"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="st-empty show">
                                <div class="st-empty-icon">
                                    <i class="bi bi-list-stars"></i>
                                </div>
                                <h3>{{ __('لا توجد تفاصيل للمعاملة بعد') }}</h3>
                                <p>{{ __('يمكنك إضافة أول سطر تفصيلي الآن حتى يكتمل سجل المعاملة ويصبح جاهزًا للمراجعة والاعتماد.') }}</p>
                                <div style="margin-top: 1rem;">
                                    <a href="{{ route('share-trans-lines.create', ['trans_id' => $shares_tran->id]) }}" class="st-btn st-btn-success">
                                        <i class="bi bi-plus-circle-fill"></i>
                                        {{ __('إضافة تفاصيل المعاملة') }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </section>
                </div>

                <div class="st-shell">
                    <section class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-title-wrap">
                                <span class="st-card-icon">
                                    <i class="bi bi-clock-history"></i>
                                </span>
                                <div>
                                    <h2 class="st-card-title">{{ __('بيانات إضافية') }}</h2>
                                    <p class="st-card-subtitle">{{ __('معلومات زمنية تساعد في تتبع إنشاء السجل وآخر تعديل عليه.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="st-info-list">
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('تاريخ الإنشاء') }}</span>
                                <div class="st-info-value">{{ $shares_tran->created_at?->format('Y-m-d H:i') ?? __('غير متوفر') }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('آخر تحديث') }}</span>
                                <div class="st-info-value">{{ $shares_tran->updated_at?->format('Y-m-d H:i') ?? __('غير متوفر') }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('منذ الإنشاء') }}</span>
                                <div class="st-info-value">{{ $shares_tran->created_at?->diffForHumans() ?? __('غير متوفر') }}</div>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('منذ آخر تحديث') }}</span>
                                <div class="st-info-value">{{ $shares_tran->updated_at?->diffForHumans() ?? __('غير متوفر') }}</div>
                            </div>
                        </div>
                    </section>

                    <section class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-title-wrap">
                                <span class="st-card-icon">
                                    <i class="bi bi-lightning-charge-fill"></i>
                                </span>
                                <div>
                                    <h2 class="st-card-title">{{ __('إجراءات سريعة') }}</h2>
                                    <p class="st-card-subtitle">{{ __('كل الإجراءات الرئيسية متاحة هنا بدون تشتيت وبما يتوافق مع حالة السجل.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="st-inline-actions" style="width: 100%;">
                            <a href="{{ route('shares-trans.edit', $shares_tran) }}" class="st-btn st-btn-warning">
                                <i class="bi bi-pencil-square"></i>
                                {{ __('تعديل المعاملة') }}
                            </a>

                            @if(!$shares_tran->posted)
                                <form action="{{ route('shares-trans.post', $shares_tran) }}" method="POST" style="width: 100%;">
                                    @csrf
                                    <button type="submit" class="st-btn st-btn-success" style="width: 100%;" data-confirm="{{ __('هل أنت متأكد من اعتماد هذه المعاملة؟') }}">
                                        <i class="bi bi-patch-check-fill"></i>
                                        {{ __('اعتماد المعاملة') }}
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('shares-trans.destroy', $shares_tran) }}" method="POST" style="width: 100%;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="st-btn st-btn-danger" style="width: 100%;" data-confirm="{{ __('هل أنت متأكد من حذف هذه المعاملة؟ هذا الإجراء لا يمكن التراجع عنه.') }}">
                                    <i class="bi bi-trash3-fill"></i>
                                    {{ __('حذف المعاملة') }}
                                </button>
                            </form>
                        </div>

                        @if($shares_tran->posted)
                            <div class="st-banner is-warning" style="margin-top: 1rem;">
                                <i class="bi bi-shield-lock-fill"></i>
                                <div>{{ __('هذه المعاملة معتمدة حاليًا، لذلك يفضل مراجعة التفاصيل فقط ما لم تكن هناك حاجة إدارية لتعديلها عبر الإجراءات المسموح بها.') }}</div>
                            </div>
                        @else
                            <div class="st-banner" style="margin-top: 1rem;">
                                <i class="bi bi-info-circle-fill"></i>
                                <div>{{ __('المعاملة ما زالت غير معتمدة، ويمكنك تعديلها أو استكمال تفاصيلها قبل اعتمادها نهائيًا.') }}</div>
                            </div>
                        @endif
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-confirm]').forEach(function (element) {
                element.addEventListener('click', function (event) {
                    const message = this.getAttribute('data-confirm');

                    if (message && !window.confirm(message)) {
                        event.preventDefault();
                    }
                });
            });
        });
    </script>
@endpush
