<?php
// Script para configurar automaticamente a base de dados AgroPerto no XAMPP
echo "<h1>🚀 Configuração Automática da Base de Dados AgroPerto</h1>";

// Configurações do XAMPP
$host = 'localhost';
$username = 'root';
$password = '1.Angelopb';  // Sua senha do XAMPP
$port = 3306;

echo "<h2>📋 Configurações:</h2>";
echo "<ul>";
echo "<li><strong>Host:</strong> $host</li>";
echo "<li><strong>Usuário:</strong> $username</li>";
echo "<li><strong>Senha:</strong> " . (empty($password) ? '(vazia)' : '***') . "</li>";
echo "<li><strong>Porta:</strong> $port</li>";
echo "</ul>";

try {
    echo "<h2>🔌 Conectando ao MariaDB...</h2>";
    
    // Conectar sem especificar base de dados para poder criá-la
    $pdo = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "✅ <strong>Conectado ao MariaDB!</strong>";
    echo "</div>";
    
    echo "<h2>📂 Lendo script SQL...</h2>";
    
    // Ler o ficheiro SQL
    $sqlFile = '../database_setup_xampp.sql';
    if (!file_exists($sqlFile)) {
        throw new Exception("Ficheiro SQL não encontrado: $sqlFile");
    }
    
    $sql = file_get_contents($sqlFile);
    if ($sql === false) {
        throw new Exception("Erro ao ler o ficheiro SQL");
    }
    
    echo "<div style='background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "📄 <strong>Script SQL carregado!</strong><br>";
    echo "Tamanho: " . number_format(strlen($sql)) . " caracteres";
    echo "</div>";
    
    echo "<h2>⚙️ Executando script SQL...</h2>";
    
    // Dividir o SQL em comandos individuais
    $commands = array_filter(array_map('trim', explode(';', $sql)));
    $successCount = 0;
    $errorCount = 0;
    $errors = [];
    
    foreach ($commands as $command) {
        if (empty($command) || strpos($command, '--') === 0) {
            continue; // Pular comentários e linhas vazias
        }
        
        try {
            $pdo->exec($command);
            $successCount++;
        } catch (PDOException $e) {
            $errorCount++;
            $errors[] = [
                'command' => substr($command, 0, 100) . '...',
                'error' => $e->getMessage()
            ];
        }
    }
    
    echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "✅ <strong>Script executado!</strong><br>";
    echo "Comandos executados com sucesso: $successCount<br>";
    if ($errorCount > 0) {
        echo "Comandos com erro: $errorCount";
    }
    echo "</div>";
    
    if (!empty($errors)) {
        echo "<h3>⚠️ Erros encontrados:</h3>";
        echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        foreach ($errors as $error) {
            echo "<strong>Comando:</strong> " . htmlspecialchars($error['command']) . "<br>";
            echo "<strong>Erro:</strong> " . htmlspecialchars($error['error']) . "<br><br>";
        }
        echo "</div>";
    }
    
    // Conectar à base de dados criada para verificar
    echo "<h2>🔍 Verificando base de dados criada...</h2>";
    
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=agro_marketplace_laravel;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Verificar tabelas
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<div style='background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "📋 <strong>Tabelas criadas (" . count($tables) . "):</strong><br>";
    
    $expectedTables = [
        'users', 'categories', 'products', 'orders', 'order_items', 
        'carts', 'cart_items', 'addresses', 'reviews', 'feedbacks'
    ];
    
    foreach ($expectedTables as $table) {
        if (in_array($table, $tables)) {
            // Contar registros
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM `$table`");
            $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            echo "✅ <strong>$table</strong> - $count registros<br>";
        } else {
            echo "❌ <strong>$table</strong> - NÃO ENCONTRADA<br>";
        }
    }
    echo "</div>";
    
    // Testar algumas consultas
    echo "<h2>🧪 Testando funcionalidades...</h2>";
    
    try {
        // Testar consulta de produtos com categoria
        $stmt = $pdo->query("
            SELECT p.name as produto, c.name as categoria, p.price, p.stock_quantity 
            FROM products p 
            JOIN categories c ON p.category_id = c.id 
            LIMIT 5
        ");
        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "✅ <strong>Consulta de produtos funcionando!</strong><br>";
        echo "Produtos encontrados: " . count($produtos) . "<br>";
        foreach ($produtos as $produto) {
            echo "• {$produto['produto']} ({$produto['categoria']}) - €{$produto['price']}<br>";
        }
        echo "</div>";
        
    } catch (Exception $e) {
        echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "❌ <strong>Erro na consulta de produtos:</strong> " . $e->getMessage();
        echo "</div>";
    }
    
    echo "<h2>🎉 Configuração Concluída!</h2>";
    echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 20px; border-radius: 5px; margin: 20px 0; text-align: center;'>";
    echo "<h3>✅ Base de dados configurada com sucesso!</h3>";
    echo "<p>A base de dados <strong>agro_marketplace_laravel</strong> foi criada e populada com dados de exemplo.</p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "❌ <strong>ERRO:</strong><br>";
    echo $e->getMessage();
    echo "</div>";
    
    echo "<h2>🔧 Possíveis Soluções:</h2>";
    echo "<ul>";
    echo "<li>Verifique se o XAMPP está a correr</li>";
    echo "<li>Verifique se o MySQL/MariaDB está ativo no XAMPP</li>";
    echo "<li>Confirme se a senha está correta: <strong>1.Angelopb</strong></li>";
    echo "<li>Tente usar uma porta diferente (3307)</li>";
    echo "<li>Verifique as permissões do utilizador root</li>";
    echo "</ul>";
}

echo "<hr>";
echo "<h2>🚀 Próximos Passos:</h2>";
echo "<ol>";
echo "<li>Teste a conexão: <a href='teste-conexao-xampp.php' target='_blank'><strong>teste-conexao-xampp.php</strong></a></li>";
echo "<li>Teste a API: <a href='api-xampp.php/status' target='_blank'><strong>api-xampp.php/status</strong></a></li>";
echo "<li>Acesse o sistema: <a href='index-xampp.php' target='_blank'><strong>index-xampp.php</strong></a></li>";
echo "</ol>";

echo "<p><small>Arquivo: setup-database.php | Data: " . date('Y-m-d H:i:s') . "</small></p>";
?>
