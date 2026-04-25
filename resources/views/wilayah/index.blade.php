@extends('layouts.app')

@section('title', 'Wilayah Administrasi - Bouclear')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-0">{{ $view === 'dasawisma' ? 'Dasawisma' : 'Wilayah Administrasi' }}</h4>
        <p class="mb-0 opacity-75">
            {{ $view === 'dasawisma' ? 'Data dasawisma dan rekap tabel terkait' : 'Kelola data wilayah administrasi' }}
        </p>
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
            <input type="hidden" name="view" value="{{ $view }}">
            <div class="row g-3">
                <div class="col-md-{{ $view === 'dasawisma' ? '5' : '8' }}">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control" name="search" placeholder="Cari kecamatan, kelurahan, RT, RW, dasawisma, atau nama..." value="{{ request('search') }}">
                    </div>
                </div>
                @if($view === 'dasawisma')
                    <div class="col-md-3">
                        <select class="form-select" name="dasawisma">
                            <option value="all">-- Semua Dasawisma --</option>
                            @foreach($dasawismaList as $d)
                                <option value="{{ $d }}" {{ $selectedDasawisma == $d ? 'selected' : '' }}>{{ $d }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-md-{{ $view === 'dasawisma' ? '2' : '2' }}">
                    <button type="submit" class="btn btn-secondary rounded-pill w-100">
                        <i class="bi bi-search me-2"></i> Cari
                    </button>
                </div>
                <div class="col-md-{{ $view === 'dasawisma' ? '2' : '2' }}">
                    <a href="{{ route('wilayah.index', ['view' => $view]) }}" class="btn btn-outline-secondary rounded-pill w-100">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        @if($view !== 'dasawisma')
            <div class="mb-4">
                <div class="text-muted small fw-semibold mb-2">PENGGUNA</div>
                <button type="button" class="btn p-0 border-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#usersModal">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary bg-opacity-10 text-primary"
                         style="width: 64px; height: 64px; font-size: 22px; font-weight: 700;">
                        {{ $users->count() }}
                    </div>
                </button>
            </div>
        @endif

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-2">
            <div class="text-muted small">
                Menampilkan {{ $wilayahs->firstItem() ?? 0 }} - {{ $wilayahs->lastItem() ?? 0 }} dari {{ $wilayahs->total() }} data
            </div>
        </div>

        <div class="table-responsive overflow-auto">
            <table class="table table-hover align-middle text-nowrap" style="min-width: 900px;">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Kecamatan</th>
                        <th>Kelurahan</th>
                        <th>RT</th>
                        <th>RW</th>
                        @if($view === 'dasawisma')
                            <th>Dasawisma</th>
                        @endif
                        <th>Nama Pengguna</th>
                        <th width="120" class="text-nowrap">Actions</th>
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
                        @if($view === 'dasawisma')
                            <td>{{ $wilayah->dasawisma }}</td>
                        @endif
                        <td>{{ $wilayah->nama_pengguna }}</td>
                        <td class="text-nowrap">
                            <div class="d-flex flex-nowrap gap-1">
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
                        <td colspan="{{ $view === 'dasawisma' ? '8' : '7' }}" class="text-center py-4">
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

        @if($view === 'dasawisma')
            <div class="mt-5">
                <h6 class="fw-bold mb-3">Data Warga</h6>
                <div class="table-responsive overflow-auto">
                    <table class="table table-hover align-middle text-nowrap" style="min-width: 850px;">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>RT</th>
                                <th>RW</th>
                                <th>Dasawisma</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($wargasDasawisma as $i => $w)
                                <tr>
                                    <td class="text-center">{{ $i + 1 }}</td>
                                    <td>{{ $w->nik_masked }}</td>
                                    <td class="fw-semibold">{{ $w->nama_lengkap }}</td>
                                    <td>{{ $w->rt }}</td>
                                    <td>{{ $w->rw }}</td>
                                    <td>{{ $w->dasawisma }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">Belum ada data warga</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('warga.index', ['dasawisma' => $selectedDasawisma]) }}" class="btn btn-outline-secondary rounded-pill">
                    Lihat Semua Warga
                </a>
            </div>

            <div class="mt-5">
                <h6 class="fw-bold mb-3">Perpindahan Warga</h6>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Warga</th>
                                <th>Asal</th>
                                <th>Tujuan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($perpindahansDasawisma as $i => $p)
                                <tr>
                                    <td class="text-center">{{ $i + 1 }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $p->warga?->nama_lengkap ?? '-' }}</div>
                                        <div class="text-muted small">{{ $p->warga?->nik_masked ?? '-' }}</div>
                                    </td>
                                    <td>{{ $p->asal }}</td>
                                    <td>{{ $p->tujuan }}</td>
                                    <td>{!! $p->status_badge !!}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">Belum ada data perpindahan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('perpindahan.index', ['dasawisma' => $selectedDasawisma]) }}" class="btn btn-outline-secondary rounded-pill">
                    Lihat Semua Perpindahan
                </a>
            </div>
        @endif
    </div>
</div>

@if($view !== 'dasawisma')
    <div class="modal fade" id="usersModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive overflow-auto">
                        <table class="table table-hover align-middle text-nowrap mb-0" style="min-width: 720px;">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">No</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Aktifitas Terakhir</th>
                                    <th width="140">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $i => $u)
                                    <tr>
                                        <td class="text-center">{{ $i + 1 }}</td>
                                        <td class="fw-semibold">{{ $u->name }}</td>
                                        <td>{{ $u->email }}</td>
                                        <td>
                                            @if($u->last_activity_at)
                                                {{ $u->last_activity_at->format('d/m/Y H:i') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#userEditModal{{ $u->id }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form action="{{ route('users.destroy', $u) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Yakin hapus pengguna ini?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">Belum ada pengguna</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @foreach($users as $u)
                        <div class="modal fade" id="userEditModal{{ $u->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Pengguna</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('users.update', $u) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Nama</label>
                                                <input type="text" class="form-control" name="name" value="{{ $u->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control" name="email" value="{{ $u->email }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Role</label>
                                                <select class="form-select" name="role" required>
                                                    <option value="user" {{ $u->role === 'user' ? 'selected' : '' }}>User</option>
                                                    <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-primary rounded-pill">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
