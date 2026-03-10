<?php

namespace App\Http\Controllers;

use App\Models\ShareTransLine;
use App\Models\SharesTrans;
use App\Models\Modification;
use Illuminate\Http\Request;

class ShareTransLinesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shareTransLines = ShareTransLine::with(['contributor'])->orderBy('created_at', 'desc')->paginate(10);

        return view('share-trans-lines.index', compact('shareTransLines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contributors = \App\Models\Contributor::all();
        return view('share-trans-lines.create', compact('contributors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'contributor_id' => 'required|exists:contributors,id',
            'trans_id' => 'required|string|max:50',
            'count_debit' => 'nullable|numeric|min:0',
            'count_credit' => 'nullable|numeric|min:0',
            'amount_per_share' => 'required|numeric|min:0',
            'line_notes' => 'nullable|string|max:500',
            'posted' => 'boolean',
        ]);

        ShareTransLine::create([
            'contributor_id' => $request->contributor_id,
            'trans_id' => $request->trans_id,
            'count_debit' => $request->count_debit ?? 0,
            'count_credit' => $request->count_credit ?? 0,
            'amount_per_share' => $request->amount_per_share,
            'line_notes' => $request->line_notes,
            'posted' => $request->has('posted'),
        ]);

        return redirect()->route('share-trans-lines.index')
                        ->with('success', 'تم إضافة تفصيل معاملة الأسهم بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(ShareTransLine $shareTransLine)
    {
        $shareTransLine->load(['sharesTrans', 'contributor']);
        return view('share-trans-lines.show', compact('shareTransLine'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShareTransLine $shareTransLine)
    {
        $sharesTrans = SharesTrans::all();
        return view('share-trans-lines.edit', compact('shareTransLine', 'sharesTrans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShareTransLine $shareTransLine)
    {
        
        $request->validate([
            // 'shares_trans_id' => 'required|exists:shares_trans,id',
            // 'share_count' => 'required|integer|min:1',
            // 'share_price' => 'required|numeric|min:0',
            'line_notes_2' => 'required|string',
        ]);
        $shareTransLine->update([
            'shares_trans_id' => $request->shares_trans_id,
            'share_count' => $request->share_count,
            'share_price' => $request->share_price,
            'total_amount' => $request->share_count * $request->share_price,
        ]);

        $url = url()->previous();
        Modification::logChange($url , $request->line_notes_2 , auth()->user()->id);


        return redirect()->route('share-trans-lines.index')
                        ->with('success', 'تم تحديث تفصيل معاملة الأسهم بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShareTransLine $shareTransLine)
    {
        $shareTransLine->delete();

        return redirect()->route('share-trans-lines.index')
                        ->with('success', 'تم حذف تفصيل معاملة الأسهم بنجاح');
    }

    /**
     * Toggle the posted status of a transaction line.
     */
    public function togglePosted(ShareTransLine $shareTransLine)
    {
        $shareTransLine->update([
            'posted' => !$shareTransLine->posted
        ]);

        return response()->json([
            'success' => true,
            'posted' => $shareTransLine->posted
        ]);
    }
}
