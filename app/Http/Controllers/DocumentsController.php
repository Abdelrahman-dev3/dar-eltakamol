<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Category;
use App\Models\Meeting;
use App\Models\User;
use App\Services\ParticipantAudienceResolver;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $documents = Document::with('meeting:id,name,date')
            ->withCount('recipients')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ParticipantAudienceResolver $audienceResolver): View
    {
        $meetings = Meeting::orderBy('date', 'desc')->get(['id', 'name', 'date']);
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $audienceScopes = $audienceResolver->scopeOptions();
        $committeeOptions = $audienceResolver->committeeOptions();
        $companies = Category::companies()->orderBy('name')->get();
        $departments = Category::departments()->with('parent')->orderBy('name')->get();

        return view('documents.create', compact(
            'meetings',
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
    public function store(Request $request, ParticipantAudienceResolver $audienceResolver): RedirectResponse
    {
        $this->normalizeRecipientUsersInput($request);

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|max:51200', // 50MB max per file
            'meeting_id' => 'nullable|exists:meetings,id',
            'audience_scope' => 'nullable|in:manual,all_users,all_contributors,board_members,committee,company,department',
            'audience_committee' => 'nullable|required_if:audience_scope,committee|string|max:255',
            'audience_category_id' => 'nullable|required_if:audience_scope,company,department|exists:categories,id',
            'recipient_users' => 'nullable|array',
            'recipient_users.*' => 'exists:users,id',
        ]);

        $recipientUserIds = $audienceResolver->resolve(
            $request->input('audience_scope', ParticipantAudienceResolver::SCOPE_MANUAL),
            $request->input('recipient_users', []),
            $request->integer('audience_category_id') ?: null,
            $request->input('audience_committee')
        );

        $uploadedCount = 0;

        // Handle multiple file uploads
        if ($request->hasFile('files')) {
            DB::transaction(function () use ($request, $validated, $recipientUserIds, &$uploadedCount): void {
                foreach ($request->file('files') as $file) {
                    if ($file->isValid()) {
                        $originalName = $file->getClientOriginalName();
                        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

                        // Store file in storage/app/public/documents
                        $path = $file->storeAs('documents', $fileName, 'public');

                        // Use provided name or file name
                        $documentName = $validated['name'] ?? pathinfo($originalName, PATHINFO_FILENAME);

                        $document = Document::create([
                            'name' => $documentName,
                            'meeting_id' => $validated['meeting_id'] ?? null,
                            'audience_scope' => $validated['audience_scope'] ?? ParticipantAudienceResolver::SCOPE_MANUAL,
                            'audience_committee' => ($validated['audience_scope'] ?? null) === ParticipantAudienceResolver::SCOPE_COMMITTEE
                                ? ($validated['audience_committee'] ?? null)
                                : null,
                            'audience_category_id' => in_array($validated['audience_scope'] ?? null, [ParticipantAudienceResolver::SCOPE_COMPANY, ParticipantAudienceResolver::SCOPE_DEPARTMENT], true)
                                ? ($validated['audience_category_id'] ?? null)
                                : null,
                            'file_path' => $path,
                            'original_filename' => $originalName,
                            'file_type' => $file->getClientMimeType(),
                            'file_size' => $file->getSize(),
                        ]);

                        $document->recipients()->sync($recipientUserIds);
                        $uploadedCount++;
                    }
                }
            });
        }

        return redirect()
            ->route('documents.index')
            ->with('success', __('تم إضافة :count ملف بنجاح', ['count' => $uploadedCount]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document): View
    {
        $document->load('meeting:id,name,date', 'recipients:id,name,email');

        return view('documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document, ParticipantAudienceResolver $audienceResolver): View
    {
        $document->load('meeting:id,name,date', 'recipients:id,name,email');
        $meetings = Meeting::orderBy('date', 'desc')->get(['id', 'name', 'date']);
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $audienceScopes = $audienceResolver->scopeOptions();
        $committeeOptions = $audienceResolver->committeeOptions();
        $companies = Category::companies()->orderBy('name')->get();
        $departments = Category::departments()->with('parent')->orderBy('name')->get();

        return view('documents.edit', compact(
            'document',
            'meetings',
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
    public function update(Request $request, Document $document, ParticipantAudienceResolver $audienceResolver): RedirectResponse
    {
        $this->normalizeRecipientUsersInput($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'nullable|file|max:51200', // 50MB max
            'meeting_id' => 'nullable|exists:meetings,id',
            'audience_scope' => 'nullable|in:manual,all_users,all_contributors,board_members,committee,company,department',
            'audience_committee' => 'nullable|required_if:audience_scope,committee|string|max:255',
            'audience_category_id' => 'nullable|required_if:audience_scope,company,department|exists:categories,id',
            'recipient_users' => 'nullable|array',
            'recipient_users.*' => 'exists:users,id',
        ]);

        $recipientUserIds = $audienceResolver->resolve(
            $request->input('audience_scope', ParticipantAudienceResolver::SCOPE_MANUAL),
            $request->input('recipient_users', []),
            $request->integer('audience_category_id') ?: null,
            $request->input('audience_committee')
        );

        $updateData = [
            'name' => $validated['name'],
            'meeting_id' => $validated['meeting_id'] ?? null,
            'audience_scope' => $validated['audience_scope'] ?? ParticipantAudienceResolver::SCOPE_MANUAL,
            'audience_committee' => ($validated['audience_scope'] ?? null) === ParticipantAudienceResolver::SCOPE_COMMITTEE
                ? ($validated['audience_committee'] ?? null)
                : null,
            'audience_category_id' => in_array($validated['audience_scope'] ?? null, [ParticipantAudienceResolver::SCOPE_COMPANY, ParticipantAudienceResolver::SCOPE_DEPARTMENT], true)
                ? ($validated['audience_category_id'] ?? null)
                : null,
        ];

        // Handle file upload if new file is provided
        if ($request->hasFile('file')) {
            // Delete old file
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

            // Store new file
            $path = $file->storeAs('documents', $fileName, 'public');

            $updateData['file_path'] = $path;
            $updateData['original_filename'] = $originalName;
            $updateData['file_type'] = $file->getClientMimeType();
            $updateData['file_size'] = $file->getSize();
        }

        DB::transaction(function () use ($document, $updateData, $recipientUserIds): void {
            $document->update($updateData);
            $document->recipients()->sync($recipientUserIds);
        });

        return redirect()
            ->route('documents.index')
            ->with('success', __('تم تحديث الملف بنجاح'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document): RedirectResponse
    {
        // Delete file from storage
        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()
            ->route('documents.index')
            ->with('success', __('تم حذف الملف بنجاح'));
    }

    /**
     * Download the file.
     */
    public function download(Document $document)
    {
        if (!$document->file_path || !Storage::disk('public')->exists($document->file_path)) {
            return redirect()
                ->route('documents.show', $document)
                ->with('error', __('الملف غير موجود'));
        }

        return Storage::disk('public')->download(
            $document->file_path,
            $document->original_filename
        );
    }

    private function normalizeRecipientUsersInput(Request $request): void
    {
        if (!$request->has('recipient_users')) {
            return;
        }

        $recipientUsers = collect((array) $request->input('recipient_users'))
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
            'recipient_users' => $recipientUsers,
        ]);
    }
}

