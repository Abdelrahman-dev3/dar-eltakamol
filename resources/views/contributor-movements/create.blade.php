@extends('layouts.app')

@section('title', __('إضافة حركة للمساهم'))

@include('shares-trans.partials.styles')

@php
    $transactionTypes = [
        \App\Models\SharesTrans::TRANS_TYPE_BUY => __('شراء'),
        \App\Models\SharesTrans::TRANS_TYPE_SELL => __('بيع'),
        \App\Models\SharesTrans::TRANS_TYPE_TRANSFER => __('مناقلة'),
        \App\Models\SharesTrans::TRANS_TYPE_DIVIDEND => __('توزيعات'),
    ];
@endphp

@section('content')
    <div class="st-page">
        <div class="st-shell">
            <section class="st-hero">
                <div class="st-hero-inner">
                    <div>
                        <span class="st-hero-badge">
                            <i class="bi bi-plus-circle-fill"></i>
                            {{ __('حركة جديدة') }}
                        </span>
                        <h1 class="st-hero-title">{{ __('إضافة حركة للمساهم') }}</h1>
                        <p class="st-hero-subtitle">
                            {{ __('بعد حفظ الحركة سيتم إنشاء سجل معتمد وتحديث رصيد الأسهم للمساهمين المرتبطين بالحركة مباشرة.') }}
                        </p>
                    </div>

                    <div class="st-hero-actions">
                        <a href="{{ route('contributor-movements.index') }}" class="st-btn st-btn-secondary">
                            <i class="bi bi-arrow-right-circle"></i>
                            {{ __('العودة للحركات') }}
                        </a>
                    </div>
                </div>
            </section>

            @if ($errors->any())
                <div class="st-banner is-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>{{ __('يرجى مراجعة الحقول المطلوبة وتصحيح الأخطاء قبل الحفظ.') }}</div>
                </div>
            @endif

            <div class="st-grid-two">
                <section class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-title-wrap">
                            <span class="st-card-icon"><i class="bi bi-pencil-square"></i></span>
                            <div>
                                <h2 class="st-card-title">{{ __('بيانات الحركة') }}</h2>
                                <p class="st-card-subtitle">{{ __('حدد نوع الحركة والمساهمين والكمية والسعر مع وصف واضح لسبب الإضافة.') }}</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('contributor-movements.store') }}" method="POST" data-contributor-movement-form>
                        @csrf

                        <div class="st-form-grid">
                            <div class="st-form-field">
                                <label for="date" class="st-label">{{ __('تاريخ الحركة') }} <span class="st-required">*</span></label>
                                <input type="date" name="date" id="date" class="st-input" value="{{ old('date', now()->format('Y-m-d')) }}" required>
                                @error('date')<span class="st-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="st-form-field">
                                <label for="trans_type" class="st-label">{{ __('نوع الحركة') }} <span class="st-required">*</span></label>
                                <select name="trans_type" id="trans_type" class="st-select" required>
                                    <option value="">{{ __('اختر نوع الحركة') }}</option>
                                    @foreach($transactionTypes as $value => $label)
                                        <option value="{{ $value }}" {{ old('trans_type') == (string) $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('trans_type')<span class="st-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="st-form-field">
                                <label for="from_contributor_id" class="st-label">{{ __('المساهم من') }}</label>
                                <select name="from_contributor_id" id="from_contributor_id" class="st-select" data-party-select>
                                    <option value="">{{ __('بدون') }}</option>
                                    @foreach($contributors as $contributor)
                                        <option value="{{ $contributor->id }}" data-shares="{{ (float) ($contributor->share_count_cr ?? 0) }}" {{ old('from_contributor_id') == (string) $contributor->id ? 'selected' : '' }}>
                                            {{ $contributor->name }} - {{ number_format((float) ($contributor->share_count_cr ?? 0), 2) }} {{ __('سهم') }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="st-help" id="fromBalanceHelp"></span>
                                @error('from_contributor_id')<span class="st-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="st-form-field">
                                <label for="to_contributor_id" class="st-label">{{ __('المساهم إلى') }}</label>
                                <select name="to_contributor_id" id="to_contributor_id" class="st-select" data-party-select>
                                    <option value="">{{ __('بدون') }}</option>
                                    @foreach($contributors as $contributor)
                                        <option value="{{ $contributor->id }}" data-shares="{{ (float) ($contributor->share_count_cr ?? 0) }}" {{ old('to_contributor_id') == (string) $contributor->id ? 'selected' : '' }}>
                                            {{ $contributor->name }} - {{ number_format((float) ($contributor->share_count_cr ?? 0), 2) }} {{ __('سهم') }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="st-help" id="toBalanceHelp"></span>
                                @error('to_contributor_id')<span class="st-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="st-form-field">
                                <label for="shares_count" class="st-label">{{ __('عدد الأسهم') }} <span class="st-required">*</span></label>
                                <input type="number" step="0.01" min="0.01" name="shares_count" id="shares_count" class="st-input" value="{{ old('shares_count') }}" required>
                                @error('shares_count')<span class="st-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="st-form-field">
                                <label for="amount_per_share" class="st-label">{{ __('سعر السهم') }} <span class="st-required">*</span></label>
                                <input type="number" step="0.01" min="0" name="amount_per_share" id="amount_per_share" class="st-input" value="{{ old('amount_per_share') }}" required>
                                @error('amount_per_share')<span class="st-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="st-form-field full">
                                <label for="description" class="st-label">{{ __('الوصف') }} <span class="st-required">*</span></label>
                                <textarea name="description" id="description" class="st-textarea" placeholder="{{ __('اكتب سبب إضافة هذه الحركة أو بيانها المحاسبي...') }}" required>{{ old('description') }}</textarea>
                                @error('description')<span class="st-error">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="st-form-footer">
                            <p class="st-form-footer-note" id="movementHint">
                                {{ __('اختر نوع الحركة ليظهر ملخص تأثيرها على أرصدة المساهمين قبل الحفظ.') }}
                            </p>

                            <div class="st-inline-actions">
                                <button type="submit" class="st-btn st-btn-primary">
                                    <i class="bi bi-check2-circle"></i>
                                    {{ __('حفظ الحركة') }}
                                </button>
                                <a href="{{ route('contributor-movements.index') }}" class="st-btn st-btn-secondary">
                                    <i class="bi bi-x-circle"></i>
                                    {{ __('إلغاء') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </section>

                <div class="st-shell">
                    <section class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-title-wrap">
                                <span class="st-card-icon"><i class="bi bi-calculator-fill"></i></span>
                                <div>
                                    <h2 class="st-card-title">{{ __('ملخص فوري') }}</h2>
                                    <p class="st-card-subtitle">{{ __('يتحدث عند تغيير المساهمين أو عدد الأسهم.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="st-info-list">
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('الرصيد الجديد للمساهم من') }}</span>
                                <span class="st-info-value" id="fromNewBalance">{{ __('غير محدد') }}</span>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('الرصيد الجديد للمساهم إلى') }}</span>
                                <span class="st-info-value" id="toNewBalance">{{ __('غير محدد') }}</span>
                            </div>
                            <div class="st-info-item">
                                <span class="st-info-label">{{ __('إجمالي القيمة') }}</span>
                                <span class="st-info-value" id="movementTotal">0.00</span>
                            </div>
                        </div>
                    </section>

                    <section class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-title-wrap">
                                <span class="st-card-icon"><i class="bi bi-info-circle-fill"></i></span>
                                <div>
                                    <h2 class="st-card-title">{{ __('قواعد الحفظ') }}</h2>
                                </div>
                            </div>
                        </div>

                        <div class="st-note-box">
                            <i class="bi bi-shield-check"></i>
                            <div>{{ __('البيع والمناقلة يخصمان من رصيد المساهم في حقل "من"، والشراء والمناقلة يضيفان إلى رصيد المساهم في حقل "إلى". التوزيعات تسجل كسجل حركة ولا تغير عدد الأسهم.') }}</div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('[data-contributor-movement-form]');
            const typeInput = document.getElementById('trans_type');
            const fromInput = document.getElementById('from_contributor_id');
            const toInput = document.getElementById('to_contributor_id');
            const sharesInput = document.getElementById('shares_count');
            const priceInput = document.getElementById('amount_per_share');
            const fromNewBalance = document.getElementById('fromNewBalance');
            const toNewBalance = document.getElementById('toNewBalance');
            const movementTotal = document.getElementById('movementTotal');
            const movementHint = document.getElementById('movementHint');
            const fromBalanceHelp = document.getElementById('fromBalanceHelp');
            const toBalanceHelp = document.getElementById('toBalanceHelp');

            function selectedShares(select) {
                const option = select?.selectedOptions?.[0];
                return Number(option?.getAttribute('data-shares') || 0);
            }

            function formatNumber(value) {
                return Number(value || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            function updateSummary() {
                const type = Number(typeInput.value || 0);
                const shares = Number(sharesInput.value || 0);
                const price = Number(priceInput.value || 0);
                const fromShares = selectedShares(fromInput);
                const toShares = selectedShares(toInput);
                const isDividend = type === {{ \App\Models\SharesTrans::TRANS_TYPE_DIVIDEND }};

                fromBalanceHelp.textContent = fromInput.value ? '{{ __("الرصيد الحالي") }}: ' + formatNumber(fromShares) : '';
                toBalanceHelp.textContent = toInput.value ? '{{ __("الرصيد الحالي") }}: ' + formatNumber(toShares) : '';
                movementTotal.textContent = formatNumber(shares * price);

                if (fromInput.value) {
                    fromNewBalance.textContent = isDividend ? formatNumber(fromShares) : formatNumber(fromShares - shares);
                    fromNewBalance.classList.toggle('st-badge-danger', !isDividend && fromShares - shares < 0);
                } else {
                    fromNewBalance.textContent = '{{ __("غير محدد") }}';
                    fromNewBalance.classList.remove('st-badge-danger');
                }

                if (toInput.value) {
                    toNewBalance.textContent = isDividend ? formatNumber(toShares) : formatNumber(toShares + shares);
                } else {
                    toNewBalance.textContent = '{{ __("غير محدد") }}';
                }

                const hints = {
                    {{ \App\Models\SharesTrans::TRANS_TYPE_BUY }}: '{{ __("شراء: سيتم إضافة عدد الأسهم إلى المساهم المحدد في حقل إلى.") }}',
                    {{ \App\Models\SharesTrans::TRANS_TYPE_SELL }}: '{{ __("بيع: سيتم خصم عدد الأسهم من المساهم المحدد في حقل من.") }}',
                    {{ \App\Models\SharesTrans::TRANS_TYPE_TRANSFER }}: '{{ __("مناقلة: سيتم الخصم من مساهم والإضافة إلى مساهم آخر.") }}',
                    {{ \App\Models\SharesTrans::TRANS_TYPE_DIVIDEND }}: '{{ __("توزيعات: سيتم تسجيل الحركة دون تغيير رصيد الأسهم.") }}'
                };

                movementHint.textContent = hints[type] || '{{ __("اختر نوع الحركة ليظهر ملخص تأثيرها على أرصدة المساهمين قبل الحفظ.") }}';
            }

            [typeInput, fromInput, toInput, sharesInput, priceInput].forEach(function (input) {
                input?.addEventListener('input', updateSummary);
                input?.addEventListener('change', updateSummary);
            });

            form?.addEventListener('submit', function (event) {
                if (!window.confirm('{{ __("هل أنت متأكد من حفظ هذه الحركة وتحديث الأرصدة؟") }}')) {
                    event.preventDefault();
                }
            });

            updateSummary();
        });
    </script>
@endpush
