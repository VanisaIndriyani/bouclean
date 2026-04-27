@extends('layouts.app')

@section('title', 'Edit Profil - Bouclear')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-0">Pengaturan Profil</h4>
        <p class="mb-0 opacity-75">Kelola informasi akun Anda</p>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-4 mb-4">
            <div class="mb-3">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="rounded-circle shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                @else
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 120px; height: 120px;">
                        <i class="bi bi-person fs-1"></i>
                    </div>
                @endif
            </div>
            <h5 class="mb-1">{{ $user->name }}</h5>
            <p class="text-muted small mb-3">{{ $user->username }}</p>
            <span class="badge bg-secondary rounded-pill px-3">{{ ucfirst($user->role) }}</span>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h6 class="mb-4 text-primary fw-bold"><i class="bi bi-person-gear me-2"></i>Informasi Dasar</h6>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username', $user->username) }}" required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Foto Profil</label>
                            <input type="file" class="form-control @error('avatar') is-invalid @enderror" name="avatar" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG, GIF. Maks: 2MB</small>
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <h6 class="mb-4 text-primary fw-bold"><i class="bi bi-shield-lock me-2"></i>Keamanan (Kosongkan jika tidak ingin ganti)</h6>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Password Saat Ini</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" id="currentPassword">
                                <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" id="toggleCurrentPassword" style="border-color: #dee2e6;">
                                    <i class="bi bi-eye-slash text-muted" id="toggleCurrentPasswordIcon"></i>
                                </button>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" id="newPassword">
                                <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" id="toggleNewPassword" style="border-color: #dee2e6;">
                                    <i class="bi bi-eye-slash text-muted" id="toggleNewPasswordIcon"></i>
                                </button>
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="new_password_confirmation" id="newPasswordConfirmation">
                                <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" id="toggleNewPasswordConfirmation" style="border-color: #dee2e6;">
                                    <i class="bi bi-eye-slash text-muted" id="toggleNewPasswordConfirmationIcon"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-check-lg me-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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

    toggleField('toggleCurrentPassword', 'currentPassword', 'toggleCurrentPasswordIcon');
    toggleField('toggleNewPassword', 'newPassword', 'toggleNewPasswordIcon');
    toggleField('toggleNewPasswordConfirmation', 'newPasswordConfirmation', 'toggleNewPasswordConfirmationIcon');
</script>
@endpush
