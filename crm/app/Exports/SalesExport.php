<?php

namespace App\Exports;

use App\Models\Deal;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromQuery, WithHeadings, WithMapping
{
    private ?int $userId;

    private ?string $role;

    private ?string $startDate;

    private ?string $endDate;

    public function __construct(?int $userId = null, ?string $role = null, ?string $startDate = null, ?string $endDate = null)
    {
        $this->userId = $userId;
        $this->role = $role;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Streaming per-chunk agar tidak memuat seluruh data ke memori.
     */
    public function query()
    {
        $query = Deal::query()
            ->leftJoin('users as sales', 'sales.id', '=', 'deals.user_id')
            ->leftJoin('leads', 'leads.id', '=', 'deals.lead_id')
            ->select('deals.*', 'sales.name as sales_name', 'leads.company_name as lead_company')
            ->where('deals.stage', 'closed_won');

        // Filter periode (deals.created_at)
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('deals.created_at', [
                $this->startDate.' 00:00:00',
                $this->endDate.' 23:59:59',
            ]);
        }

        if ($this->role !== 'admin') {
            $query->where('deals.user_id', $this->userId);
        }

        return $query;
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
            $deal->lead_company ?? '-',
            $deal->deal_value,
            $deal->sales_name ?? '-',
            $deal->updated_at->format('Y-m-d H:i'),
        ];
    }
}
