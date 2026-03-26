@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .bookf-page {
        padding: 8px 0 28px;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .bookf-shell {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .bookf-hero {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        padding: 30px;
        background:
            radial-gradient(circle at top left, rgba(196, 168, 90, 0.3), transparent 34%),
            linear-gradient(135deg, #fffaf0 0%, #ffffff 48%, #f5efe2 100%);
        border: 1px solid rgba(170, 134, 63, 0.16);
        box-shadow: 0 18px 48px rgba(15, 23, 42, 0.08);
    }

    .bookf-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
    }

    .bookf-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(170, 134, 63, 0.1);
        color: var(--primary-color);
        font-size: 1rem;
        font-weight: 800;
    }

    .bookf-title {
        margin: 0;
        font-size: clamp(2rem, 3vw, 2.7rem);
        font-weight: 900;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .bookf-subtitle {
        margin: 12px 0 0;
        max-width: 780px;
        color: var(--text-secondary);
        font-size: 1.06rem;
        line-height: 1.9;
    }

    .bookf-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .bookf-btn,
    .bookf-btn-muted {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 52px;
        padding: 14px 20px;
        border: 0;
        border-radius: 18px;
        text-decoration: none !important;
        font-size: 1rem;
        font-weight: 800;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .bookf-btn {
        background: linear-gradient(135deg, var(--primary-color), #c49b48);
        color: #fff !important;
        box-shadow: 0 18px 28px rgba(170, 134, 63, 0.24);
    }

    .bookf-btn-muted {
        background: rgba(255, 255, 255, 0.9);
        color: var(--text-primary) !important;
        border: 1px solid rgba(170, 134, 63, 0.16);
    }

    .bookf-btn:hover,
    .bookf-btn-muted:hover {
        transform: translateY(-2px);
    }

    .bookf-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.5fr) minmax(300px, 0.85fr);
        gap: 22px;
        align-items: start;
    }

    .bookf-panel,
    .bookf-mini-card {
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 16px 38px rgba(15, 23, 42, 0.06);
    }

    .bookf-panel {
        padding: 24px;
    }

    .bookf-panel-header {
        margin-bottom: 20px;
    }

    .bookf-panel-title-wrap {
        display: flex;
        align-items: flex-start;
        gap: 16px;
    }

    .bookf-panel-icon {
        width: 56px;
        height: 56px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(170, 134, 63, 0.12);
        color: var(--primary-color);
        font-size: 1.35rem;
        flex-shrink: 0;
    }

    .bookf-panel-title {
        margin: 0;
        font-size: 1.35rem;
        font-weight: 900;
        color: var(--text-primary);
    }

    .bookf-panel-subtitle {
        margin: 8px 0 0;
        color: var(--text-secondary);
        line-height: 1.8;
    }

    .bookf-fields-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .bookf-field,
    .bookf-preview {
        border-radius: 22px;
        background: rgba(248, 250, 252, 0.92);
        border: 1px solid rgba(170, 134, 63, 0.1);
        padding: 18px;
    }

    .bookf-field.full-width {
        grid-column: 1 / -1;
    }

    .bookf-field label {
        display: block;
        margin-bottom: 10px;
        color: var(--text-primary);
        font-size: 0.97rem;
        font-weight: 800;
    }

    .bookf-input,
    .bookf-select,
    .bookf-textarea {
        width: 100%;
        min-height: 54px;
        border-radius: 16px;
        border: 1px solid rgba(170, 134, 63, 0.15);
        background: #fff;
        color: var(--text-primary);
        font-size: 1rem;
        padding: 14px 16px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }

    .bookf-textarea {
        min-height: 130px;
        resize: vertical;
    }

    .bookf-input:focus,
    .bookf-select:focus,
    .bookf-textarea:focus {
        outline: none;
        border-color: rgba(170, 134, 63, 0.4);
        box-shadow: 0 0 0 4px rgba(170, 134, 63, 0.12);
    }

    .bookf-help {
        display: block;
        margin-top: 10px;
        color: var(--text-secondary);
        font-size: 0.93rem;
        line-height: 1.8;
    }

    .bookf-error {
        margin-top: 10px;
        color: var(--danger-color);
        font-size: 0.92rem;
        font-weight: 700;
    }

    .bookf-preview {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .bookf-preview strong {
        color: var(--text-primary);
    }

    .bookf-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid rgba(170, 134, 63, 0.12);
    }

    .bookf-footer-note {
        margin: 0;
        max-width: 720px;
        color: var(--text-secondary);
        line-height: 1.8;
    }

    .bookf-footer-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .bookf-side-stack {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .bookf-mini-card {
        padding: 22px;
    }

    .bookf-mini-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 16px;
        color: var(--text-primary);
        font-size: 1.08rem;
        font-weight: 900;
    }

    .bookf-tip-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .bookf-tip-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        color: var(--text-secondary);
        line-height: 1.8;
    }

    .bookf-tip-item i {
        color: var(--primary-color);
        font-size: 1rem;
        margin-top: 3px;
    }

    .bookf-stat-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .bookf-stat-box {
        border-radius: 18px;
        padding: 16px;
        background: rgba(248, 250, 252, 0.92);
        border: 1px solid rgba(170, 134, 63, 0.1);
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .bookf-stat-box strong {
        color: var(--text-primary);
        font-size: 1.15rem;
        font-weight: 900;
    }

    .bookf-stat-box span {
        color: var(--text-secondary);
        line-height: 1.7;
    }

    .flatpickr-calendar {
        border-radius: 18px;
        border: 1px solid rgba(170, 134, 63, 0.18);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
        font-family: inherit;
    }

    .flatpickr-day.selected,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange,
    .flatpickr-day.selected:hover {
        background: var(--primary-color);
        border-color: var(--primary-color);
    }

    .flatpickr-time .flatpickr-am-pm,
    .flatpickr-time input:hover,
    .flatpickr-time .numInputWrapper:hover {
        background: rgba(170, 134, 63, 0.08);
    }

    html[data-theme="dark"] .bookf-hero {
        background:
            radial-gradient(circle at top left, rgba(141, 110, 43, 0.28), transparent 34%),
            linear-gradient(135deg, rgba(17, 24, 39, 0.98) 0%, rgba(15, 23, 42, 0.98) 48%, rgba(30, 41, 59, 0.98) 100%);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 24px 52px rgba(2, 6, 23, 0.34);
    }

    html[data-theme="dark"] .bookf-panel,
    html[data-theme="dark"] .bookf-mini-card,
    html[data-theme="dark"] .bookf-field,
    html[data-theme="dark"] .bookf-preview,
    html[data-theme="dark"] .bookf-stat-box,
    html[data-theme="dark"] .bookf-btn-muted,
    html[data-theme="dark"] .flatpickr-calendar {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 18px 38px rgba(2, 6, 23, 0.28);
    }

    html[data-theme="dark"] .bookf-input,
    html[data-theme="dark"] .bookf-select,
    html[data-theme="dark"] .bookf-textarea,
    html[data-theme="dark"] .flatpickr-months,
    html[data-theme="dark"] .flatpickr-weekdays,
    html[data-theme="dark"] .flatpickr-days,
    html[data-theme="dark"] .flatpickr-time {
        background: rgba(15, 23, 42, 0.95);
        border-color: rgba(148, 163, 184, 0.2);
        color: var(--text-primary);
    }

    html[data-theme="dark"] .bookf-badge {
        background: rgba(141, 110, 43, 0.16);
        color: #f6deb0;
    }

    @media (max-width: 1199px) {
        .bookf-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .bookf-fields-grid,
        .bookf-stat-grid {
            grid-template-columns: 1fr;
        }

        .bookf-panel,
        .bookf-mini-card {
            padding: 18px;
        }
    }
</style>
@endpush
