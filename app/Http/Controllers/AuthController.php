<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Näita loginivormi.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Logimine: kontrollib parooli JA is_active staatust.
     */
    public function login(Request $request)
    {
        // valideerime sisendi
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // otsime kasutaja e-posti järgi
        $user = User::where('email', $credentials['email'])->first();

        // kui kasutajat pole või parool ei klapi
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withErrors(['email' => 'Vale e-post või parool.'])
                ->onlyInput('email');
        }

        // KASUTAJA MITTEAKTIIVNE – EI LASE SISSE
        if (!$user->is_active) {
            return back()
                ->withErrors(['email' => 'Sinu konto on mitteaktiivne. Palun võta ühendust administraatoriga.'])
                ->onlyInput('email');
        }

        // logime kasutaja sisse
        Auth::login($user, $request->boolean('remember'));

        // turvalisuse mõttes regenereerime sessiooni
        $request->session()->regenerate();

        // suuname menus.index lehel
        return redirect()->intended(route('menus.index'));
    }

    /**
     * Logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Pärast väljalogimist -> otse /login lehele
        return redirect('/login');
    }
}
