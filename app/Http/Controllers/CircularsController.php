<?php

namespace App\Http\Controllers;

use App\Models\Circular;
use App\Models\CircularAttachment;
use App\Models\Category;
use App\Models\User;
use App\Services\ParticipantAudienceResolver;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CircularsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $circulars = Circular::withCount('recipients')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('circulars.index', compact('circulars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ParticipantAudienceResolver $audienceResolver): View
    {
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $audienceScopes = $audienceResolver->scopeOptions();
        $committeeOptions = $audienceResolver->committeeOptions();
        $companies = Category::companies()->orderBy('name')->get();
        $departments = Category::departments()->with('parent')->orderBy('name')->get();

        return view('circulars.create', compact(
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
            'description' => 'nullable|string|max:2000',
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|max:51200', // 50MB max per file
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

        if ($request->hasFile('files')) {
            DB::transaction(function () use ($request, $validated, $recipientUserIds, &$uploadedCount): void {
                $files = collect($request->file('files'))->filter(fn ($file) => $file && $file->isValid())->values();

                if ($files->isEmpty()) {
                    return;
                }

                $firstFile = $files->first();
                $firstOriginalName = $firstFile->getClientOriginalName();
                $firstPath = $firstFile->storeAs('circulars', Str::uuid() . '.' . $firstFile->getClientOriginalExtension(), 'public');
                $circularName = $validated['name'] ?? pathinfo($firstOriginalName, PATHINFO_FILENAME);

                $circular = Circular::create([
                    'name' => $circularName,
                    'description' => $validated['description'] ?? null,
                    'meeting_id' => null,
                    'audience_scope' => $validated['audience_scope'] ?? ParticipantAudienceResolver::SCOPE_MANUAL,
                    'audience_committee' => ($validated['audience_scope'] ?? null) === ParticipantAudienceResolver::SCOPE_COMMITTEE
                        ? ($validated['audience_committee'] ?? null)
                        : null,
                    'audience_category_id' => in_array($validated['audience_scope'] ?? null, [ParticipantAudienceResolver::SCOPE_COMPANY, ParticipantAudienceResolver::SCOPE_DEPARTMENT], true)
                        ? ($validated['audience_category_id'] ?? null)
                        : null,
                    'file_path' => $firstPath,
                    'original_filename' => $firstOriginalName,
                    'file_type' => $firstFile->getClientMimeType(),
                    'file_size' => $firstFile->getSize(),
                ]);

                $circular->recipients()->sync($recipientUserIds);

                $files->each(function ($file, int $index) use ($circular, $firstPath, $firstOriginalName, $firstFile): void {
                    $path = $index === 0
                        ? $firstPath
                        : $file->storeAs('circulars', Str::uuid() . '.' . $file->getClientOriginalExtension(), 'public');

                    CircularAttachment::create([
                        'circular_id' => $circular->id,
                        'file_path' => $path,
                        'original_filename' => $index === 0 ? $firstOriginalName : $file->getClientOriginalName(),
                        'file_type' => $index === 0 ? $firstFile->getClientMimeType() : $file->getClientMimeType(),
                        'file_size' => $index === 0 ? $firstFile->getSize() : $file->getSize(),
                        'sort_order' => $index,
                    ]);
                });

                $uploadedCount = $files->count();
            });
        }

        return redirect()
            ->route('circulars.index')
            ->with('success', __('تم إضافة التعميم بنجاح مع :count مرفق', ['count' => $uploadedCount]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Circular $circular): View
    {
        $circular->load('recipients:id,name,email', 'attachments');

        return view('circulars.show', compact('circular'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Circular $circular, ParticipantAudienceResolver $audienceResolver): View
    {
        $circular->load('recipients:id,name,email');
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $audienceScopes = $audienceResolver->scopeOptions();
        $committeeOptions = $audienceResolver->committeeOptions();
        $companies = Category::companies()->orderBy('name')->get();
        $departments = Category::departments()->with('parent')->orderBy('name')->get();

        return view('circulars.edit', compact(
            'circular',
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
    public function update(Request $request, Circular $circular, ParticipantAudienceResolver $audienceResolver): RedirectResponse
    {
        $this->normalizeRecipientUsersInput($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'file' => 'nullable|file|max:51200', // 50MB max
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
            'description' => $validated['description'] ?? null,
            'meeting_id' => null,
            'audience_scope' => $validated['audience_scope'] ?? ParticipantAudienceResolver::SCOPE_MANUAL,
            'audience_committee' => ($validated['audience_scope'] ?? null) === ParticipantAudienceResolver::SCOPE_COMMITTEE
                ? ($validated['audience_committee'] ?? null)
                : null,
            'audience_category_id' => in_array($validated['audience_scope'] ?? null, [ParticipantAudienceResolver::SCOPE_COMPANY, ParticipantAudienceResolver::SCOPE_DEPARTMENT], true)
                ? ($validated['audience_category_id'] ?? null)
                : null,
        ];
        $replacementAttachmentData = null;

        // Handle file upload if new file is provided
        if ($request->hasFile('file')) {
            $oldPaths = $circular->attachments()
                ->pluck('file_path')
                ->push($circular->file_path)
                ->filter()
                ->unique();

            $oldPaths->each(function (string $path): void {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            });

            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

            // Store new file
            $path = $file->storeAs('circulars', $fileName, 'public');

            $updateData['file_path'] = $path;
            $updateData['original_filename'] = $originalName;
            $updateData['file_type'] = $file->getClientMimeType();
            $updateData['file_size'] = $file->getSize();
            $replacementAttachmentData = [
                'file_path' => $path,
                'original_filename' => $originalName,
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'sort_order' => 0,
            ];
        }

        DB::transaction(function () use ($circular, $updateData, $recipientUserIds, $replacementAttachmentData): void {
            $circular->update($updateData);
            if ($replacementAttachmentData) {
                $circular->attachments()->delete();
                $circular->attachments()->create($replacementAttachmentData);
            }
            $circular->recipients()->sync($recipientUserIds);
        });

        return redirect()
            ->route('circulars.index')
            ->with('success', __('تم تحديث التعميم بنجاح'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Circular $circular): RedirectResponse
    {
        $paths = $circular->attachments()
            ->pluck('file_path')
            ->push($circular->file_path)
            ->filter()
            ->unique();

        $paths->each(function (string $path): void {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        });

        $circular->delete();

        return redirect()
            ->route('circulars.index')
            ->with('success', __('تم حذف التعميم بنجاح'));
    }

    /**
     * Download the file.
     */
    public function download(Circular $circular)
    {
        if (!$circular->file_path || !Storage::disk('public')->exists($circular->file_path)) {
            return redirect()
                ->route('circulars.show', $circular)
                ->with('error', __('الملف غير موجود'));
        }

        return Storage::disk('public')->download(
            $circular->file_path,
            $circular->original_filename
        );
    }

    public function viewFile(Circular $circular)
    {
        if (!$circular->file_path || !Storage::disk('public')->exists($circular->file_path)) {
            return redirect()
                ->route('circulars.show', $circular)
                ->with('error', __('الملف غير موجود'));
        }

        return $this->inlineStorageResponse($circular->file_path, $circular->original_filename, $circular->file_type);
    }

    public function viewAttachment(CircularAttachment $attachment)
    {
        return $this->inlineStorageResponse($attachment->file_path, $attachment->original_filename, $attachment->file_type);
    }

    public function downloadAttachment(CircularAttachment $attachment)
    {
        if (!$attachment->file_path || !Storage::disk('public')->exists($attachment->file_path)) {
            return redirect()
                ->route('circulars.show', $attachment->circular_id)
                ->with('error', __('الملف غير موجود'));
        }

        return Storage::disk('public')->download(
            $attachment->file_path,
            $attachment->original_filename
        );
    }

    private function inlineStorageResponse(string $filePath, string $filename, ?string $storedMimeType = null)
    {
        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            return redirect()
                ->back()
                ->with('error', __('الملف غير موجود'));
        }

        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $mimeType = $extension === 'pdf'
            ? 'application/pdf'
            : ($storedMimeType ?: Storage::disk('public')->mimeType($filePath) ?: 'application/octet-stream');

        $safeFilename = Str::ascii($filename);
        $safeFilename = $safeFilename !== '' ? $safeFilename : 'attachment.' . ($extension ?: 'bin');

        return response()->stream(function () use ($filePath): void {
            echo Storage::disk('public')->get($filePath);
        }, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . addslashes($safeFilename) . '"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
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
