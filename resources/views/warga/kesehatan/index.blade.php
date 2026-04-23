@extends('layouts.app')

@section('title', 'Kesehatan Warga - Bouclear')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-0">Kesehatan Warga</h4>
        <p class="mb-0 opacity-75">{{ $warga->nama_lengkap }} ({{ $warga->nik }})</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('warga.index') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="bi bi-arrow-left me-2"></i> Kembali
        </a>
        <a href="{{ route('warga.kesehatan.create', $warga) }}" class="btn btn-primary rounded-pill">
            <i class="bi bi-plus-lg me-2"></i> Tambah
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Kek</th>
                        <th>Anemia</th>
                        <th>Haid &gt; 7 Hari</th>
                        <th>Belum Imunisasi</th>
                        <th>TBC Mangkir</th>
                        <th>Remaja Rokok</th>
                        <th>Ada Jentik</th>
                        <th>Tanggal Laporan</th>
                        <th width="140">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kesehatans as $index => $k)
                    <tr>
                        <td class="text-center">{{ $kesehatans->firstItem() + $index }}</td>
                        <td>{{ $k->kek ? 'Ya' : 'Tidak' }}</td>
                        <td>{{ $k->anemia ? 'Ya' : 'Tidak' }}</td>
                        <td>{{ $k->haid_lebih_7_hari ? 'Ya' : 'Tidak' }}</td>
                        <td>{{ $k->belum_imunisasi ? 'Ya' : 'Tidak' }}</td>
                        <td>{{ $k->tbc_mangkir ? 'Ya' : 'Tidak' }}</td>
                        <td>{{ $k->remaja_rokok ? 'Ya' : 'Tidak' }}</td>
                        <td>{{ $k->ada_jentik ? 'Ya' : 'Tidak' }}</td>
                        <td>{{ $k->tanggal_laporan ? $k->tanggal_laporan->format('d/m/Y') : '-' }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('warga.kesehatan.edit', [$warga, $k]) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if(Auth::user()->role === 'admin')
                                <form action="{{ route('warga.kesehatan.destroy', [$warga, $k]) }}" method="POST" class="d-inline">
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
                                <strong>Belum ada data kesehatan</strong>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $kesehatans->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection

