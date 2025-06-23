<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\ToolLoan;
use App\Models\Warehouse;
use App\Models\TechnicalProgram;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_tools' => Tool::sum('total_quantity'),
            'available_tools' => Tool::sum('available_quantity'),
            'active_loans' => ToolLoan::whereIn('status', ['approved', 'delivered'])->count(),
            'pending_loans' => ToolLoan::where('status', 'pending')->count(),
            'total_warehouses' => Warehouse::where('is_active', true)->count(),
            'total_programs' => TechnicalProgram::where('is_active', true)->count(),
        ];

        $recent_loans = ToolLoan::with(['user', 'technicalProgram', 'classroom'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $low_stock_tools = Tool::where('available_quantity', '<=', 5)
            ->with('warehouse')
            ->orderBy('available_quantity', 'asc')
            ->limit(10)
            ->get();

        return view('dashboard', compact('stats', 'recent_loans', 'low_stock_tools'));
    }
}
