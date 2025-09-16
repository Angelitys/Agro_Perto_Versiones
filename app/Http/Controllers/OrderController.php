<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Notifications\OrderPlaced;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route("cart.index")->with("error", "Seu carrinho está vazio.");
        }

        $request->validate([
            'pickup_date' => 'required|date|after_or_equal:today',
        ]);

        DB::beginTransaction();
        try {
            $order = new Order();
            
            
            \Log::info('OrderController@store: Cart total before saving order', ['cart_total' => $cart->total]);
            \Log::info('OrderController@store: Pickup date before saving order', ['pickup_date' => $request->input("pickup_date")]);

            $order->user_id = $user->id;
            $order->total_amount = $cart->total;
            $order->status = "pending"; // Ou outro status inicial
            $order->pickup_date = $request->input("pickup_date");
            $order->delivery_status = "pending";
            // Adicionar um endereço de entrega padrão ou obtê-lo da requisição
            // Por enquanto, usaremos um valor padrão para resolver o erro SQL
            $order->shipping_address = json_encode([
                'street' => 'Endereço Padrão',
                'number' => '123',
                'neighborhood' => 'Bairro Padrão',
                'city' => 'Cidade Padrão',
                'state' => 'Estado Padrão',
                'zip_code' => '00000-000'
            ]);
            $order->save();

            \Log::info('OrderController@store: Order saved successfully', ['order_id' => $order->id]);

            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    "order_id" => $order->id,
                    "product_id" => $cartItem->product_id,
                    "quantity" => $cartItem->quantity,
                    "price" => $cartItem->product->price,
                ]);
            }

            // Limpar o carrinho
            $cart->items()->delete();

            // Enviar notificação
            $user->notify(new OrderPlaced($order));

            DB::commit();

            return redirect()->route("orders.show", $order)->with("success", "Pedido realizado com sucesso!");
        } catch (\Exception $e) {
            DB::rollBack();
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

