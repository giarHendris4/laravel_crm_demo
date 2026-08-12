<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class LeadAssignmentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'partner_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $lead = Lead::findOrFail($validated['lead_id']);

        // Assign/Attach Partner ke Lead (Many to Many)
        $lead->partners()->syncWithoutDetaching([
            $validated['partner_id'] => [
                'assigned_by' => auth()->id(),
                'notes' => $validated['notes'] ?? null,
            ],
        ]);

        return back()->with('success', 'Lead berhasil ditugaskan ke Partner.');
    }
}
