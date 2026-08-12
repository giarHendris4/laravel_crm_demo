<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    /**
     * Tampilkan daftar leads.
     * Admin melihat semua leads; Sales hanya melihat miliknya.
     */
    public function index()
    {
        $user = Auth::user();

        $leads = Lead::with(['user', 'category'])
            ->when($user->role === 'sales', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->paginate(10);

        return view('leads.index', compact('leads'));
    }

    /**
     * Form tambah lead baru.
     */
    public function create()
    {
        $categories = LeadCategory::orderBy('name')->get();

        return view('leads.create', compact('categories'));
    }

    /**
     * Simpan lead baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lead_category_id' => 'nullable|exists:lead_categories,id',
            'title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'opportunity_value' => 'required|numeric|min:0',
            'status' => 'required|in:new,contacted,proposal,negotiation,won,lost',
        ]);

        $validated['user_id'] = Auth::id();

        Lead::create($validated);

        return redirect()->route('leads.index')->with('success', 'Lead berhasil ditambahkan!');
    }

    // Show Lead
    public function show(Lead $lead)
    {
        $lead->load(['activities.user', 'category']);

        return view('leads.show', compact('lead'));
    }

    /**
     * Form edit lead.
     */
    public function edit(Lead $lead)
    {
        // Otorisasi sederhana: Sales tidak boleh mengedit lead milik Sales lain
        if (Auth::user()->role === 'sales' && $lead->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = LeadCategory::orderBy('name')->get();

        return view('leads.edit', compact('lead', 'categories'));
    }

    /**
     * Update lead.
     */
    public function update(Request $request, Lead $lead)
    {
        if (Auth::user()->role === 'sales' && $lead->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'lead_category_id' => 'nullable|exists:lead_categories,id',
            'title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'opportunity_value' => 'required|numeric|min:0',
            'status' => 'required|in:new,contacted,proposal,negotiation,won,lost',
        ]);

        $lead->update($validated);

        return redirect()->route('leads.index')->with('success', 'Lead berhasil diperbarui!');
    }

    /**
     * Hapus lead.
     */
    public function destroy(Lead $lead)
    {
        if (Auth::user()->role === 'sales' && $lead->user_id !== Auth::id()) {
            abort(403);
        }

        $lead->delete();

        return redirect()->route('leads.index')->with('success', 'Lead berhasil dihapus!');
    }
}
