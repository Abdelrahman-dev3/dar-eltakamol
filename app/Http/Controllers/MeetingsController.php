<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Poll;
use App\Models\User;
use App\Models\MeetingAttachment;
use App\Models\Category;
use App\Services\ParticipantAudienceResolver;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MeetingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $meetings = Meeting::with(['users:id,name'])
            ->withCount(['users', 'attachments'])
            ->orderBy('date', 'desc')
            ->paginate(15);
        
        return view('meetings.index', compact('meetings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ParticipantAudienceResolver $audienceResolver): View
    {
        $users = User::orderBy('name')->get();
        $polls = Poll::with('meeting:id,name,date')
            ->whereNull('meeting_id')
            ->orderByDesc('created_date')
            ->get(['id', 'title', 'question', 'meeting_id', 'created_date']);
        $audienceScopes = $audienceResolver->scopeOptions();
        $committeeOptions = $audienceResolver->committeeOptions();
        $companies = Category::companies()->orderBy('name')->get();
        $departments = Category::departments()->with('parent')->orderBy('name')->get();
        
        return view('meetings.create', compact('users', 'polls', 'audienceScopes', 'committeeOptions', 'companies', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ParticipantAudienceResolver $audienceResolver): RedirectResponse
    {
        $this->normalizeUserIdsInput($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'date' => 'required|date',
            'audience_scope' => 'nullable|in:manual,all_users,all_contributors,board_members,committee,company,department',
            'audience_committee' => 'nullable|required_if:audience_scope,committee|string|max:255',
            'audience_category_id' => 'nullable|required_if:audience_scope,company,department|exists:categories,id',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'poll_ids' => 'nullable|array',
            'poll_ids.*' => 'exists:polls,id',
            'attachments.*' => 'nullable|file|max:20480', // 20MB max per file
            'attachment_descriptions' => 'nullable|array',
        ]);

        $userIds = $audienceResolver->resolve(
            $request->input('audience_scope', ParticipantAudienceResolver::SCOPE_MANUAL),
            $request->input('user_ids', []),
            $request->integer('audience_category_id') ?: null,
            $request->input('audience_committee')
        );

        $meeting = DB::transaction(function () use ($validated, $userIds): Meeting {
            $meeting = Meeting::create([
                'name' => $validated['name'],
                'url' => $validated['url'],
                'date' => $validated['date'],
                'audience_scope' => $validated['audience_scope'] ?? ParticipantAudienceResolver::SCOPE_MANUAL,
                'audience_committee' => ($validated['audience_scope'] ?? null) === ParticipantAudienceResolver::SCOPE_COMMITTEE
                    ? ($validated['audience_committee'] ?? null)
                    : null,
                'audience_category_id' => in_array($validated['audience_scope'] ?? null, [ParticipantAudienceResolver::SCOPE_COMPANY, ParticipantAudienceResolver::SCOPE_DEPARTMENT], true)
                    ? ($validated['audience_category_id'] ?? null)
                    : null,
            ]);

            $meeting->users()->sync($userIds);
            $this->syncLinkedPolls($meeting, $validated['poll_ids'] ?? []);

            return $meeting;
        });

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            $this->uploadAttachments($meeting, $request->file('attachments'), $request->input('attachment_descriptions', []));
        }

        return redirect()
            ->route('meetings.index')
            ->with('success', __('تم إنشاء الاجتماع بنجاح'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Meeting $meeting): View
    {
        $meeting->load([
            'users',
            'attachments.uploader',
            'polls' => fn ($query) => $query->latest('created_date'),
            'polls.pollOptions' => fn ($query) => $query->orderBy('votes', 'desc'),
            'polls.pollAnswers.user',
        ]);
        
        return view('meetings.show', compact('meeting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Meeting $meeting, ParticipantAudienceResolver $audienceResolver): View
    {
        $meeting->load(['users', 'attachments.uploader', 'polls']);
        $users = User::orderBy('name')->get();
        $polls = Poll::with('meeting:id,name,date')
            ->where(function ($query) use ($meeting) {
                $query->whereNull('meeting_id')
                    ->orWhere('meeting_id', $meeting->id);
            })
            ->orderByDesc('created_date')
            ->get(['id', 'title', 'question', 'meeting_id', 'created_date']);
        $audienceScopes = $audienceResolver->scopeOptions();
        $committeeOptions = $audienceResolver->committeeOptions();
        $companies = Category::companies()->orderBy('name')->get();
        $departments = Category::departments()->with('parent')->orderBy('name')->get();
        
        return view('meetings.edit', compact('meeting', 'users', 'polls', 'audienceScopes', 'committeeOptions', 'companies', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meeting $meeting, ParticipantAudienceResolver $audienceResolver): RedirectResponse
    {
        $this->normalizeUserIdsInput($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'date' => 'required|date',
            'audience_scope' => 'nullable|in:manual,all_users,all_contributors,board_members,committee,company,department',
            'audience_committee' => 'nullable|required_if:audience_scope,committee|string|max:255',
            'audience_category_id' => 'nullable|required_if:audience_scope,company,department|exists:categories,id',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'poll_ids' => 'nullable|array',
            'poll_ids.*' => 'exists:polls,id',
            'attachments.*' => 'nullable|file|max:20480', // 20MB max per file
            'attachment_descriptions' => 'nullable|array',
        ]);

        $userIds = $audienceResolver->resolve(
            $request->input('audience_scope', ParticipantAudienceResolver::SCOPE_MANUAL),
            $request->input('user_ids', []),
            $request->integer('audience_category_id') ?: null,
            $request->input('audience_committee')
        );

        DB::transaction(function () use ($meeting, $validated, $userIds): void {
            $meeting->update([
                'name' => $validated['name'],
                'url' => $validated['url'],
                'date' => $validated['date'],
                'audience_scope' => $validated['audience_scope'] ?? ParticipantAudienceResolver::SCOPE_MANUAL,
                'audience_committee' => ($validated['audience_scope'] ?? null) === ParticipantAudienceResolver::SCOPE_COMMITTEE
                    ? ($validated['audience_committee'] ?? null)
                    : null,
                'audience_category_id' => in_array($validated['audience_scope'] ?? null, [ParticipantAudienceResolver::SCOPE_COMPANY, ParticipantAudienceResolver::SCOPE_DEPARTMENT], true)
                    ? ($validated['audience_category_id'] ?? null)
                    : null,
            ]);

            $meeting->users()->sync($userIds);
            $this->syncLinkedPolls($meeting, $validated['poll_ids'] ?? []);
        });

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            $this->uploadAttachments($meeting, $request->file('attachments'), $request->input('attachment_descriptions', []));
        }

        return redirect()
            ->route('meetings.index')
            ->with('success', __('تم تحديث الاجتماع بنجاح'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meeting $meeting): RedirectResponse
    {
        // Delete all attachments first
        foreach ($meeting->attachments as $attachment) {
            if (Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }
        }

        $meeting->delete();

        return redirect()
            ->route('meetings.index')
            ->with('success', __('تم حذف الاجتماع بنجاح'));
    }

    /**
     * Upload attachments for a meeting.
     */
    private function uploadAttachments(Meeting $meeting, array $files, array $descriptions = []): void
    {
        foreach ($files as $index => $file) {
            if ($file->isValid()) {
                // Determine file type
                $mimeType = $file->getMimeType();
                $extension = strtolower($file->getClientOriginalExtension());
                
                // Categorize file type
                if (str_starts_with($mimeType, 'image/')) {
                    $fileType = 'image';
                } elseif (in_array($extension, ['pdf'])) {
                    $fileType = 'pdf';
                } elseif (in_array($extension, ['doc', 'docx'])) {
                    $fileType = 'document';
                } elseif (in_array($extension, ['xls', 'xlsx'])) {
                    $fileType = 'spreadsheet';
                } elseif (in_array($extension, ['zip', 'rar', '7z'])) {
                    $fileType = 'archive';
                } else {
                    $fileType = 'other';
                }

                // Generate unique file name
                $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('meetings/attachments', $fileName, 'public');

                MeetingAttachment::create([
                    'meeting_id' => $meeting->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => $fileType,
                    'file_size' => $file->getSize(),
                    'mime_type' => $mimeType,
                    'description' => $descriptions[$index] ?? null,
                    'uploaded_by' => Auth::id(),
                ]);
            }
        }
    }

    /**
     * Download a meeting attachment.
     */
    public function downloadAttachment(MeetingAttachment $attachment)
    {
        if (!Storage::disk('public')->exists($attachment->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }

    /**
     * Delete a meeting attachment.
     */
    public function deleteAttachment(MeetingAttachment $attachment): RedirectResponse
    {
        // Delete file from storage
        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        // Delete record
        $meetingId = $attachment->meeting_id;
        $attachment->delete();

        return redirect()->route('meetings.show', $meetingId)
            ->with('success', 'تم حذف المرفق بنجاح');
    }

    private function normalizeUserIdsInput(Request $request): void
    {
        if (!$request->has('user_ids')) {
            return;
        }

        $userIds = collect((array) $request->input('user_ids'))
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
            'user_ids' => $userIds,
        ]);
    }

    private function syncLinkedPolls(Meeting $meeting, array $pollIds): void
    {
        $pollIds = collect($pollIds)
            ->map(fn ($pollId) => (int) $pollId)
            ->filter()
            ->unique()
            ->values()
            ->all();

        Poll::where('meeting_id', $meeting->id)
            ->whereNotIn('id', $pollIds ?: [0])
            ->update([
                'meeting_id' => null,
                'poll_type' => 'general',
            ]);

        if ($pollIds === []) {
            return;
        }

        Poll::whereIn('id', $pollIds)->update([
            'meeting_id' => $meeting->id,
            'poll_type' => 'meeting',
        ]);
    }
}

