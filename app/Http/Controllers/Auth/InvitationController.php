<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserInvitation;
use App\Models\User;
use App\Models\TechnicalProgram;
use App\Jobs\SendInvitationEmail;
use App\Jobs\SendWelcomeEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class InvitationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['accept', 'complete']);
        $this->middleware('permission:manage users')->only(['create', 'store']);
    }

    public function create()
    {
        $technicalPrograms = TechnicalProgram::where('is_active', true)->get();
        $pendingInvitations = UserInvitation::with(['invitedBy', 'technicalProgram'])
            ->where('is_used', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('auth.invite', compact('technicalPrograms', 'pendingInvitations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email|unique:user_invitations,email',
            'role' => 'required|in:admin,teacher,logistics',
            'technical_program_id' => 'nullable|exists:technical_programs,id|required_if:role,teacher',
        ], [
            'email.unique' => 'Este correo ya está registrado o tiene una invitación pendiente.',
            'technical_program_id.required_if' => 'Los profesores deben tener un programa técnico asignado.'
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Create invitation
                $invitation = UserInvitation::createInvitation(
                    email: $request->email,
                    role: $request->role,
                    technicalProgramId: $request->technical_program_id,
                    invitedBy: Auth::id(),
                    metadata: [
                        'invited_from_ip' => $request->ip(),
                        'invited_at' => now()->toISOString()
                    ]
                );

                // Queue invitation email
                SendInvitationEmail::dispatch($invitation);
            });

            return redirect()->route('invitations.create')
                ->with('success', 'Invitación enviada exitosamente a ' . $request->email);

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al enviar la invitación: ' . $e->getMessage());
        }
    }

    public function accept(string $token)
    {
        $invitation = UserInvitation::where('token', $token)->first();

        if (!$invitation) {
            return redirect()->route('login')
                ->with('error', 'Enlace de invitación inválido.');
        }

        if (!$invitation->isValid()) {
            return redirect()->route('login')
                ->with('error', 'Esta invitación ha expirado o ya fue utilizada.');
        }

        return view('auth.accept-invitation', compact('invitation'));
    }

    public function complete(Request $request, string $token)
    {
        $invitation = UserInvitation::where('token', $token)->first();

        if (!$invitation || !$invitation->isValid()) {
            return redirect()->route('login')
                ->with('error', 'Enlace de invitación inválido o expirado.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'second_last_name' => 'nullable|string|max:255',
            'identification_number' => 'required|string|max:50|unique:users,identification_number',
            'phone_number' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::defaults()],
            'terms' => 'required|accepted'
        ], [
            'first_name.required' => 'El nombre es obligatorio.',
            'last_name.required' => 'El apellido paterno es obligatorio.',
            'identification_number.required' => 'El número de identificación es obligatorio.',
            'identification_number.unique' => 'Este número de identificación ya está registrado.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'terms.accepted' => 'Debe aceptar los términos y condiciones.'
        ]);

        try {
            DB::transaction(function () use ($request, $invitation) {
                // Create user
                $user = User::create([
                    'name' => trim($request->first_name . ' ' . $request->last_name),
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'second_last_name' => $request->second_last_name,
                    'email' => $invitation->email,
                    'password' => Hash::make($request->password),
                    'identification_number' => $request->identification_number,
                    'phone_number' => $request->phone_number,
                    'technical_program_id' => $invitation->technical_program_id,
                    'is_active' => true,
                    'profile_completed' => true,
                    'password_changed' => true,
                    'profile_completed_at' => now(),
                    'invited_by' => $invitation->invited_by,
                    'email_verified_at' => now() // Auto-verify email since they clicked the invitation link
                ]);

                // Assign role
                $user->assignRole($invitation->role);

                // Mark invitation as used
                $invitation->markAsUsed();

                // Queue welcome email
                if (config('app.welcome_email_enabled', true)) {
                    SendWelcomeEmail::dispatch($user);
                }

                // Log the user in
                Auth::login($user);
            });

            return redirect()->route('dashboard')
                ->with('success', '¡Bienvenido! Tu cuenta ha sido creada exitosamente.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al completar el registro: ' . $e->getMessage());
        }
    }

    public function resend(UserInvitation $invitation)
    {
        if ($invitation->is_used) {
            return back()->with('error', 'Esta invitación ya fue utilizada.');
        }

        try {
            // Update expiration time
            $invitation->update([
                'expires_at' => now()->addHours(24)
            ]);

            // Resend email
            SendInvitationEmail::dispatch($invitation);

            return back()->with('success', 'Invitación reenviada a ' . $invitation->email);

        } catch (\Exception $e) {
            return back()->with('error', 'Error al reenviar la invitación: ' . $e->getMessage());
        }
    }

    public function cancel(UserInvitation $invitation)
    {
        if ($invitation->is_used) {
            return back()->with('error', 'No se puede cancelar una invitación ya utilizada.');
        }

        $invitation->delete();

        return back()->with('success', 'Invitación cancelada exitosamente.');
    }
}
