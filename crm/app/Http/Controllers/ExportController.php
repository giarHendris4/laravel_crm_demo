<?php

namespace App\Http\Controllers;

use App\Exports\LeadsExport;
use App\Exports\SalesExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportLeads()
    {
        return Excel::download(new LeadsExport, 'daftar-leads-' . date('Y-m-d') . '.xlsx');
    }

    public function exportSales()
    {
        return Excel::download(new SalesExport, 'laporan-penjualan-' . date('Y-m-d') . '.xlsx');
    }
}