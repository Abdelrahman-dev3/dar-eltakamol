@push('styles')
<style>
    .servf-page {
        padding: 8px 0 28px;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .servf-shell {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .servf-hero {
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

    .servf-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
    }

    .servf-badge {
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

    .servf-title {
        margin: 0;
        font-size: clamp(2rem, 3vw, 2.7rem);
        font-weight: 900;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .servf-subtitle {
        margin: 12px 0 0;
        max-width: 760px;
        color: var(--text-secondary);
        font-size: 1.06rem;
        line-height: 1.9;
    }

    .servf-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .servf-btn,
    .servf-btn-muted {
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

    .servf-btn {
        background: linear-gradient(135deg, var(--primary-color), #c49b48);
        color: #fff !important;
        box-shadow: 0 18px 28px rgba(170, 134, 63, 0.24);
    }

    .servf-btn-muted {
        background: rgba(255, 255, 255, 0.9);
        color: var(--text-primary) !important;
        border: 1px solid rgba(170, 134, 63, 0.16);
    }

    .servf-btn:hover,
    .servf-btn-muted:hover {
        transform: translateY(-2px);
    }

    .servf-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.5fr) minmax(300px, 0.85fr);
        gap: 22px;
        align-items: start;
    }

    .servf-panel,
    .servf-mini-card {
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 16px 38px rgba(15, 23, 42, 0.06);
    }

    .servf-panel {
        padding: 24px;
    }

    .servf-panel-header {
        margin-bottom: 20px;
    }

    .servf-panel-title-wrap {
        display: flex;
        align-items: flex-start;
        gap: 16px;
    }

    .servf-panel-icon {
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

    .servf-panel-title {
        margin: 0;
        font-size: 1.35rem;
        font-weight: 900;
        color: var(--text-primary);
    }

    .servf-panel-subtitle {
        margin: 8px 0 0;
        color: var(--text-secondary);
        line-height: 1.8;
    }

    .servf-field {
        border-radius: 22px;
        background: rgba(248, 250, 252, 0.92);
        border: 1px solid rgba(170, 134, 63, 0.1);
        padding: 18px;
    }

    .servf-field label {
        display: block;
        margin-bottom: 10px;
        color: var(--text-primary);
        font-size: 0.97rem;
        font-weight: 800;
    }

    .servf-input {
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

    .servf-input:focus {
        outline: none;
        border-color: rgba(170, 134, 63, 0.4);
        box-shadow: 0 0 0 4px rgba(170, 134, 63, 0.12);
    }

    .servf-help {
        display: block;
        margin-top: 10px;
        color: var(--text-secondary);
        font-size: 0.93rem;
        line-height: 1.8;
    }

    .servf-error {
        margin-top: 10px;
        color: var(--danger-color);
        font-size: 0.92rem;
        font-weight: 700;
    }

    .servf-preview {
        margin-top: 18px;
        padding: 16px 18px;
        border-radius: 18px;
        background: rgba(248, 250, 252, 0.92);
        border: 1px dashed rgba(170, 134, 63, 0.16);
        color: var(--text-secondary);
        line-height: 1.8;
    }

    .servf-preview strong {
        color: var(--text-primary);
    }

    .servf-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid rgba(170, 134, 63, 0.12);
    }

    .servf-footer-note {
        margin: 0;
        max-width: 720px;
        color: var(--text-secondary);
        line-height: 1.8;
    }

    .servf-footer-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .servf-side-stack {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .servf-mini-card {
        padding: 22px;
    }

    .servf-mini-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 16px;
        color: var(--text-primary);
        font-size: 1.08rem;
        font-weight: 900;
    }

    .servf-tip-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .servf-tip-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        color: var(--text-secondary);
        line-height: 1.8;
    }

    .servf-tip-item i {
        color: var(--primary-color);
        font-size: 1rem;
        margin-top: 3px;
    }

    .servf-stat-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .servf-stat-box {
        border-radius: 18px;
        padding: 16px;
        background: rgba(248, 250, 252, 0.92);
        border: 1px solid rgba(170, 134, 63, 0.1);
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .servf-stat-box strong {
        color: var(--text-primary);
        font-size: 1.15rem;
        font-weight: 900;
    }

    .servf-stat-box span {
        color: var(--text-secondary);
        line-height: 1.7;
    }

    html[data-theme="dark"] .servf-hero {
        background:
            radial-gradient(circle at top left, rgba(141, 110, 43, 0.28), transparent 34%),
            linear-gradient(135deg, rgba(17, 24, 39, 0.98) 0%, rgba(15, 23, 42, 0.98) 48%, rgba(30, 41, 59, 0.98) 100%);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 24px 52px rgba(2, 6, 23, 0.34);
    }

    html[data-theme="dark"] .servf-panel,
    html[data-theme="dark"] .servf-mini-card,
    html[data-theme="dark"] .servf-field,
    html[data-theme="dark"] .servf-preview,
    html[data-theme="dark"] .servf-stat-box,
    html[data-theme="dark"] .servf-btn-muted {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 18px 38px rgba(2, 6, 23, 0.28);
    }

    html[data-theme="dark"] .servf-input {
        background: rgba(15, 23, 42, 0.95);
        border-color: rgba(148, 163, 184, 0.2);
        color: var(--text-primary);
    }

    html[data-theme="dark"] .servf-badge {
        background: rgba(141, 110, 43, 0.16);
        color: #f6deb0;
    }

    @media (max-width: 1199px) {
        .servf-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .servf-stat-grid {
            grid-template-columns: 1fr;
        }

        .servf-panel,
        .servf-mini-card {
            padding: 18px;
        }
    }
</style>
@endpush
