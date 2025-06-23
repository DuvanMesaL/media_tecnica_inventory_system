<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TechnicalProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage users');
    }

    public function index(Request $request)
    {
        $query = User::with(['technicalProgram', 'roles']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('identification_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->role($request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('program')) {
            $query->where('technical_program_id', $request->program);
        }

        $users = $query->orderBy('name')->paginate(15);
        $roles = Role::all();
        $programs = TechnicalProgram::where('is_active', true)->get();

        return view('users.index', compact('users', 'roles', 'programs'));
    }

    public function show(User $user)
    {
        $user->load(['technicalProgram', 'roles', 'toolLoans.toolLoanItems.tool']);

        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $programs = TechnicalProgram::where('is_active', true)->get();

        return view('users.edit', compact('user', 'roles', 'programs'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'second_last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'identification_number' => 'nullable|string|max:50|unique:users,identification_number,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'technical_program_id' => 'nullable|exists:technical_programs,id',
            'role' => 'required|exists:roles,name',
            'is_active' => 'boolean',
            'password' => 'nullable|min:8|confirmed'
        ]);

        $updateData = $request->except(['password', 'password_confirmation', 'role']);

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        // Update role
        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        if ($user->toolLoans()->count() > 0) {
            return back()->with('error', 'No se puede eliminar un usuario que tiene préstamos asociados.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }
}
