<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $credentials['is_active'] = true; // Only allow active users

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();
            $user->updateLastLogin();

            // Check if user needs to complete profile
            if ($user->needsProfileCompletion()) {
                return redirect()->route('profile.complete')
                    ->with('info', 'Por favor completa tu perfil para continuar.');
            }

            return redirect()->intended(route('dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => ['Las credenciales proporcionadas no coinciden con nuestros registros o tu cuenta está inactiva.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
