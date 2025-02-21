<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Cek apakah input adalah Email atau NIK
        $fieldType = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'nik';

        // Coba login menggunakan email atau NIK
        if (!Auth::attempt([$fieldType => $request->input('login'), 'password' => $request->password], $request->boolean('remember'))) {
            return back()->withErrors([
                'login' => 'NIK atau Email dan password tidak sesuai.',
            ])->onlyInput('login');
        }

        // Regenerasi session jika berhasil login
        $request->session()->regenerate();

        return redirect()->intended(route('front.index', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
