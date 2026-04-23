@extends('layouts.app')

@section('title', 'Edit Iuran - Bouclear')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-0">Edit Iuran Sampah</h4>
        <p class="mb-0 opacity-75">Edit data iuran sampah</p>
    </div>
    <a href="{{ route('iuran-sampah.index') }}" class="btn btn-outline-secondary rounded-pill">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('iuran-sampah.update', $iuranSampah) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Warga <span class="text-danger">*</span></label>
                    <select class="form-select @error('warga_id') is-invalid @enderror" name="warga_id" required>
                        <option value="">-- Pilih Warga --</option>
                        @foreach($wargas as $warga)
                            <option value="{{ $warga->id }}" {{ old('warga_id', $iuranSampah->warga_id) == $warga->id ? 'selected' : '' }}>
                                {{ $warga->nama_lengkap }} - {{ $warga->nik }}
                            </option>
                        @endforeach
                    </select>
                    @error('warga_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Bulan <span class="text-danger">*</span></label>
                    <select class="form-select @error('bulan') is-invalid @enderror" name="bulan" required>
                        <option value="">-- Pilih --</option>
                        @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $bulan)
                            <option value="{{ $loop->iteration }}" {{ old('bulan', $iuranSampah->bulan) == $loop->iteration ? 'selected' : '' }}>{{ $bulan }}</option>
                        @endforeach
                    </select>
                    @error('bulan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tahun <span class="text-danger">*</span></label>
                    <select class="form-select @error('tahun') is-invalid @enderror" name="tahun" required>
                        <option value="">-- Pilih --</option>
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ old('tahun', $iuranSampah->tahun) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    @error('tahun')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Nominal (Rp) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('nominal') is-invalid @enderror" name="nominal" value="{{ old('nominal', $iuranSampah->nominal) }}" min="0" required>
                    @error('nominal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" name="status">
                        <option value="belum" {{ old('status', $iuranSampah->status) == 'belum' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="lunas" {{ old('status', $iuranSampah->status) == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tanggal Bayar</label>
                    <input type="date" class="form-control @error('tanggal_bayar') is-invalid @enderror" name="tanggal_bayar" value="{{ old('tanggal_bayar', $iuranSampah->tanggal_bayar?->format('Y-m-d')) }}">
                    @error('tanggal_bayar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Petugas</label>
                    <input type="text" class="form-control @error('petugas') is-invalid @enderror" name="petugas" value="{{ old('petugas', $iuranSampah->petugas) }}">
                    @error('petugas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-check-lg me-2"></i> Update
                </button>
                <a href="{{ route('iuran-sampah.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
