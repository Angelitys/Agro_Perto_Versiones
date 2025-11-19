<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\CheckoutController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aqui estão registradas todas as rotas da aplicação.
| Cada grupo está devidamente comentado e organizado.
|
*/

// ==========================
// 🌐 ROTAS PÚBLICAS
// ==========================

// Página inicial
Route::get("/", [App\Http\Controllers\WelcomeController::class, "index"])->name("welcome");
Route::get("/home", [App\Http\Controllers\WelcomeController::class, "index"])->name("home");

// Categorias
Route::get("/categories", [CategoryController::class, "index"])->name("categories.index");
Route::get("/categories/{category}", [CategoryController::class, "show"])->name("categories.show");

// Produtos (públicos)
Route::get("/products", [ProductController::class, "index"])->name("products.index");
Route::get("/products/category/{category}", [ProductController::class, "byCategory"])->name("products.by-category");

// Meus Produtos (produtor) - DEVE vir ANTES de {product}
Route::get("/products/my-products", [ProductController::class, "myProducts"])->name("products.my")->middleware('auth');

// Criar e armazenar produtos (DEVE vir ANTES de {product})
Route::get("/products/create", [ProductController::class, "create"])->name("products.create");
Route::post("/products", [ProductController::class, "store"])->name("products.store");

// Rotas com parâmetro dinâmico {product} vêm por último
Route::get("/products/{product}", [ProductController::class, "show"])->name("products.show");
Route::resource("products", ProductController::class)->except(["index", "show", "create", "store"]);

// Avaliações públicas
Route::get("/products/{product}/reviews", [App\Http\Controllers\PublicReviewController::class, "productReviews"])->name("reviews.product");
Route::get("/producers/{producer}/reviews", [App\Http\Controllers\PublicReviewController::class, "producerReviews"])->name("reviews.producer");

// Perfil do usuário
Route::get("/profile", [ProfileController::class, "edit"])->name("profile.edit");
Route::patch("/profile", [ProfileController::class, "update"])->name("profile.update");
Route::delete("/profile", [ProfileController::class, "destroy"])->name("profile.destroy");

// Dashboard
Route::get("/dashboard", [DashboardController::class, "index"])->name("dashboard");

// ==========================
// 🔐 ROTAS PROTEGIDAS (AUTENTICAÇÃO)
// ==========================

Route::middleware(['auth'])->group(function () {
    // Rotas de Venda na Feira (Teste 01)
    Route::get('/sales/fair-sale', [SaleController::class, 'create'])->name('sales.create-fair');
    Route::post('/sales/fair-sale', [SaleController::class, 'store'])->name('sales.store-fair');
    // Pedidos do usuário
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.my');
    Route::post('/orders/{order}/no-show', [OrderController::class, 'markNoShow'])->name('orders.no-show');

    // Carrinho
    Route::get("/cart", [CartController::class, "index"])->name("cart.index");
    Route::post("/cart/add/{product}", [CartController::class, "add"])->name("cart.add");
    Route::patch("/cart/update/{cartItem}", [CartController::class, "update"])->name("cart.update");
    Route::delete("/cart/remove/{cartItem}", [CartController::class, "remove"])->name("cart.remove");
    Route::delete("/cart/clear", [CartController::class, "clear"])->name("cart.clear");
    Route::get("/cart/count", [CartController::class, "count"])->name("cart.count");

    // Checkout
    Route::get("/checkout", [CheckoutController::class, "index"])->name("checkout.index");
    Route::post("/checkout", [CheckoutController::class, "store"])->name("checkout.store");

    // Pedidos
    Route::get("/orders", [OrderController::class, "index"])->name("orders.index");
    Route::get("/orders/{order}/awaiting-confirmation", [OrderController::class, "awaitingConfirmation"])->name("orders.awaiting-confirmation");
    Route::get("/orders/{order}", [OrderController::class, "show"])->name("orders.show");
    // Route::post("/orders", [OrderController::class, "store"])->name("orders.store"); // Removido por redundância
    Route::post("/orders/{order}/confirm-delivery", [OrderController::class, "confirmDelivery"])->name("orders.confirm-delivery");
    Route::post("/orders/{order}/confirm", [OrderController::class, "confirmOrder"])->name("orders.confirm");
    Route::post("/orders/{order}/reject", [OrderController::class, "rejectOrder"])->name("orders.reject");

    // Vendas
    Route::get("/sales", [SalesController::class, "index"])->name("sales.index");
    Route::get("/sales/{order}", [SalesController::class, "show"])->name("sales.show");
    Route::post("/sales/{order}/update-status", [SalesController::class, "updateStatus"])->name("sales.update-status");

    // Entregas
    Route::get("/deliveries", [DeliveryController::class, "index"])->name("deliveries.index");
    Route::get("/deliveries/{delivery}", [DeliveryController::class, "show"])->name("deliveries.show");
    Route::post("/deliveries/{delivery}/update-status", [DeliveryController::class, "updateStatus"])->name("deliveries.update-status");
    Route::get("/deliveries/schedule", [DeliveryController::class, "schedule"])->name("deliveries.schedule");
    Route::post("/deliveries/schedule", [DeliveryController::class, "storeSchedule"])->name("deliveries.store-schedule");
    Route::get("/deliveries/history", [DeliveryController::class, "deliveryHistory"])->name("deliveries.history");

    // Avaliações (reviews)
    Route::get("/orders/{order}/products/{product}/review", [ReviewController::class, "create"])->name("reviews.create");
    Route::post("/orders/{order}/products/{product}/review", [ReviewController::class, "store"])->name("reviews.store");
    Route::get("/reviews/available", [ReviewController::class, "availableForReview"])->name("reviews.available");
    Route::get("/my-reviews", [ReviewController::class, "myReviews"])->name("reviews.my");

    // Avaliações públicas (vinculadas ao pedido)
    Route::get("/orders/{order}/review", [App\Http\Controllers\PublicReviewController::class, "create"])->name("public-reviews.create");
    Route::post("/orders/{order}/review", [App\Http\Controllers\PublicReviewController::class, "store"])->name("public-reviews.store");
    Route::post("/orders/{order}/enable-review", [App\Http\Controllers\PublicReviewController::class, "enableReview"])->name("public-reviews.enable");

    // Notificações
    Route::get("/notifications", [App\Http\Controllers\NotificationController::class, "index"])->name("notifications.index");
    Route::post("/notifications/{id}/mark-as-read", [App\Http\Controllers\NotificationController::class, "markAsRead"])->name("notifications.mark-as-read");
    Route::post("/notifications/mark-all-as-read", [App\Http\Controllers\NotificationController::class, "markAllAsRead"])->name("notifications.mark-all-as-read");
    Route::get("/notifications/unread-count", [App\Http\Controllers\NotificationController::class, "getUnreadCount"])->name("notifications.unread-count");

    // Páginas institucionais / legais
    Route::get("/termos-de-uso", [App\Http\Controllers\LegalController::class, "termsOfService"])->name("legal.terms-of-service");
    Route::get("/politica-de-privacidade", [App\Http\Controllers\LegalController::class, "privacyPolicy"])->name("legal.privacy-policy");
    Route::get("/sobre", [App\Http\Controllers\LegalController::class, "about"])->name("legal.about");
    Route::get("/contato", [App\Http\Controllers\LegalController::class, "contact"])->name("legal.contact");
    Route::post("/contato", [App\Http\Controllers\LegalController::class, "contactSubmit"])->name("legal.contact.submit");
    Route::get("/faq", [App\Http\Controllers\LegalController::class, "faq"])->name("legal.faq");
    Route::get("/ajuda", [App\Http\Controllers\LegalController::class, "help"])->name("legal.help");
});

// ==========================
// 🔐 AUTENTICAÇÃO (Laravel Breeze / Jetstream / Fortify)
// ==========================
require __DIR__ . "/auth.php";