<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show the user's profile.
     */
    public function show()
    {
        return view('profile');
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate the request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'id_number' => 'nullable|string|max:15',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        // Update user data
        $user->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث ملفك الشخصي بنجاح.'
        ]);
    }

    /**
     * Change the user's password.
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        // Validate the request
        $validatedData = $request->validate([
            'current_password' => 'required|string',
            'new_password' => ['required', 'confirmed', 'min:8'],
            'new_password_confirmation' => 'required|string|min:8',
        ], [
            'current_password.required' => 'كلمة المرور الحالية مطلوبة',
            'new_password.required' => 'كلمة المرور الجديدة مطلوبة',
            'new_password.confirmed' => 'كلمة المرور الجديدة غير متطابقة',
            'new_password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'new_password_confirmation.required' => 'تأكيد كلمة المرور مطلوب',
        ]);

        // Check if current password is correct
        if (!Hash::check($validatedData['current_password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'كلمة المرور الحالية غير صحيحة'
            ], 400);
        }

        // Update password
        $user->update([
            'password' => Hash::make($validatedData['new_password'])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تغيير كلمة المرور بنجاح.'
        ]);
    }
}