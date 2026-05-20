@if($meeting->polls->isNotEmpty())
    <section class="cp-card">
        <h2 class="cp-card-title"><i class="bi bi-ui-checks-grid"></i>{{ __('الاستطلاعات المرتبطة') }}</h2>
        <div class="cp-grid-2">
            @foreach($meeting->polls as $poll)
                @php
                    $pollTitle = $poll->title ?: $poll->question;
                    $pollEnded = $poll->end_date && $poll->end_date->isPast();
                    $totalVotes = (int) $poll->pollAnswers->count();
                @endphp
                <article class="cp-card" style="box-shadow:none;">
                    <div class="cp-section-head">
                        <h3 class="cp-card-title" style="margin:0;">{{ $pollTitle }}</h3>
                        <span class="cp-badge">{{ $pollEnded ? __('انتهى التصويت') : ($poll->isCurrentlyActive() ? __('نشط') : __('غير نشط')) }}</span>
                    </div>

                    @if($poll->question && $poll->question !== $pollTitle)
                        <p class="cp-subtitle">{{ $poll->question }}</p>
                    @endif

                    <div class="cp-news-meta">
                        <span><i class="bi bi-calendar2-play"></i>{{ __('البداية') }}: {{ $poll->start_date?->format('Y-m-d H:i') ?: '-' }}</span>
                        <span><i class="bi bi-calendar2-x"></i>{{ __('النهاية') }}: {{ $poll->end_date?->format('Y-m-d H:i') ?: '-' }}</span>
                        <span><i class="bi bi-bar-chart-fill"></i>{{ number_format($totalVotes) }} {{ __('صوت') }}</span>
                    </div>

                    @if($pollEnded)
                        <div style="display:flex; flex-direction:column; gap:.75rem; margin-top:1rem;">
                            @forelse($poll->pollOptions as $option)
                                @php
                                    $optionVotes = (int) $option->votes;
                                    $percentage = $totalVotes > 0 ? ($optionVotes / $totalVotes) * 100 : 0;
                                @endphp
                                <div>
                                    <div style="display:flex; justify-content:space-between; gap:1rem; color:var(--text-primary); font-weight:800;">
                                        <span>{{ $option->option_text }}</span>
                                        <span>{{ number_format($optionVotes) }} - {{ number_format($percentage, 1) }}%</span>
                                    </div>
                                    <div style="height:10px; border-radius:999px; background:rgba(170,134,63,.14); overflow:hidden; margin-top:.45rem;">
                                        <div style="height:100%; width:{{ $percentage }}%; background:var(--primary-color); border-radius:inherit;"></div>
                                    </div>
                                </div>
                            @empty
                                <div class="cp-empty">{{ __('لا توجد خيارات تصويت مرتبطة بهذا الاستطلاع.') }}</div>
                            @endforelse
                        </div>
                    @else
                        <p class="cp-subtitle" style="margin-top:1rem;">{{ __('ستظهر نتائج التصويت هنا بعد انتهاء فترة التصويت.') }}</p>
                    @endif

                    <div class="cp-actions" style="margin-top:1rem;">
                        <a class="cp-btn cp-btn-secondary" href="{{ route('contributor.polls.show', $poll) }}">
                            <i class="bi bi-eye-fill"></i>{{ __('تفاصيل الاستطلاع') }}
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endif
