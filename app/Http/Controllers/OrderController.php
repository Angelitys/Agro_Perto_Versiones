<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Notifications\OrderPlaced;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct()
    {
        // O middleware 'auth' já é aplicado nas rotas para este controlador.
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Auth::user()->orders()->with("orderItems.product")->latest()->get();
        return view("orders.index")->with("orders", $orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $cart = $user->cart;

        Log::info('Starting order process', ['user_id' => $user->id, 'cart_id' => $cart->id, 'current_time' => now()->toDateTimeString()]);

        // Forçar o carregamento dos itens do carrinho
        $cart->load('items');
        Log::info('Items loaded', ['items_count' => $cart->items->count()]);

        if (!$cart || $cart->items->isEmpty()) {
            Log::warning('Cart is empty or invalid', ['cart_id' => $cart->id, 'items_count' => $cart->items->count()]);
            return redirect()->route("cart.index")->with("error", "Seu carrinho está vazio.");
        }

        // Log dos dados brutos do request para depuração
        Log::debug('Raw request data', ['input' => $request->all()]);

        Log::info('Validation starting', ['request_data' => $request->all()]);
        try {
            $request->validate([
                'pickup_date' => 'required|date|after_or_equal:' . now()->toDateString(),
                'pickup_time' => 'required|string',
                'pickup_notes' => 'nullable|string|max:500',
                'payment_method' => 'required|in:cash,pix',
            ]);
            Log::info('Validation passed', ['request_data' => $request->all()]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors(), 'request_data' => $request->all()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        DB::beginTransaction();
        try {
            $order = new Order();
            Log::info('Creating order', ['cart_total' => $cart->total, 'pickup_date' => $request->input("pickup_date")]);

            $order->user_id = $user->id;
            $order->total_amount = $cart->total;
            $order->status = "awaiting_confirmation"; // Aguardando confirmação do produtor
            $order->pickup_date = $request->input("pickup_date");
            $order->pickup_time = $request->input("pickup_time");
            $order->pickup_notes = $request->input("pickup_notes");
            $order->payment_method = $request->input("payment_method");
            $order->delivery_status = "pending";
            $order->shipping_address = json_encode([
                'street' => 'Endereço Padrão',
                'number' => '123',
                'neighborhood' => 'Bairro Padrão',
                'city' => 'Cidade Padrão',
                'state' => 'Estado Padrão',
                'zip_code' => '00000-000'
            ]);
            $order->save();
            Log::info('Order saved successfully', ['order_id' => $order->id]);

            foreach ($cart->items as $cartItem) {
                $product = $cartItem->product;
                $subtotal = $cartItem->quantity * $cartItem->price;
                OrderItem::create([
                    "order_id" => $order->id,
                    "product_id" => $cartItem->product_id,
                    "product_name" => $product->name,
                    "product_price" => $cartItem->price,
                    "quantity" => $cartItem->quantity,
                    "subtotal" => $subtotal,
                ]);
            }

            $cart->items()->delete();
            $cart->total = 0;
            $cart->save();

            $user->notify(new OrderPlaced($order));
            $notificationService = new \App\Services\ProducerNotificationService();
            $notificationService->notifyProducersAboutNewOrder($order);

            DB::commit();
            Log::info('Order process completed', ['order_id' => $order->id]);
            return redirect()->route("orders.awaiting-confirmation", $order->id)->with("success", "Pedido enviado para análise do produtor!");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing order', ['error' => $e->getMessage(), 'request' => $request->all()]);
            return redirect()->route("cart.index")->with("error", "Erro ao finalizar o pedido: " . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        $order->load("orderItems.product");
        return view("orders.show")->with("order", $order);
    }

    /**
     * Exibir tela de pedido aguardando confirmação
     */
    public function awaitingConfirmation(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Se o pedido já foi confirmado ou rejeitado, redirecionar para a view normal
        if ($order->status !== 'awaiting_confirmation') {
            return redirect()->route('orders.show', $order->id);
        }
        
        $order->load("orderItems.product.user");
        return view("orders.awaiting-confirmation")->with("order", $order);
    }

    public function confirmDelivery(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->delivery_status = 'delivered';
        $order->save();

        return redirect()->back()->with('success', 'Entrega confirmada com sucesso!');
    }

    /**
     * Marca o pedido como "Não Compareceu" (Teste 11).
     * Apenas o produtor pode fazer isso.
     */
    public function markNoShow(Order $order)
    {
        // Verifica se o usuário é produtor
        if (Auth::user()->type !== 'producer') {
            abort(403, 'Acesso negado.');
        }

        // Verifica se o produtor está relacionado a algum item do pedido
        $isProducerForOrder = $order->orderItems()->whereHas('product', function ($query) {
            $query->where('user_id', Auth::id());
        })->exists();

        if (!$isProducerForOrder) {
            abort(403, 'Você não é o produtor responsável por este pedido.');
        }

        // 1. Altera o status do pedido para 'no_show'
        $order->delivery_status = 'no_show';
        $order->save();

        // 2. Aplica avaliação negativa no histórico do consumidor (simulação)
        // Em um sistema real, isso afetaria a reputação do usuário
        $order->user->no_show_count = ($order->user->no_show_count ?? 0) + 1;
        $order->user->save();
        
        // 3. Notifica o consumidor (simulação)
        // O NotificationService real faria isso
        // $notificationService = app(\App\Services\NotificationService::class);
        // $notificationService->notifyNoShow($order);

        return redirect()->back()->with('warning', 'Pedido marcado como "Não Compareceu". O histórico do cliente foi atualizado.');
    }

    /**
     * Produtor confirma o pedido
     */
    public function confirmOrder(Order $order)
    {
        // Verifica se o usuário é produtor
        if (Auth::user()->type !== 'producer') {
            abort(403, 'Acesso negado.');
        }

        // Verifica se o produtor está relacionado a algum item do pedido
        $isProducerForOrder = $order->orderItems()->whereHas('product', function ($query) {
            $query->where('user_id', Auth::id());
        })->exists();

        if (!$isProducerForOrder) {
            abort(403, 'Você não é o produtor responsável por este pedido.');
        }

        // Atualiza o status do pedido
        $order->status = 'confirmed';
        $order->producer_confirmed_at = now();
        $order->save();

        // Notificar o cliente
        $order->user->notify(new \App\Notifications\OrderConfirmed($order));

        return redirect()->back()->with('success', 'Pedido confirmado com sucesso! O cliente foi notificado.');
    }

    /**
     * Produtor rejeita o pedido
     */
    public function rejectOrder(Request $request, Order $order)
    {
        // Verifica se o usuário é produtor
        if (Auth::user()->type !== 'producer') {
            abort(403, 'Acesso negado.');
        }

        // Verifica se o produtor está relacionado a algum item do pedido
        $isProducerForOrder = $order->orderItems()->whereHas('product', function ($query) {
            $query->where('user_id', Auth::id());
        })->exists();

        if (!$isProducerForOrder) {
            abort(403, 'Você não é o produtor responsável por este pedido.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        // Atualiza o status do pedido
        $order->status = 'rejected';
        $order->producer_rejection_reason = $request->rejection_reason;
        $order->save();

        // Notificar o cliente
        $order->user->notify(new \App\Notifications\OrderRejected($order));

        return redirect()->back()->with('warning', 'Pedido rejeitado. O cliente foi notificado.');
    }

    // Métodos create, edit, update, destroy podem ser implementados conforme necessário
    public function create() { /* ... */ }
    public function edit(string $id) { /* ... */ }
    public function update(Request $request, string $id) { /* ... */ }
    public function destroy(string $id) { /* ... */ }
}