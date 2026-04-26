<?php

namespace App\Http\Controllers;

use App\Models\PilahSampah;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PilahSampahController extends Controller
{
    private function normalizeNik(?string $nik): string
    {
        return preg_replace('/\D+/', '', (string) $nik) ?? '';
    }

    public function index(Request $request)
    {
        $query = PilahSampah::with(['warga', 'user']);

        if ($request->filled('search')) {
            $search = (string) $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('warga', function ($wargaQuery) use ($search) {
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

        return view('pilah-sampah.index', compact('pilahSampahs'));
    }

    public function create()
    {
        return view('pilah-sampah.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kepala_keluarga_nik' => 'required|string',
            'kecamatan' => 'nullable|string|max:255',
            'kelurahan' => 'nullable|string|max:255',
            'rt' => 'nullable|string|max:3',
            'rw' => 'nullable|string|max:3',
            'dasawisma' => 'nullable|string|max:255',
            'jenis_sampah' => 'nullable|string|max:255',
            'berat' => 'required|numeric|min:0.01',
            'sedekah' => 'required|boolean',
            'harga' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $nik = $this->normalizeNik($validated['kepala_keluarga_nik']);
        if (strlen($nik) !== 16) {
            return back()
                ->withErrors(['kepala_keluarga_nik' => 'NIK harus 16 digit.'])
                ->withInput();
        }
        $warga = Warga::query()->where('nik', $nik)->first();
        if (! $warga) {
            return back()
                ->withErrors(['kepala_keluarga_nik' => 'NIK tidak ditemukan.'])
                ->withInput();
        }

        $validated['warga_id'] = $warga->id;
        $validated['jenis_kelamin'] = $warga->jenis_kelamin;
        $validated['user_id'] = Auth::id();

        foreach (['kecamatan', 'kelurahan', 'rt', 'rw', 'dasawisma'] as $field) {
            $value = array_key_exists($field, $validated) ? trim((string) $validated[$field]) : '';
            if ($value === '') {
                $validated[$field] = (string) ($warga->{$field} ?? '');
            }
        }

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pilah-sampah', 'public');
        }

        unset($validated['kepala_keluarga_nik']);

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
            'kepala_keluarga_nik' => 'required|string',
            'kecamatan' => 'nullable|string|max:255',
            'kelurahan' => 'nullable|string|max:255',
            'rt' => 'nullable|string|max:3',
            'rw' => 'nullable|string|max:3',
            'dasawisma' => 'nullable|string|max:255',
            'jenis_sampah' => 'nullable|string|max:255',
            'berat' => 'required|numeric|min:0.01',
            'sedekah' => 'required|boolean',
            'harga' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $nik = $this->normalizeNik($validated['kepala_keluarga_nik']);
        if (strlen($nik) !== 16) {
            return back()
                ->withErrors(['kepala_keluarga_nik' => 'NIK harus 16 digit.'])
                ->withInput();
        }
        $warga = Warga::query()->where('nik', $nik)->first();
        if (! $warga) {
            return back()
                ->withErrors(['kepala_keluarga_nik' => 'NIK tidak ditemukan.'])
                ->withInput();
        }

        $validated['warga_id'] = $warga->id;
        $validated['jenis_kelamin'] = $warga->jenis_kelamin;

        foreach (['kecamatan', 'kelurahan', 'rt', 'rw', 'dasawisma'] as $field) {
            $value = array_key_exists($field, $validated) ? trim((string) $validated[$field]) : '';
            if ($value === '') {
                $validated[$field] = (string) ($warga->{$field} ?? '');
            }
        }

        if ($request->hasFile('foto')) {
            if ($pilahSampah->foto) {
                Storage::disk('public')->delete($pilahSampah->foto);
            }
            $validated['foto'] = $request->file('foto')->store('pilah-sampah', 'public');
        }

        unset($validated['kepala_keluarga_nik']);

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
