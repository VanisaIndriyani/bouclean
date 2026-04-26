<?php

namespace App\Http\Controllers;

use App\Models\Perpindahan;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerpindahanController extends Controller
{
    private function normalizeNik(?string $nik): string
    {
        return preg_replace('/\D+/', '', (string) $nik) ?? '';
    }

    public function index(Request $request)
    {
        $query = Perpindahan::with(['warga', 'user']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('asal', 'like', "%{$search}%")
                    ->orWhere('tujuan', 'like', "%{$search}%")
                    ->orWhere('diusulkan_oleh', 'like', "%{$search}%")
                    ->orWhereHas('warga', function ($qw) use ($search) {
                        $qw->where('nama_lengkap', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('dasawisma') && $request->dasawisma !== 'all') {
            $query->whereHas('warga', function ($q) use ($request) {
                $q->where('dasawisma', $request->dasawisma);
            });
        }

        $perpindahans = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('perpindahan.index', compact('perpindahans'));
    }

    public function create()
    {
        return view('perpindahan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warga_nik' => 'required|string',
            'asal' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'diusulkan_oleh' => 'required|string|max:255',
        ]);

        $nik = $this->normalizeNik($validated['warga_nik']);
        if (strlen($nik) !== 16) {
            return back()
                ->withErrors(['warga_nik' => 'NIK harus 16 digit.'])
                ->withInput();
        }
        $warga = Warga::query()->where('nik', $nik)->first();
        if (! $warga) {
            return back()
                ->withErrors(['warga_nik' => 'NIK tidak ditemukan.'])
                ->withInput();
        }

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';
        $validated['warga_id'] = $warga->id;
        unset($validated['warga_nik']);
        Perpindahan::create($validated);

        return redirect()->route('perpindahan.index')->with('success', 'Pengajuan perpindahan berhasil dikirim.');
    }

    public function edit(Perpindahan $perpindahan)
    {
        $perpindahan->load('warga');

        return view('perpindahan.edit', compact('perpindahan'));
    }

    public function update(Request $request, Perpindahan $perpindahan)
    {
        $validated = $request->validate([
            'warga_nik' => 'required|string',
            'asal' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'diusulkan_oleh' => 'required|string|max:255',
            'status' => 'sometimes|in:pending,disetujui,ditolak',
            'tindak_lanjut' => 'nullable|string',
        ]);

        $nik = $this->normalizeNik($validated['warga_nik']);
        if (strlen($nik) !== 16) {
            return back()
                ->withErrors(['warga_nik' => 'NIK harus 16 digit.'])
                ->withInput();
        }
        $warga = Warga::query()->where('nik', $nik)->first();
        if (! $warga) {
            return back()
                ->withErrors(['warga_nik' => 'NIK tidak ditemukan.'])
                ->withInput();
        }

        $validated['warga_id'] = $warga->id;
        unset($validated['warga_nik']);
        $perpindahan->update($validated);

        return redirect()->route('perpindahan.index')->with('success', 'Data perpindahan berhasil diperbarui.');
    }

    public function approve(Perpindahan $perpindahan)
    {
        $perpindahan->update([
            'status' => 'disetujui',
            'tindak_lanjut' => 'Disetujui oleh admin pada '.now()->format('d/m/Y H:i'),
        ]);

        return redirect()->route('perpindahan.index')->with('success', 'Pengajuan perpindahan disetujui.');
    }

    public function reject(Perpindahan $perpindahan)
    {
        $perpindahan->update([
            'status' => 'ditolak',
            'tindak_lanjut' => 'Ditolak oleh admin pada '.now()->format('d/m/Y H:i'),
        ]);

        return redirect()->route('perpindahan.index')->with('error', 'Pengajuan perpindahan ditolak.');
    }

    public function destroy(Perpindahan $perpindahan)
    {
        $perpindahan->delete();

        return redirect()->route('perpindahan.index')->with('success', 'Data perpindahan berhasil dihapus.');
    }
}
