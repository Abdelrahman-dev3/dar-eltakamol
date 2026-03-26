@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/ar.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('[data-meeting-form]');
        if (!form) {
            return;
        }

        const urlInput = document.getElementById('url');
        const urlPreview = document.querySelector('[data-meeting-link-preview] span');
        const usersSelect = document.querySelector('[data-users-select]');
        const selectedUsersPreview = document.getElementById('selectedUsersPreview');
        const selectedUsersEmpty = document.getElementById('selectedUsersEmpty');
        const selectedUsersCount = document.getElementById('selectedUsersCount');
        const attachmentsContainer = document.getElementById('attachmentsContainer');
        const attachmentRowsCount = document.getElementById('attachmentRowsCount');
        const addAttachmentRowButton = document.getElementById('addAttachmentRow');
        const dateInput = document.querySelector('[data-date-picker]');
        const dateTrigger = document.querySelector('[data-date-trigger]');

        if (dateInput && typeof flatpickr === 'function') {
            const appTheme = document.documentElement.getAttribute('data-theme') || 'light';
            const meetingPickerLocale = '{{ app()->getLocale() === 'ar' ? 'ar' : '' }}';
            const meetingPicker = flatpickr(dateInput, {
                enableTime: true,
                time_24hr: true,
                dateFormat: 'Y-m-d H:i',
                altInput: true,
                altInputClass: 'meeting-input meeting-flatpickr-alt-input',
                altFormat: 'l، j F Y - H:i',
                locale: meetingPickerLocale || undefined,
                minuteIncrement: 5,
                disableMobile: true,
                static: false,
                monthSelectorType: 'static',
                prevArrow: '<i class="bi bi-chevron-right"></i>',
                nextArrow: '<i class="bi bi-chevron-left"></i>',
                onReady: function (selectedDates, dateStr, instance) {
                    instance.calendarContainer.classList.add('meeting-calendar');
                    instance.altInput.setAttribute('dir', '{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}');
                    instance.altInput.setAttribute('data-theme-context', appTheme);
                }
            });

            if (dateTrigger) {
                dateTrigger.addEventListener('click', function () {
                    meetingPicker.open();
                });
            }
        }

        function updateLinkPreview() {
            if (!urlPreview || !urlInput) {
                return;
            }

            const value = urlInput.value.trim();
            urlPreview.textContent = value || 'سيظهر الرابط هنا بعد إدخاله.';
        }

        function updateSelectedUsersPreview() {
            if (!usersSelect || !selectedUsersPreview || !selectedUsersEmpty || !selectedUsersCount) {
                return;
            }

            const options = Array.from(usersSelect.selectedOptions || []);
            selectedUsersPreview.innerHTML = '';
            selectedUsersCount.textContent = options.length;

            if (options.length === 0) {
                selectedUsersEmpty.style.display = '';
                return;
            }

            selectedUsersEmpty.style.display = 'none';

            options.forEach(function (option) {
                const name = option.dataset.name || option.textContent.trim();
                const email = option.dataset.email || '';
                const initials = name.trim().slice(0, 1) || 'م';
                const item = document.createElement('div');
                item.className = 'meeting-user-card';
                item.innerHTML =
                    '<span class="meeting-user-avatar">' + initials + '</span>' +
                    '<div><strong>' + name + '</strong><small>' + email + '</small></div>';
                selectedUsersPreview.appendChild(item);
            });
        }

        function reindexAttachmentRows() {
            const rows = Array.from(attachmentsContainer.querySelectorAll('[data-attachment-row]'));

            rows.forEach(function (row, index) {
                const title = row.querySelector('.meeting-attachment-label span');
                const removeButton = row.querySelector('[data-remove-attachment]');

                if (title) {
                    title.textContent = 'مرفق جديد #' + (index + 1);
                }

                if (removeButton) {
                    removeButton.style.display = rows.length > 1 ? '' : 'none';
                }
            });

            if (attachmentRowsCount) {
                attachmentRowsCount.textContent = rows.length;
            }
        }

        function bindAttachmentRow(row) {
            if (!row) {
                return;
            }

            const removeButton = row.querySelector('[data-remove-attachment]');
            const fileInput = row.querySelector('[data-attachment-file]');
            const fileNamePreview = row.querySelector('[data-attachment-file-name]');

            if (removeButton) {
                removeButton.addEventListener('click', function () {
                    const rows = attachmentsContainer.querySelectorAll('[data-attachment-row]');

                    if (rows.length <= 1) {
                        const descriptionInput = row.querySelector('input[name="attachment_descriptions[]"]');
                        if (fileInput) {
                            fileInput.value = '';
                        }
                        if (descriptionInput) {
                            descriptionInput.value = '';
                        }
                        if (fileNamePreview) {
                            fileNamePreview.textContent = 'لم يتم اختيار ملف بعد.';
                        }
                        return;
                    }

                    row.remove();
                    reindexAttachmentRows();
                });
            }

            if (fileInput && fileNamePreview) {
                fileInput.addEventListener('change', function () {
                    const file = this.files && this.files[0];
                    fileNamePreview.textContent = file ? 'الملف المختار: ' + file.name : 'لم يتم اختيار ملف بعد.';
                });
            }
        }

        if (attachmentsContainer) {
            Array.from(attachmentsContainer.querySelectorAll('[data-attachment-row]')).forEach(bindAttachmentRow);
            reindexAttachmentRows();
        }

        if (addAttachmentRowButton && attachmentsContainer) {
            addAttachmentRowButton.addEventListener('click', function () {
                const row = document.createElement('div');
                row.className = 'meeting-attachment-row';
                row.setAttribute('data-attachment-row', '');
                row.innerHTML =
                    '<div class="meeting-attachment-head">' +
                        '<div class="meeting-attachment-label"><i class="bi bi-file-earmark-plus"></i><span>مرفق جديد</span></div>' +
                        '<button type="button" class="meeting-attachment-remove" data-remove-attachment title="حذف هذا الصف"><i class="bi bi-trash3"></i></button>' +
                    '</div>' +
                    '<div class="row">' +
                        '<div class="col-md-6">' +
                            '<div class="form-group meeting-field">' +
                                '<label>الملف</label>' +
                                '<input type="file" name="attachments[]" class="form-control meeting-input" data-attachment-file>' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-md-6">' +
                            '<div class="form-group meeting-field" style="margin-bottom: 0;">' +
                                '<label>وصف المرفق</label>' +
                                '<input type="text" name="attachment_descriptions[]" class="form-control meeting-input" maxlength="255" placeholder="أدخل وصفًا مختصرًا للمرفق إن وجد">' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="meeting-file-preview" data-attachment-file-name>لم يتم اختيار ملف بعد.</div>';

                attachmentsContainer.appendChild(row);
                bindAttachmentRow(row);
                reindexAttachmentRows();
            });
        }

        if (usersSelect) {
            usersSelect.addEventListener('change', updateSelectedUsersPreview);
            updateSelectedUsersPreview();
        }

        if (urlInput) {
            urlInput.addEventListener('input', updateLinkPreview);
            updateLinkPreview();
        }

        form.addEventListener('submit', function (event) {
            const name = (document.getElementById('name')?.value || '').trim();
            const url = (document.getElementById('url')?.value || '').trim();
            const date = (document.getElementById('date')?.value || '').trim();

            if (!name) {
                alert(form.dataset.emptyName || 'يرجى إدخال اسم الاجتماع');
                event.preventDefault();
                return;
            }

            if (!url) {
                alert(form.dataset.emptyUrl || 'يرجى إدخال رابط الاجتماع');
                event.preventDefault();
                return;
            }

            if (!date) {
                alert(form.dataset.emptyDate || 'يرجى إدخال تاريخ الاجتماع');
                event.preventDefault();
                return;
            }

            const confirmMessage =
                (form.dataset.confirmMessage || 'هل أنت متأكد؟') +
                '\n\n' +
                (form.dataset.nameLabel || 'الاجتماع') + ': ' + name +
                '\n' +
                (form.dataset.dateLabel || 'التاريخ') + ': ' + date;

            if (!window.confirm(confirmMessage)) {
                event.preventDefault();
            }
        });
    });
</script>
@endpush
