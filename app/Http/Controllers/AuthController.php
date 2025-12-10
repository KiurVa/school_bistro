<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Näita sisselogimise lehte
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Logi kasutaja sisse
     */
   public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Mõlemad (admin ja user) lähevad dashboardile
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Vale e-post või parool.',
        ])->onlyInput('email');
    }


    /**
     * Logi kasutaja välja
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
