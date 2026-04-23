<?php

namespace App\Http\Controllers;

use App\Models\IuranSampah;
use App\Models\Perpindahan;
use App\Models\PilahSampah;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    private function yearExpr(string $driver): string
    {
        return $driver === 'sqlite' ? "strftime('%Y', created_at)" : 'YEAR(created_at)';
    }

    private function availableYears(): array
    {
        $driver = DB::connection()->getDriverName();
        $expr = $this->yearExpr($driver);

        $pilahYears = PilahSampah::query()
            ->selectRaw("$expr as y")
            ->whereNotNull('created_at')
            ->distinct()
            ->pluck('y')
            ->map(fn ($y) => (int) $y)
            ->all();

        $iuranYears = IuranSampah::query()
            ->selectRaw("$expr as y")
            ->whereNotNull('created_at')
            ->distinct()
            ->pluck('y')
            ->map(fn ($y) => (int) $y)
            ->all();

        $years = collect(array_merge($pilahYears, $iuranYears))
            ->filter()
            ->unique()
            ->sortDesc()
            ->values()
            ->all();

        return $years ?: [(int) now()->format('Y')];
    }

    private function monthlyStatsForYear(int $year): array
    {
        $jumlahWarga = Warga::count();
        $totalSampah = PilahSampah::sum('berat') / 1000; // Konversi ke Kg
        $totalIuran = IuranSampah::where('status', 'lunas')->sum('nominal');
        $iuranBelumLunas = IuranSampah::where('status', 'belum')->count();
        $perpindahanPending = Perpindahan::where('status', 'pending')->count();

        // Data untuk Grafik Bulanan (Januari - Desember)
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

        $sampahData = [];
        $iuranData = [];

        for ($i = 1; $i <= 12; $i++) {
            $sampahData[] = PilahSampah::whereMonth('created_at', $i)
                ->whereYear('created_at', $year)
                ->sum('berat') / 1000;

            $iuranData[] = IuranSampah::whereMonth('created_at', $i)
                ->whereYear('created_at', $year)
                ->where('status', 'lunas')
                ->sum('nominal');
        }

        return compact(
            'jumlahWarga',
            'totalSampah',
            'totalIuran',
            'iuranBelumLunas',
            'perpindahanPending',
            'labels',
            'sampahData',
            'iuranData'
        );
    }

    public function index(Request $request)
    {
        $years = $this->availableYears();
        $selectedYear = (int) $request->query('year', $years[0]);
        if (! in_array($selectedYear, $years, true)) {
            $selectedYear = $years[0];
        }

        $stats = $this->monthlyStatsForYear($selectedYear);

        return view('dashboard', array_merge($stats, [
            'years' => $years,
            'selectedYear' => $selectedYear,
        ]));
    }

    public function exportMonthlyExcel(Request $request)
    {
        $years = $this->availableYears();
        $selectedYear = (int) $request->query('year', $years[0]);
        if (! in_array($selectedYear, $years, true)) {
            $selectedYear = $years[0];
        }

        $stats = $this->monthlyStatsForYear($selectedYear);

        $rows = [];
        foreach ($stats['labels'] as $idx => $label) {
            $rows[] = [
                $label,
                $stats['sampahData'][$idx] ?? 0,
                $stats['iuranData'][$idx] ?? 0,
            ];
        }

        $export = new class($rows) implements FromArray, WithHeadings
        {
            public function __construct(private readonly array $rows) {}

            public function array(): array
            {
                return $this->rows;
            }

            public function headings(): array
            {
                return ['Bulan', 'Sampah (Kg)', 'Iuran Lunas (Rp)'];
            }
        };

        $filename = 'statistik-bulanan-'.$selectedYear.'.xlsx';

        return Excel::download($export, $filename);
    }
}
