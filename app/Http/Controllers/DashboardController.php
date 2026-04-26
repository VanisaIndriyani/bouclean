<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\IuranSampah;
use App\Models\Perpindahan;
use App\Models\PilahSampah;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $contactUnreadCount = 0;

        if (Auth::user()->role === 'admin') {
            $contactUnreadCount = ContactMessage::query()->where('is_read', false)->count();
        }

        return view('dashboard', array_merge($stats, [
            'years' => $years,
            'selectedYear' => $selectedYear,
            'contactUnreadCount' => $contactUnreadCount,
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

        $filename = 'statistik-bulanan-'.$selectedYear.'.xls';

        $escape = static fn ($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

        return response()->streamDownload(function () use ($stats, $escape) {
            echo "\xEF\xBB\xBF";
            echo '<table border="1">';
            echo '<thead><tr>';
            echo '<th>'.$escape('Bulan').'</th>';
            echo '<th>'.$escape('Sampah (Kg)').'</th>';
            echo '<th>'.$escape('Iuran Lunas (Rp)').'</th>';
            echo '</tr></thead>';
            echo '<tbody>';

            foreach ($stats['labels'] as $idx => $label) {
                $sampah = $stats['sampahData'][$idx] ?? 0;
                $iuran = $stats['iuranData'][$idx] ?? 0;

                echo '<tr>';
                echo '<td>'.$escape($label).'</td>';
                echo '<td>'.$escape($sampah).'</td>';
                echo '<td>'.$escape($iuran).'</td>';
                echo '</tr>';
            }

            echo '</tbody></table>';
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }
}
