<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\PickupSchedule;
use App\Models\User;
use App\Models\Category;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $notificationService;

    public function __construct()
    {
        // Inicializar o serviço apenas quando necessário (mantido como está)
    }

    private function getNotificationService()
    {
        if (!$this->notificationService) {
            $this->notificationService = app(NotificationService::class);
        }
        return $this->notificationService;
    }

    /**
     * Dashboard principal
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->type === 'producer') {
            return $this->producerDashboard();
        } elseif ($user->type === 'consumer') {
            return $this->consumerDashboard();
        }

        return $this->adminDashboard();
    }

    /**
     * Dashboard do produtor
     */
    private function producerDashboard()
    {
        $user = Auth::user();

        // Estatísticas básicas
        $totalProducts = Product::where('user_id', $user->id)->count();
        $activeProducts = Product::where('user_id', $user->id)->where('active', true)->count();
        $totalStock = Product::where('user_id', $user->id)->sum('stock_quantity');
        $lowStockProducts = Product::where('user_id', $user->id)
            ->where('stock_quantity', '<=', 5)
            ->where('active', true)
            ->count();

        // Dados simulados (placeholders)
        $monthlyRevenue = 0;
        $lowStockProductsList = Product::where('user_id', $user->id)
            ->where('active', true)
            ->where('stock_quantity', '<=', 5)
            ->with('category')
            ->orderBy('stock_quantity', 'asc')
            ->limit(5)
            ->get();
        $recentProducts = Product::where('user_id', $user->id)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $upcomingPickups = collect();
        $salesChart = [];
        $topProducts = collect();
        $unreadNotifications = collect();

        // Teste para isolar o erro: Adicione log ou retorno simples
        \Illuminate\Support\Facades\Log::info('Acessando producerDashboard para user ID: ' . $user->id);

        return view('dashboard-simple', compact(
            'user',
            'totalProducts',
            'activeProducts',
            'totalStock',
            'lowStockProducts',
            'monthlyRevenue',
            'lowStockProductsList',
            'recentProducts',
            'upcomingPickups',
            'salesChart',
            'topProducts',
            'unreadNotifications'
        ));
    }

    /**
     * Dashboard do consumidor
     */
    private function consumerDashboard()
    {
        $user = Auth::user();

        // Estatísticas básicas (placeholders)
        $totalOrders = 0;
        $pendingOrders = 0;
        $completedOrders = 0;
        $totalSpent = 0;

        // Dados simulados
        $recentOrders = collect();
        $upcomingPickups = collect();
        $favoriteProducts = collect();
        $recentProducts = Product::active()
            ->inStock()
            ->with(['category', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
        $popularCategories = Category::active()
            ->withCount(['products' => function ($query) {
                $query->active()->inStock();
            }])
            ->orderBy('products_count', 'desc')
            ->take(6)
            ->get();
        $unreadNotifications = collect();

        // Teste para isolar o erro
        \Illuminate\Support\Facades\Log::info('Acessando consumerDashboard para user ID: ' . $user->id);

        return view('dashboard-simple', compact(
            'user',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalSpent',
            'recentOrders',
            'upcomingPickups',
            'favoriteProducts',
            'recentProducts',
            'popularCategories',
            'unreadNotifications'
        ));
    }

    /**
     * Dashboard do administrador
     */
    private function adminDashboard()
    {
        // Estatísticas gerais
        $stats = [
            'total_users' => User::count(),
            'total_producers' => User::where('type', 'producer')->count(),
            'total_consumers' => User::where('type', 'consumer')->count(),
            'total_products' => Product::count(),
            'active_products' => Product::active()->count(),
            'total_orders' => 0, // Placeholder
            'pending_orders' => 0, // Placeholder
        ];

        // Dados simulados
        $monthlyRevenue = 0;
        $recentUsers = User::orderBy('created_at', 'desc')->limit(5)->get();
        $recentOrders = collect();

        return view('dashboard.admin', compact(
            'stats',
            'monthlyRevenue',
            'recentUsers',
            'recentOrders'
        ));
    }

    /**
     * Relatório de vendas
     */
    public function salesReport(Request $request)
    {
        $user = Auth::user();

        if ($user->type !== 'producer') {
            abort(403, 'Acesso negado.');
        }

        $startDate = $request->get('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $period = $request->get('period', 'daily');

        // Dados simulados
        $sales = collect();
        $groupedSales = collect();
        $periodStats = [
            'total_orders' => 0,
            'total_revenue' => 0,
            'average_order_value' => 0,
            'total_items_sold' => 0,
        ];
        $topProducts = collect();
        $topCustomers = collect();

        return view('dashboard.sales-report', compact(
            'sales',
            'groupedSales',
            'periodStats',
            'topProducts',
            'topCustomers',
            'startDate',
            'endDate',
            'period'
        ));
    }

    // Métodos privados auxiliares (mantidos como placeholders)
    private function getSalesChartData($producerId, $days = 30)
    {
        $chartData = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartData[] = [
                'date' => $date,
                'orders' => 0,
                'revenue' => 0,
            ];
        }
        return $chartData;
    }

    private function getTopProducts($producerId, $limit = 5, $startDate = null, $endDate = null)
    {
        return collect();
    }

    private function getTopCustomers($producerId, $limit = 5, $startDate = null, $endDate = null)
    {
        return collect();
    }

    private function getFavoriteProducts($userId, $limit = 5)
    {
        return collect();
    }
}