<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| Here are the authentication routes for the apartment rental system.
| Includes standard auth routes and Google OAuth integration.
|
*/

// Google OAuth Routes
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');

// Basic auth routes
Route::get('/login', function() {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::post('/login', function(\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (\Illuminate\Support\Facades\Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }

    return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
});

Route::get('/register', function() {
    return view('auth.register');
})->name('register')->middleware('guest');

Route::post('/register', function(\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
    ]);

    try {
        event(new \Illuminate\Auth\Events\Registered($user));
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Verification email failed: ' . $e->getMessage());
    }

    \Illuminate\Support\Facades\Auth::login($user);
    return redirect(route('verification.notice'));
});

Route::post('/logout', function(\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Http\Request $request) {
    $user = \App\Models\User::findOrFail($request->route('id'));

    if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
        abort(403);
    }

    if ($user->hasVerifiedEmail()) {
        return redirect()->intended(route('dashboard'));
    }

    $user->markEmailAsVerified();

    return redirect()->intended(route('dashboard'));
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
    try {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Verification email failed: ' . $e->getMessage());
        return back()->with('error', 'Unable to send verification email. Please try again later or contact support.');
    }
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
