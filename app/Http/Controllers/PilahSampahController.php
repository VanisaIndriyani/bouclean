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

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('warga', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $pilahSampahs = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('pilah-sampah.index', compact('pilahSampahs'));
    }

    public function create()
    {
        $wargas = Warga::orderBy('nama_lengkap')->get();
        return view('pilah-sampah.create', compact('wargas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warga_id' => 'required|exists:wargas,id',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'berat' => 'required|numeric|min:0.01',
            'sedekah' => 'boolean',
            'harga' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['sedekah'] = $request->has('sedekah');

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pilah-sampah', 'public');
        }

        PilahSampah::create($validated);

        return redirect()->route('pilah-sampah.index')->with('success', 'Data pilah sampah berhasil ditambahkan.');
    }

    public function edit(PilahSampah $pilahSampah)
    {
        $wargas = Warga::orderBy('nama_lengkap')->get();
        return view('pilah-sampah.edit', compact('pilahSampah', 'wargas'));
    }

    public function update(Request $request, PilahSampah $pilahSampah)
    {
        $validated = $request->validate([
            'warga_id' => 'required|exists:wargas,id',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'berat' => 'required|numeric|min:0.01',
            'sedekah' => 'boolean',
            'harga' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $validated['sedekah'] = $request->has('sedekah');

        if ($request->hasFile('foto')) {
            if ($pilahSampah->foto) {
                Storage::disk('public')->delete($pilahSampah->foto);
            }
            $validated['foto'] = $request->file('foto')->store('pilah-sampah', 'public');
        }

        $pilahSampah->update($validated);

        return redirect()->route('pilah-sampah.index')->with('success', 'Data pilah sampah berhasil diperbarui.');
    }

    public function destroy(PilahSampah $pilahSampah)
    {
        if ($pilahSampah->foto) {
            Storage::disk('public')->delete($pilahSampah->foto);
        }
        $pilahSampah->delete();
        return redirect()->route('pilah-sampah.index')->with('success', 'Data pilah sampah berhasil dihapus.');
    }
}
