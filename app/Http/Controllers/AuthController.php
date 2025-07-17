<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use App\Notifications\TwoFactorCodeNotification;
use App\Helpers\ActivityLogger;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('authenticated') && session('role')) {
            // Redirect to the appropriate dashboard based on role
            return redirect('/dashboard/' . session('role'));
        }
        return view('home');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'role' => 'required|string|in:administrator,supervisor,cashier'
        ]);

        $username = $request->username;
        $lockoutKey = 'lockout_' . $username;
        $attemptsKey = 'login_attempts_' . $username;
        $maxAttempts = 5;
        $lockoutMinutes = 5;

        // Check lockout
        if (session($lockoutKey) && now()->lt(session($lockoutKey))) {
            $lockoutUntil = session($lockoutKey);
            $now = now();
            $totalSeconds = $now->diffInSeconds($lockoutUntil);
            $minutes = floor($totalSeconds / 60);
            $seconds = $totalSeconds % 60;
            $formatted = $minutes . ' minute' . ($minutes != 1 ? 's' : '') . ' and ' . $seconds . ' second' . ($seconds != 1 ? 's' : '');
            return response()->json([
                'success' => false,
                'message' => "Account locked due to too many failed attempts. Try again in $formatted."
            ], 423);
        }

        // Demo credentials for testing
        $demoUsers = [
            'administrator' => ['username' => 'admin', 'password' => 'admin123'],
            'supervisor' => ['username' => 'supervisor', 'password' => 'super123'],
            'cashier' => ['username' => 'cashier', 'password' => 'cash123']
        ];

        $selectedRole = $request->role;
        $demoUser = $demoUsers[$selectedRole] ?? null;

        // First try demo users
        if ($demoUser && 
            $username === $demoUser['username'] && 
            $request->password === $demoUser['password']) {
            session()->forget([$attemptsKey, $lockoutKey]);
            // 2FA for demo users (simulate email)
            $code = random_int(100000, 999999);
            session([
                '2fa_code' => $code,
                '2fa_expires' => now()->addMinutes(10),
                '2fa_user' => [
                    'username' => $username,
                    'role' => $selectedRole,
                    'demo' => true
                ]
            ]);
            // In real use, send email. For demo, just show code in log.
            \Log::info('2FA code for demo user: ' . $code);
            return response()->json([
                'success' => true,
                'message' => '2FA code sent to your email. Please enter the code to continue.',
                'redirect' => route('2fa.form')
            ]);
        }

        // Try database authentication
        $user = User::where('username', $username)->first();
        
        if ($user) {
            // Enforce single session per user
            if ($user->logged_in) {
                return response()->json([
                    'success' => false,
                    'message' => 'This account is already logged in on another device or browser. Please log out first.'
                ], 423);
            }
        }
        if ($user && Hash::check($request->password, $user->password)) {
            // Check if email is verified
            if (!$user->hasVerifiedEmail()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please verify your email address before logging in. Check your inbox for the verification link.'
                ], 401);
            }

            // Check if role matches
            if ($user->role !== $selectedRole) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid role selected for this user.'
                ], 401);
            }

            // Store user session
            session()->forget([$attemptsKey, $lockoutKey]);
            $code = random_int(100000, 999999);
            $user->notify(new TwoFactorCodeNotification($code));
            // Mark user as logged in
            $user->logged_in = true;
            $user->save();
            session([
                '2fa_code' => $code,
                '2fa_expires' => now()->addMinutes(10),
                '2fa_user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'role' => $user->role,
                    'demo' => false
                ]
            ]);
            ActivityLogger::log('login', 'User', $user->id ?? null, 'User logged in: ' . $username);
            return response()->json([
                'success' => true,
                'message' => '2FA code sent to your email. Please enter the code to continue.',
                'redirect' => route('2fa.form')
            ]);
        }

        // Failed login: increment attempts
        $attempts = session($attemptsKey, 0) + 1;
        session([$attemptsKey => $attempts]);
        if ($attempts >= $maxAttempts) {
            $lockoutUntil = now()->addMinutes($lockoutMinutes);
            session([$lockoutKey => $lockoutUntil]);
            session()->forget($attemptsKey);
            return response()->json([
                'success' => false,
                'message' => "Account locked due to too many failed attempts. Try again in $lockoutMinutes minutes."
            ], 423);
        }

        ActivityLogger::log('login_failed', 'User', null, 'Failed login attempt for username: ' . $username, 'WARNING');
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials. Please try again.'
        ], 401);
    }

    public function register(Request $request)
    {
        // Log the request data for debugging
        \Log::info('Registration request data:', $request->all());
        
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:administrator,supervisor,cashier',
            'terms' => 'required|accepted',
        ]);

        if ($validator->fails()) {
            \Log::error('Validation failed:', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            \Log::info('User created successfully:', ['user_id' => $user->id]);

            // Send email verification
            event(new Registered($user));

            return response()->json([
                'success' => true,
                'message' => 'Account created successfully! Please check your email to verify your account before logging in.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Registration failed:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification link.'
            ], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'Email already verified.'
            ], 400);
        }

        $user->markEmailAsVerified();

        // Return a view instead of JSON for better user experience
        return view('auth.email-verified', [
            'user' => $user
        ]);
    }

    public function logout()
    {
        $user = Auth::user();
        $username = $user ? $user->username : (session('username') ?? 'unknown');
        // Set logged_in to false if possible
        if (!$user && session('user_id')) {
            $user = \App\Models\User::find(session('user_id'));
        }
        if ($user) {
            $user->logged_in = false;
            $user->save();
        }
        ActivityLogger::log('logout', 'User', $user->id ?? null, 'User logged out: ' . $username);
        session()->flush();
        return redirect('/');
    }

    public function dashboard($role)
    {
        if (!session('authenticated')) {
            return redirect('/');
        }

        if (session('role') !== $role) {
            return redirect('/');
        }

        switch ($role) {
            case 'cashier':
                return redirect()->route('pos.index');
            case 'supervisor':
                return redirect()->route('supervisor.index');
            case 'administrator':
                return redirect()->route('administrator.index');
            default:
                return redirect('/');
        }
    }
} 