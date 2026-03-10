<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modification;

class ModifyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index(Request $request)
    {
        $edits = Modification::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('modify.index' , compact('edits'));
    }
}
