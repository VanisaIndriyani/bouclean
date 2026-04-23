@extends('layouts.app')

@section('title', 'Data Warga - Bouclean')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-0">Data Warga</h4>
        <p class="mb-0 opacity-75">Kelola data warga Bank Sampah</p>
    </div>
    <a href="{{ route('warga.create') }}" class="btn btn-primary rounded-pill">
        <i class="bi bi-plus-lg me-2"></i> Tambah Warga
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="GET" action="{{ route('warga.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control" name="search" placeholder="Cari nama, NIK, atau wilayah..." value="{{ request('search') }}">
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
                        <th>NIK</th>
                        <th>Nama Lengkap</th>
                        <th>JK</th>
                        <th>Tempat, Tgl Lahir</th>
                        <th>Wilayah</th>
                        <th>Dasawisma</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wargas as $index => $warga)
                    <tr>
                        <td class="text-center">{{ $wargas->firstItem() + $index }}</td>
                        <td>
                            <span class="badge bg-light text-dark">{{ $warga->nik }}</span>
                        </td>
                        <td>
                            <strong>{{ $warga->nama_lengkap }}</strong>
                        </td>
                        <td>
                            @if($warga->jenis_kelamin == 'Laki-laki')
                                <span class="badge bg-primary">L</span>
                            @else
                                <span class="badge bg-danger">P</span>
                            @endif
                        </td>
                        <td>
                            {{ $warga->tempat_lahir }}, {{ $warga->tanggal_lahir->format('d/m/Y') }}
                        </td>
                        <td>
                            <small>{{ $warga->kelurahan }}</small><br>
                            <small class="text-muted">RT {{ $warga->rt }}/RW {{ $warga->rw }}, {{ $warga->kecamatan }}</small>
                        </td>
                        <td>{{ $warga->dasawisma }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('warga.edit', $warga) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if(Auth::user()->role === 'admin')
                                    <form action="{{ route('warga.destroy', $warga) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Yakin hapus data ini?')">
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
                                <strong>Belum ada data warga</strong>
                                <p class="mb-0 small">Silakan tambahkan data warga baru</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $wargas->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
