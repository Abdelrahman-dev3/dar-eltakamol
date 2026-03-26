@extends('layouts.app')

@section('title', __('الحجوزات'))

@php
    $statusLabels = \App\Models\Booking::getStatuses();
    $totalBookings = $bookings->count();
    $confirmedBookings = $bookings->where('status', 'confirmed')->count();
    $pendingBookings = $bookings->where('status', 'pending')->count();
    $todayBookings = $bookings->filter(fn ($booking) => optional($booking->booking_date)?->isToday())->count();
@endphp

@push('styles')
<style>
    .book-page { padding: 8px 0 28px; color: var(--text-primary); font-size: 1rem; }
    .book-shell { display: flex; flex-direction: column; gap: 24px; }
    .book-hero { position: relative; overflow: hidden; border-radius: 28px; padding: 30px; background: radial-gradient(circle at top left, rgba(196, 168, 90, 0.32), transparent 34%), linear-gradient(135deg, #fffaf0 0%, #ffffff 48%, #f5efe2 100%); border: 1px solid rgba(170, 134, 63, 0.16); box-shadow: 0 18px 48px rgba(15, 23, 42, 0.08); }
    .book-hero-inner { position: relative; z-index: 1; display: flex; align-items: center; justify-content: space-between; gap: 18px; flex-wrap: wrap; }
    .book-badge { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 12px; padding: 8px 14px; border-radius: 999px; background: rgba(170, 134, 63, 0.1); color: var(--primary-color); font-size: 1rem; font-weight: 800; }
    .book-title { margin: 0; font-size: clamp(2rem, 3vw, 2.7rem); font-weight: 900; color: var(--text-primary); line-height: 1.2; }
    .book-subtitle { margin: 12px 0 0; max-width: 780px; color: var(--text-secondary); font-size: 1.06rem; line-height: 1.9; }
    .book-actions { display: flex; gap: 12px; flex-wrap: wrap; }
    .book-primary-btn, .book-secondary-btn, .book-action-btn { display: inline-flex; align-items: center; justify-content: center; gap: 10px; border: 0; text-decoration: none !important; font-weight: 800; transition: transform 0.25s ease, box-shadow 0.25s ease; }
    .book-primary-btn, .book-secondary-btn { min-height: 52px; padding: 14px 20px; border-radius: 18px; font-size: 1rem; }
    .book-primary-btn { background: linear-gradient(135deg, var(--primary-color), #c49b48); color: #fff !important; box-shadow: 0 18px 28px rgba(170, 134, 63, 0.24); }
    .book-secondary-btn { background: rgba(255, 255, 255, 0.9); color: var(--text-primary) !important; border: 1px solid rgba(170, 134, 63, 0.16); }
    .book-primary-btn:hover, .book-secondary-btn:hover, .book-action-btn:hover { transform: translateY(-2px); }
    .book-stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; }
    .book-stat-card, .book-toolbar, .book-list-card { border-radius: 24px; background: rgba(255, 255, 255, 0.96); border: 1px solid rgba(170, 134, 63, 0.14); box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06); }
    .book-stat-card { padding: 22px 20px; }
    .book-stat-icon { width: 52px; height: 52px; border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; background: linear-gradient(135deg, rgba(170, 134, 63, 0.16), rgba(170, 134, 63, 0.06)); color: var(--primary-color); font-size: 1.4rem; margin-bottom: 16px; }
    .book-stat-value { margin: 0; font-size: 2rem; font-weight: 900; color: var(--text-primary); }
    .book-stat-label { margin: 6px 0 0; color: var(--text-secondary); font-size: 1rem; font-weight: 700; }
    .book-toolbar { display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; padding: 18px 20px; }
    .book-search { position: relative; flex: 1 1 340px; }
    .book-search i { position: absolute; top: 50%; inset-inline-start: 16px; transform: translateY(-50%); color: var(--text-light); font-size: 1rem; }
    .book-search input { width: 100%; height: 54px; padding-inline-start: 46px; padding-inline-end: 18px; border-radius: 18px; border: 1px solid rgba(170, 134, 63, 0.16); background: #fff; color: var(--text-primary); font-size: 1rem; }
    .book-toolbar-meta { display: flex; gap: 10px; flex-wrap: wrap; }
    .book-chip { display: inline-flex; align-items: center; gap: 8px; min-height: 44px; padding: 10px 14px; border-radius: 14px; background: #f8f5ed; border: 1px solid rgba(170, 134, 63, 0.14); color: var(--text-secondary); font-size: 0.96rem; font-weight: 700; }
    .book-list-card { overflow: hidden; }
    .book-list-body { padding: 14px; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
    .book-card { border-radius: 24px; background: #fff; border: 1px solid rgba(226, 232, 240, 0.92); padding: 22px; transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease; }
    .book-card:hover { transform: translateY(-3px); border-color: rgba(170, 134, 63, 0.22); box-shadow: 0 18px 34px rgba(15, 23, 42, 0.08); }
    .book-card.is-hidden { display: none; }
    .book-card-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 18px; }
    .book-card-main { display: flex; gap: 14px; align-items: flex-start; min-width: 0; }
    .book-avatar { width: 54px; height: 54px; border-radius: 18px; display: inline-flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--primary-color), #cba55c); color: #fff; font-size: 1.2rem; box-shadow: 0 12px 24px rgba(170, 134, 63, 0.18); flex-shrink: 0; }
    .book-name { margin: 0; color: var(--text-primary); font-size: 1.15rem; font-weight: 900; line-height: 1.5; }
    .book-subline { margin-top: 6px; color: var(--text-secondary); font-size: 0.94rem; line-height: 1.7; }
    .book-status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 8px 12px; border-radius: 999px; font-size: 0.85rem; font-weight: 800; white-space: nowrap; }
    .book-status-badge.pending { background: rgba(245, 158, 11, 0.12); color: #b45309; }
    .book-status-badge.confirmed { background: rgba(34, 197, 94, 0.12); color: #15803d; }
    .book-status-badge.cancelled { background: rgba(239, 68, 68, 0.12); color: #b91c1c; }
    .book-status-badge.completed { background: rgba(14, 165, 233, 0.12); color: #0369a1; }
    .book-status-badge.no_show { background: rgba(100, 116, 139, 0.14); color: #475569; }
    .book-status-badge.rescheduled { background: rgba(139, 92, 246, 0.12); color: #6d28d9; }
    .book-detail-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; margin-bottom: 18px; }
    .book-detail-box { border-radius: 18px; background: rgba(248, 250, 252, 0.92); border: 1px solid rgba(170, 134, 63, 0.1); padding: 14px; }
    .book-detail-label { display: block; color: var(--text-light); font-size: 0.84rem; font-weight: 700; margin-bottom: 6px; }
    .book-detail-value { color: var(--text-primary); font-size: 0.98rem; font-weight: 800; line-height: 1.6; }
    .book-notes { margin-bottom: 18px; padding: 14px; border-radius: 18px; background: rgba(248, 250, 252, 0.92); border: 1px solid rgba(170, 134, 63, 0.1); color: var(--text-secondary); line-height: 1.8; }
    .book-notes strong { color: var(--text-primary); display: block; margin-bottom: 4px; }
    .book-card-actions { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
    .book-status-toggle { display: inline-flex; align-items: center; gap: 10px; color: var(--text-secondary); font-size: 0.92rem; font-weight: 700; }
    .book-toggle { position: relative; width: 58px; height: 32px; display: inline-block; }
    .book-toggle-input { display: none; }
    .book-toggle-label { position: absolute; inset: 0; border-radius: 999px; background: rgba(148, 163, 184, 0.45); cursor: pointer; transition: background 0.2s ease; }
    .book-toggle-label::before { content: ""; position: absolute; top: 3px; left: 3px; width: 26px; height: 26px; border-radius: 50%; background: #fff; box-shadow: 0 6px 16px rgba(15, 23, 42, 0.18); transition: transform 0.2s ease; }
    .book-toggle-input:checked + .book-toggle-label { background: #22c55e; }
    .book-toggle-input:checked + .book-toggle-label::before { transform: translateX(26px); }
    .book-action-stack { display: flex; gap: 10px; flex-wrap: wrap; }
    .book-action-btn { min-height: 44px; padding: 0 14px; border-radius: 14px; font-size: 0.95rem; }
    .book-action-btn.edit { background: rgba(217, 119, 6, 0.12); color: #b45309; }
    .book-action-btn.delete { background: rgba(220, 38, 38, 0.1); color: var(--danger-color); }
    .book-empty { display: none; padding: 48px 24px 54px; text-align: center; }
    .book-empty.show { display: block; }
    .book-empty-icon { width: 74px; height: 74px; margin: 0 auto 16px; border-radius: 22px; display: flex; align-items: center; justify-content: center; background: rgba(170, 134, 63, 0.12); color: var(--primary-color); font-size: 1.8rem; }
    html[data-theme="dark"] .book-hero { background: radial-gradient(circle at top left, rgba(141, 110, 43, 0.28), transparent 34%), linear-gradient(135deg, rgba(17, 24, 39, 0.98) 0%, rgba(15, 23, 42, 0.98) 48%, rgba(30, 41, 59, 0.98) 100%); border-color: rgba(148, 163, 184, 0.14); box-shadow: 0 24px 52px rgba(2, 6, 23, 0.34); }
    html[data-theme="dark"] .book-secondary-btn, html[data-theme="dark"] .book-stat-card, html[data-theme="dark"] .book-toolbar, html[data-theme="dark"] .book-list-card, html[data-theme="dark"] .book-card, html[data-theme="dark"] .book-chip, html[data-theme="dark"] .book-detail-box, html[data-theme="dark"] .book-notes, html[data-theme="dark"] .book-empty { background: rgba(15, 23, 42, 0.92); border-color: rgba(148, 163, 184, 0.14); color: var(--text-primary); box-shadow: 0 18px 38px rgba(2, 6, 23, 0.28); }
    html[data-theme="dark"] .book-search input { background: rgba(15, 23, 42, 0.95); border-color: rgba(148, 163, 184, 0.2); color: var(--text-primary); }
    html[data-theme="dark"] .book-badge { background: rgba(141, 110, 43, 0.16); color: #f6deb0; }
    @media (max-width: 1199px) { .book-stats, .book-list-body { grid-template-columns: 1fr; } }
    @media (max-width: 767px) { .book-detail-grid { grid-template-columns: 1fr; } .book-card-actions { flex-direction: column; align-items: stretch; } }
</style>
@endpush

@section('content')
<div class="book-page">
    <div class="book-shell">
        <section class="book-hero">
            <div class="book-hero-inner">
                <div>
                    <span class="book-badge">
                        <i class="bi bi-calendar2-week-fill"></i>
                        {{ __('إدارة الحجوزات') }}
                    </span>
                    <h1 class="book-title">{{ __('الحجوزات') }}</h1>
                    <p class="book-subtitle">{{ __('استعرض كل الحجوزات الحالية، وابحث بينها، وعدّل حالتها بسرعة أو افتح الحجز لتحديث بياناته من نفس الواجهة الحديثة.') }}</p>
                </div>

                <div class="book-actions">
                    <a href="{{ route('bookings.create') }}" class="book-primary-btn">
                        <i class="bi bi-plus-circle-fill"></i>
                        {{ __('إضافة حجز جديد') }}
                    </a>
                    <a href="{{ route('dashboard') }}" class="book-secondary-btn">
                        <i class="bi bi-grid-1x2-fill"></i>
                        {{ __('العودة إلى لوحة التحكم') }}
                    </a>
                </div>
            </div>
        </section>

        <section class="book-stats">
            <article class="book-stat-card"><span class="book-stat-icon"><i class="bi bi-calendar-check-fill"></i></span><p class="book-stat-value">{{ $totalBookings }}</p><p class="book-stat-label">{{ __('إجمالي الحجوزات') }}</p></article>
            <article class="book-stat-card"><span class="book-stat-icon"><i class="bi bi-hourglass-split"></i></span><p class="book-stat-value">{{ $pendingBookings }}</p><p class="book-stat-label">{{ __('حجوزات قيد الانتظار') }}</p></article>
            <article class="book-stat-card"><span class="book-stat-icon"><i class="bi bi-patch-check-fill"></i></span><p class="book-stat-value">{{ $confirmedBookings }}</p><p class="book-stat-label">{{ __('حجوزات مؤكدة') }}</p></article>
            <article class="book-stat-card"><span class="book-stat-icon"><i class="bi bi-calendar-event-fill"></i></span><p class="book-stat-value">{{ $todayBookings }}</p><p class="book-stat-label">{{ __('حجوزات اليوم') }}</p></article>
        </section>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <section class="book-toolbar">
            <div class="book-search">
                <i class="bi bi-search"></i>
                <input type="search" id="bookingsSearch" placeholder="{{ __('ابحث بالخدمة أو المستخدم أو التاريخ أو الحالة...') }}" autocomplete="off">
            </div>
            <div class="book-toolbar-meta">
                <span class="book-chip"><i class="bi bi-eye-fill"></i><span id="visibleBookingsCount">{{ $totalBookings }}</span>{{ __('نتيجة ظاهرة') }}</span>
            </div>
        </section>

        <section class="book-list-card">
            @if($bookings->isNotEmpty())
                <div class="book-list-body">
                    @foreach($bookings as $booking)
                        @php
                            $statusText = $statusLabels[$booking->status] ?? $booking->status;
                            $searchableText = implode(' ', [
                                optional($booking->service)->name,
                                optional($booking->user)->name,
                                optional($booking->booking_date)->format('Y-m-d'),
                                optional($booking->booking_time)->format('H:i'),
                                $statusText,
                                $booking->notes,
                            ]);
                        @endphp
                        <article class="book-card book-search-item" data-search="{{ mb_strtolower($searchableText) }}">
                            <div class="book-card-head">
                                <div class="book-card-main">
                                    <div class="book-avatar"><i class="bi bi-calendar2-check-fill"></i></div>
                                    <div style="min-width: 0;">
                                        <h3 class="book-name">{{ optional($booking->service)->name ?: __('خدمة غير محددة') }}</h3>
                                        <div class="book-subline">{{ __('طالب الخدمة: ') }}{{ optional($booking->user)->name ?: __('غير محدد') }}</div>
                                    </div>
                                </div>
                                <span class="book-status-badge {{ $booking->status }}"><i class="bi bi-circle-fill" style="font-size: .45rem;"></i>{{ $statusText }}</span>
                            </div>

                            <div class="book-detail-grid">
                                <div class="book-detail-box"><span class="book-detail-label">{{ __('التاريخ') }}</span><div class="book-detail-value">{{ optional($booking->booking_date)->format('Y-m-d') ?: '--' }}</div></div>
                                <div class="book-detail-box"><span class="book-detail-label">{{ __('الوقت') }}</span><div class="book-detail-value">{{ optional($booking->booking_time)->format('h:i A') ?: '--' }}</div></div>
                                <div class="book-detail-box"><span class="book-detail-label">{{ __('تاريخ الإنشاء') }}</span><div class="book-detail-value">{{ $booking->created_at?->format('Y-m-d') }}</div></div>
                                <div class="book-detail-box"><span class="book-detail-label">{{ __('آخر تحديث') }}</span><div class="book-detail-value">{{ $booking->updated_at?->diffForHumans() }}</div></div>
                            </div>

                            @if($booking->notes)
                                <div class="book-notes"><strong>{{ __('ملاحظات') }}</strong>{{ $booking->notes }}</div>
                            @endif

                            <div class="book-card-actions">
                                <div class="book-status-toggle">
                                    <span>{{ __('تأكيد سريع') }}</span>
                                    <div class="book-toggle">
                                        <input class="book-toggle-input" id="toggle_{{ $booking->id }}" type="checkbox" @checked($booking->status === 'confirmed') />
                                        <label class="book-toggle-label" title="{{ $statusText }}" for="toggle_{{ $booking->id }}"></label>
                                    </div>
                                </div>

                                <div class="book-action-stack">
                                    <a href="{{ route('bookings.edit', $booking->id) }}" class="book-action-btn edit"><i class="bi bi-pencil-square"></i>{{ __('تعديل') }}</a>
                                    <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" style="display: inline-flex;" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا الحجز؟') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="book-action-btn delete"><i class="bi bi-trash3-fill"></i>{{ __('حذف') }}</button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="book-empty" id="bookingsEmptyState">
                    <div class="book-empty-icon"><i class="bi bi-search-heart"></i></div>
                    <h3>{{ __('لا توجد نتائج مطابقة') }}</h3>
                    <p>{{ __('جرّب البحث باسم مختلف أو بتاريخ أو بحالة الحجز للوصول إلى العنصر المطلوب.') }}</p>
                </div>
            @else
                <div class="book-empty show">
                    <div class="book-empty-icon"><i class="bi bi-calendar2-plus"></i></div>
                    <h3>{{ __('لا توجد حجوزات بعد') }}</h3>
                    <p>{{ __('ابدأ بإضافة أول حجز ليظهر هنا داخل قائمة حديثة تدعم البحث والتبديل السريع للحالة.') }}</p>
                </div>
            @endif
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('bookingsSearch');
        const cards = Array.from(document.querySelectorAll('.book-search-item'));
        const visibleCount = document.getElementById('visibleBookingsCount');
        const emptyState = document.getElementById('bookingsEmptyState');

        if (searchInput && cards.length > 0) {
            function normalize(value) { return (value || '').toString().toLowerCase().trim(); }
            function filterCards() {
                const query = normalize(searchInput.value);
                let shown = 0;
                cards.forEach(function (card) {
                    const haystack = normalize(card.getAttribute('data-search'));
                    const match = query === '' || haystack.indexOf(query) !== -1;
                    card.classList.toggle('is-hidden', !match);
                    if (match) shown += 1;
                });
                if (visibleCount) visibleCount.textContent = shown;
                if (emptyState) emptyState.classList.toggle('show', shown === 0);
            }
            searchInput.addEventListener('input', filterCards);
        }

        document.querySelectorAll('.book-toggle-input').forEach(function (toggle) {
            toggle.addEventListener('change', async function (e) {
                const bookingId = e.target.id.replace('toggle_', '');
                const newStatus = e.target.checked ? 'confirmed' : 'pending';
                try {
                    const response = await fetch(`/bookings/${bookingId}/status`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ status: newStatus })
                    });
                    const data = await response.json();
                    if (!response.ok) throw new Error(data.message || 'status update failed');
                    window.location.reload();
                } catch (error) {
                    alert('{{ __('حدث خطأ أثناء تحديث حالة الحجز.') }}');
                    e.target.checked = !e.target.checked;
                }
            });
        });
    });
</script>
@endpush
