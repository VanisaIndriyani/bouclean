@extends('layouts.app')

@section('title', 'Buat Akun User - Bouclear')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-0">Pengguna</h4>
        <p class="mb-0 opacity-75">Kelola akun user (dibuat oleh admin)</p>
    </div>
    <button type="button" class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#userCreateModal">
        <i class="bi bi-person-plus me-2"></i> Buat Akun
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('users.create') }}" class="mb-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama, username, dasawisma...">
                    </div>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-outline-primary rounded-pill" type="submit">Cari</button>
                    <a class="btn btn-outline-secondary rounded-pill" href="{{ route('users.create') }}">Reset</a>
                </div>
            </div>
        </form>

        <div class="table-responsive overflow-auto">
            <table class="table table-hover align-middle text-nowrap mb-0" style="min-width: 720px;">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Nama</th>
                        <th>Dasawisma</th>
                        <th>Username</th>
                        <th width="140">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($users ?? collect()) as $i => $u)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td class="fw-semibold">{{ $u->name }}</td>
                            <td>{{ $u->dasawisma ?? '-' }}</td>
                            <td>{{ $u->username }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#userEditModal{{ $u->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('users.destroy', $u) }}" method="POST" class="d-inline" id="deleteForm{{ $u->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal" data-delete-form="deleteForm{{ $u->id }}">
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
    </div>
</div>

<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
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
                <button type="button" class="btn btn-danger rounded-pill" id="confirmDeleteButton">Hapus</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="userCreateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buat Akun</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required placeholder="Nama petugas">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required placeholder="contoh: fendi_01">
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Dasawisma</label>
                            @php
                                $dasawismaInvalid = $errors->has('dasawisma') || $errors->has('dasawisma.*');
                                $oldDasawisma = (array) old('dasawisma', []);
                            @endphp
                            <div class="border rounded p-2 {{ $dasawismaInvalid ? 'border-danger' : '' }}" style="max-height: 140px; overflow: auto;">
                                @forelse(($dasawismaOptions ?? collect()) as $opt)
                                    <div class="form-check">
                                        <input
                                            class="form-check-input {{ $dasawismaInvalid ? 'is-invalid' : '' }}"
                                            type="checkbox"
                                            name="dasawisma[]"
                                            id="createDasawisma{{ $loop->index }}"
                                            value="{{ $opt }}"
                                            {{ in_array($opt, $oldDasawisma, true) ? 'checked' : '' }}
                                        >
                                        <label class="form-check-label" for="createDasawisma{{ $loop->index }}">{{ $opt }}</label>
                                    </div>
                                @empty
                                    <div class="text-muted small">Belum ada data dasawisma.</div>
                                @endforelse
                            </div>
                            @error('dasawisma')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('dasawisma.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-lock text-muted"></i>
                                </span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="createPassword" required placeholder="Minimal 8 karakter">
                                <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" id="toggleCreatePassword" style="border-color: #dee2e6;">
                                    <i class="bi bi-eye-slash text-muted" id="toggleCreatePasswordIcon"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-shield-lock text-muted"></i>
                                </span>
                                <input type="password" class="form-control" name="password_confirmation" id="createPasswordConfirmation" required placeholder="Ulangi password">
                                <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" id="toggleCreatePasswordConfirmation" style="border-color: #dee2e6;">
                                    <i class="bi bi-eye-slash text-muted" id="toggleCreatePasswordConfirmationIcon"></i>
                                </button>
                            </div>
                        </div>
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

@foreach(($users ?? collect()) as $u)
    <div class="modal fade" id="userEditModal{{ $u->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('users.update', $u) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control" name="name" value="{{ $u->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" value="{{ $u->username }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Dasawisma</label>
                                @php
                                    $selectedDasawisma = collect(explode(',', (string) ($u->dasawisma ?? '')))
                                        ->map(fn ($d) => trim((string) $d))
                                        ->filter(fn ($d) => $d !== '')
                                        ->values()
                                        ->all();
                                @endphp
                                <div class="border rounded p-2" style="max-height: 140px; overflow: auto;">
                                    @forelse(($dasawismaOptions ?? collect()) as $opt)
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="dasawisma[]"
                                                id="editDasawisma{{ $u->id }}_{{ $loop->index }}"
                                                value="{{ $opt }}"
                                                {{ in_array($opt, $selectedDasawisma, true) ? 'checked' : '' }}
                                                {{ $u->role === 'admin' ? 'disabled' : '' }}
                                            >
                                            <label class="form-check-label" for="editDasawisma{{ $u->id }}_{{ $loop->index }}">{{ $opt }}</label>
                                        </div>
                                    @empty
                                        <div class="text-muted small">Belum ada data dasawisma.</div>
                                    @endforelse
                                </div>
                            </div>
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
@endsection

@push('scripts')
<script>
    const deleteConfirmModal = document.getElementById('deleteConfirmModal');
    const confirmDeleteButton = document.getElementById('confirmDeleteButton');
    let deleteFormId = null;

    deleteConfirmModal?.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        deleteFormId = button?.getAttribute('data-delete-form') || null;
    });

    confirmDeleteButton?.addEventListener('click', function () {
        if (!deleteFormId) return;
        const form = document.getElementById(deleteFormId);
        form?.submit();
    });

    const toggleField = (toggleButtonId, inputId, iconId) => {
        const toggleButton = document.getElementById(toggleButtonId);
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (!toggleButton || !input || !icon) return;

        toggleButton.addEventListener('click', function () {
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        });
    };

    toggleField('toggleCreatePassword', 'createPassword', 'toggleCreatePasswordIcon');
    toggleField('toggleCreatePasswordConfirmation', 'createPasswordConfirmation', 'toggleCreatePasswordConfirmationIcon');
</script>
@endpush
