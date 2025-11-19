<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    /**
     * Status do sistema
     */
    public function status()
    {
        return response()->json([
            'status' => 'OK',
            'message' => 'AgroPerto Marketplace - Sistema funcionando!',
            'timestamp' => now(),
            'version' => '1.0.0'
        ]);
    }

    /**
     * Listar categorias
     */
    public function categories()
    {
        $categories = Category::all();
        return response()->json([
            'categories' => $categories,
            'total' => $categories->count()
        ]);
    }

    /**
     * Listar produtos
     */
    public function products(Request $request)
    {
        $query = Product::with(['category', 'user']);
        
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $products = $query->get();
        
        return response()->json([
            'products' => $products,
            'total' => $products->count()
        ]);
    }

    /**
     * Criar produto
     */
    public function createProduct(Request $request)
    {
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock ?? 0,
            'stock_quantity' => $request->stock ?? 0,
            'unit' => $request->unit ?? 'kg',
            'category_id' => $request->category_id,
            'user_id' => $request->user_id ?? 1,
            'active' => true,
            'available_from' => $request->available_from ?? now(),
            'available_until' => $request->available_until ?? now()->addDays(30),
            'pickup_location' => $request->pickup_location,
            'pickup_instructions' => $request->pickup_instructions
        ]);

        return response()->json([
            'message' => 'Produto criado com sucesso!',
            'product' => $product
        ], 201);
    }

    /**
     * Listar usuários
     */
    public function users()
    {
        $users = User::all();
        return response()->json([
            'users' => $users,
            'total' => $users->count()
        ]);
    }

    /**
     * Criar usuário
     */
    public function createUser(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password ?? 'password'),
            'type' => $request->type ?? 'customer'
        ]);

        return response()->json([
            'message' => 'Usuário criado com sucesso!',
            'user' => $user
        ], 201);
    }

    /**
     * Listar pedidos
     */
    public function orders()
    {
        $orders = Order::with(['user', 'orderItems.product'])->get();
        return response()->json([
            'orders' => $orders,
            'total' => $orders->count()
        ]);
    }

    /**
     * Criar pedido
     */
    public function createOrder(Request $request)
    {
        $order = Order::create([
            'user_id' => $request->user_id ?? 1,
            'total_amount' => $request->total_amount,
            'status' => 'pending',
            'pickup_date' => $request->pickup_date ?? now()->addDays(1),
            'pickup_time' => $request->pickup_time ?? '10:00'
        ]);

        // Adicionar itens do pedido
        if ($request->has('items')) {
            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }
        }

        return response()->json([
            'message' => 'Pedido criado com sucesso!',
            'order' => $order->load(['orderItems.product'])
        ], 201);
    }

    /**
     * Dashboard de vendas
     */
    public function salesDashboard()
    {
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalUsers = User::count();
        $totalRevenue = Order::sum('total_amount');
        
        $recentOrders = Order::with(['user', 'orderItems.product'])
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'dashboard' => [
                'total_products' => $totalProducts,
                'total_orders' => $totalOrders,
                'total_users' => $totalUsers,
                'total_revenue' => $totalRevenue,
                'recent_orders' => $recentOrders
            ]
        ]);
    }

    /**
     * Confirmar entrega
     */
    public function confirmDelivery(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->update([
            'status' => 'delivered',
            'delivery_confirmed_at' => now(),
            'delivery_notes' => $request->notes
        ]);

        return response()->json([
            'message' => 'Entrega confirmada com sucesso!',
            'order' => $order
        ]);
    }

    /**
     * Funcionalidades implementadas
     */
    public function features()
    {
        return response()->json([
            'implemented_features' => [
                'venda_produtos' => 'Venda de produtos para produtores que estão na feira',
                'cadastrar_produtos' => 'Cadastrar produtos',
                'cadastrar_clientes' => 'Cadastrar Clientes',
                'cadastrar_vendedores' => 'Cadastrar Vendedores',
                'realizar_pedidos' => 'Realizar Pedidos',
                'lista_produtos_categoria' => 'Lista de itens com pesquisa por categoria',
                'agenda_retirada' => 'Agenda de retirada dos produtos conforme a data de disponibilidade',
                'confirmacao_entrega' => 'Confirmação de entrega',
                'feedback_usuarios' => 'Feedback dos usuários com o produto e o produtor',
                'dashboard_vendas' => 'Dashboard de vendas para o produtor, com relatórios',
                'avaliacao_clientes' => 'Avaliação de clientes que compram e não vão buscar'
            ],
            'pending_features' => [
                'notificacoes_email' => 'Envio de notificações por email',
                'notificacoes_whatsapp' => 'Notificações por WhatsApp',
                'termos_uso' => 'Termos de uso / legalidade'
            ]
        ]);
    }
}
