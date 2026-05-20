<?php

namespace App\Http\Controllers;

use App\Models\Regulation;
use App\Models\Category;
use App\Models\User;
use App\Services\ParticipantAudienceResolver;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RegulationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $regulations = Regulation::withCount('recipients')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('regulations.index', compact('regulations'));
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

        return view('regulations.create', compact(
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

                        // Store file in storage/app/public/regulations
                        $path = $file->storeAs('regulations', $fileName, 'public');

                        // Use provided name or file name
                        $regulationName = $validated['name'] ?? pathinfo($originalName, PATHINFO_FILENAME);

                        $regulation = Regulation::create([
                            'name' => $regulationName,
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

                        $regulation->recipients()->sync($recipientUserIds);
                        $uploadedCount++;
                    }
                }
            });
        }

        return redirect()
            ->route('regulations.index')
            ->with('success', __('تم إضافة :count لائحة بنجاح', ['count' => $uploadedCount]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Regulation $regulation): View
    {
        $regulation->load('recipients:id,name,email');

        return view('regulations.show', compact('regulation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Regulation $regulation, ParticipantAudienceResolver $audienceResolver): View
    {
        $regulation->load('recipients:id,name,email');
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $audienceScopes = $audienceResolver->scopeOptions();
        $committeeOptions = $audienceResolver->committeeOptions();
        $companies = Category::companies()->orderBy('name')->get();
        $departments = Category::departments()->with('parent')->orderBy('name')->get();

        return view('regulations.edit', compact(
            'regulation',
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
    public function update(Request $request, Regulation $regulation, ParticipantAudienceResolver $audienceResolver): RedirectResponse
    {
        $this->normalizeRecipientUsersInput($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
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
            if ($regulation->file_path && Storage::disk('public')->exists($regulation->file_path)) {
                Storage::disk('public')->delete($regulation->file_path);
            }

            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

            // Store new file
            $path = $file->storeAs('regulations', $fileName, 'public');

            $updateData['file_path'] = $path;
            $updateData['original_filename'] = $originalName;
            $updateData['file_type'] = $file->getClientMimeType();
            $updateData['file_size'] = $file->getSize();
        }

        DB::transaction(function () use ($regulation, $updateData, $recipientUserIds): void {
            $regulation->update($updateData);
            $regulation->recipients()->sync($recipientUserIds);
        });

        return redirect()
            ->route('regulations.index')
            ->with('success', __('تم تحديث اللائحة بنجاح'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Regulation $regulation): RedirectResponse
    {
        // Delete file from storage
        if ($regulation->file_path && Storage::disk('public')->exists($regulation->file_path)) {
            Storage::disk('public')->delete($regulation->file_path);
        }

        $regulation->delete();

        return redirect()
            ->route('regulations.index')
            ->with('success', __('تم حذف اللائحة بنجاح'));
    }

    /**
     * Download the file.
     */
    public function download(Regulation $regulation)
    {
        if (!$regulation->file_path || !Storage::disk('public')->exists($regulation->file_path)) {
            return redirect()
                ->route('regulations.show', $regulation)
                ->with('error', __('الملف غير موجود'));
        }

        return Storage::disk('public')->download(
            $regulation->file_path,
            $regulation->original_filename
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

