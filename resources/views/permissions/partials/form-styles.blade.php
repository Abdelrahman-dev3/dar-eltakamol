@push('styles')
<style>
    .permf-page {
        padding: 8px 0 28px;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .permf-shell {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .permf-hero {
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

    .permf-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
    }

    .permf-badge {
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

    .permf-title {
        margin: 0;
        font-size: clamp(2rem, 3vw, 2.7rem);
        font-weight: 900;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .permf-subtitle {
        margin: 12px 0 0;
        max-width: 780px;
        color: var(--text-secondary);
        font-size: 1.06rem;
        line-height: 1.9;
    }

    .permf-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .permf-btn,
    .permf-btn-muted {
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

    .permf-btn {
        background: linear-gradient(135deg, var(--primary-color), #c49b48);
        color: #fff !important;
        box-shadow: 0 18px 28px rgba(170, 134, 63, 0.24);
    }

    .permf-btn-muted {
        background: rgba(255, 255, 255, 0.9);
        color: var(--text-primary) !important;
        border: 1px solid rgba(170, 134, 63, 0.16);
    }

    .permf-btn:hover,
    .permf-btn-muted:hover {
        transform: translateY(-2px);
    }

    .permf-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.5fr) minmax(300px, 0.9fr);
        gap: 22px;
        align-items: start;
    }

    .permf-panel,
    .permf-mini-card {
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 16px 38px rgba(15, 23, 42, 0.06);
    }

    .permf-panel {
        padding: 24px;
    }

    .permf-panel-header {
        margin-bottom: 20px;
    }

    .permf-panel-title-wrap {
        display: flex;
        align-items: flex-start;
        gap: 16px;
    }

    .permf-panel-icon {
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

    .permf-panel-title {
        margin: 0;
        font-size: 1.35rem;
        font-weight: 900;
        color: var(--text-primary);
    }

    .permf-panel-subtitle {
        margin: 8px 0 0;
        color: var(--text-secondary);
        line-height: 1.8;
    }

    .permf-section {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-top: 24px;
    }

    .permf-section:first-of-type {
        margin-top: 0;
    }

    .permf-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
        color: var(--text-primary);
        font-size: 1.15rem;
        font-weight: 900;
    }

    .permf-fields-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .permf-field,
    .permf-departments,
    .permf-preview {
        border-radius: 22px;
        background: rgba(248, 250, 252, 0.92);
        border: 1px solid rgba(170, 134, 63, 0.1);
        padding: 18px;
    }

    .permf-field.full-width {
        grid-column: 1 / -1;
    }

    .permf-field label {
        display: block;
        margin-bottom: 10px;
        color: var(--text-primary);
        font-size: 0.97rem;
        font-weight: 800;
    }

    .permf-input,
    .permf-select,
    .permf-textarea {
        width: 100%;
        min-height: 52px;
        border-radius: 16px;
        border: 1px solid rgba(170, 134, 63, 0.15);
        background: #fff;
        color: var(--text-primary);
        font-size: 1rem;
        padding: 14px 16px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }

    .permf-textarea {
        min-height: 130px;
        resize: vertical;
    }

    .permf-input:focus,
    .permf-select:focus,
    .permf-textarea:focus {
        outline: none;
        border-color: rgba(170, 134, 63, 0.4);
        box-shadow: 0 0 0 4px rgba(170, 134, 63, 0.12);
    }

    .permf-help,
    .permf-inline-note {
        display: block;
        margin-top: 10px;
        color: var(--text-secondary);
        font-size: 0.93rem;
        line-height: 1.8;
    }

    .permf-error {
        margin-top: 10px;
        color: var(--danger-color);
        font-size: 0.92rem;
        font-weight: 700;
    }

    .permf-search {
        position: relative;
    }

    .permf-search i {
        position: absolute;
        top: 50%;
        inset-inline-start: 16px;
        transform: translateY(-50%);
        color: var(--text-light);
        font-size: 1rem;
    }

    .permf-search input {
        padding-inline-start: 44px;
    }

    .permf-departments-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .permf-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 40px;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(170, 134, 63, 0.1);
        color: var(--primary-color);
        font-size: 0.92rem;
        font-weight: 800;
    }

    .permf-department-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .permf-department-option {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        min-height: 78px;
        padding: 14px;
        border-radius: 18px;
        background: #fff;
        border: 1px solid rgba(170, 134, 63, 0.12);
        transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        cursor: pointer;
    }

    .permf-department-option:hover {
        transform: translateY(-2px);
        border-color: rgba(170, 134, 63, 0.24);
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.08);
    }

    .permf-department-option input {
        margin-top: 4px;
        flex-shrink: 0;
    }

    .permf-department-option.is-hidden {
        display: none;
    }

    .permf-department-title {
        display: block;
        color: var(--text-primary);
        font-weight: 800;
        line-height: 1.6;
    }

    .permf-department-subtitle {
        display: block;
        margin-top: 4px;
        color: var(--text-secondary);
        font-size: 0.92rem;
    }

    .permf-preview {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .permf-preview-label {
        color: var(--text-secondary);
        font-size: 0.92rem;
        font-weight: 700;
    }

    .permf-preview-value {
        color: var(--text-primary);
        font-size: 1.08rem;
        font-weight: 900;
        line-height: 1.7;
    }

    .permf-preview-code {
        display: inline-flex;
        align-items: center;
        width: fit-content;
        max-width: 100%;
        padding: 8px 12px;
        border-radius: 12px;
        background: rgba(15, 23, 42, 0.06);
        color: var(--text-secondary);
        font-size: 0.92rem;
        overflow-wrap: anywhere;
    }

    .permf-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid rgba(170, 134, 63, 0.12);
    }

    .permf-footer-note {
        margin: 0;
        max-width: 720px;
        color: var(--text-secondary);
        line-height: 1.8;
    }

    .permf-footer-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .permf-side-stack {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .permf-mini-card {
        padding: 22px;
    }

    .permf-mini-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 16px;
        color: var(--text-primary);
        font-size: 1.08rem;
        font-weight: 900;
    }

    .permf-tip-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .permf-tip-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        color: var(--text-secondary);
        line-height: 1.8;
    }

    .permf-tip-item i {
        color: var(--primary-color);
        font-size: 1rem;
        margin-top: 3px;
    }

    .permf-stat-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .permf-stat-box {
        border-radius: 18px;
        padding: 16px;
        background: rgba(248, 250, 252, 0.92);
        border: 1px solid rgba(170, 134, 63, 0.1);
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .permf-stat-box strong {
        color: var(--text-primary);
        font-size: 1.15rem;
        font-weight: 900;
    }

    .permf-stat-box span {
        color: var(--text-secondary);
        line-height: 1.7;
    }

    html[data-theme="dark"] .permf-hero {
        background:
            radial-gradient(circle at top left, rgba(141, 110, 43, 0.28), transparent 34%),
            linear-gradient(135deg, rgba(17, 24, 39, 0.98) 0%, rgba(15, 23, 42, 0.98) 48%, rgba(30, 41, 59, 0.98) 100%);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 24px 52px rgba(2, 6, 23, 0.34);
    }

    html[data-theme="dark"] .permf-panel,
    html[data-theme="dark"] .permf-mini-card,
    html[data-theme="dark"] .permf-field,
    html[data-theme="dark"] .permf-departments,
    html[data-theme="dark"] .permf-preview,
    html[data-theme="dark"] .permf-stat-box,
    html[data-theme="dark"] .permf-department-option,
    html[data-theme="dark"] .permf-btn-muted {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 18px 38px rgba(2, 6, 23, 0.28);
    }

    html[data-theme="dark"] .permf-input,
    html[data-theme="dark"] .permf-select,
    html[data-theme="dark"] .permf-textarea {
        background: rgba(15, 23, 42, 0.95);
        border-color: rgba(148, 163, 184, 0.2);
        color: var(--text-primary);
    }

    html[data-theme="dark"] .permf-preview-code {
        background: rgba(148, 163, 184, 0.12);
        color: var(--text-secondary);
    }

    html[data-theme="dark"] .permf-badge,
    html[data-theme="dark"] .permf-chip {
        background: rgba(141, 110, 43, 0.16);
        color: #f6deb0;
    }

    @media (max-width: 1199px) {
        .permf-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .permf-fields-grid,
        .permf-department-grid,
        .permf-stat-grid {
            grid-template-columns: 1fr;
        }

        .permf-panel,
        .permf-mini-card {
            padding: 18px;
        }
    }
</style>
@endpush
