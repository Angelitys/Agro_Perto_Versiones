<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Product;


class ProductController extends Controller
{
    public function products(Request $request)
    {
        try {
            $query = DB::table("products as p")
                ->leftJoin("categories as c", "p.category_id", "=", "c.id")
                ->leftJoin("users as u", "p.user_id", "=", "u.id")
                ->select(
                    "p.*",
                    "c.name as category_name",
                    "u.name as producer_name"
                )
                ->where("p.active", true);

            // Filtro por categoria
            if ($request->has("category_id")) {
                $query->where("p.category_id", $request->category_id);
            }

            // Pesquisa por nome
            if ($request->has("search")) {
                $query->where("p.name", "like", "%" . $request->search . "%");
            }

            // Produtos disponíveis hoje
            if ($request->has("available_today")) {
                $today = Carbon::now();
                $query->where(function($q) use ($today) {
                    $q->whereNull("p.available_from")
                      ->orWhere("p.available_from", "<=", $today);
                })->where(function($q) use ($today) {
                    $q->whereNull("p.available_until")
                      ->orWhere("p.available_until", ">=", $today);
                });
            }

            $products = $query->orderBy("p.created_at", "desc")->get();
            
            return response()->json(["products" => $products]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function product($id)
    {
        try {
            $product = DB::table("products as p")
                ->leftJoin("categories as c", "p.category_id", "=", "c.id")
                ->leftJoin("users as u", "p.user_id", "=", "u.id")
                ->select(
                    "p.*",
                    "c.name as category_name",
                    "u.name as producer_name"
                )
                ->where("p.id", $id)
                ->first();

            if (!$product) {
                return response()->json(["error" => "Produto não encontrado"], 404);
            }

            return response()->json(["product" => $product]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function addProduct(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "name" => "required|string|max:255",
                "description" => "required|string",
                "price" => "required|numeric|min:0",
                "stock_quantity" => "required|integer|min:0",
                "unit" => "required|string",
                "category_id" => "required|integer|exists:categories,id",
                "user_id" => "required|integer|exists:users,id"
            ]);

            if ($validator->fails()) {
                return response()->json(["error" => $validator->errors()->first()], 400);
            }

            $slug = strtolower(str_replace(" ", "-", $request->name));

            $product = \App\Models\Product::create([
                "name" => $request->name,
                "slug" => $slug,
                "description" => $request->description,
                "price" => $request->price,
                "stock_quantity" => $request->stock_quantity,
                "unit" => $request->unit,
                "category_id" => $request->category_id,
                "user_id" => $request->user_id,
                "origin" => $request->origin,
                "fair_location" => $request->fair_location,
                "harvest_date" => $request->harvest_date,
                "available_from" => $request->available_from,
                "available_until" => $request->available_until,
                "active" => true,
            ]);

            $productId = $product->id;

            return response()->json([
                "message" => "Produto adicionado com sucesso!",
                "product_id" => $productId
            ], 201);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function updateProduct(Request $request, $id)
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                return response()->json(["error" => "Produto não encontrado"], 404);
            }

            $validator = Validator::make($request->all(), [
                "name" => "sometimes|string|max:255",
                "description" => "sometimes|string",
                "price" => "sometimes|numeric|min:0",
                "stock_quantity" => "sometimes|integer|min:0",
                "unit" => "sometimes|string",
                "category_id" => "sometimes|integer|exists:categories,id",
                "user_id" => "sometimes|integer|exists:users,id",
                "active" => "sometimes|boolean"
            ]);

            if ($validator->fails()) {
                return response()->json(["error" => $validator->errors()->first()], 400);
            }

            $product->update($request->all());

            return response()->json(["message" => "Produto atualizado com sucesso!", "product" => $product]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function deleteProduct($id)
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                return response()->json(["error" => "Produto não encontrado"], 404);
            }

            $product->delete();

            return response()->json(["message" => "Produto excluído com sucesso!"]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function productsByCategory($category_id)
    {
        try {
            $products = DB::table("products as p")
                ->leftJoin("categories as c", "p.category_id", "=", "c.id")
                ->leftJoin("users as u", "p.user_id", "=", "u.id")
                ->select(
                    "p.*",
                    "c.name as category_name",
                    "u.name as producer_name"
                )
                ->where("p.active", true)
                ->where("p.category_id", $category_id)
                ->orderBy("p.created_at", "desc")
                ->get();

            if ($products->isEmpty()) {
                return response()->json(["message" => "Nenhum produto encontrado para esta categoria."], 404);
            }

            return response()->json(["products" => $products]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
}

