@extends('layouts.marketplace')

@section('title', $product->name . ' - AgroPerto')
@section('description', Str::limit($product->description, 160))

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
                    <span class="ml-1 text-gray-900">{{ $product->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Product Images -->
        <div>
            @if($product->images && count($product->images) > 0)
                <div x-data="{ activeImage: 0 }">
                    <!-- Main Image -->
                    <div class="aspect-w-1 aspect-h-1 bg-gray-200 rounded-xl overflow-hidden mb-4">
                        <template x-for="(image, index) in {{ json_encode($product->images) }}" :key="index">
                            <img 
                                x-show="activeImage === index"
                                :src="`/storage/${image}`" 
                                :alt="'{{ $product->name }} - Imagem ' + (index + 1)"
                                class="w-full h-96 object-cover"
                            >
                        </template>
                    </div>
                    
                    <!-- Thumbnail Images -->
                    @if(count($product->images) > 1)
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($product->images as $index => $image)
                        <button 
                            @click="activeImage = {{ $index }}"
                            :class="activeImage === {{ $index }} ? 'ring-2 ring-green-500' : ''"
                            class="aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden hover:opacity-75 transition-opacity"
                        >
                            <img src="{{ Storage::url($image) }}" alt="{{ $product->name }}" class="w-full h-20 object-cover">
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>
            @else
                <!-- Placeholder Image -->
                <div class="aspect-w-1 aspect-h-1 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center">
                    <svg class="w-24 h-24 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            @endif
        </div>

        <!-- Product Info -->
        <div>
            <!-- Category Badge -->
            <div class="mb-4">
                <a href="{{ route('products.by-category', $product->category) }}" class="inline-block bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full hover:bg-green-200 transition-colors">
                    {{ $product->category->name }}
                </a>
            </div>

            <!-- Product Name -->
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>

            <!-- Price -->
            <div class="mb-6">
                <span class="text-4xl font-bold text-green-600">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                <span class="text-gray-500 text-lg">/ {{ $product->unit }}</span>
            </div>

            <!-- Stock Status -->
            <div class="mb-6">
                @if($product->stock_quantity > 0)
                    <div class="flex items-center text-green-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">Em estoque ({{ $product->stock_quantity }} {{ $product->unit }} disponíveis)</span>
                    </div>
                @else
                    <div class="flex items-center text-red-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="font-medium">Fora de estoque</span>
                    </div>
                @endif
            </div>

            <!-- Add to Cart Form -->
            @auth
                @if($product->stock_quantity > 0)
                <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-8">
                    @csrf
                    <div class="flex items-center space-x-4 mb-4">
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantidade</label>
                            <input 
                                type="number" 
                                id="quantity" 
                                name="quantity" 
                                value="1" 
                                min="1" 
                                max="{{ $product->stock_quantity }}"
                                class="w-20 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            >
                        </div>
                        <div class="flex-1">
                            <button type="submit" class="w-full bg-green-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                                Adicionar ao Carrinho
                            </button>
                        </div>
                    </div>
                </form>
                @endif
            @else
                <div class="mb-8">
                    <p class="text-gray-600 mb-4">Faça login para adicionar produtos ao carrinho</p>
                    <div class="flex space-x-4">
                        <a href="{{ route('login') }}" class="bg-green-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                            Fazer Login
                        </a>
                        <a href="{{ route('register') }}" class="border border-green-600 text-green-600 py-3 px-6 rounded-lg font-semibold hover:bg-green-50 transition-colors">
                            Criar Conta
                        </a>
                    </div>
                </div>
            @endauth

            <!-- Product Description -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Descrição</h3>
                <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
            </div>

            <!-- Product Details -->
            <div class="border-t border-gray-200 pt-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Detalhes do Produto</h3>
                <dl class="grid grid-cols-1 gap-4">
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Produtor:</dt>
                        <dd class="font-medium text-gray-900">{{ $product->user->name }}</dd>
                    </div>
                    @if($product->origin)
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Origem:</dt>
                        <dd class="font-medium text-gray-900">{{ $product->origin }}</dd>
                    </div>
                    @endif
                    @if($product->harvest_date)
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Data da Colheita:</dt>
                        <dd class="font-medium text-gray-900">{{ $product->harvest_date->format('d/m/Y') }}</dd>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Unidade:</dt>
                        <dd class="font-medium text-gray-900">{{ $product->unit }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Edit Product (Owner Only) -->
            @auth
                @if(auth()->user()->id === $product->user_id)
                <div class="border-t border-gray-200 pt-8">
                    <div class="flex space-x-4">
                        <a href="{{ route('products.edit', $product) }}" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                            Editar Produto
                        </a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este produto?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-colors">
                                Excluir Produto
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            @endauth
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-16">
        <h2 class="text-2xl font-bold text-gray-900 mb-8">Produtos Relacionados</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $relatedProduct)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden border border-gray-100">
                <div class="aspect-w-1 aspect-h-1 bg-gray-200">
                    @if($relatedProduct->first_image)
                        <img src="{{ Storage::url($relatedProduct->first_image) }}" alt="{{ $relatedProduct->name }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                            <svg class="w-12 h-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                </div>
                
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 mb-1 line-clamp-1">{{ $relatedProduct->name }}</h3>
                    <p class="text-sm text-gray-600 mb-2 line-clamp-2">{{ Str::limit($relatedProduct->description, 60) }}</p>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-lg font-bold text-green-600">R$ {{ number_format($relatedProduct->price, 2, ',', '.') }}</span>
                            <span class="text-xs text-gray-500">/ {{ $relatedProduct->unit }}</span>
                        </div>
                        <a href="{{ route('products.show', $relatedProduct) }}" class="bg-green-600 text-white px-3 py-1 rounded-lg text-sm hover:bg-green-700 transition-colors">
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
@endsection

