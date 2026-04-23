@extends('layouts.app')

@section('title', 'Data Warga - Bouclear')

@push('styles')
<style>
    .action-cell {
        display: grid;
        grid-template-columns: 44px 1fr;
        gap: 10px;
        align-items: start;
    }
    .action-icons {
        display: flex;
        flex-direction: column;
        gap: 8px;
        align-items: center;
    }
    .action-icons .btn {
        width: 44px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }
    .action-pills {
        display: flex;
        flex-direction: column;
        gap: 8px;
        align-items: stretch;
    }
    .pill-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        padding: 8px 14px;
        border-radius: 999px;
        font-weight: 700;
        font-size: 12px;
        line-height: 1;
        color: #fff;
        text-decoration: none;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        transition: transform 0.15s ease, box-shadow 0.15s ease, opacity 0.15s ease;
        white-space: nowrap;
        justify-content: center;
        width: 100%;
    }
    .pill-action i {
        font-size: 16px;
    }
    .pill-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 22px rgba(0, 0, 0, 0.14);
        color: #fff;
        opacity: 0.95;
    }
    .pill-status { background: #2f5b9a; }
    .pill-activity { background: #1f9d55; }
    .pill-health { background: #2aa8c9; }
</style>
@endpush

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
                        <th>Jenis Kelamin</th>
                        <th>Tempat, Tgl Lahir</th>
                        <th>Wilayah</th>
                        <th>Dasawisma</th>
                        <th width="220">Aksi</th>
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
                            <div class="action-cell">
                                <div class="action-icons">
                                    <a href="{{ route('warga.edit', $warga) }}" class="btn btn-sm btn-outline-primary rounded-pill" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if(Auth::user()->role === 'admin')
                                        <form action="{{ route('warga.destroy', $warga) }}" method="POST" class="m-0 p-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" title="Hapus" onclick="return confirm('Yakin hapus data ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <div class="action-pills">
                                    <button type="button" class="pill-action pill-status" data-bs-toggle="modal" data-bs-target="#statusModal{{ $warga->id }}">
                                        <i class="bi bi-diagram-3"></i> Status
                                    </button>
                                    <button type="button" class="pill-action pill-activity" data-bs-toggle="modal" data-bs-target="#activityModal{{ $warga->id }}">
                                        <i class="bi bi-activity"></i> Aktifitas
                                    </button>
                                    <a href="{{ route('warga.kesehatan.index', $warga) }}" class="pill-action pill-health">
                                        <i class="bi bi-stethoscope"></i> Kesehatan
                                    </a>
                                </div>
                            </div>

                            <div class="modal fade" id="statusModal{{ $warga->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Status Warga</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="p-3 bg-light rounded-3">
                                                        <div class="fw-semibold">{{ $warga->nama_lengkap }}</div>
                                                        <div class="text-muted small">{{ $warga->nik }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="p-3 bg-light rounded-3">
                                                        <div class="small text-muted">Status</div>
                                                        <div class="fw-semibold">{{ $warga->status ?? '-' }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="p-3 border rounded-3">
                                                        <div class="small text-muted">Buta</div>
                                                        <div class="fw-semibold">{{ $warga->buta ? 'Ya' : 'Tidak' }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="p-3 border rounded-3">
                                                        <div class="small text-muted">Hamil</div>
                                                        <div class="fw-semibold">{{ $warga->hamil ? 'Ya' : 'Tidak' }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="p-3 border rounded-3">
                                                        <div class="small text-muted">Menyusui</div>
                                                        <div class="fw-semibold">{{ $warga->menyusui ? 'Ya' : 'Tidak' }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <a href="{{ route('perpindahan.create') }}" class="btn btn-primary rounded-pill">
                                                <i class="bi bi-arrow-left-right me-2"></i> Ajukan Perpindahan
                                            </a>
                                            <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="activityModal{{ $warga->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Aktifitas</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="p-3 bg-light rounded-3">
                                                        <div class="fw-semibold">{{ $warga->nama_lengkap }}</div>
                                                        <div class="text-muted small">{{ $warga->nik }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="p-3 bg-light rounded-3">
                                                        <div class="small text-muted">Terakhir Diubah</div>
                                                        <div class="fw-semibold">{{ $warga->updated_at ? $warga->updated_at->format('d/m/Y H:i') : '-' }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="p-3 border rounded-3">
                                                        <div class="small text-muted">Dibuat</div>
                                                        <div class="fw-semibold">{{ $warga->created_at ? $warga->created_at->format('d/m/Y H:i') : '-' }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="p-3 border rounded-3">
                                                        <div class="small text-muted">Penginput</div>
                                                        <div class="fw-semibold">{{ $warga->user?->name ?? '-' }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
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
