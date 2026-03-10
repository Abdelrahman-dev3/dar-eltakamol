<?php

namespace App\Http\Controllers;

use App\Models\Regulation;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RegulationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $regulations = Regulation::orderBy('created_at', 'desc')->paginate(15);

        return view('regulations.index', compact('regulations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('regulations.create');
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

                    // Store file in storage/app/public/regulations
                    $path = $file->storeAs('regulations', $fileName, 'public');

                    // Use provided name or file name
                    $regulationName = $validated['name'] ?? pathinfo($originalName, PATHINFO_FILENAME);

                    Regulation::create([
                        'name' => $regulationName,
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
            ->route('regulations.index')
            ->with('success', __('تم إضافة :count لائحة بنجاح', ['count' => $uploadedCount]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Regulation $regulation): View
    {
        return view('regulations.show', compact('regulation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Regulation $regulation): View
    {
        return view('regulations.edit', compact('regulation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Regulation $regulation): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'nullable|file|max:51200', // 50MB max
        ]);

        $updateData = ['name' => $validated['name']];

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

        $regulation->update($updateData);

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
}

