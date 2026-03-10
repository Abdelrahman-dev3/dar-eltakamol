<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $permissions = Permission::with('categories')->orderBy('module')->orderBy('name')->paginate(20);

        return view('permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $modules = $this->getAvailableModules();
        $categories = Category::orderBy('name')->get();

        return view('permissions.create', compact('modules', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:permissions',
            'description' => 'nullable|string|max:500',
            'module' => 'nullable|string|max:100',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        $permission = Permission::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'module' => $validated['module'] ?? null,
        ]);

        // Attach categories if provided
        if (!empty($validated['category_ids'])) {
            $permission->categories()->attach($validated['category_ids']);
        }

        return redirect()
            ->route('permissions.index')
            ->with('success', __('تم إنشاء الصلاحية بنجاح'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission): View
    {
        $permission->load('categories.users');

        return view('permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission): View
    {
        $permission->load('categories');
        $modules = $this->getAvailableModules();
        $categories = Category::orderBy('name')->get();

        return view('permissions.edit', compact('permission', 'modules', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:permissions,slug,' . $permission->id,
            'description' => 'nullable|string|max:500',
            'module' => 'nullable|string|max:100',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        $permission->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'module' => $validated['module'] ?? null,
        ]);

        // Sync categories
        $permission->categories()->sync($validated['category_ids'] ?? []);

        return redirect()
            ->route('permissions.index')
            ->with('success', __('تم تحديث الصلاحية بنجاح'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission): RedirectResponse
    {
        $permission->delete();

        return redirect()
            ->route('permissions.index')
            ->with('success', __('تم حذف الصلاحية بنجاح'));
    }

    /**
     * Get available modules.
     */
    private function getAvailableModules(): array
    {
        return [
            'contributors' => 'المساهمين',
            'users' => 'المستخدمين',
            'meetings' => 'الاجتماعات',
            'polls' => 'الاستطلاعات',
            'shares' => 'الأسهم',
            'transactions' => 'المعاملات',
            'documents' => 'الملفات',
            'regulations' => 'اللوائح',
            'circulars' => 'التعاميم',
            'categories' => 'التصنيفات',
            'settings' => 'الإعدادات',
            'bookings' => 'الحجوزات',
            'services' => 'الخدمات',
        ];
    }
}


