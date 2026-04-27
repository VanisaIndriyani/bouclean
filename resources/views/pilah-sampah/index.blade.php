@extends('layouts.app')

@section('title', 'Pilah Sampah - Bouclear')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-0">Pilah Sampah</h4>
        <p class="mb-0 opacity-75">Kelola data pilah sampah warga</p>
    </div>
    <a href="{{ route('pilah-sampah.create') }}" class="btn btn-primary rounded-pill">
        <i class="bi bi-plus-lg me-2"></i> Tambah
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        @php
            $bulanMap = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember',
            ];
        @endphp
        <form method="GET" action="{{ route('pilah-sampah.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control" name="search" placeholder="Cari nama, NIK, atau wilayah..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-secondary rounded-pill w-100">
                        <i class="bi bi-search me-2"></i> Cari
                    </button>
                </div>
            </div>
        </form>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-2">
            <div class="text-muted small">
                Menampilkan {{ $pilahSampahs->firstItem() ?? 0 }} - {{ $pilahSampahs->lastItem() ?? 0 }} dari {{ $pilahSampahs->total() }} data
            </div>
        </div>

        <div class="table-responsive overflow-auto">
            <table class="table table-hover align-middle text-nowrap" style="min-width: 1100px;">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Bulan</th>
                        <th>Tahun</th>
                        <th>Kepala Keluarga</th>
                        <th>Jenis Sampah</th>
                        <th class="text-center">
                            <div class="fw-semibold">BERAT</div>
                            <div class="text-muted small">(SATUAN GR)</div>
                        </th>
                        <th>Sedekah</th>
                        <th>Harga</th>
                        <th>Foto</th>
                        <th width="120" class="text-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pilahSampahs as $index => $pilah)
                    <tr>
                        <td class="text-center">{{ $pilahSampahs->firstItem() + $index }}</td>
                        @php
                            $bulanNo = $pilah->bulan ?? $pilah->created_at?->month;
                            $tahunVal = $pilah->tahun ?? $pilah->created_at?->year;
                        @endphp
                        <td>{{ $bulanMap[$bulanNo] ?? '-' }}</td>
                        <td>{{ $tahunVal ?? '-' }}</td>
                        <td>
                            @php
                                $warga = $pilah->warga;
                                $displayNama = null;
                                $displayNikRaw = null;

                                if ($warga) {
                                    $displayNama = $warga->nama_lengkap;
                                    $displayNikRaw = $warga->getRawOriginal('nik');
                                } else {
                                    $combined = trim((string) ($pilah->kepala_keluarga_nik ?? ''));
                                    if ($combined !== '') {
                                        if (preg_match('/^(.*?)\s*\((.*?)\)\s*$/', $combined, $m)) {
                                            $displayNama = trim($m[1]) !== '' ? trim($m[1]) : null;
                                            $displayNikRaw = trim($m[2]) !== '' ? trim($m[2]) : null;
                                        } else {
                                            $displayNama = $combined;
                                            $displayNikRaw = $combined;
                                        }
                                    }
                                }

                                if ($displayNama !== null && preg_match('/^\d+$/', $displayNama)) {
                                    $displayNama = null;
                                }

                                $digits = preg_replace('/\D+/', '', (string) ($displayNikRaw ?? '')) ?? '';
                                if ($digits === '' && $displayNikRaw !== null) {
                                    $digits = '';
                                }

                                $nikMasked = '-';
                                if ($digits !== '') {
                                    $len = strlen($digits);
                                    if ($len <= 4) {
                                        $nikMasked = $digits;
                                    } else {
                                        $first4 = substr($digits, 0, 4);
                                        $offset = 4;

                                        $parts = [$first4];

                                        if ($len > $offset) {
                                            $take = min(2, $len - $offset);
                                            $parts[] = substr($digits, $offset, $take);
                                            $offset += $take;
                                        }

                                        if ($len > $offset) {
                                            $take = min(3, $len - $offset);
                                            $parts[] = substr($digits, $offset, $take);
                                            $offset += $take;
                                        }

                                        if ($len > $offset + 4) {
                                            $parts[] = substr($digits, $offset, $len - $offset - 4);
                                        }

                                        if ($len >= 8) {
                                            $parts[] = substr($digits, -4);
                                        }

                                        $nikMasked = implode('*', array_values(array_filter($parts, fn ($p) => $p !== '')));
                                    }
                                } elseif ($displayNikRaw !== null && trim((string) $displayNikRaw) !== '') {
                                    $nikMasked = trim((string) $displayNikRaw);
                                }
                            @endphp
                            <div class="fw-semibold">{{ $displayNama ?? '-' }}</div>
                            <span class="badge bg-light text-dark">{{ $nikMasked }}</span>
                        </td>
                        <td>{{ $pilah->jenis_sampah ?? '-' }}</td>
                        <td class="text-center">{{ number_format($pilah->berat, 0, ',', '.') }}</td>
                        <td>
                            @if($pilah->sedekah)
                                <span class="badge bg-success"><i class="bi bi-check-lg"></i> Ya</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-x-lg"></i> Tidak</span>
                            @endif
                        </td>
                        <td>Rp {{ number_format($pilah->harga, 0, ',', '.') }}</td>
                        <td>
                            @if($pilah->foto_url)
                                <a href="{{ $pilah->foto_url }}" target="_blank">
                                    <img src="{{ $pilah->foto_url }}" alt="Foto" class="img-thumbnail" style="width: 64px; height: 64px; object-fit: cover;">
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-nowrap">
                            <div class="d-flex flex-nowrap gap-1">
                                <a href="{{ route('pilah-sampah.edit', $pilah) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if(Auth::user()->role === 'admin')
                                    <form action="{{ route('pilah-sampah.destroy', $pilah) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Yakin hapus?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                <strong>Belum ada data pilah sampah</strong>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $pilahSampahs->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
