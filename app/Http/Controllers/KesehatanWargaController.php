<?php

namespace App\Http\Controllers;

use App\Models\KesehatanWarga;
use App\Models\Warga;
use Illuminate\Http\Request;

class KesehatanWargaController extends Controller
{
    public function index(Warga $warga)
    {
        $kesehatans = KesehatanWarga::where('warga_id', $warga->id)->orderByDesc('tanggal_laporan')->paginate(10);

        return view('warga.kesehatan.index', compact('warga', 'kesehatans'));
    }

    public function create(Warga $warga)
    {
        return view('warga.kesehatan.create', compact('warga'));
    }

    public function store(Request $request, Warga $warga)
    {
        $validated = $request->validate([
            'kek' => 'nullable|boolean',
            'anemia' => 'nullable|boolean',
            'haid_lebih_7_hari' => 'nullable|boolean',
            'belum_imunisasi' => 'nullable|boolean',
            'tbc_mangkir' => 'nullable|boolean',
            'remaja_rokok' => 'nullable|boolean',
            'ada_jentik' => 'nullable|boolean',
            'tanggal_laporan' => 'nullable|date',
        ]);

        $validated['warga_id'] = $warga->id;
        $validated['kek'] = $request->boolean('kek');
        $validated['anemia'] = $request->boolean('anemia');
        $validated['haid_lebih_7_hari'] = $request->boolean('haid_lebih_7_hari');
        $validated['belum_imunisasi'] = $request->boolean('belum_imunisasi');
        $validated['tbc_mangkir'] = $request->boolean('tbc_mangkir');
        $validated['remaja_rokok'] = $request->boolean('remaja_rokok');
        $validated['ada_jentik'] = $request->boolean('ada_jentik');

        KesehatanWarga::create($validated);

        return redirect()->route('warga.kesehatan.index', $warga)->with('success', 'Data kesehatan berhasil ditambahkan.');
    }

    public function edit(Warga $warga, KesehatanWarga $kesehatan)
    {
        abort_unless((int) $kesehatan->warga_id === (int) $warga->id, 404);

        return view('warga.kesehatan.edit', compact('warga', 'kesehatan'));
    }

    public function update(Request $request, Warga $warga, KesehatanWarga $kesehatan)
    {
        abort_unless((int) $kesehatan->warga_id === (int) $warga->id, 404);

        $validated = $request->validate([
            'kek' => 'nullable|boolean',
            'anemia' => 'nullable|boolean',
            'haid_lebih_7_hari' => 'nullable|boolean',
            'belum_imunisasi' => 'nullable|boolean',
            'tbc_mangkir' => 'nullable|boolean',
            'remaja_rokok' => 'nullable|boolean',
            'ada_jentik' => 'nullable|boolean',
            'tanggal_laporan' => 'nullable|date',
        ]);

        $validated['kek'] = $request->boolean('kek');
        $validated['anemia'] = $request->boolean('anemia');
        $validated['haid_lebih_7_hari'] = $request->boolean('haid_lebih_7_hari');
        $validated['belum_imunisasi'] = $request->boolean('belum_imunisasi');
        $validated['tbc_mangkir'] = $request->boolean('tbc_mangkir');
        $validated['remaja_rokok'] = $request->boolean('remaja_rokok');
        $validated['ada_jentik'] = $request->boolean('ada_jentik');

        $kesehatan->update($validated);

        return redirect()->route('warga.kesehatan.index', $warga)->with('success', 'Data kesehatan berhasil diperbarui.');
    }

    public function destroy(Warga $warga, KesehatanWarga $kesehatan)
    {
        abort_unless((int) $kesehatan->warga_id === (int) $warga->id, 404);

        $kesehatan->delete();

        return redirect()->route('warga.kesehatan.index', $warga)->with('success', 'Data kesehatan berhasil dihapus.');
    }
}
