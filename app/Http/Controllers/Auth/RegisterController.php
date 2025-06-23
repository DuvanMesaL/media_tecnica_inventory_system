<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TechnicalProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage users');
    }

    public function showRegistrationForm()
    {
        $technicalPrograms = TechnicalProgram::where('is_active', true)->get();
        return view('auth.register', compact('technicalPrograms'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|in:admin,teacher,logistics',
            'technical_program_id' => 'nullable|exists:technical_programs,id|required_if:role,teacher',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'technical_program_id' => $request->technical_program_id,
            'is_active' => true,
        ]);

        // Assign role
        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }
}
