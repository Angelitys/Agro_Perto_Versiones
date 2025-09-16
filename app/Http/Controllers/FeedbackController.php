<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth"]);
    }

    public function create(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Verifica se o feedback já foi enviado para este pedido
        if (Feedback::where("order_id", $order->id)->exists()) {
            return redirect()->route("orders.show", $order)->with("error", "Feedback já enviado para este pedido.");
        }

        $order->load("orderItems.product");
        return view("feedbacks.create", compact("order"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "order_id" => "required|exists:orders,id",
            "rating" => "required|integer|min:1|max:5",
            "comment" => "nullable|string|max:1000",
        ]);

        $order = Order::findOrFail($request->order_id);

        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Verifica se o feedback já foi enviado para este pedido
        if (Feedback::where("order_id", $order->id)->exists()) {
            return redirect()->route("orders.show", $order)->with("error", "Feedback já enviado para este pedido.");
        }

        // Para cada item do pedido, cria um feedback associado ao produto e ao produtor
        foreach ($order->orderItems as $item) {
            Feedback::create([
                "user_id" => Auth::id(),
                "order_id" => $order->id,
                "product_id" => $item->product_id,
                "producer_id" => $item->product->user_id,
                "rating" => $request->rating,
                "comment" => $request->comment,
            ]);
        }

        return redirect()->route("orders.show", $order)->with("success", "Feedback enviado com sucesso!");
    }
}

