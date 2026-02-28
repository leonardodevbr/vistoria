<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SimplePasswordMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $senha = 'vistoria2024';
        
        if (session('autenticado') !== true) {
            if ($request->is('login') || $request->isMethod('post') && $request->path() === 'login') {
                if ($request->isMethod('post')) {
                    if ($request->input('senha') === $senha) {
                        session(['autenticado' => true]);
                        return redirect('/');
                    } else {
                        return redirect('/login')->with('erro', 'Senha incorreta!');
                    }
                }
                return $next($request);
            }
            
            return redirect('/login');
        }
        
        return $next($request);
    }
}
