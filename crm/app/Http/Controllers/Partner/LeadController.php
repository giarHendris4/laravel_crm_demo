<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index()
    {
        // Strict Data Isolation: Partner HANYA bisa lihat lead yang di-assign ke ID mereka
        $leads = auth()->user()->assignedLeads()->with('user')->get();

        return view('partner.leads.index', compact('leads'));
    }

    public function updateStatus(Request $request, Lead $lead)
    {
        // Pengecekan keamanan: Pastikan lead ini memang ditugaskan ke partner yang sedang login
        abort_unless(
            auth()->user()->assignedLeads()->where('lead_id', $lead->id)->exists(),
            403,
            'Anda tidak memiliki akses ke lead ini.'
        );

        $validated = $request->validate([
            'status' => 'required|in:new,contacted,proposal,negotiation,won,lost',
        ]);

        $lead->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Status lead berhasil diperbarui.');
    }
}