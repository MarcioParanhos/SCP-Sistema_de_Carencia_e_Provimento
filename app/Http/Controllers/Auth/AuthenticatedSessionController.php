<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{

    protected function redirectTo() {
        return '/';
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Verifica o perfil do usuário após o login
        if (Auth::check() && Auth::user()->profile === "cad_tecnico") {
            return redirect()->route('aposentadorias.show');
        }

        if (Auth::check() && Auth::user()->name === "Usuario CPM") {
            return redirect()->route('aposentadorias.show');
        }

        if (Auth::check() && Auth::user()->profile === "cgi_tecnico") {
            return redirect()->route('regularizacao_funcional.show');
        }

        // NTE users will follow the default redirect (RouteServiceProvider::HOME -> '/')

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}