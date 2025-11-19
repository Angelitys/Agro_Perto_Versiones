<?php
// API completa do AgroPerto Marketplace - Versão Corrigida para XAMPP
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Configuração do banco XAMPP/MariaDB
$host = 'localhost';
$dbname = 'agro_marketplace_laravel';
$username = 'root';
$password = '1.Angelopb';
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

// Obter método e endpoint - VERSÃO CORRIGIDA
$method = $_SERVER['REQUEST_METHOD'];

// Processar a URL de forma mais robusta
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = $_SERVER['SCRIPT_NAME'];

// Remover o script name da URI para obter o path
$path = str_replace($scriptName, '', $requestUri);

// Remover query string se existir
$path = strtok($path, '?');

// Garantir que sempre comece com /
if (empty($path) || $path === '') {
    $path = '/status';
} elseif ($path[0] !== '/') {
    $path = '/' . $path;
}

// Dados de entrada
$input = json_decode(file_get_contents('php://input'), true);

// Debug - remover em produção
error_log("API Debug - Method: $method, Path: $path, URI: $requestUri, Script: $scriptName");

// Roteamento da API
switch ($path) {
    case '/':
    case '/status':
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM information_schema.tables WHERE table_schema = '$dbname'");
            $tableCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            jsonResponse([
                'status' => 'online',
                'message' => 'AgroPerto Marketplace API - Funcionando!',
                'database' => 'XAMPP/MariaDB conectado',
                'tables_found' => $tableCount,
                'timestamp' => date('Y-m-d H:i:s'),
                'version' => '1.0.1-fixed',
                'debug' => [
                    'method' => $method,
                    'path' => $path,
                    'request_uri' => $requestUri,
                    'script_name' => $scriptName
                ]
            ]);
        } catch(PDOException $e) {
            jsonResponse(['error' => 'Erro de banco: ' . $e->getMessage()], 500);
        }
        break;

    case '/categories':
        if ($method === 'GET') {
            try {
                $stmt = $pdo->query("SELECT * FROM categories WHERE active = 1 ORDER BY name");
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                jsonResponse(['categories' => $categories]);
            } catch(PDOException $e) {
                jsonResponse(['error' => 'Erro ao buscar categorias: ' . $e->getMessage()], 500);
            }
        } else {
            jsonResponse(['error' => 'Método não permitido para /categories'], 405);
        }
        break;

    case '/products':
        if ($method === 'GET') {
            try {
                $stmt = $pdo->query("
                    SELECT p.*, c.name as category_name, u.name as producer_name 
                    FROM products p 
                    JOIN categories c ON p.category_id = c.id 
                    JOIN users u ON p.user_id = u.id 
                    WHERE p.active = 1 
                    ORDER BY p.created_at DESC
                ");
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                jsonResponse(['products' => $products]);
            } catch(PDOException $e) {
                jsonResponse(['error' => 'Erro ao buscar produtos: ' . $e->getMessage()], 500);
            }
        } elseif ($method === 'POST') {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO products (name, slug, description, price, stock_quantity, unit, category_id, user_id, origin, harvest_date, available_from, available_until, fair_location, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
                ");
                
                $slug = strtolower(str_replace(' ', '-', $input['name']));
                
                $stmt->execute([
                    $input['name'],
                    $slug,
                    $input['description'],
                    $input['price'],
                    $input['stock_quantity'],
                    $input['unit'],
                    $input['category_id'],
                    $input['user_id'],
                    $input['origin'] ?? null,
                    $input['harvest_date'] ?? null,
                    $input['available_from'] ?? null,
                    $input['available_until'] ?? null,
                    $input['fair_location'] ?? null
                ]);
                
                jsonResponse(['message' => 'Produto criado com sucesso!', 'id' => $pdo->lastInsertId()], 201);
            } catch(PDOException $e) {
                jsonResponse(['error' => 'Erro ao criar produto: ' . $e->getMessage()], 500);
            }
        } else {
            jsonResponse(['error' => 'Método não permitido para /products'], 405);
        }
        break;

    case '/users':
        if ($method === 'GET') {
            try {
                $stmt = $pdo->query("SELECT id, name, email, type, created_at FROM users ORDER BY created_at DESC");
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                jsonResponse(['users' => $users]);
            } catch(PDOException $e) {
                jsonResponse(['error' => 'Erro ao buscar usuários: ' . $e->getMessage()], 500);
            }
        } else {
            jsonResponse(['error' => 'Método não permitido para /users'], 405);
        }
        break;

    case '/register':
        if ($method === 'POST') {
            try {
                // Verificar se email já existe
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$input['email']]);
                if ($stmt->fetch()) {
                    jsonResponse(['error' => 'Email já cadastrado'], 400);
                }
                
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, type, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
                $stmt->execute([
                    $input['name'],
                    $input['email'],
                    password_hash($input['password'], PASSWORD_DEFAULT),
                    $input['type'] ?? 'consumer'
                ]);
                
                jsonResponse(['message' => 'Usuário registrado com sucesso!', 'id' => $pdo->lastInsertId()], 201);
            } catch(PDOException $e) {
                jsonResponse(['error' => 'Erro ao registrar usuário: ' . $e->getMessage()], 500);
            }
        } else {
            jsonResponse(['error' => 'Método não permitido para /register'], 405);
        }
        break;

    case '/login':
        if ($method === 'POST') {
            try {
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->execute([$input['email']]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($user && password_verify($input['password'], $user['password'])) {
                    unset($user['password']);
                    jsonResponse(['message' => 'Login realizado com sucesso!', 'user' => $user]);
                } else {
                    jsonResponse(['error' => 'Credenciais inválidas'], 401);
                }
            } catch(PDOException $e) {
                jsonResponse(['error' => 'Erro no login: ' . $e->getMessage()], 500);
            }
        } else {
            jsonResponse(['error' => 'Método não permitido para /login'], 405);
        }
        break;

    case '/orders':
        if ($method === 'GET') {
            try {
                $stmt = $pdo->query("
                    SELECT o.*, u.name as customer_name 
                    FROM orders o 
                    JOIN users u ON o.user_id = u.id 
                    ORDER BY o.created_at DESC
                ");
                $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
                jsonResponse(['orders' => $orders]);
            } catch(PDOException $e) {
                jsonResponse(['error' => 'Erro ao buscar pedidos: ' . $e->getMessage()], 500);
            }
        } elseif ($method === 'POST') {
            try {
                $pdo->beginTransaction();
                
                // Criar pedido
                $stmt = $pdo->prepare("
                    INSERT INTO orders (user_id, total_amount, status, payment_method, pickup_date, created_at, updated_at) 
                    VALUES (?, ?, 'pending', ?, ?, NOW(), NOW())
                ");
                
                $totalAmount = 0;
                foreach ($input['items'] as $item) {
                    $totalAmount += $item['price'] * $item['quantity'];
                }
                
                $stmt->execute([
                    $input['user_id'],
                    $totalAmount,
                    $input['payment_method'] ?? 'cash',
                    $input['pickup_date'] ?? null
                ]);
                
                $orderId = $pdo->lastInsertId();
                
                // Adicionar itens do pedido
                $stmt = $pdo->prepare("
                    INSERT INTO order_items (order_id, product_id, quantity, price, total, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, NOW(), NOW())
                ");
                
                foreach ($input['items'] as $item) {
                    $stmt->execute([
                        $orderId,
                        $item['product_id'],
                        $item['quantity'],
                        $item['price'],
                        $item['price'] * $item['quantity']
                    ]);
                }
                
                $pdo->commit();
                jsonResponse(['message' => 'Pedido criado com sucesso!', 'order_id' => $orderId], 201);
            } catch(PDOException $e) {
                $pdo->rollBack();
                jsonResponse(['error' => 'Erro ao criar pedido: ' . $e->getMessage()], 500);
            }
        } else {
            jsonResponse(['error' => 'Método não permitido para /orders'], 405);
        }
        break;

    default:
        // Tentar processar URLs com parâmetros
        if (preg_match('/^\/products\/(\d+)$/', $path, $matches)) {
            $productId = $matches[1];
            if ($method === 'GET') {
                try {
                    $stmt = $pdo->prepare("
                        SELECT p.*, c.name as category_name, u.name as producer_name 
                        FROM products p 
                        JOIN categories c ON p.category_id = c.id 
                        JOIN users u ON p.user_id = u.id 
                        WHERE p.id = ?
                    ");
                    $stmt->execute([$productId]);
                    $product = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($product) {
                        jsonResponse(['product' => $product]);
                    } else {
                        jsonResponse(['error' => 'Produto não encontrado'], 404);
                    }
                } catch(PDOException $e) {
                    jsonResponse(['error' => 'Erro ao buscar produto: ' . $e->getMessage()], 500);
                }
            }
        } elseif (preg_match('/^\/categories\/(\d+)$/', $path, $matches)) {
            $categoryId = $matches[1];
            if ($method === 'GET') {
                try {
                    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
                    $stmt->execute([$categoryId]);
                    $category = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($category) {
                        jsonResponse(['category' => $category]);
                    } else {
                        jsonResponse(['error' => 'Categoria não encontrada'], 404);
                    }
                } catch(PDOException $e) {
                    jsonResponse(['error' => 'Erro ao buscar categoria: ' . $e->getMessage()], 500);
                }
            }
        } elseif (preg_match('/^\/products\/category\/(\d+)$/', $path, $matches)) {
            $categoryId = $matches[1];
            if ($method === 'GET') {
                try {
                    $stmt = $pdo->prepare("
                        SELECT p.*, c.name as category_name, u.name as producer_name 
                        FROM products p 
                        JOIN categories c ON p.category_id = c.id 
                        JOIN users u ON p.user_id = u.id 
                        WHERE p.category_id = ? AND p.active = 1
                    ");
                    $stmt->execute([$categoryId]);
                    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    jsonResponse(['products' => $products]);
                } catch(PDOException $e) {
                    jsonResponse(['error' => 'Erro ao buscar produtos da categoria: ' . $e->getMessage()], 500);
                }
            }
        } elseif ($path === '/cart/add' && $method === 'POST') {
            try {
                // Verificar se já existe carrinho para o usuário
                $stmt = $pdo->prepare("SELECT id FROM carts WHERE user_id = ?");
                $stmt->execute([$input['user_id']]);
                $cart = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$cart) {
                    // Criar carrinho
                    $stmt = $pdo->prepare("INSERT INTO carts (user_id, created_at, updated_at) VALUES (?, NOW(), NOW())");
                    $stmt->execute([$input['user_id']]);
                    $cartId = $pdo->lastInsertId();
                } else {
                    $cartId = $cart['id'];
                }
                
                // Adicionar item ao carrinho
                $stmt = $pdo->prepare("
                    INSERT INTO cart_items (cart_id, product_id, quantity, price, created_at, updated_at) 
                    VALUES (?, ?, ?, (SELECT price FROM products WHERE id = ?), NOW(), NOW())
                ");
                $stmt->execute([$cartId, $input['product_id'], $input['quantity'], $input['product_id']]);
                
                jsonResponse(['message' => 'Item adicionado ao carrinho!'], 201);
            } catch(PDOException $e) {
                jsonResponse(['error' => 'Erro ao adicionar ao carrinho: ' . $e->getMessage()], 500);
            }
        } elseif (preg_match('/^\/cart\/(\d+)$/', $path, $matches)) {
            $userId = $matches[1];
            if ($method === 'GET') {
                try {
                    $stmt = $pdo->prepare("
                        SELECT ci.*, p.name as product_name, p.price as product_price 
                        FROM cart_items ci 
                        JOIN carts c ON ci.cart_id = c.id 
                        JOIN products p ON ci.product_id = p.id 
                        WHERE c.user_id = ?
                    ");
                    $stmt->execute([$userId]);
                    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    jsonResponse(['cart_items' => $items]);
                } catch(PDOException $e) {
                    jsonResponse(['error' => 'Erro ao buscar carrinho: ' . $e->getMessage()], 500);
                }
            }
        } else {
            jsonResponse([
                'error' => 'Endpoint não encontrado',
                'path' => $path,
                'method' => $method,
                'available_endpoints' => [
                    'GET /status',
                    'GET /categories',
                    'GET /products',
                    'GET /products/{id}',
                    'GET /categories/{id}',
                    'GET /products/category/{id}',
                    'GET /users',
                    'POST /register',
                    'POST /login',
                    'GET /orders',
                    'POST /orders',
                    'POST /cart/add',
                    'GET /cart/{user_id}'
                ]
            ], 404);
        }
        break;
}
?>
