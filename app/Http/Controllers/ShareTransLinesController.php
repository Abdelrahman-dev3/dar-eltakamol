<?php

namespace App\Http\Controllers;

use App\Models\ShareTransLine;
use App\Models\SharesTrans;
use App\Models\Modification;
use App\Models\Contributor;
use Illuminate\Http\Request;

class ShareTransLinesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $selectedTransId = request()->query('trans_id');

        $query = ShareTransLine::with(['contributor', 'sharesTrans']);

        if ($selectedTransId) {
            $query->where('trans_id', $selectedTransId);
        }

        $shareTransLines = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('share-trans-lines.index', compact('shareTransLines', 'selectedTransId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contributors = Contributor::orderBy('name')->get();
        $sharesTrans = SharesTrans::orderByDesc('date')->get();
        $selectedTransId = request()->query('trans_id');

        return view('share-trans-lines.create', compact('contributors', 'sharesTrans', 'selectedTransId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'contributor_id' => 'required|exists:contributors,id',
            'trans_id' => 'required|exists:shares_trans,id',
            'count_debit' => 'nullable|numeric|min:0',
            'count_credit' => 'nullable|numeric|min:0',
            'amount_per_share' => 'required|numeric|min:0',
            'line_notes' => 'nullable|string|max:500',
            'posted' => 'boolean',
        ]);

        $countDebit = $request->filled('count_debit') ? (float) $request->count_debit : 0;
        $countCredit = $request->filled('count_credit') ? (float) $request->count_credit : 0;

        if ($countDebit > 0 && $countCredit > 0) {
            return redirect()->back()
                ->withErrors(['count_debit' => 'لا يمكن تعبئة الخصم والدائن معًا في نفس السطر.'])
                ->withInput();
        }

        if ($countDebit <= 0 && $countCredit <= 0) {
            return redirect()->back()
                ->withErrors(['count_debit' => 'يجب إدخال قيمة في الخصم أو الدائن.'])
                ->withInput();
        }

        ShareTransLine::create([
            'contributor_id' => $request->contributor_id,
            'trans_id' => $request->trans_id,
            'count_debit' => $countDebit,
            'count_credit' => $countCredit,
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
        $sharesTrans = SharesTrans::orderByDesc('date')->get();
        $contributors = Contributor::orderBy('name')->get();
        $shareTransLine->load(['sharesTrans', 'contributor']);

        return view('share-trans-lines.edit', compact('shareTransLine', 'sharesTrans', 'contributors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShareTransLine $shareTransLine)
    {
        $request->validate([
            'contributor_id' => 'required|exists:contributors,id',
            'trans_id' => 'required|exists:shares_trans,id',
            'count_debit' => 'nullable|numeric|min:0',
            'count_credit' => 'nullable|numeric|min:0',
            'amount_per_share' => 'required|numeric|min:0',
            'line_notes' => 'nullable|string|max:500',
            'line_notes_2' => 'required|string',
        ]);

        $countDebit = $request->filled('count_debit') ? (float) $request->count_debit : 0;
        $countCredit = $request->filled('count_credit') ? (float) $request->count_credit : 0;

        if ($countDebit > 0 && $countCredit > 0) {
            return redirect()->back()
                ->withErrors(['count_debit' => 'لا يمكن تعبئة الخصم والدائن معًا في نفس السطر.'])
                ->withInput();
        }

        if ($countDebit <= 0 && $countCredit <= 0) {
            return redirect()->back()
                ->withErrors(['count_debit' => 'يجب إدخال قيمة في الخصم أو الدائن.'])
                ->withInput();
        }

        $shareTransLine->update([
            'contributor_id' => $request->contributor_id,
            'trans_id' => $request->trans_id,
            'count_debit' => $countDebit,
            'count_credit' => $countCredit,
            'amount_per_share' => $request->amount_per_share,
            'line_notes' => $request->line_notes,
            'posted' => $request->boolean('posted'),
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

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'posted' => $shareTransLine->posted
            ]);
        }

        return redirect()->back()->with('success', 'تم تحديث حالة اعتماد السطر بنجاح');
    }
}
