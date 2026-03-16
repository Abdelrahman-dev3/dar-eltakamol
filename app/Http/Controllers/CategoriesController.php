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
        $categories = Category::companies()->with([
                'children' => fn ($query) => $query
                    ->withCount(['contributors', 'users', 'permissions'])
                    ->orderBy('name'),
            ])->withCount(['children'])->orderBy('name')->paginate(15);

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $companies = Category::companies()->orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();
        $selectedKind = $request->query('kind', $request->filled('company_id') ? 'department' : 'company');
        $selectedCompanyId = $request->query('company_id');

        if ($selectedCompanyId) {
            $selectedCompany = Category::companies()->find($selectedCompanyId);
            $selectedCompanyId = $selectedCompany?->id;
        }

        return view('categories.create', compact('companies', 'permissions', 'selectedKind', 'selectedCompanyId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kind' => 'required|in:company,department',
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'permission_ids' => 'nullable|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);

        $parentId = $this->resolveParentIdForRequest($validated['kind'], $validated['parent_id'] ?? null);
        $this->validateUniqueCategoryName($validated['name'], $parentId);

        $category = Category::create([
            'name' => $validated['name'],
            'parent_id' => $parentId,
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
            'contributors.user',
            'users.contributor',
            'children.permissions',
            'children.users',
            'children.contributors',
        ]);

        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): View
    {
        $category->load('permissions', 'parent');
        $companies = Category::companies()
            ->when($category->isCompany(), fn ($query) => $query->whereKeyNot($category->id))
            ->orderBy('name')
            ->get();
        $permissions = Permission::orderBy('name')->get();

        return view('categories.edit', compact('category', 'companies', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'permission_ids' => 'nullable|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);
        $parentId = $category->parent_id;
        if ($category->isDepartment()) {
            $parentId = $this->resolveParentIdForRequest('department', $validated['parent_id'] ?? null, $category);
        }
        $this->validateUniqueCategoryName($validated['name'], $parentId, $category->id);

        $category->update([
            'name' => $validated['name'],
            'parent_id' => $parentId,
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
        if ($category->contributors()->count() > 0) {
            return redirect()
                ->route('categories.index')
                ->with('error', 'لا يمكن حذف هذا السجل لأنه مرتبط بمساهمين.');
        }

        if ($category->users()->count() > 0) {
            return redirect()
                ->route('categories.index')
                ->with('error', 'لا يمكن حذف هذا السجل لأنه مرتبط بمستخدمين.');
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

    private function resolveParentIdForRequest(string $kind, ?int $parentId, ?Category $currentCategory = null): ?int
    {
        if ($kind === 'company') {
            return null;
        }

        if (!$parentId) {
            throw ValidationException::withMessages([
                'parent_id' => 'يرجى اختيار الشركة التي ستتبع لها الإدارة.',
            ]);
        }

        $parent = Category::findOrFail($parentId);

        if ($currentCategory && $parent->id === $currentCategory->id) {
            throw ValidationException::withMessages([
                'parent_id' => 'لا يمكن ربط الإدارة بنفسها.',
            ]);
        }

        if ($parent->isDepartment()) {
            throw ValidationException::withMessages([
                'parent_id' => 'يمكن ربط الإدارة بالشركة فقط، ولا يسمح بإنشاء مستوى ثالث داخل الإدارات.',
            ]);
        }

        return $parent->id;
    }

    private function validateUniqueCategoryName(string $name, ?int $parentId, ?int $ignoreId = null): void
    {
        $query = Category::query()
            ->where('name', $name)
            ->when(
                $parentId === null,
                fn ($builder) => $builder->whereNull('parent_id'),
                fn ($builder) => $builder->where('parent_id', $parentId)
            );

        if ($ignoreId) {
            $query->whereKeyNot($ignoreId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'name' => 'هذا الاسم مستخدم بالفعل داخل نفس المستوى.',
            ]);
        }
    }
}
