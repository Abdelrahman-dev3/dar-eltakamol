@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('[data-contributor-form]');
        if (!form) {
            return;
        }

        const tempPasswordInput = document.getElementById('temp_password');
        const documentsInput = document.getElementById('documents');
        const fileList = document.getElementById('file-list');
        const profileInput = document.getElementById('profile_picture');
        const profilePreview = document.querySelector('[data-profile-preview]');
        const profileFallback = document.querySelector('[data-profile-fallback]');
        const companySelect = document.querySelector('[data-company-select]');
        const departmentsSelect = document.querySelector('[data-departments-select]');

        if (tempPasswordInput) {
            tempPasswordInput.addEventListener('focus', function () {
                if (this.value.trim()) {
                    return;
                }

                this.value = Math.random().toString(36).slice(2, 8);
            });
        }

        if (documentsInput && fileList) {
            documentsInput.addEventListener('change', function () {
                fileList.innerHTML = '';

                Array.from(this.files || []).forEach(function (file, index) {
                    const fileSize = (file.size / 1024 / 1024).toFixed(2);
                    const item = document.createElement('div');
                    item.className = 'contributor-file-item';
                    item.innerHTML =
                        '<div style="display:flex;align-items:center;gap:12px;">' +
                            '<span class="contributor-file-icon"><i class="bi bi-file-earmark-text"></i></span>' +
                            '<div>' +
                                '<strong>' + file.name + '</strong>' +
                                '<small>الملف ' + (index + 1) + '</small>' +
                            '</div>' +
                        '</div>' +
                        '<small>' + fileSize + ' MB</small>';
                    fileList.appendChild(item);
                });
            });
        }

        if (profileInput && profilePreview) {
            profileInput.addEventListener('change', function () {
                const file = this.files && this.files[0];
                if (!file) {
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (event) {
                    profilePreview.src = event.target.result;
                    profilePreview.style.display = 'block';

                    if (profileFallback) {
                        profileFallback.style.display = 'none';
                    }
                };

                reader.readAsDataURL(file);
            });
        }

        if (companySelect && departmentsSelect) {
            const departmentOptions = Array.from(departmentsSelect.options);

            function syncDepartments() {
                const companyId = companySelect.value;
                const selectedValues = new Set(
                    Array.from(departmentsSelect.selectedOptions).map(function (option) {
                        return option.value;
                    })
                );

                departmentOptions.forEach(function (option) {
                    const shouldShow = !companyId || option.dataset.companyId === companyId;
                    option.hidden = !shouldShow;

                    if (!shouldShow && selectedValues.has(option.value)) {
                        option.selected = false;
                    }
                });
            }

            companySelect.addEventListener('change', syncDepartments);
            syncDepartments();
        }

        form.addEventListener('submit', function (event) {
            const name = (document.getElementById('name')?.value || '').trim();
            const idNumber = (document.getElementById('id_number')?.value || '').trim();

            if (!name) {
                alert(form.dataset.emptyName || 'يرجى إدخال الاسم');
                event.preventDefault();
                return;
            }

            if (!idNumber) {
                alert(form.dataset.emptyId || 'يرجى إدخال رقم الهوية');
                event.preventDefault();
                return;
            }

            const confirmMessage =
                (form.dataset.confirmMessage || 'هل أنت متأكد؟') +
                '\n\n' +
                (form.dataset.nameLabel || 'الاسم') + ': ' + name +
                '\n' +
                (form.dataset.idLabel || 'رقم الهوية') + ': ' + idNumber;

            if (!window.confirm(confirmMessage)) {
                event.preventDefault();
            }
        });
    });
</script>
@endpush
