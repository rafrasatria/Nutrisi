<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SiswaAuthController extends Controller
{
    public function showRegister()
    {
        $kelas = Kelas::orderBy('nama')->get();
        return view('siswa.register', compact('kelas'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|min:3|max:50|alpha_dash|unique:users,username',
            'password' => 'required|min:6',
            'kelas_id' => 'required|exists:kelas,id',
            'kelompok' => 'required|max:100',
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'username.min' => 'Username minimal 3 karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'kelas_id.required' => 'Kelas wajib dipilih.',
            'kelas_id.exists' => 'Kelas tidak valid.',
            'kelompok.required' => 'Kelompok wajib diisi.',
        ]);

        User::create([
            'name' => $validated['username'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => 'siswa',
            'kelas_id' => $validated['kelas_id'],
            'kelompok' => $validated['kelompok'],
        ]);

        return redirect()->route('siswa.login')->with('success', 'Akun berhasil dibuat, silakan login.');
    }

    public function showLogin()
    {
        return view('siswa.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::where('username', $validated['username'])->where('role', 'siswa')->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return back()->withErrors(['username' => 'Username atau password salah.'])->withInput();
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('siswa.dashboard')->with('success', 'Login berhasil, selamat datang '.$user->username.'!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('siswa.login')->with('success', 'Berhasil logout.');
    }
}
