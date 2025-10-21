<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cart = $user->cart;

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Seu carrinho está vazio.');
        }

        return view('checkout.simple-index', compact('cart'));
    }

    public function store(Request $request)
    {
        $orderController = app(\App\Http\Controllers\OrderController::class);
        $response = $orderController->store($request);

        // Se o OrderController não redirecionar, forçar um fallback
        if (!is_a($response, \Illuminate\Http\RedirectResponse::class)) {
            \Illuminate\Support\Facades\Log::warning('OrderController não retornou redirecionamento. Verifique a lógica.');
            return redirect()->route('order.confirmation')->with('error', 'Erro ao processar a compra.');
        }

        return $response;
    }
}