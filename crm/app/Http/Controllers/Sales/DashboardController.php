<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Deal;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $myLeadsCount = Lead::where('user_id', $user->id)->count();
        $myCustomersCount = Customer::where('user_id', $user->id)->count();
        $myPipelineValue = Deal::where('user_id', $user->id)
            ->whereIn('stage', ['qualification', 'proposal', 'negotiation'])
            ->sum('deal_value');
        $myWonRevenue = Deal::where('user_id', $user->id)
            ->where('stage', 'closed_won')
            ->sum('deal_value');

        return view('sales.dashboard', compact(
            'myLeadsCount',
            'myCustomersCount',
            'myPipelineValue',
            'myWonRevenue'
        ));
    }
}
