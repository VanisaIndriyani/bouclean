@extends('layouts.app')

@section('title', 'Dashboard - Bouclean')

@push('styles')
<style>
    .ls-1 { letter-spacing: 1px; }
    .card-stat { transition: all 0.3s ease; }
    .card-stat:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    .icon-shape { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; }
    .quick-menu-item { transition: all 0.2s ease; border: 1px solid transparent; }
    .quick-menu-item:hover { background-color: #fff !important; border-color: rgba(13, 110, 253, 0.2); transform: scale(1.05); box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Selamat Datang, {{ Auth::user()->name }}!</h4>
            <p class="mb-0 opacity-75">Dashboard Bank Sampah Digital Bouclean</p>
        </div>
        <div>
            <span class="badge bg-light text-dark fs-6">
                <i class="bi bi-calendar3 me-1"></i> {{ now()->format('d F Y') }}
            </span>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="card card-stat border-0 shadow-sm h-100 overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="icon-shape bg-info bg-opacity-10 text-info rounded-3 p-3">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 small">
                        <i class="bi bi-check-circle-fill me-1"></i> Aktif
                    </span>
                </div>
                <h6 class="text-muted mb-1 text-uppercase ls-1 small fw-bold">Total Warga</h6>
                <h2 class="mb-3 fw-bold">{{ $jumlahWarga }}</h2>
                <a href="{{ route('warga.index') }}" class="btn btn-link p-0 text-decoration-none text-info small fw-semibold">
                    Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card card-stat border-0 shadow-sm h-100 overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="icon-shape bg-success bg-opacity-10 text-success rounded-3 p-3">
                        <i class="bi bi-trash3 fs-4"></i>
                    </div>
                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2 small">
                        <i class="bi bi-recycle me-1"></i> Terpilah
                    </span>
                </div>
                <h6 class="text-muted mb-1 text-uppercase ls-1 small fw-bold">Total Sampah</h6>
                <h2 class="mb-3 fw-bold">{{ number_format($totalSampah, 2) }} <small class="fs-6 text-muted fw-normal">kg</small></h2>
                <a href="{{ route('pilah-sampah.index') }}" class="btn btn-link p-0 text-decoration-none text-success small fw-semibold">
                    Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    @if(Auth::user()->role === 'admin')
    <div class="col-md-6 col-lg-3">
        <div class="card card-stat border-0 shadow-sm h-100 overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="icon-shape bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                        <i class="bi bi-wallet2 fs-4"></i>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 small">
                        <i class="bi bi-cash-stack me-1"></i> Lunas
                    </span>
                </div>
                <h6 class="text-muted mb-1 text-uppercase ls-1 small fw-bold">Iuran Terkumpul</h6>
                <h2 class="mb-3 fw-bold">Rp {{ number_format($totalIuran, 0, ',', '.') }}</h2>
                <a href="{{ route('iuran-sampah.index') }}" class="btn btn-link p-0 text-decoration-none text-warning small fw-semibold">
                    Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    @endif

    <div class="col-md-6 col-lg-3">
        <div class="card card-stat border-0 shadow-sm h-100 overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="icon-shape bg-danger bg-opacity-10 text-danger rounded-3 p-3">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 small">
                        <i class="bi bi-exclamation-circle-fill me-1"></i> Tertunda
                    </span>
                </div>
                <h6 class="text-muted mb-1 text-uppercase ls-1 small fw-bold">Iuran Belum Lunas</h6>
                <h2 class="mb-3 fw-bold">{{ $iuranBelumLunas }}</h2>
                <a href="{{ route('iuran-sampah.index', ['status' => 'belum']) }}" class="btn btn-link p-0 text-decoration-none text-danger small fw-semibold">
                    Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm overflow-hidden h-100">
            <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-graph-up me-2 text-primary"></i>Statistik Bulanan 2026</h6>
                <div class="dropdown">
                    <button class="btn btn-sm btn-light rounded-pill px-3" type="button" data-bs-toggle="dropdown">
                        Tahun 2026 <i class="bi bi-chevron-down ms-1"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-4">
                <div style="height: 350px;">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3 px-4">
                <h6 class="mb-0 fw-bold"><i class="bi bi-bell me-2 text-warning"></i>Notifikasi</h6>
            </div>
            <div class="card-body p-4">
                @if($perpindahanPending > 0)
                <div class="d-flex align-items-start p-3 bg-warning bg-opacity-10 rounded-3 mb-3 border-start border-4 border-warning">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-3 fs-4"></i>
                    <div>
                        <h6 class="mb-1 fw-bold">Verifikasi Perpindahan</h6>
                        <p class="small text-muted mb-2">Ada {{ $perpindahanPending }} pengajuan yang butuh verifikasi.</p>
                        <a href="{{ route('perpindahan.index') }}" class="btn btn-sm btn-warning rounded-pill px-3">Cek Sekarang</a>
                    </div>
                </div>
                @endif
                <div class="text-center py-5">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-check2-circle text-muted fs-1"></i>
                    </div>
                    <p class="text-muted small">Tidak ada notifikasi baru lainnya.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Sampah (Kg)',
                data: @json($sampahData),
                backgroundColor: 'rgba(13, 202, 240, 0.6)',
                borderColor: '#0dcaf0',
                borderWidth: 1,
                borderRadius: 5,
                yAxisID: 'y'
            }, {
                label: 'Iuran (Rp)',
                data: @json($iuranData),
                backgroundColor: 'rgba(25, 135, 84, 0.6)',
                borderColor: '#198754',
                borderWidth: 1,
                borderRadius: 5,
                type: 'line',
                tension: 0.4,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: { display: true, text: 'Berat Sampah (Kg)', font: { weight: 'bold' } },
                    grid: { drawOnChartArea: false }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: { display: true, text: 'Total Iuran (Rp)', font: { weight: 'bold' } },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: { size: 12, family: "'Plus Jakarta Sans', sans-serif" }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    titleColor: '#1a1d20',
                    bodyColor: '#1a1d20',
                    borderColor: 'rgba(0,0,0,0.1)',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) { label += ': '; }
                            if (context.parsed.y !== null) {
                                if (context.datasetIndex === 1) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                                } else {
                                    label += context.parsed.y + ' Kg';
                                }
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
</script>
@endpush

<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-white py-3 px-4 border-0">
                <h6 class="mb-0 fw-bold"><i class="bi bi-grid-3x3-gap text-primary me-2"></i>Menu Akses Cepat</h6>
            </div>
            <div class="card-body p-4 pt-0">
                <div class="row g-3">
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('warga.create') }}" class="text-decoration-none">
                            <div class="quick-menu-item p-3 bg-primary bg-opacity-10 rounded-4 text-center h-100">
                                <i class="bi bi-person-plus fs-2 text-primary"></i>
                                <p class="mb-0 mt-2 small fw-bold text-dark">Tambah Warga</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('perpindahan.create') }}" class="text-decoration-none">
                            <div class="quick-menu-item p-3 bg-warning bg-opacity-10 rounded-4 text-center h-100">
                                <i class="bi bi-arrow-left-right fs-2 text-warning"></i>
                                <p class="mb-0 mt-2 small fw-bold text-dark">Ajukan Pindah</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('pilah-sampah.create') }}" class="text-decoration-none">
                            <div class="quick-menu-item p-3 bg-success bg-opacity-10 rounded-4 text-center h-100">
                                <i class="bi bi-trash3 fs-2 text-success"></i>
                                <p class="mb-0 mt-2 small fw-bold text-dark">Input Sampah</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('iuran-sampah.create') }}" class="text-decoration-none">
                            <div class="quick-menu-item p-3 bg-info bg-opacity-10 rounded-4 text-center h-100">
                                <i class="bi bi-cash-stack fs-2 text-info"></i>
                                <p class="mb-0 mt-2 small fw-bold text-dark">Input Iuran</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('wilayah.index') }}" class="text-decoration-none">
                            <div class="quick-menu-item p-3 bg-secondary bg-opacity-10 rounded-4 text-center h-100">
                                <i class="bi bi-map fs-2 text-secondary"></i>
                                <p class="mb-0 mt-2 small fw-bold text-dark">Data Wilayah</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('perpindahan.index') }}" class="text-decoration-none">
                            <div class="quick-menu-item p-3 bg-danger bg-opacity-10 rounded-4 text-center h-100">
                                <i class="bi bi-clipboard-check fs-2 text-danger"></i>
                                <p class="mb-0 mt-2 small fw-bold text-dark">Verifikasi</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
