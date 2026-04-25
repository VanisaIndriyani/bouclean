@extends('layouts.app')

@section('title', 'Iuran Sampah - Bouclear')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-0">Iuran Sampah</h4>
        <p class="mb-0 opacity-75">Kelola iuran sampah warga</p>
    </div>
    <a href="{{ route('iuran-sampah.create') }}" class="btn btn-primary rounded-pill">
        <i class="bi bi-plus-lg me-2"></i> Tambah Iuran
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="GET" action="{{ route('iuran-sampah.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-2">
                    <select class="form-select" name="bulan">
                        <option value="all">-- Semua Bulan --</option>
                        @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $bulan)
                            <option value="{{ $bulan }}" {{ request('bulan') == $bulan ? 'selected' : '' }}>{{ $bulan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="tahun">
                        <option value="all">-- Semua Tahun --</option>
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="status">
                        <option value="all">-- Semua Status --</option>
                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Lunas</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control" name="search" placeholder="Cari nama/NIK/petugas..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary rounded-pill w-100">
                        <i class="bi bi-search me-2"></i> Cari
                    </button>
                </div>
            </div>
        </form>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-2">
            <div class="text-muted small">
                Menampilkan {{ $iuranSampahs->firstItem() ?? 0 }} - {{ $iuranSampahs->lastItem() ?? 0 }} dari {{ $iuranSampahs->total() }} data
            </div>
        </div>

        <div class="table-responsive overflow-auto">
            <table class="table table-hover align-middle text-nowrap" style="min-width: 950px;">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Warga</th>
                        <th>Periode</th>
                        <th>Nominal</th>
                        <th>Status</th>
                        <th>Tanggal Bayar</th>
                        <th>Petugas</th>
                        <th width="120" class="text-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($iuranSampahs as $index => $iuran)
                    <tr>
                        <td class="text-center">{{ $iuranSampahs->firstItem() + $index }}</td>
                        <td>
                            <strong>{{ $iuran->warga->nama_lengkap }}</strong><br>
                            <small class="text-muted">{{ $iuran->warga->nik_masked }}</small>
                        </td>
                        <td>{{ $iuran->bulan }} {{ $iuran->tahun }}</td>
                        <td>Rp {{ number_format($iuran->nominal, 0, ',', '.') }}</td>
                        <td>{!! $iuran->status_badge !!}</td>
                        <td>
                            @if($iuran->tanggal_bayar)
                                {{ $iuran->tanggal_bayar->format('d/m/Y') }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $iuran->petugas ?? '-' }}</td>
                        <td class="text-nowrap">
                            <div class="d-flex flex-nowrap gap-1">
                                <a href="{{ route('iuran-sampah.edit', $iuran) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if(Auth::user()->role === 'admin')
                                    <form action="{{ route('iuran-sampah.destroy', $iuran) }}" method="POST" class="d-inline">
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
                        <td colspan="8" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                <strong>Belum ada data iuran</strong>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $iuranSampahs->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
