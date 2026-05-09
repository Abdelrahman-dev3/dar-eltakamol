<?php

namespace App\Http\Controllers;

use App\Models\IndependentPurchaseOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndependentPurchaseOrdersController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $orders = IndependentPurchaseOrder::query()
            ->with('contributor.user')
            ->latest('requested_at')
            ->paginate(15)
            ->withQueryString();

        $stats = $this->stats();

        return view('independent-purchase-orders.index', compact('orders', 'stats'));
    }

    public function show(IndependentPurchaseOrder $independent_purchase_order): View
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $independent_purchase_order->load('contributor.user');

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
            'completed_count' => $orders->where('status', IndependentPurchaseOrder::STATUS_COMPLETED)->count(),
            'total_shares' => (float) $orders->sum(fn ($order) => (float) $order->count),
            'total_value' => (float) $orders->sum(fn ($order) => (float) $order->count * (float) $order->amount_per_share),
        ];
    }
}
