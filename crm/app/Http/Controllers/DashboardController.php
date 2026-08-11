<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Lead;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Query berdasarkan role (Admin melihat semua, Sales hanya data milik sendiri)
        $dealQuery = Deal::query();
        $leadQuery = Lead::query();

        if ($user->role !== 'admin') {
            $dealQuery->where('user_id', $user->id);
            $leadQuery->where('user_id', $user->id);
        }

        // Hitung Total Revenue dari Deal yang closed_won
        $totalRevenue = (clone $dealQuery)->where('stage', 'closed_won')->sum('deal_value');

        // Hitung Conversion Rate (Jumlah Deal Won / Total Lead * 100)
        $totalLeads = $leadQuery->count();
        $totalWonDeals = (clone $dealQuery)->where('stage', 'closed_won')->count();
        
        $conversionRate = $totalLeads > 0 
            ? round(($totalWonDeals / $totalLeads) * 100, 2) 
            : 0;

        return view('dashboard.index', compact('totalRevenue', 'conversionRate', 'totalLeads', 'totalWonDeals'));
    }
}