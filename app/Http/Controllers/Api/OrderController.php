<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index()
    {
        try {
            $orders = DB::table("orders as o")
                ->leftJoin("users as u", "o.user_id", "=", "u.id")
                ->select("o.*", "u.name as customer_name")
                ->orderBy("o.created_at", "desc")
                ->get();

            return response()->json(["orders" => $orders]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "user_id" => "required|integer|exists:users,id",
                "items" => "required|array|min:1",
                "items.*.product_id" => "required|integer|exists:products,id",
                "items.*.quantity" => "required|integer|min:1",
            ]);

            if ($validator->fails()) {
                return response()->json(["error" => $validator->errors()->first()], 400);
            }

            DB::beginTransaction();

            $total = 0;
            foreach ($request->items as $item) {
                $product = DB::table("products")->where("id", $item["product_id"])->first();
                if (!$product) {
                    throw new \Exception("Produto com ID " . $item["product_id"] . " não encontrado.");
                }
                $total += $product->price * $item["quantity"];
            }

            $orderId = DB::table("orders")->insertGetId([
                "user_id" => $request->user_id,
                "order_number" => "ORD-" . time() . "-" . uniqid(),
                "total_amount" => $total,
                "status" => "pending",
                "payment_method" => $request->payment_method ?? "cash",
                "pickup_date" => $request->pickup_date,
                "notes" => $request->notes,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ]);

            foreach ($request->items as $item) {
                $product = DB::table("products")->where("id", $item["product_id"])->first();
                DB::table("order_items")->insert([
                    "order_id" => $orderId,
                    "product_id" => $item["product_id"],
                    "quantity" => $item["quantity"],
                    "product_name" => $product->name,
                    "product_price" => $product->price,
                    "subtotal" => $item["quantity"] * $product->price,
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                ]);
            }

            DB::commit();

            return response()->json([
                "message" => "Pedido criado com sucesso!",
                "order_id" => $orderId
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $order = DB::table("orders as o")
                ->leftJoin("users as u", "o.user_id", "=", "u.id")
                ->select("o.*", "u.name as customer_name")
                ->where("o.id", $id)
                ->first();

            if (!$order) {
                return response()->json(["error" => "Pedido não encontrado"], 404);
            }

            $orderItems = DB::table("order_items")
                ->where("order_id", $id)
                ->get();

            return response()->json(["order" => $order, "items" => $orderItems]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function updatePickupSchedule(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                "pickup_date" => "required|date",
            ]);

            if ($validator->fails()) {
                return response()->json(["error" => $validator->errors()->first()], 400);
            }

            $order = DB::table("orders")->where("id", $id)->first();
            if (!$order) {
                return response()->json(["error" => "Pedido não encontrado"], 404);
            }

            DB::table("orders")->where("id", $id)->update([
                "pickup_date" => $request->pickup_date,
                "updated_at" => Carbon::now(),
            ]);

            return response()->json(["message" => "Agendamento de recolha atualizado com sucesso!"]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function confirmDelivery($id)
    {
        try {
            $order = DB::table("orders")->where("id", $id)->first();
            if (!$order) {
                return response()->json(["error" => "Pedido não encontrado"], 404);
            }

            DB::table("orders")->where("id", $id)->update([
                "status" => "delivered",
                "updated_at" => Carbon::now(),
            ]);

            return response()->json(["message" => "Entrega confirmada com sucesso!"]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
}

