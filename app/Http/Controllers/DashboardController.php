<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->type === 'producer') {
            return $this->producerDashboard($user);
        } else {
            return $this->consumerDashboard($user);
        }
    }
    
    private function producerDashboard($user)
    {
        // Estatísticas do produtor
        $totalProducts = Product::where('user_id', $user->id)->count();
        $activeProducts = Product::where('user_id', $user->id)->where('active', true)->count();
        $totalStock = Product::where('user_id', $user->id)->sum('stock_quantity');
        $lowStockProducts = Product::where('user_id', $user->id)
            ->where('stock_quantity', '<=', 5)
            ->where('active', true)
            ->count();
        
        // Produtos recentes
        $recentProducts = Product::where('user_id', $user->id)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Produtos com baixo estoque
        $lowStockProductsList = Product::where('user_id', $user->id)
            ->where('stock_quantity', '<=', 5)
            ->where('active', true)
            ->with('category')
            ->take(5)
            ->get();
        
        // Pedidos recentes (se implementado)
        $recentOrders = collect(); // Placeholder para futuras implementações
        
        return view('dashboard', compact(
            'user',
            'totalProducts',
            'activeProducts',
            'totalStock',
            'lowStockProducts',
            'recentProducts',
            'lowStockProductsList',
            'recentOrders'
        ));
    }
    
    private function consumerDashboard($user)
    {
        // Estatísticas do consumidor
        $totalOrders = 0; // Placeholder para futuras implementações
        $favoriteProducts = collect(); // Placeholder para futuras implementações
        
        // Produtos recentes adicionados ao marketplace
        $recentProducts = Product::active()
            ->inStock()
            ->with(['category', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
        
        // Categorias populares
        $popularCategories = Category::active()
            ->withCount(['products' => function ($query) {
                $query->active()->inStock();
            }])
            ->orderBy('products_count', 'desc')
            ->take(6)
            ->get();
        
        return view('dashboard', compact(
            'user',
            'totalOrders',
            'favoriteProducts',
            'recentProducts',
            'popularCategories'
        ));
    }
}
