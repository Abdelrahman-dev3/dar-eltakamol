@php
    $config = match ((int) $type) {
        1 => ['label' => __('شراء'), 'class' => 'buy', 'icon' => 'bi bi-bag-check-fill'],
        2 => ['label' => __('بيع'), 'class' => 'sell', 'icon' => 'bi bi-box-arrow-up-left'],
        3 => ['label' => __('تحويل'), 'class' => 'transfer', 'icon' => 'bi bi-arrow-left-right'],
        4 => ['label' => __('أرباح'), 'class' => 'dividend', 'icon' => 'bi bi-coin'],
        default => ['label' => __('غير محدد'), 'class' => 'neutral', 'icon' => 'bi bi-question-circle-fill'],
    };

    $showIcon = $showIcon ?? true;
@endphp

<span class="st-badge st-badge-{{ $config['class'] }}">
    @if($showIcon)
        <i class="{{ $config['icon'] }}"></i>
    @endif
    {{ $config['label'] }}
</span>
