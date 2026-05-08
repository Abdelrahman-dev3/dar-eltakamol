<?php

namespace App\Http\Controllers;

use App\Models\TradingPeriod;
use Illuminate\Http\Request;

class TradingPeriodsController extends Controller
{
    public function index()
    {
        $periods = TradingPeriod::orderByDesc('year')->orderBy('offer_starts_at')->paginate(15);

        return view('trading-periods.index', compact('periods'));
    }

    public function create()
    {
        return view('trading-periods.create', ['period' => new TradingPeriod()]);
    }

    public function store(Request $request)
    {
        TradingPeriod::create($this->validated($request));

        return redirect()->route('trading-periods.index')->with('success', 'تم إنشاء فترة التداول بنجاح');
    }

    public function edit(TradingPeriod $trading_period)
    {
        return view('trading-periods.edit', ['period' => $trading_period]);
    }

    public function update(Request $request, TradingPeriod $trading_period)
    {
        $trading_period->update($this->validated($request));

        return redirect()->route('trading-periods.index')->with('success', 'تم تحديث فترة التداول بنجاح');
    }

    public function destroy(TradingPeriod $trading_period)
    {
        $trading_period->delete();

        return redirect()->route('trading-periods.index')->with('success', 'تم حذف فترة التداول بنجاح');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'year' => 'required|integer|min:2000|max:2100',
            'name' => 'required|string|max:120',
            'offer_starts_at' => 'required|date',
            'offer_ends_at' => 'required|date|after_or_equal:offer_starts_at',
            'processing_starts_at' => 'required|date|after:offer_ends_at',
            'processing_ends_at' => 'required|date|after_or_equal:processing_starts_at',
            'private_starts_at' => 'required|date|after:processing_ends_at',
            'private_ends_at' => 'required|date|after_or_equal:private_starts_at',
            'is_active' => 'boolean',
        ]) + ['is_active' => $request->boolean('is_active')];
    }
}
