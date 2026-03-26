@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.querySelector('[data-permission-department-search]');
        const items = Array.from(document.querySelectorAll('[data-department-option]'));

        if (!searchInput || items.length === 0) {
            return;
        }

        function normalize(value) {
            return (value || '').toString().toLowerCase().trim();
        }

        function filterItems() {
            const query = normalize(searchInput.value);

            items.forEach(function (item) {
                const haystack = normalize(item.getAttribute('data-search'));
                const match = query === '' || haystack.indexOf(query) !== -1;
                item.classList.toggle('is-hidden', !match);
            });
        }

        searchInput.addEventListener('input', filterItems);
    });
</script>
@endpush
