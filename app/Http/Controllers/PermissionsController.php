<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $permissions = Permission::with(['departments.parent'])->orderBy('name')->paginate(20);

        return view('permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $modules = $this->getAvailableModules();
        $departments = Category::departments()->with('parent')->orderBy('name')->get();

        return view('permissions.create', compact('modules', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePermissionRequest($request);
        $departmentIds = $this->validateDepartmentIds($validated['department_ids'] ?? []);

        $permission = Permission::create($this->buildPermissionPayload($validated));
        $permission->categories()->sync($departmentIds);

        return redirect()
            ->route('permissions.index')
            ->with('success', 'تم إنشاء الصلاحية بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission): View
    {
        $permission->load('departments.parent', 'departments.users');

        return view('permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission): View
    {
        $permission->load('departments.parent');
        $modules = $this->getAvailableModules();
        $departments = Category::departments()->with('parent')->orderBy('name')->get();

        return view('permissions.edit', compact('permission', 'modules', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $validated = $this->validatePermissionRequest($request, $permission);
        $departmentIds = $this->validateDepartmentIds($validated['department_ids'] ?? []);

        $permission->update($this->buildPermissionPayload($validated, $permission));
        $permission->categories()->sync($departmentIds);

        return redirect()
            ->route('permissions.index')
            ->with('success', 'تم تحديث الصلاحية بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission): RedirectResponse
    {
        $permission->delete();

        return redirect()
            ->route('permissions.index')
            ->with('success', 'تم حذف الصلاحية بنجاح.');
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
            'categories' => 'العضوية',
            'settings' => 'الإعدادات',
            'bookings' => 'الحجوزات',
            'services' => 'الخدمات',
        ];
    }

    private function validateDepartmentIds(array $departmentIds): array
    {
        if (empty($departmentIds)) {
            return [];
        }

        $validDepartmentsCount = Category::departments()
            ->whereIn('id', $departmentIds)
            ->count();

        if ($validDepartmentsCount !== count($departmentIds)) {
            throw ValidationException::withMessages([
                'department_ids' => 'يمكن ربط الصلاحية بإدارات فقط وليس بالشركة مباشرة.',
            ]);
        }

        return $departmentIds;
    }

    private function validatePermissionRequest(Request $request, ?Permission $permission = null): array
    {
        $nameRule = 'required|string|max:255';

        if (Schema::hasColumn('permissions', 'slug')) {
            return $request->validate([
                'name' => $nameRule,
                'slug' => 'required|string|max:255|unique:permissions,slug,' . ($permission?->id ?? 'NULL'),
                'description' => 'nullable|string|max:500',
                'module' => 'nullable|string|max:100',
                'department_ids' => 'nullable|array',
                'department_ids.*' => 'exists:categories,id',
            ]);
        }

        return $request->validate([
            'name' => $permission
                ? $nameRule . '|unique:permissions,name,' . $permission->id
                : $nameRule . '|unique:permissions,name',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'module' => 'nullable|string|max:100',
            'department_ids' => 'nullable|array',
            'department_ids.*' => 'exists:categories,id',
        ]);
    }

    private function buildPermissionPayload(array $validated, ?Permission $permission = null): array
    {
        if (Schema::hasColumn('permissions', 'slug')) {
            return [
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'] ?? null,
                'module' => $validated['module'] ?? null,
            ];
        }

        return [
            'name' => $validated['name'],
            'guard_name' => $permission->guard_name ?? 'web',
        ];
    }
}
