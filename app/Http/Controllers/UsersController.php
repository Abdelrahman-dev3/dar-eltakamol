<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $users = User::with('categories')->orderBy('created_at', 'desc')->paginate(15);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('users.create', compact('categories'));
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
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'id_number' => $validated['id_number'] ?? null,
        ]);

        // Attach categories if provided
        if (!empty($validated['category_ids'])) {
            $user->categories()->attach($validated['category_ids']);
        }

        return redirect()
            ->route('users.index')
            ->with('success', __('تم إنشاء المستخدم بنجاح'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        $user->load('categories', 'contributor');

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        $user->load('categories');
        $categories = Category::orderBy('name')->get();

        return view('users.edit', compact('user', 'categories'));
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
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'id_number' => $validated['id_number'] ?? null,
        ];

        // Update password only if provided
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        // Sync categories
        $user->categories()->sync($validated['category_ids'] ?? []);

        return redirect()
            ->route('users.index')
            ->with('success', __('تم تحديث المستخدم بنجاح'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Prevent deleting current logged-in user
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('users.index')
                ->with('error', __('لا يمكنك حذف حسابك الخاص'));
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', __('تم حذف المستخدم بنجاح'));
    }
}


