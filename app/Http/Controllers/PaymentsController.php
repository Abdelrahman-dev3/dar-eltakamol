<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\SharesPO;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::with(['sharesPO'])
                          ->orderBy('date', 'desc')
                          ->paginate(15);

        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sharesPOs = SharesPO::all();
        return view('payments.create', compact('sharesPOs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'shares_po_number' => 'nullable|string|max:50',
            'bank_info' => 'nullable|string|max:500',
            'confirmed' => 'boolean',
            'transfer_document' => 'nullable|string|max:255',
        ]);

        Payment::create([
            'date' => $request->date,
            'amount' => $request->amount,
            'shares_po_number' => $request->shares_po_number,
            'bank_info' => $request->bank_info,
            'confirmed' => $request->has('confirmed'),
            'transfer_document' => $request->transfer_document,
        ]);

        return redirect()->route('payments.index')
                        ->with('success', 'تم إضافة الدفعة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load(['sharesPO']);
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $sharesPOs = SharesPO::all();
        return view('payments.edit', compact('payment', 'sharesPOs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'shares_po_number' => 'nullable|string|max:50',
            'bank_info' => 'nullable|string|max:500',
            'confirmed' => 'boolean',
            'transfer_document' => 'nullable|string|max:255',
        ]);

        $payment->update([
            'date' => $request->date,
            'amount' => $request->amount,
            'shares_po_number' => $request->shares_po_number,
            'bank_info' => $request->bank_info,
            'confirmed' => $request->has('confirmed'),
            'transfer_document' => $request->transfer_document,
        ]);

        return redirect()->route('payments.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments.index')
                        ->with('success', 'تم حذف الدفعة بنجاح');
    }

    /**
     * Toggle the confirmed status of a payment.
     */
    public function toggleConfirmed(Payment $payment)
    {
        $payment->update([
            'confirmed' => !$payment->confirmed
        ]);

        return response()->json([
            'success' => true,
            'confirmed' => $payment->confirmed
        ]);
    }
}