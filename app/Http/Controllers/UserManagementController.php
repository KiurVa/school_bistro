<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Lihtne kontroll: ainult admin saab seda kontrollerit kasutada.
     */
    protected function ensureAdmin(): void
    {
        if (!auth()->user() || !auth()->user()->is_admin) {
            abort(403, 'Sul pole õigust seda lehte vaadata.');
        }
    }

    /**
     * Kasutajate nimekiri + lisamisvorm.
     */
    public function index()
    {
        $this->ensureAdmin();

        $users = User::orderByDesc('is_admin')
            ->orderBy('name')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Uue kasutaja salvestamine.
     */
    public function store(Request $request)
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed', // password_confirmation
            'is_admin' => 'nullable|boolean',
            'is_active'=> 'nullable|boolean',
        ]);

        $user = new User();
        $user->name      = $validated['name'];
        $user->email     = $validated['email'];
        $user->password  = Hash::make($validated['password']);
        $user->is_admin  = $request->boolean('is_admin');
        $user->is_active = $request->boolean('is_active', true);
        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Kasutaja lisatud.');
    }

    /**
     * Näita kasutaja muutmise vormi.
     */
    public function edit(User $user)
    {
        $this->ensureAdmin();

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Uuenda kasutaja andmeid.
     */
    public function update(Request $request, User $user)
    {
        $this->ensureAdmin();

        // ei luba iseennast mitteaktiivseks teha
        if ($user->id === auth()->id() && !$request->boolean('is_active')) {
            return back()->withErrors([
                'is_active' => 'Ei saa muuta iseennast mitteaktiivseks.',
            ]);
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'is_admin' => 'nullable|boolean',
            'is_active'=> 'nullable|boolean',
        ]);

        $user->name      = $validated['name'];
        $user->email     = $validated['email'];
        $user->is_admin  = $request->boolean('is_admin');
        $user->is_active = $request->boolean('is_active', false);

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Kasutaja uuendatud.');
    }

    /**
     * Kasutaja kustutamine.
     */
    public function destroy(User $user)
    {
        $this->ensureAdmin();

        // ei luba iseennast kustutada
        if ($user->id === auth()->id()) {
            return back()->withErrors([
                'general' => 'Ei saa kustutada iseennast.',
            ]);
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Kasutaja kustutatud.');
    }
}
