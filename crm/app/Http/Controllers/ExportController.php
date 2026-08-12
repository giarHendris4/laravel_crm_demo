<?php

namespace App\Http\Controllers;

use App\Exports\LeadsExport;
use App\Exports\SalesExport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Excel as ExcelType;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * Halaman Laporan & Export (dapat diakses semua role).
     */
    public function index()
    {
        return view('reports.index');
    }

    public function exportLeads(Request $request)
    {
        $user = auth()->user();
        $range = $this->resolvePeriod($request);
        $format = $request->input('format', 'xlsx') === 'csv' ? 'csv' : 'xlsx';

        $export = new LeadsExport($user->id, $user->role, $range['start'], $range['end']);

        $file = 'daftar-leads-'.date('Y-m-d').'.'.$format;
        $writerType = $format === 'csv' ? ExcelType::CSV : null;

        Excel::queue($export, $file, null, $writerType);

        return redirect()->back()->with('success', 'Export data leads sedang diproses di latar belakang.');
    }

    public function exportSales(Request $request)
    {
        $user = auth()->user();
        $range = $this->resolvePeriod($request);
        $format = $request->input('format', 'xlsx') === 'csv' ? 'csv' : 'xlsx';

        $export = new SalesExport($user->id, $user->role, $range['start'], $range['end']);

        $file = 'laporan-penjualan-'.date('Y-m-d').'.'.$format;
        $writerType = $format === 'csv' ? ExcelType::CSV : null;

        Excel::queue($export, $file, null, $writerType);

        return redirect()->back()->with('success', 'Export laporan penjualan sedang diproses di latar belakang.');
    }

    /**
     * Resolusi periode: harian, mingguan, atau custom (start-end).
     */
    private function resolvePeriod(Request $request): array
    {
        $period = $request->input('period', 'daily');
        $today = Carbon::today();

        return match ($period) {
            'weekly' => [
                'start' => $today->copy()->subDays(6)->format('Y-m-d'),
                'end' => $today->format('Y-m-d'),
            ],
            'custom' => [
                'start' => $request->input('start', $today->format('Y-m-d')),
                'end' => $request->input('end', $today->format('Y-m-d')),
            ],
            default => [
                'start' => $today->format('Y-m-d'),
                'end' => $today->format('Y-m-d'),
            ],
        };
    }
}
