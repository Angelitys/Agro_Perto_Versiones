@extends('layouts.marketplace')

@section('title', 'Carrinho de Compras - AgroPerto')
@section('description', 'Revise seus produtos selecionados e finalize sua compra no AgroPerto')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Carrinho de Compras</h1>
        <p class="text-gray-600">Revise seus produtos antes de finalizar a compra</p>
    </div>

    @if($cart && $cart->items->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">
                            Produtos ({{ $cart->total_items }} {{ $cart->total_items == 1 ? 'item' : 'itens' }})
                        </h2>
                        
                        <div class="space-y-4">
                            @foreach($cart->items as $item)
                            <div class="flex items-center space-x-4 p-4 border border-gray-100 rounded-lg">
                                <!-- Product Image -->
                                <div class="flex-shrink-0">
                                    @if($item->product->first_image)
                                        <img src="{{ Storage::url($item->product->first_image) }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded-lg">
                                    @else
                                        <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-green-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Info -->
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">
                                        <a href="{{ route('products.show', $item->product) }}" class="hover:text-green-600">
                                            {{ $item->product->name }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-600">{{ $item->product->category->name }}</p>
                                    <p class="text-sm text-gray-500">Vendido por {{ $item->product->user->name }}</p>
                                </div>

                                <!-- Quantity Controls -->
                                <div class="flex items-center space-x-2">
                                    <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center space-x-2">
                                        @csrf
                                        @method('PATCH')
                                        <input 
                                            type="number" 
                                            name="quantity" 
                                            value="{{ $item->quantity }}" 
                                            min="1" 
                                            max="{{ $item->product->stock_quantity }}"
                                            class="w-16 border border-gray-300 rounded px-2 py-1 text-center focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                            onchange="this.form.submit()"
                                        >
                                        <span class="text-sm text-gray-500">{{ $item->product->unit }}</span>
                                    </form>
                                </div>

                                <!-- Price -->
                                <div class="text-right">
                                    <div class="font-semibold text-gray-900">
                                        R$ {{ number_format($item->subtotal, 2, ',', '.') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        R$ {{ number_format($item->price, 2, ',', '.') }} / {{ $item->product->unit }}
                                    </div>
                                </div>

                                <!-- Remove Button -->
                                <div>
                                    <form action="{{ route('cart.remove', $item) }}" method="POST" onsubmit="return confirm('Remover este item do carrinho?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Clear Cart -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Limpar todo o carrinho?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    Limpar Carrinho
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 sticky top-8">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Resumo do Pedido</h2>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal ({{ $cart->total_items }} itens)</span>
                                <span class="font-medium">R$ {{ number_format($cart->total, 2, ',', '.') }}</span>
                            </div>
                            
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Frete</span>
                                <span class="font-medium text-green-600">Grátis</span>
                            </div>
                            
                            <hr class="my-4">
                            
                            <div class="flex justify-between text-lg font-semibold">
                                <span>Total</span>
                                <span class="text-green-600">R$ {{ number_format($cart->total, 2, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('orders.create') }}" class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition-colors text-center block">
                                Finalizar Compra
                            </a>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('products.index') }}" class="w-full border border-gray-300 text-gray-700 py-3 px-4 rounded-lg font-semibold hover:bg-gray-50 transition-colors text-center block">
                                Continuar Comprando
                            </a>
                        </div>

                        <!-- Trust Badges -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="space-y-3 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Produtos frescos garantidos
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    Compra segura
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    Entrega rápida
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="text-center py-16">
            <div class="max-w-md mx-auto">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.1 5H19M7 13v6a2 2 0 002 2h4a2 2 0 002-2v-6m-6 0V9a2 2 0 012-2h2a2 2 0 012 2v4.01"></path>
                </svg>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Seu carrinho está vazio</h2>
                <p class="text-gray-600 mb-8">Adicione alguns produtos frescos da agricultura familiar ao seu carrinho para começar.</p>
                
                <div class="space-y-4">
                    <a href="{{ route('products.index') }}" class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors inline-block">
                        Explorar Produtos
                    </a>
                    <div>
                        <a href="{{ route('home') }}" class="text-green-600 hover:text-green-700 font-medium">
                            Voltar ao Início
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Auto-update cart count
    function updateCartCount() {
        fetch('{{ route("cart.count") }}')
            .then(response => response.json())
            .then(data => {
                const cartCount = document.getElementById('cart-count');
                if (cartCount) {
                    cartCount.textContent = data.count || 0;
                }
            })
            .catch(error => console.error('Erro ao atualizar carrinho:', error));
    }

    // Update on page load
    updateCartCount();
</script>
@endpush

