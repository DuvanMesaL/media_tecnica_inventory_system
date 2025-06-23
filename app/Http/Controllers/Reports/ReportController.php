<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\ToolLoan;
use App\Models\ToolLoanItem;
use App\Models\TechnicalProgram;
use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view reports');
    }

    public function index()
    {
        // Overview statistics
        $stats = [
            'total_tools' => Tool::sum('total_quantity'),
            'available_tools' => Tool::sum('available_quantity'),
            'tools_on_loan' => Tool::sum('total_quantity') - Tool::sum('available_quantity'),
            'active_loans' => ToolLoan::whereIn('status', ['approved', 'delivered'])->count(),
            'overdue_loans' => ToolLoan::where('status', 'delivered')
                ->where('expected_return_date', '<', now())->count(),
            'total_value' => Tool::whereNotNull('unit_price')
                ->sum(DB::raw('total_quantity * unit_price')),
        ];

        // Recent activity
        $recent_loans = ToolLoan::with(['user', 'technicalProgram'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Top categories by usage
        $top_categories = ToolLoanItem::join('tools', 'tool_loan_items.tool_id', '=', 'tools.id')
            ->select('tools.category', DB::raw('SUM(tool_loan_items.quantity_delivered) as total_loaned'))
            ->groupBy('tools.category')
            ->orderBy('total_loaned', 'desc')
            ->limit(10)
            ->get();

        // Monthly loan trends (last 12 months)
        $monthly_trends = ToolLoan::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total_loans')
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('reports.index', compact('stats', 'recent_loans', 'top_categories', 'monthly_trends'));
    }

    public function toolsReport(Request $request)
    {
        $query = Tool::with(['warehouse']);

        // Filters
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        if ($request->filled('category')) {
            $query->where('category', 'like', "%{$request->category}%");
        }

        if ($request->filled('stock_level')) {
            switch ($request->stock_level) {
                case 'out_of_stock':
                    $query->where('available_quantity', 0);
                    break;
                case 'low_stock':
                    $query->where('available_quantity', '>', 0)
                          ->where('available_quantity', '<=', 5);
                    break;
                case 'adequate_stock':
                    $query->where('available_quantity', '>', 5);
                    break;
            }
        }

        $tools = $query->orderBy('name')->paginate(20);
        $warehouses = Warehouse::where('is_active', true)->get();
        $categories = Tool::distinct()->pluck('category')->filter()->sort();

        // Summary statistics
        $summary = [
            'total_tools' => $query->count(),
            'total_value' => $query->whereNotNull('unit_price')
                ->sum(DB::raw('total_quantity * unit_price')),
            'out_of_stock' => Tool::where('available_quantity', 0)->count(),
            'low_stock' => Tool::where('available_quantity', '>', 0)
                ->where('available_quantity', '<=', 5)->count(),
        ];

        return view('reports.tools', compact('tools', 'warehouses', 'categories', 'summary'));
    }

    public function loansReport(Request $request)
    {
        $query = ToolLoan::with(['user', 'technicalProgram', 'classroom', 'warehouse']);

        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('loan_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('loan_date', '<=', $request->date_to);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Program filter
        if ($request->filled('technical_program_id')) {
            $query->where('technical_program_id', $request->technical_program_id);
        }

        // Overdue filter
        if ($request->filled('overdue') && $request->overdue === '1') {
            $query->where('status', 'delivered')
                  ->where('expected_return_date', '<', now());
        }

        $loans = $query->orderBy('created_at', 'desc')->paginate(20);
        $programs = TechnicalProgram::where('is_active', true)->get();

        // Summary statistics
        $summary = [
            'total_loans' => $query->count(),
            'pending_loans' => ToolLoan::where('status', 'pending')->count(),
            'active_loans' => ToolLoan::whereIn('status', ['approved', 'delivered'])->count(),
            'overdue_loans' => ToolLoan::where('status', 'delivered')
                ->where('expected_return_date', '<', now())->count(),
            'completed_loans' => ToolLoan::where('status', 'returned')->count(),
        ];

        return view('reports.loans', compact('loans', 'programs', 'summary'));
    }

    public function usageReport(Request $request)
    {
        // Most used tools
        $most_used_tools = ToolLoanItem::join('tools', 'tool_loan_items.tool_id', '=', 'tools.id')
            ->select(
                'tools.name',
                'tools.code',
                'tools.category',
                DB::raw('COUNT(tool_loan_items.id) as loan_count'),
                DB::raw('SUM(tool_loan_items.quantity_delivered) as total_quantity_loaned')
            )
            ->groupBy('tools.id', 'tools.name', 'tools.code', 'tools.category')
            ->orderBy('total_quantity_loaned', 'desc')
            ->limit(20)
            ->get();

        // Usage by technical program
        $usage_by_program = ToolLoan::join('technical_programs', 'tool_loans.technical_program_id', '=', 'technical_programs.id')
            ->select(
                'technical_programs.name',
                'technical_programs.code',
                DB::raw('COUNT(tool_loans.id) as total_loans'),
                DB::raw('COUNT(CASE WHEN tool_loans.status = "returned" THEN 1 END) as completed_loans'),
                DB::raw('COUNT(CASE WHEN tool_loans.status = "delivered" AND tool_loans.expected_return_date < NOW() THEN 1 END) as overdue_loans')
            )
            ->groupBy('technical_programs.id', 'technical_programs.name', 'technical_programs.code')
            ->orderBy('total_loans', 'desc')
            ->get();

        // Usage by category
        $usage_by_category = ToolLoanItem::join('tools', 'tool_loan_items.tool_id', '=', 'tools.id')
            ->select(
                'tools.category',
                DB::raw('COUNT(tool_loan_items.id) as loan_count'),
                DB::raw('SUM(tool_loan_items.quantity_delivered) as total_quantity')
            )
            ->groupBy('tools.category')
            ->orderBy('total_quantity', 'desc')
            ->get();

        // Lost and damaged tools
        $damaged_lost_tools = ToolLoanItem::join('tools', 'tool_loan_items.tool_id', '=', 'tools.id')
            ->join('tool_loans', 'tool_loan_items.tool_loan_id', '=', 'tool_loans.id')
            ->select(
                'tools.name',
                'tools.code',
                'tool_loan_items.condition_returned',
                'tool_loan_items.quantity_returned',
                'tool_loans.loan_number',
                'tool_loans.actual_return_date'
            )
            ->whereIn('tool_loan_items.condition_returned', ['damaged', 'lost'])
            ->orderBy('tool_loans.actual_return_date', 'desc')
            ->get();

        // Top borrowers
        $top_borrowers = ToolLoan::join('users', 'tool_loans.user_id', '=', 'users.id')
            ->select(
                'users.name',
                'users.email',
                DB::raw('COUNT(tool_loans.id) as total_loans'),
                DB::raw('COUNT(CASE WHEN tool_loans.status = "delivered" AND tool_loans.expected_return_date < NOW() THEN 1 END) as overdue_count')
            )
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('total_loans', 'desc')
            ->limit(15)
            ->get();

        return view('reports.usage', compact(
            'most_used_tools',
            'usage_by_program',
            'usage_by_category',
            'damaged_lost_tools',
            'top_borrowers'
        ));
    }

    public function export(Request $request, $type)
    {
        $this->authorize('export', 'reports');

        switch ($type) {
            case 'tools':
                return $this->exportTools($request);
            case 'loans':
                return $this->exportLoans($request);
            case 'usage':
                return $this->exportUsage($request);
            default:
                abort(404);
        }
    }

    private function exportTools(Request $request)
    {
        $tools = Tool::with('warehouse')->get();

        $filename = 'tools_report_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($tools) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'Tool Name',
                'Code',
                'Category',
                'Warehouse',
                'Condition',
                'Total Quantity',
                'Available Quantity',
                'On Loan',
                'Unit Price',
                'Total Value'
            ]);

            // CSV data
            foreach ($tools as $tool) {
                fputcsv($file, [
                    $tool->name,
                    $tool->code,
                    $tool->category,
                    $tool->warehouse->name,
                    ucfirst($tool->condition),
                    $tool->total_quantity,
                    $tool->available_quantity,
                    $tool->total_quantity - $tool->available_quantity,
                    $tool->unit_price ? '$' . number_format($tool->unit_price, 2) : '',
                    $tool->unit_price ? '$' . number_format($tool->total_quantity * $tool->unit_price, 2) : ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportLoans(Request $request)
    {
        $loans = ToolLoan::with(['user', 'technicalProgram', 'classroom', 'warehouse'])->get();

        $filename = 'loans_report_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($loans) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'Loan Number',
                'Borrower',
                'Technical Program',
                'Classroom',
                'Warehouse',
                'Status',
                'Loan Date',
                'Expected Return',
                'Actual Return',
                'Days Overdue'
            ]);

            // CSV data
            foreach ($loans as $loan) {
                $daysOverdue = '';
                if ($loan->status === 'delivered' && $loan->expected_return_date < now()) {
                    $daysOverdue = now()->diffInDays($loan->expected_return_date);
                }

                fputcsv($file, [
                    $loan->loan_number,
                    $loan->user->name,
                    $loan->technicalProgram->name,
                    $loan->classroom->name,
                    $loan->warehouse->name,
                    ucfirst($loan->status),
                    $loan->loan_date->format('Y-m-d'),
                    $loan->expected_return_date->format('Y-m-d'),
                    $loan->actual_return_date ? $loan->actual_return_date->format('Y-m-d') : '',
                    $daysOverdue
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportUsage(Request $request)
    {
        $usage_data = ToolLoanItem::join('tools', 'tool_loan_items.tool_id', '=', 'tools.id')
            ->join('tool_loans', 'tool_loan_items.tool_loan_id', '=', 'tool_loans.id')
            ->join('users', 'tool_loans.user_id', '=', 'users.id')
            ->join('technical_programs', 'tool_loans.technical_program_id', '=', 'technical_programs.id')
            ->select(
                'tools.name as tool_name',
                'tools.code as tool_code',
                'tools.category',
                'users.name as borrower',
                'technical_programs.name as program',
                'tool_loan_items.quantity_delivered',
                'tool_loan_items.quantity_returned',
                'tool_loan_items.condition_returned',
                'tool_loans.loan_date',
                'tool_loans.actual_return_date'
            )
            ->get();

        $filename = 'usage_report_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($usage_data) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'Tool Name',
                'Tool Code',
                'Category',
                'Borrower',
                'Technical Program',
                'Quantity Delivered',
                'Quantity Returned',
                'Return Condition',
                'Loan Date',
                'Return Date'
            ]);

            // CSV data
            foreach ($usage_data as $item) {
                fputcsv($file, [
                    $item->tool_name,
                    $item->tool_code,
                    $item->category,
                    $item->borrower,
                    $item->program,
                    $item->quantity_delivered,
                    $item->quantity_returned,
                    $item->condition_returned ? ucfirst($item->condition_returned) : '',
                    $item->loan_date,
                    $item->actual_return_date
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
