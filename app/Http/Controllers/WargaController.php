<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WargaController extends Controller
{
    public function lookup(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        if ($q === '') {
            return response()->json([]);
        }

        $digits = preg_replace('/\D+/', '', $q) ?? '';
        $mode = (string) $request->query('mode', '');

        $query = Warga::query();
        if ($mode === 'kk') {
            $query->select(['id', 'nama_lengkap', 'nik', 'no_kk'])
                ;
        } else {
            $query->select(['id', 'nama_lengkap', 'nik']);
        }

        $rows = $query
            ->where(function ($w) use ($q, $digits, $mode) {
                $w->where('nama_lengkap', 'like', "%{$q}%");

                if ($digits !== '') {
                    $w->orWhere('nik', 'like', "%{$digits}%");
                    if ($mode === 'kk') {
                        $w->orWhere('no_kk', 'like', "%{$digits}%");
                    }
                } else {
                    $w->orWhere('nik', 'like', "%{$q}%");
                    if ($mode === 'kk') {
                        $w->orWhere('no_kk', 'like', "%{$q}%");
                    }
                }
            })
            ->orderBy('nama_lengkap')
            ->limit(10)
            ->get()
            ->map(function (Warga $w) use ($mode) {
                if ($mode === 'kk') {
                    $noKk = trim((string) ($w->no_kk ?? ''));
                    $label = $w->nama_lengkap.' ('.($noKk !== '' ? $noKk : $w->nik).')';
                    return [
                        'id' => $w->id,
                        'value' => $label,
                        'label' => $label,
                    ];
                }

                return [
                    'id' => $w->id,
                    'value' => $w->nama_lengkap.' ('.$w->nik.')',
                ];
            })
            ->values();

        return response()->json($rows);
    }

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

        if ($request->has('dasawisma') && $request->dasawisma !== 'all') {
            $query->where('dasawisma', $request->dasawisma);
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
        $request->merge([
            'jenis_kelamin' => $this->normalizeJenisKelamin($request->input('jenis_kelamin')),
            'ajukan_perpindahan' => $this->normalizeAjukanPerpindahan($request->input('ajukan_perpindahan')),
        ]);

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:wargas,nik',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'kecamatan' => 'required|string|max:255',
            'kelurahan' => 'required|string|max:255',
            'rt' => ['required', 'string', 'max:10', 'regex:/^\d+$/'],
            'rw' => ['required', 'string', 'max:10', 'regex:/^(\d+|[IVXLCDM]+)$/i'],
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
            'akseptor_kb' => 'nullable|string|max:20',
            'aktif_posyandu' => 'nullable|string|max:20',
            'bina_keluarga_balita' => 'nullable|string|max:20',
            'memiliki_tabungan' => 'nullable|string|max:20',
            'mengikuti_kelompok_belajar' => 'nullable|string|max:20',
            'jenis_kelompok_belajar' => 'nullable|string|max:255',
            'ikut_kegiatan_operasional' => 'nullable|string|max:20',
            'jenis_operasi' => 'nullable|string|max:255',
            'mengikuti_paud' => 'nullable|string|max:20',
            'berkebutuhan_khusus' => 'nullable|string|max:20',
            'buta' => 'nullable|string|max:20',
            'hamil' => 'nullable|string|max:20',
            'menyusui' => 'nullable|string|max:20',
            'status' => 'nullable|string|max:50',
            'ajukan_perpindahan' => 'nullable|string|max:50',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['akseptor_kb'] = $this->parseBooleanText($request->input('akseptor_kb'));
        $validated['aktif_posyandu'] = $this->parseBooleanText($request->input('aktif_posyandu'));
        $validated['bina_keluarga_balita'] = $this->parseBooleanText($request->input('bina_keluarga_balita'));
        $validated['memiliki_tabungan'] = $this->parseBooleanText($request->input('memiliki_tabungan'));
        $validated['mengikuti_kelompok_belajar'] = $this->parseBooleanText($request->input('mengikuti_kelompok_belajar'));
        $validated['ikut_kegiatan_operasional'] = $this->parseBooleanText($request->input('ikut_kegiatan_operasional'));
        $validated['mengikuti_paud'] = $this->parseBooleanText($request->input('mengikuti_paud'));
        $validated['berkebutuhan_khusus'] = $this->parseBooleanText($request->input('berkebutuhan_khusus'));
        $validated['buta'] = $this->parseBooleanText($request->input('buta'));
        $validated['hamil'] = $this->parseBooleanText($request->input('hamil'));
        $validated['menyusui'] = $this->parseBooleanText($request->input('menyusui'));
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
        $request->merge([
            'jenis_kelamin' => $this->normalizeJenisKelamin($request->input('jenis_kelamin')),
            'ajukan_perpindahan' => $this->normalizeAjukanPerpindahan($request->input('ajukan_perpindahan')),
        ]);

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:wargas,nik,'.$warga->id,
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'kecamatan' => 'required|string|max:255',
            'kelurahan' => 'required|string|max:255',
            'rt' => ['required', 'string', 'max:10', 'regex:/^\d+$/'],
            'rw' => ['required', 'string', 'max:10', 'regex:/^(\d+|[IVXLCDM]+)$/i'],
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
            'akseptor_kb' => 'nullable|string|max:20',
            'aktif_posyandu' => 'nullable|string|max:20',
            'bina_keluarga_balita' => 'nullable|string|max:20',
            'memiliki_tabungan' => 'nullable|string|max:20',
            'mengikuti_kelompok_belajar' => 'nullable|string|max:20',
            'jenis_kelompok_belajar' => 'nullable|string|max:255',
            'ikut_kegiatan_operasional' => 'nullable|string|max:20',
            'jenis_operasi' => 'nullable|string|max:255',
            'mengikuti_paud' => 'nullable|string|max:20',
            'berkebutuhan_khusus' => 'nullable|string|max:20',
            'buta' => 'nullable|string|max:20',
            'hamil' => 'nullable|string|max:20',
            'menyusui' => 'nullable|string|max:20',
            'status' => 'nullable|string|max:50',
            'ajukan_perpindahan' => 'nullable|string|max:50',
        ]);

        $validated['akseptor_kb'] = $this->parseBooleanText($request->input('akseptor_kb'));
        $validated['aktif_posyandu'] = $this->parseBooleanText($request->input('aktif_posyandu'));
        $validated['bina_keluarga_balita'] = $this->parseBooleanText($request->input('bina_keluarga_balita'));
        $validated['memiliki_tabungan'] = $this->parseBooleanText($request->input('memiliki_tabungan'));
        $validated['mengikuti_kelompok_belajar'] = $this->parseBooleanText($request->input('mengikuti_kelompok_belajar'));
        $validated['ikut_kegiatan_operasional'] = $this->parseBooleanText($request->input('ikut_kegiatan_operasional'));
        $validated['mengikuti_paud'] = $this->parseBooleanText($request->input('mengikuti_paud'));
        $validated['berkebutuhan_khusus'] = $this->parseBooleanText($request->input('berkebutuhan_khusus'));
        $validated['buta'] = $this->parseBooleanText($request->input('buta'));
        $validated['hamil'] = $this->parseBooleanText($request->input('hamil'));
        $validated['menyusui'] = $this->parseBooleanText($request->input('menyusui'));
        $warga->update($validated);

        return redirect()->route('warga.index')->with('success', 'Data warga berhasil diperbarui.');
    }

    private function normalizeJenisKelamin($value): string
    {
        $raw = trim((string) $value);
        if ($raw === '') {
            return '';
        }

        $normalized = mb_strtolower($raw);
        $normalized = str_replace(['.', ',', '_', '-', '/'], ' ', $normalized);
        $normalized = preg_replace('/\s+/', ' ', $normalized) ?? $normalized;

        if (str_contains($normalized, 'perem') || str_contains($normalized, 'wanita')) {
            return 'Perempuan';
        }

        if (str_contains($normalized, 'laki') || str_contains($normalized, 'pria')) {
            return 'Laki-laki';
        }

        return $raw;
    }

    private function parseBooleanText($value): bool
    {
        $raw = trim((string) $value);
        if ($raw === '') {
            return false;
        }

        $normalized = mb_strtolower($raw);
        $normalized = str_replace([' ', '.', ',', '_', '-'], '', $normalized);

        if (in_array($normalized, ['1', 'y', 'ya', 'iya', 'yes', 'true', 't', 'on'], true)) {
            return true;
        }

        if (in_array($normalized, ['0', 'n', 'no', 'tidak', 'false', 'f', 'off'], true)) {
            return false;
        }

        return false;
    }

    private function normalizeAjukanPerpindahan($value): ?string
    {
        $raw = trim((string) $value);
        if ($raw === '') {
            return null;
        }

        $normalized = mb_strtolower($raw);
        $normalized = str_replace(['.', ',', '_', '-', '/'], ' ', $normalized);
        $normalized = preg_replace('/\s+/', ' ', $normalized) ?? $normalized;
        $normalized = trim($normalized);

        if ($normalized === 'tidak' || $normalized === 'tdk' || $normalized === 'no') {
            return 'tidak';
        }

        if (str_contains($normalized, 'ke dalam') || str_contains($normalized, 'kedalam')) {
            return 'kedalam_kita';
        }

        if (str_contains($normalized, 'keluar')) {
            return 'keluar_kota';
        }

        return $raw;
    }

    public function destroy(Warga $warga)
    {
        $warga->delete();

        return redirect()->route('warga.index')->with('success', 'Data warga berhasil dihapus.');
    }
}
