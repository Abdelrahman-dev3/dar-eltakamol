<?php

namespace App\Services;

use App\Models\CompanyPurchaseObligation;
use App\Models\SellShares;

class CompanyPurchaseObligationService
{
    public function createForUnmatchedOffer(SellShares $offer): int
    {
        $offer->loadMissing('seller', 'companyPurchaseObligations');

        if ($offer->companyPurchaseObligations()->exists()) {
            return 0;
        }

        $sellerShares = max((float) $offer->seller?->share_count_cr, 1);
        $shareRatio = (float) $offer->count / $sellerShares;
        $currentYear = (int) now()->year;
        $created = 0;

        if ($shareRatio < 0.25) {
            $this->createObligation($offer, (float) $offer->count, $currentYear, 100);
            return 1;
        }

        $remaining = (float) $offer->count;
        for ($i = 0; $i < 4 && $remaining > 0; $i++) {
            $count = $i === 3 ? $remaining : round(((float) $offer->count) * 0.25, 2);
            $this->createObligation($offer, min($count, $remaining), $currentYear + $i, 25);
            $remaining = round($remaining - $count, 2);
            $created++;
        }

        return $created;
    }

    private function createObligation(SellShares $offer, float $sharesCount, int $dueYear, float $percentage): void
    {
        CompanyPurchaseObligation::create([
            'sell_share_id' => $offer->id,
            'seller_id' => $offer->user_id,
            'shares_count' => $sharesCount,
            'amount_per_share' => $offer->amount_per_share,
            'total_amount' => round($sharesCount * (float) $offer->amount_per_share, 2),
            'due_year' => $dueYear,
            'annual_percentage' => $percentage,
            'status' => CompanyPurchaseObligation::STATUS_SCHEDULED,
            'payment_kind' => 'cash',
            'due_date' => $dueYear . '-12-31',
            'notes' => 'التزام شراء شركة لحصة لم يتم شراؤها من الشركاء ضمن فترة التداول.',
        ]);
    }
}
