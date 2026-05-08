<?php

namespace App\Services;

use App\Models\Contributor;
use App\Models\SellShares;
use Illuminate\Validation\ValidationException;

class SellShareAnnualLimitService
{
    public function annualLimit(Contributor $seller): float
    {
        return round(((float) $seller->share_count_cr) * 0.25, 2);
    }

    public function soldOrOfferedThisYear(Contributor $seller, ?SellShares $except = null): float
    {
        return (float) SellShares::query()
            ->where('user_id', $seller->id)
            ->whereYear('insert_date', now()->year)
            ->where('ad_status', '!=', SellShares::AD_STATUS_CANCELLED)
            ->when($except, fn ($query) => $query->where('id', '!=', $except->id))
            ->sum('count');
    }

    public function remaining(Contributor $seller, ?SellShares $except = null): float
    {
        return max(0, $this->annualLimit($seller) - $this->soldOrOfferedThisYear($seller, $except));
    }

    public function assertWithinLimit(Contributor $seller, float $requestedCount, ?SellShares $except = null): void
    {
        $remaining = $this->remaining($seller, $except);

        if ($requestedCount > $remaining) {
            throw ValidationException::withMessages([
                'count' => 'لا يمكن بيع أكثر من 25% من الأسهم خلال السنة. المتاح حالياً: ' . number_format($remaining, 2) . ' سهم.',
            ]);
        }
    }
}
