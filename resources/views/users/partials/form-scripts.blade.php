@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('[data-user-form]');
        const searchInput = document.querySelector('[data-user-permission-search]');
        const cards = Array.from(document.querySelectorAll('[data-permission-card]'));

        if (searchInput && cards.length > 0) {
            function normalize(value) {
                return (value || '').toString().toLowerCase().trim();
            }

            function filterPermissions() {
                const query = normalize(searchInput.value);

                cards.forEach(function (card) {
                    const haystack = normalize(card.getAttribute('data-search'));
                    const visible = query === '' || haystack.indexOf(query) !== -1;
                    card.classList.toggle('is-hidden', !visible);
                });
            }

            searchInput.addEventListener('input', filterPermissions);
            filterPermissions();
        }

        if (!form) {
            return;
        }

        form.addEventListener('submit', function (event) {
            const name = (document.getElementById('name')?.value || '').trim();
            const email = (document.getElementById('email')?.value || '').trim();

            if (!name || !email) {
                return;
            }

            const summary = '\n' + (form.dataset.nameLabel || 'المستخدم') + ': ' + name + '\n' +
                (form.dataset.emailLabel || 'البريد') + ': ' + email;

            if (!window.confirm((form.dataset.confirmMessage || 'هل أنت متأكد؟') + summary)) {
                event.preventDefault();
            }
        });
    });
</script>
@endpush
