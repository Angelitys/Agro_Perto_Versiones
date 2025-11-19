<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class SimpleHomeController extends Controller
{
    /**
     * Exibir a página inicial
     */
    public function index()
    {
        // Buscar categorias
        $categories = Category::all();
        
        // Buscar produtos
        $products = Product::with(['category', 'user'])->get();
        
        return response()->json([
            'message' => 'AgroPerto Marketplace - Sistema funcionando!',
            'categories' => $categories,
            'products' => $products,
            'status' => 'OK'
        ]);
    }

    /**
     * Buscar produtos
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        $categoryId = $request->get('category');
        
        $products = Product::with(['category', 'user']);
        
        if ($query) {
            $products->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            });
        }
        
        if ($categoryId) {
            $products->where('category_id', $categoryId);
        }
        
        $products = $products->get();
        $categories = Category::all();
        
        return response()->json([
            'products' => $products,
            'categories' => $categories,
            'query' => $query,
            'categoryId' => $categoryId
        ]);
    }
}
