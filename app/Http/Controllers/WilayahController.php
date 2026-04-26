<?php

namespace App\Http\Controllers;

use App\Models\Perpindahan;
use App\Models\User;
use App\Models\Warga;
use App\Models\Wilayah;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $penggunaWargaMap = [];

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
            $users = User::query()->orderBy('name')->get(['id', 'name', 'email', 'role']);

            $lastActivityMap = DB::table('sessions')
                ->selectRaw('user_id, MAX(last_activity) AS last_activity')
                ->whereNotNull('user_id')
                ->groupBy('user_id')
                ->pluck('last_activity', 'user_id');

            $users->transform(function (User $user) use ($lastActivityMap) {
                $ts = $lastActivityMap[$user->id] ?? null;
                $user->setAttribute('last_activity_at', $ts ? Carbon::createFromTimestamp((int) $ts) : null);

                return $user;
            });

            $kecamatans = $wilayahs->getCollection()->pluck('kecamatan')->filter()->unique()->values();
            $kelurahans = $wilayahs->getCollection()->pluck('kelurahan')->filter()->unique()->values();
            $rts = $wilayahs->getCollection()->pluck('rt')->filter()->unique()->values();
            $rws = $wilayahs->getCollection()->pluck('rw')->filter()->unique()->values();
            $dasawismas = $wilayahs->getCollection()->pluck('dasawisma')->filter()->unique()->values();

            $penggunaCountMap = Warga::query()
                ->selectRaw('kecamatan, kelurahan, rt, rw, dasawisma, COUNT(*) as cnt')
                ->whereNotNull('account_user_id')
                ->whereHas('accountUser', fn ($q) => $q->whereNotNull('last_login_at'))
                ->when($kecamatans->count(), fn ($q) => $q->whereIn('kecamatan', $kecamatans))
                ->when($kelurahans->count(), fn ($q) => $q->whereIn('kelurahan', $kelurahans))
                ->when($rts->count(), fn ($q) => $q->whereIn('rt', $rts))
                ->when($rws->count(), fn ($q) => $q->whereIn('rw', $rws))
                ->when($dasawismas->count(), fn ($q) => $q->whereIn('dasawisma', $dasawismas))
                ->groupBy('kecamatan', 'kelurahan', 'rt', 'rw', 'dasawisma')
                ->get()
                ->mapWithKeys(function ($row) {
                    $key = implode('|', [
                        $row->kecamatan,
                        $row->kelurahan,
                        $row->rt,
                        $row->rw,
                        $row->dasawisma,
                    ]);

                    return [$key => (int) $row->cnt];
                })
                ->all();

            $penggunaWargaMap = Warga::query()
                ->select(['id', 'nama_lengkap', 'nik', 'kecamatan', 'kelurahan', 'rt', 'rw', 'dasawisma', 'account_user_id'])
                ->with(['accountUser:id,email,last_login_at'])
                ->whereNotNull('account_user_id')
                ->whereHas('accountUser', fn ($q) => $q->whereNotNull('last_login_at'))
                ->when($kecamatans->count(), fn ($q) => $q->whereIn('kecamatan', $kecamatans))
                ->when($kelurahans->count(), fn ($q) => $q->whereIn('kelurahan', $kelurahans))
                ->when($rts->count(), fn ($q) => $q->whereIn('rt', $rts))
                ->when($rws->count(), fn ($q) => $q->whereIn('rw', $rws))
                ->when($dasawismas->count(), fn ($q) => $q->whereIn('dasawisma', $dasawismas))
                ->orderBy('nama_lengkap')
                ->get()
                ->groupBy(fn (Warga $w) => implode('|', [$w->kecamatan, $w->kelurahan, $w->rt, $w->rw, $w->dasawisma]))
                ->all();
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
            'penggunaWargaMap'
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
            'rt' => 'required|string|size:3',
            'rw' => 'required|string|size:3',
            'dasawisma' => 'required|string|max:255',
            'nama_pengguna' => 'required|string|max:255',
        ]);

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
            'rt' => 'required|string|size:3',
            'rw' => 'required|string|size:3',
            'dasawisma' => 'required|string|max:255',
            'nama_pengguna' => 'required|string|max:255',
        ]);

        $wilayah->update($validated);

        return redirect()->route('wilayah.index')->with('success', 'Data wilayah berhasil diperbarui.');
    }

    public function destroy(Wilayah $wilayah)
    {
        $wilayah->delete();

        return redirect()->route('wilayah.index')->with('success', 'Data wilayah berhasil dihapus.');
    }
}
