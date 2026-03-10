<?php

namespace App\Http\Controllers;

use App\Models\UsersProfit;
use App\Models\User;
use App\Models\Profit;
use App\Models\Contributor;
use Illuminate\Http\Request;
use App\Models\Modification;

class UsersProfitsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usersProfits = UsersProfit::with(['contributor', 'profit'])->orderBy('created_at', 'desc')->paginate(10);

        return view('users-profits.index', compact('usersProfits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contributors = Contributor::with('user')->get();
        $profits = Profit::where('confirmed', true)->get();

        return view('users-profits.create', compact('contributors', 'profits'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'contributor_id' => 'required|exists:contributors,id',
            'profits_id' => 'required|exists:profits,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'nullable|date',
            'is_paid' => 'boolean',
        ]);

        UsersProfit::create([
            'contributor_id' => $request->contributor_id,
            'profits_id' => $request->profits_id,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'is_paid' => $request->has('is_paid'),
        ]);

        return redirect()->route('users-profits.index')
                        ->with('success', 'تم إضافة ربح المستخدم بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(UsersProfit $usersProfit)
    {
        $usersProfit->load(['contributor.user', 'profit']);
        return view('users-profits.show', compact('usersProfit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UsersProfit $usersProfit)
    {
        $usersProfit->load(['contributor.user', 'profit']);
        $contributors = Contributor::with('user')->get();
        $profits = Profit::where('confirmed', true)->get();

        return view('users-profits.edit', compact('usersProfit', 'contributors', 'profits'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UsersProfit $usersProfit)
    {
        $request->validate([
            'contributor_id' => 'required|exists:contributors,id',
            'profits_id' => 'required|exists:profits,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'nullable|date',
            'is_paid' => 'boolean',
            'line_notes' => 'required|string',
        ]);

        $usersProfit->update([
            'contributor_id' => $request->contributor_id,
            'profits_id' => $request->profits_id,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'is_paid' => $request->has('is_paid'),
        ]);

        $url = url()->previous();
        Modification::logChange($url , $request->line_notes , auth()->user()->id);

        return redirect()->route('users-profits.index')
                        ->with('success', 'تم تحديث ربح المستخدم بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UsersProfit $usersProfit)
    {
        $usersProfit->delete();

        return redirect()->route('users-profits.index')
                        ->with('success', 'تم حذف ربح المستخدم بنجاح');
    }

    /**
     * Mark profit as paid.
     */
    public function markAsPaid(UsersProfit $usersProfit)
    {
        $usersProfit->update([
            'is_paid' => true,
            'payment_date' => now(),
        ]);

        return redirect()->back()
                        ->with('success', 'تم تسجيل الدفع بنجاح');
    }

    /**
     * Get user's profits.
     */
    public function userProfits(User $user)
    {
        $userProfits = UsersProfit::where('contributor_id', $user->id)
                                 ->with('profit')
                                 ->orderBy('created_at', 'desc')
                                 ->paginate(10);

        return view('users-profits.user-profits', compact('userProfits', 'user'));
    }
}
