<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordReset;
use App\Mail\ResetPasswordMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }
    
    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,user_email',
        ], [
            'email.exists' => 'No account found with this email address.',
        ]);
        
        $user = User::where('user_email', $request->email)->first();
        
        // Check if user is active
        if ($user->user_status === 'deactivated') {
            return back()->with('error', 'Your account is deactivated. Please contact support.');
        }
        
        // Generate reset code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Delete any existing reset records for this email
        PasswordReset::where('email', $request->email)->delete();
        
        // Create new reset record
        PasswordReset::create([
            'email' => $request->email,
            'token' => $code,
            'expires_at' => Carbon::now()->addMinutes(30),
        ]);
        
        // Send email
        Mail::to($request->email)->send(new ResetPasswordMail($code, $user->user_name));
        
        // Store email in session
        session(['reset_email' => $request->email]);
        
        return redirect()->route('password.reset.form')->with('status', 'Reset code sent to your email!');
    }
    
    public function showResetForm()
    {
        if (!session('reset_email')) {
            return redirect()->route('password.request');
        }
        
        return view('auth.reset-password');
    }
    
    public function verifyResetCode(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);
        
        $email = session('reset_email');
        
        if (!$email) {
            return redirect()->route('password.request')->with('error', 'Please request password reset first.');
        }
        
        $reset = PasswordReset::where('email', $email)->first();
        
        if (!$reset) {
            return redirect()->route('password.request')->with('error', 'Reset request not found. Please try again.');
        }
        
        if ($reset->isExpired()) {
            $reset->delete();
            session()->forget('reset_email');
            return redirect()->route('password.request')->with('error', 'Reset code has expired. Please request a new one.');
        }
        
        if ($reset->token !== $request->code) {
            return back()->with('error', 'Invalid reset code. Please try again.');
        }
        
        // Code is valid - mark as verified
        session(['reset_verified' => true]);
        
        return redirect()->route('password.update.form')->with('status', 'Code verified! Please enter your new password.');
    }
    
    public function showUpdatePasswordForm()
    {
        if (!session('reset_email') || !session('reset_verified')) {
            return redirect()->route('password.request');
        }
        
        return view('auth.update-password');
    }
    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
        $email = session('reset_email');
        
        if (!$email) {
            return redirect()->route('password.request')->with('error', 'Session expired. Please request password reset again.');
        }
        
        $reset = PasswordReset::where('email', $email)->first();
        
        if (!$reset || $reset->isExpired()) {
            if ($reset) $reset->delete();
            session()->forget(['reset_email', 'reset_verified']);
            return redirect()->route('password.request')->with('error', 'Reset code expired. Please request a new one.');
        }
        
        // Update password
        $user = User::where('user_email', $email)->first();
        $user->user_password = bcrypt($request->password);
        $user->save();
        
        // Clean up
        $reset->delete();
        session()->forget(['reset_email', 'reset_verified']);
        
        return redirect()->route('login')->with('status', 'Password reset successfully! You can now login with your new password.');
    }
    
    public function resendResetCode()
    {
        $email = session('reset_email');
        
        if (!$email) {
            return response()->json(['error' => 'Please request password reset first.'], 400);
        }
        
        $user = User::where('user_email', $email)->first();
        
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 400);
        }
        
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        PasswordReset::updateOrCreate(
            ['email' => $email],
            [
                'token' => $code,
                'expires_at' => Carbon::now()->addMinutes(30),
            ]
        );
        
        Mail::to($email)->send(new ResetPasswordMail($code, $user->user_name));
        
        return response()->json(['success' => 'New reset code sent to your email!']);
    }
}