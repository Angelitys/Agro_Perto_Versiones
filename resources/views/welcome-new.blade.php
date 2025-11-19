<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroPerto - Produtos Frescos da Agricultura Familiar</title>
    <meta name="description" content="Descubra produtos frescos e orgânicos direto dos produtores rurais. Apoie a agricultura familiar brasileira!">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">AgroPerto</h1>
                            <p class="text-xs text-gray-600">Agricultura Familiar</p>
                        </div>
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="flex-1 max-w-lg mx-8">
                    <div class="relative">
                        <input type="text" placeholder="Buscar produtos..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="flex items-center space-x-4">
                    <!-- Cart -->
                    <button class="relative p-2 text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v5a2 2 0 11-4 0v-5m4 0V8a2 2 0 10-4 0v5.01"></path>
                        </svg>
                        <span class="absolute -top-1 -right-1 bg-green-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                    </button>
                    
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                        Entrar
                    </a>
                    <a href="{{ route('register') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Cadastrar
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-green-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div>
                    <h1 class="text-4xl lg:text-5xl font-bold mb-4">
                        Produtos Frescos<br>
                        Direto do Campo
                    </h1>
                    <p class="text-xl mb-8 text-green-100">
                        Conectamos você diretamente aos produtores rurais. Produtos frescos, orgânicos e sustentáveis entregues na sua porta.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('products.index') }}" class="bg-white text-green-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors text-center">
                            Ver Produtos
                        </a>
                        <a href="{{ route('register') }}" class="border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-green-600 transition-colors text-center">
                            Cadastre-se
                        </a>
                    </div>
                </div>
                
                <!-- Categories Grid -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-green-500 bg-opacity-50 p-6 rounded-lg text-center">
                        <div class="text-4xl mb-2">🥕</div>
                        <h3 class="font-semibold">Verduras Frescas</h3>
                    </div>
                    <div class="bg-green-500 bg-opacity-50 p-6 rounded-lg text-center">
                        <div class="text-4xl mb-2">🍎</div>
                        <h3 class="font-semibold">Frutas Orgânicas</h3>
                    </div>
                    <div class="bg-green-500 bg-opacity-50 p-6 rounded-lg text-center">
                        <div class="text-4xl mb-2">🥛</div>
                        <h3 class="font-semibold">Laticínios</h3>
                    </div>
                    <div class="bg-green-500 bg-opacity-50 p-6 rounded-lg text-center">
                        <div class="text-4xl mb-2">🍯</div>
                        <h3 class="font-semibold">Mel Puro</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Categorias de Produtos</h2>
                <p class="text-lg text-gray-600">Explore nossa variedade de produtos frescos da agricultura familiar</p>
            </div>
            
            @if($categories->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($categories as $category)
                        <div class="bg-gray-50 rounded-lg p-6 text-center hover:shadow-lg transition-shadow">
                            <div class="text-4xl mb-4">🌱</div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $category->name }}</h3>
                            <p class="text-gray-600 text-sm mb-4">{{ $category->description ?? 'Produtos frescos e selecionados' }}</p>
                            <a href="{{ route('products.by-category', $category) }}" class="text-green-600 hover:text-green-700 font-medium">
                                Ver produtos →
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-600">Nenhuma categoria cadastrada ainda.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Produtos em Destaque</h2>
                <p class="text-lg text-gray-600">Os produtos mais frescos e populares da nossa plataforma</p>
            </div>
            
            @if($featuredProducts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($featuredProducts as $product)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            <div class="h-48 bg-gray-200 flex items-center justify-center">
                                @if($product->image)
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="text-6xl">🌱</div>
                                @endif
                            </div>
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $product->name }}</h3>
                                <p class="text-gray-600 text-sm mb-3">{{ Str::limit($product->description, 100) }}</p>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-2xl font-bold text-green-600">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                                        <span class="text-gray-500 text-sm">/ {{ $product->unit }}</span>
                                    </div>
                                    <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                        Adicionar
                                    </button>
                                </div>
                                <div class="mt-3 text-xs text-gray-500">
                                    Por: {{ $product->user->name }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="text-center mt-8">
                    <a href="{{ route('products.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold">
                        Ver Todos os Produtos
                    </a>
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-600">Nenhum produto cadastrado ainda.</p>
                    <a href="{{ route('register') }}" class="mt-4 inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold">
                        Seja um Produtor
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold">AgroPerto</h3>
                            <p class="text-xs text-gray-400">Agricultura Familiar</p>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm">
                        Conectando produtores e consumidores para uma alimentação mais saudável e sustentável.
                    </p>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Links Rápidos</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('products.index') }}" class="hover:text-white">Produtos</a></li>
                        <li><a href="{{ route('categories.index') }}" class="hover:text-white">Categorias</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white">Cadastre-se</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white">Entrar</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Para Produtores</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('register') }}" class="hover:text-white">Venda seus produtos</a></li>
                        <li><a href="{{ route('dashboard') }}" class="hover:text-white">Dashboard</a></li>
                        <li><a href="{{ route('sales.index') }}" class="hover:text-white">Minhas vendas</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contato</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li>📧 contato@agroperto.com.br</li>
                        <li>📱 (11) 99999-9999</li>
                        <li>📍 São Paulo, SP</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400 text-sm">
                <p>&copy; 2024 AgroPerto. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>
