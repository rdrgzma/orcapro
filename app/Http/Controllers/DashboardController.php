<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\WorkOrder;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'pendingBudgets' => Budget::where('status','sent')->count(),
            'openOS' => WorkOrder::where('status','open')->count(),
            'revenueMonth' => Budget::whereMonth('created_at', now()->month)->sum('total'),
        ]);
    }
}

