<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use App\Models\PilahSampah;
use App\Models\IuranSampah;
use Illuminate\Http\Request;
use IlluminateSupport\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $jumlahWarga = Warga::count();
        $totalSampah = PilahSampah::sum('berat') / 1000; // Konversi ke Kg
        $totalIuran = IuranSampah::where('status', 'lunas')->sum('nominal');
        $iuranBelumLunas = IuranSampah::where('status', 'belum')->count();
        $perpindahanPending = \App\Models\Perpindahan::where('status', 'pending')->count();

        // Data untuk Grafik Bulanan (Januari - Desember)
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        
        $sampahData = [];
        $iuranData = [];

        for ($i = 1; $i <= 12; $i++) {
            $sampahData[] = PilahSampah::whereMonth('created_at', $i)
                            ->whereYear('created_at', 2026)
                            ->sum('berat') / 1000;

            $iuranData[] = IuranSampah::whereMonth('created_at', $i)
                            ->whereYear('created_at', 2026)
                            ->where('status', 'lunas')
                            ->sum('nominal');
        }

        return view('dashboard', compact(
            'jumlahWarga', 
            'totalSampah', 
            'totalIuran', 
            'iuranBelumLunas',
            'perpindahanPending',
            'labels',
            'sampahData',
            'iuranData'
        ));
    }
}
