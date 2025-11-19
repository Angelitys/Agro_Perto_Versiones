<?php
// Teste da API Corrigida do AgroPerto
echo "<h1>🧪 Teste da API Corrigida - AgroPerto</h1>";

// Configuração da API
$apiUrl = 'http://localhost/agro-marketplace-alpha/public/api-xampp-fixed.php';

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
            echo "<pre style='background: rgba(0,0,0,0.1); padding: 10px; border-radius: 3px; margin-top: 10px; overflow-x: auto; max-height: 300px;'>";
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

echo "<h2>🚀 Testando API Corrigida</h2>";

$totalTests = 0;
$passedTests = 0;

// Teste 1: Status da API
echo "<h2>📊 Teste Básico</h2>";
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

// Teste 4: Obter produto específico
$totalTests++;
$result = makeApiRequest('/products/1');
if (displayTestResult('Obter Produto ID 1', $result)) {
    $passedTests++;
}

// Teste 5: Produtos por categoria
$totalTests++;
$result = makeApiRequest('/products/category/1');
if (displayTestResult('Produtos da Categoria 1', $result)) {
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
    echo "<p>✅ <strong>Muito Bom!</strong> A API está funcionando bem.</p>";
} elseif ($successRate >= 60) {
    echo "<p>⚠️ <strong>Atenção!</strong> A API tem alguns problemas.</p>";
} else {
    echo "<p>❌ <strong>Crítico!</strong> A API precisa de correções.</p>";
}
echo "</div>";

echo "<h2>🚀 Próximos Passos</h2>";
echo "<ol>";
echo "<li>Se os testes passaram, substitua o arquivo <strong>api-xampp.php</strong> pelo <strong>api-xampp-fixed.php</strong></li>";
echo "<li>Acesse o sistema: <a href='index-xampp.php' target='_blank'><strong>Sistema Completo</strong></a></li>";
echo "<li>Configure a base de dados se ainda não fez: <a href='setup-database.php' target='_blank'><strong>Setup Database</strong></a></li>";
echo "</ol>";

echo "<p><small>Teste executado em: " . date('Y-m-d H:i:s') . "</small></p>";
?>
