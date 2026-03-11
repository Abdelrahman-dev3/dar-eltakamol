<style>
    .membership-page {
        --membership-ink: #143642;
        --membership-gold: #c48a3a;
        --membership-gold-strong: #a86d20;
        --membership-sand: #f5efe3;
        --membership-cloud: #fffdf8;
        --membership-line: rgba(20, 54, 66, 0.12);
        --membership-mint: #d8efe4;
        --membership-danger: #b94f46;
        position: relative;
        padding: 28px 0 42px;
        font-family: "Cairo", "Segoe UI", sans-serif;
        color: var(--membership-ink);
    }

    .membership-page::before,
    .membership-page::after {
        content: "";
        position: fixed;
        inset: auto;
        border-radius: 999px;
        pointer-events: none;
        z-index: 0;
        opacity: 0.45;
        filter: blur(14px);
    }

    .membership-page::before {
        width: 280px;
        height: 280px;
        top: 110px;
        right: -70px;
        background: radial-gradient(circle, rgba(196, 138, 58, 0.24) 0%, rgba(196, 138, 58, 0) 72%);
        animation: membershipFloat 11s ease-in-out infinite;
    }

    .membership-page::after {
        width: 220px;
        height: 220px;
        bottom: 40px;
        left: -50px;
        background: radial-gradient(circle, rgba(20, 54, 66, 0.18) 0%, rgba(20, 54, 66, 0) 72%);
        animation: membershipFloat 13s ease-in-out infinite reverse;
    }

    .membership-shell {
        position: relative;
        z-index: 1;
    }

    .membership-hero,
    .membership-panel,
    .membership-card,
    .membership-section,
    .membership-list-item {
        animation: membershipReveal 0.75s ease both;
    }

    .membership-hero {
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
        padding: 30px 32px;
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 28px;
        background:
            linear-gradient(135deg, rgba(20, 54, 66, 0.96), rgba(27, 80, 99, 0.92)),
            linear-gradient(120deg, rgba(196, 138, 58, 0.24), rgba(255, 255, 255, 0));
        box-shadow: 0 24px 54px rgba(20, 54, 66, 0.18);
        color: #fff;
    }

    .membership-hero::before {
        content: "";
        position: absolute;
        width: 240px;
        height: 240px;
        top: -70px;
        left: -60px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.14) 0%, rgba(255, 255, 255, 0) 72%);
    }

    .membership-hero::after {
        content: "";
        position: absolute;
        inset: 0;
        background-image: linear-gradient(120deg, transparent 15%, rgba(255, 255, 255, 0.07) 35%, transparent 55%);
        transform: translateX(-100%);
        animation: membershipShimmer 5.5s linear infinite;
    }

    .membership-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        font-size: 12px;
        letter-spacing: 0.08em;
    }

    .membership-title {
        margin: 0;
        font-size: 32px;
        font-weight: 800;
        line-height: 1.3;
    }

    .membership-subtitle {
        max-width: 760px;
        margin: 10px 0 0;
        color: rgba(255, 255, 255, 0.84);
        font-size: 15px;
        line-height: 1.9;
    }

    .membership-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 22px;
    }

    .membership-btn,
    .membership-btn-secondary,
    .membership-btn-muted,
    .membership-icon-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: 0;
        border-radius: 16px;
        text-decoration: none !important;
        transition: transform 0.22s ease, box-shadow 0.22s ease, opacity 0.22s ease;
    }

    .membership-btn {
        padding: 12px 18px;
        background: linear-gradient(135deg, #f2c36b, #d5902d);
        color: #143642 !important;
        box-shadow: 0 14px 28px rgba(212, 145, 45, 0.25);
        font-weight: 700;
    }

    .membership-btn-secondary {
        padding: 12px 18px;
        background: rgba(255, 255, 255, 0.12);
        color: #fff !important;
        border: 1px solid rgba(255, 255, 255, 0.18);
    }

    .membership-btn-muted {
        padding: 11px 16px;
        background: #fff;
        color: var(--membership-ink) !important;
        border: 1px solid var(--membership-line);
        box-shadow: 0 10px 24px rgba(20, 54, 66, 0.08);
    }

    .membership-btn-danger {
        background: linear-gradient(135deg, #d96a62, #b94f46);
        color: #fff !important;
    }

    .membership-btn:hover,
    .membership-btn-secondary:hover,
    .membership-btn-muted:hover,
    .membership-icon-btn:hover {
        transform: translateY(-2px);
    }

    .membership-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 14px;
        margin-bottom: 20px;
    }

    .membership-stat {
        padding: 18px 20px;
        border: 1px solid rgba(20, 54, 66, 0.08);
        border-radius: 22px;
        background: rgba(255, 255, 255, 0.84);
        box-shadow: 0 16px 36px rgba(20, 54, 66, 0.08);
        backdrop-filter: blur(8px);
    }

    .membership-stat-label {
        color: rgba(20, 54, 66, 0.66);
        font-size: 13px;
    }

    .membership-stat-value {
        margin-top: 8px;
        font-size: 30px;
        font-weight: 800;
        line-height: 1;
    }

    .membership-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 18px;
    }

    .membership-card,
    .membership-panel,
    .membership-section {
        border: 1px solid rgba(20, 54, 66, 0.08);
        border-radius: 24px;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(250, 247, 240, 0.96));
        box-shadow: 0 18px 40px rgba(20, 54, 66, 0.08);
    }

    .membership-card {
        position: relative;
        overflow: hidden;
        padding: 22px;
    }

    .membership-card::after {
        content: "";
        position: absolute;
        inset: auto -40px -40px auto;
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(196, 138, 58, 0.16) 0%, rgba(196, 138, 58, 0) 70%);
    }

    .membership-card-head,
    .membership-section-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 16px;
    }

    .membership-card-title,
    .membership-section-title {
        margin: 0;
        font-size: 22px;
        font-weight: 800;
        color: var(--membership-ink);
    }

    .membership-card-subtitle {
        margin-top: 4px;
        color: rgba(20, 54, 66, 0.62);
        font-size: 13px;
    }

    .membership-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(20, 54, 66, 0.08);
        color: var(--membership-ink);
        font-size: 12px;
        font-weight: 700;
    }

    .membership-badge.company {
        background: rgba(196, 138, 58, 0.18);
        color: var(--membership-gold-strong);
    }

    .membership-badge.department {
        background: rgba(28, 113, 107, 0.13);
        color: #17655d;
    }

    .membership-meta {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
        margin: 18px 0;
    }

    .membership-meta-item {
        padding: 14px 12px;
        border-radius: 18px;
        background: rgba(20, 54, 66, 0.04);
        text-align: center;
    }

    .membership-meta-item strong {
        display: block;
        font-size: 22px;
        font-weight: 800;
    }

    .membership-meta-item span {
        color: rgba(20, 54, 66, 0.62);
        font-size: 12px;
    }

    .membership-card-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .membership-icon-btn {
        width: 40px;
        height: 40px;
        background: rgba(20, 54, 66, 0.08);
        color: var(--membership-ink) !important;
    }

    .membership-icon-btn.danger {
        background: rgba(185, 79, 70, 0.1);
        color: var(--membership-danger) !important;
    }

    .membership-panel {
        overflow: hidden;
        padding: 0;
    }

    .membership-panel-head {
        padding: 22px 24px 0;
    }

    .membership-panel-body {
        padding: 24px;
    }

    .membership-note,
    .membership-empty,
    .membership-alert {
        padding: 16px 18px;
        border-radius: 18px;
        margin-bottom: 16px;
        border: 1px solid transparent;
    }

    .membership-note {
        background: rgba(216, 239, 228, 0.72);
        border-color: rgba(28, 113, 107, 0.16);
        color: #1d6159;
    }

    .membership-alert.info {
        background: rgba(20, 54, 66, 0.06);
        border-color: rgba(20, 54, 66, 0.1);
        color: var(--membership-ink);
    }

    .membership-alert.warning {
        background: rgba(196, 138, 58, 0.1);
        border-color: rgba(196, 138, 58, 0.18);
        color: #8a5a18;
    }

    .membership-empty {
        background: rgba(20, 54, 66, 0.04);
        color: rgba(20, 54, 66, 0.72);
        text-align: center;
    }

    .membership-form-grid {
        display: grid;
        gap: 18px;
    }

    .membership-field label {
        display: block;
        margin-bottom: 8px;
        font-weight: 700;
        color: var(--membership-ink);
    }

    .membership-input,
    .membership-select,
    .membership-readonly {
        width: 100%;
        min-height: 52px;
        padding: 14px 16px;
        border: 1px solid rgba(20, 54, 66, 0.12);
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.92);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.45);
        transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
    }

    .membership-input:focus,
    .membership-select:focus {
        outline: none;
        border-color: rgba(196, 138, 58, 0.7);
        box-shadow: 0 0 0 4px rgba(196, 138, 58, 0.12);
        transform: translateY(-1px);
    }

    .membership-readonly {
        display: flex;
        align-items: center;
        color: rgba(20, 54, 66, 0.72);
        background: rgba(20, 54, 66, 0.05);
    }

    .membership-help {
        display: block;
        margin-top: 8px;
        color: rgba(20, 54, 66, 0.58);
        font-size: 12px;
        line-height: 1.8;
    }

    .membership-error {
        display: block;
        margin-top: 8px;
        color: var(--membership-danger);
        font-size: 12px;
        font-weight: 700;
    }

    .membership-actions-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 24px;
    }

    .membership-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 14px;
    }

    .membership-detail {
        padding: 18px;
        border-radius: 20px;
        background: rgba(20, 54, 66, 0.04);
    }

    .membership-detail-label {
        color: rgba(20, 54, 66, 0.58);
        font-size: 12px;
        margin-bottom: 8px;
    }

    .membership-detail-value {
        font-size: 19px;
        font-weight: 800;
    }

    .membership-list {
        display: grid;
        gap: 12px;
    }

    .membership-list-item {
        padding: 16px 18px;
        border: 1px solid rgba(20, 54, 66, 0.08);
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.82);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .membership-list-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 30px rgba(20, 54, 66, 0.08);
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
        font-size: 18px;
        font-weight: 800;
        color: var(--membership-ink);
    }

    .membership-list-sub {
        color: rgba(20, 54, 66, 0.64);
        font-size: 13px;
        margin-top: 4px;
    }

    .membership-chip-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
    }

    .membership-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 11px;
        border-radius: 999px;
        background: rgba(20, 54, 66, 0.07);
        font-size: 12px;
        color: rgba(20, 54, 66, 0.74);
    }

    .membership-columns {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
        margin-top: 18px;
    }

    .membership-pagination {
        margin-top: 22px;
        text-align: center;
    }

    .membership-page .pagination > li > a,
    .membership-page .pagination > li > span {
        border-radius: 12px !important;
        margin: 0 3px;
        border-color: rgba(20, 54, 66, 0.08);
        color: var(--membership-ink);
    }

    .membership-page .pagination > .active > span,
    .membership-page .pagination > .active > a {
        background: linear-gradient(135deg, #d8a44f, #bb7b24);
        border-color: transparent;
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
        .membership-columns {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .membership-page {
            padding-top: 10px;
        }

        .membership-hero,
        .membership-panel-body,
        .membership-panel-head,
        .membership-card {
            padding-left: 18px;
            padding-right: 18px;
        }

        .membership-title {
            font-size: 26px;
        }

        .membership-meta {
            grid-template-columns: 1fr;
        }
    }
</style>
