<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $categories = Category::with('users')->orderBy('name')->paginate(15);

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();
        
        return view('categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        Category::create($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', __('تم إضافة التصنيف بنجاح'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): View
    {
        $category->load('users');

        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): View
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();
        
        return view('categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', __('تم تحديث التصنيف بنجاح'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        // Check if category has users
        if ($category->users()->count() > 0) {
            return redirect()
                ->route('categories.index')
                ->with('error', __('لا يمكن حذف التصنيف لأنه مرتبط بمستخدمين'));
        }

        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', __('تم حذف التصنيف بنجاح'));
    }
}

