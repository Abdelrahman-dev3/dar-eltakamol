<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contributor;
use App\Models\SellShares;
use App\Models\SharesTrans;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $contributorsCount = Contributor::count();
        $sellSharesCount = SellShares::count();
        $transactionsCount = SharesTrans::count();
        $totalShares = Contributor::sum('share_count_cr');

        return view('dashboard', compact(
            'contributorsCount',
            'sellSharesCount', 
            'transactionsCount',
            'totalShares'
        ));
    }
}
