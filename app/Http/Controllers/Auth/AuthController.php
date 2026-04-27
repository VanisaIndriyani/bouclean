<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Warga;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'min:3', 'max:255', 'regex:/^[a-zA-Z0-9_.]+$/', 'unique:users,username'],
            'password' => 'required|string|min:8|confirmed',
        ]);

        $username = strtolower(trim((string) $request->username));
        $email = $username.'@bouclear.invalid';

        $user = User::create([
            'name' => trim((string) $request->name),
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'last_login_at' => null,
        ]);

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan login menggunakan username dan password yang sudah didaftarkan.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');
        $credentials['username'] = strtolower(trim((string) $credentials['username']));

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user) {
                $user->forceFill(['last_login_at' => now()])->saveQuietly();
            }

            if ($user && $user->role === 'warga') {
                $warga = Warga::query()->where('account_user_id', $user->id)->first();
                if ($warga) {
                    Wilayah::query()->firstOrCreate([
                        'kecamatan' => $warga->kecamatan,
                        'kelurahan' => $warga->kelurahan,
                        'rt' => $warga->rt,
                        'rw' => $warga->rw,
                        'dasawisma' => $warga->dasawisma,
                    ], [
                        'nama_pengguna' => mb_strtoupper($warga->dasawisma),
                    ]);
                }
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
