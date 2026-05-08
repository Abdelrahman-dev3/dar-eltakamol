<?php

namespace App\Http\Controllers;

use App\Models\Contributor;
use App\Models\Modification;
use App\Models\Setting;
use App\Models\SellShares;
use App\Models\SharesPO;
use Illuminate\Http\Request;
use App\Services\BuyerPenaltyService;
use App\Services\TradingWindowService;

class SharesPOController extends Controller
{
    protected function buildStats(): array
    {
        $orders = SharesPO::select(['count', 'amount_per_share', 'accept', 'po_status'])->get();

        return [
            'total_count' => $orders->count(),
            'accepted_count' => $orders->where('accept', true)->count(),
            'pending_accept_count' => $orders->where('accept', false)->count(),
            'total_shares' => (float) $orders->sum(fn ($order) => (float) $order->count),
            'total_value' => (float) $orders->sum(fn ($order) => (float) $order->count * (float) $order->amount_per_share),
            'average_price' => (float) $orders->avg(fn ($order) => (float) $order->amount_per_share),
            'completed_count' => $orders->where('po_status', SharesPO::PO_STATUS_COMPLETED)->count(),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sharesPOs = SharesPO::with(['contributor', 'sellShare.seller'])
            ->orderBy('insert_date', 'desc')
            ->paginate(15)
            ->withQueryString();

        $stats = $this->buildStats();

        return view('shares-pos.index', compact('sharesPOs', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contributors = Contributor::orderBy('name')->get();
        $sellShares = SellShares::with('seller')->orderByDesc('insert_date')->get();
        $stock = Setting::getValue('base_price', 0);
        $stats = $this->buildStats();
        $currentPeriod = app(TradingWindowService::class)->currentPeriod();
        $currentPhase = app(TradingWindowService::class)->currentPhase();

        return view('shares-pos.create', compact('contributors', 'sellShares', 'stock', 'stats', 'currentPeriod', 'currentPhase'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        app(TradingWindowService::class)->assertMarketEntryAllowed('لا يمكن إنشاء طلب شراء خارج مراحل العرض والصفقات الخاصة.');
        $basePrice = Setting::getValue('base_price', 0);

        $request->validate([
            'user_id' => 'required|exists:contributors,id',
            'sale_number' => 'required|exists:sell_shares,id',
            'count' => 'required|numeric|min:0',
            'amount_per_share' => 'required|numeric|min:0',
            'accept' => 'boolean',
            'insert_date' => 'required|date',
            'po_status' => 'required|integer|between:0,2',
        ]);
        $buyer = Contributor::findOrFail($request->user_id);
        app(BuyerPenaltyService::class)->assertCanTrade($buyer);
        $offer = SellShares::findOrFail($request->sale_number);

        if ((int) $offer->user_id === (int) $buyer->id) {
            return redirect()->back()->withInput()->withErrors([
                'user_id' => 'لا يمكن للبائع إنشاء طلب شراء على عرضه.',
            ]);
        }

        if ($request->amount_per_share < $basePrice) {
            return redirect()->back()->withInput()->withErrors([
                'amount_per_share' => 'يجب ألا يقل سعر السهم عن ' . $basePrice . ' ريال.',
            ]);
        }

        SharesPO::create([
            'user_id' => $request->user_id,
            'sale_number' => $request->sale_number,
            'count' => $request->count,
            'amount_per_share' => $request->amount_per_share,
            'accept' => $request->has('accept'),
            'insert_date' => $request->insert_date,
            'po_status' => $request->po_status,
        ]);

        return redirect()->route('shares-pos.index')
            ->with('success', 'تم إضافة طلب الشراء بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(SharesPO $shares_po)
    {
        $shares_po->load(['contributor', 'sellShare.seller']);

        return view('shares-pos.show', ['sharesPO' => $shares_po]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SharesPO $shares_po)
    {
        $shares_po->load(['contributor', 'sellShare.seller']);

        $contributors = Contributor::orderBy('name')->get();
        $sellShares = SellShares::with('seller')->orderByDesc('insert_date')->get();
        $stock = Setting::getValue('base_price', 0);
        $stats = $this->buildStats();

        return view('shares-pos.edit', [
            'sharesPO' => $shares_po,
            'contributors' => $contributors,
            'sellShares' => $sellShares,
            'stock' => $stock,
            'stats' => $stats,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SharesPO $shares_po)
    {
        $basePrice = Setting::getValue('base_price', 0);

        $request->validate([
            'user_id' => 'required|exists:contributors,id',
            'sale_number' => 'required|exists:sell_shares,id',
            'count' => 'required|numeric|min:0',
            'amount_per_share' => 'required|numeric|min:0',
            'accept' => 'boolean',
            'insert_date' => 'required|date',
            'po_status' => 'required|integer|between:0,2',
            'line_notes' => 'required|string',
        ]);
        $buyer = Contributor::findOrFail($request->user_id);
        app(BuyerPenaltyService::class)->assertCanTrade($buyer);

        if ($request->amount_per_share < $basePrice) {
            return redirect()->back()->withInput()->withErrors([
                'amount_per_share' => 'يجب ألا يقل سعر السهم عن ' . $basePrice . ' ريال.',
            ]);
        }

        $shares_po->update([
            'user_id' => $request->user_id,
            'sale_number' => $request->sale_number,
            'count' => $request->count,
            'amount_per_share' => $request->amount_per_share,
            'accept' => $request->has('accept'),
            'insert_date' => $request->insert_date,
            'po_status' => $request->po_status,
        ]);

        Modification::logChange(url()->previous(), $request->line_notes, auth()->id());

        return redirect()->route('shares-pos.index')
            ->with('success', 'تم تحديث طلب الشراء بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SharesPO $shares_po)
    {
        $shares_po->delete();

        return redirect()->route('shares-pos.index')
            ->with('success', 'تم حذف طلب الشراء بنجاح');
    }

    /**
     * Toggle the accept status of a purchase order.
     */
    public function toggleAccept(SharesPO $shares_po)
    {
        $shares_po->update([
            'accept' => !$shares_po->accept,
        ]);

        if (!request()->expectsJson()) {
            return redirect()->back()->with(
                'success',
                $shares_po->accept ? 'تم قبول طلب الشراء بنجاح' : 'تم رفض طلب الشراء بنجاح'
            );
        }

        return response()->json([
            'success' => true,
            'accept' => $shares_po->accept,
        ]);
    }

    public function markDefault(SharesPO $shares_po)
    {
        app(BuyerPenaltyService::class)->registerDefault($shares_po->load('contributor'), auth()->id());
        $shares_po->update(['defaulted_at' => now()]);

        return redirect()->back()->with('success', 'تم تسجيل إخلال المشتري وتطبيق الإنذار/الحظر حسب سجله.');
    }
}
