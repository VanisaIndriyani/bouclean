@extends('layouts.app')

@section('title', 'Pesan Masuk - Bouclear')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-0">Pesan Masuk</h4>
        <p class="mb-0 opacity-75">Kelola pesan dari halaman Hubungi Kami</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
            <div class="btn-group" role="group">
                <a href="{{ route('contact-messages.index', ['status' => 'unread', 'search' => request('search')]) }}" class="btn btn-sm {{ $status === 'unread' ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-3">Belum Dibaca</a>
                <a href="{{ route('contact-messages.index', ['status' => 'all', 'search' => request('search')]) }}" class="btn btn-sm {{ $status !== 'unread' ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-3">Semua</a>
            </div>
        </div>

        <form method="GET" action="{{ route('contact-messages.index') }}" class="mb-4">
            <input type="hidden" name="status" value="{{ $status }}">
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control" name="search" placeholder="Cari nama atau isi pesan..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary rounded-pill w-100">
                        <i class="bi bi-search me-2"></i> Cari
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('contact-messages.index', ['status' => $status]) }}" class="btn btn-outline-secondary rounded-pill w-100">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-2">
            <div class="text-muted small">
                Menampilkan {{ $messages->firstItem() ?? 0 }} - {{ $messages->lastItem() ?? 0 }} dari {{ $messages->total() }} pesan
            </div>
        </div>

        <div class="table-responsive overflow-auto">
            <table class="table table-hover align-middle text-nowrap" style="min-width: 900px;">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Nama</th>
                        <th>Pesan</th>
                        <th>Waktu</th>
                        <th>Status</th>
                        <th width="150" class="text-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $i => $m)
                        <tr>
                            <td class="text-center">{{ $messages->firstItem() + $i }}</td>
                            <td class="fw-semibold">{{ $m->nama_lengkap }}</td>
                            <td style="max-width: 520px;">
                                <div class="text-wrap" style="white-space: normal;">{{ $m->pesan }}</div>
                            </td>
                            <td class="text-muted small">{{ $m->created_at?->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($m->is_read)
                                    <span class="badge bg-secondary">Dibaca</span>
                                @else
                                    <span class="badge bg-primary">Belum</span>
                                @endif
                            </td>
                            <td class="text-nowrap">
                                @if(! $m->is_read)
                                    <form action="{{ route('contact-messages.read', $m) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill">Tandai Dibaca</button>
                                    </form>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    <strong>Belum ada pesan</strong>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $messages->links() }}
        </div>
    </div>
</div>
@endsection
