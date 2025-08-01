<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole {
    public function handle(Request $request, Closure $next, ...$roles) {
        // Verifica si el usuario está logueado, si tiene un rol, y si ese rol está en la lista permitida
        if (!Auth::check() || !Auth::user()->rol || !in_array(Auth::user()->rol->nombre, $roles)) {
            abort(403, 'Acceso no autorizado.');
        }
        return $next($request);
    }
}