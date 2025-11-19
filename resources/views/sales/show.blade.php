@extends('layouts.marketplace')

@section('title', 'Detalhes da Venda #' . $order->id . ' - AgroPerto')
@section('description', 'Detalhes do pedido e gerenciamento de status')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    Pedido #{{ $order->id }}
                </h1>
                <p class="text-gray-600">
                    Realizado em {{ $order->created_at->format('d/m/Y \à\s H:i') }}
                </p>
            </div>
            <a href="{{ route('sales.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar às Vendas
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informações do Pedido -->
        <div class="lg:col-span-2">
            <!-- Status do Pedido -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Status do Pedido</h2>
                
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        'awaiting_confirmation' => 'bg-orange-100 text-orange-800 border-orange-200',
                        'confirmed' => 'bg-green-100 text-green-800 border-green-200',
                        'processing' => 'bg-blue-100 text-blue-800 border-blue-200',
                        'shipped' => 'bg-purple-100 text-purple-800 border-purple-200',
                        'delivered' => 'bg-green-100 text-green-800 border-green-200',
                        'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                        'rejected' => 'bg-red-100 text-red-800 border-red-200',
                    ];
                    $statusLabels = [
                        'pending' => 'Pendente',
                        'awaiting_confirmation' => 'Aguardando Confirmação',
                        'confirmed' => 'Confirmado',
                        'processing' => 'Processando',
                        'shipped' => 'Enviado',
                        'delivered' => 'Entregue',
                        'cancelled' => 'Cancelado',
                        'rejected' => 'Rejeitado',
                    ];
                @endphp
                
                <div class="flex items-center justify-between mb-4">
                    <span class="inline-flex px-3 py-2 text-sm font-semibold rounded-lg border {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                        {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                    </span>
                    
                    @if(session('success'))
                        <div class="text-green-600 text-sm font-medium">
                            {{ session('success') }}
                        </div>
                    @endif
                </div>
                
                <!-- Botões de Confirmação/Rejeição -->
                @if($order->status === 'awaiting_confirmation')
                    <div class="bg-orange-50 border-l-4 border-orange-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-orange-400 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-orange-700">
                                    <strong>Ação Necessária:</strong> Este pedido está aguardando sua confirmação. Verifique se você pode atender o cliente no horário solicitado.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3">
                        <!-- Botão Confirmar -->
                        <form action="{{ route('orders.confirm', $order) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                <i class="fas fa-check-circle mr-2"></i>
                                Confirmar Pedido
                            </button>
                        </form>
                        
                        <!-- Botão Rejeitar -->
                        <button onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="flex-1 px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold">
                            <i class="fas fa-times-circle mr-2"></i>
                            Rejeitar Pedido
                        </button>
                    </div>
                    
                    <!-- Modal de Rejeição -->
                    <div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                            <div class="mt-3">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Rejeitar Pedido</h3>
                                <form action="{{ route('orders.reject', $order) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                            Motivo da rejeição *
                                        </label>
                                        <textarea id="rejection_reason" name="rejection_reason" rows="4" required
                                                  placeholder="Ex: Não tenho disponibilidade neste horário. Poderia ser às 10h?"
                                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-transparent"></textarea>
                                        <p class="text-xs text-gray-500 mt-1">O cliente receberá esta mensagem por e-mail.</p>
                                    </div>
                                    <div class="flex gap-3">
                                        <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" 
                                                class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                                            Cancelar
                                        </button>
                                        <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                            Confirmar Rejeição
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Formulário para Atualizar Status -->
                    <form action="{{ route('sales.updateStatus', $order) }}" method="POST" class="flex items-center space-x-4">
                        @csrf
                        @method('PATCH')
                        
                        <select name="status" class="flex-1 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pendente</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processando</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Enviado</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Entregue</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                        
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            Atualizar Status
                        </button>
                    </form>
                @endif
            </div>

            <!-- Itens do Pedido -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Itens do Pedido</h2>
                
                <div class="space-y-4">
                    @php $total = 0; @endphp
                    @foreach($order->orderItems as $item)
                        @php 
                            $itemTotal = $item->quantity * $item->price;
                            $total += $itemTotal;
                        @endphp
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-green-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $item->product->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $item->product->category->name }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ $item->quantity }} {{ $item->product->unit }} × R$ {{ number_format($item->price, 2, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">R$ {{ number_format($itemTotal, 2, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Total -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold text-gray-900">Total dos seus produtos:</span>
                        <span class="text-xl font-bold text-green-600">R$ {{ number_format($total, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informações do Cliente -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informações do Cliente</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                        <p class="text-gray-900">{{ $order->user->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                        <p class="text-gray-900">{{ $order->user->email }}</p>
                    </div>
                    
                    @if($order->shipping_address)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Endereço de Entrega</label>
                        <div class="text-gray-900 text-sm">
                            <p>{{ $order->shipping_address['street'] ?? 'N/A' }}</p>
                            <p>{{ $order->shipping_address['city'] ?? 'N/A' }}, {{ $order->shipping_address['state'] ?? 'N/A' }}</p>
                            <p>CEP: {{ $order->shipping_address['zip_code'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                    @endif
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Data do Pedido</label>
                        <p class="text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                
                <!-- Ações -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="space-y-2">
                        <a href="mailto:{{ $order->user->email }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Entrar em Contato
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

