@extends('layouts.app')

@section('title', 'Confirmar Recebimento - AgroPerto')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-green-50 px-6 py-4 border-b border-green-200">
            <h1 class="text-2xl font-bold text-green-800">Confirmar Recebimento</h1>
            <p class="text-green-600 mt-1">Pedido #{{ $order->order_number }}</p>
        </div>

        <div class="p-6">
            <!-- Informações da Entrega -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informações da Entrega</h2>
                
                <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Data de Entrega:</span>
                        <span class="font-medium">{{ $order->delivered_at->format('d/m/Y H:i') }}</span>
                    </div>
                    
                    @if($order->delivery_notes)
                    <div>
                        <span class="text-gray-600">Observações do Produtor:</span>
                        <p class="mt-1 text-gray-900">{{ $order->delivery_notes }}</p>
                    </div>
                    @endif
                    
                    @if($order->delivery_photo)
                    <div>
                        <span class="text-gray-600">Foto da Entrega:</span>
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $order->delivery_photo) }}" 
                                 alt="Foto da entrega" 
                                 class="max-w-sm rounded-lg shadow-sm">
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Itens do Pedido -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Itens Entregues</h2>
                
                <div class="space-y-4">
                    @foreach($order->orderItems as $item)
                    <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                        @if($item->product->images && count($item->product->images) > 0)
                            <img src="{{ asset('storage/' . $item->product->images[0]) }}" 
                                 alt="{{ $item->product_name }}" 
                                 class="w-16 h-16 object-cover rounded-lg">
                        @else
                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900">{{ $item->product_name }}</h3>
                            <p class="text-sm text-gray-600">
                                Quantidade: {{ $item->quantity }} {{ $item->product->unit ?? 'unidade(s)' }}
                            </p>
                            <p class="text-sm text-gray-600">
                                Preço unitário: R$ {{ number_format($item->product_price, 2, ',', '.') }}
                            </p>
                        </div>
                        
                        <div class="text-right">
                            <p class="font-medium text-gray-900">
                                R$ {{ number_format($item->subtotal, 2, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Formulário de Confirmação -->
            <form action="{{ route('delivery.confirm', $order) }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Avaliação da Entrega -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Como você avalia esta entrega? *
                    </label>
                    <div class="flex space-x-2">
                        @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer">
                            <input type="radio" name="delivery_rating" value="{{ $i }}" class="sr-only" required>
                            <div class="star-rating w-8 h-8 text-gray-300 hover:text-yellow-400 transition-colors">
                                <svg class="w-full h-full fill-current" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </div>
                        </label>
                        @endfor
                    </div>
                    @error('delivery_rating')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Feedback -->
                <div>
                    <label for="customer_feedback" class="block text-sm font-medium text-gray-700 mb-2">
                        Comentários sobre a entrega (opcional)
                    </label>
                    <textarea 
                        id="customer_feedback" 
                        name="customer_feedback" 
                        rows="4"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Conte-nos como foi sua experiência com esta entrega..."
                    >{{ old('customer_feedback') }}</textarea>
                    @error('customer_feedback')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Botões -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('orders.show', $order) }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Voltar
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Confirmar Recebimento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Script para avaliação por estrelas
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star-rating');
    const radioInputs = document.querySelectorAll('input[name="delivery_rating"]');
    
    stars.forEach((star, index) => {
        star.addEventListener('click', function() {
            radioInputs[index].checked = true;
            updateStars(index + 1);
        });
        
        star.addEventListener('mouseenter', function() {
            updateStars(index + 1);
        });
    });
    
    document.querySelector('form').addEventListener('mouseleave', function() {
        const checkedInput = document.querySelector('input[name="delivery_rating"]:checked');
        const rating = checkedInput ? parseInt(checkedInput.value) : 0;
        updateStars(rating);
    });
    
    function updateStars(rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
    }
});
</script>
@endsection

