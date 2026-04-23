@extends('layouts.app')

@section('title', 'Tambah Warga - Bouclear')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-0">Tambah Data Warga</h4>
        <p class="mb-0 opacity-75">Tambah data warga baru</p>
    </div>
    <a href="{{ route('warga.index') }}" class="btn btn-outline-secondary rounded-pill">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('warga.store') }}" method="POST">
            @csrf

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
                    @error('nama_lengkap')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">NIK <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nik') is-invalid @enderror" name="nik" value="{{ old('nik') }}" maxlength="16" required>
                    @error('nik')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select class="form-select @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin" required>
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required>
                    @error('tempat_lahir')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                    @error('tanggal_lahir')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <hr>
                    <h6 class="mb-3"><i class="bi bi-map me-2"></i>Alamat / Wilayah</h6>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('kecamatan') is-invalid @enderror" name="kecamatan" value="{{ old('kecamatan') }}" required>
                    @error('kecamatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Kelurahan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('kelurahan') is-invalid @enderror" name="kelurahan" value="{{ old('kelurahan') }}" required>
                    @error('kelurahan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2">
                    <label class="form-label">RT <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('rt') is-invalid @enderror" name="rt" value="{{ old('rt') }}" maxlength="3" required>
                    @error('rt')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2">
                    <label class="form-label">RW <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('rw') is-invalid @enderror" name="rw" value="{{ old('rw') }}" maxlength="3" required>
                    @error('rw')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Dasawisma <span class="text-danger">*</span></label>
                    <select class="form-select @error('dasawisma') is-invalid @enderror" name="dasawisma" required>
                        <option value="">-- Pilih Dasawisma --</option>
                        @foreach(['Dahlia 1','Dahlia 2','Dahlia 3','Dahlia 4','Bougenville 1','Bougenville 2','Bougenville 3'] as $dasa)
                            <option value="{{ $dasa }}" {{ old('dasawisma') == $dasa ? 'selected' : '' }}>{{ $dasa }}</option>
                        @endforeach
                    </select>
                    @error('dasawisma')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <hr>
                    <h6 class="mb-3"><i class="bi bi-person-badge me-2"></i>Data Keluarga & Kependudukan</h6>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status dalam Keluarga</label>
                    <input type="text" class="form-control @error('status_dalam_keluarga') is-invalid @enderror" name="status_dalam_keluarga" value="{{ old('status_dalam_keluarga') }}" placeholder="Contoh: Kepala Keluarga">
                    @error('status_dalam_keluarga')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">No Kartu Keluarga</label>
                    <input type="text" class="form-control @error('no_kk') is-invalid @enderror" name="no_kk" value="{{ old('no_kk') }}" placeholder="Nomor KK">
                    @error('no_kk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">No Register PKK</label>
                    <input type="text" class="form-control @error('no_register_pkk') is-invalid @enderror" name="no_register_pkk" value="{{ old('no_register_pkk') }}" placeholder="No Register PKK">
                    @error('no_register_pkk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Agama</label>
                    <select class="form-select @error('agama') is-invalid @enderror" name="agama">
                        <option value="">-- Pilih --</option>
                        @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu','Lainnya'] as $agama)
                            <option value="{{ $agama }}" {{ old('agama') == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                        @endforeach
                    </select>
                    @error('agama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status Perkawinan</label>
                    <select class="form-select @error('status_perkawinan') is-invalid @enderror" name="status_perkawinan">
                        <option value="">-- Pilih --</option>
                        @foreach(['Belum Kawin','Kawin','Cerai Hidup','Cerai Mati'] as $sp)
                            <option value="{{ $sp }}" {{ old('status_perkawinan') == $sp ? 'selected' : '' }}>{{ $sp }}</option>
                        @endforeach
                    </select>
                    @error('status_perkawinan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status Tinggal</label>
                    <select class="form-select @error('status_tinggal') is-invalid @enderror" name="status_tinggal">
                        <option value="">-- Pilih --</option>
                        @foreach(['Tinggal Tetap','Kontrak','Menumpang','Lainnya'] as $st)
                            <option value="{{ $st }}" {{ old('status_tinggal') == $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                    @error('status_tinggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Alamat</label>
                    <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="3" placeholder="Alamat lengkap">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Pendidikan</label>
                    <input type="text" class="form-control @error('pendidikan') is-invalid @enderror" name="pendidikan" value="{{ old('pendidikan') }}" placeholder="Contoh: SMA">
                    @error('pendidikan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Pekerjaan</label>
                    <input type="text" class="form-control @error('pekerjaan') is-invalid @enderror" name="pekerjaan" value="{{ old('pekerjaan') }}" placeholder="Contoh: Ibu Rumah Tangga">
                    @error('pekerjaan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Merantau ke</label>
                    <input type="text" class="form-control @error('merantau_ke') is-invalid @enderror" name="merantau_ke" value="{{ old('merantau_ke') }}" placeholder="Opsional">
                    @error('merantau_ke')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Perantau dari</label>
                    <input type="text" class="form-control @error('perantau_dari') is-invalid @enderror" name="perantau_dari" value="{{ old('perantau_dari') }}" placeholder="Opsional">
                    @error('perantau_dari')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <hr>
                    <h6 class="mb-3"><i class="bi bi-clipboard-heart me-2"></i>Program & Kegiatan</h6>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Akseptor KB</label>
                    <select class="form-select @error('akseptor_kb') is-invalid @enderror" name="akseptor_kb">
                        <option value="0" {{ old('akseptor_kb', '0') == '0' ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ old('akseptor_kb') == '1' ? 'selected' : '' }}>Ya</option>
                    </select>
                    @error('akseptor_kb')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Aktif Posyandu</label>
                    <select class="form-select @error('aktif_posyandu') is-invalid @enderror" name="aktif_posyandu">
                        <option value="0" {{ old('aktif_posyandu', '0') == '0' ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ old('aktif_posyandu') == '1' ? 'selected' : '' }}>Ya</option>
                    </select>
                    @error('aktif_posyandu')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Bina Keluarga Balita</label>
                    <select class="form-select @error('bina_keluarga_balita') is-invalid @enderror" name="bina_keluarga_balita">
                        <option value="0" {{ old('bina_keluarga_balita', '0') == '0' ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ old('bina_keluarga_balita') == '1' ? 'selected' : '' }}>Ya</option>
                    </select>
                    @error('bina_keluarga_balita')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Memiliki Tabungan</label>
                    <select class="form-select @error('memiliki_tabungan') is-invalid @enderror" name="memiliki_tabungan">
                        <option value="0" {{ old('memiliki_tabungan', '0') == '0' ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ old('memiliki_tabungan') == '1' ? 'selected' : '' }}>Ya</option>
                    </select>
                    @error('memiliki_tabungan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Mengikuti Kelompok Belajar</label>
                    <select class="form-select @error('mengikuti_kelompok_belajar') is-invalid @enderror" name="mengikuti_kelompok_belajar">
                        <option value="0" {{ old('mengikuti_kelompok_belajar', '0') == '0' ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ old('mengikuti_kelompok_belajar') == '1' ? 'selected' : '' }}>Ya</option>
                    </select>
                    @error('mengikuti_kelompok_belajar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Jenis Kelompok Belajar</label>
                    <input type="text" class="form-control @error('jenis_kelompok_belajar') is-invalid @enderror" name="jenis_kelompok_belajar" value="{{ old('jenis_kelompok_belajar') }}" placeholder="Opsional">
                    @error('jenis_kelompok_belajar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Ikut Kegiatan Operasional</label>
                    <select class="form-select @error('ikut_kegiatan_operasional') is-invalid @enderror" name="ikut_kegiatan_operasional">
                        <option value="0" {{ old('ikut_kegiatan_operasional', '0') == '0' ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ old('ikut_kegiatan_operasional') == '1' ? 'selected' : '' }}>Ya</option>
                    </select>
                    @error('ikut_kegiatan_operasional')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Jenis Operasi</label>
                    <input type="text" class="form-control @error('jenis_operasi') is-invalid @enderror" name="jenis_operasi" value="{{ old('jenis_operasi') }}" placeholder="Opsional">
                    @error('jenis_operasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Mengikuti PAUD/Sejenis</label>
                    <select class="form-select @error('mengikuti_paud') is-invalid @enderror" name="mengikuti_paud">
                        <option value="0" {{ old('mengikuti_paud', '0') == '0' ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ old('mengikuti_paud') == '1' ? 'selected' : '' }}>Ya</option>
                    </select>
                    @error('mengikuti_paud')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Berkebutuhan Khusus</label>
                    <select class="form-select @error('berkebutuhan_khusus') is-invalid @enderror" name="berkebutuhan_khusus">
                        <option value="0" {{ old('berkebutuhan_khusus', '0') == '0' ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ old('berkebutuhan_khusus') == '1' ? 'selected' : '' }}>Ya</option>
                    </select>
                    @error('berkebutuhan_khusus')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <hr>
                    <h6 class="mb-3"><i class="bi bi-activity me-2"></i>Status (Opsional)</h6>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Buta</label>
                    <select class="form-select @error('buta') is-invalid @enderror" name="buta">
                        <option value="0" {{ old('buta', '0') == '0' ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ old('buta') == '1' ? 'selected' : '' }}>Ya</option>
                    </select>
                    @error('buta')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Hamil</label>
                    <select class="form-select @error('hamil') is-invalid @enderror" name="hamil">
                        <option value="0" {{ old('hamil', '0') == '0' ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ old('hamil') == '1' ? 'selected' : '' }}>Ya</option>
                    </select>
                    @error('hamil')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Menyusui</label>
                    <select class="form-select @error('menyusui') is-invalid @enderror" name="menyusui">
                        <option value="0" {{ old('menyusui', '0') == '0' ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ old('menyusui') == '1' ? 'selected' : '' }}>Ya</option>
                    </select>
                    @error('menyusui')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <input type="text" class="form-control @error('status') is-invalid @enderror" name="status" value="{{ old('status') }}" placeholder="Opsional">
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-check-lg me-2"></i> Simpan
                </button>
                <a href="{{ route('warga.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
