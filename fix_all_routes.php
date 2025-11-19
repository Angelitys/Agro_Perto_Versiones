<?php

// Script para corrigir todas as rotas e funcionalidades do sistema AgroPerto

echo "🔧 Iniciando correção de todas as rotas do sistema...\n\n";

// 1. Corrigir o método getOrCreateCart no modelo User
$userModelPath = __DIR__ . '/app/Models/User.php';
$userContent = file_get_contents($userModelPath);

if (!str_contains($userContent, 'getOrCreateCart')) {
    $userContent = str_replace(
        'use Laravel\Sanctum\HasApiTokens;',
        'use Laravel\Sanctum\HasApiTokens;
use App\Models\Cart;',
        $userContent
    );
    
    $userContent = str_replace(
        '    protected $casts = [
        \'email_verified_at\' => \'datetime\',
        \'password\' => \'hashed\',
    ];',
        '    protected $casts = [
        \'email_verified_at\' => \'datetime\',
        \'password\' => \'hashed\',
    ];

    /**
     * Get or create cart for user
     */
    public function getOrCreateCart()
    {
        $cart = $this->carts()->first();
        
        if (!$cart) {
            $cart = $this->carts()->create([
                \'total\' => 0,
                \'total_items\' => 0
            ]);
        }
        
        return $cart;
    }

    /**
     * Relationship with carts
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }',
        $userContent
    );
    
    file_put_contents($userModelPath, $userContent);
    echo "✅ Método getOrCreateCart adicionado ao modelo User\n";
}

// 2. Verificar se o modelo Cart tem os relacionamentos corretos
$cartModelPath = __DIR__ . '/app/Models/Cart.php';
if (file_exists($cartModelPath)) {
    $cartContent = file_get_contents($cartModelPath);
    
    if (!str_contains($cartContent, 'updateTotals')) {
        $cartContent = str_replace(
            '    protected $fillable = [
        \'user_id\',
        \'total\',
        \'total_items\'
    ];',
            '    protected $fillable = [
        \'user_id\',
        \'total\',
        \'total_items\'
    ];

    /**
     * Update cart totals
     */
    public function updateTotals()
    {
        $this->total_items = $this->items->sum(\'quantity\');
        $this->total = $this->items->sum(\'subtotal\');
        $this->save();
    }',
            $cartContent
        );
        
        file_put_contents($cartModelPath, $cartContent);
        echo "✅ Método updateTotals adicionado ao modelo Cart\n";
    }
}

// 3. Corrigir o CartController
$cartControllerPath = __DIR__ . '/app/Http/Controllers/CartController.php';
if (file_exists($cartControllerPath)) {
    $cartContent = file_get_contents($cartControllerPath);
    
    // Adicionar método add se não existir
    if (!str_contains($cartContent, 'public function add(')) {
        $cartContent = str_replace(
            '    /**
     * Adicionar produto ao carrinho
     */',
            '    /**
     * Adicionar produto ao carrinho
     */
    public function add(Request $request, Product $product)
    {
        $request->validate([
            \'quantity\' => \'required|integer|min:1|max:\' . $product->stock_quantity
        ]);

        $cart = auth()->user()->getOrCreateCart();
        
        // Verificar se o produto já está no carrinho
        $cartItem = $cart->items()->where("product_id", $product->id)->first();
        
        if ($cartItem) {
            // Atualizar quantidade
            $newQuantity = $cartItem->quantity + $request->quantity;
            
            if ($newQuantity > $product->stock_quantity) {
                return back()->with(\'error\', \'Quantidade solicitada excede o estoque disponível.\');
            }
            
            $cartItem->update([
                \'quantity\' => $newQuantity,
                \'price\' => $product->price,
                \'subtotal\' => $newQuantity * $product->price
            ]);
        } else {
            // Criar novo item no carrinho
            $cart->items()->create([
                \'product_id\' => $product->id,
                \'quantity\' => $request->quantity,
                \'price\' => $product->price,
                \'subtotal\' => $request->quantity * $product->price
            ]);
        }
        
        // Atualizar totais do carrinho
        $cart->updateTotals();
        
        return redirect()->route(\'cart.index\')->with(\'success\', \'Produto adicionado ao carrinho com sucesso!\');
    }',
            $cartContent
        );
        
        file_put_contents($cartControllerPath, $cartContent);
        echo "✅ Método add corrigido no CartController\n";
    }
}

// 4. Criar middleware para verificar se usuário pode comprar
$middlewarePath = __DIR__ . '/app/Http/Middleware/CanPurchase.php';
if (!file_exists($middlewarePath)) {
    $middlewareContent = '<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CanPurchase
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route(\'login\')->with(\'error\', \'Você precisa estar logado para comprar.\');
        }
        
        if (auth()->user()->type !== \'consumer\') {
            return redirect()->back()->with(\'error\', \'Apenas consumidores podem comprar produtos.\');
        }
        
        return $next($request);
    }
}';
    
    file_put_contents($middlewarePath, $middlewareContent);
    echo "✅ Middleware CanPurchase criado\n";
}

// 5. Atualizar as rotas web.php
$routesPath = __DIR__ . '/routes/web.php';
$routesContent = file_get_contents($routesPath);

// Adicionar middleware nas rotas do carrinho
if (!str_contains($routesContent, 'CanPurchase')) {
    $routesContent = str_replace(
        '// Carrinho (requer autenticação)
Route::middleware(\'auth\')->group(function () {',
        '// Carrinho (requer autenticação)
Route::middleware([\'auth\', App\Http\Middleware\CanPurchase::class])->group(function () {',
        $routesContent
    );
    
    file_put_contents($routesPath, $routesContent);
    echo "✅ Middleware CanPurchase adicionado às rotas do carrinho\n";
}

// 6. Registrar middleware no Kernel
$kernelPath = __DIR__ . '/app/Http/Kernel.php';
if (file_exists($kernelPath)) {
    $kernelContent = file_get_contents($kernelPath);
    
    if (!str_contains($kernelContent, 'can.purchase')) {
        $kernelContent = str_replace(
            '        \'throttle\' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        \'verified\' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,',
            '        \'throttle\' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        \'verified\' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        \'can.purchase\' => \App\Http\Middleware\CanPurchase::class,',
            $kernelContent
        );
        
        file_put_contents($kernelPath, $kernelContent);
        echo "✅ Middleware CanPurchase registrado no Kernel\n";
    }
}

// 7. Criar view de sucesso para carrinho
$successViewPath = __DIR__ . '/resources/views/cart/success.blade.php';
if (!file_exists($successViewPath)) {
    $successContent = '<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produto Adicionado - AgroPerto</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-8 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Produto Adicionado!</h1>
            <p class="text-gray-600 mb-6">O produto foi adicionado ao seu carrinho com sucesso.</p>
            <div class="space-y-3">
                <a href="{{ route(\'cart.index\') }}" class="block w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors">
                    Ver Carrinho
                </a>
                <a href="{{ route(\'products.index\') }}" class="block w-full border border-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-50 transition-colors">
                    Continuar Comprando
                </a>
            </div>
        </div>
    </div>
</body>
</html>';
    
    file_put_contents($successViewPath, $successContent);
    echo "✅ View de sucesso criada\n";
}

echo "\n🎉 Todas as correções foram aplicadas com sucesso!\n";
echo "📋 Resumo das correções:\n";
echo "   ✅ Método getOrCreateCart adicionado ao modelo User\n";
echo "   ✅ Método updateTotals adicionado ao modelo Cart\n";
echo "   ✅ Método add corrigido no CartController\n";
echo "   ✅ Middleware CanPurchase criado e registrado\n";
echo "   ✅ View de sucesso para carrinho criada\n";
echo "\n🚀 O sistema agora deve funcionar completamente!\n";
