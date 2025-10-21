<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    /**
     * Display a listing of the producer's sales.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Verificar se o usuário é um produtor
        if ($user->type !== 'producer') {
            abort(403, 'Acesso negado. Apenas produtores podem acessar esta página.');
        }
        
        // Buscar todos os pedidos que contêm produtos do produtor logado
        $sales = Order::whereHas('orderItems.product', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['orderItems.product', 'user'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        
        // Calcular estatísticas de vendas
        $totalSales = Order::whereHas('orderItems.product', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();
        
        $totalRevenue = OrderItem::whereHas('product', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->sum(DB::raw('quantity * price'));
        
        $pendingSales = Order::where('status', 'pending')
            ->whereHas('orderItems.product', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count();
        
        $completedSales = Order::where('status', 'delivered')
            ->whereHas('orderItems.product', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count();
        
        return view('sales.index', compact(
            'sales',
            'totalSales',
            'totalRevenue',
            'pendingSales',
            'completedSales'
        ));
    }
    
    /**
     * Display the specified sale.
     */
    public function show(Order $order)
    {
        $user = Auth::user();
        
        // Verificar se o usuário é um produtor
        if ($user->type !== 'producer') {
            abort(403, 'Acesso negado. Apenas produtores podem acessar esta página.');
        }
        
        // Verificar se o pedido contém produtos do produtor logado
        $hasProducerProducts = $order->orderItems()->whereHas('product', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->exists();
        
        if (!$hasProducerProducts) {
            abort(404, 'Pedido não encontrado ou não pertence a você.');
        }
        
        // Carregar apenas os itens do pedido que pertencem ao produtor
        $order->load(['orderItems' => function ($query) use ($user) {
            $query->whereHas('product', function ($subQuery) use ($user) {
                $subQuery->where('user_id', $user->id);
            });
        }, 'orderItems.product', 'user']);
        
        return view('sales.show', compact('order'));
    }
    
    /**
     * Update the status of a sale item.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $user = Auth::user();
        
        // Verificar se o usuário é um produtor
        if ($user->type !== 'producer') {
            abort(403, 'Acesso negado.');
        }
        
        // Verificar se o pedido contém produtos do produtor logado
        $hasProducerProducts = $order->orderItems()->whereHas('product', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->exists();
        
        if (!$hasProducerProducts) {
            abort(404, 'Pedido não encontrado ou não pertence a você.');
        }
        
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);
        
        // Atualizar o status do pedido
        $order->update([
            'status' => $request->status
        ]);
        
        return redirect()->route('sales.show', $order)
            ->with('success', 'Status do pedido atualizado com sucesso!');
    }
}

