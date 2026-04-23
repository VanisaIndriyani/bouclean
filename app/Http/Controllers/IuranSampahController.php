<?php

namespace App\Http\Controllers;

use App\Models\IuranSampah;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IuranSampahController extends Controller
{
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
        $wargas = Warga::orderBy('nama_lengkap')->get();
        $bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $tahunList = range(date('Y'), date('Y') - 5);

        return view('iuran-sampah.create', compact('wargas', 'bulanList', 'tahunList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warga_id' => 'required|exists:wargas,id',
            'bulan' => 'required|in:Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember',
            'tahun' => 'required|integer|min:2020|max:2100',
            'nominal' => 'required|numeric|min:0',
            'status' => 'sometimes|in:lunas,belum',
            'tanggal_bayar' => 'nullable|date',
            'petugas' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = Auth::id();

        if ($validated['status'] === 'lunas' && ! $request->has('tanggal_bayar')) {
            $validated['tanggal_bayar'] = date('Y-m-d');
        }

        IuranSampah::create($validated);

        return redirect()->route('iuran-sampah.index')->with('success', 'Data iuran sampah berhasil ditambahkan.');
    }

    public function edit(IuranSampah $iuranSampah)
    {
        $wargas = Warga::orderBy('nama_lengkap')->get();
        $bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $tahunList = range(date('Y'), date('Y') - 5);

        return view('iuran-sampah.edit', compact('iuranSampah', 'wargas', 'bulanList', 'tahunList'));
    }

    public function update(Request $request, IuranSampah $iuranSampah)
    {
        $validated = $request->validate([
            'warga_id' => 'required|exists:wargas,id',
            'bulan' => 'required|in:Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember',
            'tahun' => 'required|integer|min:2020|max:2100',
            'nominal' => 'required|numeric|min:0',
            'status' => 'sometimes|in:lunas,belum',
            'tanggal_bayar' => 'nullable|date',
            'petugas' => 'nullable|string|max:255',
        ]);

        $iuranSampah->update($validated);

        return redirect()->route('iuran-sampah.index')->with('success', 'Data iuran sampah berhasil diperbarui.');
    }

    public function destroy(IuranSampah $iuranSampah)
    {
        $iuranSampah->delete();

        return redirect()->route('iuran-sampah.index')->with('success', 'Data iuran sampah berhasil dihapus.');
    }
}
