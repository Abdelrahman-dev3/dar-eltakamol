@once
    @push('styles')
        <style>
            .ss-page { padding: 0.5rem 0 2rem; }
            .ss-shell { display: flex; flex-direction: column; gap: 1.5rem; }
            .ss-hero {
                position: relative;
                overflow: hidden;
                border-radius: 2rem;
                padding: 2rem;
                background:
                    radial-gradient(circle at top left, rgba(34, 197, 94, 0.18), transparent 34%),
                    linear-gradient(135deg, #f4fbf5 0%, #ffffff 45%, #edf7f0 100%);
                border: 1px solid rgba(34, 197, 94, 0.12);
                box-shadow: 0 1.5rem 3rem rgba(15, 23, 42, 0.08);
                animation: ssFadeUp 0.7s ease both;
            }
            .ss-hero::before,
            .ss-hero::after {
                content: "";
                position: absolute;
                border-radius: 999px;
                pointer-events: none;
            }
            .ss-hero::before {
                width: 14rem;
                height: 14rem;
                top: -5rem;
                inset-inline-end: -3rem;
                background: rgba(34, 197, 94, 0.09);
                animation: ssFloat 8s ease-in-out infinite;
            }
            .ss-hero::after {
                width: 10rem;
                height: 10rem;
                bottom: -4rem;
                inset-inline-start: -2rem;
                background: rgba(170, 134, 63, 0.12);
                animation: ssFloat 10s ease-in-out infinite reverse;
            }
            .ss-hero-inner {
                position: relative;
                z-index: 1;
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 1rem;
                flex-wrap: wrap;
            }
            .ss-badge-top {
                display: inline-flex;
                align-items: center;
                gap: 0.55rem;
                margin-bottom: 0.9rem;
                padding: 0.7rem 1rem;
                border-radius: 999px;
                background: rgba(34, 197, 94, 0.1);
                color: var(--success-color);
                font-size: 1rem;
                font-weight: 800;
            }
            .ss-title {
                margin: 0;
                color: var(--text-primary);
                font-size: clamp(2.1rem, 4vw, 3.2rem);
                font-weight: 900;
                line-height: 1.15;
            }
            .ss-subtitle {
                margin: 0.85rem 0 0;
                max-width: 50rem;
                color: var(--text-secondary);
                font-size: 1.08rem;
                line-height: 1.9;
            }
            .ss-actions,
            .ss-inline-actions,
            .ss-chip-row {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                flex-wrap: wrap;
            }
            .ss-btn,
            .ss-icon-btn {
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
            .ss-btn {
                min-height: 3.35rem;
                padding: 0.85rem 1.25rem;
                border-radius: 1.1rem;
                font-size: 1rem;
            }
            .ss-icon-btn {
                min-width: 2.9rem;
                min-height: 2.9rem;
                padding: 0.75rem 0.95rem;
                border-radius: 1rem;
                font-size: 0.96rem;
            }
            .ss-btn:hover,
            .ss-icon-btn:hover { transform: translateY(-0.125rem); opacity: 0.98; }
            .ss-btn-primary {
                background: linear-gradient(135deg, var(--primary-color), #c49b48);
                color: #fff;
                box-shadow: 0 1rem 2rem rgba(170, 134, 63, 0.2);
            }
            .ss-btn-primary:hover { color: #fff; }
            .ss-btn-secondary,
            .ss-icon-btn-secondary {
                background: rgba(255,255,255,0.92);
                color: var(--text-primary);
                border: 1px solid rgba(170, 134, 63, 0.16);
            }
            .ss-btn-info,
            .ss-icon-btn-info {
                background: rgba(14, 165, 233, 0.12);
                color: #0284c7;
                border: 1px solid rgba(14, 165, 233, 0.12);
            }
            .ss-btn-warning,
            .ss-icon-btn-warning {
                background: rgba(217, 119, 6, 0.12);
                color: var(--warning-color);
                border: 1px solid rgba(217, 119, 6, 0.12);
            }
            .ss-btn-danger,
            .ss-icon-btn-danger {
                background: rgba(220, 38, 38, 0.1);
                color: var(--danger-color);
                border: 1px solid rgba(220, 38, 38, 0.1);
            }
            .ss-btn-success,
            .ss-icon-btn-success {
                background: rgba(5, 150, 105, 0.12);
                color: var(--success-color);
                border: 1px solid rgba(5, 150, 105, 0.12);
            }
            .ss-stat-grid,
            .ss-summary-grid,
            .ss-form-grid,
            .ss-grid-two,
            .ss-grid-three {
                display: grid;
                gap: 1rem;
            }
            .ss-stat-grid,
            .ss-summary-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
            .ss-grid-two {
                grid-template-columns: minmax(0, 1.55fr) minmax(19rem, 0.95fr);
                align-items: start;
            }
            .ss-grid-three { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .ss-form-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .ss-card,
            .ss-stat-card,
            .ss-summary-card,
            .ss-empty,
            .ss-row,
            .ss-toolbar,
            .ss-table-wrap {
                background: rgba(255, 255, 255, 0.96);
                border: 1px solid rgba(170, 134, 63, 0.14);
                box-shadow: 0 1rem 2.75rem rgba(15, 23, 42, 0.06);
                animation: ssFadeUp 0.78s ease both;
            }
            .ss-stat-card,
            .ss-summary-card {
                position: relative;
                overflow: hidden;
                padding: 1.35rem 1.25rem;
                border-radius: 1.5rem;
            }
            .ss-stat-card::after,
            .ss-summary-card::after {
                content: "";
                position: absolute;
                width: 6.2rem;
                height: 6.2rem;
                border-radius: 50%;
                inset-inline-end: -1.35rem;
                top: -2.5rem;
                background: rgba(34, 197, 94, 0.08);
            }
            .ss-stat-icon,
            .ss-summary-icon,
            .ss-card-icon {
                width: 3.25rem;
                height: 3.25rem;
                border-radius: 1rem;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, rgba(170, 134, 63, 0.16), rgba(34, 197, 94, 0.08));
                color: var(--primary-color);
                font-size: 1.35rem;
                flex-shrink: 0;
            }
            .ss-stat-icon,
            .ss-summary-icon { margin-bottom: 1rem; }
            .ss-stat-value,
            .ss-summary-value {
                margin: 0;
                color: var(--text-primary);
                font-size: clamp(1.7rem, 2.5vw, 2.15rem);
                font-weight: 900;
                line-height: 1.1;
            }
            .ss-stat-label,
            .ss-summary-label {
                margin: 0.45rem 0 0;
                color: var(--text-secondary);
                font-size: 0.98rem;
                font-weight: 700;
                line-height: 1.75;
            }
            .ss-card {
                border-radius: 1.8rem;
                padding: 1.55rem;
            }
            .ss-card-header {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 0.9rem;
                flex-wrap: wrap;
                margin-bottom: 1.25rem;
            }
            .ss-card-title-wrap { display: flex; align-items: flex-start; gap: 0.9rem; }
            .ss-card-title {
                margin: 0;
                color: var(--text-primary);
                font-size: 1.38rem;
                font-weight: 900;
                line-height: 1.3;
            }
            .ss-card-subtitle {
                margin: 0.35rem 0 0;
                color: var(--text-secondary);
                font-size: 0.96rem;
                line-height: 1.8;
            }
            .ss-toolbar,
            .ss-table-wrap,
            .ss-chip,
            .ss-info-item,
            .ss-note-box,
            .ss-availability-card,
            .ss-empty {
                border-radius: 1.35rem;
                border: 1px solid rgba(170, 134, 63, 0.12);
            }
            .ss-toolbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                flex-wrap: wrap;
                padding: 1rem 1.1rem;
            }
            .ss-search { position: relative; flex: 1 1 22rem; }
            .ss-search i {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                inset-inline-start: 1rem;
                color: var(--text-light);
            }
            .ss-search input,
            .ss-input,
            .ss-select,
            .ss-textarea {
                width: 100%;
                border-radius: 1rem;
                border: 1px solid rgba(170, 134, 63, 0.16);
                background: #fff;
                color: var(--text-primary);
                transition: border-color 0.22s ease, box-shadow 0.22s ease, background-color 0.22s ease;
            }
            .ss-search input {
                min-height: 3.45rem;
                padding-inline-start: 2.9rem;
                padding-inline-end: 1rem;
                font-size: 1rem;
            }
            .ss-input,
            .ss-select,
            .ss-textarea {
                min-height: 3.4rem;
                padding: 0.9rem 1rem;
                font-size: 1rem;
            }
            .ss-textarea { min-height: 8rem; resize: vertical; }
            .ss-search input:focus,
            .ss-input:focus,
            .ss-select:focus,
            .ss-textarea:focus {
                outline: none;
                border-color: rgba(170, 134, 63, 0.42);
                box-shadow: 0 0 0 0.3rem rgba(170, 134, 63, 0.1);
            }
            .ss-form-field { display: flex; flex-direction: column; gap: 0.5rem; min-width: 0; }
            .ss-form-field.full { grid-column: 1 / -1; }
            .ss-label {
                color: var(--text-primary);
                font-size: 0.98rem;
                font-weight: 800;
            }
            .ss-required { color: var(--danger-color); }
            .ss-help {
                color: var(--text-secondary);
                font-size: 0.88rem;
                line-height: 1.75;
            }
            .ss-error {
                color: var(--danger-color);
                font-size: 0.88rem;
                font-weight: 700;
            }
            .ss-form-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                flex-wrap: wrap;
                margin-top: 1.4rem;
                padding-top: 1.3rem;
                border-top: 1px solid rgba(170, 134, 63, 0.12);
            }
            .ss-form-footer-note {
                margin: 0;
                flex: 1 1 18rem;
                color: var(--text-secondary);
                font-size: 0.95rem;
                line-height: 1.85;
            }
            .ss-chip {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                min-height: 2.8rem;
                padding: 0.75rem 1rem;
                background: #f8f5ed;
                color: var(--text-secondary);
                font-size: 0.94rem;
                font-weight: 800;
            }
            .ss-info-list { display: grid; gap: 0.9rem; }
            .ss-info-item {
                padding: 1rem 1.05rem;
                background: rgba(248, 245, 237, 0.7);
            }
            .ss-info-label {
                display: block;
                margin-bottom: 0.4rem;
                color: var(--text-light);
                font-size: 0.88rem;
                font-weight: 700;
            }
            .ss-info-value {
                color: var(--text-primary);
                font-size: 1.02rem;
                font-weight: 800;
                line-height: 1.65;
                word-break: break-word;
            }
            .ss-info-value.muted { color: var(--text-secondary); font-weight: 700; }
            .ss-note-box {
                display: flex;
                align-items: flex-start;
                gap: 0.85rem;
                padding: 1rem 1.1rem;
                background: rgba(170, 134, 63, 0.08);
                color: var(--text-secondary);
                line-height: 1.8;
            }
            .ss-note-box i {
                color: var(--primary-color);
                font-size: 1.15rem;
                margin-top: 0.15rem;
            }
            .ss-note-box.success {
                background: rgba(5, 150, 105, 0.1);
                border-color: rgba(5, 150, 105, 0.16);
            }
            .ss-note-box.success i { color: var(--success-color); }
            .ss-note-box.warning {
                background: rgba(217, 119, 6, 0.1);
                border-color: rgba(217, 119, 6, 0.16);
            }
            .ss-note-box.warning i { color: var(--warning-color); }
            .ss-note-box.danger {
                background: rgba(220, 38, 38, 0.08);
                border-color: rgba(220, 38, 38, 0.16);
            }
            .ss-note-box.danger i { color: var(--danger-color); }
            .ss-list-card {
                border-radius: 1.9rem;
                background: rgba(255, 255, 255, 0.96);
                border: 1px solid rgba(170, 134, 63, 0.14);
                box-shadow: 0 1rem 2.75rem rgba(15, 23, 42, 0.07);
                overflow: hidden;
                animation: ssFadeUp 0.92s ease both;
            }
            .ss-list-head,
            .ss-row {
                display: grid;
                grid-template-columns: minmax(16rem, 2.1fr) minmax(8rem, 0.9fr) minmax(8rem, 0.9fr) minmax(8.5rem, 0.9fr) minmax(14rem, 1.3fr);
                align-items: center;
                gap: 1rem;
            }
            .ss-list-head {
                padding: 1.1rem 1.35rem;
                background: linear-gradient(180deg, #fff9ef 0%, #fcf7ee 100%);
                color: var(--text-secondary);
                font-size: 0.95rem;
                font-weight: 800;
                border-bottom: 1px solid rgba(170, 134, 63, 0.12);
            }
            .ss-list-card .ss-list-head { display: none; }
            .ss-list-body { padding: 0.75rem 0.75rem 1rem; }
            #sellSharesList {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 1rem;
                padding: 1rem;
            }
            #sellSharesList .ss-row {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                align-items: stretch;
                gap: 0.7rem;
                min-height: 100%;
                margin: 0;
                padding: 0.95rem;
                border-radius: 1.15rem;
                box-shadow: 0 0.65rem 1.35rem rgba(15, 23, 42, 0.055);
            }
            #sellSharesList .ss-row-main {
                display: grid;
                grid-template-columns: auto minmax(0, 1fr);
                align-items: center;
                gap: 0.75rem;
                grid-column: 1 / -1;
            }
            #sellSharesList .ss-row-title {
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                font-size: 1rem;
            }
            #sellSharesList .ss-row .ss-actions {
                margin-top: 0;
                padding-top: 0.6rem;
                border-top: 1px solid rgba(170, 134, 63, 0.10);
                justify-content: flex-start;
                gap: 0.45rem;
                grid-column: 1 / -1;
            }
            #sellSharesList .ss-field {
                padding: 0.65rem 0.7rem;
                border-radius: 0.8rem;
                background: rgba(248, 245, 237, 0.46);
                border: 1px solid rgba(170, 134, 63, 0.08);
                gap: 0.2rem;
            }
            #sellSharesList .ss-field[style] {
                grid-column: 1 / -1 !important;
                padding: 0.7rem 0 0;
                border: 0;
                border-top: 1px solid rgba(170, 134, 63, 0.08);
                border-radius: 0;
                background: transparent;
            }
            #sellSharesList .ss-row-avatar {
                width: 2.8rem;
                height: 2.8rem;
                border-radius: 0.85rem;
                font-size: 0.95rem;
                box-shadow: 0 0.65rem 1.1rem rgba(170, 134, 63, 0.14);
            }
            #sellSharesList .ss-row-meta {
                margin-top: 0.25rem;
                gap: 0.35rem;
            }
            #sellSharesList .ss-chip {
                min-height: 2.15rem;
                padding: 0.45rem 0.65rem;
                font-size: 0.82rem;
            }
            #sellSharesList .ss-pill {
                padding: 0.4rem 0.62rem;
                font-size: 0.78rem;
            }
            #sellSharesList .ss-field-label {
                font-size: 0.76rem;
            }
            #sellSharesList .ss-field-value {
                font-size: 0.9rem;
                line-height: 1.45;
            }
            #sellSharesList .ss-help {
                font-size: 0.8rem;
                line-height: 1.55;
            }
            #sellSharesList .ss-icon-btn {
                min-width: 2.35rem;
                min-height: 2.35rem;
                padding: 0.55rem 0.65rem;
                border-radius: 0.75rem;
                font-size: 0.85rem;
            }
            #sellSharesList .ss-row:hover {
                transform: translateY(-0.1rem);
                box-shadow: 0 0.85rem 1.55rem rgba(15, 23, 42, 0.075);
            }
            #sellSharesList .ss-row.is-hidden { display: none; }
            .ss-row {
                margin: 0.75rem 0.75rem 0;
                padding: 1.2rem;
                border-radius: 1.45rem;
                background: #fff;
                border: 1px solid rgba(226, 232, 240, 0.95);
                transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
            }
            .ss-row:hover {
                transform: translateY(-0.2rem);
                border-color: rgba(170, 134, 63, 0.2);
                box-shadow: 0 1.25rem 2rem rgba(15, 23, 42, 0.08);
            }
            .ss-row.is-hidden { display: none; }
            .ss-row-main { display: flex; align-items: center; gap: 0.9rem; min-width: 0; }
            .ss-row-avatar {
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
            .ss-row-title {
                margin: 0;
                color: var(--text-primary);
                font-size: 1.1rem;
                font-weight: 900;
                line-height: 1.4;
            }
            .ss-row-meta {
                margin-top: 0.35rem;
                display: flex;
                align-items: center;
                gap: 0.45rem;
                flex-wrap: wrap;
            }
            .ss-field { display: flex; flex-direction: column; gap: 0.35rem; min-width: 0; }
            .ss-field-label {
                color: var(--text-light);
                font-size: 0.84rem;
                font-weight: 700;
            }
            .ss-field-value {
                color: var(--text-primary);
                font-size: 0.98rem;
                font-weight: 800;
                line-height: 1.65;
                word-break: break-word;
            }
            .ss-field-value.muted { color: var(--text-secondary); font-weight: 700; }
            .ss-pill {
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
                padding: 0.5rem 0.8rem;
                border-radius: 999px;
                font-size: 0.85rem;
                font-weight: 900;
            }
            .ss-pill-initial { background: rgba(148, 163, 184, 0.14); color: var(--text-secondary); }
            .ss-pill-active { background: rgba(5, 150, 105, 0.12); color: var(--success-color); }
            .ss-pill-completed { background: rgba(14, 165, 233, 0.12); color: #0284c7; }
            .ss-pill-cancelled { background: rgba(220, 38, 38, 0.1); color: var(--danger-color); }
            .ss-pill-pending { background: rgba(217, 119, 6, 0.12); color: var(--warning-color); }
            .ss-table-wrap { overflow: hidden; background: rgba(255,255,255,0.92); }
            .ss-table-scroll { overflow-x: auto; }
            .ss-table {
                width: 100%;
                margin: 0;
                border-collapse: separate;
                border-spacing: 0;
            }
            .ss-table thead th {
                padding: 1rem;
                background: linear-gradient(180deg, #fff9ef 0%, #fcf7ee 100%);
                color: var(--text-secondary);
                font-size: 0.92rem;
                font-weight: 900;
                white-space: nowrap;
                border-bottom: 1px solid rgba(170, 134, 63, 0.12);
            }
            .ss-table tbody td,
            .ss-table tfoot td,
            .ss-table tfoot th {
                padding: 1rem;
                color: var(--text-primary);
                font-size: 0.96rem;
                vertical-align: middle;
                border-bottom: 1px solid rgba(226, 232, 240, 0.9);
            }
            .ss-table tbody tr:hover td { background: rgba(170, 134, 63, 0.03); }
            .ss-table tbody tr:last-child td { border-bottom: 0; }
            .ss-table tfoot td,
            .ss-table tfoot th {
                background: rgba(170, 134, 63, 0.06);
                font-weight: 900;
            }
            .ss-text-center { text-align: center; }
            .ss-text-right { text-align: right; }
            .ss-empty {
                display: none;
                padding: 3rem 1.5rem 3.4rem;
                text-align: center;
                border-radius: 1.8rem;
            }
            .ss-empty.show { display: block; }
            .ss-empty-icon {
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
            .ss-empty h3 {
                margin: 0 0 0.55rem;
                color: var(--text-primary);
                font-size: 1.6rem;
                font-weight: 900;
            }
            .ss-empty p {
                margin: 0;
                color: var(--text-secondary);
                font-size: 1rem;
                line-height: 1.85;
            }
            .ss-pagination {
                padding: 1.25rem 1.4rem;
                border-top: 1px solid rgba(170, 134, 63, 0.1);
                background: linear-gradient(180deg, rgba(255, 249, 239, 0.35) 0%, rgba(255, 255, 255, 0.92) 100%);
            }
            .ss-pagination .pagination { margin: 0; }
            .ss-pagination .pagination > li > a,
            .ss-pagination .pagination > li > span {
                border-radius: 0.9rem !important;
                margin: 0 0.2rem;
                border: 1px solid rgba(170, 134, 63, 0.14);
                color: var(--text-primary);
                min-width: 2.8rem;
                text-align: center;
                background: rgba(255, 255, 255, 0.92);
            }
            .ss-pagination .pagination > .active > span,
            .ss-pagination .pagination > .active > span:hover,
            .ss-pagination .pagination > .active > span:focus,
            .ss-pagination .pagination > .active > a,
            .ss-pagination .pagination > .active > a:hover,
            .ss-pagination .pagination > .active > a:focus {
                background: linear-gradient(135deg, var(--primary-color), #c49b48);
                border-color: transparent;
                color: #fff;
            }
            .ss-availability-grid { display: grid; gap: 0.75rem; }
            .ss-availability-card {
                padding: 0.95rem 1rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 0.75rem;
                background: rgba(248, 245, 237, 0.72);
            }
            .ss-availability-label {
                color: var(--text-secondary);
                font-size: 0.9rem;
                font-weight: 700;
            }
            .ss-availability-value {
                color: var(--text-primary);
                font-size: 1.05rem;
                font-weight: 900;
            }
            html[data-theme="dark"] .ss-hero {
                background:
                    radial-gradient(circle at top left, rgba(34, 197, 94, 0.14), transparent 34%),
                    linear-gradient(135deg, rgba(17, 24, 39, 0.98) 0%, rgba(15, 23, 42, 0.98) 46%, rgba(30, 41, 59, 0.96) 100%);
                border-color: rgba(148, 163, 184, 0.15);
            }
            html[data-theme="dark"] .ss-card,
            html[data-theme="dark"] .ss-stat-card,
            html[data-theme="dark"] .ss-summary-card,
            html[data-theme="dark"] .ss-list-card,
            html[data-theme="dark"] .ss-empty,
            html[data-theme="dark"] .ss-row,
            html[data-theme="dark"] .ss-toolbar,
            html[data-theme="dark"] .ss-table-wrap {
                background: rgba(17, 24, 39, 0.94);
                border-color: rgba(148, 163, 184, 0.16);
                box-shadow: 0 1.2rem 2.6rem rgba(2, 6, 23, 0.35);
            }
            html[data-theme="dark"] .ss-list-head,
            html[data-theme="dark"] .ss-table thead th {
                background: linear-gradient(180deg, rgba(30, 41, 59, 0.95) 0%, rgba(17, 24, 39, 0.98) 100%);
                border-color: rgba(148, 163, 184, 0.12);
            }
            html[data-theme="dark"] .ss-input,
            html[data-theme="dark"] .ss-select,
            html[data-theme="dark"] .ss-textarea,
            html[data-theme="dark"] .ss-search input,
            html[data-theme="dark"] .ss-info-item,
            html[data-theme="dark"] .ss-availability-card,
            html[data-theme="dark"] .ss-chip,
            html[data-theme="dark"] .ss-btn-secondary,
            html[data-theme="dark"] .ss-icon-btn-secondary,
            html[data-theme="dark"] .ss-pagination .pagination > li > a,
            html[data-theme="dark"] .ss-pagination .pagination > li > span {
                background: rgba(15, 23, 42, 0.88);
                border-color: rgba(148, 163, 184, 0.16);
            }
            html[data-theme="dark"] .ss-note-box {
                background: rgba(141, 110, 43, 0.12);
                border-color: rgba(141, 110, 43, 0.18);
            }
            html[data-theme="dark"] .ss-note-box.success {
                background: rgba(5, 150, 105, 0.12);
                border-color: rgba(5, 150, 105, 0.18);
            }
            html[data-theme="dark"] .ss-note-box.warning {
                background: rgba(217, 119, 6, 0.12);
                border-color: rgba(217, 119, 6, 0.18);
            }
            html[data-theme="dark"] .ss-note-box.danger {
                background: rgba(220, 38, 38, 0.12);
                border-color: rgba(220, 38, 38, 0.18);
            }
            @keyframes ssFadeUp {
                from { opacity: 0; transform: translateY(1.1rem); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes ssFloat {
                0%, 100% { transform: translate3d(0,0,0); }
                50% { transform: translate3d(0, 0.75rem, 0); }
            }
            @media (max-width: 1199px) {
                .ss-stat-grid,
                .ss-summary-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
                .ss-grid-two,
                .ss-grid-three { grid-template-columns: 1fr; }
                .ss-list-head { display: none; }
                #sellSharesList { grid-template-columns: repeat(2, minmax(0, 1fr)); }
                .ss-row {
                    grid-template-columns: 1fr 1fr;
                    align-items: flex-start;
                }
                .ss-row-main,
                .ss-row .ss-actions { grid-column: 1 / -1; }
            }
            @media (max-width: 767px) {
                .ss-page { padding: 0 0 1.2rem; }
                .ss-hero,
                .ss-card { padding: 1.2rem; border-radius: 1.45rem; }
                .ss-title { font-size: 1.9rem; }
                .ss-subtitle,
                .ss-form-footer-note,
                .ss-empty p { font-size: 0.96rem; }
                .ss-actions,
                .ss-inline-actions { width: 100%; }
                .ss-btn { width: 100%; }
                .ss-stat-grid,
                .ss-summary-grid,
                .ss-form-grid { grid-template-columns: 1fr; }
                .ss-chip-row { width: 100%; }
                .ss-chip { flex: 1 1 100%; justify-content: center; }
                .ss-list-body { padding: 0.5rem; }
                #sellSharesList {
                    grid-template-columns: 1fr;
                    padding: 0.75rem;
                    gap: 0.75rem;
                }
                #sellSharesList .ss-row {
                    grid-template-columns: 1fr;
                    padding: 0.85rem;
                    gap: 0.6rem;
                }
                .ss-row {
                    grid-template-columns: 1fr;
                    padding: 1rem;
                    margin: 0.6rem 0 0;
                }
                .ss-row-avatar {
                    width: 3.15rem;
                    height: 3.15rem;
                    border-radius: 1rem;
                }
                .ss-icon-btn { flex: 1 1 calc(50% - 0.5rem); }
                .ss-form-footer { flex-direction: column; align-items: stretch; }
            }
        </style>
    @endpush
@endonce
