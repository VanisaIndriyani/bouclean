@extends('layouts.app')

@section('title', 'Daftar Akun - Bouclean')

@section('content')
<div class="login-page">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5 col-lg-4">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #0d6efd 0%, #198754 100%);">
                        <div class="brand-logo mb-2 bg-transparent border-0">
                            <img src="{{ asset('img/Bougenville.png') }}" alt="Logo" style="width: 80px; height: auto;">
                        </div>
                        <h4 class="mb-0 text-white fw-bold">Bouclean</h4>
                        <p class="mb-0 text-white opacity-75 small">Bank Sampah Digital</p>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="text-center mb-4">Daftar Akun Baru</h5>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="bi bi-person text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus placeholder="Masukkan nama lengkap">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="bi bi-envelope text-muted"></i>
                                    </span>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="email@contoh.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="bi bi-lock text-muted"></i>
                                    </span>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Minimal 8 karakter">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Konfirmasi Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="bi bi-shield-lock text-muted"></i>
                                    </span>
                                    <input type="password" class="form-control" name="password_confirmation" required placeholder="Ulangi password">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success w-100 rounded-pill py-2 fw-bold">
                                <i class="bi bi-person-plus me-2"></i> Daftar Sekarang
                            </button>
                        </form>
                        
                        <div class="text-center mt-4">
                            <p class="small text-muted mb-0">Sudah punya akun? <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Masuk di sini</a></p>
                        </div>
                    </div>
                    <div class="card-footer text-center py-3 bg-light">
                        <small class="text-muted">Bank Sampah Digital &copy; {{ date('Y') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .login-page {
        background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
    }
    .card {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.9);
    }
    .brand-logo {
        width: 80px;
        height: 80px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
    }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush