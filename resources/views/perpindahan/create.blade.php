@extends('layouts.app')

@section('title', 'Ajukan Perpindahan - Bouclear')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-0">Ajukan Perpindahan Warga</h4>
        <p class="mb-0 opacity-75">Form pengajuan perpindahan warga</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('perpindahan.store') }}" method="POST">
            @csrf

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Warga <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('warga_nik') is-invalid @enderror" id="perpindahanNikInput" name="warga_nik" value="{{ old('warga_nik') }}" list="wargaNikList" autocomplete="off" required placeholder="Ketik nama atau NIK, contoh: Anen (3374...)">
                    <datalist id="wargaNikList"></datalist>
                    @error('warga_nik')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Diusulkan Oleh <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('diusulkan_oleh') is-invalid @enderror" name="diusulkan_oleh" value="{{ old('diusulkan_oleh') }}" required>
                    @error('diusulkan_oleh')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Alamat Asal <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('asal') is-invalid @enderror" name="asal" rows="3" required>{{ old('asal') }}</textarea>
                    @error('asal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Alamat Tujuan <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('tujuan') is-invalid @enderror" name="tujuan" rows="3" required>{{ old('tujuan') }}</textarea>
                    @error('tujuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-send me-2"></i> Ajukan
                </button>
                <a href="{{ route('perpindahan.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
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
        const input = document.getElementById('perpindahanNikInput');
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
