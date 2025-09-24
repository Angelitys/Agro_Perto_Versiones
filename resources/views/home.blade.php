@extends('layouts.marketplace')

@section('title', 'AgroPerto - Produtos Frescos da Agricultura Familiar')
@section('description', 'Descubra produtos frescos e orgânicos direto dos produtores rurais. Apoie a agricultura familiar brasileira!')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-green-600 to-green-700 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-4xl lg:text-6xl font-bold mb-6">
                    Produtos Frescos
                    <span class="text-green-200">Direto do Campo</span>
                </h1>
                <p class="text-xl mb-8 text-green-100">
                    Conectamos você diretamente aos produtores rurais. Produtos frescos, orgânicos e sustentáveis entregues na sua porta.
                </p>
                
                <!-- Search Bar -->
                <div class="mb-8">
                    <form action="{{ route('search') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
                        <div class="flex-1">
                            <input 
                                type="text" 
                                name="q" 
                                placeholder="Busque por produtos... (ex: tomate, alface, mel)" 
                                value="{{ request('q') }}"
                                class="w-full px-4 py-3 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-300"
                            >
                        </div>
                        <button 
                            type="submit"
                            class="bg-green-800 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-900 transition-colors flex items-center justify-center"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Buscar
                        </button>
                    </form>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('products.index') }}" class="bg-white text-green-600 px-8 py-3 rounded-lg font-semibold hover:bg-green-50 transition-colors text-center">
                        Ver Produtos
                    </a>
                    <a href="{{ route('register') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-green-600 transition-colors text-center">
                        Cadastre-se
                    </a>
                </div>
            </div>
            <div class="relative">
                <div class="bg-white rounded-2xl p-8 shadow-2xl">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-green-50 rounded-lg p-4 text-center">
                            <div class="text-2xl mb-2">🥕</div>
                            <div class="text-green-800 font-semibold">Verduras Frescas</div>
                        </div>
                        <div class="bg-orange-50 rounded-lg p-4 text-center">
                            <div class="text-2xl mb-2">🍎</div>
                            <div class="text-orange-800 font-semibold">Frutas Orgânicas</div>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-4 text-center">
                            <div class="text-2xl mb-2">🥛</div>
                            <div class="text-yellow-800 font-semibold">Laticínios</div>
                        </div>
                        <div class="bg-red-50 rounded-lg p-4 text-center">
                            <div class="text-2xl mb-2">🍯</div>
                            <div class="text-red-800 font-semibold">Mel Puro</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
@if($categories->count() > 0)
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Categorias de Produtos</h2>
            <p class="text-gray-600">Explore nossa variedade de produtos frescos da agricultura familiar</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            @foreach($categories as $category)
            <a href="{{ route('products.by-category', $category) }}" class="group">
                <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow border border-gray-100 text-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:bg-green-200 transition-colors">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 group-hover:text-green-600 transition-colors">{{ $category->name }}</h3>
                    <p class="text-xs text-gray-500 mt-1">{{ $category->products_count }} produtos</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Featured Products -->
@if($featuredProducts->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Produtos em Destaque</h2>
            <p class="text-gray-600">Os produtos mais frescos e populares da nossa plataforma</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                <div class="aspect-w-1 aspect-h-1 bg-gray-200">
                    @if($product->first_image)
                        <img src="{{ asset('storage/' . $product->first_image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                            <svg class="w-12 h-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                </div>
                
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">{{ $product->category->name }}</span>
                        <span class="text-xs text-gray-500">{{ $product->stock_quantity }} {{ $product->unit }}</span>
                    </div>
                    
                    <h3 class="font-semibold text-gray-900 mb-1">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-600 mb-2 line-clamp-2">{{ Str::limit($product->description, 60) }}</p>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-lg font-bold text-green-600">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                            <span class="text-xs text-gray-500">/ {{ $product->unit }}</span>
                        </div>
                        <a href="{{ route('products.show', $product) }}" class="bg-green-600 text-white px-3 py-1 rounded-lg text-sm hover:bg-green-700 transition-colors">
                            Ver
                        </a>
                    </div>
                    
                    <div class="flex items-center mt-2 text-xs text-gray-500">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $product->origin ?? 'Agricultura Familiar' }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-8">
            <a href="{{ route('products.index') }}" class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                Ver Todos os Produtos
            </a>
        </div>
    </div>
</section>
@endif

<!-- Benefits Section -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Por que Escolher o AgroPerto?</h2>
            <p class="text-gray-600">Benefícios únicos para produtores e consumidores</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Produtos Frescos</h3>
                <p class="text-gray-600">Direto do produtor para sua mesa, garantindo máxima frescura e qualidade.</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Apoio Local</h3>
                <p class="text-gray-600">Fortalece a economia local e apoia famílias produtoras rurais.</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Preços Justos</h3>
                <p class="text-gray-600">Eliminamos intermediários, garantindo melhores preços para todos.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-green-600 text-white py-16">
    <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold mb-4">Pronto para Começar?</h2>
        <p class="text-xl text-green-100 mb-8">
            Junte-se à nossa comunidade e descubra o melhor da agricultura familiar brasileira.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @guest
                <a href="{{ route('register') }}" class="bg-white text-green-600 px-8 py-3 rounded-lg font-semibold hover:bg-green-50 transition-colors">
                    Criar Conta
                </a>
                <a href="{{ route('products.index') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-green-600 transition-colors">
                    Explorar Produtos
                </a>
            @else
                <a href="{{ route('products.create') }}" class="bg-white text-green-600 px-8 py-3 rounded-lg font-semibold hover:bg-green-50 transition-colors">
                    Vender Produtos
                </a>
                <a href="{{ route('products.index') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-green-600 transition-colors">
                    Comprar Produtos
                </a>
            @endguest
        </div>
    </div>
</section>
@endsection

