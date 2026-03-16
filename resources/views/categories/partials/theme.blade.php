<style>
    .membership-page {
        --membership-ink: #16323f;
        --membership-ink-soft: #335867;
        --membership-gold: #c88a34;
        --membership-gold-strong: #a96816;
        --membership-sand: #f6efe1;
        --membership-cream: #fffaf1;
        --membership-card: rgba(255, 252, 246, 0.94);
        --membership-line: rgba(22, 50, 63, 0.11);
        --membership-line-strong: rgba(22, 50, 63, 0.18);
        --membership-mint: #d7eee3;
        --membership-danger: #b94f46;
        --membership-shadow: 0 24px 60px rgba(22, 50, 63, 0.11);
        --membership-text-xs: 0.88rem;
        --membership-text-sm: 0.96rem;
        --membership-text-md: 1.05rem;
        --membership-text-lg: 1.26rem;
        --membership-text-xl: 1.55rem;
        --membership-text-2xl: clamp(2rem, 3vw, 3rem);
        --membership-surface-soft: rgba(255, 255, 255, 0.88);
        --membership-surface-muted: rgba(22, 50, 63, 0.04);
        --membership-surface-chip: rgba(22, 50, 63, 0.07);
        position: relative;
        font-size: 1rem;
        line-height: 1.7;
        font-family: "Cairo", "Segoe UI", sans-serif;
        color: var(--membership-ink);
        background:
            radial-gradient(circle at top right, rgba(200, 138, 52, 0.09), transparent 24%),
            radial-gradient(circle at bottom left, rgba(22, 50, 63, 0.06), transparent 28%);
    }

    .membership-page::before,
    .membership-page::after {
        content: "";
        position: fixed;
        border-radius: 999px;
        pointer-events: none;
        z-index: 0;
        filter: blur(16px);
        opacity: 0.5;
    }

    .membership-page::before {
        width: 320px;
        height: 320px;
        top: 110px;
        right: -80px;
        background: radial-gradient(circle, rgba(200, 138, 52, 0.22) 0%, rgba(200, 138, 52, 0) 72%);
        animation: membershipFloat 12s ease-in-out infinite;
    }

    .membership-page::after {
        width: 250px;
        height: 250px;
        bottom: 32px;
        left: -65px;
        background: radial-gradient(circle, rgba(22, 50, 63, 0.16) 0%, rgba(22, 50, 63, 0) 72%);
        animation: membershipFloat 13s ease-in-out infinite reverse;
    }

    .membership-shell {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        gap: 22px;
    }

    .membership-hero,
    .membership-panel,
    .membership-card,
    .membership-section,
    .membership-list-item,
    .membership-stat {
        animation: membershipReveal 0.75s ease both;
    }

    .membership-hero {
        position: relative;
        overflow: hidden;
        padding: 34px 36px;
        border-radius: 30px;
        background:
            linear-gradient(135deg, rgba(16, 39, 50, 0.97), rgba(29, 80, 98, 0.94)),
            linear-gradient(120deg, rgba(200, 138, 52, 0.24), rgba(255, 255, 255, 0));
        border: 1px solid rgba(255, 255, 255, 0.14);
        box-shadow: 0 30px 65px rgba(16, 39, 50, 0.24);
        color: #fff;
    }


    .membership-kicker {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 14px;
        padding: 9px 15px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.11);
        border: 1px solid rgba(255, 255, 255, 0.1);
        font-size: var(--membership-text-xs);
        font-weight: 700;
        letter-spacing: 0.06em;
    }

    .membership-title {
        margin: 0;
        font-size: var(--membership-text-2xl);
        font-weight: 900;
        line-height: 1.2;
        letter-spacing: -0.02em;
    }

    .membership-subtitle {
        max-width: 840px;
        margin: 12px 0 0;
        color: rgba(255, 255, 255, 0.84);
        font-size: var(--membership-text-md);
        line-height: 1.95;
    }

    .membership-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 24px;
    }

    .membership-btn,
    .membership-btn-secondary,
    .membership-btn-muted,
    .membership-icon-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        border: 0;
        border-radius: 18px;
        text-decoration: none !important;
        transition: transform 0.22s ease, box-shadow 0.22s ease, opacity 0.22s ease, border-color 0.22s ease;
        font-weight: 800;
    }

    .membership-btn {
        padding: 13px 20px;
        background: linear-gradient(135deg, #f0c56f, #d88f29);
        color: #16323f !important;
        box-shadow: 0 18px 34px rgba(216, 143, 41, 0.28);
    }

    .membership-btn-secondary {
        padding: 13px 20px;
        background: rgba(255, 255, 255, 0.12);
        color: #fff !important;
        border: 1px solid rgba(255, 255, 255, 0.16);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08);
    }

    .membership-btn-muted {
        padding: 11px 16px;
        background: #fff;
        color: var(--membership-ink) !important;
        border: 1px solid var(--membership-line);
        box-shadow: 0 12px 28px rgba(22, 50, 63, 0.08);
    }

    .membership-icon-btn {
        width: 42px;
        height: 42px;
        background: rgba(22, 50, 63, 0.08);
        color: var(--membership-ink) !important;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.5);
    }

    .membership-icon-btn.danger {
        background: rgba(185, 79, 70, 0.1);
        color: var(--membership-danger) !important;
    }

    .membership-btn:hover,
    .membership-btn-secondary:hover,
    .membership-btn-muted:hover,
    .membership-icon-btn:hover {
        transform: translateY(-2px);
    }

    .membership-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(195px, 1fr));
        gap: 14px;
    }

    .membership-stat {
        position: relative;
        overflow: hidden;
        padding: 20px 20px 18px;
        border-radius: 24px;
        background: var(--membership-surface-soft);
        border: 1px solid rgba(22, 50, 63, 0.08);
        box-shadow: 0 16px 34px rgba(22, 50, 63, 0.08);
        backdrop-filter: blur(10px);
    }

    .membership-stat::before {
        content: "";
        position: absolute;
        inset-inline-start: 0;
        top: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, rgba(22, 50, 63, 0.88), rgba(200, 138, 52, 0.94));
    }

    .membership-stat-label {
        color: rgba(22, 50, 63, 0.64);
        font-size: var(--membership-text-sm);
        font-weight: 700;
    }

    .membership-stat-value {
        margin-top: 10px;
        font-size: clamp(1.9rem, 3vw, 2.45rem);
        font-weight: 900;
        line-height: 1;
        color: var(--membership-ink);
    }

    .membership-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(290px, 1fr));
        gap: 18px;
    }

    .membership-card,
    .membership-panel,
    .membership-section {
        border: 1px solid rgba(22, 50, 63, 0.08);
        border-radius: 26px;
        background:
            linear-gradient(180deg, rgba(255, 255, 255, 0.97), rgba(250, 246, 238, 0.96)),
            var(--membership-card);
        box-shadow: var(--membership-shadow);
    }

    .membership-card {
        position: relative;
        overflow: hidden;
        padding: 24px;
    }

    .membership-company-card {
        padding: 0;
        background:
            linear-gradient(180deg, rgba(255, 255, 255, 0.99), rgba(248, 243, 233, 0.98)),
            var(--membership-card);
    }

    .membership-company-card::before {
        content: "";
        position: absolute;
        inset: 0 0 auto;
        height: 5px;
        background: linear-gradient(90deg, #c88a34, #16323f);
        z-index: 1;
    }

    .membership-card::after {
        content: "";
        position: absolute;
        inset: auto -46px -46px auto;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(200, 138, 52, 0.16) 0%, rgba(200, 138, 52, 0) 70%);
    }

    .membership-card-head,
    .membership-section-head,
    .membership-panel-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
    }

    .membership-card-title,
    .membership-section-title {
        margin: 0;
        font-size: var(--membership-text-xl);
        font-weight: 900;
        color: var(--membership-ink);
        line-height: 1.3;
    }

    .membership-card-subtitle {
        margin-top: 5px;
        color: rgba(22, 50, 63, 0.62);
        font-size: var(--membership-text-sm);
        line-height: 1.8;
    }

    .membership-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 12px;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(22, 50, 63, 0.08);
        color: var(--membership-ink);
        font-size: var(--membership-text-xs);
        font-weight: 800;
    }

    .membership-badge.company {
        background: rgba(200, 138, 52, 0.18);
        color: var(--membership-gold-strong);
    }

    .membership-badge.department {
        background: rgba(28, 113, 107, 0.13);
        color: #17655d;
    }

    .membership-meta {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin: 20px 0;
    }

    .membership-meta-item {
        padding: 15px 12px;
        border-radius: 18px;
        background: var(--membership-surface-muted);
        border: 1px solid rgba(22, 50, 63, 0.05);
        text-align: center;
    }

    .membership-meta-item strong {
        display: block;
        font-size: 1.7rem;
        font-weight: 900;
        color: var(--membership-ink);
    }

    .membership-meta-item span {
        color: rgba(22, 50, 63, 0.62);
        font-size: var(--membership-text-xs);
        font-weight: 700;
    }

    .membership-card-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .membership-company-card .membership-card-head {
        position: relative;
        padding: 24px 24px 18px;
        background:
            linear-gradient(135deg, rgba(22, 50, 63, 0.045), rgba(200, 138, 52, 0.08)),
            linear-gradient(180deg, rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0));
        border-bottom: 1px solid rgba(22, 50, 63, 0.07);
    }

    .membership-company-brand {
        display: flex;
        align-items: flex-start;
        gap: 16px;
    }

    .membership-company-mark {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 62px;
        height: 62px;
        border-radius: 20px;
        background: linear-gradient(135deg, rgba(200, 138, 52, 0.18), rgba(22, 50, 63, 0.14));
        color: var(--membership-ink);
        font-size: 2rem;
        box-shadow:
            inset 0 1px 0 rgba(255, 255, 255, 0.5),
            0 14px 26px rgba(22, 50, 63, 0.09);
    }

    .membership-company-card .membership-badge {
        margin-bottom: 10px;
    }

    .membership-company-card .membership-card-title {
        font-size: clamp(1.7rem, 2.5vw, 2.2rem);
        margin-top: 2px;
    }

    .membership-company-card .membership-card-subtitle {
        max-width: 440px;
        margin-top: 8px;
    }

    .membership-company-hero {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 18px 24px 0;
    }

    .membership-company-date {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: rgba(22, 50, 63, 0.7);
        font-size: var(--membership-text-sm);
        font-weight: 700;
    }

    .membership-company-link {
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.86);
    }

    .membership-company-meta {
        margin: 18px 24px;
    }

    .membership-company-stat {
        padding: 18px 14px;
        background: linear-gradient(180deg, rgba(22, 50, 63, 0.03), rgba(22, 50, 63, 0.06));
        border-color: rgba(22, 50, 63, 0.08);
    }

    .membership-company-stat.primary {
        background: linear-gradient(180deg, rgba(200, 138, 52, 0.18), rgba(200, 138, 52, 0.08));
        border-color: rgba(200, 138, 52, 0.16);
    }

    .membership-company-strip,
    .membership-company-empty-note {
        margin: 0 24px 24px;
    }

    .membership-company-strip {
        padding: 16px 17px 17px;
        border-radius: 20px;
        background: rgba(22, 50, 63, 0.045);
        border: 1px solid rgba(22, 50, 63, 0.07);
    }

    .membership-company-strip-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        color: var(--membership-ink);
        font-size: var(--membership-text-sm);
        font-weight: 800;
    }

    .membership-company-department-chip {
        background: rgba(255, 255, 255, 0.85);
        color: var(--membership-ink);
        border: 1px solid rgba(22, 50, 63, 0.08);
    }

    .membership-company-department-chip.more {
        background: rgba(200, 138, 52, 0.12);
        color: var(--membership-gold-strong);
        border-color: rgba(200, 138, 52, 0.14);
    }

    .membership-company-empty-note {
        margin-top: 0;
    }

    .membership-panel {
        overflow: hidden;
    }

    .membership-panel-head {
        padding: 24px 24px 0;
    }

    .membership-panel-body {
        padding: 24px;
    }

    .membership-note,
    .membership-empty,
    .membership-alert {
        padding: 16px 18px;
        border-radius: 18px;
        border: 1px solid transparent;
    }

    .membership-note {
        background: rgba(215, 238, 227, 0.78);
        border-color: rgba(28, 113, 107, 0.16);
        color: #1d6159;
        line-height: 1.9;
    }

    .membership-alert.info {
        background: rgba(22, 50, 63, 0.06);
        border-color: rgba(22, 50, 63, 0.1);
        color: var(--membership-ink);
    }

    .membership-alert.warning {
        background: rgba(200, 138, 52, 0.11);
        border-color: rgba(200, 138, 52, 0.18);
        color: #8a5917;
    }

    .membership-empty {
        background: var(--membership-surface-muted);
        border-color: rgba(22, 50, 63, 0.05);
        color: rgba(22, 50, 63, 0.72);
        text-align: center;
        line-height: 1.9;
    }

    .membership-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
        align-items: start;
    }

    .membership-form-grid .membership-note,
    .membership-form-grid .membership-alert,
    .membership-form-grid .membership-actions-bar {
        grid-column: 1 / -1;
    }

    .membership-field label {
        display: block;
        margin-bottom: 9px;
        font-weight: 800;
        color: var(--membership-ink);
    }

    .membership-input,
    .membership-select,
    .membership-readonly {
        width: 100%;
        min-height: 54px;
        padding: 14px 16px;
        border: 1px solid rgba(22, 50, 63, 0.12);
        border-radius: 17px;
        background: rgba(255, 255, 255, 0.94);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.45);
        transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        color: var(--membership-ink);
        font-size: 1rem;
    }

    .membership-select[multiple] {
        min-height: 180px;
        padding: 12px;
    }

    .membership-input:focus,
    .membership-select:focus {
        outline: none;
        border-color: rgba(200, 138, 52, 0.72);
        box-shadow: 0 0 0 4px rgba(200, 138, 52, 0.12);
        transform: translateY(-1px);
    }

    .membership-readonly {
        display: flex;
        align-items: center;
        color: rgba(22, 50, 63, 0.72);
        background: rgba(22, 50, 63, 0.05);
    }

    .membership-help {
        display: block;
        margin-top: 8px;
        color: rgba(22, 50, 63, 0.58);
        font-size: var(--membership-text-xs);
        line-height: 1.8;
    }

    .membership-error {
        display: block;
        margin-top: 8px;
        color: var(--membership-danger);
        font-size: var(--membership-text-xs);
        font-weight: 800;
    }

    .membership-actions-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 6px;
        padding-top: 18px;
        border-top: 1px solid rgba(22, 50, 63, 0.08);
    }

    .membership-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 14px;
    }

    .membership-detail {
        padding: 18px;
        border-radius: 20px;
        background: rgba(22, 50, 63, 0.04);
        border: 1px solid rgba(22, 50, 63, 0.05);
    }

    .membership-detail-label {
        color: rgba(22, 50, 63, 0.58);
        font-size: var(--membership-text-xs);
        margin-bottom: 9px;
        font-weight: 700;
    }

    .membership-detail-value {
        font-size: var(--membership-text-lg);
        font-weight: 900;
        line-height: 1.45;
        color: var(--membership-ink);
    }

    .membership-list {
        display: grid;
        gap: 12px;
    }

    .membership-list-item {
        padding: 18px;
        border: 1px solid rgba(22, 50, 63, 0.08);
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.84);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .membership-list-item:hover {
        transform: translateY(-2px);
        border-color: rgba(22, 50, 63, 0.14);
        box-shadow: 0 16px 32px rgba(22, 50, 63, 0.08);
    }

    .membership-list-top {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .membership-list-name {
        margin: 0;
        font-size: 1.35rem;
        font-weight: 900;
        color: var(--membership-ink);
    }

    .membership-list-sub {
        color: rgba(22, 50, 63, 0.64);
        font-size: var(--membership-text-sm);
        margin-top: 4px;
        line-height: 1.8;
    }

    .membership-chip-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 14px;
    }

    .membership-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 11px;
        border-radius: 999px;
        background: var(--membership-surface-chip);
        font-size: var(--membership-text-xs);
        font-weight: 700;
        color: rgba(22, 50, 63, 0.74);
    }

    .membership-columns {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .membership-pagination {
        margin-top: 4px;
        text-align: center;
    }

    .membership-page .pagination > li > a,
    .membership-page .pagination > li > span {
        border-radius: 12px !important;
        margin: 0 3px;
        border-color: rgba(22, 50, 63, 0.08);
        color: var(--membership-ink);
        box-shadow: none;
    }

    .membership-page .pagination > .active > span,
    .membership-page .pagination > .active > a {
        background: linear-gradient(135deg, #d8a44f, #bb7b24);
        border-color: transparent;
        color: #fff;
    }

    html[data-theme="dark"] .membership-page {
        --membership-ink: #eef4ff;
        --membership-ink-soft: #b7c6d8;
        --membership-gold: #d8b16d;
        --membership-gold-strong: #f5cf8c;
        --membership-sand: #182231;
        --membership-cream: #0f1724;
        --membership-card: rgba(15, 23, 36, 0.96);
        --membership-line: rgba(148, 163, 184, 0.18);
        --membership-line-strong: rgba(148, 163, 184, 0.28);
        --membership-mint: rgba(45, 95, 84, 0.32);
        --membership-danger: #ff8f86;
        --membership-shadow: 0 24px 60px rgba(2, 6, 23, 0.42);
        --membership-surface-soft: rgba(15, 23, 42, 0.86);
        --membership-surface-muted: rgba(148, 163, 184, 0.08);
        --membership-surface-chip: rgba(148, 163, 184, 0.1);
        background:
            radial-gradient(circle at top right, rgba(216, 177, 109, 0.12), transparent 24%),
            radial-gradient(circle at bottom left, rgba(96, 165, 250, 0.1), transparent 30%);
    }

    html[data-theme="dark"] .membership-page::before {
        background: radial-gradient(circle, rgba(216, 177, 109, 0.22) 0%, rgba(216, 177, 109, 0) 72%);
    }

    html[data-theme="dark"] .membership-page::after {
        background: radial-gradient(circle, rgba(96, 165, 250, 0.18) 0%, rgba(96, 165, 250, 0) 72%);
    }

    html[data-theme="dark"] .membership-hero {
        background:
            linear-gradient(135deg, rgba(8, 15, 28, 0.98), rgba(18, 37, 57, 0.96)),
            linear-gradient(120deg, rgba(216, 177, 109, 0.22), rgba(255, 255, 255, 0));
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 30px 65px rgba(2, 6, 23, 0.36);
    }

    html[data-theme="dark"] .membership-kicker,
    html[data-theme="dark"] .membership-btn-secondary {
        background: rgba(148, 163, 184, 0.1);
        border-color: rgba(148, 163, 184, 0.14);
    }

    html[data-theme="dark"] .membership-btn {
        color: #0f172a !important;
        box-shadow: 0 18px 34px rgba(216, 177, 109, 0.22);
    }

    html[data-theme="dark"] .membership-btn-muted,
    html[data-theme="dark"] .membership-icon-btn,
    html[data-theme="dark"] .membership-stat,
    html[data-theme="dark"] .membership-card,
    html[data-theme="dark"] .membership-panel,
    html[data-theme="dark"] .membership-section,
    html[data-theme="dark"] .membership-list-item,
    html[data-theme="dark"] .membership-detail,
    html[data-theme="dark"] .membership-empty,
    html[data-theme="dark"] .membership-alert.info,
    html[data-theme="dark"] .membership-alert.warning,
    html[data-theme="dark"] .membership-note,
    html[data-theme="dark"] .membership-readonly,
    html[data-theme="dark"] .membership-company-link,
    html[data-theme="dark"] .membership-company-strip,
    html[data-theme="dark"] .membership-company-department-chip {
        background: rgba(15, 23, 42, 0.88);
        color: var(--membership-ink) !important;
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 18px 35px rgba(2, 6, 23, 0.22);
    }

    html[data-theme="dark"] .membership-stat::before,
    html[data-theme="dark"] .membership-company-card::before {
        background: linear-gradient(90deg, rgba(216, 177, 109, 0.98), rgba(96, 165, 250, 0.92));
    }

    html[data-theme="dark"] .membership-company-card .membership-card-head {
        background:
            linear-gradient(135deg, rgba(96, 165, 250, 0.08), rgba(216, 177, 109, 0.12)),
            linear-gradient(180deg, rgba(255, 255, 255, 0.04), rgba(255, 255, 255, 0));
        border-bottom-color: rgba(148, 163, 184, 0.12);
    }

    html[data-theme="dark"] .membership-company-mark {
        background: linear-gradient(135deg, rgba(216, 177, 109, 0.2), rgba(96, 165, 250, 0.16));
        color: #f8fafc;
        box-shadow:
            inset 0 1px 0 rgba(255, 255, 255, 0.06),
            0 14px 26px rgba(2, 6, 23, 0.24);
    }

    html[data-theme="dark"] .membership-badge.company {
        background: rgba(216, 177, 109, 0.14);
        color: #f5cf8c;
    }

    html[data-theme="dark"] .membership-badge.department {
        background: rgba(45, 95, 84, 0.32);
        color: #8ce0c5;
    }

    html[data-theme="dark"] .membership-meta-item,
    html[data-theme="dark"] .membership-company-stat,
    html[data-theme="dark"] .membership-chip {
        background: rgba(148, 163, 184, 0.08);
        border-color: rgba(148, 163, 184, 0.14);
        color: var(--membership-ink);
    }

    html[data-theme="dark"] .membership-company-stat.primary,
    html[data-theme="dark"] .membership-company-department-chip.more,
    html[data-theme="dark"] .membership-alert.warning {
        background: rgba(216, 177, 109, 0.14);
        color: #f5cf8c !important;
        border-color: rgba(216, 177, 109, 0.18);
    }

    html[data-theme="dark"] .membership-note {
        background: rgba(45, 95, 84, 0.24);
        color: #baf3df;
        border-color: rgba(91, 173, 150, 0.18);
    }

    html[data-theme="dark"] .membership-alert.info {
        background: rgba(96, 165, 250, 0.12);
        color: #d8eaff;
        border-color: rgba(96, 165, 250, 0.18);
    }

    html[data-theme="dark"] .membership-empty {
        color: #d7e3f3;
    }

    html[data-theme="dark"] .membership-card-subtitle,
    html[data-theme="dark"] .membership-stat-label,
    html[data-theme="dark"] .membership-detail-label,
    html[data-theme="dark"] .membership-list-sub,
    html[data-theme="dark"] .membership-help,
    html[data-theme="dark"] .membership-meta-item span,
    html[data-theme="dark"] .membership-company-date,
    html[data-theme="dark"] .membership-chip {
        color: rgba(226, 232, 240, 0.78);
    }

    html[data-theme="dark"] .membership-input,
    html[data-theme="dark"] .membership-select {
        background: rgba(15, 23, 42, 0.82);
        border-color: rgba(148, 163, 184, 0.18);
        color: var(--membership-ink);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.02);
    }

    html[data-theme="dark"] .membership-input:focus,
    html[data-theme="dark"] .membership-select:focus {
        border-color: rgba(216, 177, 109, 0.68);
        box-shadow: 0 0 0 4px rgba(216, 177, 109, 0.12);
    }

    html[data-theme="dark"] .membership-page .pagination > li > a,
    html[data-theme="dark"] .membership-page .pagination > li > span {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.14);
        color: var(--membership-ink);
    }

    @keyframes membershipReveal {
        from {
            opacity: 0;
            transform: translateY(18px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes membershipFloat {
        0%, 100% {
            transform: translate3d(0, 0, 0);
        }
        50% {
            transform: translate3d(0, -16px, 0);
        }
    }

    @keyframes membershipShimmer {
        100% {
            transform: translateX(100%);
        }
    }

    @media (max-width: 991px) {
        .membership-columns,
        .membership-form-grid {
            grid-template-columns: 1fr;
        }

        .membership-company-brand,
        .membership-company-hero {
            flex-direction: column;
            align-items: stretch;
        }
    }

    @media (max-width: 767px) {
        .membership-page {
            padding-top: 12px;
        }

        .membership-hero,
        .membership-panel-body,
        .membership-panel-head,
        .membership-card,
        .membership-section {
            padding-left: 18px;
            padding-right: 18px;
        }

        .membership-hero {
            padding-top: 24px;
            padding-bottom: 24px;
        }

        .membership-title {
            font-size: clamp(1.8rem, 7vw, 2.35rem);
        }

        .membership-meta {
            grid-template-columns: 1fr;
        }

        .membership-actions,
        .membership-actions-bar,
        .membership-section-head {
            flex-direction: column;
            align-items: stretch;
        }

        .membership-btn,
        .membership-btn-secondary,
        .membership-btn-muted {
            width: 100%;
        }
    }
</style>
