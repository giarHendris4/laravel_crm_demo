<?php

namespace App\Exports;

use App\Models\Deal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        $user = auth()->user();

        // Ambil deal yang bernilai Won (Penjualan / Sales Berhasil)
        $query = Deal::where('stage', 'closed_won')->with(['user', 'lead']);

        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID Deal',
            'Judul Transaksi',
            'Perusahaan / Lead',
            'Nilai Transaksi (Sales)',
            'Sales Rep',
            'Tanggal Closed',
        ];
    }

    public function map($deal): array
    {
        return [
            $deal->id,
            $deal->title,
            $deal->lead->company_name ?? '-',
            $deal->deal_value,
            $deal->user->name ?? '-',
            $deal->updated_at->format('Y-m-d H:i'),
        ];
    }
}