@push('styles')
<style>
    .doc-form-page {
        padding: 10px 0 30px;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .doc-form-page button,
    .doc-form-page input,
    .doc-form-page select,
    .doc-form-page textarea {
        font: inherit;
    }

    .doc-form-shell {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .doc-form-hero {
        position: relative;
        overflow: hidden;
        border-radius: 30px;
        padding: clamp(24px, 3vw, 34px);
        background:
            radial-gradient(circle at top right, rgba(196, 168, 90, 0.28), transparent 30%),
            linear-gradient(135deg, #fff8ec 0%, #ffffff 46%, #f5ecdc 100%);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
        animation: docFadeUp 0.72s ease both;
    }

    .doc-form-hero::before,
    .doc-form-hero::after {
        content: "";
        position: absolute;
        border-radius: 999px;
        pointer-events: none;
    }

    .doc-form-hero::before {
        width: 220px;
        height: 220px;
        top: -120px;
        inset-inline-end: -70px;
        background: rgba(170, 134, 63, 0.10);
    }

    .doc-form-hero::after {
        width: 180px;
        height: 180px;
        bottom: -100px;
        inset-inline-start: -50px;
        background: rgba(196, 168, 90, 0.13);
    }

    .doc-form-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 22px;
        flex-wrap: wrap;
    }

    .doc-form-badge {
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

    .doc-form-title {
        margin: 0;
        color: var(--text-primary);
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 900;
        line-height: 1.1;
    }

    .doc-form-subtitle {
        margin: 12px 0 0;
        max-width: 760px;
        color: var(--text-secondary);
        font-size: clamp(1rem, 1.5vw, 1.18rem);
        line-height: 1.9;
    }

    .doc-form-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .doc-form-btn,
    .doc-form-btn-muted,
    .doc-form-btn-danger {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 13px 18px;
        border-radius: 18px;
        border: 1px solid transparent;
        text-decoration: none !important;
        font-size: 1.02rem;
        font-weight: 800;
        transition: transform 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease, border-color 0.25s ease, color 0.25s ease;
    }

    .doc-form-btn {
        background: linear-gradient(135deg, var(--primary-color), #c49b48);
        color: #fff !important;
        box-shadow: 0 18px 30px rgba(170, 134, 63, 0.24);
    }

    .doc-form-btn-muted {
        background: rgba(255, 255, 255, 0.9);
        color: var(--text-primary) !important;
        border-color: rgba(170, 134, 63, 0.14);
    }

    .doc-form-btn-danger {
        background: rgba(220, 38, 38, 0.08);
        color: var(--danger-color) !important;
        border-color: rgba(220, 38, 38, 0.16);
    }

    .doc-form-btn:hover,
    .doc-form-btn-muted:hover,
    .doc-form-btn-danger:hover {
        transform: translateY(-2px);
    }

    .doc-form-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.85fr) minmax(280px, 0.95fr);
        gap: 22px;
        align-items: start;
    }

    .doc-panel {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        padding: 24px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.12);
        box-shadow: 0 18px 38px rgba(15, 23, 42, 0.07);
        animation: docFadeUp 0.8s ease both;
    }

    .doc-panel::after {
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

    .doc-panel > * {
        position: relative;
        z-index: 1;
    }

    .doc-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }

    .doc-panel-title-wrap {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .doc-panel-icon {
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

    .doc-panel-title {
        margin: 0;
        color: var(--text-primary);
        font-size: clamp(1.35rem, 2vw, 1.65rem);
        font-weight: 900;
    }

    .doc-panel-subtitle {
        margin: 5px 0 0;
        color: var(--text-secondary);
        font-size: 0.98rem;
        line-height: 1.8;
    }

    .doc-section {
        padding: 18px;
        border-radius: 24px;
        background: linear-gradient(180deg, rgba(248, 250, 252, 0.85), rgba(255, 255, 255, 0.98));
        border: 1px solid rgba(170, 134, 63, 0.10);
        margin-bottom: 18px;
    }

    .doc-section:last-child {
        margin-bottom: 0;
    }

    .doc-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 16px;
        color: var(--text-primary);
        font-size: 1.12rem;
        font-weight: 900;
    }

    .doc-field {
        margin-bottom: 18px;
    }

    .doc-field:last-child {
        margin-bottom: 0;
    }

    .doc-field label {
        margin-bottom: 8px;
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 800;
    }

    .doc-field .help-block {
        margin-bottom: 0;
        font-size: 0.92rem;
        font-weight: 700;
    }

    .doc-input {
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

    .doc-input:focus {
        outline: none;
        border-color: rgba(170, 134, 63, 0.48);
        box-shadow: 0 0 0 4px rgba(170, 134, 63, 0.12);
        background: #fff;
    }

    .doc-field.has-error .doc-input {
        border-color: rgba(220, 38, 38, 0.5);
        box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.10);
    }

    .doc-inline-note {
        margin-top: 8px;
        color: var(--text-secondary);
        font-size: 0.92rem;
        line-height: 1.8;
    }

    .doc-upload-zone {
        position: relative;
        border-radius: 24px;
        border: 1px dashed rgba(170, 134, 63, 0.24);
        background:
            radial-gradient(circle at top, rgba(196, 168, 90, 0.10), transparent 45%),
            rgba(255, 248, 236, 0.8);
        padding: 22px;
    }

    .doc-upload-zone .doc-input[type="file"] {
        padding: 10px 12px;
    }

    .doc-files-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 14px;
    }

    .doc-file-item,
    .doc-current-file {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 14px;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.94);
        border: 1px solid rgba(170, 134, 63, 0.10);
        animation: docFadeUp 0.35s ease both;
    }

    .doc-file-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .doc-file-icon {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(170, 134, 63, 0.12);
        color: var(--primary-color);
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .doc-file-meta strong {
        display: block;
        color: var(--text-primary);
        font-size: 0.98rem;
        font-weight: 800;
        word-break: break-word;
    }

    .doc-file-meta small,
    .doc-current-file-text {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.7;
    }

    .doc-current-file-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .doc-current-file-actions a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 14px;
        border: 1px solid transparent;
        text-decoration: none !important;
        font-weight: 800;
        background: rgba(170, 134, 63, 0.10);
        color: var(--primary-color);
    }

    .doc-side-stack {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .doc-mini-card {
        position: relative;
        overflow: hidden;
        border-radius: 24px;
        padding: 20px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.12);
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.06);
        animation: docFadeUp 0.88s ease both;
    }

    .doc-mini-card::after {
        content: "";
        position: absolute;
        inset: auto -40px -70px auto;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(170, 134, 63, 0.08);
    }

    .doc-mini-card > * {
        position: relative;
        z-index: 1;
    }

    .doc-mini-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 14px;
        color: var(--text-primary);
        font-size: 1.14rem;
        font-weight: 900;
    }

    .doc-tip-list,
    .doc-meta-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .doc-tip-item,
    .doc-meta-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        color: var(--text-secondary);
        font-size: 0.98rem;
        line-height: 1.8;
    }

    .doc-tip-item i,
    .doc-meta-item i {
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

    .doc-stat-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .doc-stat-box {
        padding: 14px;
        border-radius: 18px;
        background: rgba(248, 250, 252, 0.94);
        border: 1px solid rgba(170, 134, 63, 0.10);
    }

    .doc-stat-box strong {
        display: block;
        color: var(--text-primary);
        font-size: 1.18rem;
        font-weight: 900;
    }

    .doc-stat-box span {
        display: block;
        margin-top: 6px;
        color: var(--text-secondary);
        font-size: 0.92rem;
        line-height: 1.6;
    }

    .doc-form-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        margin-top: 24px;
        padding-top: 18px;
        border-top: 1px solid rgba(170, 134, 63, 0.12);
    }

    .doc-form-footer-note {
        color: var(--text-secondary);
        font-size: 0.96rem;
        line-height: 1.8;
    }

    .doc-form-footer-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    html[data-theme="dark"] .doc-form-hero {
        background:
            radial-gradient(circle at top right, rgba(141, 110, 43, 0.28), transparent 32%),
            linear-gradient(135deg, rgba(17, 24, 39, 0.98) 0%, rgba(15, 23, 42, 0.98) 55%, rgba(30, 41, 59, 0.98) 100%);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 24px 52px rgba(2, 6, 23, 0.34);
    }

    html[data-theme="dark"] .doc-form-btn-muted,
    html[data-theme="dark"] .doc-panel,
    html[data-theme="dark"] .doc-mini-card,
    html[data-theme="dark"] .doc-file-item,
    html[data-theme="dark"] .doc-current-file {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 18px 38px rgba(2, 6, 23, 0.28);
    }

    html[data-theme="dark"] .doc-section,
    html[data-theme="dark"] .doc-stat-box,
    html[data-theme="dark"] .doc-upload-zone {
        background: rgba(15, 23, 42, 0.76);
        border-color: rgba(148, 163, 184, 0.12);
    }

    html[data-theme="dark"] .doc-form-badge {
        background: rgba(141, 110, 43, 0.16);
        color: #f6deb0;
    }

    html[data-theme="dark"] .doc-input {
        background: rgba(15, 23, 42, 0.95);
        border-color: rgba(148, 163, 184, 0.20);
        color: var(--text-primary);
    }

    html[data-theme="dark"] .doc-input:focus {
        background: rgba(15, 23, 42, 1);
    }

    html[data-theme="dark"] .doc-current-file-actions a:hover,
    html[data-theme="dark"] .doc-form-btn-muted:hover {
        color: #f6deb0 !important;
        border-color: rgba(141, 110, 43, 0.26);
    }

    @media (max-width: 1199px) {
        .doc-form-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .doc-form-page {
            padding-top: 0;
        }

        .doc-panel,
        .doc-mini-card,
        .doc-form-hero {
            border-radius: 24px;
            padding: 20px;
        }

        .doc-form-footer {
            align-items: stretch;
        }

        .doc-form-footer-actions {
            width: 100%;
        }

        .doc-form-footer-actions .doc-form-btn,
        .doc-form-footer-actions .doc-form-btn-muted,
        .doc-form-footer-actions .doc-form-btn-danger {
            flex: 1 1 100%;
            justify-content: center;
        }

        .doc-stat-grid {
            grid-template-columns: 1fr;
        }

        .doc-file-item,
        .doc-current-file {
            flex-direction: column;
            align-items: stretch;
        }
    }

    @keyframes docFadeUp {
        from {
            opacity: 0;
            transform: translateY(18px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush
