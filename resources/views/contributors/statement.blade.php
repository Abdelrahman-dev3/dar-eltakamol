@extends('layouts.app')

@section('title', __('كشف حساب المساهم'))

@include('shares-trans.partials.styles')

@php
    $currentValue = $currentShares * $currentSharePrice;
    $soldSharesTotal = $sellShares->sum(fn ($offer) => (float) $offer->count);
    $purchaseOrdersTotal = $purchaseOrders->sum(fn ($order) => (float) $order->count);
    $movementSharesTotal = $movements->sum(fn ($movement) => (float) $movement->shares_count);

    $sellStatusMap = [
        \App\Models\SellShares::AD_STATUS_INITIAL => ['label' => __('مبدئي'), 'class' => 'neutral'],
        \App\Models\SellShares::AD_STATUS_ACTIVE => ['label' => __('نشط'), 'class' => 'success'],
        \App\Models\SellShares::AD_STATUS_COMPLETED => ['label' => __('مكتمل'), 'class' => 'success'],
        \App\Models\SellShares::AD_STATUS_CANCELLED => ['label' => __('ملغي'), 'class' => 'danger'],
    ];

    $poStatusMap = [
        \App\Models\SharesPO::PO_STATUS_PENDING => ['label' => __('في الانتظار'), 'class' => 'warning'],
        \App\Models\SharesPO::PO_STATUS_REVIEW => ['label' => __('قيد المراجعة'), 'class' => 'neutral'],
        \App\Models\SharesPO::PO_STATUS_COMPLETED => ['label' => __('مكتمل'), 'class' => 'success'],
    ];
@endphp

@section('content')
    <div class="st-page">
        <div class="st-shell">
            <section class="st-hero">
                <div class="st-hero-inner">
                    <div>
                        <span class="st-hero-badge">
                            <i class="bi bi-file-earmark-text-fill"></i>
                            {{ __('كشف حساب المساهم') }}
                        </span>
                        <h1 class="st-hero-title">{{ $contributor->name }}</h1>
                        <p class="st-hero-subtitle">
                            {{ __('ملخص كامل للأرصدة الحالية، عروض البيع، طلبات الشراء، والحركات التاريخية المرتبطة بهذا المساهم.') }}
                        </p>
                    </div>

                    <div class="st-hero-actions">
                        <a href="{{ route('contributors.show', $contributor) }}" class="st-btn st-btn-info">
                            <i class="bi bi-person-lines-fill"></i>
                            {{ __('تفاصيل المساهم') }}
                        </a>
                        <a href="{{ route('contributors.index') }}" class="st-btn st-btn-secondary">
                            <i class="bi bi-arrow-right-circle"></i>
                            {{ __('العودة للمساهمين') }}
                        </a>
                    </div>
                </div>
            </section>

            <section class="st-stat-grid">
                <article class="st-stat-card">
                    <div class="st-stat-icon"><i class="bi bi-layers-fill"></i></div>
                    <p class="st-stat-value">{{ number_format($currentShares, 2) }}</p>
                    <p class="st-stat-label">{{ __('عدد الأسهم الحالي') }}</p>
                </article>
                <article class="st-stat-card">
                    <div class="st-stat-icon"><i class="bi bi-pie-chart-fill"></i></div>
                    <p class="st-stat-value">{{ number_format($ownershipPercentage, 4) }}%</p>
                    <p class="st-stat-label">{{ __('نسبة الأسهم من الإجمالي') }}</p>
                </article>
                <article class="st-stat-card">
                    <div class="st-stat-icon"><i class="bi bi-cash-coin"></i></div>
                    <p class="st-stat-value">{{ number_format($currentSharePrice, 2) }}</p>
                    <p class="st-stat-label">{{ __('سعر السهم الحالي') }}</p>
                </article>
                <article class="st-stat-card">
                    <div class="st-stat-icon"><i class="bi bi-calculator-fill"></i></div>
                    <p class="st-stat-value">{{ number_format($currentValue, 2) }}</p>
                    <p class="st-stat-label">{{ __('القيمة التقديرية الحالية') }}</p>
                </article>
            </section>

            <section class="st-summary-grid">
                <article class="st-summary-card">
                    <div class="st-summary-icon"><i class="bi bi-megaphone-fill"></i></div>
                    <p class="st-summary-value">{{ number_format($sellShares->count()) }}</p>
                    <p class="st-summary-label">{{ __('عروض بيع مقدمة') }} - {{ number_format($soldSharesTotal, 2) }} {{ __('سهم') }}</p>
                </article>
                <article class="st-summary-card">
                    <div class="st-summary-icon"><i class="bi bi-cart-check-fill"></i></div>
                    <p class="st-summary-value">{{ number_format($purchaseOrders->count()) }}</p>
                    <p class="st-summary-label">{{ __('طلبات شراء قدمها') }} - {{ number_format($purchaseOrdersTotal, 2) }} {{ __('سهم') }}</p>
                </article>
                <article class="st-summary-card">
                    <div class="st-summary-icon"><i class="bi bi-arrow-left-right"></i></div>
                    <p class="st-summary-value">{{ number_format($movements->count()) }}</p>
                    <p class="st-summary-label">{{ __('حركات مرتبطة') }} - {{ number_format($movementSharesTotal, 2) }} {{ __('سهم') }}</p>
                </article>
                <article class="st-summary-card">
                    <div class="st-summary-icon"><i class="bi bi-list-check"></i></div>
                    <p class="st-summary-value">{{ number_format($shareTransLines->count()) }}</p>
                    <p class="st-summary-label">{{ __('بنود معاملات أسهم تاريخية') }}</p>
                </article>
            </section>

            <section class="st-card">
                <div class="st-card-header">
                    <div class="st-card-title-wrap">
                        <span class="st-card-icon"><i class="bi bi-person-vcard-fill"></i></span>
                        <div>
                            <h2 class="st-card-title">{{ __('بيانات المساهم') }}</h2>
                        </div>
                    </div>
                </div>

                <div class="st-summary-grid">
                    <div class="st-info-item">
                        <span class="st-info-label">{{ __('رقم الهوية') }}</span>
                        <span class="st-info-value">{{ $contributor->id_number ?: __('غير متوفر') }}</span>
                    </div>
                    <div class="st-info-item">
                        <span class="st-info-label">{{ __('الجوال') }}</span>
                        <span class="st-info-value">{{ $contributor->phone_num ?: __('غير متوفر') }}</span>
                    </div>
                    <div class="st-info-item">
                        <span class="st-info-label">{{ __('البنك') }}</span>
                        <span class="st-info-value">{{ $contributor->bank_name ?: __('غير متوفر') }}</span>
                    </div>
                    <div class="st-info-item">
                        <span class="st-info-label">{{ __('الشركات والإدارات') }}</span>
                        <span class="st-info-value">
                            {{ $contributor->departments->pluck('name')->filter()->implode('، ') ?: __('غير مرتبط بإدارات') }}
                        </span>
                    </div>
                </div>
            </section>

            <section class="st-card">
                <div class="st-card-header">
                    <div class="st-card-title-wrap">
                        <span class="st-card-icon"><i class="bi bi-megaphone-fill"></i></span>
                        <div>
                            <h2 class="st-card-title">{{ __('عمليات البيع وعروض الشراء عليها') }}</h2>
                            <p class="st-card-subtitle">{{ __('كل عروض البيع التي أنشأها هذا المساهم مع طلبات الشراء المرتبطة بكل عرض.') }}</p>
                        </div>
                    </div>
                </div>

                @forelse($sellShares as $offer)
                    @php
                        $sellStatus = $sellStatusMap[(int) $offer->ad_status] ?? ['label' => __('غير محدد'), 'class' => 'neutral'];
                    @endphp
                    <div class="st-table-wrap" style="margin-bottom: 1rem;">
                        <div class="st-table-scroll">
                            <table class="st-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('عرض البيع') }}</th>
                                        <th>{{ __('تاريخ العرض') }}</th>
                                        <th>{{ __('عدد الأسهم') }}</th>
                                        <th>{{ __('سعر السهم') }}</th>
                                        <th>{{ __('الإجمالي') }}</th>
                                        <th>{{ __('الحالة') }}</th>
                                        <th>{{ __('الإجراءات') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>#{{ $offer->id }}</td>
                                        <td>{{ $offer->insert_date?->format('Y-m-d') ?: __('غير محدد') }}</td>
                                        <td>{{ number_format((float) $offer->count, 2) }}</td>
                                        <td>{{ number_format((float) $offer->amount_per_share, 2) }}</td>
                                        <td>{{ number_format((float) $offer->total_amount, 2) }}</td>
                                        <td><span class="st-badge st-badge-{{ $sellStatus['class'] }}">{{ $sellStatus['label'] }}</span></td>
                                        <td>
                                            <a href="{{ route('sell-shares.show', $offer) }}" class="st-icon-btn st-icon-btn-info" title="{{ __('عرض') }}">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($offer->sharesPOs->isNotEmpty())
                        <div class="st-table-wrap" style="margin: -0.4rem 0 1.2rem;">
                            <div class="st-table-scroll">
                                <table class="st-table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('طلب الشراء') }}</th>
                                            <th>{{ __('المشتري') }}</th>
                                            <th>{{ __('العدد') }}</th>
                                            <th>{{ __('سعر السهم') }}</th>
                                            <th>{{ __('الحالة') }}</th>
                                            <th>{{ __('مقبول') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($offer->sharesPOs as $order)
                                            @php
                                                $poStatus = $poStatusMap[(int) $order->po_status] ?? ['label' => __('غير محدد'), 'class' => 'neutral'];
                                            @endphp
                                            <tr>
                                                <td>#{{ $order->id }}</td>
                                                <td>{{ $order->contributor?->name ?: __('غير معروف') }}</td>
                                                <td>{{ number_format((float) $order->count, 2) }}</td>
                                                <td>{{ number_format((float) $order->amount_per_share, 2) }}</td>
                                                <td><span class="st-badge st-badge-{{ $poStatus['class'] }}">{{ $poStatus['label'] }}</span></td>
                                                <td>{{ $order->accept ? __('نعم') : __('لا') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="st-empty show">
                        <div class="st-empty-icon"><i class="bi bi-folder-x"></i></div>
                        <h3>{{ __('لا توجد عروض بيع') }}</h3>
                        <p>{{ __('لم يقم هذا المساهم بإنشاء عروض بيع حتى الآن.') }}</p>
                    </div>
                @endforelse
            </section>

            <section class="st-card">
                <div class="st-card-header">
                    <div class="st-card-title-wrap">
                        <span class="st-card-icon"><i class="bi bi-cart-check-fill"></i></span>
                        <div>
                            <h2 class="st-card-title">{{ __('طلبات الشراء التي قدمها المساهم') }}</h2>
                        </div>
                    </div>
                </div>

                @if($purchaseOrders->isNotEmpty())
                    <div class="st-table-wrap">
                        <div class="st-table-scroll">
                            <table class="st-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('رقم الطلب') }}</th>
                                        <th>{{ __('عرض البيع') }}</th>
                                        <th>{{ __('البائع') }}</th>
                                        <th>{{ __('العدد') }}</th>
                                        <th>{{ __('سعر السهم') }}</th>
                                        <th>{{ __('الإجمالي') }}</th>
                                        <th>{{ __('الحالة') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($purchaseOrders as $order)
                                        @php
                                            $poStatus = $poStatusMap[(int) $order->po_status] ?? ['label' => __('غير محدد'), 'class' => 'neutral'];
                                        @endphp
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>#{{ $order->sale_number }}</td>
                                            <td>{{ $order->sellShare?->seller?->name ?: __('غير معروف') }}</td>
                                            <td>{{ number_format((float) $order->count, 2) }}</td>
                                            <td>{{ number_format((float) $order->amount_per_share, 2) }}</td>
                                            <td>{{ number_format((float) $order->total_amount, 2) }}</td>
                                            <td><span class="st-badge st-badge-{{ $poStatus['class'] }}">{{ $poStatus['label'] }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="st-empty show">
                        <div class="st-empty-icon"><i class="bi bi-cart-x"></i></div>
                        <h3>{{ __('لا توجد طلبات شراء') }}</h3>
                        <p>{{ __('لم يقدم هذا المساهم طلبات شراء حتى الآن.') }}</p>
                    </div>
                @endif
            </section>

            <section class="st-card">
                <div class="st-card-header">
                    <div class="st-card-title-wrap">
                        <span class="st-card-icon"><i class="bi bi-clock-history"></i></span>
                        <div>
                            <h2 class="st-card-title">{{ __('الحركات المرتبطة بالمساهم') }}</h2>
                        </div>
                    </div>
                </div>

                @if($movements->isNotEmpty())
                    <div class="st-table-wrap">
                        <div class="st-table-scroll">
                            <table class="st-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('التاريخ') }}</th>
                                        <th>{{ __('النوع') }}</th>
                                        <th>{{ __('من') }}</th>
                                        <th>{{ __('إلى') }}</th>
                                        <th>{{ __('الأسهم') }}</th>
                                        <th>{{ __('سعر السهم') }}</th>
                                        <th>{{ __('رصيد قبل/بعد') }}</th>
                                        <th>{{ __('الوصف') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($movements as $movement)
                                        @php
                                            $isFrom = (int) $movement->from_contributor_id === (int) $contributor->id;
                                            $balanceBefore = $isFrom ? $movement->from_balance_before : $movement->to_balance_before;
                                            $balanceAfter = $isFrom ? $movement->from_balance_after : $movement->to_balance_after;
                                        @endphp
                                        <tr>
                                            <td>{{ $movement->date?->format('Y-m-d') }}</td>
                                            <td>@include('shares-trans.partials.type-badge', ['type' => $movement->movement_type])</td>
                                            <td>{{ $movement->fromContributor?->name ?: __('غير محدد') }}</td>
                                            <td>{{ $movement->toContributor?->name ?: __('غير محدد') }}</td>
                                            <td>{{ number_format((float) $movement->shares_count, 2) }}</td>
                                            <td>{{ number_format((float) $movement->amount_per_share, 2) }}</td>
                                            <td>
                                                @if(!is_null($balanceBefore))
                                                    {{ number_format((float) $balanceBefore, 2) }} -> {{ number_format((float) $balanceAfter, 2) }}
                                                @else
                                                    {{ __('غير متوفر') }}
                                                @endif
                                            </td>
                                            <td>{{ $movement->description }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="st-empty show">
                        <div class="st-empty-icon"><i class="bi bi-journal-x"></i></div>
                        <h3>{{ __('لا توجد حركات') }}</h3>
                        <p>{{ __('لا توجد حركات مساهمين مسجلة على هذا المساهم حتى الآن.') }}</p>
                    </div>
                @endif
            </section>

            @if($shareTransLines->isNotEmpty())
                <section class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-title-wrap">
                            <span class="st-card-icon"><i class="bi bi-list-check"></i></span>
                            <div>
                                <h2 class="st-card-title">{{ __('بنود معاملات الأسهم التاريخية') }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="st-table-wrap">
                        <div class="st-table-scroll">
                            <table class="st-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('رقم البند') }}</th>
                                        <th>{{ __('رقم المعاملة') }}</th>
                                        <th>{{ __('التاريخ') }}</th>
                                        <th>{{ __('مدين') }}</th>
                                        <th>{{ __('دائن') }}</th>
                                        <th>{{ __('سعر السهم') }}</th>
                                        <th>{{ __('الملاحظات') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($shareTransLines as $line)
                                        <tr>
                                            <td>#{{ $line->id }}</td>
                                            <td>#{{ $line->trans_id }}</td>
                                            <td>{{ $line->sharesTrans?->date?->format('Y-m-d') ?: $line->created_at?->format('Y-m-d') }}</td>
                                            <td>{{ number_format((float) $line->count_debit, 2) }}</td>
                                            <td>{{ number_format((float) $line->count_credit, 2) }}</td>
                                            <td>{{ number_format((float) $line->amount_per_share, 2) }}</td>
                                            <td>{{ $line->line_notes ?: __('لا توجد') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection
