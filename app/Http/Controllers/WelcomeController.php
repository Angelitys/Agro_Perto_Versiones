<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $categories = Category::take(4)->get();
        $featuredProducts = Product::where('active', true)
            ->with(['category', 'user'])
            ->take(6)
            ->get();
            
        return view('welcome-new', compact('categories', 'featuredProducts'));
    }
}
