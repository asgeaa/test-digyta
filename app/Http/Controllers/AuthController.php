<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function index()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $validated['username'])->first();

        if (!$user) {
            return back()->withErrors([
                'username' => 'Username belum terdaftar.',
            ])->withInput();
        }

        if ($validated['password'] !== $user->password) {
            return back()->withErrors([
                'password' => 'Password salah.',
            ])->withInput();
        }

        auth()->login($user);

        return redirect()->route('task.index');
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('auth.index')
            ->with([
                'pesan'       => 'Anda berhasil logout.',
                'alert-class' => 'alert-success',
            ]);
    }
}
