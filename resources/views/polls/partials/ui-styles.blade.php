@push('styles')
<style>
    .poll-page {
        padding: 8px 0 30px;
    }

    .poll-shell {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .poll-hero,
    .poll-card,
    .poll-stat-card {
        border-radius: 28px;
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 18px 44px rgba(15, 23, 42, 0.07);
    }

    .poll-hero {
        position: relative;
        overflow: hidden;
        padding: clamp(24px, 3vw, 34px);
        background:
            radial-gradient(circle at top right, rgba(196, 168, 90, 0.28), transparent 32%),
            linear-gradient(135deg, #fff8ec 0%, #ffffff 46%, #f5ecdc 100%);
        animation: pollFadeUp 0.72s ease both;
    }

    .poll-hero::before,
    .poll-hero::after {
        content: "";
        position: absolute;
        border-radius: 999px;
        pointer-events: none;
    }

    .poll-hero::before {
        width: 220px;
        height: 220px;
        top: -120px;
        inset-inline-end: -70px;
        background: rgba(170, 134, 63, 0.10);
    }

    .poll-hero::after {
        width: 180px;
        height: 180px;
        bottom: -100px;
        inset-inline-start: -50px;
        background: rgba(196, 168, 90, 0.13);
    }

    .poll-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 22px;
        flex-wrap: wrap;
    }

    .poll-badge,
    .poll-chip,
    .poll-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-radius: 999px;
        font-weight: 800;
    }

    .poll-badge {
        margin-bottom: 14px;
        padding: 8px 14px;
        background: rgba(170, 134, 63, 0.12);
        color: var(--primary-color);
        font-size: 1rem;
    }

    .poll-title {
        margin: 0;
        color: var(--text-primary);
        font-size: clamp(2rem, 4vw, 3.1rem);
        font-weight: 900;
        line-height: 1.1;
    }

    .poll-subtitle {
        margin: 12px 0 0;
        max-width: 880px;
        color: var(--text-secondary);
        font-size: clamp(1rem, 1.4vw, 1.2rem);
        line-height: 1.9;
    }

    .poll-meta-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 18px;
    }

    .poll-chip {
        padding: 10px 14px;
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(170, 134, 63, 0.14);
        color: var(--text-primary);
        font-size: 0.98rem;
    }

    .poll-status-badge {
        padding: 10px 14px;
        font-size: 0.96rem;
    }

    .poll-status-badge.active {
        background: rgba(5, 150, 105, 0.12);
        border: 1px solid rgba(5, 150, 105, 0.18);
        color: var(--success-color);
    }

    .poll-status-badge.upcoming {
        background: rgba(14, 165, 233, 0.12);
        border: 1px solid rgba(14, 165, 233, 0.18);
        color: #0284c7;
    }

    .poll-status-badge.ended {
        background: rgba(100, 116, 139, 0.14);
        border: 1px solid rgba(100, 116, 139, 0.18);
        color: var(--text-secondary);
    }

    .poll-status-badge.inactive {
        background: rgba(220, 38, 38, 0.10);
        border: 1px solid rgba(220, 38, 38, 0.16);
        color: var(--danger-color);
    }

    .poll-hero-actions,
    .poll-action-row,
    .poll-footer-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .poll-btn,
    .poll-btn-muted,
    .poll-btn-danger,
    .poll-icon-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 48px;
        padding: 12px 18px;
        border-radius: 18px;
        border: 1px solid transparent;
        text-decoration: none !important;
        font-size: 1rem;
        font-weight: 800;
        transition: transform 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease, border-color 0.25s ease, color 0.25s ease;
    }

    .poll-btn {
        background: linear-gradient(135deg, var(--primary-color), #c49b48);
        color: #fff !important;
        box-shadow: 0 18px 30px rgba(170, 134, 63, 0.24);
    }

    .poll-btn-muted,
    .poll-icon-btn {
        background: rgba(255, 255, 255, 0.92);
        color: var(--text-primary) !important;
        border-color: rgba(170, 134, 63, 0.14);
    }

    .poll-btn-danger {
        background: rgba(220, 38, 38, 0.08);
        color: var(--danger-color) !important;
        border-color: rgba(220, 38, 38, 0.16);
    }

    .poll-btn:hover,
    .poll-btn-muted:hover,
    .poll-btn-danger:hover,
    .poll-icon-btn:hover {
        transform: translateY(-2px);
    }

    .poll-btn:hover {
        box-shadow: 0 22px 34px rgba(170, 134, 63, 0.28);
    }

    .poll-btn-muted:hover,
    .poll-icon-btn:hover {
        color: var(--primary-color) !important;
        border-color: rgba(170, 134, 63, 0.24);
    }

    .poll-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }

    .poll-stat-card,
    .poll-card {
        position: relative;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.96);
        animation: pollFadeUp 0.78s ease both;
    }

    .poll-stat-card {
        padding: 22px 20px;
    }

    .poll-stat-card::after,
    .poll-card::after {
        content: "";
        position: absolute;
        border-radius: 50%;
        background: rgba(170, 134, 63, 0.07);
        pointer-events: none;
    }

    .poll-stat-card::after {
        width: 96px;
        height: 96px;
        top: -38px;
        inset-inline-end: -18px;
    }

    .poll-card::after {
        width: 120px;
        height: 120px;
        top: -50px;
        inset-inline-end: -38px;
    }

    .poll-stat-card > *,
    .poll-card > * {
        position: relative;
        z-index: 1;
    }

    .poll-stat-icon,
    .poll-card-icon {
        width: 52px;
        height: 52px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(170, 134, 63, 0.16), rgba(170, 134, 63, 0.06));
        color: var(--primary-color);
    }

    .poll-stat-icon {
        font-size: 1.35rem;
        margin-bottom: 14px;
    }

    .poll-card-icon {
        font-size: 1.28rem;
        flex-shrink: 0;
    }

    .poll-stat-value {
        margin: 0;
        color: var(--text-primary);
        font-size: 2rem;
        font-weight: 900;
    }

    .poll-stat-label {
        margin: 6px 0 0;
        color: var(--text-secondary);
        font-size: 1rem;
        font-weight: 700;
    }

    .poll-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.7fr) minmax(280px, 0.95fr);
        gap: 22px;
        align-items: start;
    }

    .poll-form-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.7fr) minmax(280px, 0.95fr);
        gap: 22px;
        align-items: start;
    }

    .poll-card {
        padding: 24px;
    }

    .poll-card.full-span {
        grid-column: 1 / -1;
    }

    .poll-card-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .poll-card-title-wrap {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .poll-card-title {
        margin: 0;
        color: var(--text-primary);
        font-size: clamp(1.28rem, 2vw, 1.58rem);
        font-weight: 900;
    }

    .poll-card-note {
        margin: 5px 0 0;
        color: var(--text-secondary);
        font-size: 0.96rem;
        line-height: 1.8;
    }

    .poll-detail-grid,
    .poll-mini-stats,
    .poll-users-grid,
    .poll-option-grid {
        display: grid;
        gap: 14px;
    }

    .poll-detail-grid,
    .poll-option-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .poll-mini-stats,
    .poll-users-grid {
        grid-template-columns: 1fr;
    }

    .poll-detail-item,
    .poll-mini-stat,
    .poll-user-card,
    .poll-option-card,
    .poll-list-row,
    .poll-empty-state,
    .poll-vote-state,
    .poll-manage-option {
        border-radius: 22px;
        background: rgba(248, 250, 252, 0.92);
        border: 1px solid rgba(170, 134, 63, 0.10);
    }

    .poll-detail-item,
    .poll-mini-stat,
    .poll-user-card,
    .poll-option-card,
    .poll-vote-state,
    .poll-manage-option {
        padding: 16px;
    }

    .poll-detail-label,
    .poll-mini-label,
    .poll-option-meta,
    .poll-user-meta,
    .poll-form-note,
    .poll-help-text {
        color: var(--text-secondary);
        font-size: 0.94rem;
        line-height: 1.8;
    }

    .poll-detail-label,
    .poll-mini-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 700;
    }

    .poll-detail-value,
    .poll-mini-value,
    .poll-option-title,
    .poll-user-name {
        color: var(--text-primary);
        font-weight: 800;
    }

    .poll-detail-value,
    .poll-mini-value {
        font-size: 1.04rem;
        line-height: 1.6;
        word-break: break-word;
    }

    .poll-list-shell {
        border-radius: 28px;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(170, 134, 63, 0.14);
        box-shadow: 0 16px 44px rgba(15, 23, 42, 0.07);
        animation: pollFadeUp 0.86s ease both;
    }

    .poll-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        padding: 18px 20px;
        border-bottom: 1px solid rgba(170, 134, 63, 0.12);
        background: linear-gradient(180deg, rgba(255, 249, 239, 0.62), rgba(255, 255, 255, 0.96));
    }

    .poll-search {
        position: relative;
        flex: 1 1 320px;
    }

    .poll-search i {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        inset-inline-start: 16px;
        color: var(--text-light);
        font-size: 1.06rem;
    }

    .poll-search input,
    .poll-input,
    .poll-select,
    .poll-textarea {
        width: 100%;
        border-radius: 16px;
        border: 1px solid rgba(148, 163, 184, 0.24);
        background: rgba(255, 255, 255, 0.96);
        color: var(--text-primary);
        transition: border-color 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease;
    }

    .poll-search input {
        height: 54px;
        padding-inline-start: 46px;
        padding-inline-end: 18px;
        font-size: 1.05rem;
    }

    .poll-input,
    .poll-select,
    .poll-textarea {
        min-height: 50px;
        padding: 12px 16px;
        font-size: 1rem;
    }

    .poll-textarea {
        min-height: 140px;
        resize: vertical;
    }

    select.poll-select[multiple] {
        min-height: 180px;
        padding: 10px 12px;
    }

    .poll-search input:focus,
    .poll-input:focus,
    .poll-select:focus,
    .poll-textarea:focus {
        outline: none;
        border-color: rgba(170, 134, 63, 0.48);
        box-shadow: 0 0 0 4px rgba(170, 134, 63, 0.12);
        background: #fff;
    }

    .poll-toolbar-meta,
    .poll-meta-group {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .poll-meta-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 44px;
        padding: 10px 14px;
        border-radius: 14px;
        background: #f8f5ed;
        border: 1px solid rgba(170, 134, 63, 0.14);
        color: var(--text-secondary);
        font-size: 0.96rem;
        font-weight: 700;
    }

    .poll-list-body {
        padding: 12px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .poll-list-row {
        display: grid;
        grid-template-columns: minmax(240px, 2.2fr) minmax(150px, 1fr) minmax(140px, 0.9fr) minmax(180px, 1.2fr) minmax(170px, 1.1fr);
        align-items: center;
        gap: 16px;
        padding: 18px;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }

    .poll-list-row:hover {
        transform: translateY(-2px);
        border-color: rgba(170, 134, 63, 0.18);
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.07);
    }

    .poll-list-row.is-hidden {
        display: none;
    }

    .poll-main-title {
        margin: 0;
        color: var(--text-primary);
        font-size: 1.16rem;
        font-weight: 900;
        line-height: 1.5;
    }

    .poll-main-subtitle {
        margin-top: 6px;
        color: var(--text-secondary);
        font-size: 0.95rem;
        line-height: 1.7;
    }

    .poll-table-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .poll-table-label {
        color: var(--text-light);
        font-size: 0.84rem;
        font-weight: 700;
    }

    .poll-table-value {
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.6;
    }

    .poll-table-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        flex-wrap: wrap;
    }

    .poll-form-section,
    .poll-option-builder,
    .poll-question-block {
        padding: 18px;
        border-radius: 24px;
        background: linear-gradient(180deg, rgba(248, 250, 252, 0.85), rgba(255, 255, 255, 0.98));
        border: 1px solid rgba(170, 134, 63, 0.10);
    }

    .poll-form-section + .poll-form-section,
    .poll-option-builder + .poll-form-section,
    .poll-question-block + .poll-question-block {
        margin-top: 18px;
    }

    .poll-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 16px;
        color: var(--text-primary);
        font-size: 1.14rem;
        font-weight: 900;
    }

    .poll-field {
        margin-bottom: 18px;
    }

    .poll-field:last-child {
        margin-bottom: 0;
    }

    .poll-field label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 800;
    }

    .poll-field.has-error .poll-input,
    .poll-field.has-error .poll-select,
    .poll-field.has-error .poll-textarea {
        border-color: rgba(220, 38, 38, 0.5);
        box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.10);
    }

    .poll-error {
        margin-top: 8px;
        color: var(--danger-color);
        font-size: 0.92rem;
        font-weight: 700;
    }

    .poll-field-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .poll-toggle {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 16px;
        border-radius: 18px;
        background: rgba(170, 134, 63, 0.06);
        border: 1px solid rgba(170, 134, 63, 0.12);
    }

    .poll-toggle input {
        width: 18px;
        height: 18px;
        margin: 0;
    }

    .poll-toggle strong {
        display: block;
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 900;
    }

    .poll-toggle span {
        display: block;
        margin-top: 4px;
        color: var(--text-secondary);
        font-size: 0.92rem;
    }

    .poll-option-builder-head,
    .poll-option-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .poll-option-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .poll-option-card {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .poll-option-top {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .poll-option-order {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(170, 134, 63, 0.12);
        color: var(--primary-color);
        font-size: 1rem;
        font-weight: 900;
        flex-shrink: 0;
    }

    .poll-option-title {
        margin: 0;
        font-size: 1.02rem;
    }

    .poll-option-input-row {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 12px;
        align-items: center;
    }

    .poll-option-stack {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .poll-progress-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .poll-progress-item {
        padding: 16px;
        border-radius: 20px;
        background: rgba(248, 250, 252, 0.92);
        border: 1px solid rgba(170, 134, 63, 0.10);
    }

    .poll-progress-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 10px;
        flex-wrap: wrap;
    }

    .poll-progress-head strong {
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 800;
    }

    .poll-progress-values {
        color: var(--text-secondary);
        font-size: 0.94rem;
        font-weight: 700;
    }

    .poll-progress {
        width: 100%;
        height: 12px;
        border-radius: 999px;
        background: rgba(226, 232, 240, 0.9);
        overflow: hidden;
    }

    .poll-progress-bar {
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(135deg, var(--primary-color), #d3af6a);
    }

    .poll-vote-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .poll-vote-option {
        display: block;
        margin: 0;
        cursor: pointer;
    }

    .poll-vote-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .poll-vote-option-body {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 16px 18px;
        border-radius: 20px;
        border: 1px solid rgba(170, 134, 63, 0.12);
        background: rgba(248, 250, 252, 0.94);
        transition: border-color 0.25s ease, box-shadow 0.25s ease, transform 0.25s ease;
    }

    .poll-vote-option input:checked + .poll-vote-option-body {
        border-color: rgba(170, 134, 63, 0.42);
        box-shadow: 0 0 0 4px rgba(170, 134, 63, 0.12);
        transform: translateY(-1px);
        background: rgba(255, 248, 236, 0.98);
    }

    .poll-vote-option-title {
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 800;
    }

    .poll-vote-option-check {
        width: 28px;
        height: 28px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(170, 134, 63, 0.10);
        color: var(--primary-color);
        flex-shrink: 0;
    }

    .poll-vote-state,
    .poll-empty-state {
        text-align: center;
    }

    .poll-empty-state {
        padding: 34px 22px;
    }

    .poll-empty-state i,
    .poll-vote-state i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 64px;
        height: 64px;
        margin-bottom: 12px;
        border-radius: 20px;
        background: rgba(170, 134, 63, 0.12);
        color: var(--primary-color);
        font-size: 1.7rem;
    }

    .poll-empty-state h3,
    .poll-vote-state h3 {
        margin: 0 0 8px;
        color: var(--text-primary);
        font-size: 1.45rem;
        font-weight: 900;
    }

    .poll-empty-state p,
    .poll-vote-state p {
        margin: 0;
        color: var(--text-secondary);
        font-size: 1rem;
        line-height: 1.8;
    }

    .poll-chart-wrap {
        position: relative;
        min-height: 280px;
    }

    .poll-results-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(300px, 0.95fr);
        gap: 18px;
    }

    .poll-attendees-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    .poll-attendees-table thead th {
        padding: 0 14px 10px;
        color: var(--text-secondary);
        font-size: 0.92rem;
        font-weight: 800;
    }

    .poll-attendees-table tbody td {
        padding: 14px;
        color: var(--text-primary);
        font-size: 0.98rem;
        font-weight: 700;
        background: rgba(248, 250, 252, 0.92);
        border-top: 1px solid rgba(170, 134, 63, 0.10);
        border-bottom: 1px solid rgba(170, 134, 63, 0.10);
    }

    .poll-attendees-table tbody td:first-child {
        border-inline-start: 1px solid rgba(170, 134, 63, 0.10);
        border-top-right-radius: 16px;
        border-bottom-right-radius: 16px;
    }

    .poll-attendees-table tbody td:last-child {
        border-inline-end: 1px solid rgba(170, 134, 63, 0.10);
        border-top-left-radius: 16px;
        border-bottom-left-radius: 16px;
    }

    html[data-theme="dark"] .poll-hero {
        background:
            radial-gradient(circle at top right, rgba(141, 110, 43, 0.28), transparent 32%),
            linear-gradient(135deg, rgba(17, 24, 39, 0.98) 0%, rgba(15, 23, 42, 0.98) 55%, rgba(30, 41, 59, 0.98) 100%);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 24px 52px rgba(2, 6, 23, 0.34);
    }

    html[data-theme="dark"] .poll-card,
    html[data-theme="dark"] .poll-stat-card,
    html[data-theme="dark"] .poll-btn-muted,
    html[data-theme="dark"] .poll-icon-btn,
    html[data-theme="dark"] .poll-list-shell {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.14);
        box-shadow: 0 18px 38px rgba(2, 6, 23, 0.28);
    }

    html[data-theme="dark"] .poll-chip,
    html[data-theme="dark"] .poll-form-section,
    html[data-theme="dark"] .poll-option-builder,
    html[data-theme="dark"] .poll-question-block,
    html[data-theme="dark"] .poll-detail-item,
    html[data-theme="dark"] .poll-mini-stat,
    html[data-theme="dark"] .poll-user-card,
    html[data-theme="dark"] .poll-option-card,
    html[data-theme="dark"] .poll-list-row,
    html[data-theme="dark"] .poll-empty-state,
    html[data-theme="dark"] .poll-vote-state,
    html[data-theme="dark"] .poll-manage-option,
    html[data-theme="dark"] .poll-progress-item,
    html[data-theme="dark"] .poll-vote-option-body,
    html[data-theme="dark"] .poll-meta-pill,
    html[data-theme="dark"] .poll-toolbar {
        background: rgba(15, 23, 42, 0.76);
        border-color: rgba(148, 163, 184, 0.12);
    }

    html[data-theme="dark"] .poll-search input,
    html[data-theme="dark"] .poll-input,
    html[data-theme="dark"] .poll-select,
    html[data-theme="dark"] .poll-textarea {
        background: rgba(15, 23, 42, 0.95);
        border-color: rgba(148, 163, 184, 0.20);
        color: var(--text-primary);
    }

    html[data-theme="dark"] .poll-search input:focus,
    html[data-theme="dark"] .poll-input:focus,
    html[data-theme="dark"] .poll-select:focus,
    html[data-theme="dark"] .poll-textarea:focus {
        background: rgba(15, 23, 42, 1);
    }

    html[data-theme="dark"] .poll-toggle,
    html[data-theme="dark"] .poll-progress,
    html[data-theme="dark"] .poll-attendees-table tbody td {
        background: rgba(15, 23, 42, 0.82);
        border-color: rgba(148, 163, 184, 0.16);
    }

    @media (max-width: 1399px) {
        .poll-stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .poll-results-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 1199px) {
        .poll-grid,
        .poll-form-layout {
            grid-template-columns: 1fr;
        }

        .poll-list-row {
            grid-template-columns: 1fr;
        }

        .poll-table-actions {
            justify-content: flex-start;
        }
    }

    @media (max-width: 767px) {
        .poll-page {
            padding-top: 0;
        }

        .poll-hero,
        .poll-card,
        .poll-stat-card {
            border-radius: 24px;
            padding: 20px;
        }

        .poll-stats-grid {
            grid-template-columns: 1fr;
        }

        .poll-hero-actions,
        .poll-action-row,
        .poll-footer-actions {
            width: 100%;
        }

        .poll-hero-actions .poll-btn,
        .poll-hero-actions .poll-btn-muted,
        .poll-action-row .poll-btn,
        .poll-action-row .poll-btn-muted,
        .poll-action-row .poll-btn-danger,
        .poll-footer-actions .poll-btn,
        .poll-footer-actions .poll-btn-muted,
        .poll-footer-actions .poll-btn-danger {
            flex: 1 1 100%;
        }

        .poll-option-input-row {
            grid-template-columns: 1fr;
        }

        .poll-detail-grid,
        .poll-option-grid,
        .poll-field-grid {
            grid-template-columns: 1fr;
        }

        .poll-list-body {
            padding: 8px;
        }
    }

    @keyframes pollFadeUp {
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
