@extends('layouts.app')

@section('title', 'Tambah Kesehatan Warga - Bouclear')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-0">Tambah Kesehatan Warga</h4>
        <p class="mb-0 opacity-75">{{ $warga->nama_lengkap }} ({{ $warga->nik_masked }})</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('warga.kesehatan.store', $warga) }}" method="POST">
            @csrf
            <div class="row g-4">
                <div class="col-md-4">
                    <label class="form-label">KEK</label>
                    <select class="form-select @error('kek') is-invalid @enderror" name="kek">
                        <option value="0" {{ old('kek', '0') == '0' ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ old('kek') == '1' ? 'selected' : '' }}>Ya</option>
                    </select>
                    @error('kek')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Anemia</label>
                    <select class="form-select @error('anemia') is-invalid @enderror" name="anemia">
                        <option value="0" {{ old('anemia', '0') == '0' ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ old('anemia') == '1' ? 'selected' : '' }}>Ya</option>
                    </select>
                    @error('anemia')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Haid &gt; 7 Hari</label>
                    <select class="form-select @error('haid_lebih_7_hari') is-invalid @enderror" name="haid_lebih_7_hari">
                        <option value="0" {{ old('haid_lebih_7_hari', '0') == '0' ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ old('haid_lebih_7_hari') == '1' ? 'selected' : '' }}>Ya</option>
                    </select>
                    @error('haid_lebih_7_hari')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Belum Imunisasi</label>
                    <select class="form-select @error('belum_imunisasi') is-invalid @enderror" name="belum_imunisasi">
                        <option value="0" {{ old('belum_imunisasi', '0') == '0' ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ old('belum_imunisasi') == '1' ? 'selected' : '' }}>Ya</option>
                    </select>
                    @error('belum_imunisasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">TBC Tidak Berobat/Mangkir</label>
                    <select class="form-select @error('tbc_mangkir') is-invalid @enderror" name="tbc_mangkir">
                        <option value="0" {{ old('tbc_mangkir', '0') == '0' ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ old('tbc_mangkir') == '1' ? 'selected' : '' }}>Ya</option>
                    </select>
                    @error('tbc_mangkir')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Remaja Rokok</label>
                    <select class="form-select @error('remaja_rokok') is-invalid @enderror" name="remaja_rokok">
                        <option value="0" {{ old('remaja_rokok', '0') == '0' ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ old('remaja_rokok') == '1' ? 'selected' : '' }}>Ya</option>
                    </select>
                    @error('remaja_rokok')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ada Jentik</label>
                    <select class="form-select @error('ada_jentik') is-invalid @enderror" name="ada_jentik">
                        <option value="0" {{ old('ada_jentik', '0') == '0' ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ old('ada_jentik') == '1' ? 'selected' : '' }}>Ya</option>
                    </select>
                    @error('ada_jentik')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Laporan</label>
                    <input type="date" class="form-control @error('tanggal_laporan') is-invalid @enderror" name="tanggal_laporan" value="{{ old('tanggal_laporan') }}">
                    @error('tanggal_laporan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-check-lg me-2"></i> Simpan
                </button>
                <a href="{{ route('warga.kesehatan.index', $warga) }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
