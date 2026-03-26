@push('styles')
<style>
    .reg-form-page {
        padding: 10px 0 30px;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .reg-form-page button,
    .reg-form-page input,
    .reg-form-page select,
    .reg-form-page textarea {
        font: inherit;
    }

    .reg-form-shell {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .reg-form-hero {
        position: relative;
        overflow: hidden;
        border-radius: 30px;
        padding: clamp(24px, 3vw, 34px);
        background:
            radial-gradient(circle at top right, rgba(196, 168, 90, 0.28), transparent 30%),
            linear-gradient(135deg, #fff8ec 0%, #ffffff 46%, #f5ecdc 100%);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
        animation: regFadeUp 0.72s ease both;
    }

    .reg-form-hero::before,
    .reg-form-hero::after {
        content: "";
        position: absolute;
        border-radius: 999px;
        pointer-events: none;
    }

    .reg-form-hero::before {
        width: 220px;
        height: 220px;
        top: -120px;
        inset-inline-end: -70px;
        background: rgba(170, 134, 63, 0.10);
    }

    .reg-form-hero::after {
        width: 180px;
        height: 180px;
        bottom: -100px;
        inset-inline-start: -50px;
        background: rgba(196, 168, 90, 0.13);
    }

    .reg-form-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 22px;
        flex-wrap: wrap;
    }

    .reg-form-badge {
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

    .reg-form-title {
        margin: 0;
        color: var(--text-primary);
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 900;
        line-height: 1.1;
    }

    .reg-form-subtitle {
        margin: 12px 0 0;
        max-width: 760px;
        color: var(--text-secondary);
        font-size: clamp(1rem, 1.5vw, 1.18rem);
        line-height: 1.9;
    }

    .reg-form-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .reg-form-btn,
    .reg-form-btn-muted,
    .reg-form-btn-danger {
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

    .reg-form-btn {
        background: linear-gradient(135deg, var(--primary-color), #c49b48);
        color: #fff !important;
        box-shadow: 0 18px 30px rgba(170, 134, 63, 0.24);
    }

    .reg-form-btn-muted {
        background: rgba(255, 255, 255, 0.9);
        color: var(--text-primary) !important;
        border-color: rgba(170, 134, 63, 0.14);
    }

    .reg-form-btn-danger {
        background: rgba(220, 38, 38, 0.08);
        color: var(--danger-color) !important;
        border-color: rgba(220, 38, 38, 0.16);
    }

    .reg-form-btn:hover,
    .reg-form-btn-muted:hover,
    .reg-form-btn-danger:hover {
        transform: translateY(-2px);
    }

    .reg-form-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.85fr) minmax(280px, 0.95fr);
        gap: 22px;
        align-items: start;
    }

    .reg-panel {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        padding: 24px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.12);
        box-shadow: 0 18px 38px rgba(15, 23, 42, 0.07);
        animation: regFadeUp 0.8s ease both;
    }

    .reg-panel::after {
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

    .reg-panel > * {
        position: relative;
        z-index: 1;
    }

    .reg-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }

    .reg-panel-title-wrap {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .reg-panel-icon {
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

    .reg-panel-title {
        margin: 0;
        color: var(--text-primary);
        font-size: clamp(1.35rem, 2vw, 1.65rem);
        font-weight: 900;
    }

    .reg-panel-subtitle {
        margin: 5px 0 0;
        color: var(--text-secondary);
        font-size: 0.98rem;
        line-height: 1.8;
    }

    .reg-section {
        padding: 18px;
        border-radius: 24px;
        background: linear-gradient(180deg, rgba(248, 250, 252, 0.85), rgba(255, 255, 255, 0.98));
        border: 1px solid rgba(170, 134, 63, 0.10);
        margin-bottom: 18px;
    }

    .reg-section:last-child {
        margin-bottom: 0;
    }

    .reg-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 16px;
        color: var(--text-primary);
        font-size: 1.12rem;
        font-weight: 900;
    }

    .reg-field {
        margin-bottom: 18px;
    }

    .reg-field:last-child {
        margin-bottom: 0;
    }

    .reg-field label {
        margin-bottom: 8px;
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 800;
    }

    .reg-field .help-block {
        margin-bottom: 0;
        font-size: 0.92rem;
        font-weight: 700;
    }

    .reg-input {
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

    .reg-input:focus {
        outline: none;
        border-color: rgba(170, 134, 63, 0.48);
        box-shadow: 0 0 0 4px rgba(170, 134, 63, 0.12);
        background: #fff;
    }

    .reg-field.has-error .reg-input {
        border-color: rgba(220, 38, 38, 0.5);
        box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.10);
    }

    .reg-inline-note {
        margin-top: 8px;
        color: var(--text-secondary);
        font-size: 0.92rem;
        line-height: 1.8;
    }

    .reg-upload-zone {
        position: relative;
        border-radius: 24px;
        border: 1px dashed rgba(170, 134, 63, 0.24);
        background:
            radial-gradient(circle at top, rgba(196, 168, 90, 0.10), transparent 45%),
            rgba(255, 248, 236, 0.8);
        padding: 22px;
    }

    .reg-upload-zone .reg-input[type="file"] {
        padding: 10px 12px;
    }

    .reg-files-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 14px;
    }

    .reg-file-item,
    .reg-current-file {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 14px;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.94);
        border: 1px solid rgba(170, 134, 63, 0.10);
        animation: regFadeUp 0.35s ease both;
    }

    .reg-file-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .reg-file-icon {
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

    .reg-file-meta strong {
        display: block;
        color: var(--text-primary);
        font-size: 0.98rem;
        font-weight: 800;
        word-break: break-word;
    }

    .reg-file-meta small,
    .reg-current-file-text {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.7;
    }

    .reg-current-file-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .reg-current-file-actions a {
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

    .reg-side-stack {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .reg-mini-card {
        position: relative;
        overflow: hidden;
        border-radius: 24px;
        padding: 20px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.12);
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.06);
        animation: regFadeUp 0.88s ease both;
    }

    .reg-mini-card::after {
        content: "";
        position: absolute;
        inset: auto -40px -70px auto;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(170, 134, 63, 0.08);
    }

    .reg-mini-card > * {
        position: relative;
        z-index: 1;
    }

    .reg-mini-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 14px;
        color: var(--text-primary);
        font-size: 1.14rem;
        font-weight: 900;
    }

    .reg-tip-list,
    .reg-meta-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .reg-tip-item,
    .reg-meta-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        color: var(--text-secondary);
        font-size: 0.98rem;
        line-height: 1.8;
    }

    .reg-tip-item i,
    .reg-meta-item i {
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

    .reg-stat-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .reg-stat-box {
        padding: 14px;
        border-radius: 18px;
        background: rgba(248, 250, 252, 0.94);
        border: 1px solid rgba(170, 134, 63, 0.10);
    }

    .reg-stat-box strong {
        display: block;
        color: var(--text-primary);
        font-size: 1.18rem;
        font-weight: 900;
    }

    .reg-stat-box span {
        display: block;
        margin-top: 6px;
        color: var(--text-secondary);
        font-size: 0.92rem;
        line-height: 1.6;
    }

    .reg-form-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        margin-top: 24px;
        padding-top: 18px;
        border-top: 1px solid rgba(170, 134, 63, 0.12);
    }

    .reg-form-footer-note {
        color: var(--text-secondary);
        font-size: 0.96rem;
        line-height: 1.8;
    }

    .reg-form-footer-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    html[data-theme="dark"] .reg-form-hero {
        background:
            radial-gradient(circle at top right, rgba(141, 110, 43, 0.28), transparent 32%),
            linear-gradient(135deg, rgba(17, 24, 39, 0.98) 0%, rgba(15, 23, 42, 0.98) 55%, rgba(30, 41, 59, 0.98) 100%);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 24px 52px rgba(2, 6, 23, 0.34);
    }

    html[data-theme="dark"] .reg-form-btn-muted,
    html[data-theme="dark"] .reg-panel,
    html[data-theme="dark"] .reg-mini-card,
    html[data-theme="dark"] .reg-file-item,
    html[data-theme="dark"] .reg-current-file {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 18px 38px rgba(2, 6, 23, 0.28);
    }

    html[data-theme="dark"] .reg-section,
    html[data-theme="dark"] .reg-stat-box,
    html[data-theme="dark"] .reg-upload-zone {
        background: rgba(15, 23, 42, 0.76);
        border-color: rgba(148, 163, 184, 0.12);
    }

    html[data-theme="dark"] .reg-form-badge {
        background: rgba(141, 110, 43, 0.16);
        color: #f6deb0;
    }

    html[data-theme="dark"] .reg-input {
        background: rgba(15, 23, 42, 0.95);
        border-color: rgba(148, 163, 184, 0.20);
        color: var(--text-primary);
    }

    html[data-theme="dark"] .reg-input:focus {
        background: rgba(15, 23, 42, 1);
    }

    html[data-theme="dark"] .reg-current-file-actions a:hover,
    html[data-theme="dark"] .reg-form-btn-muted:hover {
        color: #f6deb0 !important;
        border-color: rgba(141, 110, 43, 0.26);
    }

    @media (max-width: 1199px) {
        .reg-form-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .reg-form-page {
            padding-top: 0;
        }

        .reg-panel,
        .reg-mini-card,
        .reg-form-hero {
            border-radius: 24px;
            padding: 20px;
        }

        .reg-form-footer {
            align-items: stretch;
        }

        .reg-form-footer-actions {
            width: 100%;
        }

        .reg-form-footer-actions .reg-form-btn,
        .reg-form-footer-actions .reg-form-btn-muted,
        .reg-form-footer-actions .reg-form-btn-danger {
            flex: 1 1 100%;
            justify-content: center;
        }

        .reg-stat-grid {
            grid-template-columns: 1fr;
        }

        .reg-file-item,
        .reg-current-file {
            flex-direction: column;
            align-items: stretch;
        }
    }

    @keyframes regFadeUp {
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
