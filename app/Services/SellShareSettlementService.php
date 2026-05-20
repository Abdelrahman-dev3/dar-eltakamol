<?php

namespace App\Services;

use App\Models\SellShareAllocation;
use App\Models\SellShares;
use App\Models\SellShareSettlement;
use App\Models\SharesPO;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SellShareSettlementService
{
    public function settleBySeller(SellShares $offer, ?int $createdBy = null): SellShareSettlement
    {
        return DB::transaction(function () use ($offer, $createdBy) {
            $offer = SellShares::query()
                ->whereKey($offer->id)
                ->lockForUpdate()
                ->firstOrFail();

            $eligibleOrders = $offer->sharesPOs()
                ->where(function ($query): void {
                    $query
                        ->whereNull('po_status')
                        ->orWhereNotIn('po_status', [
                            SharesPO::PO_STATUS_COMPLETED,
                            SharesPO::PO_STATUS_REJECTED,
                        ]);
                })
                ->lockForUpdate()
                ->get();

            if ($eligibleOrders->isNotEmpty()) {
                $prices = $eligibleOrders
                    ->map(fn (SharesPO $order) => number_format((float) $order->amount_per_share, 2, '.', ''))
                    ->unique()
                    ->values();

                if ($prices->count() === 1) {
                    foreach ($eligibleOrders as $order) {
                        $order->update([
                            'accept' => true,
                            'accepted_count' => $order->count,
                            'po_status' => SharesPO::PO_STATUS_REVIEW,
                        ]);
                    }
                } else {
                    $highestPrice = (float) $eligibleOrders->max(fn (SharesPO $order) => (float) $order->amount_per_share);

                    foreach ($eligibleOrders as $order) {
                        if ((float) $order->amount_per_share >= $highestPrice) {
                            $order->update([
                                'accept' => true,
                                'accepted_count' => (float) $order->accepted_count > 0 ? $order->accepted_count : $order->count,
                                'po_status' => SharesPO::PO_STATUS_REVIEW,
                            ]);
                        }
                    }

                    $lowerPricedOrders = $eligibleOrders->filter(
                        fn (SharesPO $order) => (float) $order->amount_per_share < $highestPrice
                    );

                    if ($lowerPricedOrders->isNotEmpty()) {
                        throw ValidationException::withMessages([
                            'settlement' => 'توجد طلبات شراء بسعر أقل من أعلى سعر مقدم. يمكن انتظار رفع السعر أو رفض الطلبات الأقل قبل تنفيذ التسوية.',
                        ]);
                    }
                }
            }

            return $this->settle($offer->refresh(), $createdBy);
        });
    }

    public function settle(SellShares $offer, ?int $createdBy = null): SellShareSettlement
    {
        return DB::transaction(function () use ($offer, $createdBy) {
            $offer->loadMissing('sharesPOs.contributor', 'settlement.allocations');

            $settlement = SellShareSettlement::firstOrCreate(
                ['sell_share_id' => $offer->id],
                [
                    'created_by' => $createdBy,
                    'status' => SellShareSettlement::STATUS_DRAFT,
                    'method' => 'buyers',
                    'offered_count' => $offer->count,
                ]
            );

            if ($settlement->allocations()->where('transferred_count', '>', 0)->exists()) {
                throw ValidationException::withMessages([
                    'settlement' => 'لا يمكن إعادة توزيع عرض بدأ نقل ملكية بعض أسهمه.',
                ]);
            }

            $orders = $offer->sharesPOs()
                ->where('accept', true)
                ->where(function ($query): void {
                    $query
                        ->whereNull('po_status')
                        ->orWhereNotIn('po_status', [
                            SharesPO::PO_STATUS_COMPLETED,
                            SharesPO::PO_STATUS_REJECTED,
                        ]);
                })
                ->orderBy('insert_date')
                ->orderBy('id')
                ->get();

            $settlement->allocations()->delete();

            if ($orders->isEmpty()) {
                app(CompanyPurchaseObligationService::class)->createForUnmatchedOffer($offer);
                $settlement->update([
                    'status' => SellShareSettlement::STATUS_ALLOCATED,
                    'method' => 'company',
                    'allocated_count' => 0,
                    'notes' => 'لا توجد طلبات شراء مقبولة؛ تم إنشاء التزام شراء على الشركة.',
                ]);

                return $settlement->refresh();
            }

            $allocations = $this->buildEqualAllocations((float) $offer->count, $orders);
            $allocatedCount = 0;

            foreach ($allocations as $orderId => $sharesCount) {
                if ($sharesCount <= 0) {
                    continue;
                }

                $order = $orders->firstWhere('id', $orderId);
                $allocatedCount += $sharesCount;

                SellShareAllocation::create([
                    'settlement_id' => $settlement->id,
                    'sell_share_id' => $offer->id,
                    'shares_po_id' => $order->id,
                    'seller_id' => $offer->user_id,
                    'buyer_id' => $order->user_id,
                    'allocation_type' => SellShareAllocation::TYPE_BUYER,
                    'shares_count' => $sharesCount,
                    'amount_per_share' => $order->amount_per_share,
                    'total_amount' => round($sharesCount * (float) $order->amount_per_share, 2),
                    'status' => SellShareAllocation::STATUS_PENDING,
                ]);
            }

            $settlement->update([
                'status' => SellShareSettlement::STATUS_ALLOCATED,
                'method' => 'buyers',
                'allocated_count' => round($allocatedCount, 2),
            ]);

            $offer->update([
                'ad_status' => $allocatedCount >= (float) $offer->count
                    ? SellShares::AD_STATUS_COMPLETED
                    : SellShares::AD_STATUS_ACTIVE,
            ]);

            return $settlement->refresh();
        });
    }

    private function buildEqualAllocations(float $offeredCount, $orders): array
    {
        $remaining = round($offeredCount, 2);
        $buyersCount = max($orders->count(), 1);
        $baseShare = floor(($offeredCount / $buyersCount) * 100) / 100;
        $allocations = [];

        foreach ($orders as $order) {
            $orderCount = $this->acceptedOrderCount($order);
            $allocation = min($baseShare, $orderCount, $remaining);
            $allocations[$order->id] = round($allocation, 2);
            $remaining = round($remaining - $allocation, 2);
        }

        while ($remaining > 0) {
            $changed = false;

            foreach ($orders as $order) {
                $current = $allocations[$order->id] ?? 0;
                $orderRemaining = round($this->acceptedOrderCount($order) - $current, 2);

                if ($orderRemaining <= 0 || $remaining <= 0) {
                    continue;
                }

                $step = min(1, $orderRemaining, $remaining);
                $allocations[$order->id] = round($current + $step, 2);
                $remaining = round($remaining - $step, 2);
                $changed = true;
            }

            if (!$changed) {
                break;
            }
        }

        return $allocations;
    }

    private function acceptedOrderCount(SharesPO $order): float
    {
        $acceptedCount = (float) ($order->accepted_count ?? 0);

        if ($acceptedCount <= 0) {
            return (float) $order->count;
        }

        return min($acceptedCount, (float) $order->count);
    }
}
