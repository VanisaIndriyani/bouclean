<?php

namespace App\Http\Controllers;

use App\Models\IuranSampah;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IuranSampahController extends Controller
{
    private function extractDigits(?string $value): string
    {
        return preg_replace('/\D+/', '', trim((string) $value)) ?? '';
    }

    private function extractNik16(?string $value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        if (preg_match('/(\d{16})/', $value, $m)) {
            return $m[1];
        }

        $digits = $this->extractDigits($value);
        return strlen($digits) === 16 ? $digits : null;
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
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhereHas('warga', function ($qw) use ($search) {
                        $qw->where('nama_lengkap', 'like', "%{$search}%")
                            ->orWhere('nik', 'like', "%{$search}%")
                            ->orWhere('no_kk', 'like', "%{$search}%");
                    });
            });
        }

        $iuranSampahs = $query->orderBy('tahun', 'desc')->orderByRaw("FIELD(bulan, 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')")->paginate(10);

        $tokens = $iuranSampahs->getCollection()
            ->map(function (IuranSampah $iuran) {
                if ($iuran->warga) {
                    $nik = $this->extractNik16((string) $iuran->warga->getRawOriginal('nik'));
                    return $nik ? ['type' => 'nik', 'value' => $nik] : null;
                }

                $raw = (string) ($iuran->nik ?? '');
                $digits = $this->extractDigits($raw);
                if ($digits === '') {
                    return null;
                }

                $nik = $this->extractNik16($raw);
                if ($nik) {
                    return ['type' => 'nik', 'value' => $nik];
                }

                return ['type' => 'kk', 'value' => $digits];
            })
            ->filter()
            ->all();

        $niks = collect($tokens)->where('type', 'nik')->pluck('value')->unique()->values()->all();
        $kks = collect($tokens)->where('type', 'kk')->pluck('value')->unique()->values()->all();

        $wargaByNik = collect();
        if (!empty($niks)) {
            $wargaByNik = Warga::query()
                ->select(['nik', 'nama_lengkap'])
                ->whereIn('nik', $niks)
                ->get()
                ->keyBy('nik');
        }

        $wargaByKk = collect();
        if (!empty($kks)) {
            $wargaKkRows = Warga::query()
                ->select(['no_kk', 'nik', 'nama_lengkap'])
                ->whereNotNull('no_kk')
                ->where('no_kk', '!=', '')
                ->where(function ($q) use ($kks) {
                    foreach ($kks as $kk) {
                        $q->orWhere('no_kk', 'like', '%'.$kk.'%');
                    }
                })
                ->get();

            $wargaByKk = $wargaKkRows
                ->mapWithKeys(function (Warga $w) {
                    $digits = preg_replace('/\D+/', '', (string) ($w->no_kk ?? '')) ?? '';
                    return $digits !== '' ? [$digits => $w] : [];
                });
        }

        $bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $tahunList = IuranSampah::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        return view('iuran-sampah.index', compact('iuranSampahs', 'bulanList', 'tahunList', 'wargaByNik', 'wargaByKk'));
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
            'nik' => 'required|string|max:255',
            'bulan' => 'required|in:Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember',
            'tahun' => 'required|integer|min:2020|max:2100',
            'nominal' => 'required|numeric|min:0',
            'status' => 'sometimes|in:lunas,belum',
            'tanggal_bayar' => 'nullable|date',
            'petugas' => 'nullable|string|max:255',
        ]);

        $inputKkOrNik = trim((string) $validated['nik']);
        $digits = $this->extractDigits($inputKkOrNik);
        $nikCandidate = $this->extractNik16($inputKkOrNik);

        $validated['nik'] = $digits !== '' ? $digits : $inputKkOrNik;
        $validated['warga_id'] = null;
        $validated['user_id'] = Auth::id();

        if ($validated['status'] === 'lunas' && ! $request->has('tanggal_bayar')) {
            $validated['tanggal_bayar'] = date('Y-m-d');
        }

        $warga = Warga::query()
            ->where(function ($q) use ($inputKkOrNik, $digits) {
                $q->where('no_kk', $inputKkOrNik);
                if ($digits !== '' && $digits !== $inputKkOrNik) {
                    $q->orWhere('no_kk', $digits)->orWhere('no_kk', 'like', '%'.$digits.'%');
                }
            })
            ->when($nikCandidate !== null, function ($q) use ($nikCandidate) {
                $q->orWhere('nik', $nikCandidate);
            })
            ->first();

        if ($warga) {
            $validated['warga_id'] = $warga->id;
            $validated['nik'] = (string) $warga->getRawOriginal('nik');
        } elseif ($nikCandidate !== null) {
            $validated['nik'] = $nikCandidate;
        }

        IuranSampah::create($validated);

        return redirect()->route('iuran-sampah.index')->with('success', 'Data iuran sampah berhasil ditambahkan.');
    }

    public function edit(IuranSampah $iuran_sampah)
    {
        $bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $tahunList = range(date('Y'), date('Y') - 5);
        $iuranSampah = $iuran_sampah;
        $iuranSampah->load('warga');

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
            'nik' => 'required|string|max:255',
            'bulan' => 'required|in:Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember',
            'tahun' => 'required|integer|min:2020|max:2100',
            'nominal' => 'required|numeric|min:0',
            'status' => 'sometimes|in:lunas,belum',
            'tanggal_bayar' => 'nullable|date',
            'petugas' => 'nullable|string|max:255',
        ]);

        $inputKkOrNik = trim((string) $validated['nik']);
        $digits = $this->extractDigits($inputKkOrNik);
        $nikCandidate = $this->extractNik16($inputKkOrNik);

        $validated['nik'] = $digits !== '' ? $digits : $inputKkOrNik;
        $validated['warga_id'] = null;

        $warga = Warga::query()
            ->where(function ($q) use ($inputKkOrNik, $digits) {
                $q->where('no_kk', $inputKkOrNik);
                if ($digits !== '' && $digits !== $inputKkOrNik) {
                    $q->orWhere('no_kk', $digits)->orWhere('no_kk', 'like', '%'.$digits.'%');
                }
            })
            ->when($nikCandidate !== null, function ($q) use ($nikCandidate) {
                $q->orWhere('nik', $nikCandidate);
            })
            ->first();

        if ($warga) {
            $validated['warga_id'] = $warga->id;
            $validated['nik'] = (string) $warga->getRawOriginal('nik');
        } elseif ($nikCandidate !== null) {
            $validated['nik'] = $nikCandidate;
        }

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
