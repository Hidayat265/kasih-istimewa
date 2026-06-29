<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmailVerification;
use App\Mail\VerificationCodeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Carbon\Carbon;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,user_email'],
            'dob' => ['nullable', 'date', 'before_or_equal:' . now()->subYears(18)->format('Y-m-d')],
            'phone_number' => ['nullable', 'string', 'min:10', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'dob.before_or_equal' => 'You must be at least 18 years old.',
        ]);

        // Generate a 6-digit verification code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Prepare user data for temporary storage
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'dob' => $request->dob,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ];
        
        // Save or update verification record
        EmailVerification::updateOrCreate(
            ['email' => $request->email],
            [
                'code' => $code,
                'user_data' => $userData,
                'expires_at' => Carbon::now()->addMinutes(30),
            ]
        );
        
        // Send verification email
        Mail::to($request->email)->send(new VerificationCodeMail($code, $request->name));
        
        // Store email in session for verification page
        session(['verification_email' => $request->email]);
        
        return redirect()->route('verification.show')->with('status', 'Verification code sent to your email! Please check your inbox.');
    }
    
    public function showVerificationForm()
    {
        if (!session('verification_email')) {
            return redirect()->route('register');
        }
        
        return view('auth.verify-code');
    }
    
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);
        
        $email = session('verification_email');
        
        if (!$email) {
            return redirect()->route('register')->with('error', 'Please register first.');
        }
        
        $verification = EmailVerification::where('email', $email)->first();
        
        if (!$verification) {
            return redirect()->route('register')->with('error', 'Verification record not found. Please register again.');
        }
        
        if ($verification->isExpired()) {
            $verification->delete();
            session()->forget('verification_email');
            return redirect()->route('register')->with('error', 'Verification code has expired. Please register again.');
        }
        
        if ($verification->code !== $request->code) {
            return back()->with('error', 'Invalid verification code. Please try again.');
        }
        
        // Code is valid - Create the user account
        $userData = $verification->user_data;
        
        // Generate user_id
        $lastUser = User::orderBy('user_id', 'desc')->first();
        if (!$lastUser) {
            $userId = 'USR-0001';
        } else {
            $number = intval(substr($lastUser->user_id, 4));
            $userId = 'USR-' . str_pad($number + 1, 4, '0', STR_PAD_LEFT);
        }
        
        // Create the user
        $user = User::create([
            'user_id' => $userId,
            'user_name' => strtoupper($userData['name']),
            'user_email' => $userData['email'],
            'user_dob' => $userData['dob'],
            'user_phone_number' => $userData['phone_number'],
            'user_password' => $userData['password'],
            'user_status' => 'active',
            'user_email_verified_at' => now(),
        ]);
        
        // Clean up verification record
        $verification->delete();
        session()->forget('verification_email');
        
        return redirect()->route('login')->with('status', 'Email verified successfully! You can now login.');
    }
    
    public function resendCode(Request $request)
    {
        $email = session('verification_email');
        
        if (!$email) {
            return response()->json(['error' => 'Please register first.'], 400);
        }
        
        $verification = EmailVerification::where('email', $email)->first();
        
        if (!$verification) {
            return response()->json(['error' => 'Please register again.'], 400);
        }
        
        // Generate new code
        $newCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $verification->update([
            'code' => $newCode,
            'expires_at' => Carbon::now()->addMinutes(30),
        ]);
        
        // Resend email
        Mail::to($email)->send(new VerificationCodeMail($newCode, $verification->user_data['name']));
        
        return response()->json(['success' => 'New verification code sent to your email!']);
    }
}