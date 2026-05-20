@extends('layouts.app')
@section('title', __('داشبورد المساهم'))
@include('contributor-portal.partials.styles')

@section('content')
<div class="cp-page">
    <section class="cp-hero">
        <div>
            <h1 class="cp-title">{{ __('مرحبًا') }} {{ $contributor->name }}</h1>
            <p class="cp-subtitle">{{ __('ملخص سريع لحسابك كمساهم، وأهم المؤشرات المرتبطة بأسهمك وحركتك.') }}</p>
        </div>
        <div class="cp-actions">
            <a class="cp-btn cp-btn-primary" href="{{ route('contributor.sell-offers.create') }}"><i class="bi bi-plus-circle-fill"></i>{{ __('عرض بيع جديد') }}</a>
            <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.purchase-orders.create') }}"><i class="bi bi-cart-plus-fill"></i>{{ __('طلب شراء جديد') }}</a>
        </div>
    </section>

    <section class="cp-grid-2 cp-feature-row">
        <article class="cp-card">
            <div class="cp-section-head">
                <h2 class="cp-card-title"><i class="bi bi-newspaper"></i>{{ __('آخر الأخبار') }}</h2>
                <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.news') }}">{{ __('عرض الكل') }}</a>
            </div>
            <div class="cp-news-list compact">
                @forelse($latestNews as $item)
                    <article class="cp-news-item">
                        <div class="cp-news-icon"><i class="fa {{ $item->file_icon }}"></i></div>
                        <div class="cp-news-body">
                            <h3 class="cp-news-title">{{ $item->name }}</h3>
                            <div class="cp-news-meta">
                                <span><i class="bi bi-calendar3"></i>{{ $item->created_at?->format('Y-m-d H:i') }}</span>
                                <span><i class="bi bi-paperclip"></i>{{ $item->original_filename }}</span>
                            </div>
                        </div>
                        <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.news.show', $item) }}">{{ __('عرض') }}</a>
                    </article>
                @empty
                    <div class="cp-empty">{{ __('لا توجد أخبار مخصصة لحسابك حاليا') }}</div>
                @endforelse
            </div>
        </article>

        <article class="cp-card cp-meeting-card">
            <div class="cp-section-head">
                <h2 class="cp-card-title"><i class="bi bi-calendar-event-fill"></i>{{ __('الاجتماع القادم') }}</h2>
                <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.meetings') }}">{{ __('كل الاجتماعات') }}</a>
            </div>
            @if($nextMeeting)
                <div class="cp-meeting-summary">
                    <div class="cp-meeting-date">
                        <span>{{ $nextMeeting->date?->format('d') }}</span>
                        <strong>{{ $nextMeeting->date?->format('m/Y') }}</strong>
                    </div>
                    <div class="cp-meeting-body">
                        <h3 class="cp-meeting-title"><a href="{{ route('contributor.meetings.show', $nextMeeting) }}">{{ $nextMeeting->name }}</a></h3>
                        <div class="cp-news-meta">
                            <span><i class="bi bi-clock-fill"></i>{{ $nextMeeting->date?->format('Y-m-d H:i') }}</span>
                            <span><i class="bi bi-paperclip"></i>{{ number_format($nextMeeting->attachments_count ?? 0) }} {{ __('مرفق') }}</span>
                        </div>
                    </div>
                    @if($nextMeeting->url)
                        <a class="cp-btn cp-btn-primary" href="{{ $nextMeeting->url }}" target="_blank" rel="noopener">
                            <i class="bi bi-camera-video-fill"></i>{{ __('رابط الاجتماع') }}
                        </a>
                    @endif
                </div>
            @else
                <div class="cp-empty">{{ __('لا يوجد اجتماع قادم مخصص لحسابك حاليا') }}</div>
            @endif
        </article>
    </section>

    <section class="cp-grid">
        <article class="cp-stat"><i class="bi bi-layers-fill"></i><p class="cp-stat-value">{{ number_format($stats['shares'], 2) }}</p><p class="cp-stat-label">{{ __('عدد الأسهم الخاصة بك') }}</p></article>
        <article class="cp-stat"><i class="bi bi-cash-coin"></i><p class="cp-stat-value">{{ number_format($stats['share_price'], 2) }}</p><p class="cp-stat-label">{{ __('سعر السهم') }}</p></article>
        <article class="cp-stat"><i class="bi bi-pie-chart-fill"></i><p class="cp-stat-value">{{ number_format($stats['ownership_percentage'], 4) }}%</p><p class="cp-stat-label">{{ __('نسبتك من الأسهم الكلية') }}</p></article>
        <article class="cp-stat"><i class="bi bi-calculator-fill"></i><p class="cp-stat-value">{{ number_format($stats['estimated_value'], 2) }}</p><p class="cp-stat-label">{{ __('القيمة التقديرية') }}</p></article>
    </section>

    <section class="cp-grid">
        <article class="cp-stat"><i class="bi bi-megaphone-fill"></i><p class="cp-stat-value">{{ number_format($sellOffersCount) }}</p><p class="cp-stat-label">{{ __('عروض البيع') }}</p></article>
        <article class="cp-stat"><i class="bi bi-cart-check-fill"></i><p class="cp-stat-value">{{ number_format($purchaseOrdersCount) }}</p><p class="cp-stat-label">{{ __('طلبات الشراء') }}</p></article>
        <article class="cp-stat"><i class="bi bi-arrow-left-right"></i><p class="cp-stat-value">{{ number_format($movementsCount) }}</p><p class="cp-stat-label">{{ __('الحركات') }}</p></article>
        <article class="cp-stat"><i class="bi bi-newspaper"></i><p class="cp-stat-value">{{ number_format($newsCount) }}</p><p class="cp-stat-label">{{ __('الأخبار') }}</p></article>
        <article class="cp-stat"><i class="bi bi-folder2-open"></i><p class="cp-stat-value">{{ number_format($filesCount) }}</p><p class="cp-stat-label">{{ __('الملفات') }}</p></article>
        <article class="cp-stat"><i class="bi bi-journal-richtext"></i><p class="cp-stat-value">{{ number_format($regulationsCount) }}</p><p class="cp-stat-label">{{ __('اللوائح') }}</p></article>
        <article class="cp-stat"><i class="bi bi-headset"></i><p class="cp-stat-value">{{ number_format($serviceRequestsCount) }}</p><p class="cp-stat-label">{{ __('طلبات الخدمات') }}</p></article>
        <article class="cp-stat"><i class="bi bi-journal-text"></i><p class="cp-stat-value">{{ number_format($stats['total_shares'], 2) }}</p><p class="cp-stat-label">{{ __('إجمالي أسهم المساهمين') }}</p></article>
    </section>

    <section class="cp-grid-2 cp-charts-grid">
        <article class="cp-card cp-chart-card">
            <div class="cp-section-head">
                <h2 class="cp-card-title"><i class="bi bi-pie-chart-fill"></i>{{ __('نسبة أسهم المساهم') }}</h2>
                <span class="cp-badge">{{ number_format($stats['ownership_percentage'], 4) }}%</span>
            </div>
            <div class="cp-chart-wrap">
                <canvas id="ownershipChart"></canvas>
            </div>
        </article>

        <article class="cp-card cp-chart-card">
            <div class="cp-section-head">
                <h2 class="cp-card-title"><i class="bi bi-bar-chart-fill"></i>{{ __('عروض البيع والشراء') }}</h2>
                <span class="cp-badge">{{ __('خاص بالمساهم') }}</span>
            </div>
            <div class="cp-chart-wrap">
                <canvas id="tradingChart"></canvas>
            </div>
        </article>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Chart === 'undefined') {
            return;
        }

        const charts = @json($dashboardCharts);
        const computedStyle = getComputedStyle(document.documentElement);
        const textColor = computedStyle.getPropertyValue('--text-primary').trim() || '#1e293b';
        const secondaryColor = computedStyle.getPropertyValue('--text-secondary').trim() || '#64748b';
        const borderColor = computedStyle.getPropertyValue('--border-color').trim() || 'rgba(226, 232, 240, 0.8)';

        Chart.defaults.font.family = 'Zain, sans-serif';
        Chart.defaults.color = textColor;

        const ownershipCanvas = document.getElementById('ownershipChart');
        if (ownershipCanvas) {
            new Chart(ownershipCanvas, {
                type: 'doughnut',
                data: {
                    labels: charts.ownership.labels,
                    datasets: [{
                        data: charts.ownership.data,
                        backgroundColor: ['#aa863f', '#0ea5e9'],
                        borderColor: '#ffffff',
                        borderWidth: 3,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                                color: textColor
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return context.label + ': ' + Number(context.raw || 0).toLocaleString('ar-SA') + ' سهم';
                                }
                            }
                        }
                    }
                }
            });
        }

        const tradingCanvas = document.getElementById('tradingChart');
        if (tradingCanvas) {
            new Chart(tradingCanvas, {
                type: 'bar',
                data: {
                    labels: charts.trading.labels,
                    datasets: [
                        {
                            label: '{{ __('عدد العمليات') }}',
                            data: charts.trading.counts,
                            backgroundColor: '#aa863f',
                            borderRadius: 10,
                            maxBarThickness: 44
                        },
                        {
                            label: '{{ __('عدد الأسهم') }}',
                            data: charts.trading.shares,
                            backgroundColor: '#0ea5e9',
                            borderRadius: 10,
                            maxBarThickness: 44
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                                color: textColor
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { color: secondaryColor, precision: 0 },
                            grid: { color: borderColor }
                        },
                        x: {
                            ticks: { color: secondaryColor },
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
