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
    private function normalizeNik(?string $nik): string
    {
        return preg_replace('/\D+/', '', (string) $nik) ?? '';
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|size:16',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $nik = $this->normalizeNik($request->nik);
        $warga = Warga::query()->where('nik', $nik)->first();
        if (! $warga) {
            return back()
                ->withErrors(['nik' => 'NIK tidak ditemukan di data warga.'])
                ->withInput();
        }
        if ($warga->account_user_id) {
            return back()
                ->withErrors(['nik' => 'NIK ini sudah punya akun.'])
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'warga',
            'last_login_at' => null,
        ]);

        $warga->forceFill(['account_user_id' => $user->id])->save();
        Wilayah::query()->firstOrCreate([
            'kecamatan' => $warga->kecamatan,
            'kelurahan' => $warga->kelurahan,
            'rt' => $warga->rt,
            'rw' => $warga->rw,
            'dasawisma' => $warga->dasawisma,
        ], [
            'nama_pengguna' => mb_strtoupper($warga->dasawisma),
        ]);

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan login menggunakan email dan password yang sudah didaftarkan.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
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
            'email' => 'Email atau password salah.',
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
