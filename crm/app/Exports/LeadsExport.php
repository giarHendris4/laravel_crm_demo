<?php

namespace App\Exports;

use App\Models\Lead;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LeadsExport implements FromQuery, ShouldQueue, WithHeadings, WithMapping
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
     * FromQuery agar data diekspor per-chunk (streaming), tidak memuat
     * seluruh baris ke memori. User di-pass dari constructor karena di
     * dalam job antrian guard auth() tidak tersedia.
     */
    public function query()
    {
        $base = Lead::query()
            ->leftJoin('users as sales', 'sales.id', '=', 'leads.user_id')
            ->select('leads.*', 'sales.name as sales_name');

        // Filter periode (created_at)
        if ($this->startDate && $this->endDate) {
            $base->whereBetween('leads.created_at', [
                $this->startDate.' 00:00:00',
                $this->endDate.' 23:59:59',
            ]);
        }

        // Otorisasi: Admin semua, Partner hanya lead yang di-assign, Sales hanya miliknya
        if ($this->role === 'admin') {
            return $base;
        }

        if ($this->role === 'partner') {
            return Lead::query()
                ->leftJoin('users as sales', 'sales.id', '=', 'leads.user_id')
                ->leftJoin('lead_assignments as la', 'la.lead_id', '=', 'leads.id')
                ->select('leads.*', 'sales.name as sales_name')
                ->when($this->startDate && $this->endDate, fn ($q) => $q->whereBetween('leads.created_at', [
                    $this->startDate.' 00:00:00',
                    $this->endDate.' 23:59:59',
                ]))
                ->where('la.partner_id', $this->userId);
        }

        return $base->where('leads.user_id', $this->userId);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Judul Lead',
            'Nama Perusahaan',
            'Kontak',
            'Email',
            'Telepon',
            'Nilai Peluang',
            'Status',
            'Sales Rep',
            'Tanggal Dibuat',
        ];
    }

    public function map($lead): array
    {
        return [
            $lead->id,
            $lead->title,
            $lead->company_name,
            $lead->contact_name,
            $lead->email,
            $lead->phone,
            $lead->opportunity_value,
            $lead->status,
            $lead->sales_name ?? '-',
            $lead->created_at->format('Y-m-d H:i'),
        ];
    }
}
