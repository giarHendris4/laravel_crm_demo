<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'type' => 'required|in:call,meeting,email,note',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'performed_at' => 'required|date',
        ]);

        $validated['user_id'] = auth()->id();

        Activity::create($validated);

        return back();
    }
}
