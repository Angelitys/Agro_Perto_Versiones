<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Exibir a página inicial
     */
    public function index()
    {
        // Buscar categorias ativas com contagem de produtos
        $categories = Category::active()
            ->withCount(['products' => function($query) {
                $query->active()->inStock();
            }])
            ->take(6)
            ->get();
        
        // Buscar produtos em destaque (mais recentes e ativos)
        $featuredProducts = Product::active()
            ->inStock()
            ->with(['category', 'user'])
            ->latest()
            ->take(8)
            ->get();
        
        // Buscar produtos por categoria (se especificada)
        $categoryProducts = [];
        if (request('category')) {
            $category = Category::where('slug', request('category'))->first();
            if ($category) {
                $categoryProducts = $category->products()
                    ->active()
                    ->inStock()
                    ->with(['category', 'user'])
                    ->paginate(12);
            }
        }

        return view('home', compact('categories', 'featuredProducts', 'categoryProducts'));
    }

    /**
     * Buscar produtos
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        $categoryId = $request->get('category');
        
        $products = Product::active()
            ->inStock()
            ->with(['category', 'user']);
        
        if ($query) {
            $products->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            });
        }
        
        if ($categoryId) {
            $products->where('category_id', $categoryId);
        }
        
        $products = $products->paginate(12);
        $categories = Category::active()->get();
        
        return view('products.search', compact('products', 'categories', 'query', 'categoryId'));
    }
}
