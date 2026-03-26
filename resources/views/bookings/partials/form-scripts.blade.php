@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ar.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.flatpickr) {
            flatpickr('#date', {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'Y-m-d',
                locale: flatpickr.l10ns.ar,
            });

            flatpickr('#time', {
                enableTime: true,
                noCalendar: true,
                dateFormat: 'H:i',
                time_24hr: false,
                minuteIncrement: 5,
                locale: flatpickr.l10ns.ar,
            });
        }
    });
</script>
@endpush
