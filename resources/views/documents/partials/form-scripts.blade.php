@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('[data-doc-form]');
        if (!form) {
            return;
        }

        const multipleFilesInput = document.querySelector('[data-doc-files]');
        const singleFileInput = document.querySelector('[data-doc-single-file]');
        const multiplePreview = document.getElementById('docFilesPreview');
        const singlePreview = document.getElementById('docSingleFilePreview');

        function formatFileSize(bytes) {
            if (!bytes) {
                return '0 KB';
            }

            const units = ['B', 'KB', 'MB', 'GB'];
            let size = bytes;
            let unitIndex = 0;

            while (size >= 1024 && unitIndex < units.length - 1) {
                size /= 1024;
                unitIndex += 1;
            }

            return size.toFixed(size >= 10 || unitIndex === 0 ? 0 : 1) + ' ' + units[unitIndex];
        }

        function fileIcon(name) {
            const extension = (name.split('.').pop() || '').toLowerCase();

            if (['pdf'].includes(extension)) return 'bi-filetype-pdf';
            if (['doc', 'docx'].includes(extension)) return 'bi-file-earmark-word';
            if (['xls', 'xlsx'].includes(extension)) return 'bi-file-earmark-excel';
            if (['ppt', 'pptx'].includes(extension)) return 'bi-file-earmark-ppt';
            if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].includes(extension)) return 'bi-file-earmark-image';
            if (['zip', 'rar', '7z'].includes(extension)) return 'bi-file-earmark-zip';
            if (['txt'].includes(extension)) return 'bi-file-earmark-text';
            return 'bi-file-earmark';
        }

        function renderFiles(files, container) {
            if (!container) {
                return;
            }

            container.innerHTML = '';

            Array.from(files || []).forEach(function (file, index) {
                const item = document.createElement('div');
                item.className = 'doc-file-item';
                item.innerHTML =
                    '<div class="doc-file-meta">' +
                        '<span class="doc-file-icon"><i class="bi ' + fileIcon(file.name) + '"></i></span>' +
                        '<div>' +
                            '<strong>' + file.name + '</strong>' +
                            '<small>الملف ' + (index + 1) + '</small>' +
                        '</div>' +
                    '</div>' +
                    '<small>' + formatFileSize(file.size) + '</small>';
                container.appendChild(item);
            });
        }

        if (multipleFilesInput && multiplePreview) {
            multipleFilesInput.addEventListener('change', function () {
                renderFiles(this.files, multiplePreview);
            });
        }

        if (singleFileInput && singlePreview) {
            singleFileInput.addEventListener('change', function () {
                renderFiles(this.files, singlePreview);
            });
        }

        form.addEventListener('submit', function (event) {
            const name = (document.getElementById('name')?.value || '').trim();

            if (multipleFilesInput && (!multipleFilesInput.files || multipleFilesInput.files.length === 0)) {
                alert(form.dataset.emptyFiles || 'يرجى اختيار ملف واحد على الأقل');
                event.preventDefault();
                return;
            }

            if ((form.dataset.requireName || '') === 'true' && !name) {
                alert(form.dataset.emptyName || 'يرجى إدخال اسم الملف');
                event.preventDefault();
                return;
            }

            const summary = name ? '\n' + (form.dataset.nameLabel || 'الاسم') + ': ' + name : '';
            const confirmMessage = (form.dataset.confirmMessage || 'هل أنت متأكد؟') + summary;

            if (!window.confirm(confirmMessage)) {
                event.preventDefault();
            }
        });
    });
</script>
@endpush
