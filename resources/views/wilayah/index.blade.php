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
                        <th>RW</th>
                        <th>RT</th>
                        @if($view === 'dasawisma')
                            <th>Dasawisma</th>
                            <th>Nama Pengguna</th>
                        @else
                            <th>Nama</th>
                            <th>Pengguna</th>
                        @endif
                        <th width="120" class="text-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wilayahs as $index => $wilayah)
                    <tr>
                        <td class="text-center">{{ $wilayahs->firstItem() + $index }}</td>
                        <td>{{ $wilayah->kecamatan }}</td>
                        <td>{{ $wilayah->kelurahan }}</td>
                        <td>{{ $wilayah->rw }}</td>
                        <td>{{ $wilayah->rt }}</td>
                        @if($view === 'dasawisma')
                            <td>{{ $wilayah->dasawisma }}</td>
                            <td>{{ $wilayah->nama_pengguna }}</td>
                        @else
                            <td>
                                @php
                                    $namaDasawisma = trim((string) $wilayah->dasawisma);
                                    $dasawismaNama = $namaDasawisma;
                                    $dasawismaNo = null;
                                    if (preg_match('/^(.*)\s+(\d+)$/', $namaDasawisma, $m)) {
                                        $dasawismaNama = trim($m[1]);
                                        $dasawismaNo = $m[2];
                                    }
                                @endphp
                                <div class="fw-semibold text-uppercase">{{ $dasawismaNama }}</div>
                                @if($dasawismaNo !== null)
                                    <div class="text-muted small">{{ $dasawismaNo }}</div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $countKey = mb_strtolower(trim((string) $wilayah->dasawisma));
                                    $penggunaCount = $penggunaCountMap[$countKey] ?? 0;
                                    $penggunaBadgeClass = $penggunaCount > 0 ? 'bg-warning text-dark' : 'bg-primary text-white';
                                @endphp
                                <button type="button" class="btn p-0 border-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#penggunaWilayahModal{{ $wilayah->id }}">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-3 {{ $penggunaBadgeClass }}"
                                         style="width: 44px; height: 32px; font-weight: 700;">
                                        {{ $penggunaCount }}
                                    </div>
                                </button>
                            </td>
                        @endif
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

        @if($view !== 'dasawisma')
            @foreach($wilayahs as $wilayah)
                @php
                    $namaDasawisma = trim((string) $wilayah->dasawisma);
                    $dasawismaNama = $namaDasawisma;
                    $dasawismaNo = null;
                    if (preg_match('/^(.*)\s+(\d+)$/', $namaDasawisma, $m)) {
                        $dasawismaNama = trim($m[1]);
                        $dasawismaNo = $m[2];
                    }

                    $countKey = mb_strtolower(trim((string) $wilayah->dasawisma));
                    $penggunaCount = $penggunaCountMap[$countKey] ?? 0;
                @endphp
                <div class="modal fade" id="penggunaWilayahModal{{ $wilayah->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Pengguna</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive overflow-auto" style="max-height: 70vh;">
                                    <table class="table table-hover align-middle text-nowrap mb-0" style="min-width: 900px;">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="60">No</th>
                                                <th>Nama</th>
                                                <th>Username</th>
                                                <th>Aktifitas Terakhir</th>
                                                <th width="120">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $userList = $penggunaUserMap[$countKey] ?? collect();
                                            @endphp
                                            @forelse($userList as $i => $u)
                                                <tr>
                                                    <td class="text-center">{{ $i + 1 }}</td>
                                                    <td class="fw-semibold">{{ $u->name }}</td>
                                                    <td class="text-muted">{{ $u->username ?? '-' }}</td>
                                                    <td>
                                                        @if($u->last_login_at)
                                                            {{ $u->last_login_at->format('d/m/Y H:i') }}
                                                        @else
                                                            <span class="text-muted">Belum Pernah Login</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(Auth::user()->role === 'admin')
                                                            <div class="btn-group">
                                                                <a href="{{ route('users.create', ['search' => $u->username]) }}" class="btn btn-sm btn-outline-primary rounded-pill" title="Edit">
                                                                    <i class="bi bi-pencil"></i>
                                                                </a>
                                                                <form action="{{ route('users.destroy', $u) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Yakin hapus?')" title="Hapus">
                                                                        <i class="bi bi-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @else
                                                            <span class="text-muted small">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted py-3">Belum ada data pengguna</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

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
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        Pengguna
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                            {{ $users->count() }}
                        </span>
                    </h5>
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
                                        <td>{{ $u->username }}</td>
                                        <td>
                                                        @if($u->last_login_at)
                                                            {{ $u->last_login_at->format('d/m/Y H:i') }}
                                            @else
                                                            <span class="text-muted">Belum Pernah Login</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#userEditModal{{ $u->id }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                            @if($u->role !== 'admin')
                                                                <form action="{{ route('users.destroy', $u) }}" method="POST" class="d-inline" id="wilayahDeleteForm{{ $u->id }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" data-bs-toggle="modal" data-bs-target="#wilayahDeleteConfirmModal" data-delete-form="wilayahDeleteForm{{ $u->id }}">
                                                                        <i class="bi bi-trash"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
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
                                                <label class="form-label">Username</label>
                                                <input type="text" class="form-control" name="username" value="{{ $u->username }}" required>
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

<div class="modal fade" id="wilayahDeleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Peringatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning mb-0" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>Yakin hapus pengguna ini?
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger rounded-pill" id="wilayahConfirmDeleteButton">Hapus</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const wilayahDeleteConfirmModal = document.getElementById('wilayahDeleteConfirmModal');
    const wilayahConfirmDeleteButton = document.getElementById('wilayahConfirmDeleteButton');
    let wilayahDeleteFormId = null;

    wilayahDeleteConfirmModal?.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        wilayahDeleteFormId = button?.getAttribute('data-delete-form') || null;
    });

    wilayahConfirmDeleteButton?.addEventListener('click', function () {
        if (!wilayahDeleteFormId) return;
        const form = document.getElementById(wilayahDeleteFormId);
        form?.submit();
    });
</script>
@endpush
