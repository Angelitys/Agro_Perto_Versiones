<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|exists:users,id',
                'product_id' => 'required|integer|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            }

            // Buscar ou criar carrinho
            $cart = DB::table('carts')->where('user_id', $request->user_id)->first();
            if (!$cart) {
                $cartId = DB::table('carts')->insertGetId([
                    'user_id' => $request->user_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                $cartId = $cart->id;
            }

            // Buscar preço do produto
            $product = DB::table('products')->where('id', $request->product_id)->first();
            if (!$product) {
                return response()->json(['error' => 'Produto não encontrado'], 404);
            }

            // Adicionar item ao carrinho
            DB::table('cart_items')->insert([
                'cart_id' => $cartId,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'price' => $product->price,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            return response()->json(['message' => 'Produto adicionado ao carrinho!'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCart($userId)
    {
        try {
            $cartItems = DB::table('cart_items as ci')
                ->leftJoin('carts as c', 'ci.cart_id', '=', 'c.id')
                ->leftJoin('products as p', 'ci.product_id', '=', 'p.id')
                ->select('ci.*', 'p.name as product_name', 'p.price as product_price')
                ->where('c.user_id', $userId)
                ->get();

            return response()->json(['cart_items' => $cartItems]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

