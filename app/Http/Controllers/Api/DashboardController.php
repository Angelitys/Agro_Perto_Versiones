<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function salesOverview(Request $request)
    {
        try {
            $totalSales = DB::table("orders")->where("status", "delivered")->sum("total_amount");
            $totalOrders = DB::table("orders")->count();
            $totalProducts = DB::table("products")->count();
            $totalUsers = DB::table("users")->count();

            return response()->json([
                "total_sales" => $totalSales,
                "total_orders" => $totalOrders,
                "total_products" => $totalProducts,
                "total_users" => $totalUsers,
            ]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function salesByPeriod(Request $request)
    {
        try {
            $period = $request->query("period", "month"); // day, week, month, year
            $salesData = [];

            switch ($period) {
                case "day":
                    $salesData = DB::table("orders")
                        ->select(DB::raw("DATE(created_at) as date"), DB::raw("SUM(total_amount) as sales"))
                        ->where("status", "delivered")
                        ->groupBy("date")
                        ->orderBy("date", "asc")
                        ->get();
                    break;
                case "week":
                    $salesData = DB::table("orders")
                        ->select(DB::raw("STRFTIME(\"%Y-%W\", created_at) as week"), DB::raw("SUM(total_amount) as sales"))
                        ->where("status", "delivered")
                        ->groupBy("week")
                        ->orderBy("week", "asc")
                        ->get();
                    break;
                case "month":
                    $salesData = DB::table("orders")
                        ->select(DB::raw("STRFTIME(\"%Y-%m\", created_at) as month"), DB::raw("SUM(total_amount) as sales"))
                        ->where("status", "delivered")
                        ->groupBy("month")
                        ->orderBy("month", "asc")
                        ->get();
                    break;
                case "year":
                    $salesData = DB::table("orders")
                        ->select(DB::raw("STRFTIME(\"%Y\", created_at) as year"), DB::raw("SUM(total_amount) as sales"))
                        ->where("status", "delivered")
                        ->groupBy("year")
                        ->orderBy("year", "asc")
                        ->get();
                    break;
                default:
                    return response()->json(["error" => "Período inválido"], 400);
            }

            return response()->json(["sales_data" => $salesData]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function topSellingProducts(Request $request)
    {
        try {
            $limit = $request->query("limit", 5);
            $topProducts = DB::table("order_items")
                ->select("product_name", DB::raw("SUM(quantity) as total_quantity_sold"))
                ->groupBy("product_name")
                ->orderBy("total_quantity_sold", "desc")
                ->limit($limit)
                ->get();

            return response()->json(["top_products" => $topProducts]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
}
