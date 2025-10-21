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
            $order->status = "pending";
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
            return redirect()->route("orders.show", $order->id)->with("success", "Pedido realizado com sucesso!");
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

    public function confirmDelivery(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->delivery_status = 'delivered';
        $order->save();

        return redirect()->back()->with('success', 'Entrega confirmada com sucesso!');
    }

    // Métodos create, edit, update, destroy podem ser implementados conforme necessário
    public function create() { /* ... */ }
    public function edit(string $id) { /* ... */ }
    public function update(Request $request, string $id) { /* ... */ }
    public function destroy(string $id) { /* ... */ }
}