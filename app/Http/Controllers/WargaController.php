<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WargaController extends Controller
{
    public function index(Request $request)
    {
        $query = Warga::with('user');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('kecamatan', 'like', "%{$search}%")
                    ->orWhere('kelurahan', 'like', "%{$search}%")
                    ->orWhere('rt', 'like', "%{$search}%")
                    ->orWhere('rw', 'like', "%{$search}%")
                    ->orWhere('dasawisma', 'like', "%{$search}%");
            });
        }

        $wargas = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('warga.index', compact('wargas'));
    }

    public function create()
    {
        $wilayahs = Wilayah::orderBy('kecamatan')->orderBy('kelurahan')->get();

        return view('warga.create', compact('wilayahs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:wargas,nik',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'kecamatan' => 'required|string|max:255',
            'kelurahan' => 'required|string|max:255',
            'rt' => 'required|string|size:3',
            'rw' => 'required|string|size:3',
            'dasawisma' => 'required|string|max:255',
            'status_dalam_keluarga' => 'nullable|string|max:255',
            'no_kk' => 'nullable|string|max:255',
            'no_register_pkk' => 'nullable|string|max:255',
            'agama' => 'nullable|string|max:255',
            'status_perkawinan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'pendidikan' => 'nullable|string|max:255',
            'pekerjaan' => 'nullable|string|max:255',
            'status_tinggal' => 'nullable|string|max:255',
            'merantau_ke' => 'nullable|string|max:255',
            'perantau_dari' => 'nullable|string|max:255',
            'akseptor_kb' => 'nullable|boolean',
            'aktif_posyandu' => 'nullable|boolean',
            'bina_keluarga_balita' => 'nullable|boolean',
            'memiliki_tabungan' => 'nullable|boolean',
            'mengikuti_kelompok_belajar' => 'nullable|boolean',
            'jenis_kelompok_belajar' => 'nullable|string|max:255',
            'ikut_kegiatan_operasional' => 'nullable|boolean',
            'jenis_operasi' => 'nullable|string|max:255',
            'mengikuti_paud' => 'nullable|boolean',
            'berkebutuhan_khusus' => 'nullable|boolean',
            'buta' => 'nullable|boolean',
            'hamil' => 'nullable|boolean',
            'menyusui' => 'nullable|boolean',
            'status' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['akseptor_kb'] = $request->boolean('akseptor_kb');
        $validated['aktif_posyandu'] = $request->boolean('aktif_posyandu');
        $validated['bina_keluarga_balita'] = $request->boolean('bina_keluarga_balita');
        $validated['memiliki_tabungan'] = $request->boolean('memiliki_tabungan');
        $validated['mengikuti_kelompok_belajar'] = $request->boolean('mengikuti_kelompok_belajar');
        $validated['ikut_kegiatan_operasional'] = $request->boolean('ikut_kegiatan_operasional');
        $validated['mengikuti_paud'] = $request->boolean('mengikuti_paud');
        $validated['berkebutuhan_khusus'] = $request->boolean('berkebutuhan_khusus');
        $validated['buta'] = $request->boolean('buta');
        $validated['hamil'] = $request->boolean('hamil');
        $validated['menyusui'] = $request->boolean('menyusui');
        Warga::create($validated);

        return redirect()->route('warga.index')->with('success', 'Data warga berhasil ditambahkan.');
    }

    public function edit(Warga $warga)
    {
        $wilayahs = Wilayah::orderBy('kecamatan')->orderBy('kelurahan')->get();

        return view('warga.edit', compact('warga', 'wilayahs'));
    }

    public function update(Request $request, Warga $warga)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:wargas,nik,'.$warga->id,
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'kecamatan' => 'required|string|max:255',
            'kelurahan' => 'required|string|max:255',
            'rt' => 'required|string|size:3',
            'rw' => 'required|string|size:3',
            'dasawisma' => 'required|string|max:255',
            'status_dalam_keluarga' => 'nullable|string|max:255',
            'no_kk' => 'nullable|string|max:255',
            'no_register_pkk' => 'nullable|string|max:255',
            'agama' => 'nullable|string|max:255',
            'status_perkawinan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'pendidikan' => 'nullable|string|max:255',
            'pekerjaan' => 'nullable|string|max:255',
            'status_tinggal' => 'nullable|string|max:255',
            'merantau_ke' => 'nullable|string|max:255',
            'perantau_dari' => 'nullable|string|max:255',
            'akseptor_kb' => 'nullable|boolean',
            'aktif_posyandu' => 'nullable|boolean',
            'bina_keluarga_balita' => 'nullable|boolean',
            'memiliki_tabungan' => 'nullable|boolean',
            'mengikuti_kelompok_belajar' => 'nullable|boolean',
            'jenis_kelompok_belajar' => 'nullable|string|max:255',
            'ikut_kegiatan_operasional' => 'nullable|boolean',
            'jenis_operasi' => 'nullable|string|max:255',
            'mengikuti_paud' => 'nullable|boolean',
            'berkebutuhan_khusus' => 'nullable|boolean',
            'buta' => 'nullable|boolean',
            'hamil' => 'nullable|boolean',
            'menyusui' => 'nullable|boolean',
            'status' => 'nullable|string|max:255',
        ]);

        $validated['akseptor_kb'] = $request->boolean('akseptor_kb');
        $validated['aktif_posyandu'] = $request->boolean('aktif_posyandu');
        $validated['bina_keluarga_balita'] = $request->boolean('bina_keluarga_balita');
        $validated['memiliki_tabungan'] = $request->boolean('memiliki_tabungan');
        $validated['mengikuti_kelompok_belajar'] = $request->boolean('mengikuti_kelompok_belajar');
        $validated['ikut_kegiatan_operasional'] = $request->boolean('ikut_kegiatan_operasional');
        $validated['mengikuti_paud'] = $request->boolean('mengikuti_paud');
        $validated['berkebutuhan_khusus'] = $request->boolean('berkebutuhan_khusus');
        $validated['buta'] = $request->boolean('buta');
        $validated['hamil'] = $request->boolean('hamil');
        $validated['menyusui'] = $request->boolean('menyusui');
        $warga->update($validated);

        return redirect()->route('warga.index')->with('success', 'Data warga berhasil diperbarui.');
    }

    public function destroy(Warga $warga)
    {
        $warga->delete();

        return redirect()->route('warga.index')->with('success', 'Data warga berhasil dihapus.');
    }
}
