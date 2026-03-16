@push('styles')
<style>
    .contributor-form-page {
        padding: 10px 0 30px;
    }

    .contributor-form-shell {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .contributor-form-hero {
        position: relative;
        overflow: hidden;
        border-radius: 30px;
        padding: clamp(24px, 3vw, 34px);
        background:
            radial-gradient(circle at top right, rgba(196, 168, 90, 0.28), transparent 30%),
            linear-gradient(135deg, #fff8ec 0%, #ffffff 46%, #f5ecdc 100%);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
        animation: contributorFormFadeUp 0.72s ease both;
    }

    .contributor-form-hero::before,
    .contributor-form-hero::after {
        content: "";
        position: absolute;
        border-radius: 999px;
        pointer-events: none;
    }

    .contributor-form-hero::before {
        width: 220px;
        height: 220px;
        top: -120px;
        inset-inline-end: -70px;
        background: rgba(170, 134, 63, 0.10);
    }

    .contributor-form-hero::after {
        width: 180px;
        height: 180px;
        bottom: -100px;
        inset-inline-start: -50px;
        background: rgba(196, 168, 90, 0.13);
    }

    .contributor-form-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 22px;
        flex-wrap: wrap;
    }

    .contributor-form-badge {
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

    .contributor-form-title {
        margin: 0;
        color: var(--text-primary);
        font-size: clamp(2rem, 4vw, 3.15rem);
        font-weight: 900;
        line-height: 1.1;
    }

    .contributor-form-subtitle {
        margin: 12px 0 0;
        max-width: 760px;
        color: var(--text-secondary);
        font-size: clamp(1rem, 1.5vw, 1.24rem);
        line-height: 1.9;
    }

    .contributor-form-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .contributor-form-btn,
    .contributor-form-btn-muted {
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

    .contributor-form-btn {
        background: linear-gradient(135deg, var(--primary-color), #c49b48);
        color: #fff !important;
        box-shadow: 0 18px 30px rgba(170, 134, 63, 0.24);
    }

    .contributor-form-btn-muted {
        background: rgba(255, 255, 255, 0.9);
        color: var(--text-primary) !important;
        border-color: rgba(170, 134, 63, 0.14);
    }

    .contributor-form-btn:hover,
    .contributor-form-btn-muted:hover {
        transform: translateY(-2px);
    }

    .contributor-form-btn:hover {
        box-shadow: 0 22px 34px rgba(170, 134, 63, 0.28);
    }

    .contributor-form-btn-muted:hover {
        border-color: rgba(170, 134, 63, 0.24);
        color: var(--primary-color) !important;
    }

    .contributor-form-grid {
        display: grid;
        grid-template-columns: minmax(1, 1.9fr) minmax(280px, 0.95fr);
        gap: 22px;
        align-items: start;
    }

    .contributor-panel {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        padding: 24px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.12);
        box-shadow: 0 18px 38px rgba(15, 23, 42, 0.07);
        animation: contributorFormFadeUp 0.8s ease both;
    }

    .contributor-panel::after {
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

    .contributor-panel > * {
        position: relative;
        z-index: 1;
    }

    .contributor-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }

    .contributor-panel-title-wrap {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .contributor-panel-icon {
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

    .contributor-panel-title {
        margin: 0;
        color: var(--text-primary);
        font-size: clamp(1.35rem, 2vw, 1.65rem);
        font-weight: 900;
    }

    .contributor-panel-subtitle {
        margin: 5px 0 0;
        color: var(--text-secondary);
        font-size: 0.98rem;
        line-height: 1.8;
    }

    .contributor-section {
        padding: 18px;
        border-radius: 24px;
        background: linear-gradient(180deg, rgba(248, 250, 252, 0.85), rgba(255, 255, 255, 0.98));
        border: 1px solid rgba(170, 134, 63, 0.10);
        margin-bottom: 18px;
    }

    .contributor-section:last-child {
        margin-bottom: 0;
    }

    .contributor-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 16px;
        color: var(--text-primary);
        font-size: 1.12rem;
        font-weight: 900;
    }

    .contributor-field {
        margin-bottom: 18px;
    }

    .contributor-field label {
        margin-bottom: 8px;
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 800;
    }

    .contributor-field .help-block {
        margin-bottom: 0;
        font-size: 0.92rem;
        font-weight: 700;
    }

    .contributor-input,
    .contributor-textarea {
        width: 100%;
        border-radius: 16px;
        min-height: 50px;
        padding: 12px 16px;
        border: 1px solid rgba(148, 163, 184, 0.24);
        background: rgba(255, 255, 255, 0.96);
        color: var(--text-primary);
        box-shadow: none;
        transition: border-color 0.25s ease, box-shadow 0.25s ease, transform 0.25s ease, background-color 0.25s ease;
    }

    .contributor-textarea {
        min-height: 130px;
        resize: vertical;
    }

    select.contributor-input[multiple] {
        min-height: 150px;
        padding: 10px 12px;
    }

    .contributor-input:focus,
    .contributor-textarea:focus {
        outline: none;
        border-color: rgba(170, 134, 63, 0.48);
        box-shadow: 0 0 0 4px rgba(170, 134, 63, 0.12);
        background: #fff;
    }

    .contributor-field.has-error .contributor-input,
    .contributor-field.has-error .contributor-textarea {
        border-color: rgba(220, 38, 38, 0.5);
        box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.10);
    }

    .contributor-inline-note {
        margin-top: 8px;
        color: var(--text-secondary);
        font-size: 0.92rem;
        line-height: 1.8;
    }

    .contributor-toggle {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 16px;
        border-radius: 18px;
        background: rgba(170, 134, 63, 0.06);
        border: 1px solid rgba(170, 134, 63, 0.12);
    }

    .contributor-toggle input {
        width: 18px;
        height: 18px;
        margin: 0;
    }

    .contributor-toggle strong {
        display: block;
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 900;
    }

    .contributor-toggle span {
        display: block;
        margin-top: 4px;
        color: var(--text-secondary);
        font-size: 0.92rem;
    }

    .contributor-upload-grid {
        display: grid;
        grid-template-columns: minmax(0, 0.9fr) minmax(0, 1.1fr);
        gap: 16px;
    }

    .contributor-upload-preview {
        min-height: 220px;
        border-radius: 24px;
        border: 1px dashed rgba(170, 134, 63, 0.24);
        background:
            radial-gradient(circle at top, rgba(196, 168, 90, 0.10), transparent 45%),
            rgba(255, 248, 236, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        text-align: center;
        overflow: hidden;
    }

    .contributor-upload-preview img {
        width: 100%;
        max-height: 230px;
        object-fit: cover;
        border-radius: 20px;
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.15);
    }

    .contributor-avatar-fallback {
        width: 118px;
        height: 118px;
        border-radius: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary-color), #d3b06a);
        color: #fff;
        font-size: 2.6rem;
        font-weight: 900;
        box-shadow: 0 18px 32px rgba(170, 134, 63, 0.22);
    }

    .contributor-upload-card {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .contributor-files-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 8px;
    }

    .contributor-file-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 12px 14px;
        border-radius: 16px;
        background: rgba(248, 250, 252, 0.92);
        border: 1px solid rgba(170, 134, 63, 0.10);
        color: var(--text-primary);
        animation: contributorFileSlide 0.35s ease both;
    }

    .contributor-file-item strong {
        display: block;
        font-weight: 800;
    }

    .contributor-file-item small {
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .contributor-file-icon {
        width: 40px;
        height: 40px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(170, 134, 63, 0.12);
        color: var(--primary-color);
        font-size: 1.15rem;
        flex-shrink: 0;
    }

    .contributor-side-stack {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .contributor-mini-card {
        position: relative;
        overflow: hidden;
        border-radius: 24px;
        padding: 20px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.12);
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.06);
        animation: contributorFormFadeUp 0.88s ease both;
    }

    .contributor-mini-card::after {
        content: "";
        position: absolute;
        inset: auto -40px -70px auto;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(170, 134, 63, 0.08);
    }

    .contributor-mini-card > * {
        position: relative;
        z-index: 1;
    }

    .contributor-mini-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 14px;
        color: var(--text-primary);
        font-size: 1.14rem;
        font-weight: 900;
    }

    .contributor-tip-list,
    .contributor-meta-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .contributor-tip-item,
    .contributor-meta-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        color: var(--text-secondary);
        font-size: 0.98rem;
        line-height: 1.8;
    }

    .contributor-tip-item i,
    .contributor-meta-item i {
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

    .contributor-stat-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .contributor-stat-box {
        padding: 14px;
        border-radius: 18px;
        background: rgba(248, 250, 252, 0.94);
        border: 1px solid rgba(170, 134, 63, 0.10);
    }

    .contributor-stat-box strong {
        display: block;
        color: var(--text-primary);
        font-size: 1.18rem;
        font-weight: 900;
    }

    .contributor-stat-box span {
        display: block;
        margin-top: 6px;
        color: var(--text-secondary);
        font-size: 0.92rem;
        line-height: 1.6;
    }

    .contributor-form-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        margin-top: 24px;
        padding-top: 18px;
        border-top: 1px solid rgba(170, 134, 63, 0.12);
    }

    .contributor-form-footer-note {
        color: var(--text-secondary);
        font-size: 0.96rem;
        line-height: 1.8;
    }

    .contributor-form-footer-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    html[data-theme="dark"] .contributor-form-hero {
        background:
            radial-gradient(circle at top right, rgba(141, 110, 43, 0.28), transparent 32%),
            linear-gradient(135deg, rgba(17, 24, 39, 0.98) 0%, rgba(15, 23, 42, 0.98) 55%, rgba(30, 41, 59, 0.98) 100%);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 24px 52px rgba(2, 6, 23, 0.34);
    }

    html[data-theme="dark"] .contributor-form-btn-muted,
    html[data-theme="dark"] .contributor-panel,
    html[data-theme="dark"] .contributor-mini-card {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 18px 38px rgba(2, 6, 23, 0.28);
    }

    html[data-theme="dark"] .contributor-section,
    html[data-theme="dark"] .contributor-file-item,
    html[data-theme="dark"] .contributor-stat-box {
        background: rgba(15, 23, 42, 0.76);
        border-color: rgba(148, 163, 184, 0.12);
    }

    html[data-theme="dark"] .contributor-input,
    html[data-theme="dark"] .contributor-textarea {
        background: rgba(15, 23, 42, 0.95);
        border-color: rgba(148, 163, 184, 0.20);
        color: var(--text-primary);
    }

    html[data-theme="dark"] .contributor-input:focus,
    html[data-theme="dark"] .contributor-textarea:focus {
        background: rgba(15, 23, 42, 1);
    }

    html[data-theme="dark"] .contributor-toggle,
    html[data-theme="dark"] .contributor-upload-preview {
        background: rgba(15, 23, 42, 0.82);
        border-color: rgba(148, 163, 184, 0.16);
    }

    @media (max-width: 1199px) {
        .contributor-form-grid,
        .contributor-upload-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .contributor-form-page {
            padding-top: 0;
        }

        .contributor-panel,
        .contributor-mini-card,
        .contributor-form-hero {
            border-radius: 24px;
            padding: 20px;
        }

        .contributor-form-footer {
            align-items: stretch;
        }

        .contributor-form-footer-actions {
            width: 100%;
        }

        .contributor-form-footer-actions .contributor-form-btn,
        .contributor-form-footer-actions .contributor-form-btn-muted {
            flex: 1 1 100%;
            justify-content: center;
        }

        .contributor-stat-grid {
            grid-template-columns: 1fr;
        }
    }

    @keyframes contributorFormFadeUp {
        from {
            opacity: 0;
            transform: translateY(18px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes contributorFileSlide {
        from {
            opacity: 0;
            transform: translateX(12px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
</style>
@endpush
