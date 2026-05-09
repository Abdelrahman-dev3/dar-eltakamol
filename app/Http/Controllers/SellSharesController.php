<?php

namespace App\Http\Controllers;

use App\Models\SellShares;
use App\Models\Contributor;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Setting;
use Carbon\Carbon;
use App\Models\Modification;
use App\Services\SellShareAnnualLimitService;
use App\Services\SellShareSettlementService;
use App\Services\TradingWindowService;
use Illuminate\Validation\ValidationException;

class SellSharesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $sellSharesQuery = SellShares::with(['seller.user', 'sharesPOs.contributor', 'settlement.allocations.buyer'])
            ->latest('insert_date');

        if (!auth()->user()?->isAdmin()) {
            $sellSharesQuery->where('ad_status', '!=', SellShares::AD_STATUS_CANCELLED);
        }

        $sellShares = $sellSharesQuery->paginate(12);
        $tradingWindow = app(TradingWindowService::class);
        $canCreate = $this->canCreateSellShare();
        $currentPeriod = $tradingWindow->currentPeriod();
        $currentPhase = $tradingWindow->currentPhase();
        $SOTY = Setting::getValue('SOTY', 0);

        return view('sell-shares.index', compact('sellShares', 'canCreate' , 'SOTY', 'currentPeriod', 'currentPhase'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $contributors = Contributor::with('user')
            ->orderBy('name')
            ->get();
        $tradingWindow = app(TradingWindowService::class);
        $currentPeriod = $tradingWindow->currentPeriod();
        $currentPhase = $tradingWindow->currentPhase();

        return view('sell-shares.create', compact('contributors', 'currentPeriod', 'currentPhase'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        app(TradingWindowService::class)->assertMarketEntryAllowed('لا يمكن إنشاء عرض بيع خارج مراحل العرض والصفقات الخاصة.');

        $validated = $request->validate([
            'count' => 'required|numeric|min:1',
            'amount_per_share' => 'required|numeric|min:0.01',
            'end_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string|max:100',
            'user_id' => 'required|exists:contributors,id',
        ]);
        $seller = Contributor::findOrFail($validated['user_id']);
        app(SellShareAnnualLimitService::class)->assertWithinLimit($seller, (float) $validated['count']);

        if($request->filled('input_count') && $request->input_count < $validated['count']){
            return redirect()->back()->withErrors(['count' => 'عدد الأسهم المطلوب بيعه يتجاوز الأسهم المتاحة للبيع.'])->withInput();
        }

        $validated['insert_date'] = now();
        $validated['ad_status'] = SellShares::AD_STATUS_INITIAL;

        SellShares::create($validated);

        return redirect()->route('sell-shares.index')
            ->with('success', 'تم إضافة عرض البيع بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(SellShares $sellShare): View
    {
        if ((int) $sellShare->ad_status === SellShares::AD_STATUS_CANCELLED && !auth()->user()?->isAdmin()) {
            abort(404);
        }

        $sellShare->load(['seller.user', 'sharesPOs.contributor', 'settlement.allocations.buyer', 'companyPurchaseObligations']);
        $canEditSellShare = $this->canEditSellShare($sellShare);
        $annualRemaining = $sellShare->seller
            ? app(SellShareAnnualLimitService::class)->remaining($sellShare->seller, $sellShare)
            : 0;
        
        return view('sell-shares.show', compact('sellShare', 'annualRemaining', 'canEditSellShare'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SellShares $sellShare): View
    {
        if (!$this->canEditSellShare($sellShare)) {
            abort(403, $this->sellShareEditBlockedMessage($sellShare));
        }

        $sellShare->load('seller.user', 'sharesPOs');
        return view('sell-shares.edit', compact('sellShare'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SellShares $sellShare): RedirectResponse
    {
        if (!$this->canEditSellShare($sellShare)) {
            throw ValidationException::withMessages([
                'amount_per_share' => $this->sellShareEditBlockedMessage($sellShare),
            ]);
        }

        $validated = $request->validate([
            'count' => 'required|numeric|min:1',
            'amount_per_share' => 'required|numeric|min:0.01',
            'end_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string|max:100',
            'line_notes' => 'required|string',
        ]);

        $seller = $sellShare->seller()->firstOrFail();
        app(SellShareAnnualLimitService::class)->assertWithinLimit($seller, (float) $validated['count'], $sellShare);

        if($request->filled('input_count') && $request->input_count < $validated['count']){
            return redirect()->back()->withErrors(['count' => 'عدد الأسهم المطلوب بيعه يتجاوز الأسهم المتاحة للبيع.'])->withInput();
        }

        $sellShare->update($validated);

        $url = url()->previous();
        Modification::logChange($url , $request->line_notes , auth()->user()->id);

        return redirect()->route('sell-shares.index')
            ->with('success', 'تم تحديث عرض البيع بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SellShares $sellShare): RedirectResponse
    {
        $sellShare->delete();

        return redirect()->route('sell-shares.index')
            ->with('success', 'تم حذف عرض البيع بنجاح');
    }

    /**
     * Print the sell share.
     */
    public function print(SellShares $sellShare): View
    {
        if ((int) $sellShare->ad_status === SellShares::AD_STATUS_CANCELLED && !auth()->user()?->isAdmin()) {
            abort(404);
        }

        $sellShare->load(['seller.user', 'sharesPOs.contributor']);
        
        return view('sell-shares.print', compact('sellShare'));
    }

    /**
     * Check if user can create sell share.
     */
    private function canCreateSellShare(): bool
    {
        return app(TradingWindowService::class)->canCreateMarketEntry();
    }

    private function canEditSellShare(SellShares $sellShare): bool
    {
        if (!app(TradingWindowService::class)->canChangePrice()) {
            return false;
        }

        if (in_array((int) $sellShare->ad_status, [SellShares::AD_STATUS_COMPLETED, SellShares::AD_STATUS_CANCELLED], true)) {
            return false;
        }

        return !$sellShare->sharesPOs()->exists();
    }

    private function sellShareEditBlockedMessage(SellShares $sellShare): string
    {
        if (!app(TradingWindowService::class)->canChangePrice()) {
            return 'لا يمكن تعديل السعر أو كمية الأسهم إلا خلال الفترة الأولى من العرض.';
        }

        if ($sellShare->sharesPOs()->exists()) {
            return 'لا يمكن تعديل السعر أو كمية الأسهم بعد وجود طلبات شراء مرتبطة بهذا العرض.';
        }

        return 'لا يمكن تعديل هذا العرض في حالته الحالية.';
    }

    public function settle(SellShares $sellShare): RedirectResponse
    {
        if ((int) $sellShare->ad_status === SellShares::AD_STATUS_CANCELLED) {
            return redirect()->back()->with('error', 'لا يمكن تسوية عرض بيع مغلق ومؤرشف.');
        }

        $settlement = app(SellShareSettlementService::class)->settle($sellShare, auth()->id());

        return redirect()->route('sell-shares.show', $sellShare)
            ->with('success', 'تمت تسوية عرض البيع بعدد تخصيصات: ' . $settlement->allocations()->count());
    }
    public function close(SellShares $sellShare): RedirectResponse
    {
        abort_unless(auth()->user()?->isAdmin(), 403, 'هذا الإجراء متاح لحساب الادمن فقط.');

        if ((int) $sellShare->ad_status === SellShares::AD_STATUS_CANCELLED) {
            return redirect()->back()->with('success', 'عرض البيع مغلق ومؤرشف مسبقًا.');
        }

        $sellShare->update([
            'ad_status' => SellShares::AD_STATUS_CANCELLED,
        ]);

        return redirect()->route('sell-shares.index')
            ->with('success', 'تم إغلاق عرض البيع وأرشفته بنجاح. لن يظهر للآخرين ولن يقبل طلبات شراء جديدة.');
    }

    public function getusershares($userId)
    {
        $contributor = Contributor::find($userId);
        if (!$contributor) {
            return response()->json(['message' => 'Contributor not found'], 404);
        }
        $available_shares = app(SellShareAnnualLimitService::class)->remaining($contributor);

        return response()->json([
            'total_shares' => (float) $contributor->share_count_cr,
            'available_shares' => $available_shares,
            'eligible_years' => 1,
            'available_percentage' => 25,
        ]);
    }

}
