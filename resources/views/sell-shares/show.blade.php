@extends('layouts.app')

@section('title', __('عرض تفاصيل عرض البيع'))

@include('sell-shares.partials.styles')

@php
    $sellerName = $sellShare->seller->name ?? $sellShare->seller->user->name ?? __('غير معروف');
    $orders = $sellShare->sharesPOs;
    $acceptedOrders = $orders->where('accept', true)->count();
    $pendingOrders = $orders->where('accept', false)->count();
    $orderedShares = $orders->sum(fn ($po) => (float) $po->count);
@endphp

@section('content')
    <div class="ss-page">
        <div class="ss-shell">
            <section class="ss-hero">
                <div class="ss-hero-inner">
                    <div>
                        <span class="ss-badge-top">
                            <i class="bi bi-file-earmark-richtext-fill"></i>
                            {{ __('تفاصيل العرض') }} #{{ $sellShare->id }}
                        </span>
                        <h1 class="ss-title">{{ __('عرض بيع الأسهم') }}</h1>
                        <div class="ss-chip-row" style="margin-top: 1rem;">
                            @include('sell-shares.partials.status-badge', ['status' => $sellShare->ad_status])
                            <span class="ss-chip">
                                <i class="bi bi-person-fill"></i>
                                {{ $sellerName }}
                            </span>
                            <span class="ss-chip">
                                <i class="bi bi-bag-heart-fill"></i>
                                {{ $orders->count() }} {{ __('طلب شراء') }}
                            </span>
                        </div>
                    </div>

                    <div class="ss-actions">
                        <a href="{{ route('sell-shares.edit', $sellShare) }}" class="ss-btn ss-btn-warning">
                            <i class="bi bi-pencil-square"></i>
                            {{ __('تعديل') }}
                        </a>
                        <a href="{{ route('sell-shares.print', $sellShare) }}" class="ss-btn ss-btn-info">
                            <i class="bi bi-printer-fill"></i>
                            {{ __('طباعة') }}
                        </a>
                        <form action="{{ route('sell-shares.settle', $sellShare) }}" method="POST" style="display: inline-flex;">
                            @csrf
                            <button type="submit" class="ss-btn ss-btn-primary" data-confirm="سيتم توزيع العرض آلياً على طلبات الشراء المقبولة بالتساوي. هل تريد المتابعة؟">
                                <i class="bi bi-diagram-3-fill"></i>
                                تسوية العرض
                            </button>
                        </form>
                        <a href="{{ route('sell-shares.index') }}" class="ss-btn ss-btn-secondary">
                            <i class="bi bi-arrow-right-circle"></i>
                            {{ __('العودة للعروض') }}
                        </a>
                    </div>
                </div>
            </section>

            <section class="ss-summary-grid">
                <article class="ss-summary-card" style="animation-delay: 0.05s;">
                    <div class="ss-summary-icon"><i class="bi bi-bar-chart-fill"></i></div>
                    <p class="ss-summary-value">{{ number_format($sellShare->count, 0) }}</p>
                    <p class="ss-summary-label">{{ __('عدد الأسهم') }}</p>
                </article>
                <article class="ss-summary-card" style="animation-delay: 0.12s;">
                    <div class="ss-summary-icon"><i class="bi bi-cash-stack"></i></div>
                    <p class="ss-summary-value">{{ number_format($sellShare->total_amount, 2) }}</p>
                    <p class="ss-summary-label">{{ __('القيمة الإجمالية') }} {{ __('ريال') }}</p>
                </article>
                <article class="ss-summary-card" style="animation-delay: 0.19s;">
                    <div class="ss-summary-icon"><i class="bi bi-check-circle-fill"></i></div>
                    <p class="ss-summary-value">{{ number_format($acceptedOrders) }}</p>
                    <p class="ss-summary-label">{{ __('طلبات الشراء المقبولة') }}</p>
                </article>
                <article class="ss-summary-card" style="animation-delay: 0.26s;">
                    <div class="ss-summary-icon"><i class="bi bi-hourglass-split"></i></div>
                    <p class="ss-summary-value">{{ number_format($pendingOrders) }}</p>
                    <p class="ss-summary-label">{{ __('طلبات الشراء قيد الانتظار') }}</p>
                </article>
            </section>

            <section class="ss-card" style="margin-bottom: 1rem;">
                <div class="ss-card-header">
                    <div class="ss-card-title-wrap">
                        <span class="ss-card-icon"><i class="bi bi-diagram-3-fill"></i></span>
                        <div>
                            <h2 class="ss-card-title">تسوية المادة السادسة</h2>
                            <p class="ss-card-subtitle">ملخص حد البيع السنوي والتوزيع الآلي والتزامات شراء الشركة.</p>
                        </div>
                    </div>
                </div>

                <div class="ss-grid-three">
                    <div class="ss-info-item">
                        <span class="ss-info-label">المتاح السنوي بعد استثناء هذا العرض</span>
                        <div class="ss-info-value">{{ number_format((float) $annualRemaining, 2) }} سهم</div>
                    </div>
                    <div class="ss-info-item">
                        <span class="ss-info-label">حالة التسوية</span>
                        <div class="ss-info-value">{{ $sellShare->settlement->status ?? 'لم تتم التسوية' }}</div>
                    </div>
                    <div class="ss-info-item">
                        <span class="ss-info-label">الأسهم المنقولة</span>
                        <div class="ss-info-value">{{ number_format((float) optional($sellShare->settlement)->transferred_count, 2) }} سهم</div>
                    </div>
                </div>

                @if($sellShare->settlement && $sellShare->settlement->allocations->count())
                    <div class="ss-table-wrap" style="margin-top: 1rem;">
                        <div class="ss-table-scroll">
                            <table class="ss-table">
                                <thead>
                                    <tr>
                                        <th>المشتري</th>
                                        <th class="ss-text-center">المخصص</th>
                                        <th class="ss-text-center">المدفوع</th>
                                        <th class="ss-text-center">المنقول</th>
                                        <th class="ss-text-center">الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sellShare->settlement->allocations as $allocation)
                                        <tr>
                                            <td>{{ $allocation->buyer->name ?? 'الشركة' }}</td>
                                            <td class="ss-text-center">{{ number_format((float) $allocation->shares_count, 2) }}</td>
                                            <td class="ss-text-center">{{ number_format((float) $allocation->paid_amount, 2) }}</td>
                                            <td class="ss-text-center">{{ number_format((float) $allocation->transferred_count, 2) }}</td>
                                            <td class="ss-text-center">{{ $allocation->status }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if($sellShare->companyPurchaseObligations->count())
                    <div class="ss-note-box warning" style="margin-top: 1rem;">
                        <i class="bi bi-building-fill"></i>
                        <div>
                            يوجد {{ $sellShare->companyPurchaseObligations->count() }} التزام شراء على الشركة لهذا العرض.
                            <a href="{{ route('company-purchase-obligations.index') }}">عرض الالتزامات</a>
                        </div>
                    </div>
                @endif
            </section>

            <div class="ss-grid-two">
                <div class="ss-shell">
                    <section class="ss-card">
                        <div class="ss-card-header">
                            <div class="ss-card-title-wrap">
                                <span class="ss-card-icon"><i class="bi bi-info-circle-fill"></i></span>
                                <div>
                                    <h2 class="ss-card-title">{{ __('المعلومات الأساسية') }}</h2>
                                    <p class="ss-card-subtitle">{{ __('بيانات العرض الرئيسية بشكل واضح وسهل القراءة على مختلف المقاسات.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="ss-info-list">
                            <div class="ss-info-item">
                                <span class="ss-info-label">{{ __('رقم العرض') }}</span>
                                <div class="ss-info-value">#{{ $sellShare->id }}</div>
                            </div>
                            <div class="ss-info-item">
                                <span class="ss-info-label">{{ __('المساهم') }}</span>
                                <div class="ss-info-value">{{ $sellerName }}</div>
                            </div>
                            <div class="ss-info-item">
                                <span class="ss-info-label">{{ __('عدد الأسهم') }}</span>
                                <div class="ss-info-value">{{ number_format($sellShare->count, 0) }} {{ __('سهم') }}</div>
                            </div>
                            <div class="ss-info-item">
                                <span class="ss-info-label">{{ __('السعر لكل سهم') }}</span>
                                <div class="ss-info-value">{{ number_format($sellShare->amount_per_share, 2) }} {{ __('ريال') }}</div>
                            </div>
                            <div class="ss-info-item">
                                <span class="ss-info-label">{{ __('المبلغ الإجمالي') }}</span>
                                <div class="ss-info-value">{{ number_format($sellShare->total_amount, 2) }} {{ __('ريال') }}</div>
                            </div>
                            <div class="ss-info-item">
                                <span class="ss-info-label">{{ __('حالة الإعلان') }}</span>
                                <div class="ss-info-value">@include('sell-shares.partials.status-badge', ['status' => $sellShare->ad_status])</div>
                            </div>
                        </div>
                    </section>

                    @if($sellShare->notes)
                        <section class="ss-card">
                            <div class="ss-card-header">
                                <div class="ss-card-title-wrap">
                                    <span class="ss-card-icon"><i class="bi bi-chat-left-text-fill"></i></span>
                                    <div>
                                        <h2 class="ss-card-title">{{ __('ملاحظات العرض') }}</h2>
                                        <p class="ss-card-subtitle">{{ __('أي وصف أو تعليق مضاف لهذا العرض يظهر هنا.') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="ss-note-box">
                                <i class="bi bi-quote"></i>
                                <div>{{ $sellShare->notes }}</div>
                            </div>
                        </section>
                    @endif

                    <section class="ss-card">
                        <div class="ss-card-header">
                            <div class="ss-card-title-wrap">
                                <span class="ss-card-icon"><i class="bi bi-cart-check-fill"></i></span>
                                <div>
                                    <h2 class="ss-card-title">{{ __('طلبات الشراء المرتبطة') }}</h2>
                                    <p class="ss-card-subtitle">{{ __('استعراض جميع طلبات الشراء المتصلة بهذا العرض مع حالتها والقيمة التقديرية.') }}</p>
                                </div>
                            </div>
                        </div>

                        @if($orders->count() > 0)
                            <div class="ss-table-wrap">
                                <div class="ss-table-scroll">
                                    <table class="ss-table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('رقم الطلب') }}</th>
                                                <th>{{ __('المشتري') }}</th>
                                                <th class="ss-text-center">{{ __('عدد الأسهم') }}</th>
                                                <th class="ss-text-right">{{ __('السعر') }}</th>
                                                <th class="ss-text-right">{{ __('الإجمالي') }}</th>
                                                <th class="ss-text-center">{{ __('الحالة') }}</th>
                                                <th class="ss-text-center">{{ __('الإجراءات') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($orders as $po)
                                                <tr>
                                                    <td>#{{ $po->id }}</td>
                                                    <td>{{ $po->contributor->name ?? __('غير معروف') }}</td>
                                                    <td class="ss-text-center">{{ number_format($po->count, 0) }}</td>
                                                    <td class="ss-text-right">{{ number_format($po->amount_per_share, 2) }} {{ __('ريال') }}</td>
                                                    <td class="ss-text-right">{{ number_format($po->count * $po->amount_per_share, 2) }} {{ __('ريال') }}</td>
                                                    <td class="ss-text-center">
                                                        <span class="ss-pill {{ $po->accept ? 'ss-pill-active' : 'ss-pill-pending' }}">
                                                            <i class="bi {{ $po->accept ? 'bi-check-circle-fill' : 'bi-hourglass-split' }}"></i>
                                                            {{ $po->accept ? __('مقبول') : __('في الانتظار') }}
                                                        </span>
                                                    </td>
                                                    <td class="ss-text-center">
                                                        <a href="{{ route('shares-pos.show', $po) }}" class="ss-icon-btn ss-icon-btn-info" title="{{ __('عرض') }}">
                                                            <i class="bi bi-eye-fill"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2">{{ __('المجموع') }}</th>
                                                <th class="ss-text-center">{{ number_format($orderedShares, 0) }}</th>
                                                <th></th>
                                                <th class="ss-text-right">{{ number_format($orders->sum(fn ($po) => (float) $po->count * (float) $po->amount_per_share), 2) }} {{ __('ريال') }}</th>
                                                <th colspan="2"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="ss-empty show">
                                <div class="ss-empty-icon"><i class="bi bi-cart-x-fill"></i></div>
                                <h3>{{ __('لا توجد طلبات شراء بعد') }}</h3>
                                <p>{{ __('لم يتم تقديم أي طلب شراء لهذا العرض حتى الآن.') }}</p>
                            </div>
                        @endif
                    </section>
                </div>

                <div class="ss-shell">
                    <section class="ss-card">
                        <div class="ss-card-header">
                            <div class="ss-card-title-wrap">
                                <span class="ss-card-icon"><i class="bi bi-clock-history"></i></span>
                                <div>
                                    <h2 class="ss-card-title">{{ __('معلومات إضافية') }}</h2>
                                    <p class="ss-card-subtitle">{{ __('تواريخ مهمة مرتبطة بالعرض وحالته الزمنية.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="ss-info-list">
                            <div class="ss-info-item">
                                <span class="ss-info-label">{{ __('تاريخ الإنشاء') }}</span>
                                <div class="ss-info-value">{{ $sellShare->created_at->format('Y-m-d H:i') }}</div>
                            </div>
                            <div class="ss-info-item">
                                <span class="ss-info-label">{{ __('آخر تحديث') }}</span>
                                <div class="ss-info-value">{{ $sellShare->updated_at->format('Y-m-d H:i') }}</div>
                            </div>
                            <div class="ss-info-item">
                                <span class="ss-info-label">{{ __('تاريخ الإدراج') }}</span>
                                <div class="ss-info-value">{{ $sellShare->insert_date->format('Y-m-d H:i') }}</div>
                            </div>
                            <div class="ss-info-item">
                                <span class="ss-info-label">{{ __('تاريخ الانتهاء') }}</span>
                                <div class="ss-info-value {{ $sellShare->end_date ? '' : 'muted' }}">
                                    {{ $sellShare->end_date ? $sellShare->end_date->format('Y-m-d') : __('بدون انتهاء') }}
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="ss-card">
                        <div class="ss-card-header">
                            <div class="ss-card-title-wrap">
                                <span class="ss-card-icon"><i class="bi bi-lightning-charge-fill"></i></span>
                                <div>
                                    <h2 class="ss-card-title">{{ __('إجراءات سريعة') }}</h2>
                                    <p class="ss-card-subtitle">{{ __('الوصول السريع إلى أهم الإجراءات الخاصة بهذا العرض.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="ss-inline-actions" style="width: 100%;">
                            <a href="{{ route('sell-shares.edit', $sellShare) }}" class="ss-btn ss-btn-warning">
                                <i class="bi bi-pencil-square"></i>
                                {{ __('تعديل العرض') }}
                            </a>
                            <a href="{{ route('sell-shares.print', $sellShare) }}" class="ss-btn ss-btn-info">
                                <i class="bi bi-printer-fill"></i>
                                {{ __('طباعة العرض') }}
                            </a>
                            <form action="{{ route('sell-shares.destroy', $sellShare) }}" method="POST" style="width: 100%;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ss-btn ss-btn-danger" style="width: 100%;" data-confirm="{{ __('هل أنت متأكد من حذف هذا العرض؟ هذا الإجراء لا يمكن التراجع عنه.') }}">
                                    <i class="bi bi-trash3-fill"></i>
                                    {{ __('حذف العرض') }}
                                </button>
                            </form>
                        </div>

                        @if($sellShare->ad_status == \App\Models\SellShares::AD_STATUS_ACTIVE)
                            <div class="ss-note-box success" style="margin-top: 1rem;">
                                <i class="bi bi-check-circle-fill"></i>
                                <div>{{ __('العرض نشط حاليًا ويمكن للمشترين الاطلاع عليه وتقديم طلبات الشراء.') }}</div>
                            </div>
                        @elseif($sellShare->ad_status == \App\Models\SellShares::AD_STATUS_COMPLETED)
                            <div class="ss-note-box" style="margin-top: 1rem;">
                                <i class="bi bi-patch-check-fill"></i>
                                <div>{{ __('العرض مكتمل وتم الوصول إلى مرحلة نهائية ضمن دورة العمل الحالية.') }}</div>
                            </div>
                        @elseif($sellShare->ad_status == \App\Models\SellShares::AD_STATUS_CANCELLED)
                            <div class="ss-note-box danger" style="margin-top: 1rem;">
                                <i class="bi bi-x-octagon-fill"></i>
                                <div>{{ __('هذا العرض ملغي حاليًا، لذلك يفضل التعامل معه كمعلومة أرشيفية فقط.') }}</div>
                            </div>
                        @else
                            <div class="ss-note-box warning" style="margin-top: 1rem;">
                                <i class="bi bi-hourglass-split"></i>
                                <div>{{ __('العرض ما زال في حالة مبدئية ويمكن مراجعته قبل تفعيله أو إكماله.') }}</div>
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
            document.querySelectorAll('[data-confirm]').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    const message = this.getAttribute('data-confirm');

                    if (message && !window.confirm(message)) {
                        event.preventDefault();
                    }
                });
            });
        });
    </script>
@endpush
