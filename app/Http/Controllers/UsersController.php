<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $users = User::with([
                'permissions',
                'departments.parent',
                'departments.permissions',
                'contributor.departments.parent',
                'contributor.departments.permissions',
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $departments = Category::departments()->with('parent')->orderBy('name')->get();
        $permissions = $this->getAssignablePermissions();
        $moduleLabels = $this->getPermissionModuleLabels();

        return view('users.create', compact('departments', 'permissions', 'moduleLabels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => 'nullable|string|max:15',
            'id_number' => 'nullable|string|max:20',
            'department_id' => 'nullable|exists:categories,id',
            'permission_ids' => 'nullable|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);

        $departmentId = $this->validateDepartmentSelection($validated['department_id'] ?? null);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'id_number' => $validated['id_number'] ?? null,
        ]);

        if ($departmentId) {
            $user->categories()->sync([$departmentId]);
        }

        $user->permissions()->sync($validated['permission_ids'] ?? []);

        return redirect()
            ->route('users.index')
            ->with('success', 'تم إنشاء المستخدم وربطه بالإدارة وحفظ صلاحياته المباشرة بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        $user->load([
            'permissions',
            'departments.parent',
            'departments.permissions',
            'contributor.departments.parent',
            'contributor.departments.permissions',
        ]);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        $user->load([
            'permissions',
            'departments.parent',
            'departments.permissions',
            'contributor.departments.parent',
            'contributor.departments.permissions',
        ]);

        $departments = Category::departments()->with('parent')->orderBy('name')->get();
        $permissions = $this->getAssignablePermissions();
        $moduleLabels = $this->getPermissionModuleLabels();

        return view('users.edit', compact('user', 'departments', 'permissions', 'moduleLabels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'phone' => 'nullable|string|max:15',
            'id_number' => 'nullable|string|max:20',
            'department_id' => 'nullable|exists:categories,id',
            'permission_ids' => 'nullable|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);

        $departmentId = $this->validateDepartmentSelection($validated['department_id'] ?? null);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'id_number' => $validated['id_number'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);
        $user->loadMissing('contributor.departments');

        if ($user->contributor && $user->contributor->departments->isNotEmpty()) {
            $user->categories()->sync($user->contributor->departments->pluck('id')->all());
        } else {
            $user->categories()->sync($departmentId ? [$departmentId] : []);
        }

        $user->permissions()->sync($validated['permission_ids'] ?? []);

        return redirect()
            ->route('users.index')
            ->with('success', 'تم تحديث المستخدم وربطه الإداري وحفظ صلاحياته المباشرة بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('users.index')
                ->with('error', 'لا يمكنك حذف حسابك الخاص.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'تم حذف المستخدم بنجاح.');
    }

    private function validateDepartmentSelection(?int $departmentId): ?int
    {
        if (!$departmentId) {
            return null;
        }

        $department = Category::findOrFail($departmentId);

        if (!$department->isDepartment()) {
            throw ValidationException::withMessages([
                'department_id' => 'يمكن ربط المستخدم بإدارة فقط وليس بالشركة مباشرة.',
            ]);
        }

        return $departmentId;
    }

    private function getPermissionModuleLabels(): array
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
            'general' => 'عام',
        ];
    }

    private function getAssignablePermissions()
    {
        $query = Permission::query();

        if (Schema::hasColumn('permissions', 'module')) {
            $query->orderByRaw("COALESCE(module, 'zzzz'), name");
        } else {
            $query->orderBy('name');
        }

        return $query->get()
            ->sortBy(
                fn (Permission $permission) => $permission->module_display . '|' . $permission->display_name,
                SORT_NATURAL | SORT_FLAG_CASE
            )
            ->values();
    }
}
