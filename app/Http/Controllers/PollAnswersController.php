<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollAnswer;
use App\Models\PollOption;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PollAnswersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pollAnswers = PollAnswer::with(['poll.creator', 'pollOption', 'user'])->orderByDesc('answer_date')->paginate(10);

        return view('poll-answers.index', compact('pollAnswers'));
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
        $pollOptions = PollOption::with('poll')->orderBy('option_text')->get();
        $users = User::orderBy('name')->get();

        return view('poll-answers.create', compact('polls', 'pollOptions', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'poll_id' => 'required|exists:polls,id',
            'poll_option_id' => 'required|exists:poll_options,id',
            'user_id' => 'required|exists:users,id',
            'answer_date' => 'nullable|date',
        ]);

        $pollOption = PollOption::findOrFail($validated['poll_option_id']);
        if ((int) $pollOption->poll_id !== (int) $validated['poll_id']) {
            return redirect()->back()
                ->withErrors(['poll_option_id' => 'الخيار المحدد لا ينتمي إلى الاستطلاع المختار.'])
                ->withInput();
        }

        $existingAnswer = PollAnswer::where('poll_id', $validated['poll_id'])
            ->where('user_id', $validated['user_id'])
            ->first();

        if ($existingAnswer) {
            return redirect()->back()
                ->with('error', 'لقد أجاب هذا المستخدم على هذا الاستطلاع من قبل.')
                ->withInput();
        }

        PollAnswer::create([
            'poll_id' => $validated['poll_id'],
            'poll_option_id' => $validated['poll_option_id'],
            'user_id' => $validated['user_id'],
            'answer_date' => $validated['answer_date'] ?? now(),
        ]);

        $pollOption->increment('votes');

        return redirect()->route('poll-answers.index')
            ->with('success', 'تمت إضافة إجابة الاستطلاع بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(PollAnswer $pollAnswer)
    {
        $pollAnswer->load([
            'poll.creator',
            'poll.pollAnswers.user',
            'poll.pollOptions',
            'pollOption',
            'user',
        ]);

        return view('poll-answers.show', compact('pollAnswer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PollAnswer $pollAnswer)
    {
        $pollAnswer->load(['poll', 'pollOption', 'user']);
        $polls = Poll::with(['creator', 'pollOptions'])
            ->where('is_active', true)
            ->orWhere('id', $pollAnswer->poll_id)
            ->orderByDesc('created_date')
            ->get()
            ->unique('id')
            ->values();
        $pollOptions = PollOption::with('poll')->orderBy('option_text')->get();
        $users = User::orderBy('name')->get();

        return view('poll-answers.edit', compact('pollAnswer', 'polls', 'pollOptions', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PollAnswer $pollAnswer)
    {
        $validated = $request->validate([
            'poll_id' => 'required|exists:polls,id',
            'poll_option_id' => 'required|exists:poll_options,id',
            'user_id' => 'required|exists:users,id',
            'answer_date' => 'nullable|date',
        ]);

        $pollOption = PollOption::findOrFail($validated['poll_option_id']);
        if ((int) $pollOption->poll_id !== (int) $validated['poll_id']) {
            return redirect()->back()
                ->withErrors(['poll_option_id' => 'الخيار المحدد لا ينتمي إلى الاستطلاع المختار.'])
                ->withInput();
        }

        $existingAnswer = PollAnswer::where('poll_id', $validated['poll_id'])
            ->where('user_id', $validated['user_id'])
            ->where('id', '!=', $pollAnswer->id)
            ->first();

        if ($existingAnswer) {
            return redirect()->back()
                ->with('error', 'يوجد بالفعل إجابة أخرى لهذا المستخدم داخل الاستطلاع نفسه.')
                ->withInput();
        }

        if ((int) $pollAnswer->poll_option_id !== (int) $validated['poll_option_id']) {
            $oldOption = PollOption::find($pollAnswer->poll_option_id);
            if ($oldOption && $oldOption->votes > 0) {
                $oldOption->decrement('votes');
            }

            $pollOption->increment('votes');
        }

        $pollAnswer->update([
            'poll_id' => $validated['poll_id'],
            'poll_option_id' => $validated['poll_option_id'],
            'user_id' => $validated['user_id'],
            'answer_date' => $validated['answer_date'] ?? $pollAnswer->answer_date,
        ]);

        return redirect()->route('poll-answers.index')
            ->with('success', 'تم تحديث إجابة الاستطلاع بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PollAnswer $pollAnswer)
    {
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

        if (! $poll->isCurrentlyActive()) {
            return redirect()->back()
                ->with('error', 'هذا الاستطلاع غير نشط حاليًا');
        }

        $pollOption = PollOption::findOrFail($request->poll_option_id);
        if ((int) $pollOption->poll_id !== (int) $poll->id) {
            return redirect()->back()->with('error', 'الخيار المحدد لا ينتمي إلى هذا الاستطلاع.');
        }

        $existingVote = PollAnswer::where('poll_id', $poll->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingVote) {
            return redirect()->back()
                ->with('error', 'لقد صوتت على هذا الاستطلاع من قبل');
        }

        PollAnswer::create([
            'poll_id' => $poll->id,
            'poll_option_id' => $request->poll_option_id,
            'user_id' => Auth::id(),
            'answer_date' => now(),
        ]);

        $pollOption->increment('votes');

        return redirect()->back()
            ->with('success', 'تم تسجيل صوتك بنجاح');
    }
}
