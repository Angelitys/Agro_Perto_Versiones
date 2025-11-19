<?php
// Teste Completo da API AgroPerto
echo "<h1>🧪 Teste Completo da API AgroPerto</h1>";

// Configuração da API
$apiUrl = 'http://localhost/agro-marketplace-alpha/public/api-xampp.php';

// Função para fazer requisições à API
function makeApiRequest($endpoint, $method = 'GET', $data = null) {
    global $apiUrl;
    
    $url = $apiUrl . $endpoint;
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    
    if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    return [
        'response' => $response,
        'http_code' => $httpCode,
        'error' => $error
    ];
}

// Função para exibir resultado do teste
function displayTestResult($testName, $result, $expectedCode = 200) {
    echo "<div style='margin: 15px 0; padding: 15px; border-radius: 5px; border: 1px solid;'>";
    echo "<h3>🔍 $testName</h3>";
    
    if ($result['error']) {
        echo "<div style='background: #f8d7da; color: #721c24; padding: 10px; border-radius: 3px;'>";
        echo "❌ <strong>Erro de conexão:</strong> " . $result['error'];
        echo "</div>";
        return false;
    }
    
    $success = $result['http_code'] == $expectedCode;
    $bgColor = $success ? '#d4edda' : '#f8d7da';
    $textColor = $success ? '#155724' : '#721c24';
    $icon = $success ? '✅' : '❌';
    
    echo "<div style='background: $bgColor; color: $textColor; padding: 10px; border-radius: 3px;'>";
    echo "$icon <strong>HTTP {$result['http_code']}</strong><br>";
    
    if ($result['response']) {
        $decoded = json_decode($result['response'], true);
        if ($decoded) {
            echo "<pre style='background: rgba(0,0,0,0.1); padding: 10px; border-radius: 3px; margin-top: 10px; overflow-x: auto;'>";
            echo htmlspecialchars(json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            echo "</pre>";
        } else {
            echo "<pre style='background: rgba(0,0,0,0.1); padding: 10px; border-radius: 3px; margin-top: 10px;'>";
            echo htmlspecialchars($result['response']);
            echo "</pre>";
        }
    }
    echo "</div>";
    echo "</div>";
    
    return $success;
}

echo "<h2>🚀 Iniciando Testes da API</h2>";

$totalTests = 0;
$passedTests = 0;

// Teste 1: Status da API
echo "<h2>📊 Testes Básicos</h2>";
$totalTests++;
$result = makeApiRequest('/status');
if (displayTestResult('Status da API', $result)) {
    $passedTests++;
}

// Teste 2: Listar categorias
$totalTests++;
$result = makeApiRequest('/categories');
if (displayTestResult('Listar Categorias', $result)) {
    $passedTests++;
}

// Teste 3: Listar produtos
$totalTests++;
$result = makeApiRequest('/products');
if (displayTestResult('Listar Produtos', $result)) {
    $passedTests++;
}

// Teste 4: Listar utilizadores
$totalTests++;
$result = makeApiRequest('/users');
if (displayTestResult('Listar Utilizadores', $result)) {
    $passedTests++;
}

echo "<h2>🔍 Testes de Consulta Específica</h2>";

// Teste 5: Obter produto específico
$totalTests++;
$result = makeApiRequest('/products/1');
if (displayTestResult('Obter Produto ID 1', $result)) {
    $passedTests++;
}

// Teste 6: Obter categoria específica
$totalTests++;
$result = makeApiRequest('/categories/1');
if (displayTestResult('Obter Categoria ID 1', $result)) {
    $passedTests++;
}

// Teste 7: Produtos por categoria
$totalTests++;
$result = makeApiRequest('/products/category/1');
if (displayTestResult('Produtos da Categoria 1', $result)) {
    $passedTests++;
}

echo "<h2>👤 Testes de Utilizadores</h2>";

// Teste 8: Registar novo utilizador consumidor
$totalTests++;
$newConsumer = [
    'name' => 'Teste Consumidor',
    'email' => 'teste.consumidor@email.com',
    'password' => 'password123',
    'type' => 'consumer'
];
$result = makeApiRequest('/register', 'POST', $newConsumer);
if (displayTestResult('Registar Consumidor', $result, 201)) {
    $passedTests++;
}

// Teste 9: Registar novo utilizador produtor
$totalTests++;
$newProducer = [
    'name' => 'Teste Produtor',
    'email' => 'teste.produtor@email.com',
    'password' => 'password123',
    'type' => 'producer'
];
$result = makeApiRequest('/register', 'POST', $newProducer);
if (displayTestResult('Registar Produtor', $result, 201)) {
    $passedTests++;
}

// Teste 10: Login
$totalTests++;
$loginData = [
    'email' => 'teste.consumidor@email.com',
    'password' => 'password123'
];
$result = makeApiRequest('/login', 'POST', $loginData);
if (displayTestResult('Login de Utilizador', $result)) {
    $passedTests++;
}

echo "<h2>🛒 Testes de Carrinho e Pedidos</h2>";

// Teste 11: Adicionar item ao carrinho
$totalTests++;
$cartItem = [
    'user_id' => 2,
    'product_id' => 1,
    'quantity' => 2
];
$result = makeApiRequest('/cart/add', 'POST', $cartItem);
if (displayTestResult('Adicionar ao Carrinho', $result, 201)) {
    $passedTests++;
}

// Teste 12: Ver carrinho
$totalTests++;
$result = makeApiRequest('/cart/2');
if (displayTestResult('Ver Carrinho do Utilizador 2', $result)) {
    $passedTests++;
}

// Teste 13: Criar pedido
$totalTests++;
$orderData = [
    'user_id' => 2,
    'items' => [
        ['product_id' => 1, 'quantity' => 2, 'price' => 8.50],
        ['product_id' => 2, 'quantity' => 1, 'price' => 2.50]
    ],
    'pickup_date' => '2025-10-08 10:00:00',
    'payment_method' => 'cash'
];
$result = makeApiRequest('/orders', 'POST', $orderData);
if (displayTestResult('Criar Pedido', $result, 201)) {
    $passedTests++;
}

// Teste 14: Listar pedidos
$totalTests++;
$result = makeApiRequest('/orders');
if (displayTestResult('Listar Pedidos', $result)) {
    $passedTests++;
}

echo "<h2>⭐ Testes de Avaliações</h2>";

// Teste 15: Adicionar avaliação
$totalTests++;
$reviewData = [
    'user_id' => 2,
    'product_id' => 1,
    'rating' => 5,
    'title' => 'Produto excelente!',
    'comment' => 'Muito fresco e saboroso, recomendo!'
];
$result = makeApiRequest('/reviews', 'POST', $reviewData);
if (displayTestResult('Adicionar Avaliação', $result, 201)) {
    $passedTests++;
}

// Teste 16: Listar avaliações de um produto
$totalTests++;
$result = makeApiRequest('/products/1/reviews');
if (displayTestResult('Avaliações do Produto 1', $result)) {
    $passedTests++;
}

echo "<h2>🏪 Testes de Funcionalidades de Produtor</h2>";

// Teste 17: Adicionar novo produto
$totalTests++;
$newProduct = [
    'name' => 'Produto Teste',
    'description' => 'Produto criado para teste da API',
    'price' => 5.99,
    'stock_quantity' => 10,
    'unit' => 'kg',
    'category_id' => 1,
    'user_id' => 1,
    'origin' => 'Quinta de Teste',
    'harvest_date' => '2025-10-07',
    'available_from' => '2025-10-08 08:00:00',
    'available_until' => '2025-10-08 18:00:00',
    'fair_location' => 'Feira de Teste'
];
$result = makeApiRequest('/products', 'POST', $newProduct);
if (displayTestResult('Adicionar Produto', $result, 201)) {
    $passedTests++;
}

// Teste 18: Produtos do produtor
$totalTests++;
$result = makeApiRequest('/users/1/products');
if (displayTestResult('Produtos do Produtor 1', $result)) {
    $passedTests++;
}

echo "<h2>🔍 Testes de Pesquisa e Filtros</h2>";

// Teste 19: Pesquisar produtos
$totalTests++;
$result = makeApiRequest('/products/search?q=tomate');
if (displayTestResult('Pesquisar Produtos (tomate)', $result)) {
    $passedTests++;
}

// Teste 20: Produtos disponíveis hoje
$totalTests++;
$result = makeApiRequest('/products/available');
if (displayTestResult('Produtos Disponíveis Hoje', $result)) {
    $passedTests++;
}

// Resumo dos testes
echo "<hr>";
echo "<h2>📊 Resumo dos Testes</h2>";

$successRate = ($passedTests / $totalTests) * 100;
$bgColor = $successRate >= 80 ? '#d4edda' : ($successRate >= 60 ? '#fff3cd' : '#f8d7da');
$textColor = $successRate >= 80 ? '#155724' : ($successRate >= 60 ? '#856404' : '#721c24');

echo "<div style='background: $bgColor; color: $textColor; padding: 20px; border-radius: 5px; text-align: center; margin: 20px 0;'>";
echo "<h3>Resultados dos Testes</h3>";
echo "<p><strong>Total de Testes:</strong> $totalTests</p>";
echo "<p><strong>Testes Aprovados:</strong> $passedTests</p>";
echo "<p><strong>Testes Falhados:</strong> " . ($totalTests - $passedTests) . "</p>";
echo "<p><strong>Taxa de Sucesso:</strong> " . number_format($successRate, 1) . "%</p>";

if ($successRate >= 90) {
    echo "<p>🎉 <strong>Excelente!</strong> A API está funcionando perfeitamente!</p>";
} elseif ($successRate >= 80) {
    echo "<p>✅ <strong>Muito Bom!</strong> A API está funcionando bem com pequenos problemas.</p>";
} elseif ($successRate >= 60) {
    echo "<p>⚠️ <strong>Atenção!</strong> A API tem alguns problemas que precisam ser corrigidos.</p>";
} else {
    echo "<p>❌ <strong>Crítico!</strong> A API tem muitos problemas e precisa de correções urgentes.</p>";
}
echo "</div>";

echo "<h2>🚀 Próximos Passos</h2>";
echo "<ol>";
echo "<li>Se os testes passaram, acesse: <a href='index-xampp.php' target='_blank'><strong>Sistema Completo</strong></a></li>";
echo "<li>Para configurar a base de dados: <a href='setup-database.php' target='_blank'><strong>Setup Database</strong></a></li>";
echo "<li>Para testar conexão: <a href='teste-conexao-xampp.php' target='_blank'><strong>Teste Conexão</strong></a></li>";
echo "</ol>";

echo "<p><small>Teste executado em: " . date('Y-m-d H:i:s') . "</small></p>";
?>
