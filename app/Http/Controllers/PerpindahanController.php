<?php

namespace App\Http\Controllers;

use App\Models\Perpindahan;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerpindahanController extends Controller
{
    public function index(Request $request)
    {
        $query = Perpindahan::with(['warga', 'user']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('asal', 'like', "%{$search}%")
                  ->orWhere('tujuan', 'like', "%{$search}%")
                  ->orWhere('diusulkan_oleh', 'like', "%{$search}%")
                  ->orWhereHas('warga', function($qw) use ($search) {
                      $qw->where('nama_lengkap', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $perpindahans = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('perpindahan.index', compact('perpindahans'));
    }

    public function create()
    {
        $wargas = Warga::orderBy('nama_lengkap')->get();
        return view('perpindahan.create', compact('wargas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warga_id' => 'required|exists:wargas,id',
            'asal' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'diusulkan_oleh' => 'required|string|max:255',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';
        Perpindahan::create($validated);

        return redirect()->route('perpindahan.index')->with('success', 'Pengajuan perpindahan berhasil dikirim.');
    }

    public function edit(Perpindahan $perpindahan)
    {
        $wargas = Warga::orderBy('nama_lengkap')->get();
        return view('perpindahan.edit', compact('perpindahan', 'wargas'));
    }

    public function update(Request $request, Perpindahan $perpindahan)
    {
        $validated = $request->validate([
            'warga_id' => 'required|exists:wargas,id',
            'asal' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'diusulkan_oleh' => 'required|string|max:255',
            'status' => 'sometimes|in:pending,disetujui,ditolak',
            'tindak_lanjut' => 'nullable|string',
        ]);

        $perpindahan->update($validated);

        return redirect()->route('perpindahan.index')->with('success', 'Data perpindahan berhasil diperbarui.');
    }

    public function approve(Perpindahan $perpindahan)
    {
        $perpindahan->update([
            'status' => 'disetujui',
            'tindak_lanjut' => 'Disetujui oleh admin pada ' . now()->format('d/m/Y H:i'),
        ]);

        return redirect()->route('perpindahan.index')->with('success', 'Pengajuan perpindahan disetujui.');
    }

    public function reject(Perpindahan $perpindahan)
    {
        $perpindahan->update([
            'status' => 'ditolak',
            'tindak_lanjut' => 'Ditolak oleh admin pada ' . now()->format('d/m/Y H:i'),
        ]);

        return redirect()->route('perpindahan.index')->with('error', 'Pengajuan perpindahan ditolak.');
    }

    public function destroy(Perpindahan $perpindahan)
    {
        $perpindahan->delete();
        return redirect()->route('perpindahan.index')->with('success', 'Data perpindahan berhasil dihapus.');
    }
}
