@php
    $isPosted = (bool) $posted;
@endphp

<span class="st-badge st-badge-{{ $isPosted ? 'success' : 'warning' }}">
    <i class="bi {{ $isPosted ? 'bi-patch-check-fill' : 'bi-hourglass-split' }}"></i>
    {{ $isPosted ? __('معتمد') : __('غير معتمد') }}
</span>
