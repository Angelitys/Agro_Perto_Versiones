@extends('layouts.app')

@section('title', 'Avaliar Produto - AgroPerto')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-blue-50 px-6 py-4 border-b border-blue-200">
            <h1 class="text-2xl font-bold text-blue-800">Avaliar Produto</h1>
            <p class="text-blue-600 mt-1">Pedido #{{ $order->order_number }}</p>
        </div>

        <div class="p-6">
            <!-- Informações do Produto -->
            <div class="mb-8">
                <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg">
                    @if($product->images && count($product->images) > 0)
                        <img src="{{ asset('storage/' . $product->images[0]) }}" 
                             alt="{{ $product->name }}" 
                             class="w-20 h-20 object-cover rounded-lg">
                    @else
                        <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h3>
                        <p class="text-gray-600">Produtor: {{ $product->user->name }}</p>
                        <p class="text-sm text-gray-500">
                            Quantidade comprada: {{ $orderItem->quantity }} {{ $product->unit }}
                        </p>
                        <p class="text-sm text-gray-500">
                            Valor pago: R$ {{ number_format($orderItem->subtotal, 2, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Formulário de Avaliação -->
            <form action="{{ route('reviews.store', [$order, $product]) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                
                <!-- Avaliação do Produto -->
                <div class="border border-gray-200 rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Avaliação do Produto</h2>
                    
                    <div class="space-y-4">
                        <!-- Nota do Produto -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Qual nota você dá para este produto? *
                            </label>
                            <div class="flex space-x-2">
                                @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="product_rating" value="{{ $i }}" class="sr-only" required>
                                    <div class="product-star w-8 h-8 text-gray-300 hover:text-yellow-400 transition-colors">
                                        <svg class="w-full h-full fill-current" viewBox="0 0 24 24">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    </div>
                                </label>
                                @endfor
                            </div>
                            @error('product_rating')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Comentário sobre o Produto -->
                        <div>
                            <label for="product_comment" class="block text-sm font-medium text-gray-700 mb-2">
                                Comentário sobre o produto (opcional)
                            </label>
                            <textarea 
                                id="product_comment" 
                                name="product_comment" 
                                rows="3"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Como foi a qualidade, sabor, frescor do produto?"
                            >{{ old('product_comment') }}</textarea>
                            @error('product_comment')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Avaliação do Produtor -->
                <div class="border border-gray-200 rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Avaliação do Produtor</h2>
                    
                    <div class="space-y-4">
                        <!-- Nota do Produtor -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Qual nota você dá para o atendimento do produtor? *
                            </label>
                            <div class="flex space-x-2">
                                @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="producer_rating" value="{{ $i }}" class="sr-only" required>
                                    <div class="producer-star w-8 h-8 text-gray-300 hover:text-yellow-400 transition-colors">
                                        <svg class="w-full h-full fill-current" viewBox="0 0 24 24">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    </div>
                                </label>
                                @endfor
                            </div>
                            @error('producer_rating')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Comentário sobre o Produtor -->
                        <div>
                            <label for="producer_comment" class="block text-sm font-medium text-gray-700 mb-2">
                                Comentário sobre o produtor (opcional)
                            </label>
                            <textarea 
                                id="producer_comment" 
                                name="producer_comment" 
                                rows="3"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Como foi o atendimento, pontualidade, comunicação?"
                            >{{ old('producer_comment') }}</textarea>
                            @error('producer_comment')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Fotos -->
                <div class="border border-gray-200 rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Fotos (opcional)</h2>
                    
                    <div>
                        <label for="photos" class="block text-sm font-medium text-gray-700 mb-2">
                            Adicione fotos do produto recebido
                        </label>
                        <input 
                            type="file" 
                            id="photos" 
                            name="photos[]" 
                            multiple 
                            accept="image/*"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <p class="text-xs text-gray-500 mt-1">Máximo 5 fotos, 2MB cada</p>
                        @error('photos.*')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Configurações -->
                <div class="border border-gray-200 rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Configurações</h2>
                    
                    <div>
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                name="is_public" 
                                value="1" 
                                checked
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            >
                            <span class="ml-2 text-sm text-gray-700">
                                Tornar esta avaliação pública (outros usuários poderão ver)
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Botões -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('orders.show', $order) }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Enviar Avaliação
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Script para avaliação por estrelas
document.addEventListener('DOMContentLoaded', function() {
    // Estrelas do produto
    setupStarRating('.product-star', 'product_rating');
    
    // Estrelas do produtor
    setupStarRating('.producer-star', 'producer_rating');
    
    function setupStarRating(starSelector, inputName) {
        const stars = document.querySelectorAll(starSelector);
        const radioInputs = document.querySelectorAll(`input[name="${inputName}"]`);
        
        stars.forEach((star, index) => {
            star.addEventListener('click', function() {
                radioInputs[index].checked = true;
                updateStars(stars, index + 1);
            });
            
            star.addEventListener('mouseenter', function() {
                updateStars(stars, index + 1);
            });
        });
        
        // Reset on mouse leave
        const container = stars[0]?.closest('div');
        if (container) {
            container.addEventListener('mouseleave', function() {
                const checkedInput = document.querySelector(`input[name="${inputName}"]:checked`);
                const rating = checkedInput ? parseInt(checkedInput.value) : 0;
                updateStars(stars, rating);
            });
        }
    }
    
    function updateStars(stars, rating) {
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

