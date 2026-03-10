<?php

namespace App\Http\Controllers;

use App\Models\Profit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfitsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profits = Profit::orderBy('created_at', 'desc')
                         ->paginate(10);

        return view('profits.index', compact('profits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('profits.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'end_date' => 'required|date|after:date',
            'amount' => 'required|numeric|min:0',
            'confirmed' => 'boolean',
        ]);

        Profit::create([
            'date' => $request->date,
            'end_date' => $request->end_date,
            'amount' => $request->amount,
            'confirmed' => $request->has('confirmed'),
        ]);

        return redirect()->route('profits.index')
                        ->with('success', 'تم إنشاء نوع الربح بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Profit $profit)
    {
        $profit->load('usersProfits.contributor.user');
        return view('profits.show', compact('profit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profit $profit)
    {
        return view('profits.edit', compact('profit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Profit $profit)
    {
        $request->validate([
            'date' => 'required|date',
            'end_date' => 'required|date|after:date',
            'amount' => 'required|numeric|min:0',
            'confirmed' => 'boolean',
        ]);

        $profit->update([
            'date' => $request->date,
            'end_date' => $request->end_date,
            'amount' => $request->amount,
            'confirmed' => $request->has('confirmed'),
        ]);

        return redirect()->route('profits.index')
                        ->with('success', 'تم تحديث نوع الربح بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profit $profit)
    {
        $profit->delete();

        return redirect()->route('profits.index')
                        ->with('success', 'تم حذف نوع الربح بنجاح');
    }

    /**
     * Toggle confirmed status.
     */
    public function toggleActive(Profit $profit)
    {
        $profit->update(['confirmed' => !$profit->confirmed]);

        $status = $profit->confirmed ? 'مؤكد' : 'غير مؤكد';
        return redirect()->back()
                        ->with('success', "تم تغيير حالة الربح إلى {$status}");
    }
}
