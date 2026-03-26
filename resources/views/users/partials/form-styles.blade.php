@push('styles')
<style>
    .userf-page {
        padding: 10px 0 30px;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .userf-page button,
    .userf-page input,
    .userf-page select,
    .userf-page textarea {
        font: inherit;
    }

    .userf-shell {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .userf-hero {
        position: relative;
        overflow: hidden;
        border-radius: 30px;
        padding: clamp(24px, 3vw, 34px);
        background:
            radial-gradient(circle at top right, rgba(196, 168, 90, 0.28), transparent 30%),
            linear-gradient(135deg, #fff8ec 0%, #ffffff 46%, #f5ecdc 100%);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
        animation: userfFadeUp 0.72s ease both;
    }

    .userf-hero::before,
    .userf-hero::after {
        content: "";
        position: absolute;
        border-radius: 999px;
        pointer-events: none;
    }

    .userf-hero::before {
        width: 220px;
        height: 220px;
        top: -120px;
        inset-inline-end: -70px;
        background: rgba(170, 134, 63, 0.10);
    }

    .userf-hero::after {
        width: 180px;
        height: 180px;
        bottom: -100px;
        inset-inline-start: -50px;
        background: rgba(196, 168, 90, 0.13);
    }

    .userf-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 22px;
        flex-wrap: wrap;
    }

    .userf-badge {
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

    .userf-title {
        margin: 0;
        color: var(--text-primary);
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 900;
        line-height: 1.1;
    }

    .userf-subtitle {
        margin: 12px 0 0;
        max-width: 760px;
        color: var(--text-secondary);
        font-size: clamp(1rem, 1.5vw, 1.18rem);
        line-height: 1.9;
    }

    .userf-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .userf-btn,
    .userf-btn-muted {
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

    .userf-btn {
        background: linear-gradient(135deg, var(--primary-color), #c49b48);
        color: #fff !important;
        box-shadow: 0 18px 30px rgba(170, 134, 63, 0.24);
    }

    .userf-btn-muted {
        background: rgba(255, 255, 255, 0.9);
        color: var(--text-primary) !important;
        border-color: rgba(170, 134, 63, 0.14);
    }

    .userf-btn:hover,
    .userf-btn-muted:hover {
        transform: translateY(-2px);
    }

    .userf-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.85fr) minmax(300px, 0.95fr);
        gap: 22px;
        align-items: start;
    }

    .userf-panel,
    .userf-mini-card {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.12);
        box-shadow: 0 18px 38px rgba(15, 23, 42, 0.07);
        animation: userfFadeUp 0.8s ease both;
    }

    .userf-panel {
        padding: 24px;
    }

    .userf-panel::after,
    .userf-mini-card::after {
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

    .userf-panel > *,
    .userf-mini-card > * {
        position: relative;
        z-index: 1;
    }

    .userf-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }

    .userf-panel-title-wrap {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .userf-panel-icon {
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

    .userf-panel-title {
        margin: 0;
        color: var(--text-primary);
        font-size: clamp(1.35rem, 2vw, 1.65rem);
        font-weight: 900;
    }

    .userf-panel-subtitle {
        margin: 5px 0 0;
        color: var(--text-secondary);
        font-size: 0.98rem;
        line-height: 1.8;
    }

    .userf-section {
        padding: 18px;
        border-radius: 24px;
        background: linear-gradient(180deg, rgba(248, 250, 252, 0.85), rgba(255, 255, 255, 0.98));
        border: 1px solid rgba(170, 134, 63, 0.10);
        margin-bottom: 18px;
    }

    .userf-section:last-child {
        margin-bottom: 0;
    }

    .userf-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 16px;
        color: var(--text-primary);
        font-size: 1.12rem;
        font-weight: 900;
    }

    .userf-field {
        margin-bottom: 18px;
    }

    .userf-field:last-child {
        margin-bottom: 0;
    }

    .userf-field label {
        margin-bottom: 8px;
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 800;
    }

    .userf-input {
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

    .userf-input:focus {
        outline: none;
        border-color: rgba(170, 134, 63, 0.48);
        box-shadow: 0 0 0 4px rgba(170, 134, 63, 0.12);
        background: #fff;
    }

    .userf-field.has-error .userf-input {
        border-color: rgba(220, 38, 38, 0.5);
        box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.10);
    }

    .userf-inline-note {
        margin-top: 8px;
        color: var(--text-secondary);
        font-size: 0.92rem;
        line-height: 1.8;
    }

    .userf-help-block {
        margin-top: 8px;
        margin-bottom: 0;
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--danger-color);
    }

    .userf-permissions-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }

    .userf-permissions-search {
        position: relative;
        flex: 1 1 260px;
    }

    .userf-permissions-search i {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        inset-inline-start: 16px;
        color: var(--text-light);
    }

    .userf-permissions-search input {
        padding-inline-start: 44px;
    }

    .userf-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 42px;
        padding: 10px 14px;
        border-radius: 14px;
        background: rgba(170, 134, 63, 0.10);
        color: var(--primary-color);
        font-size: 0.92rem;
        font-weight: 800;
    }

    .userf-permissions-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .userf-permission-card {
        border-radius: 20px;
        padding: 16px;
        background: rgba(255, 255, 255, 0.94);
        border: 1px solid rgba(170, 134, 63, 0.10);
    }

    .userf-permission-card.is-hidden {
        display: none;
    }

    .userf-permission-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 12px;
    }

    .userf-permission-title {
        margin: 0;
        font-size: 1rem;
        font-weight: 900;
        color: var(--text-primary);
    }

    .userf-permission-count {
        color: var(--text-secondary);
        font-size: 0.88rem;
        font-weight: 700;
    }

    .userf-permission-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-height: 280px;
        overflow: auto;
        padding-inline-end: 4px;
    }

    .userf-permission-option {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px;
        border-radius: 16px;
        background: rgba(248, 250, 252, 0.94);
        border: 1px solid rgba(170, 134, 63, 0.08);
        cursor: pointer;
    }

    .userf-permission-option input {
        margin-top: 3px;
    }

    .userf-permission-name {
        color: var(--text-primary);
        font-size: 0.96rem;
        font-weight: 800;
    }

    .userf-permission-meta {
        color: var(--text-secondary);
        font-size: 0.88rem;
        line-height: 1.7;
    }

    .userf-inherited-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .userf-inherited-empty {
        padding: 14px 16px;
        border-radius: 16px;
        background: rgba(248, 250, 252, 0.94);
        color: var(--text-secondary);
        border: 1px dashed rgba(170, 134, 63, 0.16);
    }

    .userf-side-stack {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .userf-mini-card {
        padding: 20px;
    }

    .userf-mini-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 14px;
        color: var(--text-primary);
        font-size: 1.14rem;
        font-weight: 900;
    }

    .userf-meta-list,
    .userf-tip-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .userf-meta-item,
    .userf-tip-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        color: var(--text-secondary);
        font-size: 0.98rem;
        line-height: 1.8;
    }

    .userf-meta-item i,
    .userf-tip-item i {
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

    .userf-stat-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .userf-stat-box {
        padding: 14px;
        border-radius: 18px;
        background: rgba(248, 250, 252, 0.94);
        border: 1px solid rgba(170, 134, 63, 0.10);
    }

    .userf-stat-box strong {
        display: block;
        color: var(--text-primary);
        font-size: 1.18rem;
        font-weight: 900;
    }

    .userf-stat-box span {
        display: block;
        margin-top: 6px;
        color: var(--text-secondary);
        font-size: 0.92rem;
        line-height: 1.6;
    }

    .userf-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        margin-top: 24px;
        padding-top: 18px;
        border-top: 1px solid rgba(170, 134, 63, 0.12);
    }

    .userf-footer-note {
        color: var(--text-secondary);
        font-size: 0.96rem;
        line-height: 1.8;
    }

    .userf-footer-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    html[data-theme="dark"] .userf-hero {
        background:
            radial-gradient(circle at top right, rgba(141, 110, 43, 0.28), transparent 32%),
            linear-gradient(135deg, rgba(17, 24, 39, 0.98) 0%, rgba(15, 23, 42, 0.98) 55%, rgba(30, 41, 59, 0.98) 100%);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 24px 52px rgba(2, 6, 23, 0.34);
    }

    html[data-theme="dark"] .userf-btn-muted,
    html[data-theme="dark"] .userf-panel,
    html[data-theme="dark"] .userf-mini-card,
    html[data-theme="dark"] .userf-permission-card,
    html[data-theme="dark"] .userf-permission-option {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 18px 38px rgba(2, 6, 23, 0.28);
    }

    html[data-theme="dark"] .userf-section,
    html[data-theme="dark"] .userf-stat-box,
    html[data-theme="dark"] .userf-chip,
    html[data-theme="dark"] .userf-inherited-empty {
        background: rgba(15, 23, 42, 0.76);
        border-color: rgba(148, 163, 184, 0.12);
    }

    html[data-theme="dark"] .userf-badge {
        background: rgba(141, 110, 43, 0.16);
        color: #f6deb0;
    }

    html[data-theme="dark"] .userf-input {
        background: rgba(15, 23, 42, 0.95);
        border-color: rgba(148, 163, 184, 0.20);
        color: var(--text-primary);
    }

    html[data-theme="dark"] .userf-input:focus {
        background: rgba(15, 23, 42, 1);
    }

    html[data-theme="dark"] .userf-btn-muted:hover {
        color: #f6deb0 !important;
        border-color: rgba(141, 110, 43, 0.26);
    }

    @media (max-width: 1199px) {
        .userf-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 991px) {
        .userf-permissions-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .userf-page {
            padding-top: 0;
        }

        .userf-panel,
        .userf-mini-card,
        .userf-hero {
            border-radius: 24px;
            padding: 20px;
        }

        .userf-footer {
            align-items: stretch;
        }

        .userf-footer-actions {
            width: 100%;
        }

        .userf-footer-actions .userf-btn,
        .userf-footer-actions .userf-btn-muted {
            flex: 1 1 100%;
            justify-content: center;
        }

        .userf-stat-grid {
            grid-template-columns: 1fr;
        }
    }

    @keyframes userfFadeUp {
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
