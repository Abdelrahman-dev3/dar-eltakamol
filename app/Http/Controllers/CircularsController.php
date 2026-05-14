<?php

namespace App\Http\Controllers;

use App\Models\Circular;
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

        // Handle multiple file uploads
        if ($request->hasFile('files')) {
            DB::transaction(function () use ($request, $validated, $recipientUserIds, &$uploadedCount): void {
                foreach ($request->file('files') as $file) {
                    if ($file->isValid()) {
                        $originalName = $file->getClientOriginalName();
                        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

                        // Store file in storage/app/public/circulars
                        $path = $file->storeAs('circulars', $fileName, 'public');

                        // Use provided name or file name
                        $circularName = $validated['name'] ?? pathinfo($originalName, PATHINFO_FILENAME);

                        $circular = Circular::create([
                            'name' => $circularName,
                            'meeting_id' => null,
                            'file_path' => $path,
                            'original_filename' => $originalName,
                            'file_type' => $file->getClientMimeType(),
                            'file_size' => $file->getSize(),
                        ]);

                        $circular->recipients()->sync($recipientUserIds);
                        $uploadedCount++;
                    }
                }
            });
        }

        return redirect()
            ->route('circulars.index')
            ->with('success', __('تم إضافة :count تعميم بنجاح', ['count' => $uploadedCount]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Circular $circular): View
    {
        $circular->load('recipients:id,name,email');

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
            'meeting_id' => null,
        ];

        // Handle file upload if new file is provided
        if ($request->hasFile('file')) {
            // Delete old file
            if ($circular->file_path && Storage::disk('public')->exists($circular->file_path)) {
                Storage::disk('public')->delete($circular->file_path);
            }

            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

            // Store new file
            $path = $file->storeAs('circulars', $fileName, 'public');

            $updateData['file_path'] = $path;
            $updateData['original_filename'] = $originalName;
            $updateData['file_type'] = $file->getClientMimeType();
            $updateData['file_size'] = $file->getSize();
        }

        DB::transaction(function () use ($circular, $updateData, $recipientUserIds): void {
            $circular->update($updateData);
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
        // Delete file from storage
        if ($circular->file_path && Storage::disk('public')->exists($circular->file_path)) {
            Storage::disk('public')->delete($circular->file_path);
        }

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

