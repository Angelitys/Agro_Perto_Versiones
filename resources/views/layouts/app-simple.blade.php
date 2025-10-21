<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'AgroPerto - Agricultura Familiar')</title>
    <meta name="description" content="@yield('description', 'Marketplace de produtos da agricultura familiar. Compre direto do produtor!')">

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .fade-in { animation: fadeIn 0.5s ease-in; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .hover-scale { transition: transform 0.2s; }
        .hover-scale:hover { transform: scale(1.05); }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 hover-scale">
                        <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center shadow-lg">
                            <i class="fas fa-seedling text-white text-lg"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">AgroPerto</h1>
                            <p class="text-xs text-gray-500">Agricultura Familiar</p>
                        </div>
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="flex-1 max-w-lg mx-8">
                    <form action="{{ route('products.index') }}" method="GET" class="relative">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Buscar produtos..." 
                            class="w-full pl-4 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                        >
                        <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-green-600 transition-colors">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- Navigation -->
                <nav class="flex items-center space-x-6">
                    @auth
                        <!-- Cart -->
                        <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-green-600 transition-colors p-2 rounded-lg hover:bg-green-50">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            <span id="cart-count" class="absolute -top-1 -right-1 bg-green-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium">0</span>
                        </a>

                        <!-- Notifications -->
                        @if(auth()->user()->type === 'producer')
                        <a href="{{ route('notifications.index') }}" class="relative text-gray-600 hover:text-green-600 transition-colors p-2 rounded-lg hover:bg-green-50">
                            <i class="fas fa-bell text-xl"></i>
                            <span id="notification-count" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium">
                                0
                            </span>
                        </a>
                        @endif

                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 text-gray-600 hover:text-green-600 transition-colors p-2 rounded-lg hover:bg-green-50">
                                <div class="w-8 h-8 bg-gradient-to-r from-green-100 to-green-200 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-green-700">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <span class="hidden md:block font-medium">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-sm"></i>
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-2 z-50 border">
                                <div class="px-4 py-2 border-b">
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->type === 'producer' ? 'Produtor' : 'Consumidor' }}</p>
                                </div>
                                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-tachometer-alt w-4 mr-3"></i>Dashboard
                                </a>
                                @if(auth()->user()->type === 'producer')
                                <a href="{{ route('products.create') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-plus w-4 mr-3"></i>Novo Produto
                                </a>
                                @endif
                                <a href="{{ route('orders.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-shopping-bag w-4 mr-3"></i>Meus Pedidos
                                </a>
                                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-user w-4 mr-3"></i>Perfil
                                </a>
                                <hr class="my-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <i class="fas fa-sign-out-alt w-4 mr-3"></i>Sair
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-green-600 transition-colors font-medium">Entrar</a>
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-2 rounded-lg hover:from-green-600 hover:to-green-700 transition-all shadow-md hover:shadow-lg font-medium">
                            Cadastrar
                        </a>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mx-4 mt-4 fade-in">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mx-4 mt-4 fade-in">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mx-4 mt-4 fade-in">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Corrija os seguintes erros:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-seedling text-white"></i>
                        </div>
                        <span class="font-bold text-lg">AgroPerto</span>
                    </div>
                    <p class="text-gray-300 text-sm leading-relaxed">Conectando produtores e consumidores para uma alimentação mais saudável e sustentável.</p>
                </div>

                <div>
                    <h3 class="font-semibold mb-4 text-green-400">Links Rápidos</h3>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li><a href="{{ route('products.index') }}" class="hover:text-white transition-colors"><i class="fas fa-apple-alt w-4 mr-2"></i>Produtos</a></li>
                        <li><a href="{{ route('home') }}#categorias" class="hover:text-white transition-colors"><i class="fas fa-list w-4 mr-2"></i>Categorias</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition-colors"><i class="fas fa-user-plus w-4 mr-2"></i>Seja um Produtor</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold mb-4 text-green-400">Para Produtores</h3>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li><a href="{{ route('register') }}" class="hover:text-white transition-colors"><i class="fas fa-store w-4 mr-2"></i>Venda seus produtos</a></li>
                        <li><a href="#" class="hover:text-white transition-colors"><i class="fas fa-handshake w-4 mr-2"></i>Parcerias</a></li>
                        <li><a href="#" class="hover:text-white transition-colors"><i class="fas fa-chart-line w-4 mr-2"></i>Relatórios</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold mb-4 text-green-400">Contato</h3>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li><i class="fas fa-envelope w-4 mr-2"></i>contato@agroperto.com.br</li>
                        <li><i class="fas fa-phone w-4 mr-2"></i>(11) 9999-9999</li>
                        <li><i class="fas fa-map-marker-alt w-4 mr-2"></i>São Paulo, SP</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} AgroPerto. Todos os direitos reservados. Conectando produtores e consumidores para uma alimentação mais saudável e sustentável.</p>
            </div>
        </div>
    </footer>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('scripts')

    <!-- Cart Count Update Script -->
    @auth
    <script>
        // Atualizar contagem do carrinho
        function updateCartCount() {
            fetch('{{ route("cart.count") }}')
                .then(response => response.json())
                .then(data => {
                    const cartElement = document.getElementById('cart-count');
                    if (cartElement) {
                        cartElement.textContent = data.count || 0;
                    }
                })
                .catch(error => console.error('Erro ao atualizar carrinho:', error));
        }

        // Atualizar ao carregar a página
        document.addEventListener('DOMContentLoaded', updateCartCount);

        // Atualizar a cada 30 segundos
        setInterval(updateCartCount, 30000);
    </script>
    @endauth

    <!-- Smooth scrolling -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
</body>
</html>
