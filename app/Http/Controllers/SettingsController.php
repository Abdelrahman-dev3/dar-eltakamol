<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        $stock = Setting::getValue('base_price', 0);
        $SOTY = Setting::getValue('SOTY', 0);
        $trading_period_open = Setting::getValue('trading_period_open', false);
        return view('settings.index' , compact('stock' , 'SOTY', 'trading_period_open'));
    }

    public function store(Request $request)
    {
        if($request->has('settings')){
            foreach($request->settings as $key => $value){
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }

        return redirect()->back()->with('success', 'تم حفظ الإعدادات بنجاح');
    }
}
