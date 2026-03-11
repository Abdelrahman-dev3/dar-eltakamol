<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $users = User::with(['departments.parent'])->orderBy('created_at', 'desc')->paginate(15);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $departments = Category::departments()->with('parent')->orderBy('name')->get();

        return view('users.create', compact('departments'));
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

        return redirect()
            ->route('users.index')
            ->with('success', 'تم إنشاء المستخدم وربطه بالإدارة بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        $user->load('departments.parent', 'contributor');

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        $user->load('departments.parent');
        $departments = Category::departments()->with('parent')->orderBy('name')->get();

        return view('users.edit', compact('user', 'departments'));
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
        $user->categories()->sync($departmentId ? [$departmentId] : []);

        return redirect()
            ->route('users.index')
            ->with('success', 'تم تحديث المستخدم وربطه الإداري بنجاح.');
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
}
