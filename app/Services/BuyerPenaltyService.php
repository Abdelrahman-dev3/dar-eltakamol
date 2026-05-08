<?php

namespace App\Services;

use App\Models\BuyerPenalty;
use App\Models\Contributor;
use App\Models\SharesPO;
use Illuminate\Validation\ValidationException;

class BuyerPenaltyService
{
    public function assertCanTrade(Contributor $contributor): void
    {
        $isBanned = BuyerPenalty::query()
            ->where('contributor_id', $contributor->id)
            ->where('type', BuyerPenalty::TYPE_BAN)
            ->whereDate('banned_until', '>=', now()->toDateString())
            ->exists();

        if ($isBanned) {
            throw ValidationException::withMessages([
                'user_id' => 'هذا المساهم محروم من البيع والشراء حتى انتهاء مدة الحظر.',
            ]);
        }
    }

    public function registerDefault(SharesPO $order, ?int $createdBy = null, ?string $reason = null): BuyerPenalty
    {
        $contributor = $order->contributor;
        $previousWarnings = BuyerPenalty::query()
            ->where('contributor_id', $order->user_id)
            ->where('type', BuyerPenalty::TYPE_WARNING)
            ->count();

        return BuyerPenalty::create([
            'user_id' => $contributor?->user_id,
            'contributor_id' => $order->user_id,
            'shares_po_id' => $order->id,
            'type' => $previousWarnings > 0 ? BuyerPenalty::TYPE_BAN : BuyerPenalty::TYPE_WARNING,
            'reason' => $reason ?: 'عدم الالتزام بسداد قيمة طلب الشراء.',
            'banned_until' => $previousWarnings > 0 ? now()->addYear()->toDateString() : null,
            'created_by' => $createdBy,
        ]);
    }
}
