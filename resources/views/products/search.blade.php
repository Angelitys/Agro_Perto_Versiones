@extends('layouts.app')

@section('title', 'Buscar Produtos - AgroPerto')
@section('description', 'Encontre os melhores produtos da agricultura familiar')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            @if($query)
                Resultados para "{{ $query }}"
            @else
                Buscar Produtos
            @endif
        </h1>
        <p class="text-gray-600">
            @if($products->total() > 0)
                {{ $products->total() }} produto(s) encontrado(s)
            @else
                Nenhum produto encontrado
            @endif
        </p>
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <form method="GET" action="{{ route('search') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <label for="q" class="block text-sm font-medium text-gray-700 mb-1">Buscar produtos</label>
                <input 
                    type="text" 
                    id="q" 
                    name="q" 
                    value="{{ $query }}"
                    placeholder="Digite o nome do produto..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                >
            </div>

            <!-- Category -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                <select 
                    id="category" 
                    name="category"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                >
                    <option value="">Todas as categorias</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Search Button -->
            <div class="md:col-span-3 flex justify-end">
                <button 
                    type="submit"
                    class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors"
                >
                    Buscar
                </button>
            </div>
        </form>
    </div>

    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($products as $product)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                    <!-- Product Image -->
                    <div class="aspect-w-1 aspect-h-1 bg-gray-200">
                        @if($product->images && count($product->images) > 0)
                            <img 
                                src="{{ asset('storage/' . $product->images[0]) }}" 
                                alt="{{ $product->name }}"
                                class="w-full h-48 object-cover"
                            >
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="text-lg font-semibold text-gray-900 line-clamp-2">
                                <a href="{{ route('products.show', $product) }}" class="hover:text-green-600">
                                    {{ $product->name }}
                                </a>
                            </h3>
                        </div>

                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $product->description }}</p>

                        <div class="flex items-center justify-between mb-3">
                            <span class="text-2xl font-bold text-green-600">
                                R$ {{ number_format($product->price, 2, ',', '.') }}
                            </span>
                            <span class="text-sm text-gray-500">por {{ $product->unit }}</span>
                        </div>

                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <span>{{ $product->user->name }}</span>
                            <span>{{ $product->stock_quantity }} disponível</span>
                        </div>

                        <div class="mb-3">
                            <span class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">
                                {{ $product->category->name }}
                            </span>
                        </div>

                        @auth
                            <form action="{{ route('cart.add', $product) }}" method="POST">
                                @csrf
                                <div class="flex items-center space-x-2 mb-3">
                                    <input 
                                        type="number" 
                                        name="quantity" 
                                        value="1" 
                                        min="1" 
                                        max="{{ $product->stock_quantity }}"
                                        class="w-16 border border-gray-300 rounded px-2 py-1 text-center"
                                    >
                                    <button 
                                        type="submit"
                                        class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm font-medium"
                                    >
                                        Adicionar ao Carrinho
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="text-center">
                                <a href="{{ route('login') }}" class="text-green-600 hover:text-green-700 text-sm font-medium">
                                    Faça login para comprar
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $products->appends(request()->query())->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum produto encontrado</h3>
            <p class="mt-1 text-sm text-gray-500">
                @if($query)
                    Não encontramos produtos que correspondam à sua busca "{{ $query }}".
                @else
                    Tente usar palavras-chave diferentes ou navegue pelas categorias.
                @endif
            </p>
            <div class="mt-6">
                <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    Ver todos os produtos
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

