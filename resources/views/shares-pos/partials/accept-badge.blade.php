@php
    $accepted = (bool) ($accept ?? false);
@endphp

<span class="st-badge {{ $accepted ? 'st-badge-success' : 'st-badge-danger' }}">
    <i class="bi {{ $accepted ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
    {{ $accepted ? __('مقبول') : __('غير مقبول') }}
</span>
