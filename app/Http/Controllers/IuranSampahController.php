<?php

namespace App\Http\Controllers;

use App\Models\IuranSampah;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IuranSampahController extends Controller
{
    private function normalizeNik(?string $nik): string
    {
        return preg_replace('/\D+/', '', (string) $nik) ?? '';
    }

    public function index(Request $request)
    {
        $query = IuranSampah::with(['warga', 'user']);

        if ($request->has('bulan') && $request->bulan !== 'all') {
            $query->where('bulan', $request->bulan);
        }

        if ($request->has('tahun') && $request->tahun !== 'all') {
            $query->where('tahun', $request->tahun);
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('petugas', 'like', "%{$search}%")
                    ->orWhereHas('warga', function ($qw) use ($search) {
                        $qw->where('nama_lengkap', 'like', "%{$search}%")
                            ->orWhere('nik', 'like', "%{$search}%");
                    });
            });
        }

        $iuranSampahs = $query->orderBy('tahun', 'desc')->orderByRaw("FIELD(bulan, 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')")->paginate(10);

        $bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $tahunList = IuranSampah::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        return view('iuran-sampah.index', compact('iuranSampahs', 'bulanList', 'tahunList'));
    }

    public function create()
    {
        $bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $tahunList = range(date('Y'), date('Y') - 5);

        return view('iuran-sampah.create', compact('bulanList', 'tahunList'));
    }

    public function store(Request $request)
    {
        $bulan = $request->input('bulan');
        if (is_numeric($bulan)) {
            $bulanNumber = (int) $bulan;
            $bulanMap = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember',
            ];
            if (isset($bulanMap[$bulanNumber])) {
                $request->merge(['bulan' => $bulanMap[$bulanNumber]]);
            }
        }

        $validated = $request->validate([
            'nik' => 'required|string',
            'bulan' => 'required|in:Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember',
            'tahun' => 'required|integer|min:2020|max:2100',
            'nominal' => 'required|numeric|min:0',
            'status' => 'sometimes|in:lunas,belum',
            'tanggal_bayar' => 'nullable|date',
            'petugas' => 'nullable|string|max:255',
        ]);

        $nik = $this->normalizeNik($validated['nik']);
        if (strlen($nik) !== 16) {
            return back()
                ->withErrors(['nik' => 'NIK harus 16 digit.'])
                ->withInput();
        }
        $wargaId = Warga::query()->where('nik', $nik)->value('id');
        if (! $wargaId) {
            return back()
                ->withErrors(['nik' => 'NIK tidak ditemukan.'])
                ->withInput();
        }

        $validated['warga_id'] = $wargaId;
        unset($validated['nik']);
        $validated['user_id'] = Auth::id();

        if ($validated['status'] === 'lunas' && ! $request->has('tanggal_bayar')) {
            $validated['tanggal_bayar'] = date('Y-m-d');
        }

        IuranSampah::create($validated);

        return redirect()->route('iuran-sampah.index')->with('success', 'Data iuran sampah berhasil ditambahkan.');
    }

    public function edit(IuranSampah $iuran_sampah)
    {
        $bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $tahunList = range(date('Y'), date('Y') - 5);
        $iuranSampah = $iuran_sampah;

        return view('iuran-sampah.edit', compact('iuranSampah', 'bulanList', 'tahunList'));
    }

    public function update(Request $request, IuranSampah $iuran_sampah)
    {
        $iuranSampah = $iuran_sampah;

        $bulan = $request->input('bulan');
        if (is_numeric($bulan)) {
            $bulanNumber = (int) $bulan;
            $bulanMap = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember',
            ];
            if (isset($bulanMap[$bulanNumber])) {
                $request->merge(['bulan' => $bulanMap[$bulanNumber]]);
            }
        }

        $validated = $request->validate([
            'nik' => 'required|string',
            'bulan' => 'required|in:Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember',
            'tahun' => 'required|integer|min:2020|max:2100',
            'nominal' => 'required|numeric|min:0',
            'status' => 'sometimes|in:lunas,belum',
            'tanggal_bayar' => 'nullable|date',
            'petugas' => 'nullable|string|max:255',
        ]);

        $nik = $this->normalizeNik($validated['nik']);
        if (strlen($nik) !== 16) {
            return back()
                ->withErrors(['nik' => 'NIK harus 16 digit.'])
                ->withInput();
        }
        $wargaId = Warga::query()->where('nik', $nik)->value('id');
        if (! $wargaId) {
            return back()
                ->withErrors(['nik' => 'NIK tidak ditemukan.'])
                ->withInput();
        }

        $validated['warga_id'] = $wargaId;
        unset($validated['nik']);
        $iuranSampah->update($validated);

        return redirect()->route('iuran-sampah.index')->with('success', 'Data iuran sampah berhasil diperbarui.');
    }

    public function destroy(IuranSampah $iuran_sampah)
    {
        $iuranSampah = $iuran_sampah;

        $iuranSampah->delete();

        return redirect()->route('iuran-sampah.index')->with('success', 'Data iuran sampah berhasil dihapus.');
    }
}
