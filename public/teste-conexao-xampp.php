<?php
// Teste de Conexão XAMPP/MariaDB
echo "<h1>🔧 Teste de Conexão XAMPP/MariaDB</h1>";

// Suas configurações do XAMPP
$host = 'localhost';
$dbname = 'agro_marketplace_laravel';
$username = 'root';
$password = '1.Angelopb';  // Sua senha
$port = 3306;

echo "<h2>📋 Configurações:</h2>";
echo "<ul>";
echo "<li><strong>Host:</strong> $host</li>";
echo "<li><strong>Banco:</strong> $dbname</li>";
echo "<li><strong>Usuário:</strong> $username</li>";
echo "<li><strong>Senha:</strong> " . (empty($password) ? '(vazia)' : '***') . "</li>";
echo "<li><strong>Porta:</strong> $port</li>";
echo "</ul>";

try {
    echo "<h2>🔌 Testando Conexão...</h2>";
    
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "✅ <strong>CONEXÃO FUNCIONANDO!</strong><br>";
    echo "Conectado com sucesso ao banco MariaDB via XAMPP!";
    echo "</div>";
    
    // Testar se as tabelas existem
    echo "<h2>📋 Verificando Tabelas...</h2>";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) > 0) {
        echo "<div style='background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "📋 <strong>Tabelas encontradas (" . count($tables) . "):</strong><br>";
        
        foreach($tables as $table) {
            // Contar registros em cada tabela
            try {
                $stmt = $pdo->query("SELECT COUNT(*) as count FROM `$table`");
                $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
                echo "• <strong>$table</strong> - $count registros<br>";
            } catch(Exception $e) {
                echo "• <strong>$table</strong> - Erro ao contar registros<br>";
            }
        }
        echo "</div>";
        
        // Testar algumas consultas básicas
        echo "<h2>🧪 Testando Consultas...</h2>";
        
        // Testar categorias
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM categories");
            $categorias = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            echo "✅ Categorias: $categorias encontradas<br>";
        } catch(Exception $e) {
            echo "❌ Erro ao consultar categorias: " . $e->getMessage() . "<br>";
        }
        
        // Testar produtos
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM products");
            $produtos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            echo "✅ Produtos: $produtos encontrados<br>";
        } catch(Exception $e) {
            echo "❌ Erro ao consultar produtos: " . $e->getMessage() . "<br>";
        }
        
        // Testar usuários
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
            $usuarios = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            echo "✅ Usuários: $usuarios encontrados<br>";
        } catch(Exception $e) {
            echo "❌ Erro ao consultar usuários: " . $e->getMessage() . "<br>";
        }
        
    } else {
        echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "⚠️ <strong>Nenhuma tabela encontrada!</strong><br>";
        echo "Execute o script SQL no HeidiSQL para criar as tabelas.";
        echo "</div>";
    }
    
} catch(PDOException $e) {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "❌ <strong>ERRO DE CONEXÃO:</strong><br>";
    echo $e->getMessage();
    echo "</div>";
    
    echo "<h2>🔧 Possíveis Soluções:</h2>";
    echo "<ul>";
    echo "<li>Verifique se o XAMPP está rodando</li>";
    echo "<li>Verifique se o MySQL/MariaDB está ativo no XAMPP</li>";
    echo "<li>Confirme se a senha está correta: <strong>1.Angelopb</strong></li>";
    echo "<li>Verifique se o banco <strong>agro_marketplace_laravel</strong> existe no HeidiSQL</li>";
    echo "<li>Tente usar porta 3306 ou 3307</li>";
    echo "</ul>";
}

echo "<hr>";
echo "<h2>🚀 Próximos Passos:</h2>";
echo "<ol>";
echo "<li>Se a conexão funcionou, acesse: <a href='index-xampp.php' target='_blank'><strong>index-xampp.php</strong></a></li>";
echo "<li>Teste a API: <a href='api-xampp.php/status' target='_blank'><strong>api-xampp.php/status</strong></a></li>";
echo "<li>Se deu erro, ajuste as configurações acima e tente novamente</li>";
echo "</ol>";

echo "<p><small>Arquivo: teste-conexao-xampp.php | Data: " . date('Y-m-d H:i:s') . "</small></p>";
?>
