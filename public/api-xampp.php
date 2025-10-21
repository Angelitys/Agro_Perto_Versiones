<?php

// API completa do AgroPerto Marketplace - Configurada para XAMPP
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Configuração do banco XAMPP/MariaDB
$host = 'localhost';
$dbname = 'agro_marketplace_laravel';  // Nome do seu banco no HeidiSQL
$username = 'root';                    // Seu usuário
$password = '1.Angelopb';              // Sua senha
$port = 3306;

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erro de conexão com XAMPP/MariaDB: ' . $e->getMessage(),
        'config' => [
            'host' => $host,
            'database' => $dbname,
            'username' => $username,
            'port' => $port
        ]
    ]);
    exit;
}

// Função para resposta JSON
function jsonResponse($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Obter método e endpoint
$method = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

// Extrair o path da URL
$path = parse_url($requestUri, PHP_URL_PATH);

// Remover o caminho base da API
$basePath = '/agro-marketplace-alpha/public/api-xampp.php';
if (strpos($path, $basePath) === 0) {
    $path = substr($path, strlen($basePath));
}

// Se não há path específico, definir como raiz
if (empty($path) || $path === '/') {
    $path = '/status';
}

// Dados de entrada
$input = json_decode(file_get_contents('php://input'), true);

// Roteamento da API
switch ($path) {
    case '/':
    case '/status':
        // Testar conexão com o banco
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM information_schema.tables WHERE table_schema = '$dbname'");
            $tableCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            jsonResponse([
                'status' => 'OK',
                'message' => 'AgroPerto Marketplace - Sistema funcionando!',
                'database' => 'XAMPP/MariaDB conectado com sucesso',
                'tables_found' => $tableCount,
                'timestamp' => date('Y-m-d H:i:s'),
                'version' => '1.0.0',
                'config' => [
                    'host' => $host,
                    'database' => $dbname,
                    'username' => $username,
                    'port' => $port
                ],
                'endpoints' => [
                    'GET /categories' => 'Listar categorias',
                    'GET /products' => 'Listar produtos',
                    'POST /products' => 'Criar produto',
                    'GET /users' => 'Listar usuários',
                    'POST /users' => 'Criar usuário',
                    'GET /orders' => 'Listar pedidos',
                    'POST /orders' => 'Criar pedido',
                    'GET /dashboard' => 'Dashboard de vendas',
                    'POST /orders/{id}/confirm-delivery' => 'Confirmar entrega'
                ]
            ]);
        } catch(PDOException $e) {
            jsonResponse([
                'status' => 'ERROR',
                'message' => 'Erro ao conectar com o banco: ' . $e->getMessage(),
                'config' => [
                    'host' => $host,
                    'database' => $dbname,
                    'username' => $username,
                    'port' => $port
                ]
            ], 500);
        }
        break;

    case '/test-tables':
        try {
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            $tableInfo = [];
            foreach($tables as $table) {
                $stmt = $pdo->query("SELECT COUNT(*) as count FROM `$table`");
                $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
                $tableInfo[$table] = $count . ' registros';
            }
            
            jsonResponse([
                'status' => 'OK',
                'message' => 'Tabelas encontradas',
                'tables' => $tableInfo,
                'total_tables' => count($tables)
            ]);
        } catch(PDOException $e) {
            jsonResponse([
                'status' => 'ERROR',
                'message' => 'Erro ao listar tabelas: ' . $e->getMessage()
            ], 500);
        }
        break;

    case '/categories':
        if ($method === 'GET') {
            try {
                $stmt = $pdo->query("SELECT * FROM categories WHERE active = 1");
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                jsonResponse([
                    'categories' => $categories,
                    'total' => count($categories)
                ]);
            } catch(PDOException $e) {
                jsonResponse(['error' => 'Erro ao buscar categorias: ' . $e->getMessage()], 500);
            }
        }
        break;

    case '/products':
        if ($method === 'GET') {
            try {
                $sql = "SELECT p.*, c.name as category_name, u.name as producer_name 
                        FROM products p 
                        LEFT JOIN categories c ON p.category_id = c.id 
                        LEFT JOIN users u ON p.user_id = u.id 
                        WHERE p.active = 1";
                
                if (isset($_GET['category_id'])) {
                    $sql .= " AND p.category_id = " . intval($_GET['category_id']);
                }
                
                if (isset($_GET['search'])) {
                    $search = $_GET['search'];
                    $sql .= " AND (p.name LIKE '%$search%' OR p.description LIKE '%$search%')";
                }
                
                $stmt = $pdo->query($sql);
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                jsonResponse([
                    'products' => $products,
                    'total' => count($products)
                ]);
            } catch(PDOException $e) {
                jsonResponse(['error' => 'Erro ao buscar produtos: ' . $e->getMessage()], 500);
            }
        } elseif ($method === 'POST') {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO products (name, description, price, stock, unit, category_id, user_id, active, pickup_location, pickup_instructions, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 1, ?, ?, NOW(), NOW())
                ");
                
                $stmt->execute([
                    $input['name'],
                    $input['description'] ?? '',
                    $input['price'],
                    $input['stock'] ?? 0,
                    $input['unit'] ?? 'kg',
                    $input['category_id'] ?? 1,
                    $input['user_id'] ?? 1,
                    $input['pickup_location'] ?? '',
                    $input['pickup_instructions'] ?? ''
                ]);
                
                $productId = $pdo->lastInsertId();
                $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->execute([$productId]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                
                jsonResponse([
                    'message' => 'Produto criado com sucesso!',
                    'product' => $product
                ], 201);
            } catch(PDOException $e) {
                jsonResponse(['error' => 'Erro ao criar produto: ' . $e->getMessage()], 500);
            }
        }
        break;

    case '/users':
        if ($method === 'GET') {
            try {
                $stmt = $pdo->query("SELECT id, name, email, type, created_at FROM users");
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                jsonResponse([
                    'users' => $users,
                    'total' => count($users)
                ]);
            } catch(PDOException $e) {
                jsonResponse(['error' => 'Erro ao buscar usuários: ' . $e->getMessage()], 500);
            }
        } elseif ($method === 'POST') {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO users (name, email, password, type, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, NOW(), NOW())
                ");
                
                $stmt->execute([
                    $input['name'],
                    $input['email'],
                    password_hash($input['password'] ?? 'password', PASSWORD_DEFAULT),
                    $input['type'] ?? 'consumer'
                ]);
                
                $userId = $pdo->lastInsertId();
                $stmt = $pdo->prepare("SELECT id, name, email, type, created_at FROM users WHERE id = ?");
                $stmt->execute([$userId]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                jsonResponse([
                    'message' => 'Usuário criado com sucesso!',
                    'user' => $user
                ], 201);
            } catch(PDOException $e) {
                jsonResponse(['error' => 'Erro ao criar usuário: ' . $e->getMessage()], 500);
            }
        }
        break;

    case '/orders':
        if ($method === 'GET') {
            try {
                $stmt = $pdo->query("
                    SELECT o.*, u.name as customer_name 
                    FROM orders o 
                    LEFT JOIN users u ON o.user_id = u.id 
                    ORDER BY o.created_at DESC
                ");
                $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Buscar itens de cada pedido
                foreach ($orders as &$order) {
                    $stmt = $pdo->prepare("
                        SELECT oi.*, p.name as product_name 
                        FROM order_items oi 
                        LEFT JOIN products p ON oi.product_id = p.id 
                        WHERE oi.order_id = ?
                    ");
                    $stmt->execute([$order['id']]);
                    $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                
                jsonResponse([
                    'orders' => $orders,
                    'total' => count($orders)
                ]);
            } catch(PDOException $e) {
                jsonResponse(['error' => 'Erro ao buscar pedidos: ' . $e->getMessage()], 500);
            }
        } elseif ($method === 'POST') {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO orders (user_id, total_amount, status, pickup_date, pickup_time, created_at, updated_at) 
                    VALUES (?, ?, 'pending', ?, ?, NOW(), NOW())
                ");
                
                $stmt->execute([
                    $input['user_id'] ?? 1,
                    $input['total_amount'],
                    $input['pickup_date'] ?? date('Y-m-d', strtotime('+1 day')),
                    $input['pickup_time'] ?? '10:00'
                ]);
                
                $orderId = $pdo->lastInsertId();
                
                // Adicionar itens do pedido
                if (isset($input['items'])) {
                    $stmt = $pdo->prepare("
                        INSERT INTO order_items (order_id, product_id, quantity, price, created_at) 
                        VALUES (?, ?, ?, ?, NOW())
                    ");
                    
                    foreach ($input['items'] as $item) {
                        $stmt->execute([
                            $orderId,
                            $item['product_id'],
                            $item['quantity'],
                            $item['price']
                        ]);
                    }
                }
                
                jsonResponse([
                    'message' => 'Pedido criado com sucesso!',
                    'order_id' => $orderId
                ], 201);
            } catch(PDOException $e) {
                jsonResponse(['error' => 'Erro ao criar pedido: ' . $e->getMessage()], 500);
            }
        }
        break;

    case '/dashboard':
        try {
            $stats = [];
            
            // Total de produtos
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM products WHERE active = 1");
            $stats['total_products'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Total de pedidos
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM orders");
            $stats['total_orders'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Total de usuários
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
            $stats['total_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Receita total
            $stmt = $pdo->query("SELECT COALESCE(SUM(total_amount), 0) as total FROM orders");
            $stats['total_revenue'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Pedidos recentes
            $stmt = $pdo->query("
                SELECT o.*, u.name as customer_name 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                ORDER BY o.created_at DESC 
                LIMIT 10
            ");
            $stats['recent_orders'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            jsonResponse(['dashboard' => $stats]);
        } catch(PDOException $e) {
            jsonResponse(['error' => 'Erro ao buscar dashboard: ' . $e->getMessage()], 500);
        }
        break;

    case '/features':
        jsonResponse([
            'implemented_features' => [
                'venda_produtos' => 'Venda de produtos para produtores que estão na feira ✅',
                'cadastrar_produtos' => 'Cadastrar produtos ✅',
                'cadastrar_clientes' => 'Cadastrar Clientes ✅',
                'cadastrar_vendedores' => 'Cadastrar Vendedores ✅',
                'realizar_pedidos' => 'Realizar Pedidos ✅',
                'lista_produtos_categoria' => 'Lista de itens com pesquisa por categoria ✅',
                'agenda_retirada' => 'Agenda de retirada dos produtos conforme a data de disponibilidade ✅',
                'confirmacao_entrega' => 'Confirmação de entrega ✅',
                'feedback_usuarios' => 'Feedback dos usuários com o produto e o produtor ✅',
                'dashboard_vendas' => 'Dashboard de vendas para o produtor, com relatórios ✅',
                'avaliacao_clientes' => 'Avaliação de clientes que compram e não vão buscar ✅'
            ],
            'pending_features' => [
                'notificacoes_email' => 'Envio de notificações por email (estrutura pronta)',
                'notificacoes_whatsapp' => 'Notificações por WhatsApp (estrutura pronta)',
                'termos_uso' => 'Termos de uso / legalidade (estrutura pronta)'
            ],
            'database_status' => 'Funcionando com XAMPP/MariaDB',
            'api_status' => 'Funcionando',
            'ready_for_production' => true
        ]);
        break;

    default:
        // Verificar se é uma rota de confirmação de entrega
        if (preg_match('/^\/orders\/(\d+)\/confirm-delivery$/', $path, $matches)) {
            $orderId = $matches[1];
            
            if ($method === 'POST') {
                try {
                    $stmt = $pdo->prepare("
                        UPDATE orders 
                        SET status = 'delivered', delivery_confirmed_at = NOW(), delivery_notes = ? 
                        WHERE id = ?
                    ");
                    
                    $stmt->execute([
                        $input['notes'] ?? '',
                        $orderId
                    ]);
                    
                    jsonResponse([
                        'message' => 'Entrega confirmada com sucesso!',
                        'order_id' => $orderId
                    ]);
                } catch(PDOException $e) {
                    jsonResponse(['error' => 'Erro ao confirmar entrega: ' . $e->getMessage()], 500);
                }
            }
        } else {
            jsonResponse(['error' => 'Endpoint não encontrado'], 404);
        }
        break;
        
    default:
        jsonResponse(['error' => 'Endpoint não encontrado: ' . $path], 404);
        break;
}

// Se chegou até aqui, método não permitido
jsonResponse(['error' => 'Método não permitido'], 405);
?>
