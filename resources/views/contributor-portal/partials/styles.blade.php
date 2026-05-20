@once
    @push('styles')
        <style>
            .cp-page { display: flex; flex-direction: column; gap: 1.25rem; }
            .cp-hero, .cp-card, .cp-stat {
                background: rgba(255,255,255,.96);
                border: 1px solid rgba(170,134,63,.14);
                border-radius: 1.25rem;
                box-shadow: 0 .85rem 1.9rem rgba(15,23,42,.06);
            }
            .cp-hero { padding: 1.5rem; display: flex; justify-content: space-between; gap: 1rem; flex-wrap: wrap; align-items: center; }
            .cp-title { margin: 0; color: var(--text-primary); font-size: 2rem; font-weight: 900; }
            .cp-subtitle { margin: .35rem 0 0; color: var(--text-secondary); line-height: 1.7; }
            .cp-actions { display: flex; gap: .6rem; flex-wrap: wrap; align-items: center; }
            .cp-inline-form { display: inline-flex; gap: .45rem; align-items: center; flex-wrap: wrap; margin: 0; }
            .cp-inline-form .cp-input { width: 8.5rem; min-height: 2.45rem; padding: .5rem .65rem; }
            .cp-btn { display: inline-flex; align-items: center; justify-content: center; gap: .45rem; min-height: 2.7rem; padding: .65rem 1rem; border-radius: .85rem; border: 0; text-decoration: none !important; font-weight: 800; }
            .cp-btn-sm { min-height: 2.35rem; padding: .45rem .75rem; border-radius: .7rem; font-size: .9rem; }
            .cp-btn-primary { background: linear-gradient(135deg, var(--primary-color), #c49b48); color: #fff; }
            .cp-btn-secondary { background: rgba(170,134,63,.08); color: var(--primary-color); border: 1px solid rgba(170,134,63,.14); }
            .cp-btn-danger { background: rgba(220,38,38,.1); color: var(--danger-color); border: 1px solid rgba(220,38,38,.14); }
            .cp-btn-success { background: rgba(22,163,74,.1); color: #15803d; border: 1px solid rgba(22,163,74,.14); }
            .cp-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1rem; }
            .cp-grid-2 { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; }
            .cp-stat { padding: 1rem; }
            .cp-stat i { width: 2.7rem; height: 2.7rem; display: inline-flex; align-items: center; justify-content: center; border-radius: .85rem; background: rgba(170,134,63,.1); color: var(--primary-color); margin-bottom: .7rem; }
            .cp-stat-value { margin: 0; color: var(--text-primary); font-size: 1.65rem; font-weight: 900; }
            .cp-stat-label { margin: .25rem 0 0; color: var(--text-secondary); font-weight: 700; }
            .cp-card { padding: 1.1rem; }
            .cp-card-title { margin: 0 0 .85rem; color: var(--text-primary); font-size: 1.2rem; font-weight: 900; display: flex; align-items: center; gap: .45rem; }
            .cp-section-head { display: flex; align-items: center; justify-content: space-between; gap: .75rem; margin-bottom: .85rem; flex-wrap: wrap; }
            .cp-section-head .cp-card-title { margin: 0; }
            .cp-table-wrap { overflow-x: auto; border: 1px solid rgba(170,134,63,.12); border-radius: 1rem; }
            .cp-table { width: 100%; border-collapse: collapse; }
            .cp-table th, .cp-table td { padding: .85rem; border-bottom: 1px solid rgba(226,232,240,.9); color: var(--text-primary); vertical-align: middle; }
            .cp-table th { background: #fcf7ee; color: var(--text-secondary); white-space: nowrap; }
            .cp-table tr:last-child td { border-bottom: 0; }
            .cp-form { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; }
            .cp-field.full { grid-column: 1 / -1; }
            .cp-label { display: block; margin-bottom: .35rem; color: var(--text-primary); font-weight: 800; }
            .cp-input, .cp-select, .cp-textarea { width: 100%; border: 1px solid rgba(170,134,63,.16); border-radius: .85rem; padding: .75rem .85rem; background: #fff; color: var(--text-primary); }
            .cp-textarea { min-height: 7rem; resize: vertical; }
            .cp-error { color: var(--danger-color); font-weight: 700; display: block; margin-top: .3rem; }
            .cp-empty { padding: 2rem; text-align: center; color: var(--text-secondary); }
            .cp-badge { display: inline-flex; align-items: center; gap: .35rem; padding: .35rem .6rem; border-radius: 999px; background: rgba(170,134,63,.09); color: var(--primary-color); font-weight: 800; white-space: nowrap; }
            .cp-news-list { display: flex; flex-direction: column; gap: .85rem; }
            .cp-news-item { display: grid; grid-template-columns: auto minmax(0, 1fr) auto; gap: .85rem; align-items: center; padding: 1rem; border: 1px solid rgba(170,134,63,.12); border-radius: 1rem; background: #fff; }
            .cp-news-list.compact .cp-news-item { padding: .85rem; }
            .cp-news-icon { width: 3rem; height: 3rem; display: inline-flex; align-items: center; justify-content: center; border-radius: .9rem; background: rgba(170,134,63,.1); color: var(--primary-color); font-size: 1.2rem; }
            .cp-news-body { min-width: 0; }
            .cp-news-title { margin: 0; color: var(--text-primary); font-size: 1.05rem; font-weight: 900; overflow-wrap: anywhere; }
            .cp-news-meta { display: flex; gap: .75rem; flex-wrap: wrap; margin-top: .35rem; color: var(--text-secondary); font-size: .9rem; font-weight: 700; }
            .cp-news-meta span { display: inline-flex; align-items: center; gap: .3rem; max-width: 100%; overflow-wrap: anywhere; }
            .cp-news-excerpt { margin: .55rem 0 0; color: var(--text-secondary); line-height: 1.7; font-weight: 700; overflow-wrap: anywhere; }
            .cp-news-content { line-height: 1.9; color: var(--text-secondary); font-weight: 700; }
            .cp-feature-row { align-items: stretch; }
            .cp-feature-row > .cp-card { min-height: 12.5rem; display: flex; flex-direction: column; }
            .cp-feature-row .cp-news-list { flex: 1; }
            .cp-meeting-card { border-color: rgba(14,165,233,.16); }
            .cp-meeting-summary { display: grid; grid-template-columns: auto minmax(0, 1fr) auto; gap: 1rem; align-items: center; }
            .cp-meeting-date { width: 4.8rem; min-height: 4.8rem; display: inline-flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 1rem; background: rgba(14,165,233,.09); color: #0369a1; flex-shrink: 0; }
            .cp-meeting-date span { font-size: 1.8rem; font-weight: 900; line-height: 1; }
            .cp-meeting-date strong { font-size: .88rem; margin-top: .25rem; }
            .cp-meeting-body { min-width: 0; }
            .cp-meeting-title { margin: 0; color: var(--text-primary); font-size: 1.25rem; font-weight: 900; overflow-wrap: anywhere; }
            .cp-charts-grid { align-items: stretch; }
            .cp-chart-card { min-height: 25rem; display: flex; flex-direction: column; }
            .cp-chart-wrap { position: relative; min-height: 18rem; flex: 1; }
            .cp-chart-wrap canvas { width: 100% !important; height: 100% !important; }
            .cp-member-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; }
            .cp-member-card { padding: 1rem; border: 1px solid rgba(170,134,63,.12); border-radius: 1rem; background: #fff; }
            .cp-member-head { display: flex; align-items: center; gap: .75rem; margin-bottom: .85rem; }
            .cp-avatar { width: 3.2rem; height: 3.2rem; border-radius: 1rem; display: inline-flex; align-items: center; justify-content: center; background: rgba(170,134,63,.12); color: var(--primary-color); font-weight: 900; flex-shrink: 0; overflow: hidden; }
            .cp-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
            .cp-member-name { margin: 0; color: var(--text-primary); font-size: 1.05rem; font-weight: 900; overflow-wrap: anywhere; }
            .cp-member-sub { margin-top: .2rem; color: var(--text-secondary); font-weight: 700; overflow-wrap: anywhere; }
            @media (max-width: 1000px) { .cp-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } .cp-grid-2 { grid-template-columns: 1fr; } }
            @media (max-width: 1000px) { .cp-member-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
            @media (max-width: 680px) { .cp-grid, .cp-form, .cp-member-grid { grid-template-columns: 1fr; } .cp-hero { align-items: stretch; } .cp-btn { width: 100%; } .cp-news-item, .cp-meeting-summary { grid-template-columns: 1fr; } .cp-news-icon { width: 2.8rem; height: 2.8rem; } .cp-meeting-date { width: 100%; min-height: 4rem; } .cp-chart-card { min-height: 23rem; } }
        </style>
    @endpush
@endonce
