<?php

namespace App\Http\Controllers\Programs;

use App\Http\Controllers\Controller;
use App\Models\TechnicalProgram;
use Illuminate\Http\Request;

class TechnicalProgramController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage system');
    }

    public function index(Request $request)
    {
        $query = TechnicalProgram::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $programs = $query->withCount(['users', 'classrooms'])
                         ->orderBy('name')
                         ->paginate(15);

        return view('programs.index', compact('programs'));
    }

    public function create()
    {
        return view('programs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:technical_programs,code',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        TechnicalProgram::create($request->all());

        return redirect()->route('programs.index')
            ->with('success', 'Programa técnico creado exitosamente.');
    }

    public function show(TechnicalProgram $program)
    {
        $program->load(['users', 'classrooms']);

        return view('programs.show', compact('program'));
    }

    public function edit(TechnicalProgram $program)
    {
        return view('programs.edit', compact('program'));
    }

    public function update(Request $request, TechnicalProgram $program)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:technical_programs,code,' . $program->id,
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        $program->update($request->all());

        return redirect()->route('programs.index')
            ->with('success', 'Programa técnico actualizado exitosamente.');
    }

    public function destroy(TechnicalProgram $program)
    {
        // Check if program has associated users
        $usersCount = $program->users()->count();
        $classroomsCount = $program->classrooms()->count();
        $loansCount = $program->toolLoans()->count();

        if ($usersCount > 0 || $classroomsCount > 0 || $loansCount > 0) {
            $associations = [];
            if ($usersCount > 0) $associations[] = "{$usersCount} usuario(s)";
            if ($classroomsCount > 0) $associations[] = "{$classroomsCount} aula(s)";
            if ($loansCount > 0) $associations[] = "{$loansCount} préstamo(s)";

            $message = 'No se puede eliminar el programa técnico porque tiene ' . implode(', ', $associations) . ' asociado(s).';

            return back()->with('error', $message);
        }

        $programName = $program->name;
        $program->delete();

        return redirect()->route('programs.index')
            ->with('success', "El programa técnico '{$programName}' ha sido eliminado exitosamente.");
    }
}
