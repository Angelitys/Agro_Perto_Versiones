<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Route;

// Página inicial
Route::get('/', [HomeController::class, 'index'])->name('home');

// Busca de produtos
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Produtos
Route::resource('products', ProductController::class);
Route::get('/categories/{category}/products', [ProductController::class, 'byCategory'])->name('products.by-category');

// Categorias
Route::resource('categories', CategoryController::class)->only(['index', 'show']);

// Carrinho (requer autenticação)
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
});

// Pedidos (requer autenticação)
Route::middleware('auth')->group(function () {
    Route::resource('orders', OrderController::class)->except(['edit', 'update', 'destroy']);
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.my');
});

// Vendas para Produtores (requer autenticação)
Route::middleware('auth')->group(function () {
    Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
    Route::get('/sales/{order}', [SalesController::class, 'show'])->name('sales.show');
    Route::patch('/sales/{order}/status', [SalesController::class, 'updateStatus'])->name('sales.updateStatus');
});

// Dashboard (requer autenticação)
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Perfil do usuário (requer autenticação)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
