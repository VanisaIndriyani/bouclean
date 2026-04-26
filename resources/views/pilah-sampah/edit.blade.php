@extends('layouts.app')

@section('title', 'Edit Pilah Sampah - Bouclear')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-0">Edit Data Pilah Sampah</h4>
        <p class="mb-0 opacity-75">Edit data pilah sampah</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('pilah-sampah.update', $pilahSampah) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Kepala Keluarga (NIK) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('kepala_keluarga_nik') is-invalid @enderror" name="kepala_keluarga_nik" value="{{ old('kepala_keluarga_nik', $pilahSampah->warga?->nik) }}" maxlength="16" required>
                    @error('kepala_keluarga_nik')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jenis Sampah</label>
                    <input type="text" class="form-control @error('jenis_sampah') is-invalid @enderror" name="jenis_sampah" value="{{ old('jenis_sampah', $pilahSampah->jenis_sampah) }}" placeholder="Contoh: Plastik, Kertas, Logam">
                    @error('jenis_sampah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <hr>
                    <h6 class="mb-3"><i class="bi bi-map me-2"></i>Wilayah</h6>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Kecamatan</label>
                    <input type="text" class="form-control @error('kecamatan') is-invalid @enderror" name="kecamatan" id="kecamatanInput" value="{{ old('kecamatan', $pilahSampah->kecamatan) }}">
                    @error('kecamatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Kelurahan</label>
                    <input type="text" class="form-control @error('kelurahan') is-invalid @enderror" name="kelurahan" id="kelurahanInput" value="{{ old('kelurahan', $pilahSampah->kelurahan) }}">
                    @error('kelurahan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2">
                    <label class="form-label">RT</label>
                    <input type="text" class="form-control @error('rt') is-invalid @enderror" name="rt" id="rtInput" value="{{ old('rt', $pilahSampah->rt) }}" maxlength="3">
                    @error('rt')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2">
                    <label class="form-label">RW</label>
                    <input type="text" class="form-control @error('rw') is-invalid @enderror" name="rw" id="rwInput" value="{{ old('rw', $pilahSampah->rw) }}" maxlength="3">
                    @error('rw')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Dasawisma</label>
                    <input type="text" class="form-control @error('dasawisma') is-invalid @enderror" name="dasawisma" value="{{ old('dasawisma', $pilahSampah->dasawisma) }}">
                    @error('dasawisma')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Sedekah <span class="text-danger">*</span></label>
                    <select class="form-select @error('sedekah') is-invalid @enderror" name="sedekah" required>
                        <option value="">-- Pilih --</option>
                        <option value="0" {{ (string) old('sedekah', (int) $pilahSampah->sedekah) === '0' ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ (string) old('sedekah', (int) $pilahSampah->sedekah) === '1' ? 'selected' : '' }}>Ya</option>
                    </select>
                    @error('sedekah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Berat (gram) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('berat') is-invalid @enderror" name="berat" value="{{ old('berat', $pilahSampah->berat) }}" min="1" step="0.01" required>
                    @error('berat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('harga') is-invalid @enderror" name="harga" value="{{ old('harga', $pilahSampah->harga) }}" min="0" required>
                    @error('harga')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Foto</label>
                    <input type="file" class="d-none" name="foto" accept="image/*" id="fotoInput">
                    <div class="input-group">
                        <button type="button" class="btn btn-outline-secondary" id="fotoPickBtn">Choose File</button>
                        <input type="text" class="form-control @error('foto') is-invalid @enderror" id="fotoName" placeholder="Choose File" readonly>
                    </div>
                    @error('foto')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Ukuran foto maksimal 2MB dengan format (.jpg, .jpeg, .png)</div>
                    <div class="mt-2">
                        @if($pilahSampah->foto_url)
                            <img id="fotoPreview" src="{{ $pilahSampah->foto_url }}" alt="Current Foto" class="img-thumbnail" style="max-width: 150px;">
                            <div class="mt-2">
                                <a href="{{ $pilahSampah->foto_url }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="bi bi-image me-1"></i> Lihat Foto
                                </a>
                            </div>
                        @else
                            <img id="fotoPreview" src="#" alt="Preview" class="img-thumbnail d-none" style="max-width: 150px;">
                        @endif
                    </div>
                </div>

            </div>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-check-lg me-2"></i> Update
                </button>
                <a href="{{ route('pilah-sampah.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('fotoPickBtn').addEventListener('click', function() {
        document.getElementById('fotoInput').click();
    });

    document.getElementById('fotoInput').addEventListener('change', function(e) {
        const preview = document.getElementById('fotoPreview');
        const nameInput = document.getElementById('fotoName');
        nameInput.value = this.files && this.files[0] ? this.files[0].name : '';
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endpush
@endsection
