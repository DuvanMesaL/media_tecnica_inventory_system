<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TechnicalProgram;
use App\Jobs\SendWelcomeEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class ProfileCompletionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->needsProfileCompletion()) {
            return redirect()->route('dashboard');
        }

        $technicalPrograms = TechnicalProgram::where('is_active', true)->get();

        return view('auth.complete-profile', compact('user', 'technicalPrograms'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->needsProfileCompletion()) {
            return redirect()->route('dashboard');
        }

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'second_last_name' => 'nullable|string|max:255',
            'identification_number' => 'required|string|max:50|unique:users,identification_number,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
        ];

        // Only require password change if not already changed
        if (!$user->password_changed) {
            $rules['current_password'] = 'required';
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        // Only require technical program for teachers
        if ($user->hasRole('teacher')) {
            $rules['technical_program_id'] = 'required|exists:technical_programs,id';
        }

        $request->validate($rules, [
            'first_name.required' => 'El nombre es obligatorio.',
            'last_name.required' => 'El apellido paterno es obligatorio.',
            'identification_number.required' => 'El número de identificación es obligatorio.',
            'identification_number.unique' => 'Este número de identificación ya está registrado.',
            'current_password.required' => 'La contraseña actual es obligatoria.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'technical_program_id.required' => 'Los profesores deben tener un programa técnico asignado.'
        ]);

        // Verify current password if changing password
        if (!$user->password_changed && !Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
        }

        try {
            $updateData = [
                'name' => trim($request->first_name . ' ' . $request->last_name),
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'second_last_name' => $request->second_last_name,
                'identification_number' => $request->identification_number,
                'phone_number' => $request->phone_number,
                'profile_completed' => true,
                'profile_completed_at' => now(),
            ];

            // Update password if provided
            if (!$user->password_changed && $request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
                $updateData['password_changed'] = true;
            }

            // Update technical program if user is teacher
            if ($user->hasRole('teacher') && $request->filled('technical_program_id')) {
                $updateData['technical_program_id'] = $request->technical_program_id;
            }

            $user->update($updateData);

            // Send welcome email if enabled
            if (config('app.welcome_email_enabled', true)) {
                SendWelcomeEmail::dispatch($user);
            }

            return redirect()->route('dashboard')
                ->with('success', '¡Perfil completado exitosamente! Bienvenido al sistema.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al actualizar el perfil: ' . $e->getMessage());
        }
    }
}
