<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DeliveryController extends Controller
{
    /**
     * Marcar pedido como entregue (para produtores)
     */
    public function markAsDelivered(Request $request, Order $order)
    {
        // Verificar se o usuário é o produtor de algum item do pedido
        $isProducer = $order->orderItems()->whereHas('product', function($query) {
            $query->where('user_id', Auth::id());
        })->exists();

        if (!$isProducer) {
            abort(403, 'Você não tem permissão para marcar este pedido como entregue.');
        }

        $request->validate([
            'delivery_notes' => 'nullable|string|max:1000',
            'delivery_photo' => 'nullable|image|max:2048'
        ]);

        $deliveryPhoto = null;
        if ($request->hasFile('delivery_photo')) {
            $deliveryPhoto = $request->file('delivery_photo')->store('deliveries', 'public');
        }

        $order->update([
            'delivery_status' => 'delivered',
            'delivered_at' => now(),
            'delivery_notes' => $request->delivery_notes,
            'delivery_photo' => $deliveryPhoto
        ]);

        return back()->with('success', 'Pedido marcado como entregue!');
    }

    /**
     * Confirmar recebimento (para clientes)
     */
    public function confirmReceived(Request $request, Order $order)
    {
        // Verificar se o usuário é o dono do pedido
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para confirmar este pedido.');
        }

        $request->validate([
            'customer_feedback' => 'nullable|string|max:1000',
            'delivery_rating' => 'required|integer|min:1|max:5'
        ]);

        $order->update([
            'customer_confirmed' => true,
            'customer_confirmed_at' => now(),
            'customer_feedback' => $request->customer_feedback,
            'delivery_rating' => $request->delivery_rating,
            'status' => 'completed'
        ]);

        return back()->with('success', 'Recebimento confirmado! Obrigado pelo seu feedback.');
    }

    /**
     * Exibir página de confirmação de entrega
     */
    public function showConfirmation(Order $order)
    {
        // Verificar se o usuário é o dono do pedido
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Verificar se o pedido foi entregue mas ainda não confirmado
        if ($order->delivery_status !== 'delivered' || $order->customer_confirmed) {
            return redirect()->route('orders.show', $order)
                ->with('info', 'Este pedido não está disponível para confirmação.');
        }

        return view('orders.confirm-delivery', compact('order'));
    }

    /**
     * Listar entregas pendentes (para produtores)
     */
    public function pendingDeliveries()
    {
        $user = Auth::user();
        
        // Buscar pedidos com produtos do produtor que estão prontos para entrega
        $orders = Order::whereHas('orderItems.product', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->where('status', 'confirmed')
        ->where('delivery_status', 'pending')
        ->with(['user', 'orderItems.product'])
        ->orderBy('pickup_date')
        ->paginate(10);

        return view('deliveries.pending', compact('orders'));
    }

    /**
     * Histórico de entregas (para produtores)
     */
    public function deliveryHistory()
    {
        $user = Auth::user();
        
        $orders = Order::whereHas('orderItems.product', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->where('delivery_status', 'delivered')
        ->with(['user', 'orderItems.product'])
        ->orderBy('delivered_at', 'desc')
        ->paginate(10);

        return view('deliveries.history', compact('orders'));
    }
}

