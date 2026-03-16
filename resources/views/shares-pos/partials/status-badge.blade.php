@php
    $value = (int) ($status ?? 0);
    $config = match ($value) {
        \App\Models\SharesPO::PO_STATUS_PENDING => ['class' => 'st-badge-warning', 'icon' => 'bi-hourglass-split', 'label' => __('في الانتظار')],
        \App\Models\SharesPO::PO_STATUS_REVIEW => ['class' => 'st-badge-neutral', 'icon' => 'bi-search', 'label' => __('قيد المراجعة')],
        \App\Models\SharesPO::PO_STATUS_COMPLETED => ['class' => 'st-badge-success', 'icon' => 'bi-patch-check-fill', 'label' => __('مكتمل')],
        default => ['class' => 'st-badge-neutral', 'icon' => 'bi-question-circle-fill', 'label' => __('غير محدد')],
    };
@endphp

<span class="st-badge {{ $config['class'] }}">
    <i class="bi {{ $config['icon'] }}"></i>
    {{ $config['label'] }}
</span>
