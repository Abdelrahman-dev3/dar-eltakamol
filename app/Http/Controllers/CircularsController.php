<?php

namespace App\Http\Controllers;

use App\Models\Circular;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CircularsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $circulars = Circular::orderBy('created_at', 'desc')->paginate(15);

        return view('circulars.index', compact('circulars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('circulars.create');
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

                    // Store file in storage/app/public/circulars
                    $path = $file->storeAs('circulars', $fileName, 'public');

                    // Use provided name or file name
                    $circularName = $validated['name'] ?? pathinfo($originalName, PATHINFO_FILENAME);

                    Circular::create([
                        'name' => $circularName,
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
            ->route('circulars.index')
            ->with('success', __('تم إضافة :count تعميم بنجاح', ['count' => $uploadedCount]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Circular $circular): View
    {
        return view('circulars.show', compact('circular'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Circular $circular): View
    {
        return view('circulars.edit', compact('circular'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Circular $circular): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'nullable|file|max:51200', // 50MB max
        ]);

        $updateData = ['name' => $validated['name']];

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

        $circular->update($updateData);

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
}

