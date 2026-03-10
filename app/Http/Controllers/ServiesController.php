<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class ServiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sercies = Service::all();
        return view('servies.index' , compact('sercies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('servies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'service' => 'required|string|max:255',
        ]);

        Service::create([
            'name' => $request->service,
        ]);

        return redirect()->route('servies.index')->with('success', 'تم اضافة الخدمة بنجاح');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('servies.edit' , compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'service' => 'required|string|max:255',
        ]);

        $sercies = Service::findOrFail($id);
        $sercies->update([
            'name' => $request->service,
        ]);

        return redirect()->route('servies.index')->with('success', 'تم تحديث الخدمة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $sercies = Service::findOrFail($id);
        $sercies->delete();
        return redirect()->back()->with('success', 'تم حذف الخدمة بنجاح');
    }
}
