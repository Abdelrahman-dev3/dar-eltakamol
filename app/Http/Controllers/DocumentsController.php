<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $documents = Document::orderBy('created_at', 'desc')->paginate(15);

        return view('documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('documents.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|max:51200', // 50MB max per file
            'meeting_id' => 'nullable|exists:meetings,id',
        ]);

        $uploadedCount = 0;

        // Handle multiple file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $index => $file) {
                if ($file->isValid()) {
                    $originalName = $file->getClientOriginalName();
                    $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

                    // Store file in storage/app/public/documents
                    $path = $file->storeAs('documents', $fileName, 'public');

                    // Use provided name or file name
                    $documentName = $validated['name'] ?? pathinfo($originalName, PATHINFO_FILENAME);

                    Document::create([
                        'name' => $documentName,
                        'meeting_id' => $validated['meeting_id'] ?? null,
                        'file_path' => $path,
                        'original_filename' => $originalName,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                    ]);

                    $uploadedCount++;
                }
            }
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
        return view('documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document): View
    {
        return view('documents.edit', compact('document'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'nullable|file|max:51200', // 50MB max
        ]);

        $updateData = ['name' => $validated['name']];

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

        $document->update($updateData);

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
}

