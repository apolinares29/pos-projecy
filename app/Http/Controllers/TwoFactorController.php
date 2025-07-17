<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TwoFactorController extends Controller
{
    public function show2faForm(Request $request)
    {
        if (session('authenticated') && session('role')) {
            // Already logged in, redirect to dashboard
            return redirect('/dashboard/' . session('role'));
        }
        if (!session('2fa_code') || !session('2fa_user')) {
            return redirect('/');
        }
        $expires = session('2fa_expires');
        return view('auth.2fa', ['expires' => $expires]);
    }

    public function verify2fa(Request $request)
    {
        if (session('authenticated') && session('role')) {
            // Already logged in, redirect to dashboard
            return redirect('/dashboard/' . session('role'));
        }
        $request->validate(['code' => 'required|digits:6']);
        $code = session('2fa_code');
        $expires = session('2fa_expires');
        $userData = session('2fa_user');
        if (!$code || !$expires || !$userData) {
            return redirect('/')->withErrors(['code' => '2FA session expired. Please login again.']);
        }
        if (now()->gt($expires)) {
            Session::forget(['2fa_code', '2fa_expires', '2fa_user']);
            return redirect('/')->withErrors(['code' => '2FA code expired. Please login again.']);
        }
        if ($request->code != $code) {
            return back()->withErrors(['code' => 'Invalid 2FA code.']);
        }
        // Complete login
        if (!empty($userData['demo'])) {
            session([
                'user_id' => uniqid(),
                'username' => $userData['username'],
                'role' => $userData['role'],
                'authenticated' => true
            ]);
            $redirect = "/dashboard/{$userData['role']}";
        } else {
            session([
                'user_id' => $userData['id'],
                'username' => $userData['username'],
                'role' => $userData['role'],
                'authenticated' => true
            ]);
            $redirect = "/dashboard/{$userData['role']}";
        }
        Session::forget(['2fa_code', '2fa_expires', '2fa_user']);
        return redirect($redirect);
    }
}
