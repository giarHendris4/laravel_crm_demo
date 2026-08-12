<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Deal;
use App\Models\Lead;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dispatch dashboard analytics berdasarkan role.
     */
    public function index()
    {
        $user = auth()->user();

        return match ($user->role) {
            'admin' => $this->adminDashboard(),
            'sales' => $this->salesDashboard(),
            'partner' => redirect()->route('partner.dashboard'),
            default => abort(403, 'Role tidak dikenali.'),
        };
    }

    /**
     * Dashboard Admin: statistik seluruh data (semua sales).
     */
    public function adminDashboard()
    {
        $totalLeads = Lead::count();
        $totalCustomers = Customer::where('status', 'active')->count();
        $activePipelineValue = Deal::whereIn('stage', ['qualification', 'proposal', 'negotiation'])
            ->sum('deal_value');
        $wonRevenue = Deal::where('stage', 'closed_won')->sum('deal_value');
        $conversionRate = $totalLeads > 0 ? round(($totalCustomers / $totalLeads) * 100, 2) : 0;

        // Agregasi jumlah deal per stage untuk visualisasi pipeline
        $dealsByStage = Deal::select('stage', DB::raw('count(*) as total'))
            ->groupBy('stage')
            ->pluck('total', 'stage');

        // 5 deal terbaru (eager loading agar tidak N+1)
        $recentDeals = Deal::with(['user', 'lead'])->latest()->limit(5)->get();

        return view('dashboard.admin', compact(
            'totalLeads',
            'totalCustomers',
            'activePipelineValue',
            'wonRevenue',
            'conversionRate',
            'dealsByStage',
            'recentDeals'
        ));
    }

    /**
     * Dashboard Sales: statistik khusus performa sales yang sedang login.
     */
    public function salesDashboard()
    {
        $user = auth()->user();

        $myLeadsCount = Lead::where('user_id', $user->id)->count();
        $myCustomersCount = Customer::where('user_id', $user->id)->count();

        $myPipelineValue = Deal::where('user_id', $user->id)
            ->whereIn('stage', ['qualification', 'proposal', 'negotiation'])
            ->sum('deal_value');

        $myWonRevenue = Deal::where('user_id', $user->id)
            ->where('stage', 'closed_won')
            ->sum('deal_value');

        $myTotalDeals = Deal::where('user_id', $user->id)->count();
        $myWonDeals = Deal::where('user_id', $user->id)->where('stage', 'closed_won')->count();
        $winRate = $myTotalDeals > 0 ? round(($myWonDeals / $myTotalDeals) * 100, 2) : 0;

        // 5 lead terbaru milik sales ini (eager loading kategori)
        $myRecentLeads = Lead::where('user_id', $user->id)
            ->with('category')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.sales', compact(
            'myLeadsCount',
            'myCustomersCount',
            'myPipelineValue',
            'myWonRevenue',
            'winRate',
            'myRecentLeads'
        ));
    }
}
