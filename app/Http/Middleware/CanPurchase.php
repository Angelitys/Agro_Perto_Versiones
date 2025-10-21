<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CanPurchase
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado para comprar.');
        }
        
        if (auth()->user()->type !== 'consumer') {
            return redirect()->back()->with('error', 'Apenas consumidores podem comprar produtos.');
        }
        
        return $next($request);
    }
}