@extends('layouts.marketplace')

@section('title', 'Meus Pedidos - AgroPerto')
@section('description', 'Visualize o histórico dos seus pedidos no AgroPerto')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Meus Pedidos</h1>
        <p class="text-gray-600">Aqui você pode acompanhar o status e os detalhes dos seus pedidos.</p>
    </div>

    @if($orders->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="divide-y divide-gray-200">
                @foreach($orders as $order)
                    <div class="py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                        <div class="mb-4 sm:mb-0">
                            <h2 class="text-xl font-semibold text-gray-900 mb-1">
                                Pedido #{{ $order->id }}
                            </h2>
                            <p class="text-sm text-gray-600">Data: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            <p class="text-sm text-gray-600">Total: <span class="font-medium">R$ {{ number_format($order->total, 2, ',', '.') }}</span></p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="px-3 py-1 rounded-full text-sm font-medium 
                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'completed') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                            <a href="{{ route('orders.show', $order) }}" class="text-green-600 hover:text-green-700 font-medium">
                                Ver Detalhes
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="text-center py-16">
            <div class="max-w-md mx-auto">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M12 15h.01"></path>
                </svg>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Você ainda não fez nenhum pedido</h2>
                <p class="text-gray-600 mb-8">Comece a explorar nossos produtos frescos e faça seu primeiro pedido hoje!</p>
                
                <a href="{{ route('products.index') }}" class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors inline-block">
                    Explorar Produtos
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

