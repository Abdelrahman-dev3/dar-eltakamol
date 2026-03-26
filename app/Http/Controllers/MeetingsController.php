<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\User;
use App\Models\MeetingAttachment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
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
    public function create(): View
    {
        $users = User::orderBy('name')->get();
        
        return view('meetings.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'date' => 'required|date',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'attachments.*' => 'nullable|file|max:20480', // 20MB max per file
            'attachment_descriptions' => 'nullable|array',
        ]);

        $meeting = Meeting::create([
            'name' => $validated['name'],
            'url' => $validated['url'],
            'date' => $validated['date'],
        ]);

        // Attach selected users to the meeting
        if (!empty($validated['user_ids'])) {
            $meeting->users()->attach($validated['user_ids']);
        }

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
        $meeting->load(['users', 'attachments.uploader']);
        
        return view('meetings.show', compact('meeting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Meeting $meeting): View
    {
        $meeting->load(['users', 'attachments.uploader']);
        $users = User::orderBy('name')->get();
        
        return view('meetings.edit', compact('meeting', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meeting $meeting): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'date' => 'required|date',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'attachments.*' => 'nullable|file|max:20480', // 20MB max per file
            'attachment_descriptions' => 'nullable|array',
        ]);

        $meeting->update([
            'name' => $validated['name'],
            'url' => $validated['url'],
            'date' => $validated['date'],
        ]);

        // Sync selected users (this will remove old and add new ones)
        $meeting->users()->sync($validated['user_ids'] ?? []);

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
}

