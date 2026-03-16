<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\SharesPO;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    protected function buildStats(): array
    {
        $confirmedAmount = (float) Payment::where('confirmed', true)->sum('amount');
        $pendingAmount = (float) Payment::where('confirmed', false)->sum('amount');

        return [
            'total_count' => Payment::count(),
            'confirmed_count' => Payment::where('confirmed', true)->count(),
            'pending_count' => Payment::where('confirmed', false)->count(),
            'confirmed_amount' => $confirmedAmount,
            'pending_amount' => $pendingAmount,
            'total_amount' => $confirmedAmount + $pendingAmount,
            'average_amount' => (float) Payment::avg('amount'),
            'max_amount' => (float) Payment::max('amount'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::with(['sharesPO.contributor'])
            ->orderBy('date', 'desc')
            ->paginate(15)
            ->withQueryString();

        $stats = $this->buildStats();

        return view('payments.index', compact('payments', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sharesPOs = SharesPO::with('contributor')
            ->orderByDesc('insert_date')
            ->get();
        $stats = $this->buildStats();

        return view('payments.create', compact('sharesPOs', 'stats'));
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
        $payment->load(['sharesPO.contributor', 'sharesPO.sellShare']);

        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $payment->load(['sharesPO.contributor', 'sharesPO.sellShare']);

        $sharesPOs = SharesPO::with('contributor')
            ->orderByDesc('insert_date')
            ->get();
        $stats = $this->buildStats();

        return view('payments.edit', compact('payment', 'sharesPOs', 'stats'));
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

        return redirect()->route('payments.index')
            ->with('success', 'تم تحديث بيانات الدفعة بنجاح');
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
            'confirmed' => !$payment->confirmed,
        ]);

        if (!request()->expectsJson()) {
            return redirect()->back()->with(
                'success',
                $payment->confirmed ? 'تم تأكيد الدفعة بنجاح' : 'تم إلغاء تأكيد الدفعة بنجاح'
            );
        }

        return response()->json([
            'success' => true,
            'confirmed' => $payment->confirmed,
        ]);
    }
}
