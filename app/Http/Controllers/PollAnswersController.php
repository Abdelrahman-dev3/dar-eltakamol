<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollAnswer;
use App\Models\PollOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PollAnswersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pollAnswers = PollAnswer::with(['poll', 'pollOption', 'user'])
                                ->orderBy('answer_date', 'desc')
                                ->paginate(10);

        return view('poll-answers.index', compact('pollAnswers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $polls = Poll::where('is_active', true)->get();
        $pollOptions = PollOption::all();
        $users = \App\Models\User::all();

        return view('poll-answers.create', compact('polls', 'pollOptions', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'poll_id' => 'required|exists:polls,id',
            'poll_option_id' => 'required|exists:poll_options,id',
            'user_id' => 'required|exists:users,id',
        ]);

        // Check if user already answered this poll
        $existingAnswer = PollAnswer::where('poll_id', $request->poll_id)
                                   ->where('user_id', $request->user_id)
                                   ->first();

        if ($existingAnswer) {
            return redirect()->back()
                           ->with('error', 'لقد أجبت على هذا الاستطلاع من قبل');
        }

        // Create the answer
        $pollAnswer = PollAnswer::create([
            'poll_id' => $request->poll_id,
            'poll_option_id' => $request->poll_option_id,
            'user_id' => $request->user_id,
            'answer_date' => now(),
        ]);

        // Update vote count
        $pollOption = PollOption::find($request->poll_option_id);
        $pollOption->increment('votes');

        return redirect()->route('poll-answers.index')
                        ->with('success', 'تم إضافة إجابة الاستطلاع بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(PollAnswer $pollAnswer)
    {
        $pollAnswer->load(['poll', 'pollOption', 'user']);
        return view('poll-answers.show', compact('pollAnswer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PollAnswer $pollAnswer)
    {
        $polls = Poll::where('is_active', true)->get();
        $pollOptions = PollOption::all();
        $users = \App\Models\User::all();

        return view('poll-answers.edit', compact('pollAnswer', 'polls', 'pollOptions', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PollAnswer $pollAnswer)
    {
        $request->validate([
            'poll_id' => 'required|exists:polls,id',
            'poll_option_id' => 'required|exists:poll_options,id',
            'user_id' => 'required|exists:users,id',
        ]);

        // If changing the option, update vote counts
        if ($pollAnswer->poll_option_id != $request->poll_option_id) {
            // Decrease old option votes
            $oldOption = PollOption::find($pollAnswer->poll_option_id);
            if ($oldOption && $oldOption->votes > 0) {
                $oldOption->decrement('votes');
            }

            // Increase new option votes
            $newOption = PollOption::find($request->poll_option_id);
            $newOption->increment('votes');
        }

        $pollAnswer->update([
            'poll_id' => $request->poll_id,
            'poll_option_id' => $request->poll_option_id,
            'user_id' => $request->user_id,
        ]);

        return redirect()->route('poll-answers.index')
                        ->with('success', 'تم تحديث إجابة الاستطلاع بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PollAnswer $pollAnswer)
    {
        // Decrease vote count
        $pollOption = PollOption::find($pollAnswer->poll_option_id);
        if ($pollOption && $pollOption->votes > 0) {
            $pollOption->decrement('votes');
        }

        $pollAnswer->delete();

        return redirect()->route('poll-answers.index')
                        ->with('success', 'تم حذف إجابة الاستطلاع بنجاح');
    }

    /**
     * Vote on a poll (for frontend users).
     */
    public function vote(Request $request, Poll $poll)
    {
        $request->validate([
            'poll_option_id' => 'required|exists:poll_options,id',
        ]);

        // Check if poll is active
        if (!$poll->isCurrentlyActive()) {
            return redirect()->back()
                           ->with('error', 'هذا الاستطلاع غير نشط حالياً');
        }

        // Check if user already voted
        $existingVote = PollAnswer::where('poll_id', $poll->id)
                                ->where('user_id', Auth::id())
                                ->first();

        if ($existingVote) {
            return redirect()->back()
                           ->with('error', 'لقد صوتت على هذا الاستطلاع من قبل');
        }

        // Create vote
        PollAnswer::create([
            'poll_id' => $poll->id,
            'poll_option_id' => $request->poll_option_id,
            'user_id' => Auth::id(),
            'answer_date' => now(),
        ]);

        // Update vote count
        $pollOption = PollOption::find($request->poll_option_id);
        $pollOption->increment('votes');

        return redirect()->back()
                        ->with('success', 'تم تسجيل صوتك بنجاح');
    }
}
