<?php
namespace App\Http\Controllers;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm() { return view('auth.login'); }

    public function login(Request $request)
    {
        $credentials = $request->validate(['email' => ['required', 'email'], 'kata_sandi' => ['required']]);
        $pengguna = Pengguna::where('email', $credentials['email'])->first();

        if (!$pengguna || !Hash::check($credentials['kata_sandi'], $pengguna->kata_sandi)) {
            return back()->withErrors(['email' => 'Kredensial tidak cocok.'])->onlyInput('email');
        }
        if ($pengguna->status !== 'aktif') {
            return back()->withErrors(['email' => 'Akun Anda tidak aktif. Hubungi administrator.'])->onlyInput('email');
        }

        Auth::login($pengguna, $request->boolean('remember'));
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}