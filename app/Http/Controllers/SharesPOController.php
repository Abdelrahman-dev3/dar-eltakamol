<?php

namespace App\Http\Controllers;

use App\Models\SharesPO;
use App\Models\Contributor;
use App\Models\SellShares;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Modification;

class SharesPOController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sharesPOs = SharesPO::with(['contributor', 'sellShare'])->orderBy('insert_date', 'desc')->paginate(15);
        return view('shares-pos.index', compact('sharesPOs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contributors = Contributor::all();
        $sellShares = SellShares::all();
        $stock = Setting::getValue('base_price', 0);
        
        return view('shares-pos.create', compact('contributors', 'sellShares' , 'stock'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $basePrice = Setting::getValue('base_price', 0);
        
        $request->validate([
            'user_id' => 'required|exists:contributors,id',
            'sale_number' => 'nullable|string|max:50',
            'count' => 'required|numeric|min:0',
            'amount_per_share' => 'required|numeric|min:0',
            'accept' => 'boolean',
            'insert_date' => 'required|date',
            'po_status' => 'required|integer|between:0,2',
        ]);
        if($request->amount_per_share < $basePrice){
            return redirect()->back()->withInput()->withErrors(['amount_per_share' => 'يجب ألا يقل سعر السهم عن ' . $basePrice . ' ريال.']);
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

        return redirect()->route('shares-pos.index')->with('success', 'تم إضافة طلب الشراء بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(SharesPO $shares_po)
    {
        $shares_po->load(['contributor', 'sellShare']);
        return view('shares-pos.show', ['sharesPO' => $shares_po]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SharesPO $shares_po)
    {
        $contributors = Contributor::all();
        $sellShares = SellShares::all();
        $stock = Setting::getValue('base_price', 0);

        return view('shares-pos.edit', ['sharesPO' => $shares_po, 'contributors' => $contributors, 'sellShares' => $sellShares , 'stock' => $stock]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SharesPO $shares_po)
    {
        $basePrice = Setting::getValue('base_price', 0);
        $request->validate([
            'user_id' => 'required|exists:contributors,id',
            'sale_number' => 'nullable|string|max:50',
            'count' => 'required|numeric|min:0',
            'amount_per_share' => 'required|numeric|min:0',
            'accept' => 'boolean',
            'insert_date' => 'required|date',
            'po_status' => 'required|integer|between:0,2',
            'line_notes' => 'required|string',
        ]);

        if($request->amount_per_share < $basePrice){
            return redirect()->back()->withInput()->withErrors(['amount_per_share' => 'يجب ألا يقل سعر السهم عن ' . $basePrice . ' ريال.']);
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

        $url = url()->previous();
        Modification::logChange($url , $request->line_notes , auth()->user()->id);

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
            'accept' => !$shares_po->accept
        ]);

        return response()->json([
            'success' => true,
            'accept' => $shares_po->accept
        ]);
    }
}