<?php

namespace App\Http\Controllers;

use App\Models\GuruAccount;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuruAuthController extends Controller
{
    public function showLogin()
    {
        $kelas = Kelas::orderBy('nama')->get();
        return view('guru.login', compact('kelas'));
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'password' => 'required',
        ], [
            'kelas_id.required' => 'Kelas wajib dipilih.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $account = GuruAccount::where('kelas_id', $validated['kelas_id'])->first();

        if (! $account || ! Hash::check($validated['password'], $account->password)) {
            return back()->withErrors(['kelas_id' => 'Kelas atau password salah.'])->withInput();
        }

        $request->session()->put('guru_kelas_id', $account->kelas_id);
        $request->session()->regenerate();

        return redirect()->route('guru.dashboard')->with('success', 'Login berhasil.');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('guru_kelas_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('guru.login')->with('success', 'Berhasil logout.');
    }
}
