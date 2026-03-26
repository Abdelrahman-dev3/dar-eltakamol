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
        $pollOptions = PollOption::with(['poll.creator'])->orderByDesc('created_at')->paginate(10);

        return view('poll-options.index', compact('pollOptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $polls = Poll::with(['creator', 'pollOptions'])
            ->where('is_active', true)
            ->orderByDesc('created_date')
            ->get();

        return view('poll-options.create', compact('polls'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'poll_id' => 'required|exists:polls,id',
            'option_text' => 'required|string|max:255',
            'votes' => 'nullable|integer|min:0',
        ]);

        PollOption::create([
            'poll_id' => $validated['poll_id'],
            'poll_question_id' => null,
            'option_text' => trim($validated['option_text']),
            'votes' => $validated['votes'] ?? 0,
        ]);

        if ($request->boolean('return_to_poll')) {
            return redirect()->back()->with('success', 'تمت إضافة خيار جديد للاستطلاع بنجاح');
        }

        return redirect()->route('poll-options.index')->with('success', 'تم إنشاء خيار الاستطلاع بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(PollOption $pollOption)
    {
        $pollOption->load([
            'poll.creator',
            'poll.pollAnswers.user',
            'poll.pollOptions',
        ]);

        return view('poll-options.show', compact('pollOption'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PollOption $pollOption)
    {
        $pollOption->load('poll');
        $polls = Poll::with(['creator', 'pollOptions'])
            ->where('is_active', true)
            ->orWhere('id', $pollOption->poll_id)
            ->orderByDesc('created_date')
            ->get()
            ->unique('id')
            ->values();

        return view('poll-options.edit', compact('pollOption', 'polls'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PollOption $pollOption)
    {
        $validated = $request->validate([
            'poll_id' => 'required|exists:polls,id',
            'option_text' => 'required|string|max:255',
            'votes' => 'nullable|integer|min:0',
        ]);

        $pollOption->update([
            'poll_id' => $validated['poll_id'],
            'poll_question_id' => null,
            'option_text' => trim($validated['option_text']),
            'votes' => $validated['votes'] ?? 0,
        ]);

        if ($request->boolean('return_to_poll')) {
            return redirect()->back()->with('success', 'تم تحديث خيار الاستطلاع بنجاح');
        }

        return redirect()->route('poll-options.index')
            ->with('success', 'تم تحديث خيار الاستطلاع بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, PollOption $pollOption)
    {
        $pollOption->delete();

        if ($request->boolean('return_to_poll')) {
            return redirect()->back()->with('success', 'تم حذف خيار الاستطلاع بنجاح');
        }

        return redirect()->route('poll-options.index')
            ->with('success', 'تم حذف خيار الاستطلاع بنجاح');
    }
}
