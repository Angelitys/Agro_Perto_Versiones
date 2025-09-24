@extends('layouts.marketplace')

@section('title', 'Dashboard - AgroPerto')
@section('description', 'Painel de controle do AgroPerto')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            Olá, {{ auth()->user()->name }}! 👋
        </h1>
        <p class="text-gray-600">
            @if(auth()->user()->type === 'producer')
                Bem-vindo ao seu painel de produtor
            @else
                Bem-vindo ao seu painel de consumidor
            @endif
        </p>
    </div>

    @if(auth()->user()->type === 'producer')
        <!-- Dashboard do Produtor -->
        
        <!-- Estatísticas Rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total de Produtos</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalProducts }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Produtos Ativos</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $activeProducts }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Estoque Total</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalStock }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Estoque Baixo</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $lowStockProducts }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações Rápidas -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Ações Rápidas</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('products.create') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Novo Produto</p>
                        <p class="text-sm text-gray-600">Cadastrar produto</p>
                    </div>
                </a>

                <a href="{{ route('products.index') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Meus Produtos</p>
                        <p class="text-sm text-gray-600">Gerenciar produtos</p>
                    </div>
                </a>

                <a href="{{ route('sales.index') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                    <svg class="w-8 h-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Minhas Vendas</p>
                        <p class="text-sm text-gray-600">Ver pedidos e vendas</p>
                    </div>
                </a>

                <a href="{{ route('profile.edit') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-8 h-8 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Perfil</p>
                        <p class="text-sm text-gray-600">Editar perfil</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Produtos Recentes -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Produtos Recentes</h2>
                    <a href="{{ route('products.index') }}" class="text-green-600 hover:text-green-700 text-sm font-medium">Ver todos</a>
                </div>
                
                @if($recentProducts->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentProducts as $product)
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                <p class="text-sm text-gray-600">{{ $product->category->name }} • R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                            </div>
                            <span class="text-sm text-gray-500">{{ $product->stock_quantity }} {{ $product->unit }}</span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <p class="text-gray-500">Nenhum produto cadastrado ainda</p>
                        <a href="{{ route('products.create') }}" class="text-green-600 hover:text-green-700 font-medium">Cadastrar primeiro produto</a>
                    </div>
                @endif
            </div>

            <!-- Produtos com Estoque Baixo -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Estoque Baixo</h2>
                    <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">{{ $lowStockProducts }} produtos</span>
                </div>
                
                @if($lowStockProductsList->count() > 0)
                    <div class="space-y-3">
                        @foreach($lowStockProductsList as $product)
                        <div class="flex items-center p-3 bg-red-50 rounded-lg">
                            <div class="w-12 h-12 bg-gradient-to-br from-red-100 to-red-200 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                <p class="text-sm text-gray-600">{{ $product->category->name }}</p>
                            </div>
                            <span class="text-sm font-medium text-red-600">{{ $product->stock_quantity }} {{ $product->unit }}</span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-green-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-500">Todos os produtos com estoque adequado!</p>
                    </div>
                @endif
            </div>
        </div>

    @else
        <!-- Dashboard do Consumidor -->
        
        <!-- Ações Rápidas -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Ações Rápidas</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('products.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Explorar</p>
                        <p class="text-sm text-gray-600">Ver produtos</p>
                    </div>
                </a>

                <a href="{{ route('cart.index') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.1 5H19M7 13v6a2 2 0 002 2h4a2 2 0 002-2v-6m-6 0V9a2 2 0 012-2h2a2 2 0 012 2v4.01"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Carrinho</p>
                        <p class="text-sm text-gray-600">Ver carrinho</p>
                    </div>
                </a>

                <a href="#" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                    <svg class="w-8 h-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Pedidos</p>
                        <p class="text-sm text-gray-600">Meus pedidos</p>
                    </div>
                </a>

                <a href="{{ route('profile.edit') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-8 h-8 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Perfil</p>
                        <p class="text-sm text-gray-600">Editar perfil</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Produtos Recentes -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Produtos Recentes</h2>
                    <a href="{{ route('products.index') }}" class="text-green-600 hover:text-green-700 text-sm font-medium">Ver todos</a>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($recentProducts as $product)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="w-full h-32 bg-gradient-to-br from-green-100 to-green-200 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h3 class="font-medium text-gray-900 mb-1">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-600 mb-2">{{ $product->category->name }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-green-600">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                            <a href="{{ route('products.show', $product) }}" class="text-green-600 hover:text-green-700 text-sm font-medium">Ver</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Categorias Populares -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Categorias Populares</h2>
                </div>
                
                <div class="space-y-3">
                    @foreach($popularCategories as $category)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $category->name }}</p>
                            <p class="text-sm text-gray-600">{{ $category->products_count }} produtos</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

