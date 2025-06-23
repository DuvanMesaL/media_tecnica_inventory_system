<?php

namespace App\Http\Controllers\Loans;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreToolLoanRequest;
use App\Models\ToolLoan;
use App\Models\ToolLoanItem;
use App\Models\Tool;
use App\Models\TechnicalProgram;
use App\Models\Classroom;
use App\Models\Warehouse;
use App\Jobs\SendLoanNotificationEmail;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ToolLoanController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = ToolLoan::with(['user', 'technicalProgram', 'classroom', 'warehouse']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('loan_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('technicalProgram', function($programQuery) use ($search) {
                      $programQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('loan_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('loan_date', '<=', $request->date_to);
        }

        // Filter by overdue
        if ($request->filled('overdue') && $request->overdue === '1') {
            $query->where('status', 'delivered')
                  ->where('expected_return_date', '<', now());
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->hasRole('teacher')) {
            $query->where('user_id', Auth::id());
        }

        $loans = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get statistics
        $stats = [
            'total' => ToolLoan::count(),
            'pending' => ToolLoan::where('status', 'pending')->count(),
            'active' => ToolLoan::whereIn('status', ['approved', 'delivered'])->count(),
            'overdue' => ToolLoan::where('status', 'delivered')
                ->where('expected_return_date', '<', now())->count(),
        ];

        return view('loans.index', compact('loans', 'stats'));
    }

    public function create()
    {
        $technicalPrograms = TechnicalProgram::where('is_active', true)->get();
        $warehouses = Warehouse::where('is_active', true)->get();
        $tools = Tool::where('available_quantity', '>', 0)
            ->with('warehouse')
            ->orderBy('name')
            ->get();

        return view('loans.create', compact('technicalPrograms', 'warehouses', 'tools'));
    }

    public function store(StoreToolLoanRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated, $request) {
                // Create the loan
                $loan = ToolLoan::create([
                    'user_id' => Auth::id(),
                    'technical_program_id' => $validated['technical_program_id'],
                    'classroom_id' => $validated['classroom_id'],
                    'warehouse_id' => $validated['warehouse_id'],
                    'loan_date' => $validated['loan_date'],
                    'expected_return_date' => $validated['expected_return_date'],
                    'notes' => $validated['notes'],
                    'status' => 'pending'
                ]);

                // Create loan items
                foreach ($validated['tools'] as $toolData) {
                    $tool = Tool::find($toolData['tool_id']);

                    if (!$tool->isAvailable($toolData['quantity'])) {
                        throw new \Exception("Insufficient quantity for tool: {$tool->name}");
                    }

                    ToolLoanItem::create([
                        'tool_loan_id' => $loan->id,
                        'tool_id' => $toolData['tool_id'],
                        'quantity_requested' => $toolData['quantity']
                    ]);
                }
            });

            return redirect()->route('loans.index')
                ->with('success', 'Solicitud de préstamo creada exitosamente. En espera de aprobación.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al crear el préstamo: ' . $e->getMessage());
        }
    }

    public function show(ToolLoan $loan)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        // Check if user can view this loan
        if ($user->hasRole('teacher') && $loan->user_id !== Auth::id()) {
            abort(403, 'Solo puedes ver tus propios préstamos.');
        }

        $loan->load([
            'user',
            'technicalProgram',
            'classroom',
            'warehouse',
            'toolLoanItems.tool',
            'approvedBy',
            'deliveredBy',
            'receivedBy'
        ]);

        return view('loans.show', compact('loan'));
    }

    public function approve(ToolLoan $loan)
    {
        $this->authorize('approve', $loan);

        if ($loan->status !== 'pending') {
            return back()->with('error', 'Solo se pueden aprobar préstamos pendientes.');
        }

        try {
            DB::transaction(function () use ($loan) {
                // Check availability again
                foreach ($loan->toolLoanItems as $item) {
                    if (!$item->tool->isAvailable($item->quantity_requested)) {
                        throw new \Exception("La herramienta {$item->tool->name} ya no está disponible en la cantidad solicitada.");
                    }
                }

                // Reserve tools and update loan
                foreach ($loan->toolLoanItems as $item) {
                    $tool = $item->tool;
                    $tool->decrement('available_quantity', $item->quantity_requested);

                    $item->update([
                        'quantity_delivered' => $item->quantity_requested,
                        'condition_delivered' => 'good'
                    ]);
                }

                $loan->update([
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                ]);

                // Send notification email
                SendLoanNotificationEmail::dispatch($loan, 'approved');
            });

            return back()->with('success', 'Préstamo aprobado y herramientas reservadas exitosamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al aprobar el préstamo: ' . $e->getMessage());
        }
    }

    public function deliver(ToolLoan $loan)
    {
        $this->authorize('deliver', $loan);

        if ($loan->status !== 'approved') {
            return back()->with('error', 'Solo se pueden entregar préstamos aprobados.');
        }

        $loan->update([
            'status' => 'delivered',
            'delivered_by' => Auth::id(),
        ]);

        // Send notification email
        SendLoanNotificationEmail::dispatch($loan, 'delivered');

        return back()->with('success', 'Préstamo marcado como entregado exitosamente.');
    }

    public function showReturnForm(ToolLoan $loan)
    {
        $this->authorize('return', $loan);

        if ($loan->status !== 'delivered') {
            return back()->with('error', 'Solo se pueden devolver préstamos entregados.');
        }

        $loan->load(['toolLoanItems.tool']);

        return view('loans.return', compact('loan'));
    }

    public function processReturn(Request $request, ToolLoan $loan)
    {
        $this->authorize('return', $loan);

        if ($loan->status !== 'delivered') {
            return back()->with('error', 'Solo se pueden devolver préstamos entregados.');
        }

        $request->validate([
            'items' => 'required|array',
            'items.*.quantity_returned' => 'required|integer|min:0',
            'items.*.condition_returned' => 'required|in:good,damaged,lost',
            'items.*.notes' => 'nullable|string|max:500'
        ]);

        try {
            DB::transaction(function () use ($loan, $request) {
                foreach ($request->items as $itemId => $itemData) {
                    $loanItem = $loan->toolLoanItems()->find($itemId);

                    if (!$loanItem) {
                        continue;
                    }

                    // Validate return quantity
                    if ($itemData['quantity_returned'] > $loanItem->quantity_delivered) {
                        throw new \Exception("No se puede devolver más de lo entregado para {$loanItem->tool->name}");
                    }

                    // Update loan item
                    $loanItem->update([
                        'quantity_returned' => $itemData['quantity_returned'],
                        'condition_returned' => $itemData['condition_returned'],
                        'notes' => $itemData['notes']
                    ]);

                    // Update tool quantities based on condition
                    $tool = $loanItem->tool;
                    $returnedQuantity = $itemData['quantity_returned'];

                    switch ($itemData['condition_returned']) {
                        case 'good':
                            $tool->increment('available_quantity', $returnedQuantity);
                            break;
                        case 'damaged':
                            // Don't add back to available, but keep in total
                            break;
                        case 'lost':
                            $tool->decrement('total_quantity', $returnedQuantity);
                            break;
                    }
                }

                $loan->update([
                    'status' => 'returned',
                    'actual_return_date' => now(),
                    'received_by' => Auth::id(),
                ]);

                // Send notification email
                SendLoanNotificationEmail::dispatch($loan, 'returned');
            });

            return redirect()->route('loans.show', $loan)
                ->with('success', 'Herramientas devueltas exitosamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar la devolución: ' . $e->getMessage());
        }
    }

    public function cancel(ToolLoan $loan)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        // Only the requestor or admin can cancel
        if ($loan->user_id !== Auth::id() && !$user->hasRole('admin')) {
            abort(403, 'Solo puedes cancelar tus propios préstamos.');
        }

        if (!in_array($loan->status, ['pending', 'approved'])) {
            return back()->with('error', 'Solo se pueden cancelar préstamos pendientes o aprobados.');
        }

        try {
            DB::transaction(function () use ($loan) {
                // If loan was approved, return quantities to available
                if ($loan->status === 'approved') {
                    foreach ($loan->toolLoanItems as $item) {
                        $item->tool->increment('available_quantity', $item->quantity_delivered);
                    }
                }

                $loan->update(['status' => 'cancelled']);
            });

            return back()->with('success', 'Préstamo cancelado exitosamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al cancelar el préstamo: ' . $e->getMessage());
        }
    }

    public function getClassrooms(Request $request)
    {
        $classrooms = Classroom::where('technical_program_id', $request->technical_program_id)
            ->where('is_active', true)
            ->get(['id', 'name', 'code']);

        return response()->json($classrooms);
    }

    public function getToolsByWarehouse(Request $request)
    {
        $tools = Tool::where('warehouse_id', $request->warehouse_id)
            ->where('available_quantity', '>', 0)
            ->get(['id', 'name', 'code', 'available_quantity', 'category']);

        return response()->json($tools);
    }
}
