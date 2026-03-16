@php
    $config = match ((int) $status) {
        \App\Models\SellShares::AD_STATUS_INITIAL => ['label' => __('مبدئي'), 'class' => 'initial', 'icon' => 'bi bi-hourglass-split'],
        \App\Models\SellShares::AD_STATUS_ACTIVE => ['label' => __('نشط'), 'class' => 'active', 'icon' => 'bi bi-check-circle-fill'],
        \App\Models\SellShares::AD_STATUS_COMPLETED => ['label' => __('مكتمل'), 'class' => 'completed', 'icon' => 'bi bi-patch-check-fill'],
        \App\Models\SellShares::AD_STATUS_CANCELLED => ['label' => __('ملغي'), 'class' => 'cancelled', 'icon' => 'bi bi-x-octagon-fill'],
        default => ['label' => __('غير محدد'), 'class' => 'initial', 'icon' => 'bi bi-question-circle-fill'],
    };
@endphp

<span class="ss-pill ss-pill-{{ $config['class'] }}">
    <i class="{{ $config['icon'] }}"></i>
    {{ $config['label'] }}
</span>
