<?php

namespace App\Http\Controllers;

use App\Models\IndependentPurchaseOrder;
use App\Models\SellShares;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class IndependentPurchaseOrdersController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $orders = IndependentPurchaseOrder::query()
            ->with('contributor.user')
            ->withCount('sellOffers')
            ->latest('requested_at')
            ->paginate(15)
            ->withQueryString();

        $stats = $this->stats();

        return view('independent-purchase-orders.index', compact('orders', 'stats'));
    }

    public function show(IndependentPurchaseOrder $independent_purchase_order): View
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $independent_purchase_order->load(['contributor.user', 'sellOffers.seller']);

        return view('independent-purchase-orders.show', [
            'order' => $independent_purchase_order,
        ]);
    }

    public function update(Request $request, IndependentPurchaseOrder $independent_purchase_order): RedirectResponse
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $validated = $request->validate([
            'status' => 'required|integer|in:0,1,2,3',
        ]);

        $nextStatus = (int) $validated['status'];

        if ($nextStatus === IndependentPurchaseOrder::STATUS_PUBLISHED) {
            $validated['published_at'] = $independent_purchase_order->published_at ?: now();
            $validated['closed_at'] = null;
        }

        if ($nextStatus === IndependentPurchaseOrder::STATUS_CLOSED) {
            if (!$independent_purchase_order->canBeClosed()) {
                throw ValidationException::withMessages([
                    'status' => 'لا يمكن إغلاق الطلب لوجود عروض بيع قيد المعالجة أو عروض مقبولة مرتبطة به.',
                ]);
            }

            $validated['closed_at'] = now();
        }

        if ($nextStatus === IndependentPurchaseOrder::STATUS_COMPLETED) {
            $hasPendingOffers = $independent_purchase_order->sellOffers()
                ->where('independent_offer_status', SellShares::INDEPENDENT_STATUS_PENDING)
                ->exists();

            if ($hasPendingOffers) {
                throw ValidationException::withMessages([
                    'status' => 'لا يمكن إتمام الطلب قبل الرد على كل عروض البيع المقدمة.',
                ]);
            }
        }

        $independent_purchase_order->update($validated);

        return redirect()->route('independent-purchase-orders.show', $independent_purchase_order)
            ->with('success', 'تم تحديث حالة طلب الشراء المستقل بنجاح.');
    }

    private function stats(): array
    {
        $orders = IndependentPurchaseOrder::query()
            ->select(['count', 'amount_per_share', 'status'])
            ->get();

        return [
            'total_count' => $orders->count(),
            'pending_count' => $orders->where('status', IndependentPurchaseOrder::STATUS_PENDING)->count(),
            'published_count' => $orders->where('status', IndependentPurchaseOrder::STATUS_PUBLISHED)->count(),
            'completed_count' => $orders->where('status', IndependentPurchaseOrder::STATUS_COMPLETED)->count(),
            'total_shares' => (float) $orders->sum(fn ($order) => (float) $order->count),
            'total_value' => (float) $orders->sum(fn ($order) => (float) $order->count * (float) $order->amount_per_share),
        ];
    }
}
