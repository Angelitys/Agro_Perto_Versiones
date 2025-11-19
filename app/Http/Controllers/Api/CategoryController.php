<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategoryController extends Controller
{
    public function categories()
    {
        try {
            $categories = DB::table("categories")
                ->where("active", true)
                ->orderBy("name")
                ->get();
            
            return response()->json(["categories" => $categories]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
}

