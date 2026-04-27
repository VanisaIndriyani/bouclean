<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function create(Request $request)
    {
        $dasawismaOptions = Wilayah::query()
            ->select('dasawisma')
            ->whereNotNull('dasawisma')
            ->where('dasawisma', '!=', '')
            ->distinct()
            ->orderBy('dasawisma')
            ->pluck('dasawisma');

        $search = trim((string) $request->get('search', ''));
        $hasUsername = Schema::hasColumn('users', 'username');
        $hasDasawisma = Schema::hasColumn('users', 'dasawisma');

        $users = User::query()
            ->where('role', 'user')
            ->when($search !== '', function ($q) use ($search, $hasDasawisma, $hasUsername) {
                $q->where(function ($qq) use ($search, $hasDasawisma, $hasUsername) {
                    $qq->where('name', 'like', '%'.$search.'%');
                    if ($hasDasawisma) {
                        $qq->orWhere('dasawisma', 'like', '%'.$search.'%');
                    }
                    if ($hasUsername) {
                        $qq->orWhere('username', 'like', '%'.$search.'%');
                    }
                });
            })
            ->orderBy('name')
            ->get();

        if ($search !== '') {
            if ($hasUsername) {
                $users = $users->filter(function (User $u) use ($search) {
                    return str_contains(strtolower((string) ($u->username ?? '')), strtolower($search))
                        || str_contains(strtolower((string) $u->name), strtolower($search))
                        || str_contains(strtolower((string) ($u->dasawisma ?? '')), strtolower($search));
                })->values();
            } else {
                $users = $users->filter(function (User $u) use ($search) {
                    return str_contains(strtolower((string) $u->name), strtolower($search))
                        || str_contains(strtolower((string) ($u->dasawisma ?? '')), strtolower($search));
                })->values();
            }
        }

        if (! $hasUsername) {
            $users->each(function (User $u) {
                $u->setAttribute('username', (string) ($u->email ?? ''));
            });
        }

        if (! $hasDasawisma) {
            $users->each(function (User $u) {
                $u->setAttribute('dasawisma', null);
            });
        }

        return view('users.create', [
            'users' => $users,
            'dasawismaOptions' => $dasawismaOptions,
            'search' => $search,
        ]);
    }

    public function store(Request $request)
    {
        if (! Schema::hasColumn('users', 'username')) {
            return back()
                ->with('error', "Kolom 'username' belum ada di database. Jalankan: php artisan migrate")
                ->withInput();
        }
        if (! Schema::hasColumn('users', 'dasawisma')) {
            return back()
                ->with('error', "Kolom 'dasawisma' belum ada di database. Jalankan: php artisan migrate")
                ->withInput();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'dasawisma' => 'required|array|min:1',
            'dasawisma.*' => 'required|string|max:255',
            'username' => ['required', 'string', 'min:3', 'max:255', 'regex:/^[a-zA-Z0-9_.]+$/', 'unique:users,username'],
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_user_modal', 'create');
        }

        $validated = $validator->validated();

        $username = strtolower(trim((string) $validated['username']));
        $dasawisma = collect($validated['dasawisma'])
            ->map(fn ($d) => trim((string) $d))
            ->filter(fn ($d) => $d !== '')
            ->unique()
            ->implode(', ');

        User::create([
            'name' => trim((string) $validated['name']),
            'dasawisma' => $dasawisma !== '' ? $dasawisma : null,
            'username' => $username,
            'email' => $username.'@bouclear.invalid',
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'last_login_at' => null,
        ]);

        return redirect()->route('users.create')->with('success', 'Akun user berhasil dibuat.');
    }

    public function update(Request $request, User $user)
    {
        if (! Schema::hasColumn('users', 'username')) {
            return back()
                ->with('error', "Kolom 'username' belum ada di database. Jalankan: php artisan migrate")
                ->withInput();
        }
        if (! Schema::hasColumn('users', 'dasawisma')) {
            return back()
                ->with('error', "Kolom 'dasawisma' belum ada di database. Jalankan: php artisan migrate")
                ->withInput();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'min:3', 'max:255', 'regex:/^[a-zA-Z0-9_.]+$/', 'unique:users,username,'.$user->id],
            'dasawisma' => 'nullable|array',
            'dasawisma.*' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_user_modal', (string) $user->id);
        }

        $validated = $validator->validated();

        $dasawisma = collect($validated['dasawisma'] ?? [])
            ->map(fn ($d) => trim((string) $d))
            ->filter(fn ($d) => $d !== '')
            ->unique()
            ->implode(', ');
        if ($user->role === 'admin') {
            $dasawisma = (string) ($user->dasawisma ?? '');
        }

        $user->update([
            'name' => trim((string) $validated['name']),
            'dasawisma' => $dasawisma !== '' ? $dasawisma : null,
            'username' => strtolower(trim((string) $validated['username'])),
            'email' => strtolower(trim((string) $validated['username'])).'@bouclear.invalid',
        ]);

        return back()->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak bisa menghapus akun yang sedang digunakan.');
        }

        if ($user->role === 'admin') {
            return back()->with('error', 'Akun admin tidak bisa dihapus.');
        }

        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
