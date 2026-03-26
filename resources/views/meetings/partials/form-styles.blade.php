@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
<style>
    .meeting-form-page {
        padding: 10px 0 30px;
        color: var(--text-primary);
        font-size: 1rem;
    }
    .meeting-form-shell { display: flex; flex-direction: column; gap: 24px; }
    .meeting-form-hero {
        position: relative;
        overflow: hidden;
        border-radius: 30px;
        padding: clamp(24px, 3vw, 34px);
        background:
            radial-gradient(circle at top right, rgba(196, 168, 90, 0.28), transparent 30%),
            linear-gradient(135deg, #fff8ec 0%, #ffffff 46%, #f5ecdc 100%);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
        animation: meetingFormFadeUp 0.72s ease both;
    }
    .meeting-form-hero::before,
    .meeting-form-hero::after {
        content: "";
        position: absolute;
        border-radius: 999px;
        pointer-events: none;
    }
    .meeting-form-hero::before {
        width: 220px;
        height: 220px;
        top: -120px;
        inset-inline-end: -70px;
        background: rgba(170, 134, 63, 0.10);
    }
    .meeting-form-hero::after {
        width: 180px;
        height: 180px;
        bottom: -100px;
        inset-inline-start: -50px;
        background: rgba(196, 168, 90, 0.13);
    }
    .meeting-form-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 22px;
        flex-wrap: wrap;
    }
    .meeting-form-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 14px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(170, 134, 63, 0.12);
        color: var(--primary-color);
        font-size: clamp(0.95rem, 1vw, 1.02rem);
        font-weight: 800;
    }
    .meeting-form-title {
        margin: 0;
        color: var(--text-primary);
        font-size: clamp(2rem, 4vw, 3.15rem);
        font-weight: 900;
        line-height: 1.1;
    }
    .meeting-form-subtitle {
        margin: 12px 0 0;
        max-width: 760px;
        color: var(--text-secondary);
        font-size: clamp(1rem, 1.5vw, 1.24rem);
        line-height: 1.9;
    }
    .meeting-form-actions { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
    .meeting-form-page button,
    .meeting-form-page input,
    .meeting-form-page select,
    .meeting-form-page textarea {
        font: inherit;
    }
    .meeting-form-btn,
    .meeting-form-btn-muted,
    .meeting-form-btn-danger {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 13px 18px;
        border-radius: 18px;
        border: 1px solid transparent;
        text-decoration: none !important;
        font-size: 1.05rem;
        font-weight: 800;
        transition: transform 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease, border-color 0.25s ease, color 0.25s ease;
    }
    .meeting-form-btn {
        background: linear-gradient(135deg, var(--primary-color), #c49b48);
        color: #fff !important;
        box-shadow: 0 18px 30px rgba(170, 134, 63, 0.24);
    }
    .meeting-form-btn-muted {
        background: rgba(255, 255, 255, 0.9);
        color: var(--text-primary) !important;
        border-color: rgba(170, 134, 63, 0.14);
    }
    .meeting-form-btn-danger {
        background: rgba(220, 38, 38, 0.08);
        color: var(--danger-color) !important;
        border-color: rgba(220, 38, 38, 0.16);
    }
    .meeting-form-btn:hover,
    .meeting-form-btn-muted:hover,
    .meeting-form-btn-danger:hover { transform: translateY(-2px); }
    .meeting-form-btn:hover { box-shadow: 0 22px 34px rgba(170, 134, 63, 0.28); }
    .meeting-form-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.9fr) minmax(280px, 0.95fr);
        gap: 22px;
        align-items: start;
    }
    .meeting-panel {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        padding: 24px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.12);
        box-shadow: 0 18px 38px rgba(15, 23, 42, 0.07);
        animation: meetingFormFadeUp 0.8s ease both;
    }
    .meeting-panel::after {
        content: "";
        position: absolute;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        top: -54px;
        inset-inline-end: -50px;
        background: rgba(170, 134, 63, 0.07);
        pointer-events: none;
    }
    .meeting-panel > * { position: relative; z-index: 1; }
    .meeting-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }
    .meeting-panel-title-wrap { display: flex; align-items: center; gap: 14px; }
    .meeting-panel-icon {
        width: 54px;
        height: 54px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(170, 134, 63, 0.16), rgba(170, 134, 63, 0.06));
        color: var(--primary-color);
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .meeting-panel-title {
        margin: 0;
        color: var(--text-primary);
        font-size: clamp(1.35rem, 2vw, 1.65rem);
        font-weight: 900;
    }
    .meeting-panel-subtitle {
        margin: 5px 0 0;
        color: var(--text-secondary);
        font-size: 0.98rem;
        line-height: 1.8;
    }
    .meeting-section {
        padding: 18px;
        border-radius: 24px;
        background: linear-gradient(180deg, rgba(248, 250, 252, 0.85), rgba(255, 255, 255, 0.98));
        border: 1px solid rgba(170, 134, 63, 0.10);
        margin-bottom: 18px;
    }
    .meeting-section:last-child { margin-bottom: 0; }
    .meeting-section-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin: 0 0 16px;
        color: var(--text-primary);
        font-size: 1.12rem;
        font-weight: 900;
        flex-wrap: wrap;
    }
    .meeting-section-title span { display: inline-flex; align-items: center; gap: 10px; }
    .meeting-section-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        min-height: 34px;
        padding: 6px 12px;
        border-radius: 999px;
        background: rgba(170, 134, 63, 0.10);
        color: var(--primary-color);
        font-size: 0.92rem;
        font-weight: 800;
    }
    .meeting-field { margin-bottom: 18px; }
    .meeting-field label {
        margin-bottom: 8px;
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 800;
    }
    .meeting-field .help-block { margin-bottom: 0; font-size: 0.92rem; font-weight: 700; }
    .meeting-input,
    .meeting-textarea {
        width: 100%;
        border-radius: 16px;
        min-height: 50px;
        padding: 12px 16px;
        border: 1px solid rgba(148, 163, 184, 0.24);
        background: rgba(255, 255, 255, 0.96);
        color: var(--text-primary);
        box-shadow: none;
        transition: border-color 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease;
    }
    .meeting-date-input-wrap {
        position: relative;
    }
    .meeting-date-input-wrap .meeting-input {
        padding-inline-end: 56px;
    }
    .meeting-date-trigger {
        position: absolute;
        top: 50%;
        inset-inline-end: 10px;
        transform: translateY(-50%);
        width: 38px;
        height: 38px;
        border: 0;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(170, 134, 63, 0.12);
        color: var(--primary-color);
        transition: transform 0.2s ease, background-color 0.2s ease, color 0.2s ease;
    }
    .meeting-date-trigger:hover {
        transform: translateY(-50%) scale(1.03);
        background: rgba(170, 134, 63, 0.18);
    }
    .meeting-flatpickr-alt-input {
        cursor: pointer;
    }
    .flatpickr-calendar.meeting-calendar {
        border: 1px solid rgba(170, 134, 63, 0.14);
        border-radius: 22px;
        box-shadow: 0 22px 44px rgba(15, 23, 42, 0.12);
        overflow: hidden;
        font-family: var(--app-font-family);
        background: rgba(255, 255, 255, 0.98);
    }
    .flatpickr-calendar.meeting-calendar.arrowTop::before,
    .flatpickr-calendar.meeting-calendar.arrowTop::after {
        border-bottom-color: rgba(170, 134, 63, 0.18);
    }
    .flatpickr-calendar.meeting-calendar .flatpickr-months {
        background: linear-gradient(135deg, rgba(170, 134, 63, 0.10), rgba(196, 168, 90, 0.06));
    }
    .flatpickr-calendar.meeting-calendar .flatpickr-month {
        height: 56px;
    }
    .flatpickr-calendar.meeting-calendar .flatpickr-current-month {
        padding-top: 12px;
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 800;
    }
    .flatpickr-calendar.meeting-calendar .flatpickr-prev-month,
    .flatpickr-calendar.meeting-calendar .flatpickr-next-month {
        padding: 14px 12px;
        color: var(--primary-color);
        fill: var(--primary-color);
    }
    .flatpickr-calendar.meeting-calendar .flatpickr-weekdays {
        background: transparent;
    }
    .flatpickr-calendar.meeting-calendar span.flatpickr-weekday {
        color: var(--text-secondary);
        font-weight: 800;
        font-size: 0.88rem;
    }
    .flatpickr-calendar.meeting-calendar .flatpickr-day {
        border-radius: 14px;
        color: var(--text-primary);
        font-weight: 700;
    }
    .flatpickr-calendar.meeting-calendar .flatpickr-day:hover,
    .flatpickr-calendar.meeting-calendar .flatpickr-day:focus {
        background: rgba(170, 134, 63, 0.12);
        border-color: rgba(170, 134, 63, 0.12);
    }
    .flatpickr-calendar.meeting-calendar .flatpickr-day.selected,
    .flatpickr-calendar.meeting-calendar .flatpickr-day.startRange,
    .flatpickr-calendar.meeting-calendar .flatpickr-day.endRange,
    .flatpickr-calendar.meeting-calendar .flatpickr-day.selected:hover,
    .flatpickr-calendar.meeting-calendar .flatpickr-day.startRange:hover,
    .flatpickr-calendar.meeting-calendar .flatpickr-day.endRange:hover {
        background: linear-gradient(135deg, var(--primary-color), #c49b48);
        border-color: transparent;
        color: #fff;
    }
    .flatpickr-calendar.meeting-calendar .flatpickr-day.today {
        border-color: rgba(170, 134, 63, 0.35);
    }
    .flatpickr-calendar.meeting-calendar .flatpickr-time {
        border-top: 1px solid rgba(170, 134, 63, 0.10);
        background: rgba(248, 250, 252, 0.76);
    }
    .flatpickr-calendar.meeting-calendar .flatpickr-time input,
    .flatpickr-calendar.meeting-calendar .flatpickr-time .flatpickr-am-pm {
        color: var(--text-primary);
        font-weight: 800;
    }
    .flatpickr-calendar.meeting-calendar .flatpickr-time input:hover,
    .flatpickr-calendar.meeting-calendar .flatpickr-time .flatpickr-am-pm:hover,
    .flatpickr-calendar.meeting-calendar .flatpickr-time input:focus,
    .flatpickr-calendar.meeting-calendar .flatpickr-time .flatpickr-am-pm:focus {
        background: rgba(170, 134, 63, 0.10);
    }
    select.meeting-input[multiple] { min-height: 240px; padding: 10px 12px; }
    .meeting-input:focus,
    .meeting-textarea:focus {
        outline: none;
        border-color: rgba(170, 134, 63, 0.48);
        box-shadow: 0 0 0 4px rgba(170, 134, 63, 0.12);
        background: #fff;
    }
    .meeting-field.has-error .meeting-input,
    .meeting-field.has-error .meeting-textarea {
        border-color: rgba(220, 38, 38, 0.5);
        box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.10);
    }
    .meeting-inline-note {
        margin-top: 8px;
        color: var(--text-secondary);
        font-size: 0.92rem;
        line-height: 1.8;
    }
    .meeting-link-preview,
    .meeting-user-card,
    .meeting-attachment-row,
    .meeting-current-attachment {
        background: rgba(255, 255, 255, 0.94);
        border: 1px solid rgba(170, 134, 63, 0.10);
    }
    .meeting-link-preview {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 12px;
        padding: 12px 14px;
        border-radius: 16px;
        color: var(--text-secondary);
        word-break: break-all;
    }
    .meeting-attendees-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(0, 0.8fr);
        gap: 16px;
        align-items: start;
    }
    .meeting-selected-users,
    .meeting-attachments-stack,
    .meeting-current-attachments,
    .meeting-side-stack,
    .meeting-tip-list,
    .meeting-meta-list { display: flex; flex-direction: column; gap: 12px; }
    .meeting-user-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        border-radius: 18px;
    }
    .meeting-user-avatar {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary-color), #cfa75b);
        color: #fff;
        font-size: 1rem;
        font-weight: 900;
        flex-shrink: 0;
    }
    .meeting-user-card strong { display: block; color: var(--text-primary); font-size: 0.98rem; font-weight: 800; }
    .meeting-user-card small { color: var(--text-secondary); font-size: 0.88rem; }
    .meeting-empty-box {
        padding: 20px;
        border-radius: 20px;
        text-align: center;
        color: var(--text-secondary);
        background: rgba(255, 255, 255, 0.82);
        border: 1px dashed rgba(170, 134, 63, 0.16);
    }
    .meeting-attachment-row,
    .meeting-current-attachment { padding: 14px; border-radius: 18px; animation: meetingAttachmentSlide 0.3s ease both; }
    .meeting-attachment-head,
    .meeting-current-attachment-head,
    .meeting-current-attachment-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }
    .meeting-attachment-head { margin-bottom: 12px; }
    .meeting-attachment-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--text-primary);
        font-size: 0.96rem;
        font-weight: 800;
    }
    .meeting-attachment-remove {
        width: 40px;
        height: 40px;
        border: 0;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(220, 38, 38, 0.10);
        color: var(--danger-color);
    }
    .meeting-file-preview,
    .meeting-current-attachment-meta {
        margin-top: 10px;
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.8;
    }
    .meeting-add-attachment { margin-top: 14px; }
    .meeting-current-attachment-name {
        margin: 0;
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 800;
    }
    .meeting-current-attachment-actions a,
    .meeting-current-attachment-actions button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 14px;
        border: 1px solid transparent;
        text-decoration: none !important;
        font-weight: 800;
    }
    .meeting-current-attachment-actions a { background: rgba(170, 134, 63, 0.10); color: var(--primary-color); }
    .meeting-current-attachment-actions button { background: rgba(220, 38, 38, 0.08); color: var(--danger-color); border-color: rgba(220, 38, 38, 0.12); }
    .meeting-mini-card {
        position: relative;
        overflow: hidden;
        border-radius: 24px;
        padding: 20px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.12);
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.06);
        animation: meetingFormFadeUp 0.88s ease both;
    }
    .meeting-mini-card::after {
        content: "";
        position: absolute;
        inset: auto -40px -70px auto;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(170, 134, 63, 0.08);
    }
    .meeting-mini-card > * { position: relative; z-index: 1; }
    .meeting-mini-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 14px;
        color: var(--text-primary);
        font-size: 1.14rem;
        font-weight: 900;
    }
    .meeting-tip-item,
    .meeting-meta-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        color: var(--text-secondary);
        font-size: 0.98rem;
        line-height: 1.8;
    }
    .meeting-tip-item i,
    .meeting-meta-item i {
        width: 34px;
        height: 34px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(170, 134, 63, 0.12);
        color: var(--primary-color);
        flex-shrink: 0;
    }
    .meeting-stat-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }
    .meeting-stat-box {
        padding: 14px;
        border-radius: 18px;
        background: rgba(248, 250, 252, 0.94);
        border: 1px solid rgba(170, 134, 63, 0.10);
    }
    .meeting-stat-box strong { display: block; color: var(--text-primary); font-size: 1.18rem; font-weight: 900; }
    .meeting-stat-box span { display: block; margin-top: 6px; color: var(--text-secondary); font-size: 0.92rem; line-height: 1.6; }
    .meeting-form-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        margin-top: 24px;
        padding-top: 18px;
        border-top: 1px solid rgba(170, 134, 63, 0.12);
    }
    .meeting-form-footer-note { color: var(--text-secondary); font-size: 0.96rem; line-height: 1.8; }
    .meeting-form-footer-actions { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
    html[data-theme="dark"] .meeting-form-hero {
        background:
            radial-gradient(circle at top right, rgba(141, 110, 43, 0.28), transparent 32%),
            linear-gradient(135deg, rgba(17, 24, 39, 0.98) 0%, rgba(15, 23, 42, 0.98) 55%, rgba(30, 41, 59, 0.98) 100%);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 24px 52px rgba(2, 6, 23, 0.34);
    }
    html[data-theme="dark"] .meeting-form-btn-muted,
    html[data-theme="dark"] .meeting-panel,
    html[data-theme="dark"] .meeting-mini-card,
    html[data-theme="dark"] .meeting-link-preview,
    html[data-theme="dark"] .meeting-user-card,
    html[data-theme="dark"] .meeting-attachment-row,
    html[data-theme="dark"] .meeting-current-attachment {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 18px 38px rgba(2, 6, 23, 0.28);
    }
    html[data-theme="dark"] .meeting-section,
    html[data-theme="dark"] .meeting-stat-box,
    html[data-theme="dark"] .meeting-empty-box {
        background: rgba(15, 23, 42, 0.76);
        border-color: rgba(148, 163, 184, 0.12);
    }
    html[data-theme="dark"] .meeting-section-chip,
    html[data-theme="dark"] .meeting-form-badge {
        background: rgba(141, 110, 43, 0.16);
        color: #f6deb0;
    }
    html[data-theme="dark"] .meeting-input,
    html[data-theme="dark"] .meeting-textarea {
        background: rgba(15, 23, 42, 0.95);
        border-color: rgba(148, 163, 184, 0.20);
        color: var(--text-primary);
    }
    html[data-theme="dark"] .meeting-input:focus,
    html[data-theme="dark"] .meeting-textarea:focus {
        background: rgba(15, 23, 42, 1);
    }
    html[data-theme="dark"] .meeting-date-trigger {
        background: rgba(141, 110, 43, 0.16);
        color: #f6deb0;
    }
    html[data-theme="dark"] .meeting-current-attachment-actions a,
    html[data-theme="dark"] .meeting-form-btn-danger,
    html[data-theme="dark"] .meeting-attachment-remove {
        border-color: rgba(220, 38, 38, 0.18);
    }
    html[data-theme="dark"] .meeting-form-btn-muted:hover,
    html[data-theme="dark"] .meeting-current-attachment-actions a:hover {
        color: #f6deb0 !important;
        border-color: rgba(141, 110, 43, 0.26);
    }
    html[data-theme="dark"] .meeting-inline-note,
    html[data-theme="dark"] .meeting-file-preview,
    html[data-theme="dark"] .meeting-current-attachment-meta,
    html[data-theme="dark"] .meeting-tip-item,
    html[data-theme="dark"] .meeting-meta-item,
    html[data-theme="dark"] .meeting-stat-box span {
        color: var(--text-secondary);
    }
    html[data-theme="dark"] .flatpickr-calendar.meeting-calendar {
        background: rgba(15, 23, 42, 0.98);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 22px 44px rgba(2, 6, 23, 0.34);
    }
    html[data-theme="dark"] .flatpickr-calendar.meeting-calendar .flatpickr-months,
    html[data-theme="dark"] .flatpickr-calendar.meeting-calendar .flatpickr-time {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.14);
    }
    html[data-theme="dark"] .flatpickr-calendar.meeting-calendar .flatpickr-current-month,
    html[data-theme="dark"] .flatpickr-calendar.meeting-calendar span.flatpickr-weekday,
    html[data-theme="dark"] .flatpickr-calendar.meeting-calendar .flatpickr-day,
    html[data-theme="dark"] .flatpickr-calendar.meeting-calendar .flatpickr-time input,
    html[data-theme="dark"] .flatpickr-calendar.meeting-calendar .flatpickr-time .flatpickr-am-pm {
        color: var(--text-primary);
    }
    html[data-theme="dark"] .flatpickr-calendar.meeting-calendar .flatpickr-prev-month,
    html[data-theme="dark"] .flatpickr-calendar.meeting-calendar .flatpickr-next-month {
        color: #f6deb0;
        fill: #f6deb0;
    }
    html[data-theme="dark"] .flatpickr-calendar.meeting-calendar .flatpickr-day:hover,
    html[data-theme="dark"] .flatpickr-calendar.meeting-calendar .flatpickr-day:focus,
    html[data-theme="dark"] .flatpickr-calendar.meeting-calendar .flatpickr-time input:hover,
    html[data-theme="dark"] .flatpickr-calendar.meeting-calendar .flatpickr-time .flatpickr-am-pm:hover {
        background: rgba(141, 110, 43, 0.16);
        border-color: rgba(141, 110, 43, 0.16);
    }
    html[data-theme="dark"] .flatpickr-calendar.meeting-calendar .flatpickr-day.prevMonthDay,
    html[data-theme="dark"] .flatpickr-calendar.meeting-calendar .flatpickr-day.nextMonthDay {
        color: var(--text-light);
    }
    @media (max-width: 1199px) {
        .meeting-form-grid,
        .meeting-attendees-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 767px) {
        .meeting-form-page { padding-top: 0; }
        .meeting-panel,
        .meeting-mini-card,
        .meeting-form-hero { border-radius: 24px; padding: 20px; }
        .meeting-form-footer { align-items: stretch; }
        .meeting-form-footer-actions { width: 100%; }
        .meeting-form-footer-actions .meeting-form-btn,
        .meeting-form-footer-actions .meeting-form-btn-muted,
        .meeting-form-footer-actions .meeting-form-btn-danger {
            flex: 1 1 100%;
            justify-content: center;
        }
        .meeting-stat-grid { grid-template-columns: 1fr; }
    }
    @keyframes meetingFormFadeUp {
        from { opacity: 0; transform: translateY(18px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes meetingAttachmentSlide {
        from { opacity: 0; transform: translateX(12px); }
        to { opacity: 1; transform: translateX(0); }
    }
</style>
@endpush
