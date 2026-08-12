<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DealController extends Controller
{
    /**
     * Tampilkan daftar deal.
     */
    public function index()
    {
        $user = Auth::user();

        $deals = Deal::with(['user', 'lead'])
            ->when($user->role !== 'admin', fn ($q) => $q->where('user_id', $user->id))
            ->latest()
            ->paginate(10);

        return view('deals.index', compact('deals'));
    }

    /**
     * Form tambah deal baru.
     */
    public function create()
    {
        $user = Auth::user();

        // Hanya tampilkan lead yang relevan
        $leads = Lead::when($user->role !== 'admin', fn ($q) => $q->where('user_id', $user->id))
            ->latest()
            ->get();

        return view('deals.create', compact('leads'));
    }

    /**
     * Simpan deal baru.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'lead_id'             => 'required|exists:leads,id',
            'title'               => 'required|string|max:255',
            'deal_value'          => 'required|numeric|min:0',
            'stage'               => 'required|in:qualification,proposal,negotiation,closed_won,closed_lost',
            'expected_close_date' => 'required|date',
        ]);

        // Otorisasi: Pastikan Sales tidak membuat deal dari Lead milik Sales lain
        if ($user->role !== 'admin') {
            $lead = Lead::findOrFail($validated['lead_id']);
            if ($lead->user_id !== $user->id) {
                abort(403);
            }
        }

        $validated['user_id'] = $user->id;

        Deal::create($validated);

        return redirect()->route('deals.index')->with('success', 'Deal berhasil dibuat!');
    }

    /**
     * Detail deal.
     */
    public function show(Deal $deal)
    {
        $this->authorizeAccess($deal);

        $deal->load(['user', 'lead']);

        return view('deals.show', compact('deal'));
    }

    /**
     * Form edit deal.
     */
    public function edit(Deal $deal)
    {
        $this->authorizeAccess($deal);

        $user = Auth::user();

        $leads = Lead::when($user->role !== 'admin', fn ($q) => $q->where('user_id', $user->id))
            ->latest()
            ->get();

        return view('deals.edit', compact('deal', 'leads'));
    }

    /**
     * Update deal.
     */
    public function update(Request $request, Deal $deal)
    {
        $this->authorizeAccess($deal);

        $validated = $request->validate([
            'title'               => 'sometimes|required|string|max:255',
            'deal_value'          => 'sometimes|required|numeric|min:0',
            'stage'               => 'required|in:qualification,proposal,negotiation,closed_won,closed_lost',
            'expected_close_date' => 'nullable|date',
        ]);

        $deal->update($validated);

        return redirect()->route('deals.index')->with('success', 'Deal berhasil diperbarui.');
    }

    /**
     * Hapus deal.
     */
    public function destroy(Deal $deal)
    {
        $this->authorizeAccess($deal);

        $deal->delete();

        return redirect()->route('deals.index')->with('success', 'Deal berhasil dihapus.');
    }

    /**
     * Otorisasi isolasi data per-user/role.
     */
    private function authorizeAccess(Deal $deal): void
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $deal->user_id !== $user->id) {
            abort(403);
        }
    }
}