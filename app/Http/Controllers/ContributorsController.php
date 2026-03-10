<?php

namespace App\Http\Controllers;

use App\Models\Contributor;
use App\Models\ContributorDocument;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Modification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContributorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $contributors = Contributor::paginate(15);
        
        return view('contributors.index', compact('contributors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('contributors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'id_number' => 'required|string|max:10|unique:contributors',
            'phone_num' => 'nullable|string|max:15',
            'temp_password' => 'nullable|string|max:10',
            'iban' => 'nullable|string|max:24',
            'bank_name' => 'nullable|string|max:15',
            'position' => 'nullable|string|max:100',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
            'share_count_cr' => 'nullable|numeric|min:0',
            'is_board_member' => 'boolean',
            'documents.*' => 'nullable|file|max:10240', // 10MB max per file
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('contributors/profiles', $fileName, 'public');
            $validated['profile_picture'] = $filePath;
        }

        $contributor = Contributor::create($validated);

        // Handle file uploads
        if ($request->hasFile('documents')) {
            $this->uploadDocuments($contributor, $request->file('documents'), $request->input('document_descriptions', []));
        }

        return redirect()->route('contributors.index')
            ->with('success', 'تم إضافة المساهم بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contributor $contributor): View
    {
        $contributor->load(['user', 'sellShares', 'sharesPOs', 'shareTransLines', 'documents.uploader']);
        
        return view('contributors.show', compact('contributor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contributor $contributor): View
    {
        return view('contributors.edit', compact('contributor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contributor $contributor): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'id_number' => 'required|string|max:10|unique:contributors,id_number,' . $contributor->id,
            'phone_num' => 'nullable|string|max:15',
            'temp_password' => 'nullable|string|max:10',
            'iban' => 'nullable|string|max:24',
            'bank_name' => 'nullable|string|max:15',
            'position' => 'nullable|string|max:100',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
            'share_count_cr' => 'nullable|numeric|min:0',
            'is_board_member' => 'boolean',
            'line_notes' => 'required|string',
            'documents.*' => 'nullable|file|max:10240', // 10MB max per file
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($contributor->profile_picture && Storage::disk('public')->exists($contributor->profile_picture)) {
                Storage::disk('public')->delete($contributor->profile_picture);
            }

            $file = $request->file('profile_picture');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('contributors/profiles', $fileName, 'public');
            $validated['profile_picture'] = $filePath;
        }

        $contributor->update($validated);

        $url = url()->previous();
        Modification::logChange($url , $request->line_notes , auth()->user()->id);
        // Handle file uploads
        if ($request->hasFile('documents')) {
            $this->uploadDocuments($contributor, $request->file('documents'), $request->input('document_descriptions', []));
        }

        return redirect()->route('contributors.index')
            ->with('success', 'تم تحديث بيانات المساهم بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contributor $contributor): RedirectResponse
    {
        $contributor->delete();

        return redirect()->route('contributors.index')
            ->with('success', 'تم حذف المساهم بنجاح');
    }

    /**
     * Upload documents for a contributor.
     */
    private function uploadDocuments(Contributor $contributor, array $files, array $descriptions = []): void
    {
        foreach ($files as $index => $file) {
            if ($file->isValid()) {
                // Determine file type
                $mimeType = $file->getMimeType();
                $fileType = str_starts_with($mimeType, 'image/') ? 'image' : 'document';

                // Generate unique file name
                $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('contributors/documents', $fileName, 'public');

                ContributorDocument::create([
                    'contributor_id' => $contributor->id,
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
     * Download a contributor document.
     */
    public function downloadDocument(ContributorDocument $document)
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    /**
     * Delete a contributor document.
     */
    public function deleteDocument(ContributorDocument $document): RedirectResponse
    {
        // Delete file from storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        // Delete record
        $contributorId = $document->contributor_id;
        $document->delete();

        return redirect()->route('contributors.show', $contributorId)
            ->with('success', 'تم حذف الملف بنجاح');
    }
}