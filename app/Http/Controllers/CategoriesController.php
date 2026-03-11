<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $categories = Category::with(['parent'])
            ->withCount(['users', 'permissions', 'children'])
            ->orderByRaw('CASE WHEN parent_id IS NULL THEN 0 ELSE 1 END')
            ->orderBy('parent_id')
            ->orderBy('name')
            ->paginate(15);

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $company = Category::companies()->first();
        $permissions = Permission::orderBy('name')->get();

        return view('categories.create', compact('company', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
            'permission_ids' => 'nullable|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);

        $company = Category::companies()->first();

        if (empty($validated['parent_id']) && $company) {
            throw ValidationException::withMessages([
                'parent_id' => 'يوجد شركة بالفعل. يمكنك فقط إنشاء إدارات داخلها.',
            ]);
        }

        if (!empty($validated['parent_id'])) {
            $parent = Category::findOrFail($validated['parent_id']);

            if ($parent->isDepartment()) {
                throw ValidationException::withMessages([
                    'parent_id' => 'يمكن ربط الإدارة بالشركة فقط، ولا يسمح بإنشاء مستوى ثالث داخل الإدارات.',
                ]);
            }
        }

        $category = Category::create([
            'name' => $validated['name'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        if ($category->isDepartment()) {
            $category->permissions()->sync($validated['permission_ids'] ?? []);
        }

        return redirect()
            ->route('categories.index')
            ->with('success', $category->isCompany() ? 'تم إنشاء الشركة بنجاح.' : 'تم إنشاء الإدارة وربط صلاحياتها بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): View
    {
        $category->load([
            'parent',
            'permissions',
            'users.contributor',
            'children.permissions',
            'children.users',
        ]);

        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): View
    {
        $category->load('permissions', 'parent');
        $company = Category::companies()->first();
        $permissions = Permission::orderBy('name')->get();

        return view('categories.edit', compact('category', 'company', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'permission_ids' => 'nullable|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);

        $category->update([
            'name' => $validated['name'],
        ]);

        $category->permissions()->sync($category->isDepartment() ? ($validated['permission_ids'] ?? []) : []);

        return redirect()
            ->route('categories.index')
            ->with('success', $category->isCompany() ? 'تم تحديث بيانات الشركة بنجاح.' : 'تم تحديث بيانات الإدارة وصلاحياتها بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->users()->count() > 0) {
            return redirect()
                ->route('categories.index')
                ->with('error', 'لا يمكن حذف هذا السجل لأنه مرتبط بأعضاء.');
        }

        if ($category->children()->count() > 0) {
            return redirect()
                ->route('categories.index')
                ->with('error', 'لا يمكن حذف الشركة قبل حذف الإدارات التابعة لها.');
        }

        if ($category->permissions()->count() > 0) {
            return redirect()
                ->route('categories.index')
                ->with('error', 'لا يمكن حذف الإدارة قبل إزالة الصلاحيات المرتبطة بها.');
        }

        $isCompany = $category->isCompany();
        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', $isCompany ? 'تم حذف الشركة بنجاح.' : 'تم حذف الإدارة بنجاح.');
    }
}
