<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Show login page
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function store(Request $request)
    {
        // Validate form
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Find user by custom email column
        $user = User::where('user_email', $request->email)->first();

        // Debug log
        \Log::info('Login attempt', [
            'email' => $request->email,
            'user_found' => $user ? true : false,
        ]);

        // User not found
        if (!$user) {
            \Log::warning('User not found', [
                'email' => $request->email
            ]);

            throw ValidationException::withMessages([
                'email' => 'No user found with this email address.',
            ]);
        }

        // Check if user is deactivated
        if ($user->user_status === 'deactivated') {
            \Log::warning('Deactivated user attempted login', [
                'email' => $request->email,
                'user_id' => $user->user_id
            ]);

            throw ValidationException::withMessages([
                'email' => 'Your account has been deactivated. Please contact the administrator.',
            ]);
        }

        // Check password manually
        if (!Hash::check($request->password, $user->user_password)) {
            \Log::warning('Password mismatch', [
                'email' => $request->email,
            ]);

            throw ValidationException::withMessages([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        /**
         * IMPORTANT:
         * Since you use custom columns:
         * - user_email
         * - user_password
         *
         * We DO NOT use Auth::attempt()
         */

        // Login user manually
        Auth::login($user, $request->boolean('remember'));

        // Regenerate session
        $request->session()->regenerate();

        // Success log
        \Log::info('Login successful', [
            'user_id' => auth()->id(),
            'is_admin' => auth()->user()->is_admin,
        ]);

        // Redirect admin
        if (auth()->user()->is_admin) {
            return redirect()->intended(route('admin.dashboard'));
        }

        // Redirect normal user
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Logout user
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}