<?php

namespace App\Http\Controllers\Warehouses;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage system');
    }

    public function index(Request $request)
    {
        $query = Warehouse::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $warehouses = $query->withCount('tools')
                           ->orderBy('name')
                           ->paginate(15);

        return view('warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        return view('warehouses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:warehouses,code',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        Warehouse::create($request->all());

        return redirect()->route('warehouses.index')
            ->with('success', 'Almacén creado exitosamente.');
    }

    public function show(Warehouse $warehouse)
    {
        $warehouse->load(['tools' => function($query) {
            $query->orderBy('name');
        }]);

        return view('warehouses.show', compact('warehouse'));
    }

    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:warehouses,code,' . $warehouse->id,
            'location' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        $warehouse->update($request->all());

        return redirect()->route('warehouses.index')
            ->with('success', 'Almacén actualizado exitosamente.');
    }

    public function destroy(Warehouse $warehouse)
    {
        if ($warehouse->tools()->count() > 0) {
            return back()->with('error', 'No se puede eliminar un almacén que tiene herramientas asignadas.');
        }

        $warehouse->delete();

        return redirect()->route('warehouses.index')
            ->with('success', 'Almacén eliminado exitosamente.');
    }
}
