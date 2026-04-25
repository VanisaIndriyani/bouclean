@extends('layouts.app')

@section('title', 'Pilah Sampah - Bouclear')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-0">Pilah Sampah</h4>
        <p class="mb-0 opacity-75">Kelola data pilah sampah warga</p>
    </div>
    <a href="{{ route('pilah-sampah.create') }}" class="btn btn-primary rounded-pill">
        <i class="bi bi-plus-lg me-2"></i> Tambah Data
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="GET" action="{{ route('pilah-sampah.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control" name="search" placeholder="Cari nama, NIK, atau wilayah..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-secondary rounded-pill w-100">
                        <i class="bi bi-search me-2"></i> Cari
                    </button>
                </div>
            </div>
        </form>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-2">
            <div class="text-muted small">
                Menampilkan {{ $pilahSampahs->firstItem() ?? 0 }} - {{ $pilahSampahs->lastItem() ?? 0 }} dari {{ $pilahSampahs->total() }} data
            </div>
        </div>

        <div class="table-responsive overflow-auto">
            <table class="table table-hover align-middle text-nowrap" style="min-width: 900px;">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>KK</th>
                        <th>Jenis Sampah</th>
                        <th>Berat (gr)</th>
                        <th>Sedekah</th>
                        <th>Harga</th>
                        <th>Foto</th>
                        <th width="120" class="text-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pilahSampahs as $index => $pilah)
                    <tr>
                        <td class="text-center">{{ $pilahSampahs->firstItem() + $index }}</td>
                        <td>
                            <div class="fw-semibold">{{ $pilah->warga->no_kk ?: '-' }}</div>
                            <div class="text-muted small">{{ $pilah->warga->nama_lengkap }}</div>
                        </td>
                        <td>{{ $pilah->jenis_sampah ?? '-' }}</td>
                        <td>{{ number_format($pilah->berat, 0, ',', '.') }} g</td>
                        <td>
                            @if($pilah->sedekah)
                                <span class="badge bg-success"><i class="bi bi-check-lg"></i> Ya</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-x-lg"></i> Tidak</span>
                            @endif
                        </td>
                        <td>Rp {{ number_format($pilah->harga, 0, ',', '.') }}</td>
                        <td>
                            @if($pilah->foto_url)
                                <a href="{{ $pilah->foto_url }}" target="_blank">
                                    <img src="{{ $pilah->foto_url }}" alt="Foto" class="img-thumbnail" style="width: 64px; height: 64px; object-fit: cover;">
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-nowrap">
                            <div class="d-flex flex-nowrap gap-1">
                                <a href="{{ route('pilah-sampah.edit', $pilah) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if(Auth::user()->role === 'admin')
                                    <form action="{{ route('pilah-sampah.destroy', $pilah) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Yakin hapus?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                <strong>Belum ada data pilah sampah</strong>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $pilahSampahs->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
