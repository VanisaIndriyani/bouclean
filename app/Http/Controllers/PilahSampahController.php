<?php

namespace App\Http\Controllers;

use App\Models\PilahSampah;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PilahSampahController extends Controller
{
    public function index(Request $request)
    {
        $query = PilahSampah::with(['warga', 'user']);

        if ($request->filled('search')) {
            $search = (string) $request->search;

            $query->where(function ($q) use ($search) {
                $q->orWhere('kepala_keluarga_nama', 'like', "%{$search}%")
                    ->orWhere('kepala_keluarga_nik', 'like', "%{$search}%")
                    ->orWhereHas('warga', function ($wargaQuery) use ($search) {
                        $wargaQuery->where('nama_lengkap', 'like', "%{$search}%")
                            ->orWhere('nik', 'like', "%{$search}%");
                    })
                    ->orWhere('kecamatan', 'like', "%{$search}%")
                    ->orWhere('kelurahan', 'like', "%{$search}%")
                    ->orWhere('rt', 'like', "%{$search}%")
                    ->orWhere('rw', 'like', "%{$search}%")
                    ->orWhere('dasawisma', 'like', "%{$search}%")
                    ->orWhere('jenis_sampah', 'like', "%{$search}%");
            });
        }

        $pilahSampahs = $query->orderBy('created_at', 'desc')->paginate(10);

        $extractNik = function (?string $value): ?string {
            $value = trim((string) $value);
            if ($value === '') {
                return null;
            }
            if (preg_match('/(\d{16})/', $value, $m)) {
                return $m[1];
            }
            $digits = preg_replace('/\D+/', '', $value) ?? '';
            return $digits !== '' ? $digits : null;
        };

        $niks = $pilahSampahs->getCollection()
            ->map(function ($pilah) use ($extractNik) {
                $wargaNik = $pilah->warga ? (string) $pilah->warga->getRawOriginal('nik') : null;
                return $extractNik($wargaNik) ?? $extractNik((string) ($pilah->kepala_keluarga_nik ?? ''));
            })
            ->filter()
            ->unique()
            ->values()
            ->all();

        $wargaByNik = collect();
        if (! empty($niks)) {
            $wargaByNik = Warga::query()
                ->select(['nik', 'nama_lengkap'])
                ->whereIn('nik', $niks)
                ->get()
                ->keyBy('nik');
        }

        return view('pilah-sampah.index', compact('pilahSampahs', 'wargaByNik'));
    }

    public function create()
    {
        return view('pilah-sampah.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kepala_keluarga_nik' => 'required|string|max:255',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:1900|max:2100',
            'kecamatan' => 'nullable|string|max:255',
            'kelurahan' => 'nullable|string|max:255',
            'rt' => ['nullable', 'string', 'max:10', 'regex:/^\d+$/'],
            'rw' => ['nullable', 'string', 'max:10', 'regex:/^(\d+|[IVXLCDM]+)$/i'],
            'dasawisma' => 'nullable|string|max:255',
            'jenis_sampah' => 'nullable|string|max:255',
            'berat' => 'required|numeric|min:0.01',
            'sedekah' => 'required|boolean',
            'harga' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['user_id'] = Auth::id();

        $validated['kepala_keluarga_nik'] = trim((string) ($validated['kepala_keluarga_nik'] ?? ''));
        $validated['kepala_keluarga_nama'] = null;

        $validated['jenis_kelamin'] = null;
        $validated['warga_id'] = null;

        foreach (['kecamatan', 'kelurahan', 'rt', 'rw', 'dasawisma', 'jenis_sampah'] as $field) {
            $value = array_key_exists($field, $validated) ? trim((string) $validated[$field]) : '';
            $validated[$field] = $value === '' ? null : $value;
        }

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pilah-sampah', 'public');
        }

        PilahSampah::create($validated);

        return redirect()->route('pilah-sampah.index')->with('success', 'Data pilah sampah berhasil ditambahkan.');
    }

    public function edit(PilahSampah $pilah_sampah)
    {
        $pilahSampah = $pilah_sampah;

        return view('pilah-sampah.edit', compact('pilahSampah'));
    }

    public function update(Request $request, PilahSampah $pilah_sampah)
    {
        $pilahSampah = $pilah_sampah;

        $validated = $request->validate([
            'kepala_keluarga_nik' => 'required|string|max:255',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:1900|max:2100',
            'kecamatan' => 'nullable|string|max:255',
            'kelurahan' => 'nullable|string|max:255',
            'rt' => ['nullable', 'string', 'max:10', 'regex:/^\d+$/'],
            'rw' => ['nullable', 'string', 'max:10', 'regex:/^(\d+|[IVXLCDM]+)$/i'],
            'dasawisma' => 'nullable|string|max:255',
            'jenis_sampah' => 'nullable|string|max:255',
            'berat' => 'required|numeric|min:0.01',
            'sedekah' => 'required|boolean',
            'harga' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['kepala_keluarga_nik'] = trim((string) ($validated['kepala_keluarga_nik'] ?? ''));
        $validated['kepala_keluarga_nama'] = null;

        $validated['jenis_kelamin'] = null;
        $validated['warga_id'] = null;

        foreach (['kecamatan', 'kelurahan', 'rt', 'rw', 'dasawisma', 'jenis_sampah'] as $field) {
            $value = array_key_exists($field, $validated) ? trim((string) $validated[$field]) : '';
            $validated[$field] = $value === '' ? null : $value;
        }

        if ($request->hasFile('foto')) {
            if ($pilahSampah->foto) {
                Storage::disk('public')->delete($pilahSampah->foto);
            }
            $validated['foto'] = $request->file('foto')->store('pilah-sampah', 'public');
        }

        $pilahSampah->update($validated);

        return redirect()->route('pilah-sampah.index')->with('success', 'Data pilah sampah berhasil diperbarui.');
    }

    public function destroy(PilahSampah $pilah_sampah)
    {
        $pilahSampah = $pilah_sampah;

        if ($pilahSampah->foto) {
            Storage::disk('public')->delete($pilahSampah->foto);
        }
        $pilahSampah->delete();

        return redirect()->route('pilah-sampah.index')->with('success', 'Data pilah sampah berhasil dihapus.');
    }
}
