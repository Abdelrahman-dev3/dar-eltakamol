<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('طباعة عرض البيع') }} #{{ $sellShare->id }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zain:wght@200;300;400;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #aa863f;
            --primary-soft: rgba(170, 134, 63, 0.12);
            --success: #059669;
            --danger: #dc2626;
            --info: #0284c7;
            --text: #1e293b;
            --muted: #64748b;
            --border: #dbe4ee;
            --surface: #ffffff;
            --bg: #f8fafc;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Zain', sans-serif;
            background: var(--bg);
            color: var(--text);
            direction: {{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }};
        }

        .print-shell {
            max-width: 1080px;
            margin: 0 auto;
            padding: 28px;
        }

        .print-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .print-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 46px;
            padding: 10px 18px;
            border-radius: 14px;
            border: 1px solid rgba(170, 134, 63, 0.16);
            background: #fff;
            color: var(--text);
            text-decoration: none;
            font-size: 18px;
            font-weight: 800;
            cursor: pointer;
        }

        .print-btn.primary {
            background: linear-gradient(135deg, var(--primary), #c49b48);
            border-color: transparent;
            color: #fff;
        }

        .print-document {
            background: var(--surface);
            border: 1px solid rgba(170, 134, 63, 0.14);
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
        }

        .print-header {
            padding: 30px;
            background:
                radial-gradient(circle at top left, rgba(170, 134, 63, 0.18), transparent 34%),
                linear-gradient(135deg, #fffaf0 0%, #ffffff 46%, #f5efe2 100%);
            border-bottom: 1px solid rgba(170, 134, 63, 0.14);
        }

        .print-header-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .print-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: var(--primary-soft);
            color: var(--primary);
            font-size: 18px;
            font-weight: 800;
        }

        .print-title {
            margin: 14px 0 0;
            font-size: 42px;
            line-height: 1.1;
            font-weight: 900;
        }

        .print-subtitle {
            margin: 10px 0 0;
            color: var(--muted);
            font-size: 20px;
            line-height: 1.8;
        }

        .print-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 999px;
            font-size: 18px;
            font-weight: 900;
            background: var(--primary-soft);
            color: var(--primary);
        }

        .status-initial { background: rgba(148, 163, 184, 0.16); color: var(--muted); }
        .status-active { background: rgba(5, 150, 105, 0.12); color: var(--success); }
        .status-completed { background: rgba(14, 165, 233, 0.12); color: var(--info); }
        .status-cancelled { background: rgba(220, 38, 38, 0.1); color: var(--danger); }

        .print-body {
            padding: 26px 30px 32px;
            display: flex;
            flex-direction: column;
            gap: 22px;
        }

        .print-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .print-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .print-card,
        .print-stat,
        .print-note {
            border: 1px solid var(--border);
            border-radius: 22px;
            background: #fff;
        }

        .print-card {
            padding: 18px;
        }

        .print-card h2 {
            margin: 0 0 14px;
            font-size: 24px;
            font-weight: 900;
        }

        .print-info-list {
            display: grid;
            gap: 12px;
        }

        .print-info-item {
            padding: 12px 14px;
            border-radius: 16px;
            background: #f8fafc;
            border: 1px solid var(--border);
        }

        .print-label {
            display: block;
            margin-bottom: 4px;
            color: var(--muted);
            font-size: 16px;
            font-weight: 700;
        }

        .print-value {
            font-size: 19px;
            font-weight: 800;
            line-height: 1.6;
        }

        .print-stat {
            padding: 16px;
        }

        .print-stat-label {
            color: var(--muted);
            font-size: 16px;
            font-weight: 700;
        }

        .print-stat-value {
            margin-top: 8px;
            font-size: 30px;
            font-weight: 900;
        }

        .print-note {
            padding: 18px;
            background: rgba(170, 134, 63, 0.06);
        }

        .print-note-title {
            margin: 0 0 8px;
            font-size: 22px;
            font-weight: 900;
        }

        .print-note p {
            margin: 0;
            color: var(--muted);
            font-size: 19px;
            line-height: 1.85;
        }

        .print-table-wrap {
            border: 1px solid var(--border);
            border-radius: 22px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 14px 12px;
            border-bottom: 1px solid var(--border);
            text-align: right;
            font-size: 17px;
        }

        th {
            background: #fff9ef;
            color: var(--muted);
            font-weight: 900;
        }

        tbody tr:last-child td {
            border-bottom: 0;
        }

        tfoot td {
            background: rgba(170, 134, 63, 0.06);
            font-weight: 900;
        }

        .print-footer {
            padding: 18px 30px 24px;
            border-top: 1px solid rgba(170, 134, 63, 0.12);
            color: var(--muted);
            font-size: 17px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        @media (max-width: 900px) {
            .print-grid,
            .print-stats {
                grid-template-columns: 1fr;
            }
        }

        @media print {
            body {
                background: #fff;
            }

            .print-shell {
                max-width: none;
                padding: 0;
            }

            .print-toolbar {
                display: none !important;
            }

            .print-document {
                border: 0;
                border-radius: 0;
                box-shadow: none;
            }

            .print-header,
            .print-body,
            .print-footer {
                padding-inline: 18px;
            }

            @page {
                size: A4;
                margin: 12mm;
            }
        }
    </style>
</head>
<body>
@php
    $sellerName = $sellShare->seller->name ?? $sellShare->seller->user->name ?? __('غير معروف');
    $orders = $sellShare->sharesPOs;
    $statusConfig = match ((int) $sellShare->ad_status) {
        \App\Models\SellShares::AD_STATUS_INITIAL => ['label' => __('مبدئي'), 'class' => 'initial'],
        \App\Models\SellShares::AD_STATUS_ACTIVE => ['label' => __('نشط'), 'class' => 'active'],
        \App\Models\SellShares::AD_STATUS_COMPLETED => ['label' => __('مكتمل'), 'class' => 'completed'],
        \App\Models\SellShares::AD_STATUS_CANCELLED => ['label' => __('ملغي'), 'class' => 'cancelled'],
        default => ['label' => __('غير محدد'), 'class' => 'initial'],
    };
@endphp

<div class="print-shell">
    <div class="print-toolbar">
        <a href="{{ route('sell-shares.show', $sellShare) }}" class="print-btn">{{ __('العودة للعرض') }}</a>
        <button type="button" class="print-btn primary" onclick="window.print()">{{ __('طباعة الآن') }}</button>
    </div>

    <div class="print-document">
        <div class="print-header">
            <div class="print-header-top">
                <div>
                    <span class="print-kicker">{{ __('شركة دار التكامل') }}</span>
                    <h1 class="print-title">{{ __('عرض بيع أسهم') }} #{{ $sellShare->id }}</h1>
                    <p class="print-subtitle">{{ __('نسخة طباعة من بيانات العرض الأساسية وطلبات الشراء المرتبطة به.') }}</p>
                </div>

                <span class="print-status status-{{ $statusConfig['class'] }}">{{ $statusConfig['label'] }}</span>
            </div>
        </div>

        <div class="print-body">
            <div class="print-stats">
                <div class="print-stat">
                    <div class="print-stat-label">{{ __('عدد الأسهم') }}</div>
                    <div class="print-stat-value">{{ number_format($sellShare->count, 0) }}</div>
                </div>
                <div class="print-stat">
                    <div class="print-stat-label">{{ __('السعر لكل سهم') }}</div>
                    <div class="print-stat-value">{{ number_format($sellShare->amount_per_share, 2) }}</div>
                </div>
                <div class="print-stat">
                    <div class="print-stat-label">{{ __('القيمة الإجمالية') }}</div>
                    <div class="print-stat-value">{{ number_format($sellShare->total_amount, 2) }}</div>
                </div>
                <div class="print-stat">
                    <div class="print-stat-label">{{ __('طلبات الشراء') }}</div>
                    <div class="print-stat-value">{{ number_format($orders->count()) }}</div>
                </div>
            </div>

            <div class="print-grid">
                <section class="print-card">
                    <h2>{{ __('المعلومات الأساسية') }}</h2>
                    <div class="print-info-list">
                        <div class="print-info-item">
                            <span class="print-label">{{ __('المساهم') }}</span>
                            <div class="print-value">{{ $sellerName }}</div>
                        </div>
                        <div class="print-info-item">
                            <span class="print-label">{{ __('تاريخ الإدراج') }}</span>
                            <div class="print-value">{{ $sellShare->insert_date->format('Y-m-d H:i') }}</div>
                        </div>
                        <div class="print-info-item">
                            <span class="print-label">{{ __('تاريخ الانتهاء') }}</span>
                            <div class="print-value">{{ $sellShare->end_date ? $sellShare->end_date->format('Y-m-d') : __('بدون تاريخ انتهاء') }}</div>
                        </div>
                    </div>
                </section>

                <section class="print-card">
                    <h2>{{ __('المعلومات الإدارية') }}</h2>
                    <div class="print-info-list">
                        <div class="print-info-item">
                            <span class="print-label">{{ __('تاريخ الإنشاء') }}</span>
                            <div class="print-value">{{ $sellShare->created_at->format('Y-m-d H:i') }}</div>
                        </div>
                        <div class="print-info-item">
                            <span class="print-label">{{ __('آخر تحديث') }}</span>
                            <div class="print-value">{{ $sellShare->updated_at->format('Y-m-d H:i') }}</div>
                        </div>
                        <div class="print-info-item">
                            <span class="print-label">{{ __('حالة العرض') }}</span>
                            <div class="print-value">{{ $statusConfig['label'] }}</div>
                        </div>
                    </div>
                </section>
            </div>

            @if($sellShare->notes)
                <section class="print-note">
                    <h2 class="print-note-title">{{ __('ملاحظات العرض') }}</h2>
                    <p>{{ $sellShare->notes }}</p>
                </section>
            @endif

            <section class="print-card">
                <h2>{{ __('طلبات الشراء المرتبطة') }}</h2>

                @if($orders->count() > 0)
                    <div class="print-table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>{{ __('رقم الطلب') }}</th>
                                    <th>{{ __('المشتري') }}</th>
                                    <th>{{ __('عدد الأسهم') }}</th>
                                    <th>{{ __('السعر') }}</th>
                                    <th>{{ __('الإجمالي') }}</th>
                                    <th>{{ __('الحالة') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $po)
                                    <tr>
                                        <td>#{{ $po->id }}</td>
                                        <td>{{ $po->contributor->name ?? __('غير معروف') }}</td>
                                        <td>{{ number_format($po->count, 0) }}</td>
                                        <td>{{ number_format($po->amount_per_share, 2) }} {{ __('ريال') }}</td>
                                        <td>{{ number_format((float) $po->count * (float) $po->amount_per_share, 2) }} {{ __('ريال') }}</td>
                                        <td>{{ $po->accept ? __('مقبول') : __('في الانتظار') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">{{ __('المجموع') }}</td>
                                    <td>{{ number_format($orders->sum(fn ($po) => (float) $po->count), 0) }}</td>
                                    <td></td>
                                    <td>{{ number_format($orders->sum(fn ($po) => (float) $po->count * (float) $po->amount_per_share), 2) }} {{ __('ريال') }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="print-note">
                        <h2 class="print-note-title">{{ __('لا توجد طلبات شراء') }}</h2>
                        <p>{{ __('لم يتم تقديم أي طلب شراء لهذا العرض حتى وقت طباعة هذه النسخة.') }}</p>
                    </div>
                @endif
            </section>
        </div>

        <div class="print-footer">
            <div>{{ __('تم إنشاء هذه النسخة بتاريخ') }} {{ now()->format('Y-m-d H:i') }}</div>
            <div>{{ __('عرض البيع') }} #{{ $sellShare->id }}</div>
        </div>
    </div>
</div>
</body>
</html>
