<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\ZoomMeeting;
use App\Models\User;
use App\Models\Category;
use App\Services\ParticipantAudienceResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PollsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $polls = Poll::with(['pollOptions', 'creator', 'referencedUsers'])
            ->orderBy('created_date', 'desc')
            ->paginate(10);

        return view('polls.index', compact('polls'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ParticipantAudienceResolver $audienceResolver)
    {
        $zoomMeetings = ZoomMeeting::orderBy('meeting_date', 'desc')->get();
        $users = User::orderBy('name')->get();
        $audienceScopes = $audienceResolver->scopeOptions();
        $committeeOptions = $audienceResolver->committeeOptions();
        $companies = Category::companies()->orderBy('name')->get();
        $departments = Category::departments()->with('parent')->orderBy('name')->get();

        return view('polls.create', compact(
            'zoomMeetings',
            'users',
            'audienceScopes',
            'committeeOptions',
            'companies',
            'departments'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ParticipantAudienceResolver $audienceResolver)
    {
        $this->normalizeReferencedUsersInput($request);

        $request->validate([
            'question' => 'required|string|max:500',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
            'zoom_meeting_id' => 'nullable|exists:zoom_meetings,id',
            'audience_scope' => 'nullable|in:manual,all_users,all_contributors,board_members,committee,company,department',
            'audience_committee' => 'nullable|required_if:audience_scope,committee|string|max:255',
            'audience_category_id' => 'nullable|required_if:audience_scope,company,department|exists:categories,id',
            'referenced_users' => 'nullable|array',
            'referenced_users.*' => 'exists:users,id',
            'options' => 'required|array',
            'options.*' => 'nullable|string|max:255',
        ]);

        $options = collect($request->input('options', []))
            ->map(fn ($option) => trim((string) $option))
            ->filter()
            ->unique()
            ->values();

        if ($options->count() < 2) {
            return redirect()->back()
                ->withErrors(['options' => 'يجب إدخال خيارين مختلفين على الأقل.'])
                ->withInput();
        }

        $referencedUserIds = $audienceResolver->resolve(
            $request->input('audience_scope', ParticipantAudienceResolver::SCOPE_MANUAL),
            $request->input('referenced_users', []),
            $request->integer('audience_category_id') ?: null,
            $request->input('audience_committee')
        );

        DB::transaction(function () use ($request, $referencedUserIds, $options): void {
            $poll = Poll::create([
                'title' => Str::limit($request->question, 255, ''),
                'question' => $request->question,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_active' => $request->has('is_active'),
                'created_date' => now(),
                'created_by' => Auth::id(),
                'zoom_meeting_id' => $request->zoom_meeting_id,
                'audience_scope' => $request->input('audience_scope', ParticipantAudienceResolver::SCOPE_MANUAL),
                'audience_committee' => $request->input('audience_scope') === ParticipantAudienceResolver::SCOPE_COMMITTEE
                    ? $request->input('audience_committee')
                    : null,
                'audience_category_id' => in_array($request->input('audience_scope'), [ParticipantAudienceResolver::SCOPE_COMPANY, ParticipantAudienceResolver::SCOPE_DEPARTMENT], true)
                    ? $request->integer('audience_category_id')
                    : null,
            ]);

            $poll->referencedUsers()->sync($referencedUserIds);

            foreach ($options as $optionText) {
                $poll->pollOptions()->create([
                    'option_text' => $optionText,
                    'votes' => 0,
                ]);
            }
        });

        return redirect()->route('polls.index')
            ->with('success', 'تم إنشاء الاستطلاع بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Poll $poll)
    {
        $poll->load([
            'pollOptions' => fn ($query) => $query->orderBy('votes', 'desc'),
            'creator',
            'pollAnswers.user',
            'referencedUsers',
            'zoomMeeting',
            'meeting',
            'questions.options',
        ]);

        return view('polls.show', compact('poll'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Poll $poll, ParticipantAudienceResolver $audienceResolver)
    {
        $poll->load([
            'zoomMeeting',
            'referencedUsers',
            'pollOptions' => fn ($query) => $query->orderBy('votes', 'desc'),
            'pollAnswers',
            'creator',
        ]);
        $zoomMeetings = ZoomMeeting::orderBy('meeting_date', 'desc')->get();
        $users = User::orderBy('name')->get();
        $audienceScopes = $audienceResolver->scopeOptions();
        $committeeOptions = $audienceResolver->committeeOptions();
        $companies = Category::companies()->orderBy('name')->get();
        $departments = Category::departments()->with('parent')->orderBy('name')->get();

        return view('polls.edit', compact(
            'poll',
            'zoomMeetings',
            'users',
            'audienceScopes',
            'committeeOptions',
            'companies',
            'departments'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Poll $poll, ParticipantAudienceResolver $audienceResolver)
    {
        $this->normalizeReferencedUsersInput($request);

        $request->validate([
            'question' => 'required|string|max:500',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
            'zoom_meeting_id' => 'nullable|exists:zoom_meetings,id',
            'audience_scope' => 'nullable|in:manual,all_users,all_contributors,board_members,committee,company,department',
            'audience_committee' => 'nullable|required_if:audience_scope,committee|string|max:255',
            'audience_category_id' => 'nullable|required_if:audience_scope,company,department|exists:categories,id',
            'referenced_users' => 'nullable|array',
            'referenced_users.*' => 'exists:users,id',
        ]);

        $referencedUserIds = $audienceResolver->resolve(
            $request->input('audience_scope', ParticipantAudienceResolver::SCOPE_MANUAL),
            $request->input('referenced_users', []),
            $request->integer('audience_category_id') ?: null,
            $request->input('audience_committee')
        );

        DB::transaction(function () use ($poll, $request, $referencedUserIds): void {
            $poll->update([
                'title' => Str::limit($request->question, 255, ''),
                'question' => $request->question,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_active' => $request->has('is_active'),
                'zoom_meeting_id' => $request->zoom_meeting_id,
                'audience_scope' => $request->input('audience_scope', ParticipantAudienceResolver::SCOPE_MANUAL),
                'audience_committee' => $request->input('audience_scope') === ParticipantAudienceResolver::SCOPE_COMMITTEE
                    ? $request->input('audience_committee')
                    : null,
                'audience_category_id' => in_array($request->input('audience_scope'), [ParticipantAudienceResolver::SCOPE_COMPANY, ParticipantAudienceResolver::SCOPE_DEPARTMENT], true)
                    ? $request->integer('audience_category_id')
                    : null,
            ]);

            $poll->referencedUsers()->sync($referencedUserIds);
        });

        return redirect()->route('polls.index')
            ->with('success', 'تم تحديث الاستطلاع بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Poll $poll)
    {
        $poll->delete();

        return redirect()->route('polls.index')
            ->with('success', 'تم حذف الاستطلاع بنجاح');
    }

    /**
     * Show poll results.
     */
    public function results(Poll $poll)
    {
        $poll->load([
            'creator',
            'referencedUsers',
            'zoomMeeting',
            'meeting',
            'questions.options' => function ($query) {
                $query->orderBy('id');
            },
            'questions.answers.user',
            'pollOptions' => function ($query) {
                $query->orderBy('votes', 'desc');
            },
            'pollAnswers.user',
            'pollAnswers.pollOption',
        ]);

        return view('polls.results', compact('poll'));
    }

    private function normalizeReferencedUsersInput(Request $request): void
    {
        if (!$request->has('referenced_users')) {
            return;
        }

        $referencedUsers = collect((array) $request->input('referenced_users'))
            ->map(function ($userId) {
                if (is_numeric($userId)) {
                    return (int) $userId;
                }

                if (is_string($userId) && preg_match('/^user[_:-]?(\d+)$/i', trim($userId), $matches)) {
                    return (int) $matches[1];
                }

                return $userId;
            })
            ->filter(fn ($userId) => $userId !== null && $userId !== '')
            ->unique()
            ->values()
            ->all();

        $request->merge([
            'referenced_users' => $referencedUsers,
        ]);
    }
}
