@extends('layouts.marketplace')

@section('title', 'Detalhes do Pedido - AgroPerto')
@section('description', 'Visualize os detalhes do seu pedido no AgroPerto')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Detalhes do Pedido #{{ $order->id }}</h1>
        <p class="text-gray-600">Status: <span class="font-semibold text-green-600">{{ ucfirst($order->status) }}</span></p>
        <p class="text-gray-600">Data do Pedido: {{ $order->created_at->format('d/m/Y H:i') }}</p>
        <p class="text-gray-600">Data de Retirada: {{ $order->pickup_date ? \Carbon\Carbon::parse($order->pickup_date)->format('d/m/Y') : 'Não definida' }}</p>
        <p class="text-gray-600">Status da Entrega: <span class="font-semibold">{{ ucfirst($order->delivery_status) }}</span></p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Items -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Produtos do Pedido</h2>
                <div class="space-y-4">
                    @foreach($order->orderItems as $item)
                    <div class="flex items-center space-x-4 p-4 border border-gray-100 rounded-lg">
                        <!-- Product Image -->
                        <div class="flex-shrink-0">
                            @if($item->product->first_image)
                                <img src="{{ asset("storage/" . $item->product->first_image) }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded-lg">
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

                        <!-- Quantity and Price -->
                        <div class="text-right">
                            <div class="font-semibold text-gray-900">
                                R$ {{ number_format($item->subtotal, 2, ',', '.') }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $item->quantity }} x R$ {{ number_format($item->product_price, 2, ',', '.') }} / {{ $item->product->unit }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 sticky top-8 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Resumo do Pedido</h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium">R$ {{ number_format($order->orderItems->sum('subtotal'), 2, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Frete</span>
                        <span class="font-medium text-green-600">Grátis</span>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="flex justify-between text-lg font-semibold">
                        <span>Total</span>
                        <span class="text-green-600">R$ {{ number_format($order->orderItems->sum('subtotal'), 2, ',', '.') }}</span>
                    </div>
                </div>

                <div class="mt-6">
                    @if($order->delivery_status === 'pending')
                        <form action="{{ route('orders.confirm_delivery', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition-colors text-center">
                                Confirmar Entrega
                            </button>
                        </form>
                    @endif
                    @if($order->delivery_status === 'delivered')
                        <a href="{{ route('feedbacks.create', $order) }}" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors text-center block mt-4">
                            Deixar Feedback
                        </a>
                    @endif
                    <a href="{{ route('orders.index') }}" class="w-full border border-gray-300 text-gray-700 py-3 px-4 rounded-lg font-semibold hover:bg-gray-50 transition-colors text-center block mt-4">
                        Voltar para Meus Pedidos
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

