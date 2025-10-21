<?php
// API XAMPP - Versão com Query Parameters
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Configuração BD
$host = 'localhost';
$dbname = 'agro_marketplace_laravel';
$username = 'root';
$password = '1.Angelopb';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die(json_encode(['error' => 'Conexão falhou: ' . $e->getMessage()]));
}

function jsonResponse($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Obter parâmetros
$method = $_SERVER['REQUEST_METHOD'];
$path = $_GET['path'] ?? '/status';
$input = json_decode(file_get_contents('php://input'), true) ?: [];

// Log para debug
error_log("API Call - Method: $method, Path: $path");

// Roteamento
switch ($path) {
    case '/status':
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
            $productCount = $stmt->fetch()['count'];
            
            jsonResponse([
                'status' => 'online',
                'message' => 'API XAMPP funcionando!',
                'products' => $productCount,
                'timestamp' => date('Y-m-d H:i:s'),
                'method' => $method,
                'path' => $path
            ]);
        } catch(Exception $e) {
            jsonResponse(['error' => $e->getMessage()], 500);
        }
        break;

    case '/categories':
        try {
            $stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonResponse(['categories' => $categories]);
        } catch(Exception $e) {
            jsonResponse(['error' => $e->getMessage()], 500);
        }
        break;

    case '/products':
        if ($method === 'GET') {
            try {
                $stmt = $pdo->query("
                    SELECT p.*, c.name as category_name, u.name as producer_name 
                    FROM products p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    LEFT JOIN users u ON p.user_id = u.id 
                    WHERE p.active = 1 
                    ORDER BY p.created_at DESC
                ");
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                jsonResponse(['products' => $products]);
            } catch(Exception $e) {
                jsonResponse(['error' => $e->getMessage()], 500);
            }
        } elseif ($method === 'POST') {
            try {
                $slug = strtolower(str_replace(' ', '-', $input['name']));
                $stmt = $pdo->prepare("
                    INSERT INTO products (name, slug, description, price, stock_quantity, unit, category_id, user_id, origin, fair_location, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
                ");
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
                    $input['fair_location'] ?? null
                ]);
                
                jsonResponse(['message' => 'Produto criado com sucesso!', 'id' => $pdo->lastInsertId()], 201);
            } catch(Exception $e) {
                jsonResponse(['error' => $e->getMessage()], 500);
            }
        }
        break;

    case '/users':
        try {
            $stmt = $pdo->query("SELECT id, name, email, type, created_at FROM users ORDER BY created_at DESC");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonResponse(['users' => $users]);
        } catch(Exception $e) {
            jsonResponse(['error' => $e->getMessage()], 500);
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
            } catch(Exception $e) {
                jsonResponse(['error' => $e->getMessage()], 500);
            }
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
            } catch(Exception $e) {
                jsonResponse(['error' => $e->getMessage()], 500);
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
                jsonResponse(['orders' => $orders]);
            } catch(Exception $e) {
                jsonResponse(['error' => $e->getMessage()], 500);
            }
        } elseif ($method === 'POST') {
            try {
                $total = 0;
                foreach ($input['items'] as $item) {
                    $total += $item['price'] * $item['quantity'];
                }
                
                $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status, payment_method, pickup_date, created_at, updated_at) VALUES (?, ?, 'pending', ?, ?, NOW(), NOW())");
                $stmt->execute([
                    $input['user_id'],
                    $total,
                    $input['payment_method'] ?? 'cash',
                    $input['pickup_date'] ?? null
                ]);
                
                $orderId = $pdo->lastInsertId();
                
                // Adicionar itens do pedido
                $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, total, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
                foreach ($input['items'] as $item) {
                    $stmt->execute([
                        $orderId,
                        $item['product_id'],
                        $item['quantity'],
                        $item['price'],
                        $item['price'] * $item['quantity']
                    ]);
                }
                
                jsonResponse(['message' => 'Pedido criado com sucesso!', 'order_id' => $orderId], 201);
            } catch(Exception $e) {
                jsonResponse(['error' => $e->getMessage()], 500);
            }
        }
        break;

    case '/dashboard':
        try {
            // Estatísticas básicas
            $stmt = $pdo->query("SELECT COUNT(*) as total_products FROM products");
            $totalProducts = $stmt->fetch()['total_products'];
            
            $stmt = $pdo->query("SELECT COUNT(*) as total_orders FROM orders");
            $totalOrders = $stmt->fetch()['total_orders'];
            
            $stmt = $pdo->query("SELECT COALESCE(SUM(total_amount), 0) as total_revenue FROM orders WHERE status != 'cancelled'");
            $totalRevenue = $stmt->fetch()['total_revenue'];
            
            jsonResponse([
                'dashboard' => [
                    'total_products' => $totalProducts,
                    'total_orders' => $totalOrders,
                    'total_revenue' => $totalRevenue,
                    'recent_orders' => []
                ]
            ]);
        } catch(Exception $e) {
            jsonResponse(['error' => $e->getMessage()], 500);
        }
        break;

    default:
        // Tentar processar URLs com parâmetros
        if (preg_match('/^\/products\/(\d+)$/', $path, $matches)) {
            $productId = $matches[1];
            try {
                $stmt = $pdo->prepare("
                    SELECT p.*, c.name as category_name, u.name as producer_name 
                    FROM products p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    LEFT JOIN users u ON p.user_id = u.id 
                    WHERE p.id = ?
                ");
                $stmt->execute([$productId]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($product) {
                    jsonResponse(['product' => $product]);
                } else {
                    jsonResponse(['error' => 'Produto não encontrado'], 404);
                }
            } catch(Exception $e) {
                jsonResponse(['error' => $e->getMessage()], 500);
            }
        } else {
            jsonResponse([
                'error' => 'Endpoint não encontrado',
                'path' => $path,
                'method' => $method,
                'available_endpoints' => [
                    'GET ?path=/status',
                    'GET ?path=/categories',
                    'GET ?path=/products',
                    'GET ?path=/users',
                    'POST ?path=/register',
                    'POST ?path=/login',
                    'GET ?path=/orders',
                    'POST ?path=/orders',
                    'GET ?path=/dashboard'
                ]
            ], 404);
        }
        break;
}
?>
