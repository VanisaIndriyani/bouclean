@extends('layouts.app')

@section('title', 'Tambah Iuran - Bouclear')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-0">Tambah Iuran Sampah</h4>
        <p class="mb-0 opacity-75">Form input iuran sampah</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('iuran-sampah.store') }}" method="POST">
            @csrf

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">NIK Warga <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nik') is-invalid @enderror" id="iuranNikInput" name="nik" value="{{ old('nik') }}" list="wargaNikList" autocomplete="off" required placeholder="Ketik nama atau NIK, contoh: Anen (3374...)">
                    <datalist id="wargaNikList"></datalist>
                    @error('nik')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Bulan <span class="text-danger">*</span></label>
                    <select class="form-select @error('bulan') is-invalid @enderror" name="bulan" required>
                        <option value="">-- Pilih --</option>
                        @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $bulan)
                            <option value="{{ $bulan }}" {{ old('bulan') == $bulan ? 'selected' : '' }}>{{ $bulan }}</option>
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
                            <option value="{{ $y }}" {{ old('tahun', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    @error('tahun')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Nominal (Rp) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('nominal') is-invalid @enderror" name="nominal" value="{{ old('nominal', 10000) }}" min="0" required>
                    @error('nominal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" name="status">
                        <option value="belum" {{ old('status', 'belum') == 'belum' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="lunas" {{ old('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tanggal Bayar</label>
                    <input type="date" class="form-control @error('tanggal_bayar') is-invalid @enderror" name="tanggal_bayar" value="{{ old('tanggal_bayar') }}">
                    @error('tanggal_bayar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Petugas</label>
                    <input type="text" class="form-control @error('petugas') is-invalid @enderror" name="petugas" value="{{ old('petugas', Auth::user()->name) }}">
                    @error('petugas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-check-lg me-2"></i> Simpan
                </button>
                <a href="{{ route('iuran-sampah.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function() {
        const input = document.getElementById('iuranNikInput');
        const list = document.getElementById('wargaNikList');
        if (!input || !list) return;

        const endpoint = @json(route('warga.lookup'));
        let timer = null;

        function escapeHtml(value) {
            return String(value)
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        async function fetchOptions(q) {
            const res = await fetch(`${endpoint}?q=${encodeURIComponent(q)}`, {
                headers: { 'Accept': 'application/json' }
            });
            if (!res.ok) return [];
            const data = await res.json();
            return Array.isArray(data) ? data : [];
        }

        input.addEventListener('input', function() {
            const q = input.value.trim();
            if (timer) clearTimeout(timer);
            timer = setTimeout(async () => {
                if (q.length < 2) {
                    list.innerHTML = '';
                    return;
                }
                const options = await fetchOptions(q);
                list.innerHTML = options
                    .map(o => `<option value="${escapeHtml(o.value ?? '')}"></option>`)
                    .join('');
            }, 250);
        });
    })();
</script>
@endpush
