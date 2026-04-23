@extends('layouts.app')

@section('title', 'Pilah Sampah - Bouclean')

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
                        <input type="text" class="form-control" name="search" placeholder="Cari nama warga..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-secondary rounded-pill w-100">
                        <i class="bi bi-funnel me-2"></i> Filter
                    </button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Warga</th>
                        <th>JK</th>
                        <th>Berat (gram)</th>
                        <th>Sedekah</th>
                        <th>Harga</th>
                        <th>Foto</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pilahSampahs as $index => $pilah)
                    <tr>
                        <td class="text-center">{{ $pilahSampahs->firstItem() + $index }}</td>
                        <td>
                            <strong>{{ $pilah->warga->nama_lengkap }}</strong><br>
                            <small class="text-muted">{{ $pilah->warga->nik }}</small>
                        </td>
                        <td>
                            @if($pilah->jenis_kelamin == 'Laki-laki')
                                <span class="badge bg-primary">L</span>
                            @else
                                <span class="badge bg-danger">P</span>
                            @endif
                        </td>
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
                                <img src="{{ $pilah->foto_url }}" alt="Foto" class="img-thumbnail" style="max-width: 60px;">
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
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
