<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Http\Request;

class PollOptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pollOptions = PollOption::with(['poll'])
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);

        return view('poll-options.index', compact('pollOptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $polls = Poll::where('is_active', true)->get();
        return view('poll-options.create', compact('polls'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'poll_id' => 'required|exists:polls,id',
            'option_text' => 'required|string|max:255',
        ]);

        PollOption::create([
            'poll_id' => $request->poll_id,
            'option_text' => $request->option_text,
            'votes' => 0,
        ]);

        return redirect()->route('poll-options.index')
                        ->with('success', 'تم إنشاء خيار الاستطلاع بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(PollOption $pollOption)
    {
        $pollOption->load('poll');
        return view('poll-options.show', compact('pollOption'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PollOption $pollOption)
    {
        $polls = Poll::where('is_active', true)->get();
        return view('poll-options.edit', compact('pollOption', 'polls'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PollOption $pollOption)
    {
        $request->validate([
            'poll_id' => 'required|exists:polls,id',
            'option_text' => 'required|string|max:255',
        ]);

        $pollOption->update([
            'poll_id' => $request->poll_id,
            'option_text' => $request->option_text,
        ]);

        return redirect()->route('poll-options.index')
                        ->with('success', 'تم تحديث خيار الاستطلاع بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PollOption $pollOption)
    {
        $pollOption->delete();

        return redirect()->route('poll-options.index')
                        ->with('success', 'تم حذف خيار الاستطلاع بنجاح');
    }
}
