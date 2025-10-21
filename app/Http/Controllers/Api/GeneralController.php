<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GeneralController extends Controller
{
    public function status()
    {
        try {
            $productCount = DB::table("products")->count();
            $userCount = DB::table("users")->count();
            $categoryCount = DB::table("categories")->count();
            
            return response()->json([
                "status" => "online",
                "message" => "AgroPerto Marketplace API - Laravel funcionando!",
                "database" => "MySQL conectado",
                "products" => $productCount,
                "users" => $userCount,
                "categories" => $categoryCount,
                "timestamp" => Carbon::now()->format("Y-m-d H:i:s"),
                "version" => "2.0.0-laravel"
            ]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
}

