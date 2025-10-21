<?php
echo "<h1>🚨 TESTE RÁPIDO - API FINAL</h1>";

$tests = [
    '/status' => 'Status da API',
    '/categories' => 'Categorias',
    '/products' => 'Produtos'
];

foreach ($tests as $endpoint => $name) {
    echo "<h3>$name</h3>";
    
    $url = "http://localhost/agro-marketplace-alpha/public/api-xampp-FINAL.php$endpoint";
    $response = file_get_contents($url);
    
    if ($response) {
        $data = json_decode($response, true);
        echo "<div style='background: #d4edda; padding: 10px; margin: 10px 0;'>";
        echo "✅ <strong>FUNCIONOU!</strong><br>";
        echo "<pre>" . htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT)) . "</pre>";
        echo "</div>";
    } else {
        echo "<div style='background: #f8d7da; padding: 10px; margin: 10px 0;'>";
        echo "❌ <strong>ERRO!</strong>";
        echo "</div>";
    }
}

echo "<hr>";
echo "<h2>🚀 SE FUNCIONOU:</h2>";
echo "<ol>";
echo "<li>Renomeie <strong>api-xampp.php</strong> para <strong>api-xampp-old.php</strong></li>";
echo "<li>Renomeie <strong>api-xampp-FINAL.php</strong> para <strong>api-xampp.php</strong></li>";
echo "<li>Acesse: <a href='index-xampp.php'>SISTEMA PRINCIPAL</a></li>";
echo "</ol>";
?>
