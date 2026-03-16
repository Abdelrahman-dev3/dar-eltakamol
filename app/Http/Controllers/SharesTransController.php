<?php

namespace App\Http\Controllers;

use App\Models\SharesTrans;
use Illuminate\Http\Request;
use App\Models\Modification;

class SharesTransController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sharesTrans = SharesTrans::with('shareTransLines')
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('shares-trans.index', compact('sharesTrans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('shares-trans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'notes' => 'nullable|string|max:500',
            'trans_type' => 'required|integer|in:1,2,3,4',
        ]);

        SharesTrans::create([
            'date' => $request->date,
            'notes' => $request->notes,
            'trans_type' => $request->trans_type,
            'posted' => false,
        ]);

        return redirect()->route('shares-trans.index')
            ->with('success', 'تم إضافة معاملة الأسهم بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(SharesTrans $shares_tran)
    {
        $shares_tran->load('shareTransLines.contributor.user');
        return view('shares-trans.show', compact('shares_tran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SharesTrans $shares_tran)
    {
        return view('shares-trans.edit', compact('shares_tran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SharesTrans $shares_tran)
    {
        $request->validate([
            'date' => 'required|date',
            'notes' => 'nullable|string|max:500',
            'trans_type' => 'required|integer|in:1,2,3,4',
            'line_notes' => 'required|string',
        ]);

        $shares_tran->update([
            'date' => $request->date,
            'notes' => $request->notes,
            'trans_type' => $request->trans_type,
        ]);

        $url = url()->previous();
        Modification::logChange($url, $request->line_notes, auth()->user()->id);

        return redirect()->route('shares-trans.index')->with('success', 'تم تحديث معاملة الأسهم بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SharesTrans $shares_tran)
    {
        $shares_tran->delete();
        return redirect()->route('shares-trans.index')
            ->with('success', 'تم حذف معاملة الأسهم بنجاح');
    }

    /**
     * Post the transaction.
     */
    public function post(Request $request, SharesTrans $sharesTrans)
    {
        $sharesTrans->update(['posted' => true]);
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'posted' => $sharesTrans->posted,
            ]);
        }
        return redirect()->back()->with('success', 'تم اعتماد معاملة الأسهم بنجاح');
    }
}
