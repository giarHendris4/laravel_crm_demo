<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index()
{
    $user = Auth::user();

    $deals = $user->role === 'admin' 
        ? Deal::with(['user', 'lead'])->get() 
        : Deal::where('user_id', $user->id)->with('lead')->get();

    return view('deals.index', compact('deals'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'title' => 'required|string|max:255',
            'deal_value' => 'required|numeric',
            'stage' => 'required|in:qualification,proposal,negotiation,closed_won,closed_lost',
            'expected_close_date' => 'required|date',
        ]);

        $validated['user_id'] = Auth::id();

        Deal::create($validated);

        return redirect()->route('deals.index');
    }

    public function update(Request $request, Deal $deal)
    {
        $user = auth()->user();
        if ($user->role !== 'admin') {
            abort_unless($deal->user_id === $user->id, 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'deal_value' => 'sometimes|required|numeric|min:0',
            'stage' => 'required|in:qualification,proposal,negotiation,closed_won,closed_lost',
            'expected_close_date' => 'nullable|date',
        ]);

        $deal->update($validated);

        return redirect()->route('deals.index')->with('success', 'Deal berhasil diperbarui.');
    }
}