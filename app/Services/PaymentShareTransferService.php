<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\SellShareAllocation;
use App\Models\SellShareSettlement;
use App\Models\ShareTransLine;
use App\Models\SharesPO;
use App\Models\SharesTrans;
use Illuminate\Support\Facades\DB;

class PaymentShareTransferService
{
    public function applyConfirmedPayment(Payment $payment): void
    {
        if (!$payment->confirmed) {
            return;
        }

        DB::transaction(function () use ($payment) {
            $payment->loadMissing('sharesPO.sellShare');
            $order = $payment->sharesPO;

            if (!$order || !$order->sellShare || (float) $order->amount_per_share <= 0) {
                return;
            }

            $allocation = SellShareAllocation::query()
                ->where('shares_po_id', $order->id)
                ->first();

            if (!$allocation) {
                app(SellShareSettlementService::class)->settle($order->sellShare, auth()->id());
                $allocation = SellShareAllocation::query()->where('shares_po_id', $order->id)->first();
            }

            if (!$allocation) {
                return;
            }

            $confirmedAmount = (float) Payment::query()
                ->where('shares_po_number', $order->id)
                ->where('confirmed', true)
                ->sum('amount');

            $targetTransferred = min(
                (float) $allocation->shares_count,
                floor(($confirmedAmount / (float) $order->amount_per_share) * 100) / 100
            );

            $delta = round($targetTransferred - (float) $allocation->transferred_count, 2);

            if ($delta <= 0) {
                $this->syncAllocationPayment($allocation, $confirmedAmount);
                return;
            }

            $seller = $allocation->seller()->lockForUpdate()->first();
            $buyer = $allocation->buyer()->lockForUpdate()->first();

            if (!$seller || !$buyer) {
                return;
            }

            $seller->update(['share_count_cr' => max(0, (float) $seller->share_count_cr - $delta)]);
            $buyer->update(['share_count_cr' => (float) $buyer->share_count_cr + $delta]);

            $allocation->update([
                'paid_amount' => min($confirmedAmount, (float) $allocation->total_amount),
                'transferred_count' => $targetTransferred,
                'status' => $targetTransferred >= (float) $allocation->shares_count
                    ? SellShareAllocation::STATUS_PAID
                    : SellShareAllocation::STATUS_PARTIALLY_PAID,
                'posted_at' => now(),
            ]);

            $order->update([
                'transferred_count' => $targetTransferred,
                'po_status' => $targetTransferred >= (float) $allocation->shares_count
                    ? SharesPO::PO_STATUS_COMPLETED
                    : SharesPO::PO_STATUS_REVIEW,
            ]);

            $this->createShareTransaction($payment, $order, $allocation, $delta);
            $this->syncSettlement($allocation->settlement);
        });
    }

    private function syncAllocationPayment(SellShareAllocation $allocation, float $confirmedAmount): void
    {
        $allocation->update([
            'paid_amount' => min($confirmedAmount, (float) $allocation->total_amount),
        ]);
    }

    private function createShareTransaction(Payment $payment, SharesPO $order, SellShareAllocation $allocation, float $delta): void
    {
        $transaction = SharesTrans::create([
            'date' => $payment->date,
            'notes' => 'نقل ملكية تلقائي من دفعة رقم #' . $payment->id . ' لطلب شراء #' . $order->id,
            'trans_type' => SharesTrans::TRANS_TYPE_TRANSFER,
            'posted' => true,
        ]);

        ShareTransLine::create([
            'contributor_id' => $allocation->seller_id,
            'trans_id' => $transaction->id,
            'count_debit' => $delta,
            'count_credit' => 0,
            'amount_per_share' => $allocation->amount_per_share,
            'line_notes' => 'خصم أسهم من البائع مقابل طلب شراء #' . $order->id,
            'posted' => true,
        ]);

        ShareTransLine::create([
            'contributor_id' => $allocation->buyer_id,
            'trans_id' => $transaction->id,
            'count_debit' => 0,
            'count_credit' => $delta,
            'amount_per_share' => $allocation->amount_per_share,
            'line_notes' => 'إضافة أسهم للمشتري مقابل طلب شراء #' . $order->id,
            'posted' => true,
        ]);
    }

    private function syncSettlement(?SellShareSettlement $settlement): void
    {
        if (!$settlement) {
            return;
        }

        $transferred = (float) $settlement->allocations()->sum('transferred_count');
        $allocated = (float) $settlement->allocated_count;

        $settlement->update([
            'transferred_count' => $transferred,
            'status' => $allocated > 0 && $transferred >= $allocated
                ? SellShareSettlement::STATUS_COMPLETED
                : SellShareSettlement::STATUS_PARTIALLY_PAID,
            'posted_at' => $allocated > 0 && $transferred >= $allocated ? now() : $settlement->posted_at,
        ]);
    }
}
