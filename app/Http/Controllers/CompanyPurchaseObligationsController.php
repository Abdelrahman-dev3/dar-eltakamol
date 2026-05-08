<?php

namespace App\Http\Controllers;

use App\Models\CompanyPurchaseObligation;
use Illuminate\Http\Request;

class CompanyPurchaseObligationsController extends Controller
{
    public function index()
    {
        $obligations = CompanyPurchaseObligation::with(['sellShare', 'seller'])
            ->orderBy('due_year')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('company-purchase-obligations.index', compact('obligations'));
    }

    public function show(CompanyPurchaseObligation $company_purchase_obligation)
    {
        $company_purchase_obligation->load(['sellShare', 'seller']);

        return view('company-purchase-obligations.show', ['obligation' => $company_purchase_obligation]);
    }

    public function edit(CompanyPurchaseObligation $company_purchase_obligation)
    {
        $company_purchase_obligation->load(['sellShare', 'seller']);

        return view('company-purchase-obligations.edit', ['obligation' => $company_purchase_obligation]);
    }

    public function update(Request $request, CompanyPurchaseObligation $company_purchase_obligation)
    {
        $validated = $request->validate([
            'amount_per_share' => 'nullable|numeric|min:0',
            'fair_value' => 'nullable|numeric|min:0',
            'payment_kind' => 'required|string|in:cash,in_kind',
            'selected_appraiser' => 'nullable|string|max:255',
            'appraisers' => 'nullable|string|max:1000',
            'valuation_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'status' => 'required|string|in:scheduled,paid,cancelled',
            'notes' => 'nullable|string|max:2000',
        ]);

        $appraisers = collect(explode("\n", (string) ($validated['appraisers'] ?? '')))
            ->map(fn ($value) => trim($value))
            ->filter()
            ->values()
            ->all();

        $amountPerShare = $validated['amount_per_share'] ?? $company_purchase_obligation->amount_per_share;
        $company_purchase_obligation->update([
            ...$validated,
            'appraisers' => $appraisers,
            'total_amount' => $amountPerShare !== null
                ? round((float) $company_purchase_obligation->shares_count * (float) $amountPerShare, 2)
                : $company_purchase_obligation->total_amount,
        ]);

        return redirect()->route('company-purchase-obligations.show', $company_purchase_obligation)
            ->with('success', 'تم تحديث التزام شراء الشركة بنجاح');
    }
}
