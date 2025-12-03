<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset request using secret password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'secret_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Get the admin secret password from config
        $adminSecret = config('auth.admin_secret_password');
        
        // Check if secret password is configured
        if (empty($adminSecret)) {
            return back()->withErrors(['secret_password' => 'Password reset is not configured. Please contact administrator.']);
        }

        // Verify the secret password
        if ($request->secret_password !== $adminSecret) {
            return back()->withInput($request->only('email'))
                        ->withErrors(['secret_password' => 'Invalid secret password.']);
        }

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withInput($request->only('email'))
                        ->withErrors(['email' => 'We could not find a user with that email address.']);
        }

        // Update the user's password
        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();

        event(new PasswordReset($user));

        return redirect()->route('login')->with('status', 'Password has been reset successfully! You can now login with your new password.');
    }
}
