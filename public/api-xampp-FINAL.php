<?php
// API FINAL - GARANTIDA PARA FUNCIONAR
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Configuração XAMPP
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
    die(json_encode(['error' => 'Conexão falhou: ' . $e->getMessage()]));
}

function jsonResponse($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Processar URL
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Extrair path
$path = parse_url($uri, PHP_URL_PATH);
$path = str_replace('/agro-marketplace-alpha/public/api-xampp-FINAL.php', '', $path);
$path = str_replace('/agro-marketplace-alpha/public/api-xampp.php', '', $path);

if (empty($path) || $path === '/') {
    $path = '/status';
}

// Input data
$input = json_decode(file_get_contents('php://input'), true) ?: [];

// ROTEAMENTO SIMPLES E DIRETO
switch (true) {
    case ($path === '/status'):
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
            $productCount = $stmt->fetch()['count'];
            
            jsonResponse([
                'status' => 'online',
                'message' => 'API funcionando!',
                'products' => $productCount,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } catch(Exception $e) {
            jsonResponse(['error' => $e->getMessage()], 500);
        }
        break;

    case ($path === '/categories'):
        try {
            $stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonResponse(['categories' => $categories]);
        } catch(Exception $e) {
            jsonResponse(['error' => $e->getMessage()], 500);
        }
        break;

    case ($path === '/products'):
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
                $stmt = $pdo->prepare("
                    INSERT INTO products (name, slug, description, price, stock_quantity, unit, category_id, user_id, origin, harvest_date, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
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
                    $input['harvest_date'] ?? null
                ]);
                
                jsonResponse(['message' => 'Produto criado!', 'id' => $pdo->lastInsertId()], 201);
            } catch(Exception $e) {
                jsonResponse(['error' => $e->getMessage()], 500);
            }
        }
        break;

    case (preg_match('/^\/products\/(\d+)$/', $path, $matches)):
        $id = $matches[1];
        try {
            $stmt = $pdo->prepare("
                SELECT p.*, c.name as category_name, u.name as producer_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN users u ON p.user_id = u.id 
                WHERE p.id = ?
            ");
            $stmt->execute([$id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($product) {
                jsonResponse(['product' => $product]);
            } else {
                jsonResponse(['error' => 'Produto não encontrado'], 404);
            }
        } catch(Exception $e) {
            jsonResponse(['error' => $e->getMessage()], 500);
        }
        break;

    case (preg_match('/^\/products\/category\/(\d+)$/', $path, $matches)):
        $categoryId = $matches[1];
        try {
            $stmt = $pdo->prepare("
                SELECT p.*, c.name as category_name, u.name as producer_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN users u ON p.user_id = u.id 
                WHERE p.category_id = ? AND p.active = 1
            ");
            $stmt->execute([$categoryId]);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonResponse(['products' => $products]);
        } catch(Exception $e) {
            jsonResponse(['error' => $e->getMessage()], 500);
        }
        break;

    case ($path === '/users'):
        try {
            $stmt = $pdo->query("SELECT id, name, email, type, created_at FROM users");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonResponse(['users' => $users]);
        } catch(Exception $e) {
            jsonResponse(['error' => $e->getMessage()], 500);
        }
        break;

    case ($path === '/register' && $method === 'POST'):
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$input['email']]);
            if ($stmt->fetch()) {
                jsonResponse(['error' => 'Email já existe'], 400);
            }
            
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, type, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
            $stmt->execute([
                $input['name'],
                $input['email'],
                password_hash($input['password'], PASSWORD_DEFAULT),
                $input['type'] ?? 'consumer'
            ]);
            
            jsonResponse(['message' => 'Usuário criado!', 'id' => $pdo->lastInsertId()], 201);
        } catch(Exception $e) {
            jsonResponse(['error' => $e->getMessage()], 500);
        }
        break;

    case ($path === '/login' && $method === 'POST'):
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$input['email']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($input['password'], $user['password'])) {
                unset($user['password']);
                jsonResponse(['message' => 'Login OK!', 'user' => $user]);
            } else {
                jsonResponse(['error' => 'Login inválido'], 401);
            }
        } catch(Exception $e) {
            jsonResponse(['error' => $e->getMessage()], 500);
        }
        break;

    case ($path === '/orders'):
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
                
                jsonResponse(['message' => 'Pedido criado!', 'order_id' => $orderId], 201);
            } catch(Exception $e) {
                jsonResponse(['error' => $e->getMessage()], 500);
            }
        }
        break;

    case ($path === '/cart/add' && $method === 'POST'):
        try {
            $stmt = $pdo->prepare("SELECT id FROM carts WHERE user_id = ?");
            $stmt->execute([$input['user_id']]);
            $cart = $stmt->fetch();
            
            if (!$cart) {
                $stmt = $pdo->prepare("INSERT INTO carts (user_id, created_at, updated_at) VALUES (?, NOW(), NOW())");
                $stmt->execute([$input['user_id']]);
                $cartId = $pdo->lastInsertId();
            } else {
                $cartId = $cart['id'];
            }
            
            $stmt = $pdo->prepare("INSERT INTO cart_items (cart_id, product_id, quantity, price, created_at, updated_at) VALUES (?, ?, ?, (SELECT price FROM products WHERE id = ?), NOW(), NOW())");
            $stmt->execute([$cartId, $input['product_id'], $input['quantity'], $input['product_id']]);
            
            jsonResponse(['message' => 'Adicionado ao carrinho!'], 201);
        } catch(Exception $e) {
            jsonResponse(['error' => $e->getMessage()], 500);
        }
        break;

    case (preg_match('/^\/cart\/(\d+)$/', $path, $matches)):
        $userId = $matches[1];
        try {
            $stmt = $pdo->prepare("
                SELECT ci.*, p.name as product_name, p.price as product_price 
                FROM cart_items ci 
                LEFT JOIN carts c ON ci.cart_id = c.id 
                LEFT JOIN products p ON ci.product_id = p.id 
                WHERE c.user_id = ?
            ");
            $stmt->execute([$userId]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonResponse(['cart_items' => $items]);
        } catch(Exception $e) {
            jsonResponse(['error' => $e->getMessage()], 500);
        }
        break;

    case ($path === '/reviews' && $method === 'POST'):
        try {
            $stmt = $pdo->prepare("INSERT INTO reviews (user_id, product_id, rating, title, comment, verified_purchase, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, 1, 'approved', NOW(), NOW())");
            $stmt->execute([
                $input['user_id'],
                $input['product_id'],
                $input['rating'],
                $input['title'] ?? null,
                $input['comment'] ?? null
            ]);
            
            jsonResponse(['message' => 'Avaliação adicionada!'], 201);
        } catch(Exception $e) {
            jsonResponse(['error' => $e->getMessage()], 500);
        }
        break;

    case (preg_match('/^\/products\/(\d+)\/reviews$/', $path, $matches)):
        $productId = $matches[1];
        try {
            $stmt = $pdo->prepare("
                SELECT r.*, u.name as user_name 
                FROM reviews r 
                LEFT JOIN users u ON r.user_id = u.id 
                WHERE r.product_id = ? AND r.status = 'approved'
            ");
            $stmt->execute([$productId]);
            $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonResponse(['reviews' => $reviews]);
        } catch(Exception $e) {
            jsonResponse(['error' => $e->getMessage()], 500);
        }
        break;

    case (preg_match('/^\/products\/search/', $path)):
        $query = $_GET['q'] ?? '';
        try {
            $stmt = $pdo->prepare("
                SELECT p.*, c.name as category_name, u.name as producer_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN users u ON p.user_id = u.id 
                WHERE p.name LIKE ? AND p.active = 1
            ");
            $stmt->execute(['%' . $query . '%']);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonResponse(['products' => $products]);
        } catch(Exception $e) {
            jsonResponse(['error' => $e->getMessage()], 500);
        }
        break;

    case ($path === '/products/available'):
        try {
            $stmt = $pdo->query("
                SELECT p.*, c.name as category_name, u.name as producer_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN users u ON p.user_id = u.id 
                WHERE p.active = 1 
                AND (p.available_from IS NULL OR p.available_from <= NOW()) 
                AND (p.available_until IS NULL OR p.available_until >= NOW())
            ");
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonResponse(['products' => $products]);
        } catch(Exception $e) {
            jsonResponse(['error' => $e->getMessage()], 500);
        }
        break;

    case (preg_match('/^\/users\/(\d+)\/products$/', $path, $matches)):
        $userId = $matches[1];
        try {
            $stmt = $pdo->prepare("
                SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.user_id = ?
            ");
            $stmt->execute([$userId]);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonResponse(['products' => $products]);
        } catch(Exception $e) {
            jsonResponse(['error' => $e->getMessage()], 500);
        }
        break;

    default:
        jsonResponse([
            'error' => 'Endpoint não encontrado',
            'path' => $path,
            'method' => $method,
            'available' => [
                'GET /status',
                'GET /categories', 
                'GET /products',
                'GET /products/{id}',
                'GET /products/category/{id}',
                'GET /users',
                'POST /register',
                'POST /login',
                'GET /orders',
                'POST /orders'
            ]
        ], 404);
}
?>
