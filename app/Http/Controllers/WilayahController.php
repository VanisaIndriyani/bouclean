<?php

namespace App\Http\Controllers;

use App\Models\Perpindahan;
use App\Models\User;
use App\Models\Warga;
use App\Models\Wilayah;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WilayahController extends Controller
{
    public function index(Request $request)
    {
        $view = $request->get('view', 'wilayah');
        $query = Wilayah::query();

        $dasawismaList = Wilayah::select('dasawisma')
            ->whereNotNull('dasawisma')
            ->where('dasawisma', '!=', '')
            ->distinct()
            ->orderBy('dasawisma')
            ->pluck('dasawisma');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kecamatan', 'like', "%{$search}%")
                    ->orWhere('kelurahan', 'like', "%{$search}%")
                    ->orWhere('rt', 'like', "%{$search}%")
                    ->orWhere('rw', 'like', "%{$search}%")
                    ->orWhere('dasawisma', 'like', "%{$search}%")
                    ->orWhere('nama_pengguna', 'like', "%{$search}%");
            });
        }

        $selectedDasawisma = $request->get('dasawisma', 'all');
        if ($view === 'dasawisma' && $selectedDasawisma !== 'all') {
            $query->where('dasawisma', $selectedDasawisma);
        }

        $wilayahs = $query->orderBy('kecamatan')->orderBy('kelurahan')->paginate(10);

        $wargasDasawisma = collect();
        $perpindahansDasawisma = collect();
        $users = collect();
        $penggunaCountMap = [];
        $penggunaUserMap = [];

        if ($view === 'dasawisma') {
            $wargasDasawismaQuery = Warga::query()->orderBy('nama_lengkap');
            if ($selectedDasawisma !== 'all') {
                $wargasDasawismaQuery->where('dasawisma', $selectedDasawisma);
            }
            $wargasDasawisma = $wargasDasawismaQuery->limit(10)->get();

            $perpindahansDasawismaQuery = Perpindahan::with(['warga', 'user'])->orderBy('created_at', 'desc');
            if ($selectedDasawisma !== 'all') {
                $perpindahansDasawismaQuery->whereHas('warga', function ($q) use ($selectedDasawisma) {
                    $q->where('dasawisma', $selectedDasawisma);
                });
            }
            $perpindahansDasawisma = $perpindahansDasawismaQuery->limit(10)->get();
        }

        if ($view !== 'dasawisma') {
            $hasUsername = Schema::hasColumn('users', 'username');
            $hasDasawisma = Schema::hasColumn('users', 'dasawisma');

            $select = ['id', 'name', 'role', 'last_login_at'];
            if ($hasUsername) {
                $select[] = 'username';
            } else {
                $select[] = 'email';
            }
            if ($hasDasawisma) {
                $select[] = 'dasawisma';
            }

            $users = User::query()
                ->orderByRaw("case when role = 'admin' then 0 else 1 end")
                ->orderBy('name')
                ->get($select);

            if (! $hasUsername) {
                $users->each(function (User $user) {
                    $user->setAttribute('username', (string) ($user->email ?? ''));
                });
            }

            $dasawismaKeys = $wilayahs->getCollection()
                ->pluck('dasawisma')
                ->filter()
                ->map(fn ($d) => mb_strtolower(trim((string) $d)))
                ->unique()
                ->values()
                ->all();

            if ($hasDasawisma && count($dasawismaKeys) > 0) {
                $penggunaUsers = User::query()
                    ->where('role', 'user')
                    ->whereNotNull('dasawisma')
                    ->where('dasawisma', '!=', '')
                    ->get(['id', 'name', 'role', 'last_login_at', $hasUsername ? 'username' : 'email', 'dasawisma']);

                if (! $hasUsername) {
                    $penggunaUsers->each(function (User $user) {
                        $user->setAttribute('username', (string) ($user->email ?? ''));
                    });
                }

                foreach ($penggunaUsers as $u) {
                    $dasawismaList = collect(explode(',', (string) ($u->dasawisma ?? '')))
                        ->map(fn ($d) => mb_strtolower(trim((string) $d)))
                        ->filter(fn ($d) => $d !== '')
                        ->unique()
                        ->values()
                        ->all();

                    foreach ($dasawismaList as $key) {
                        if (! in_array($key, $dasawismaKeys, true)) {
                            continue;
                        }
                        $penggunaCountMap[$key] = ($penggunaCountMap[$key] ?? 0) + 1;
                        $penggunaUserMap[$key] ??= [];
                        $penggunaUserMap[$key][] = $u;
                    }
                }
            }
        }

        return view('wilayah.index', compact(
            'wilayahs',
            'dasawismaList',
            'view',
            'selectedDasawisma',
            'wargasDasawisma',
            'perpindahansDasawisma',
            'users',
            'penggunaCountMap',
            'penggunaUserMap'
        ));
    }

    public function create()
    {
        return view('wilayah.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kecamatan' => 'required|string|max:255',
            'kelurahan' => 'required|string|max:255',
            'rt' => ['required', 'string', 'max:10', 'regex:/^\d+$/'],
            'rw' => ['required', 'string', 'max:10', 'regex:/^(\d+|[IVXLCDM]+)$/i'],
            'dasawisma' => 'required|string|max:255',
            'nama_pengguna' => 'nullable|string|max:255',
        ]);

        $validated['nama_pengguna'] = trim((string) ($validated['nama_pengguna'] ?? ''));
        if ($validated['nama_pengguna'] === '') {
            $validated['nama_pengguna'] = mb_strtoupper((string) $validated['dasawisma']);
        }

        Wilayah::create($validated);

        return redirect()->route('wilayah.index')->with('success', 'Data wilayah berhasil ditambahkan.');
    }

    public function edit(Wilayah $wilayah)
    {
        return view('wilayah.edit', compact('wilayah'));
    }

    public function update(Request $request, Wilayah $wilayah)
    {
        $validated = $request->validate([
            'kecamatan' => 'required|string|max:255',
            'kelurahan' => 'required|string|max:255',
            'rt' => ['required', 'string', 'max:10', 'regex:/^\d+$/'],
            'rw' => ['required', 'string', 'max:10', 'regex:/^(\d+|[IVXLCDM]+)$/i'],
            'dasawisma' => 'required|string|max:255',
            'nama_pengguna' => 'nullable|string|max:255',
        ]);

        $validated['nama_pengguna'] = trim((string) ($validated['nama_pengguna'] ?? ''));
        if ($validated['nama_pengguna'] === '') {
            $validated['nama_pengguna'] = mb_strtoupper((string) $validated['dasawisma']);
        }

        $wilayah->update($validated);

        return redirect()->route('wilayah.index')->with('success', 'Data wilayah berhasil diperbarui.');
    }

    public function destroy(Wilayah $wilayah)
    {
        $wilayah->delete();

        return redirect()->route('wilayah.index')->with('success', 'Data wilayah berhasil dihapus.');
    }
}
