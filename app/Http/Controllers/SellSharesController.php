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

class SellSharesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $sellShares = SellShares::with(['seller.user', 'sharesPOs'])
            ->latest('insert_date')
            ->paginate(12);
        $canCreate = $this->canCreateSellShare();
        $SOTY = Setting::getValue('SOTY', 0);

        return view('sell-shares.index', compact('sellShares', 'canCreate' , 'SOTY'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $contributors = Contributor::with('user')
            ->orderBy('name')
            ->get();

        return view('sell-shares.create', compact('contributors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'count' => 'required|numeric|min:1',
            'amount_per_share' => 'required|numeric|min:0.01',
            'end_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string|max:100',
            'user_id' => 'required|exists:contributors,id',
        ]);
        if($request->input_count < $validated['count']){
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
        $sellShare->load(['seller.user', 'sharesPOs.contributor']);
        
        return view('sell-shares.show', compact('sellShare'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SellShares $sellShare): View
    {
        $sellShare->load('seller.user');
        return view('sell-shares.edit', compact('sellShare'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SellShares $sellShare): RedirectResponse
    {
        $validated = $request->validate([
            'count' => 'required|numeric|min:1',
            'amount_per_share' => 'required|numeric|min:0.01',
            'end_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string|max:100',
            'line_notes' => 'required|string',
        ]);

        if($request->input_count < $validated['count']){
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
        $sellShare->load(['seller.user', 'sharesPOs.contributor']);
        
        return view('sell-shares.print', compact('sellShare'));
    }

    /**
     * Check if user can create sell share.
     */
    private function canCreateSellShare(): bool
    {
        // Add your business logic here
        // For example, check if there are valid sale dates
        return true;
    }
    public function getusershares($userId)
    {
        $contributor = SellShares::where('user_id', $userId)->first();
        if (!$contributor) {
            return response()->json(['message' => 'Contributor not found'], 404);
        }
        $started_at = Carbon::parse($contributor->insert_date);
        $now = Carbon::now();

        $yearsPassed = $started_at->diffInYears($now);
        
        $SOTY = Setting::getValue('SOTY', 0);
        $pers = $SOTY / 3;
        
        $eligibleYears = floor(min($yearsPassed + 1, 3));
        $availablePercentage = $eligibleYears * $pers;
        $available_shares = floor($contributor->count * $availablePercentage / 100);

        return response()->json([
            'total_shares' => $contributor->count,
            'available_shares' => $available_shares,
            'eligible_years' => $eligibleYears,
            'available_percentage' => $availablePercentage,
        ]);
    }

}
