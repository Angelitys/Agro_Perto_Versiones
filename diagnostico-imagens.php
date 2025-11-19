<?php

/**
 * Script de Diagnóstico - Problema com Imagens
 * 
 * Execute este script via browser ou CLI para diagnosticar o problema das imagens
 */

// Configurar para mostrar erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Diagnóstico de Imagens - AgroPerto</h1>";
echo "<hr>";

// 1. Verificar se o autoload do Laravel existe
echo "<h2>1. Verificando Ambiente Laravel</h2>";
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
    echo "✅ Autoload encontrado<br>";
} else {
    echo "❌ Autoload NÃO encontrado. Execute: composer install<br>";
    exit;
}

// 2. Carregar Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "✅ Laravel carregado<br>";
echo "<hr>";

// 3. Verificar produtos no banco
echo "<h2>2. Verificando Produtos no Banco de Dados</h2>";

use App\Models\Product;

$products = Product::all();
echo "Total de produtos: " . $products->count() . "<br><br>";

foreach ($products as $product) {
    echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
    echo "<strong>Produto ID:</strong> {$product->id}<br>";
    echo "<strong>Nome:</strong> {$product->name}<br>";
    echo "<strong>Campo 'images' (raw):</strong> <pre>" . var_export($product->images, true) . "</pre>";
    echo "<strong>Tipo do campo 'images':</strong> " . gettype($product->images) . "<br>";
    
    if (is_array($product->images) && count($product->images) > 0) {
        echo "<strong>Primeira imagem:</strong> " . $product->images[0] . "<br>";
        echo "<strong>Caminho completo esperado:</strong> storage/app/public/" . $product->images[0] . "<br>";
        echo "<strong>URL pública esperada:</strong> " . asset('storage/' . $product->images[0]) . "<br>";
        
        // Verificar se o arquivo existe
        $fullPath = storage_path('app/public/' . $product->images[0]);
        if (file_exists($fullPath)) {
            echo "✅ <strong>Arquivo existe no servidor!</strong><br>";
            echo "<strong>Tamanho:</strong> " . filesize($fullPath) . " bytes<br>";
        } else {
            echo "❌ <strong>Arquivo NÃO existe no servidor!</strong><br>";
            echo "<strong>Procurado em:</strong> {$fullPath}<br>";
        }
    } else {
        echo "⚠️ <strong>Campo 'images' está vazio ou não é um array</strong><br>";
    }
    
    echo "</div>";
}

echo "<hr>";

// 4. Verificar link simbólico
echo "<h2>3. Verificando Link Simbólico</h2>";

$publicStoragePath = public_path('storage');
if (is_link($publicStoragePath)) {
    echo "✅ Link simbólico existe<br>";
    echo "<strong>Aponta para:</strong> " . readlink($publicStoragePath) . "<br>";
} else if (is_dir($publicStoragePath)) {
    echo "⚠️ 'public/storage' existe mas NÃO é um link simbólico<br>";
} else {
    echo "❌ Link simbólico NÃO existe. Execute: php artisan storage:link<br>";
}

echo "<hr>";

// 5. Verificar permissões
echo "<h2>4. Verificando Permissões</h2>";

$storagePath = storage_path('app/public');
if (is_dir($storagePath)) {
    echo "✅ Diretório storage/app/public existe<br>";
    echo "<strong>Permissões:</strong> " . substr(sprintf('%o', fileperms($storagePath)), -4) . "<br>";
    
    $productsPath = storage_path('app/public/products');
    if (is_dir($productsPath)) {
        echo "✅ Diretório storage/app/public/products existe<br>";
        echo "<strong>Permissões:</strong> " . substr(sprintf('%o', fileperms($productsPath)), -4) . "<br>";
        
        // Listar arquivos
        $files = scandir($productsPath);
        echo "<strong>Arquivos encontrados:</strong><br>";
        echo "<ul>";
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                echo "<li>{$file}</li>";
            }
        }
        echo "</ul>";
    } else {
        echo "⚠️ Diretório storage/app/public/products NÃO existe<br>";
    }
} else {
    echo "❌ Diretório storage/app/public NÃO existe<br>";
}

echo "<hr>";

// 6. Teste de URL
echo "<h2>5. Teste de URL de Imagem</h2>";

$testProduct = Product::whereNotNull('images')->first();
if ($testProduct && is_array($testProduct->images) && count($testProduct->images) > 0) {
    $imageUrl = asset('storage/' . $testProduct->images[0]);
    echo "<strong>URL gerada:</strong> {$imageUrl}<br>";
    echo "<strong>Teste visual:</strong><br>";
    echo "<img src='{$imageUrl}' alt='Teste' style='max-width: 300px; border: 2px solid red;'><br>";
    echo "<em>Se a imagem aparecer acima, o problema está resolvido!</em><br>";
} else {
    echo "⚠️ Nenhum produto com imagem encontrado para teste<br>";
}

echo "<hr>";
echo "<h2>✅ Diagnóstico Concluído</h2>";
echo "<p>Envie o resultado deste diagnóstico para análise.</p>";

