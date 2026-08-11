<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'   => User::count(),
            'total_sales'   => User::where('role', 'sales')->count(),
            'total_partner' => User::where('role', 'partner')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}