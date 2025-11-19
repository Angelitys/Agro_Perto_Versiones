<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo(Request $request): ?string
    {
        // Se a requisição espera JSON (ex.: API), retorna null para permitir resposta JSON
        if ($request->expectsJson()) {
            return null;
        }

        // Redireciona para a rota 'login' se o usuário não estiver autenticado
        return route('login');
    }
}