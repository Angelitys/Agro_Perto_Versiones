@extends('layouts.app-simple')

@section('title', 'Avaliações - ' . $product->name . ' - AgroPerto')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-4 mb-4">
            <a href="{{ route('products.show', $product) }}" class="text-gray-500 hover:text-green-600 transition-colors">
                <i class="fas fa-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Avaliações do Produto</h1>
                <p class="text-gray-600 mt-1">{{ $product->name }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Product Info -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                <!-- Product Image -->
                <div class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-leaf text-green-600 text-6xl"></i>
                </div>
                
                <!-- Product Details -->
                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $product->name }}</h3>
                <p class="text-gray-600 mb-4">{{ $product->description }}</p>
                
                <!-- Price -->
                <div class="flex items-center justify-between mb-4">
                    <span class="text-2xl font-bold text-green-600">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                    <span class="text-gray-500">/ {{ $product->unit }}</span>
                </div>
                
                <!-- Producer -->
                <div class="border-t pt-4">
                    <p class="text-sm text-gray-500 mb-1">Produtor</p>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-user-tie text-green-600"></i>
                        <a href="{{ route('reviews.producer', $product->user) }}" class="text-green-600 hover:text-green-700 font-medium">
                            {{ $product->user->name }}
                        </a>
                    </div>
                </div>
                
                <!-- Rating Summary -->
                <div class="border-t pt-4 mt-4">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-gray-900 mb-2">{{ number_format($averageRating, 1) }}</div>
                        <div class="flex justify-center mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star text-xl {{ $i <= $averageRating ? 'text-yellow-500' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>
                        <p class="text-gray-600">{{ $reviewCount }} {{ $reviewCount == 1 ? 'avaliação' : 'avaliações' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews List -->
        <div class="lg:col-span-2">
            @if($reviews->count() > 0)
                <div class="space-y-6">
                    @foreach($reviews as $review)
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <!-- Review Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-green-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $review->user->name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $review->reviewed_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                                
                                <!-- Verified Badge -->
                                @if($review->is_verified)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Compra Verificada
                                    </span>
                                @endif
                            </div>

                            <!-- Product Rating -->
                            <div class="mb-4">
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="text-sm font-medium text-gray-700">Produto:</span>
                                    <div class="flex">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star text-sm {{ $i <= $review->product_rating ? 'text-yellow-500' : 'text-gray-300' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-sm text-gray-600">({{ $review->product_rating }}/5)</span>
                                </div>
                                
                                @if($review->product_comment)
                                    <p class="text-gray-700 bg-gray-50 rounded-lg p-3">{{ $review->product_comment }}</p>
                                @endif
                            </div>

                            <!-- Producer Rating -->
                            <div class="mb-4">
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="text-sm font-medium text-gray-700">Produtor:</span>
                                    <div class="flex">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star text-sm {{ $i <= $review->producer_rating ? 'text-green-500' : 'text-gray-300' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-sm text-gray-600">({{ $review->producer_rating }}/5)</span>
                                </div>
                                
                                @if($review->producer_comment)
                                    <p class="text-gray-700 bg-green-50 rounded-lg p-3">{{ $review->producer_comment }}</p>
                                @endif
                            </div>

                            <!-- Photos -->
                            @if($review->photos && count($review->photos) > 0)
                                <div class="border-t pt-4">
                                    <p class="text-sm font-medium text-gray-700 mb-3">Fotos do produto:</p>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                        @foreach($review->photos as $photo)
                                            <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden cursor-pointer hover:opacity-75 transition-opacity"
                                                 onclick="openPhotoModal('{{ Storage::url($photo) }}')">
                                                <img src="{{ Storage::url($photo) }}" alt="Foto da avaliação" class="w-full h-full object-cover">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Order Info -->
                            <div class="border-t pt-4 mt-4">
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-shopping-cart mr-1"></i>
                                    Pedido #{{ $review->order_id }} • Retirado em {{ $review->order->pickup_date ? date('d/m/Y', strtotime($review->order->pickup_date)) : 'Data não informada' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $reviews->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-star text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma avaliação ainda</h3>
                    <p class="text-gray-500 mb-6">Este produto ainda não foi avaliado por nenhum consumidor.</p>
                    <a href="{{ route('products.show', $product) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Voltar ao Produto
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Photo Modal -->
<div id="photoModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div class="max-w-4xl max-h-full p-4">
        <img id="modalPhoto" src="" alt="Foto ampliada" class="max-w-full max-h-full object-contain rounded-lg">
        <button onclick="closePhotoModal()" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

@push('scripts')
<script>
function openPhotoModal(photoUrl) {
    document.getElementById('modalPhoto').src = photoUrl;
    document.getElementById('photoModal').classList.remove('hidden');
}

function closePhotoModal() {
    document.getElementById('photoModal').classList.add('hidden');
}

// Close modal on click outside
document.getElementById('photoModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePhotoModal();
    }
});

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePhotoModal();
    }
});
</script>
@endpush
@endsection
