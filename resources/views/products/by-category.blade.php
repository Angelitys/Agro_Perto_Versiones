@extends('layouts.app')

@section('title', $category->name . ' - AgroPerto')
@section('description', 'Explore produtos de ' . $category->name . ' frescos da agricultura familiar')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-green-600">Início</a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('products.index') }}" class="ml-1 text-gray-500 hover:text-green-600">Produtos</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-gray-900">{{ $category->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $category->name }}</h1>
            <p class="text-gray-600">{{ $category->description }}</p>
        </div>
        
        @auth
        <a href="{{ route('products.create') }}" class="mt-4 md:mt-0 bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
            Vender Produto
        </a>
        @endauth
    </div>

    <!-- Category Filter -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('products.index') }}" 
               class="px-4 py-2 rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                Todas as categorias
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('products.by-category', $cat) }}" 
                   class="px-4 py-2 rounded-full transition-colors {{ $cat->id == $category->id ? 'bg-green-600 text-white' : 'border border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
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
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m14 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m14 0H6m0 0l3-3m-3 3l3 3m8-6l3 3m-3-3l3-3"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum produto encontrado</h3>
            <p class="mt-1 text-sm text-gray-500">Não há produtos disponíveis nesta categoria no momento.</p>
            <div class="mt-6">
                <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    Ver todos os produtos
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

