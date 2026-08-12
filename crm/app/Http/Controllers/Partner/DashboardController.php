<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\LeadAssignment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Mengambil statistik penugasan lead khusus untuk Partner yang sedang login
        $totalAssignedLeads = LeadAssignment::where('partner_id', $user->id)->count();

        return view('partner.dashboard', compact('totalAssignedLeads'));
    }
}
