@extends('layouts.app')

@section('title', 'Wilayah Administrasi - Bouclean')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-0">Wilayah Administrasi</h4>
        <p class="mb-0 opacity-75">Kelola data wilayah administrasi</p>
    </div>
    @if(Auth::user()->role === 'admin')
    <a href="{{ route('wilayah.create') }}" class="btn btn-primary rounded-pill">
        <i class="bi bi-plus-lg me-2"></i> Tambah Wilayah
    </a>
    @endif
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="GET" action="{{ route('wilayah.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control" name="search" placeholder="Cari nama pengguna..." value="{{ request('search') }}">
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
                        <th>Kecamatan</th>
                        <th>Kelurahan</th>
                        <th>RT</th>
                        <th>RW</th>
                        <th>Dasawisma</th>
                        <th>Nama Pengguna</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wilayahs as $index => $wilayah)
                    <tr>
                        <td class="text-center">{{ $wilayahs->firstItem() + $index }}</td>
                        <td>{{ $wilayah->kecamatan }}</td>
                        <td>{{ $wilayah->kelurahan }}</td>
                        <td>{{ $wilayah->rt }}</td>
                        <td>{{ $wilayah->rw }}</td>
                        <td>{{ $wilayah->dasawisma }}</td>
                        <td>{{ $wilayah->nama_pengguna }}</td>
                        <td>
                            <div class="btn-group">
                                @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('wilayah.edit', $wilayah) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('wilayah.destroy', $wilayah) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Yakin hapus?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">Hanya Admin</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                <strong>Belum ada data wilayah</strong>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $wilayahs->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
