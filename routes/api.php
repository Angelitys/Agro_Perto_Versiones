<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware("auth:sanctum")->get("/user", function (Request $request) {
    return $request->user();
});

// Rotas de Autenticação API
Route::post("/register", [App\Http\Controllers\Api\UserController::class, "register"]);
Route::post("/login", [App\Http\Controllers\Api\UserController::class, "login"]);

// Rotas de Usuários API
Route::get("/users", [App\Http\Controllers\Api\UserController::class, "users"]);
Route::get("/users/{id}", [App\Http\Controllers\Api\UserController::class, "getUser"]);
Route::put("/users/{id}", [App\Http\Controllers\Api\UserController::class, "updateUser"]);
Route::delete("/users/{id}", [App\Http\Controllers\Api\UserController::class, "deleteUser"]);

// Rotas de Produtos API
Route::get("/products", [App\Http\Controllers\Api\ProductController::class, "products"]);
Route::post("/products", [App\Http\Controllers\Api\ProductController::class, "addProduct"]);
Route::get("/products/{id}", [App\Http\Controllers\Api\ProductController::class, "product"]);
Route::put("/products/{id}", [App\Http\Controllers\Api\ProductController::class, "updateProduct"]);
Route::delete("/products/{id}", [App\Http\Controllers\Api\ProductController::class, "deleteProduct"]);
Route::get("/products/category/{category_id}", [App\Http\Controllers\Api\ProductController::class, "productsByCategory"]);

// Rotas de Categorias API
Route::get("/categories", [App\Http\Controllers\Api\CategoryController::class, "categories"]);

// Rotas de Carrinho API
Route::prefix("cart")->group(function () {
    Route::get("/{user_id}", [App\Http\Controllers\Api\CartController::class, "getCart"]);
    Route::post("/add", [App\Http\Controllers\Api\CartController::class, "addToCart"]);
    Route::post("/remove", [App\Http\Controllers\Api\CartController::class, "removeFromCart"]);
    Route::post("/clear", [App\Http\Controllers\Api\CartController::class, "clearCart"]);
});

// Rotas de Pedidos API
Route::get("/orders", [App\Http\Controllers\Api\OrderController::class, "index"]);
Route::post("/orders", [App\Http\Controllers\Api\OrderController::class, "store"]);
Route::get("/orders/{id}", [App\Http\Controllers\Api\OrderController::class, "show"]);
Route::put("/orders/{id}/pickup-schedule", [App\Http\Controllers\Api\OrderController::class, "updatePickupSchedule"]);
Route::post("/orders/{id}/confirm-delivery", [App\Http\Controllers\Api\OrderController::class, "confirmDelivery"]);

// Rotas de Notificações API
Route::middleware("auth:sanctum")->prefix("notifications")->group(function () {
    Route::get("/", [App\Http\Controllers\Api\NotificationController::class, "index"]);
    Route::post("/{id}/mark-as-read", [App\Http\Controllers\Api\NotificationController::class, "markAsRead"]);
    Route::post("/mark-all-as-read", [App\Http\Controllers\Api\NotificationController::class, "markAllAsRead"]);
    Route::get("/unread-count", [App\Http\Controllers\Api\NotificationController::class, "getUnreadCount"]);
});

// Rotas de Avaliações API
Route::get("/reviews", [App\Http\Controllers\Api\ReviewController::class, "index"]);
Route::post("/reviews", [App\Http\Controllers\Api\ReviewController::class, "store"]);
Route::get("/reviews/{id}", [App\Http\Controllers\Api\ReviewController::class, "show"]);

// Rotas de Dashboard API
Route::get("/dashboard/sales-overview", [App\Http\Controllers\Api\DashboardController::class, "salesOverview"]);
Route::get("/dashboard/sales-by-period", [App\Http\Controllers\Api\DashboardController::class, "salesByPeriod"]);
Route::get("/dashboard/top-selling-products", [App\Http\Controllers\Api\DashboardController::class, "topSellingProducts"]);

