<?php

namespace App\Services;

use App\Models\TradingPeriod;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class TradingWindowService
{
    public function currentPeriod(?CarbonInterface $date = null): ?TradingPeriod
    {
        $date = Carbon::parse($date ?? now())->startOfDay();

        return TradingPeriod::query()
            ->where('is_active', true)
            ->whereDate('offer_starts_at', '<=', $date)
            ->whereDate('private_ends_at', '>=', $date)
            ->orderBy('offer_starts_at')
            ->first();
    }

    public function currentPhase(?CarbonInterface $date = null): ?string
    {
        $date = Carbon::parse($date ?? now())->startOfDay();
        $period = $this->currentPeriod($date);

        if (!$period) {
            return null;
        }

        return match (true) {
            $date->betweenIncluded($period->offer_starts_at, $period->offer_ends_at) => TradingPeriod::PHASE_OFFER,
            $date->betweenIncluded($period->processing_starts_at, $period->processing_ends_at) => TradingPeriod::PHASE_PROCESSING,
            $date->betweenIncluded($period->private_starts_at, $period->private_ends_at) => TradingPeriod::PHASE_PRIVATE,
            default => null,
        };
    }

    public function canCreateMarketEntry(?CarbonInterface $date = null): bool
    {
        return in_array($this->currentPhase($date), [
            TradingPeriod::PHASE_OFFER,
            TradingPeriod::PHASE_PRIVATE,
        ], true);
    }

    public function canProcessDeals(?CarbonInterface $date = null): bool
    {
        return $this->currentPhase($date) === TradingPeriod::PHASE_PROCESSING;
    }

    public function canChangePrice(?CarbonInterface $date = null): bool
    {
        return $this->canCreateMarketEntry($date);
    }

    public function assertMarketEntryAllowed(string $message = 'لا يمكن تنفيذ العملية خارج مراحل العرض والصفقات الخاصة.'): void
    {
        if (!$this->canCreateMarketEntry()) {
            throw ValidationException::withMessages([
                'trading_period' => $message . ' ' . $this->nextWindowMessage(),
            ]);
        }
    }

    public function nextWindowMessage(): string
    {
        $next = TradingPeriod::query()
            ->where('is_active', true)
            ->whereDate('offer_starts_at', '>', now()->toDateString())
            ->orderBy('offer_starts_at')
            ->first();

        if (!$next) {
            return 'لا توجد فترة تداول قادمة معرفة حالياً.';
        }

        return 'الفترة القادمة: ' . $next->name . ' من ' . $next->offer_starts_at->format('Y-m-d') . '.';
    }
}
