<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreToolRequest;
use App\Http\Requests\UpdateToolRequest;
use App\Models\Tool;
use App\Models\Warehouse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ToolController extends Controller
{

    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage tools')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query = Tool::with('warehouse');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Filter by warehouse
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        // Filter by condition
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', 'like', "%{$request->category}%");
        }

        // Filter by availability
        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                $query->where('available_quantity', '>', 0);
            } elseif ($request->availability === 'out_of_stock') {
                $query->where('available_quantity', '=', 0);
            } elseif ($request->availability === 'low_stock') {
                $query->where('available_quantity', '>', 0)
                      ->where('available_quantity', '<=', 5);
            }
        }

        $tools = $query->orderBy('name')->paginate(15);
        $warehouses = Warehouse::where('is_active', true)->get();
        $categories = Tool::distinct()->pluck('category')->filter()->sort();

        return view('tools.index', compact('tools', 'warehouses', 'categories'));
    }

    public function create()
    {
        $this->authorize('create', Tool::class);

        $warehouses = Warehouse::where('is_active', true)->get();
        $categories = Tool::distinct()->pluck('category')->filter()->sort();

        return view('tools.create', compact('warehouses', 'categories'));
    }

    public function store(StoreToolRequest $request)
    {
        $validated = $request->validated();
        $validated['available_quantity'] = $validated['total_quantity'];

        $tool = Tool::create($validated);

        return redirect()->route('tools.index')
            ->with('success', "Tool '{$tool->name}' created successfully.");
    }

    public function show(Tool $tool)
    {
        $tool->load([
            'warehouse',
            'toolLoanItems.toolLoan.user',
            'toolLoanItems.toolLoan.technicalProgram',
            'toolLoanItems.toolLoan.classroom'
        ]);

        // Get loan history
        $loanHistory = $tool->toolLoanItems()
            ->with(['toolLoan.user', 'toolLoan.technicalProgram', 'toolLoan.classroom'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tools.show', compact('tool', 'loanHistory'));
    }

    public function edit(Tool $tool)
    {
        $this->authorize('update', $tool);

        $warehouses = Warehouse::where('is_active', true)->get();
        $categories = Tool::distinct()->pluck('category')->filter()->sort();

        return view('tools.edit', compact('tool', 'warehouses', 'categories'));
    }

    public function update(UpdateToolRequest $request, Tool $tool)
    {
        $validated = $request->validated();

        // Calculate available quantity adjustment
        $quantityDifference = $validated['total_quantity'] - $tool->total_quantity;
        $validated['available_quantity'] = max(0, $tool->available_quantity + $quantityDifference);

        $tool->update($validated);

        return redirect()->route('tools.index')
            ->with('success', "Tool '{$tool->name}' updated successfully.");
    }

    public function destroy(Tool $tool)
    {
        $this->authorize('delete', $tool);

        // Check if tool has active loans
        if ($tool->toolLoanItems()->whereHas('toolLoan', function($query) {
            $query->whereIn('status', ['pending', 'approved', 'delivered']);
        })->exists()) {
            return back()->with('error', 'Cannot delete tool with active loans.');
        }

        $toolName = $tool->name;
        $tool->delete();

        return redirect()->route('tools.index')
            ->with('success', "Tool '{$toolName}' deleted successfully.");
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,update_condition,move_warehouse',
            'tool_ids' => 'required|array|min:1',
            'tool_ids.*' => 'exists:tools,id',
            'condition' => 'required_if:action,update_condition|in:good,damaged,lost',
            'warehouse_id' => 'required_if:action,move_warehouse|exists:warehouses,id'
        ]);

        $tools = Tool::whereIn('id', $request->tool_ids);
        $count = $tools->count();

        switch ($request->action) {
            case 'delete':
                $this->authorize('delete', Tool::class);
                $tools->delete();
                $message = "{$count} tools deleted successfully.";
                break;

            case 'update_condition':
                $this->authorize('update', Tool::class);
                $tools->update(['condition' => $request->condition]);
                $message = "{$count} tools condition updated to {$request->condition}.";
                break;

            case 'move_warehouse':
                $this->authorize('update', Tool::class);
                $warehouse = Warehouse::find($request->warehouse_id);
                $tools->update(['warehouse_id' => $request->warehouse_id]);
                $message = "{$count} tools moved to {$warehouse->name}.";
                break;
        }

        return back()->with('success', $message);
    }
}
