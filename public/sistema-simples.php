<?php
// Sistema AgroPerto - Versão Simples que Funciona
session_start();

// Configuração BD
$host = 'localhost';
$dbname = 'agro_marketplace_laravel';
$username = 'root';
$password = '1.Angelopb';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// Processar ações
$action = $_GET['action'] ?? 'home';
$message = '';
$error = '';

// Processar formulários
if ($_POST) {
    switch ($_POST['form_type']) {
        case 'register':
            try {
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$_POST['email']]);
                if ($stmt->fetch()) {
                    $error = "Email já cadastrado!";
                } else {
                    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, type) VALUES (?, ?, ?, ?)");
                    $stmt->execute([
                        $_POST['name'],
                        $_POST['email'],
                        password_hash($_POST['password'], PASSWORD_DEFAULT),
                        $_POST['type']
                    ]);
                    $message = "Conta criada com sucesso!";
                }
            } catch(Exception $e) {
                $error = "Erro ao criar conta: " . $e->getMessage();
            }
            break;
            
        case 'login':
            try {
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->execute([$_POST['email']]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($user && password_verify($_POST['password'], $user['password'])) {
                    $_SESSION['user'] = $user;
                    $message = "Login realizado com sucesso!";
                } else {
                    $error = "Email ou senha incorretos!";
                }
            } catch(Exception $e) {
                $error = "Erro no login: " . $e->getMessage();
            }
            break;
            
        case 'add_product':
            if (isset($_SESSION['user']) && $_SESSION['user']['type'] === 'producer') {
                try {
                    $slug = strtolower(str_replace(' ', '-', $_POST['name']));
                    $stmt = $pdo->prepare("INSERT INTO products (name, slug, description, price, stock_quantity, unit, category_id, user_id, origin, fair_location) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([
                        $_POST['name'],
                        $slug,
                        $_POST['description'],
                        $_POST['price'],
                        $_POST['stock_quantity'],
                        $_POST['unit'],
                        $_POST['category_id'],
                        $_SESSION['user']['id'],
                        $_POST['origin'],
                        $_POST['fair_location']
                    ]);
                    $message = "Produto adicionado com sucesso!";
                } catch(Exception $e) {
                    $error = "Erro ao adicionar produto: " . $e->getMessage();
                }
            }
            break;
    }
}

// Logout
if ($action === 'logout') {
    session_destroy();
    header('Location: sistema-simples.php');
    exit;
}

// Buscar dados
try {
    $categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
    $products = $pdo->query("
        SELECT p.*, c.name as category_name, u.name as producer_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        LEFT JOIN users u ON p.user_id = u.id 
        WHERE p.active = 1 
        ORDER BY p.created_at DESC
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    $error = "Erro ao carregar dados: " . $e->getMessage();
    $categories = [];
    $products = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroPerto - Produtos Frescos da Agricultura Familiar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .fade-in { animation: fadeIn 0.5s ease-in; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="sistema-simples.php" class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">AgroPerto</h1>
                            <p class="text-xs text-gray-500">Agricultura Familiar</p>
                        </div>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="flex items-center space-x-6">
                    <?php if (isset($_SESSION['user'])): ?>
                        <span class="text-gray-600">Olá, <?= htmlspecialchars($_SESSION['user']['name']) ?>!</span>
                        <?php if ($_SESSION['user']['type'] === 'producer'): ?>
                            <a href="?action=dashboard" class="text-green-600 hover:text-green-700">Dashboard</a>
                        <?php endif; ?>
                        <a href="?action=logout" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Sair</a>
                    <?php else: ?>
                        <a href="?action=login" class="text-gray-600 hover:text-green-600">Entrar</a>
                        <a href="?action=register" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Cadastrar</a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>

    <!-- Messages -->
    <?php if ($message): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mx-4 mt-4">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mx-4 mt-4">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Content -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        
        <?php if ($action === 'home' || $action === ''): ?>
            <!-- Home Page -->
            <div class="text-center mb-12">
                <div class="bg-green-600 text-white py-16 px-8 rounded-lg mb-8">
                    <h2 class="text-4xl font-bold mb-4">Produtos Frescos Direto do Campo</h2>
                    <p class="text-xl text-green-100 mb-8">Conectamos você diretamente aos produtores rurais. Produtos frescos, orgânicos e sustentáveis entregues na sua porta.</p>
                    <div class="flex justify-center space-x-4">
                        <a href="#produtos" class="bg-white text-green-600 px-6 py-3 rounded-lg font-semibold hover:bg-green-50">Ver Produtos</a>
                        <?php if (!isset($_SESSION['user'])): ?>
                            <a href="?action=register" class="border border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700">Cadastre-se</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Categories -->
            <div class="mb-12">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Categorias</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <?php foreach ($categories as $category): ?>
                        <div class="bg-white rounded-lg shadow-sm p-6 text-center hover:shadow-md transition-shadow">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-2xl">🥕</span>
                            </div>
                            <h4 class="font-semibold text-gray-900"><?= htmlspecialchars($category['name']) ?></h4>
                            <p class="text-sm text-gray-600 mt-2"><?= htmlspecialchars($category['description']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Products -->
            <div id="produtos" class="mb-12">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Produtos Disponíveis</h3>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($products as $product): ?>
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                            <div class="h-48 bg-green-100 flex items-center justify-center">
                                <span class="text-4xl">🥬</span>
                            </div>
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded"><?= htmlspecialchars($product['category_name']) ?></span>
                                    <span class="text-gray-500 text-sm"><?= htmlspecialchars($product['stock_quantity']) ?> <?= htmlspecialchars($product['unit']) ?></span>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2"><?= htmlspecialchars($product['name']) ?></h4>
                                <p class="text-gray-600 text-sm mb-4"><?= htmlspecialchars($product['description']) ?></p>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="text-2xl font-bold text-green-600">€<?= number_format($product['price'], 2) ?></span>
                                        <span class="text-gray-500 text-sm">/ <?= htmlspecialchars($product['unit']) ?></span>
                                    </div>
                                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['type'] === 'consumer'): ?>
                                        <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Adicionar</button>
                                    <?php endif; ?>
                                </div>
                                <?php if ($product['producer_name']): ?>
                                    <p class="text-xs text-gray-500 mt-2">📍 <?= htmlspecialchars($product['producer_name']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        <?php elseif ($action === 'register'): ?>
            <!-- Register Form -->
            <div class="max-w-md mx-auto bg-white rounded-lg shadow-sm p-8">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Criar conta</h2>
                    <p class="text-green-600 mt-2">Ou <a href="?action=login" class="underline">entre na sua conta existente</a></p>
                </div>

                <form method="POST" class="space-y-6">
                    <input type="hidden" name="form_type" value="register">
                    
                    <div>
                        <input type="text" name="name" placeholder="Nome completo" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <input type="email" name="email" placeholder="Email" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <select name="type" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Selecione o tipo de conta</option>
                            <option value="consumer">Consumidor</option>
                            <option value="producer">Produtor</option>
                        </select>
                    </div>
                    
                    <div>
                        <input type="password" name="password" placeholder="Senha" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    
                    <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700">
                        Criar Conta
                    </button>
                </form>
            </div>

        <?php elseif ($action === 'login'): ?>
            <!-- Login Form -->
            <div class="max-w-md mx-auto bg-white rounded-lg shadow-sm p-8">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Entrar</h2>
                    <p class="text-green-600 mt-2">Ou <a href="?action=register" class="underline">crie uma nova conta</a></p>
                </div>

                <form method="POST" class="space-y-6">
                    <input type="hidden" name="form_type" value="login">
                    
                    <div>
                        <input type="email" name="email" placeholder="Email" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <input type="password" name="password" placeholder="Senha" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    
                    <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700">
                        Entrar
                    </button>
                </form>

                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-2">Contas de teste:</h4>
                    <p class="text-sm text-gray-600">
                        <strong>Produtor:</strong> joao@produtor.com / password<br>
                        <strong>Consumidor:</strong> maria@consumidor.com / password
                    </p>
                </div>
            </div>

        <?php elseif ($action === 'dashboard' && isset($_SESSION['user']) && $_SESSION['user']['type'] === 'producer'): ?>
            <!-- Producer Dashboard -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Dashboard do Produtor</h2>
                <p class="text-gray-600">Gerencie seus produtos e vendas</p>
            </div>

            <!-- Add Product Form -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Adicionar Novo Produto</h3>
                <form method="POST" class="grid md:grid-cols-2 gap-6">
                    <input type="hidden" name="form_type" value="add_product">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nome do Produto</label>
                        <input type="text" name="name" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>
                        <select name="category_id" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="">Selecione uma categoria</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                        <textarea name="description" required 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preço (€)</label>
                        <input type="number" step="0.01" name="price" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quantidade</label>
                        <input type="number" name="stock_quantity" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Unidade</label>
                        <select name="unit" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="kg">Kg</option>
                            <option value="unidade">Unidade</option>
                            <option value="litro">Litro</option>
                            <option value="molho">Molho</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Origem</label>
                        <input type="text" name="origin" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Local da Feira</label>
                        <input type="text" name="fair_location" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                    
                    <div class="md:col-span-2">
                        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                            Adicionar Produto
                        </button>
                    </div>
                </form>
            </div>

            <!-- My Products -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Meus Produtos</h3>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php 
                    $myProducts = array_filter($products, function($p) {
                        return $p['user_id'] == $_SESSION['user']['id'];
                    });
                    foreach ($myProducts as $product): ?>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900"><?= htmlspecialchars($product['name']) ?></h4>
                            <p class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($product['description']) ?></p>
                            <div class="mt-3 flex justify-between items-center">
                                <span class="text-lg font-bold text-green-600">€<?= number_format($product['price'], 2) ?></span>
                                <span class="text-sm text-gray-500"><?= $product['stock_quantity'] ?> <?= $product['unit'] ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h3 class="text-lg font-semibold mb-2">🌱 AgroPerto Marketplace</h3>
            <p class="text-gray-400 mb-4">Conectando produtores e consumidores</p>
            <p class="text-gray-500 text-sm">Sistema funcionando com <?= count($products) ?> produtos e <?= count($categories) ?> categorias</p>
        </div>
    </footer>
</body>
</html>
