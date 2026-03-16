@php
    $isConfirmed = (bool) ($confirmed ?? false);
@endphp

<span class="st-badge {{ $isConfirmed ? 'st-badge-success' : 'st-badge-warning' }}">
    <i class="bi {{ $isConfirmed ? 'bi-patch-check-fill' : 'bi-hourglass-split' }}"></i>
    {{ $isConfirmed ? __('مؤكد') : __('قيد المراجعة') }}
</span>
