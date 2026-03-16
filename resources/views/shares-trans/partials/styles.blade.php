@once
    @push('styles')
        <style>
            .st-page { padding: 0.5rem 0 2rem; }
            .st-shell { display: flex; flex-direction: column; gap: 1.5rem; }
            .st-hero {
                position: relative;
                overflow: hidden;
                border-radius: 2rem;
                padding: 2rem;
                background:
                    radial-gradient(circle at top left, rgba(196, 168, 90, 0.28), transparent 34%),
                    linear-gradient(135deg, #fffaf0 0%, #ffffff 46%, #f5efe2 100%);
                border: 1px solid rgba(170, 134, 63, 0.16);
                box-shadow: 0 1.5rem 3rem rgba(15, 23, 42, 0.08);
                animation: stFadeUp 0.7s ease both;
            }
            .st-hero::before,
            .st-hero::after {
                content: "";
                position: absolute;
                border-radius: 999px;
                pointer-events: none;
            }
            .st-hero::before {
                width: 15rem;
                height: 15rem;
                inset-inline-end: -4rem;
                top: -6rem;
                background: rgba(170, 134, 63, 0.11);
                animation: stFloat 8s ease-in-out infinite;
            }
            .st-hero::after {
                width: 11rem;
                height: 11rem;
                inset-inline-start: -2rem;
                bottom: -5rem;
                background: rgba(196, 168, 90, 0.12);
                animation: stFloat 10s ease-in-out infinite reverse;
            }
            .st-hero-inner {
                position: relative;
                z-index: 1;
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 1rem;
                flex-wrap: wrap;
            }
            .st-hero-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.55rem;
                margin-bottom: 0.9rem;
                padding: 0.7rem 1rem;
                border-radius: 999px;
                background: rgba(170, 134, 63, 0.1);
                color: var(--primary-color);
                font-size: 1rem;
                font-weight: 800;
            }
            .st-hero-title {
                margin: 0;
                color: var(--text-primary);
                font-size: clamp(2.1rem, 4vw, 3.2rem);
                font-weight: 900;
                line-height: 1.15;
            }
            .st-hero-subtitle {
                margin: 0.85rem 0 0;
                max-width: 50rem;
                color: var(--text-secondary);
                font-size: 1.08rem;
                line-height: 1.9;
            }
            .st-hero-actions,
            .st-inline-actions,
            .st-chip-row,
            .st-action-group {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                flex-wrap: wrap;
            }
            .st-btn,
            .st-icon-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.6rem;
                border: 0;
                text-decoration: none !important;
                transition: transform 0.24s ease, box-shadow 0.24s ease, opacity 0.24s ease, background-color 0.24s ease, color 0.24s ease;
                font-weight: 800;
                cursor: pointer;
            }
            .st-btn {
                min-height: 3.35rem;
                padding: 0.85rem 1.25rem;
                border-radius: 1.1rem;
                font-size: 1rem;
            }
            .st-icon-btn {
                min-width: 2.9rem;
                min-height: 2.9rem;
                padding: 0.75rem 0.95rem;
                border-radius: 1rem;
                font-size: 0.96rem;
            }
            .st-btn:hover,
            .st-icon-btn:hover {
                transform: translateY(-0.125rem);
                opacity: 0.98;
            }
            .st-btn-primary {
                background: linear-gradient(135deg, var(--primary-color), #c49b48);
                color: #fff;
                box-shadow: 0 1rem 2rem rgba(170, 134, 63, 0.2);
            }
            .st-btn-primary:hover {
                color: #fff;
                box-shadow: 0 1.3rem 2.2rem rgba(170, 134, 63, 0.28);
            }
            .st-btn-secondary,
            .st-icon-btn-secondary {
                background: rgba(255, 255, 255, 0.92);
                color: var(--text-primary);
                border: 1px solid rgba(170, 134, 63, 0.16);
            }
            .st-btn-secondary:hover,
            .st-icon-btn-secondary:hover {
                color: var(--primary-color);
                background: #fff;
            }
            .st-btn-success,
            .st-icon-btn-success {
                background: rgba(5, 150, 105, 0.12);
                color: var(--success-color);
                border: 1px solid rgba(5, 150, 105, 0.12);
            }
            .st-btn-warning,
            .st-icon-btn-warning {
                background: rgba(217, 119, 6, 0.12);
                color: var(--warning-color);
                border: 1px solid rgba(217, 119, 6, 0.12);
            }
            .st-btn-danger,
            .st-icon-btn-danger {
                background: rgba(220, 38, 38, 0.1);
                color: var(--danger-color);
                border: 1px solid rgba(220, 38, 38, 0.1);
            }
            .st-btn-info,
            .st-icon-btn-info {
                background: rgba(14, 165, 233, 0.12);
                color: #0284c7;
                border: 1px solid rgba(14, 165, 233, 0.12);
            }
            .st-btn-ghost {
                background: rgba(170, 134, 63, 0.08);
                color: var(--primary-color);
                border: 1px dashed rgba(170, 134, 63, 0.2);
            }
            .st-stat-grid,
            .st-summary-grid,
            .st-grid-two,
            .st-type-grid,
            .st-form-grid {
                display: grid;
                gap: 1rem;
            }
            .st-stat-grid,
            .st-summary-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
            .st-grid-two {
                grid-template-columns: minmax(0, 1.55fr) minmax(19rem, 0.95fr);
                align-items: start;
            }
            .st-type-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .st-form-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .st-stat-card,
            .st-summary-card,
            .st-card,
            .st-row,
            .st-empty {
                background: rgba(255, 255, 255, 0.96);
                border: 1px solid rgba(170, 134, 63, 0.14);
                box-shadow: 0 1rem 2.75rem rgba(15, 23, 42, 0.06);
                animation: stFadeUp 0.78s ease both;
            }
            .st-stat-card,
            .st-summary-card {
                position: relative;
                overflow: hidden;
                padding: 1.35rem 1.25rem;
                border-radius: 1.5rem;
            }
            .st-stat-card::after,
            .st-summary-card::after {
                content: "";
                position: absolute;
                width: 6.2rem;
                height: 6.2rem;
                border-radius: 50%;
                inset-inline-end: -1.35rem;
                top: -2.5rem;
                background: rgba(170, 134, 63, 0.08);
            }
            .st-stat-icon,
            .st-summary-icon,
            .st-card-icon {
                width: 3.25rem;
                height: 3.25rem;
                border-radius: 1rem;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, rgba(170, 134, 63, 0.16), rgba(170, 134, 63, 0.06));
                color: var(--primary-color);
                font-size: 1.35rem;
                flex-shrink: 0;
            }
            .st-stat-icon,
            .st-summary-icon { margin-bottom: 1rem; }
            .st-stat-value,
            .st-summary-value {
                margin: 0;
                color: var(--text-primary);
                font-size: clamp(1.7rem, 2.5vw, 2.15rem);
                font-weight: 900;
                line-height: 1.1;
            }
            .st-stat-label,
            .st-summary-label {
                margin: 0.45rem 0 0;
                color: var(--text-secondary);
                font-size: 0.98rem;
                font-weight: 700;
                line-height: 1.75;
            }
            .st-card {
                border-radius: 1.8rem;
                padding: 1.55rem;
            }
            .st-card-header {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 0.9rem;
                flex-wrap: wrap;
                margin-bottom: 1.25rem;
            }
            .st-card-title-wrap {
                display: flex;
                align-items: flex-start;
                gap: 0.9rem;
            }
            .st-card-title {
                margin: 0;
                color: var(--text-primary);
                font-size: 1.38rem;
                font-weight: 900;
                line-height: 1.3;
            }
            .st-card-subtitle {
                margin: 0.35rem 0 0;
                color: var(--text-secondary);
                font-size: 0.96rem;
                line-height: 1.8;
            }
            .st-banner,
            .st-note-box,
            .st-info-item,
            .st-type-card,
            .st-toolbar,
            .st-table-wrap {
                border-radius: 1.35rem;
                border: 1px solid rgba(170, 134, 63, 0.12);
            }
            .st-banner,
            .st-note-box {
                display: flex;
                align-items: flex-start;
                gap: 0.85rem;
                padding: 1rem 1.1rem;
                background: rgba(170, 134, 63, 0.08);
                color: var(--text-secondary);
                line-height: 1.8;
            }
            .st-banner i,
            .st-note-box i {
                color: var(--primary-color);
                font-size: 1.15rem;
                margin-top: 0.2rem;
            }
            .st-banner.is-warning {
                background: rgba(217, 119, 6, 0.1);
                border-color: rgba(217, 119, 6, 0.18);
            }
            .st-banner.is-warning i { color: var(--warning-color); }
            .st-banner.is-danger {
                background: rgba(220, 38, 38, 0.08);
                border-color: rgba(220, 38, 38, 0.16);
            }
            .st-banner.is-danger i { color: var(--danger-color); }
            .st-info-list { display: grid; gap: 0.9rem; }
            .st-info-item {
                padding: 1rem 1.05rem;
                background: rgba(248, 245, 237, 0.7);
            }
            .st-info-label {
                display: block;
                margin-bottom: 0.4rem;
                color: var(--text-light);
                font-size: 0.88rem;
                font-weight: 700;
            }
            .st-info-value {
                color: var(--text-primary);
                font-size: 1.02rem;
                font-weight: 800;
                line-height: 1.65;
                word-break: break-word;
            }
            .st-info-value.muted {
                color: var(--text-secondary);
                font-weight: 700;
            }
            .st-chip {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                min-height: 2.8rem;
                padding: 0.75rem 1rem;
                border-radius: 999px;
                background: #f8f5ed;
                border: 1px solid rgba(170, 134, 63, 0.12);
                color: var(--text-secondary);
                font-size: 0.94rem;
                font-weight: 800;
            }
            .st-toolbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                flex-wrap: wrap;
                padding: 1rem 1.1rem;
                background: rgba(255, 255, 255, 0.94);
                box-shadow: 0 1rem 2rem rgba(15, 23, 42, 0.05);
                animation: stFadeUp 0.82s ease both;
            }
            .st-search {
                position: relative;
                flex: 1 1 22rem;
            }
            .st-search i {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                inset-inline-start: 1rem;
                color: var(--text-light);
                font-size: 1rem;
            }
            .st-search input,
            .st-input,
            .st-select,
            .st-textarea {
                width: 100%;
                border-radius: 1rem;
                border: 1px solid rgba(170, 134, 63, 0.16);
                background: #fff;
                color: var(--text-primary);
                transition: border-color 0.22s ease, box-shadow 0.22s ease, background-color 0.22s ease;
            }
            .st-search input {
                min-height: 3.45rem;
                padding-inline-start: 2.9rem;
                padding-inline-end: 1rem;
                font-size: 1rem;
            }
            .st-input,
            .st-select,
            .st-textarea {
                min-height: 3.4rem;
                padding: 0.9rem 1rem;
                font-size: 1rem;
                box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.4);
            }
            .st-textarea {
                min-height: 8.5rem;
                resize: vertical;
            }
            .st-search input:focus,
            .st-input:focus,
            .st-select:focus,
            .st-textarea:focus {
                outline: none;
                border-color: rgba(170, 134, 63, 0.42);
                box-shadow: 0 0 0 0.3rem rgba(170, 134, 63, 0.1);
            }
            .st-form-field {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
                min-width: 0;
            }
            .st-form-field.full { grid-column: 1 / -1; }
            .st-label {
                color: var(--text-primary);
                font-size: 0.98rem;
                font-weight: 800;
            }
            .st-required { color: var(--danger-color); }
            .st-help {
                color: var(--text-secondary);
                font-size: 0.88rem;
                line-height: 1.75;
            }
            .st-error {
                color: var(--danger-color);
                font-size: 0.88rem;
                font-weight: 700;
            }
            .st-form-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                flex-wrap: wrap;
                margin-top: 1.4rem;
                padding-top: 1.3rem;
                border-top: 1px solid rgba(170, 134, 63, 0.12);
            }
            .st-form-footer-note {
                margin: 0;
                flex: 1 1 18rem;
                color: var(--text-secondary);
                font-size: 0.95rem;
                line-height: 1.85;
            }
            .st-type-card {
                height: 100%;
                padding: 1rem;
                background: rgba(248, 245, 237, 0.8);
            }
            .st-type-card i {
                width: 2.9rem;
                height: 2.9rem;
                margin-bottom: 0.85rem;
                border-radius: 0.95rem;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: rgba(170, 134, 63, 0.1);
                color: var(--primary-color);
                font-size: 1.2rem;
            }
            .st-type-card h3 {
                margin: 0 0 0.35rem;
                color: var(--text-primary);
                font-size: 1.08rem;
                font-weight: 900;
            }
            .st-type-card p {
                margin: 0;
                color: var(--text-secondary);
                font-size: 0.94rem;
                line-height: 1.8;
            }
            .st-steps {
                margin: 0;
                padding-inline-start: 1.25rem;
                color: var(--text-secondary);
                font-size: 0.95rem;
                line-height: 1.95;
            }
            .st-list-card {
                border-radius: 1.9rem;
                background: rgba(255, 255, 255, 0.96);
                border: 1px solid rgba(170, 134, 63, 0.14);
                box-shadow: 0 1rem 2.75rem rgba(15, 23, 42, 0.07);
                overflow: hidden;
                animation: stFadeUp 0.92s ease both;
            }
            .st-list-head,
            .st-row {
                display: grid;
                grid-template-columns: minmax(16rem, 2.3fr) minmax(8.5rem, 1fr) minmax(8rem, 0.9fr) minmax(9rem, 0.9fr) minmax(14rem, 1.4fr);
                align-items: center;
                gap: 1rem;
            }
            .st-list-head {
                padding: 1.1rem 1.35rem;
                background: linear-gradient(180deg, #fff9ef 0%, #fcf7ee 100%);
                color: var(--text-secondary);
                font-size: 0.95rem;
                font-weight: 800;
                border-bottom: 1px solid rgba(170, 134, 63, 0.12);
            }
            .st-list-body { padding: 0.75rem 0.75rem 1rem; }
            .st-row {
                margin: 0.75rem 0.75rem 0;
                padding: 1.2rem;
                border-radius: 1.45rem;
                background: #fff;
                border: 1px solid rgba(226, 232, 240, 0.95);
                transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
            }
            .st-row:hover {
                transform: translateY(-0.2rem);
                border-color: rgba(170, 134, 63, 0.2);
                box-shadow: 0 1.25rem 2rem rgba(15, 23, 42, 0.08);
            }
            .st-row.is-hidden { display: none; }
            .st-row-main {
                display: flex;
                align-items: center;
                gap: 0.9rem;
                min-width: 0;
            }
            .st-row-avatar {
                width: 3.55rem;
                height: 3.55rem;
                border-radius: 1.1rem;
                flex-shrink: 0;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, var(--primary-color), #cba55c);
                color: #fff;
                font-size: 1.05rem;
                font-weight: 900;
                box-shadow: 0 1rem 1.8rem rgba(170, 134, 63, 0.2);
            }
            .st-row-title {
                margin: 0;
                color: var(--text-primary);
                font-size: 1.1rem;
                font-weight: 900;
                line-height: 1.4;
            }
            .st-row-meta {
                margin-top: 0.35rem;
                display: flex;
                align-items: center;
                gap: 0.45rem;
                flex-wrap: wrap;
            }
            .st-field {
                display: flex;
                flex-direction: column;
                gap: 0.35rem;
                min-width: 0;
            }
            .st-field-label {
                color: var(--text-light);
                font-size: 0.84rem;
                font-weight: 700;
            }
            .st-field-value {
                color: var(--text-primary);
                font-size: 0.98rem;
                font-weight: 800;
                line-height: 1.65;
                word-break: break-word;
            }
            .st-field-value.muted {
                color: var(--text-secondary);
                font-weight: 700;
            }
            .st-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
                padding: 0.5rem 0.8rem;
                border-radius: 999px;
                font-size: 0.85rem;
                font-weight: 900;
                border: 1px solid transparent;
            }
            .st-badge-buy {
                background: rgba(5, 150, 105, 0.12);
                color: var(--success-color);
            }
            .st-badge-sell {
                background: rgba(220, 38, 38, 0.1);
                color: var(--danger-color);
            }
            .st-badge-transfer {
                background: rgba(14, 165, 233, 0.12);
                color: #0284c7;
            }
            .st-badge-dividend {
                background: rgba(217, 119, 6, 0.12);
                color: var(--warning-color);
            }
            .st-badge-success {
                background: rgba(5, 150, 105, 0.12);
                color: var(--success-color);
            }
            .st-badge-warning {
                background: rgba(217, 119, 6, 0.12);
                color: var(--warning-color);
            }
            .st-badge-danger {
                background: rgba(220, 38, 38, 0.1);
                color: var(--danger-color);
            }
            .st-badge-neutral {
                background: rgba(148, 163, 184, 0.14);
                color: var(--text-secondary);
            }
            .st-empty {
                display: none;
                padding: 3rem 1.5rem 3.4rem;
                text-align: center;
                border-radius: 1.8rem;
            }
            .st-empty.show { display: block; }
            .st-empty-icon {
                width: 4.7rem;
                height: 4.7rem;
                margin: 0 auto 1rem;
                border-radius: 1.45rem;
                display: flex;
                align-items: center;
                justify-content: center;
                background: rgba(170, 134, 63, 0.12);
                color: var(--primary-color);
                font-size: 1.6rem;
            }
            .st-empty h3 {
                margin: 0 0 0.55rem;
                color: var(--text-primary);
                font-size: 1.6rem;
                font-weight: 900;
            }
            .st-empty p {
                margin: 0;
                color: var(--text-secondary);
                font-size: 1rem;
                line-height: 1.85;
            }
            .st-pagination {
                padding: 1.25rem 1.4rem;
                border-top: 1px solid rgba(170, 134, 63, 0.1);
                background: linear-gradient(180deg, rgba(255, 249, 239, 0.35) 0%, rgba(255, 255, 255, 0.92) 100%);
            }
            .st-pagination .pagination { margin: 0; }
            .st-pagination .pagination > li > a,
            .st-pagination .pagination > li > span {
                border-radius: 0.9rem !important;
                margin: 0 0.2rem;
                border: 1px solid rgba(170, 134, 63, 0.14);
                color: var(--text-primary);
                min-width: 2.8rem;
                text-align: center;
                background: rgba(255, 255, 255, 0.92);
            }
            .st-pagination .pagination > .active > span,
            .st-pagination .pagination > .active > span:hover,
            .st-pagination .pagination > .active > span:focus,
            .st-pagination .pagination > .active > a,
            .st-pagination .pagination > .active > a:hover,
            .st-pagination .pagination > .active > a:focus {
                background: linear-gradient(135deg, var(--primary-color), #c49b48);
                border-color: transparent;
                color: #fff;
            }
            .st-table-wrap {
                overflow: hidden;
                background: rgba(255, 255, 255, 0.92);
            }
            .st-table-scroll { overflow-x: auto; }
            .st-table {
                width: 100%;
                margin: 0;
                border-collapse: separate;
                border-spacing: 0;
            }
            .st-table thead th {
                padding: 1rem;
                background: linear-gradient(180deg, #fff9ef 0%, #fcf7ee 100%);
                color: var(--text-secondary);
                font-size: 0.92rem;
                font-weight: 900;
                white-space: nowrap;
                border-bottom: 1px solid rgba(170, 134, 63, 0.12);
            }
            .st-table tbody td,
            .st-table tfoot th,
            .st-table tfoot td {
                padding: 1rem;
                color: var(--text-primary);
                font-size: 0.96rem;
                vertical-align: middle;
                border-bottom: 1px solid rgba(226, 232, 240, 0.9);
            }
            .st-table tbody tr:last-child td { border-bottom: 0; }
            .st-table tbody tr:hover td { background: rgba(170, 134, 63, 0.03); }
            .st-table tfoot th,
            .st-table tfoot td {
                background: rgba(170, 134, 63, 0.06);
                font-weight: 900;
            }
            .st-text-right { text-align: right; }
            .st-text-center { text-align: center; }
            html[data-theme="dark"] .st-hero {
                background:
                    radial-gradient(circle at top left, rgba(141, 110, 43, 0.22), transparent 34%),
                    linear-gradient(135deg, rgba(17, 24, 39, 0.98) 0%, rgba(15, 23, 42, 0.98) 46%, rgba(30, 41, 59, 0.96) 100%);
                border-color: rgba(148, 163, 184, 0.15);
            }
            html[data-theme="dark"] .st-card,
            html[data-theme="dark"] .st-stat-card,
            html[data-theme="dark"] .st-summary-card,
            html[data-theme="dark"] .st-list-card,
            html[data-theme="dark"] .st-row,
            html[data-theme="dark"] .st-empty,
            html[data-theme="dark"] .st-toolbar,
            html[data-theme="dark"] .st-table-wrap {
                background: rgba(17, 24, 39, 0.94);
                border-color: rgba(148, 163, 184, 0.16);
                box-shadow: 0 1.2rem 2.6rem rgba(2, 6, 23, 0.35);
            }
            html[data-theme="dark"] .st-list-head,
            html[data-theme="dark"] .st-table thead th {
                background: linear-gradient(180deg, rgba(30, 41, 59, 0.95) 0%, rgba(17, 24, 39, 0.98) 100%);
                border-color: rgba(148, 163, 184, 0.12);
            }
            html[data-theme="dark"] .st-chip,
            html[data-theme="dark"] .st-info-item,
            html[data-theme="dark"] .st-type-card,
            html[data-theme="dark"] .st-btn-secondary,
            html[data-theme="dark"] .st-icon-btn-secondary,
            html[data-theme="dark"] .st-pagination .pagination > li > a,
            html[data-theme="dark"] .st-pagination .pagination > li > span {
                background: rgba(15, 23, 42, 0.9);
                border-color: rgba(148, 163, 184, 0.16);
            }
            html[data-theme="dark"] .st-banner,
            html[data-theme="dark"] .st-note-box {
                background: rgba(141, 110, 43, 0.12);
                border-color: rgba(141, 110, 43, 0.18);
            }
            html[data-theme="dark"] .st-banner.is-warning {
                background: rgba(217, 119, 6, 0.12);
                border-color: rgba(217, 119, 6, 0.2);
            }
            html[data-theme="dark"] .st-banner.is-danger {
                background: rgba(220, 38, 38, 0.12);
                border-color: rgba(220, 38, 38, 0.18);
            }
            html[data-theme="dark"] .st-search input,
            html[data-theme="dark"] .st-input,
            html[data-theme="dark"] .st-select,
            html[data-theme="dark"] .st-textarea {
                background: rgba(15, 23, 42, 0.88);
                border-color: rgba(148, 163, 184, 0.16);
            }
            html[data-theme="dark"] .st-table tbody td,
            html[data-theme="dark"] .st-table tfoot th,
            html[data-theme="dark"] .st-table tfoot td,
            html[data-theme="dark"] .st-pagination,
            html[data-theme="dark"] .st-list-card {
                border-color: rgba(148, 163, 184, 0.12);
            }
            html[data-theme="dark"] .st-table tbody tr:hover td { background: rgba(141, 110, 43, 0.08); }
            html[data-theme="dark"] .st-pagination {
                background: linear-gradient(180deg, rgba(30, 41, 59, 0.45) 0%, rgba(17, 24, 39, 0.94) 100%);
            }
            @keyframes stFadeUp {
                from { opacity: 0; transform: translateY(1.1rem); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes stFloat {
                0%, 100% { transform: translate3d(0, 0, 0); }
                50% { transform: translate3d(0, 0.75rem, 0); }
            }
            @media (max-width: 1199px) {
                .st-stat-grid,
                .st-summary-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
                .st-grid-two { grid-template-columns: 1fr; }
                .st-list-head { display: none; }
                .st-row {
                    grid-template-columns: 1fr 1fr;
                    align-items: flex-start;
                }
                .st-row-main,
                .st-action-group { grid-column: 1 / -1; }
                .st-action-group { justify-content: flex-start; }
            }
            @media (max-width: 767px) {
                .st-page { padding: 0 0 1.2rem; }
                .st-hero,
                .st-card {
                    padding: 1.2rem;
                    border-radius: 1.45rem;
                }
                .st-hero-title { font-size: 1.9rem; }
                .st-hero-subtitle,
                .st-form-footer-note,
                .st-empty p { font-size: 0.96rem; }
                .st-hero-actions,
                .st-inline-actions { width: 100%; }
                .st-btn { width: 100%; }
                .st-stat-grid,
                .st-summary-grid,
                .st-type-grid,
                .st-form-grid { grid-template-columns: 1fr; }
                .st-toolbar { padding: 0.9rem; }
                .st-chip-row { width: 100%; }
                .st-chip {
                    flex: 1 1 100%;
                    justify-content: center;
                }
                .st-list-body { padding: 0.5rem; }
                .st-row {
                    grid-template-columns: 1fr;
                    padding: 1rem;
                    margin: 0.6rem 0 0;
                }
                .st-row-main { align-items: flex-start; }
                .st-row-avatar {
                    width: 3.15rem;
                    height: 3.15rem;
                    border-radius: 1rem;
                }
                .st-icon-btn { flex: 1 1 calc(50% - 0.5rem); }
                .st-form-footer {
                    flex-direction: column;
                    align-items: stretch;
                }
                .st-table thead th,
                .st-table tbody td,
                .st-table tfoot th,
                .st-table tfoot td { padding: 0.85rem 0.8rem; }
            }
        </style>
    @endpush
@endonce
