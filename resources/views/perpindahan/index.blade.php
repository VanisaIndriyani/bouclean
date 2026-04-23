@extends('layouts.app')

@section('title', 'Perpindahan Warga - Bouclear')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-0">Perpindahan Warga</h4>
        <p class="mb-0 opacity-75">Kelola pengajuan perpindahan warga</p>
    </div>
    <a href="{{ route('perpindahan.create') }}" class="btn btn-primary rounded-pill">
        <i class="bi bi-plus-lg me-2"></i> Ajukan Perpindahan
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="GET" action="{{ route('perpindahan.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control" name="search" placeholder="Cari asal/tujuan..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="status">
                        <option value="all">-- Semua Status --</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3">
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
                        <th>Asal</th>
                        <th>Tujuan</th>
                        <th>Diajukan Oleh</th>
                        <th>Status</th>
                        <th>Tindak Lanjut</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($perpindahans as $index => $perpindahan)
                    <tr>
                        <td class="text-center">{{ $perpindahans->firstItem() + $index }}</td>
                        <td>
                            <strong>{{ $perpindahan->warga->nama_lengkap }}</strong><br>
                            <small class="text-muted">{{ $perpindahan->warga->nik }}</small>
                        </td>
                        <td>{{ $perpindahan->asal }}</td>
                        <td>{{ $perpindahan->tujuan }}</td>
                        <td>{{ $perpindahan->diusulkan_oleh }}</td>
                        <td>{!! $perpindahan->status_badge !!}</td>
                        <td>
                            <small class="text-muted">{{ $perpindahan->tindak_lanjut ?? '-' }}</small>
                        </td>
                        <td>
                            @if(Auth::user()->role === 'admin' && $perpindahan->status === 'pending')
                                <div class="btn-group">
                                    <form action="{{ route('perpindahan.approve', $perpindahan) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success rounded-pill" title="Setujui">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('perpindahan.reject', $perpindahan) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger rounded-pill" title="Tolak">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                            <div class="btn-group">
                                <a href="{{ route('perpindahan.edit', $perpindahan) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if(Auth::user()->role === 'admin')
                                    <form action="{{ route('perpindahan.destroy', $perpindahan) }}" method="POST" class="d-inline">
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
                                <strong>Belum ada data perpindahan</strong>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $perpindahans->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
