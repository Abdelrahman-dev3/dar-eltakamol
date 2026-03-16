@extends('layouts.app')

@section('title', __('ملاحظات التعديل'))

@include('shares-trans.partials.styles')

@php
    $editsCollection = $edits->getCollection();
    $pageEditsCount = $editsCollection->count();
    $uniqueEditorsCount = $editsCollection->pluck('modified_by')->filter()->unique()->count();
    $notesCount = $editsCollection->filter(fn ($edit) => filled($edit->note))->count();
    $latestEdit = $editsCollection->first();
@endphp

@push('styles')
    <style>
        .modify-page .st-list-head,
        .modify-page .st-row {
            grid-template-columns: minmax(14rem, 1.6fr) minmax(18rem, 2fr) minmax(10rem, 1fr) minmax(8rem, 0.9fr) minmax(10rem, 1fr);
        }

        .modify-link {
            color: var(--primary-color);
            text-decoration: none !important;
            font-weight: 800;
            transition: color 0.22s ease;
            word-break: break-word;
        }

        .modify-link:hover {
            color: var(--primary-hover);
        }

        .modify-note {
            margin-top: 0.35rem;
            color: var(--text-secondary);
            font-size: 0.94rem;
            line-height: 1.8;
        }

        .modify-table-wrap {
            margin-top: 1rem;
        }

        @media (max-width: 1199px) {
            .modify-page .st-list-head {
                display: none;
            }

            .modify-page .st-row {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 767px) {
            .modify-page .st-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="st-page modify-page">
        <div class="st-shell">
            <section class="st-hero">
                <div class="st-hero-inner">
                    <div>
                        <span class="st-hero-badge">
                            <i class="bi bi-clock-history"></i>
                            {{ __('سجل التعديلات') }}
                        </span>
                        <h1 class="st-hero-title">{{ __('ملاحظات التعديل') }}</h1>
                        <p class="st-hero-subtitle">
                            {{ __('واجهة حديثة لمراجعة كل عمليات التعديل داخل النظام، مع توضيح الصفحة المعدلة وسبب التغيير واسم المستخدم وتوقيت التنفيذ بشكل واضح ومتجاوب.') }}
                        </p>
                    </div>

                    <div class="st-hero-actions">
                        <a href="{{ route('dashboard') }}" class="st-btn st-btn-secondary">
                            <i class="bi bi-grid-1x2-fill"></i>
                            {{ __('العودة للوحة التحكم') }}
                        </a>
                    </div>
                </div>
            </section>

            <section class="st-stat-grid">
                <article class="st-stat-card" style="animation-delay: 0.05s;">
                    <div class="st-stat-icon">
                        <i class="bi bi-journal-text"></i>
                    </div>
                    <p class="st-stat-value">{{ number_format($edits->total()) }}</p>
                    <p class="st-stat-label">{{ __('إجمالي سجلات التعديل') }}</p>
                </article>

                <article class="st-stat-card" style="animation-delay: 0.12s;">
                    <div class="st-stat-icon">
                        <i class="bi bi-collection-fill"></i>
                    </div>
                    <p class="st-stat-value">{{ number_format($pageEditsCount) }}</p>
                    <p class="st-stat-label">{{ __('السجلات الظاهرة في الصفحة الحالية') }}</p>
                </article>

                <article class="st-stat-card" style="animation-delay: 0.19s;">
                    <div class="st-stat-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <p class="st-stat-value">{{ number_format($uniqueEditorsCount) }}</p>
                    <p class="st-stat-label">{{ __('عدد المستخدمين الظاهرين') }}</p>
                </article>

                <article class="st-stat-card" style="animation-delay: 0.26s;">
                    <div class="st-stat-icon">
                        <i class="bi bi-chat-left-text-fill"></i>
                    </div>
                    <p class="st-stat-value">{{ number_format($notesCount) }}</p>
                    <p class="st-stat-label">{{ __('السجلات التي تحتوي ملاحظات') }}</p>
                </article>
            </section>

            <section class="st-toolbar">
                <div class="st-search">
                    <i class="bi bi-search"></i>
                    <input
                        type="search"
                        id="modificationsSearch"
                        placeholder="{{ __('ابحث عن الصفحة أو الملاحظة أو اسم من قام بالتعديل...') }}"
                        autocomplete="off"
                    >
                </div>

                <div class="st-chip-row">
                    <span class="st-chip">
                        <i class="bi bi-eye-fill"></i>
                        <span id="visibleModificationsCount">{{ $pageEditsCount }}</span>
                        {{ __('نتيجة ظاهرة') }}
                    </span>
                    <span class="st-chip">
                        <i class="bi bi-clock-fill"></i>
                        {{ $latestEdit ? $latestEdit->created_at->diffForHumans() : __('لا توجد بيانات') }}
                    </span>
                </div>
            </section>

            <section class="st-list-card">
                @if($pageEditsCount > 0)
                    <div class="st-list-head">
                        <div>{{ __('الصفحة المعدلة') }}</div>
                        <div>{{ __('سبب التعديل') }}</div>
                        <div>{{ __('من قام بالتعديل') }}</div>
                        <div>{{ __('منذ') }}</div>
                        <div>{{ __('التاريخ') }}</div>
                    </div>

                    <div class="st-list-body" id="modificationsList">
                        @foreach($edits as $edit)
                            @php
                                $searchableText = implode(' ', [
                                    $edit->page_name,
                                    $edit->note,
                                    $edit->user->name ?? '',
                                    $edit->created_at?->format('Y-m-d H:i'),
                                ]);
                            @endphp

                            <article
                                class="st-row modification-search-item"
                                data-search="{{ mb_strtolower($searchableText) }}"
                                style="animation-delay: {{ 0.05 + ($loop->index * 0.04) }}s;"
                            >
                                <div class="st-row-main">
                                    <div class="st-row-avatar">
                                        <i class="bi bi-link-45deg"></i>
                                    </div>

                                    <div style="min-width: 0;">
                                        <h3 class="st-row-title">
                                            <a href="{{ $edit->page_name }}" class="modify-link">{{ __('عرض الصفحة') }}</a>
                                        </h3>
                                        <div class="modify-note">
                                            {{ \Illuminate\Support\Str::limit($edit->page_name, 90) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="st-field">
                                    <span class="st-field-label">{{ __('سبب التعديل') }}</span>
                                    <span class="st-field-value {{ filled($edit->note) ? '' : 'muted' }}">
                                        {{ $edit->note ?: __('لم يتم إدخال ملاحظة لهذا التعديل.') }}
                                    </span>
                                </div>

                                <div class="st-field">
                                    <span class="st-field-label">{{ __('من قام بالتعديل') }}</span>
                                    <span class="st-field-value">{{ $edit->user->name ?? __('غير معروف') }}</span>
                                </div>

                                <div class="st-field">
                                    <span class="st-field-label">{{ __('منذ') }}</span>
                                    <span class="st-field-value">{{ $edit->created_at->diffForHumans() }}</span>
                                </div>

                                <div class="st-field">
                                    <span class="st-field-label">{{ __('التاريخ') }}</span>
                                    <span class="st-field-value">{{ $edit->created_at->format('Y-m-d H:i') }}</span>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="st-empty" id="modificationsEmptyState">
                        <div class="st-empty-icon">
                            <i class="bi bi-search-heart"></i>
                        </div>
                        <h3>{{ __('لا توجد نتائج مطابقة') }}</h3>
                        <p>{{ __('جرّب كتابة جزء من رابط الصفحة أو اسم المستخدم أو ملاحظة التعديل للوصول إلى السجل المطلوب.') }}</p>
                    </div>

                    <div class="st-pagination" id="modificationsPagination">
                        {{ $edits->links() }}
                    </div>
                @else
                    <div class="st-empty show">
                        <div class="st-empty-icon">
                            <i class="bi bi-journal-x"></i>
                        </div>
                        <h3>{{ __('لا توجد ملاحظات تعديل حاليًا') }}</h3>
                        <p>{{ __('بمجرد إجراء تعديلات موثقة داخل النظام ستظهر هنا بشكل مرتب وسهل المراجعة.') }}</p>
                    </div>
                @endif
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('modificationsSearch');
            const rows = Array.from(document.querySelectorAll('.modification-search-item'));
            const visibleCount = document.getElementById('visibleModificationsCount');
            const emptyState = document.getElementById('modificationsEmptyState');
            const pagination = document.getElementById('modificationsPagination');

            if (!searchInput || rows.length === 0) {
                return;
            }

            function normalize(value) {
                return (value || '').toString().toLowerCase().trim();
            }

            function filterRows() {
                const query = normalize(searchInput.value);
                let shown = 0;

                rows.forEach(function (row) {
                    const haystack = normalize(row.getAttribute('data-search'));
                    const match = query === '' || haystack.indexOf(query) !== -1;

                    row.classList.toggle('is-hidden', !match);

                    if (match) {
                        shown += 1;
                    }
                });

                if (visibleCount) {
                    visibleCount.textContent = shown;
                }

                if (emptyState) {
                    emptyState.classList.toggle('show', shown === 0);
                }

                if (pagination) {
                    pagination.style.display = shown === 0 ? 'none' : '';
                }
            }

            searchInput.addEventListener('input', filterRows);
            filterRows();
        });
    </script>
@endpush
