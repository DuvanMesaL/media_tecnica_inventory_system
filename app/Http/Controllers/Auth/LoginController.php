<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Máximo número de intentos antes del lockout.
     *
     * @var int
     */
    protected int $maxAttempts = 5;

    /**
     * Minutos de espera antes de volver a intentar.
     *
     * @var int
     */
    protected int $decayMinutes = 1;

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
        $credentials['is_active'] = true; // Solo usuarios activos

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Actualizar último login si el método existe
            if (method_exists($user, 'updateLastLogin')) {
                $user->updateLastLogin();
            }

            // Redirigir a completar perfil si es necesario
            if (method_exists($user, 'needsProfileCompletion') && $user->needsProfileCompletion()) {
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

    public function username()
    {
        return 'email';
    }

    protected function hasTooManyLoginAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request),
            $this->maxAttempts()
        );
    }

    protected function throttleKey(Request $request)
    {
        return strtolower($request->input($this->username())) . '|' . $request->ip();
    }

    protected function limiter()
    {
        return app(\Illuminate\Cache\RateLimiter::class);
    }

    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ])],
        ])->status(429);
    }

    protected function clearLoginAttempts(Request $request)
    {
        $this->limiter()->clear($this->throttleKey($request));
    }

    protected function fireLockoutEvent(Request $request)
    {
        event(new \Illuminate\Auth\Events\Lockout($request));
    }

    public function maxAttempts()
    {
        return $this->maxAttempts;
    }

    public function decayMinutes()
    {
        return $this->decayMinutes;
    }

    protected function authenticated(Request $request, $user)
    {
        if (method_exists($user, 'updateLastLogin')) {
            $user->updateLastLogin();
        }

        if (method_exists($user, 'needsProfileCompletion') && $user->needsProfileCompletion()) {
            return redirect()->route('profile.complete')
                ->with('info', 'Por favor completa tu perfil para continuar.');
        }

        return redirect()->intended(route('dashboard'));
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    protected function loggedOut(Request $request)
    {
        return redirect()->route('login');
    }

    protected function guard()
    {
        return Auth::guard();
    }
}
