<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - AgroPerto</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
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
                <div class="flex items-center space-x-4">
                    <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                        Produtos
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                Sair
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            Entrar
                        </a>
                        <a href="{{ route('register') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Cadastrar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-green-600">
                        Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('products.index') }}" class="ml-1 text-gray-700 hover:text-green-600 md:ml-2">
                            Produtos
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-500 md:ml-2">{{ $product->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Product Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <!-- Product Image -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg flex items-center justify-center">
                    @if($product->first_image)
                        <img src="{{ asset('storage/' . $product->first_image) }}" alt="{{ $product->name }}" class="w-full h-96 object-cover rounded-lg">
                    @else
                        <div class="text-8xl">🌱</div>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <!-- Category Badge -->
                <div class="mb-4">
                    <span class="bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full">
                        {{ $product->category->name }}
                    </span>
                </div>

                <!-- Product Name -->
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>

                <!-- Price -->
                <div class="mb-6">
                    <span class="text-4xl font-bold text-green-600">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                    <span class="text-gray-500 text-lg">/ {{ $product->unit }}</span>
                </div>

                <!-- Stock -->
                <div class="mb-6">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="font-medium">{{ $product->stock_quantity }} {{ $product->unit }} disponível</span>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Descrição</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                </div>

                <!-- Producer Info -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Produtor</h3>
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="text-gray-700">{{ $product->user->name }}</span>
                    </div>
                    @if($product->origin)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-gray-700">{{ $product->origin }}</span>
                        </div>
                    @endif
                </div>

                <!-- Additional Info -->
                @if($product->harvest_date)
                    <div class="mb-6">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>Colhido em: {{ $product->harvest_date->format('d/m/Y') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Add to Cart -->
                @auth
                    @if(auth()->user()->type === 'consumer')
                        <form method="POST" action="{{ route('cart.add', $product) }}" class="mb-6">
                            @csrf
                            <div class="flex items-center space-x-4 mb-4">
                                <label for="quantity" class="text-sm font-medium text-gray-700">Quantidade:</label>
                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" 
                                       class="w-20 border border-gray-300 rounded px-3 py-2 text-center focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <span class="text-sm text-gray-500">{{ $product->unit }}</span>
                            </div>
                            <button type="submit" class="w-full bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                                Adicionar ao Carrinho
                            </button>
                        </form>
                    @endif
                @else
                    <div class="text-center py-4">
                        <p class="text-gray-600 mb-4">Faça login para comprar este produto</p>
                        <a href="{{ route('login') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                            Fazer Login
                        </a>
                    </div>
                @endauth
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Produtos Relacionados</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden border border-gray-100">
                            <!-- Product Image -->
                            <div class="h-48 bg-gray-200 flex items-center justify-center">
                                @if($relatedProduct->first_image)
                                    <img src="{{ asset('storage/' . $relatedProduct->first_image) }}" alt="{{ $relatedProduct->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="text-4xl">🌱</div>
                                @endif
                            </div>
                            
                            <!-- Product Info -->
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 mb-2">{{ $relatedProduct->name }}</h3>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-lg font-bold text-green-600">
                                            R$ {{ number_format($relatedProduct->price, 2, ',', '.') }}
                                        </span>
                                        <span class="text-xs text-gray-500">/ {{ $relatedProduct->unit }}</span>
                                    </div>
                                    <a href="{{ route('products.show', $relatedProduct) }}" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 transition-colors">
                                        Ver
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex items-center justify-center space-x-2 mb-4">
                    <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold">AgroPerto</h3>
                        <p class="text-xs text-gray-400">Agricultura Familiar</p>
                    </div>
                </div>
                <p class="text-gray-400 text-sm mb-4">
                    Conectando produtores e consumidores para uma alimentação mais saudável e sustentável.
                </p>
                <p class="text-gray-500 text-xs">&copy; 2024 AgroPerto. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>
