@extends('layouts.app')

@section('title', 'Tambah Pilah Sampah - Bouclear')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-0">Tambah Data Pilah Sampah</h4>
        <p class="mb-0 opacity-75">Form input data pilah sampah</p>
    </div>
    <a href="{{ route('pilah-sampah.index') }}" class="btn btn-outline-secondary rounded-pill">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('pilah-sampah.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Warga <span class="text-danger">*</span></label>
                    <select class="form-select @error('warga_id') is-invalid @enderror" name="warga_id" id="wargaSelect" required>
                        <option value="">-- Pilih Warga --</option>
                        @foreach($wargas as $warga)
                            <option value="{{ $warga->id }}"
                                data-kecamatan="{{ $warga->kecamatan }}"
                                data-kelurahan="{{ $warga->kelurahan }}"
                                data-rt="{{ $warga->rt }}"
                                data-rw="{{ $warga->rw }}"
                                data-dasawisma="{{ $warga->dasawisma }}"
                                data-jk="{{ $warga->jenis_kelamin }}"
                                {{ old('warga_id') == $warga->id ? 'selected' : '' }}>
                                {{ $warga->nama_lengkap }} - {{ $warga->nik }}
                            </option>
                        @endforeach
                    </select>
                    @error('warga_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select class="form-select @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin" id="jkSelect" required>
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <hr>
                    <h6 class="mb-3"><i class="bi bi-map me-2"></i>Wilayah</h6>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Kecamatan</label>
                    <input type="text" class="form-control @error('kecamatan') is-invalid @enderror" name="kecamatan" id="kecamatanInput" value="{{ old('kecamatan') }}">
                    @error('kecamatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Kelurahan</label>
                    <input type="text" class="form-control @error('kelurahan') is-invalid @enderror" name="kelurahan" id="kelurahanInput" value="{{ old('kelurahan') }}">
                    @error('kelurahan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2">
                    <label class="form-label">RW</label>
                    <input type="text" class="form-control @error('rw') is-invalid @enderror" name="rw" id="rwInput" value="{{ old('rw') }}" maxlength="3">
                    @error('rw')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2">
                    <label class="form-label">RT</label>
                    <input type="text" class="form-control @error('rt') is-invalid @enderror" name="rt" id="rtInput" value="{{ old('rt') }}" maxlength="3">
                    @error('rt')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Dasawisma</label>
                    <select class="form-select @error('dasawisma') is-invalid @enderror" name="dasawisma" id="dasawismaSelect">
                        <option value="">-- Pilih Dasawisma --</option>
                        @foreach(['Dahlia 1','Dahlia 2','Dahlia 3','Dahlia 4','Bougenville 1','Bougenville 2','Bougenville 3'] as $dasa)
                            <option value="{{ $dasa }}" {{ old('dasawisma') == $dasa ? 'selected' : '' }}>{{ $dasa }}</option>
                        @endforeach
                    </select>
                    @error('dasawisma')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jenis Sampah</label>
                    <input type="text" class="form-control @error('jenis_sampah') is-invalid @enderror" name="jenis_sampah" value="{{ old('jenis_sampah') }}" placeholder="Contoh: Plastik, Kertas, Logam">
                    @error('jenis_sampah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Berat (gram) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('berat') is-invalid @enderror" name="berat" value="{{ old('berat') }}" min="1" step="0.01" required>
                    @error('berat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('harga') is-invalid @enderror" name="harga" value="{{ old('harga') }}" min="0" required>
                    @error('harga')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Foto</label>
                    <input type="file" class="form-control @error('foto') is-invalid @enderror" name="foto" accept="image/*" id="fotoInput">
                    @error('foto')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Ukuran foto maksimal 2MB dengan format (.jpg, .jpeg, .png)</div>
                    <div class="mt-2">
                        <img id="fotoPreview" src="#" alt="Preview" class="img-thumbnail d-none" style="max-width: 150px;">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="sedekah" id="sedekahCheck" value="1" {{ old('sedekah') ? 'checked' : '' }}>
                        <label class="form-check-label" for="sedekahCheck">
                            <i class="bi bi-gift text-success me-1"></i> Include sebagai Sedekah
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-check-lg me-2"></i> Simpan
                </button>
                <a href="{{ route('pilah-sampah.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const wargaSelect = document.getElementById('wargaSelect');
    const kecamatanInput = document.getElementById('kecamatanInput');
    const kelurahanInput = document.getElementById('kelurahanInput');
    const rtInput = document.getElementById('rtInput');
    const rwInput = document.getElementById('rwInput');
    const dasawismaSelect = document.getElementById('dasawismaSelect');
    const jkSelect = document.getElementById('jkSelect');

    function syncFromWarga() {
        const opt = wargaSelect.options[wargaSelect.selectedIndex];
        if (!opt || !opt.value) return;

        if (!kecamatanInput.value) kecamatanInput.value = opt.dataset.kecamatan || '';
        if (!kelurahanInput.value) kelurahanInput.value = opt.dataset.kelurahan || '';
        if (!rtInput.value) rtInput.value = opt.dataset.rt || '';
        if (!rwInput.value) rwInput.value = opt.dataset.rw || '';
        if (!dasawismaSelect.value) dasawismaSelect.value = opt.dataset.dasawisma || '';
        if (!jkSelect.value) jkSelect.value = opt.dataset.jk || '';
    }

    wargaSelect.addEventListener('change', syncFromWarga);
    window.addEventListener('load', syncFromWarga);

    document.getElementById('fotoInput').addEventListener('change', function(e) {
        const preview = document.getElementById('fotoPreview');
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
