@extends('layouts.app-simple')

@section('title', 'Avaliar Pedido - AgroPerto')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-4 mb-4">
            <a href="{{ route('orders.show', $order) }}" class="text-gray-500 hover:text-green-600 transition-colors">
                <i class="fas fa-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Avaliar Pedido</h1>
                <p class="text-gray-600 mt-1">Compartilhe sua experiência com outros consumidores</p>
            </div>
        </div>
        
        <!-- Order Info -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Pedido #{{ $order->id }}</h3>
                    <p class="text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-green-600">R$ {{ number_format($order->total_amount, 2, ',', '.') }}</p>
                    <p class="text-sm text-gray-500">{{ $order->orderItems->count() }} {{ $order->orderItems->count() == 1 ? 'item' : 'itens' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Form -->
    <form method="POST" action="{{ route('reviews.store', $order) }}" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        @foreach($order->orderItems->groupBy('product.user_id') as $producerId => $items)
            @php $producer = $items->first()->product->user; @endphp
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Producer Header -->
                <div class="bg-green-50 px-6 py-4 border-b">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-tie text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $producer->name }}</h3>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $producer->address ?? 'Endereço não informado' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Products -->
                <div class="p-6 space-y-6">
                    @foreach($items as $item)
                        <div class="border border-gray-200 rounded-lg p-6">
                            <!-- Product Info -->
                            <div class="flex items-start space-x-4 mb-6">
                                <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-leaf text-green-600 text-2xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $item->product->name }}</h4>
                                    <p class="text-gray-600">{{ $item->quantity }} {{ $item->product->unit }} × R$ {{ number_format($item->price, 2, ',', '.') }}</p>
                                    <p class="text-sm text-gray-500 mt-1">{{ $item->product->description }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-semibold text-gray-900">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</p>
                                </div>
                            </div>

                            <input type="hidden" name="reviews[{{ $loop->parent->index }}_{{ $loop->index }}][product_id]" value="{{ $item->product->id }}">

                            <!-- Product Rating -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    <i class="fas fa-star text-yellow-500 mr-2"></i>
                                    Avaliação do Produto
                                </label>
                                <div class="flex items-center space-x-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="reviews[{{ $loop->parent->index }}_{{ $loop->index }}][product_rating]" value="{{ $i }}" class="sr-only product-rating" required>
                                            <i class="fas fa-star text-2xl text-gray-300 hover:text-yellow-500 transition-colors star-icon" data-rating="{{ $i }}"></i>
                                        </label>
                                    @endfor
                                </div>
                            </div>

                            <!-- Product Comment -->
                            <div class="mb-6">
                                <label for="product_comment_{{ $loop->parent->index }}_{{ $loop->index }}" class="block text-sm font-medium text-gray-700 mb-2">
                                    Comentário sobre o Produto (opcional)
                                </label>
                                <textarea id="product_comment_{{ $loop->parent->index }}_{{ $loop->index }}" 
                                          name="reviews[{{ $loop->parent->index }}_{{ $loop->index }}][product_comment]" 
                                          rows="3" 
                                          placeholder="Como foi a qualidade do produto? Estava fresco? Atendeu suas expectativas?"
                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                            </div>

                            <!-- Producer Rating (only for first product of each producer) -->
                            @if($loop->first)
                                <div class="border-t pt-6 mt-6">
                                    <div class="mb-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-3">
                                            <i class="fas fa-user-tie text-green-600 mr-2"></i>
                                            Avaliação do Produtor
                                        </label>
                                        <div class="flex items-center space-x-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <label class="cursor-pointer">
                                                    <input type="radio" name="reviews[{{ $loop->parent->index }}_{{ $loop->index }}][producer_rating]" value="{{ $i }}" class="sr-only producer-rating" required>
                                                    <i class="fas fa-star text-2xl text-gray-300 hover:text-green-500 transition-colors star-icon" data-rating="{{ $i }}"></i>
                                                </label>
                                            @endfor
                                        </div>
                                    </div>

                                    <!-- Producer Comment -->
                                    <div class="mb-6">
                                        <label for="producer_comment_{{ $loop->parent->index }}_{{ $loop->index }}" class="block text-sm font-medium text-gray-700 mb-2">
                                            Comentário sobre o Produtor (opcional)
                                        </label>
                                        <textarea id="producer_comment_{{ $loop->parent->index }}_{{ $loop->index }}" 
                                                  name="reviews[{{ $loop->parent->index }}_{{ $loop->index }}][producer_comment]" 
                                                  rows="3" 
                                                  placeholder="Como foi o atendimento? O produtor foi pontual? Recomendaria para outros consumidores?"
                                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                                    </div>
                                </div>
                            @else
                                <!-- Hidden fields for producer rating (copy from first product) -->
                                <input type="hidden" name="reviews[{{ $loop->parent->index }}_{{ $loop->index }}][producer_rating]" value="" class="producer-rating-hidden">
                                <input type="hidden" name="reviews[{{ $loop->parent->index }}_{{ $loop->index }}][producer_comment]" value="" class="producer-comment-hidden">
                            @endif

                            <!-- Photos -->
                            <div class="border-t pt-6 mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    <i class="fas fa-camera text-blue-600 mr-2"></i>
                                    Fotos do Produto (opcional)
                                </label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="photos_{{ $item->product->id }}" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
                                            <p class="mb-2 text-sm text-gray-500">
                                                <span class="font-semibold">Clique para enviar</span> ou arraste as fotos
                                            </p>
                                            <p class="text-xs text-gray-500">PNG, JPG ou JPEG (máx. 2MB cada)</p>
                                        </div>
                                        <input id="photos_{{ $item->product->id }}" name="photos[{{ $item->product->id }}][]" type="file" class="hidden" multiple accept="image/*">
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <!-- Submit Button -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Finalizar Avaliação</h3>
                    <p class="text-gray-600">Suas avaliações serão públicas e ajudarão outros consumidores</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('orders.show', $order) }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Enviar Avaliações
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle star ratings
    const starContainers = document.querySelectorAll('.star-icon');
    
    starContainers.forEach(star => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.dataset.rating);
            const radioInput = this.parentElement.querySelector('input[type="radio"]');
            const container = this.closest('.flex');
            
            // Update radio input
            radioInput.checked = true;
            
            // Update visual stars
            const stars = container.querySelectorAll('.star-icon');
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.classList.remove('text-gray-300');
                    if (radioInput.classList.contains('product-rating')) {
                        s.classList.add('text-yellow-500');
                    } else {
                        s.classList.add('text-green-500');
                    }
                } else {
                    s.classList.add('text-gray-300');
                    s.classList.remove('text-yellow-500', 'text-green-500');
                }
            });
        });
        
        // Hover effect
        star.addEventListener('mouseenter', function() {
            const rating = parseInt(this.dataset.rating);
            const container = this.closest('.flex');
            const stars = container.querySelectorAll('.star-icon');
            
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('text-yellow-400');
                }
            });
        });
        
        star.addEventListener('mouseleave', function() {
            const container = this.closest('.flex');
            const stars = container.querySelectorAll('.star-icon');
            stars.forEach(s => {
                s.classList.remove('text-yellow-400');
            });
        });
    });
    
    // Copy producer ratings to hidden fields
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('producer-rating')) {
            const producerSection = e.target.closest('.bg-white');
            const hiddenInputs = producerSection.querySelectorAll('.producer-rating-hidden');
            hiddenInputs.forEach(input => {
                input.value = e.target.value;
            });
        }
    });
    
    // Copy producer comments to hidden fields
    document.addEventListener('input', function(e) {
        if (e.target.name && e.target.name.includes('producer_comment')) {
            const producerSection = e.target.closest('.bg-white');
            const hiddenInputs = producerSection.querySelectorAll('.producer-comment-hidden');
            hiddenInputs.forEach(input => {
                input.value = e.target.value;
            });
        }
    });
    
    // File upload preview
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const files = Array.from(this.files);
            const label = this.closest('label');
            
            if (files.length > 0) {
                const fileNames = files.map(f => f.name).join(', ');
                label.querySelector('p').innerHTML = `<span class="font-semibold text-green-600">${files.length} arquivo(s) selecionado(s)</span>`;
            }
        });
    });
});
</script>
@endpush
@endsection
