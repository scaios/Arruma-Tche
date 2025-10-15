<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
public function handle(Request $request, Closure $next): Response
{
    // 1. Verifica se o usuário está logado E se o cargo (role) dele é 'admin'.
    if (auth()->check() && auth()->user()->role === 'admin') {
        // Se for admin, deixa ele prosseguir para a próxima etapa (o controller).
        return $next($request);
    }

    // 2. Se não for admin, bloqueia o acesso com uma página de erro "403 Acesso Negado".
    abort(403, 'Acesso Negado. Esta área é restrita para administradores.');
}
}
