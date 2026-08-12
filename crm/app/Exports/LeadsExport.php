<?php

namespace App\Exports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LeadsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        $user = auth()->user();

        // Otorisasi: Admin melihat semua, Partner hanya lead yang ter-assign, Sales hanya lead miliknya
        if ($user->role === 'admin') {
            return Lead::with('user')->get();
        } elseif ($user->role === 'partner') {
            return $user->assignedLeads()->with('user')->get();
        }

        return Lead::where('user_id', $user->id)->get();
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
            $lead->user->name ?? '-',
            $lead->created_at->format('Y-m-d H:i'),
        ];
    }
}