<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliar Produtos - AgroPerto</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .star-rating {
            display: flex;
            gap: 2px;
        }
        .star {
            font-size: 1.5rem;
            color: #d1d5db;
            cursor: pointer;
            transition: color 0.2s;
        }
        .star.active {
            color: #fbbf24;
        }
        .star:hover {
            color: #fbbf24;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('welcome') }}" class="flex items-center">
                        <i class="fas fa-leaf text-green-600 text-2xl mr-2"></i>
                        <span class="text-xl font-bold text-gray-900">AgroPerto</span>
                        <span class="text-sm text-gray-500 ml-2">Agricultura Familiar</span>
                    </a>
                </div>
                
                <nav class="flex items-center space-x-4">
                    <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-shopping-basket mr-1"></i> Produtos
                    </a>
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-sign-out-alt mr-1"></i> Sair
                        </button>
                    </form>
                </nav>
            </div>
        </div>
    </header>

    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('welcome') }}" class="text-gray-700 hover:text-gray-900">
                        <i class="fas fa-home mr-1"></i> Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('orders.show', $order) }}" class="text-gray-700 hover:text-gray-900">Pedido #{{ $order->id }}</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-gray-500">Avaliar Produtos</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header da página -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-star text-yellow-500 mr-3"></i>
                Avaliar Produtos
            </h1>
            <p class="text-gray-600 mt-2">
                Compartilhe sua experiência com os produtos e produtores. Sua avaliação ajuda outros consumidores!
            </p>
        </div>

        <!-- Informações do Pedido -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">
                <i class="fas fa-receipt text-green-600 mr-2"></i>
                Pedido #{{ $order->id }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                <div>
                    <strong>Data do Pedido:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
                </div>
                <div>
                    <strong>Retirada:</strong> {{ date('d/m/Y', strtotime($order->pickup_date)) }} às {{ $order->pickup_time }}
                </div>
                <div>
                    <strong>Total:</strong> <span class="text-green-600 font-semibold">R$ {{ number_format($order->total_amount, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Formulário de Avaliação -->
        <form method="POST" action="{{ route('reviews.store', $order) }}" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-8">
                @foreach($order->orderItems as $index => $item)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-start space-x-4 mb-6">
                            <!-- Imagem do Produto -->
                            <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-full h-full object-cover rounded-lg">
                                @else
                                    <i class="fas fa-seedling text-gray-400 text-2xl"></i>
                                @endif
                            </div>
                            
                            <!-- Informações do Produto -->
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $item->product->name }}</h3>
                                <p class="text-gray-600">{{ $item->product->category->name ?? 'Categoria' }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $item->quantity }} {{ $item->product->unit }} × R$ {{ number_format($item->price, 2, ',', '.') }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    <i class="fas fa-user mr-1"></i>
                                    Produtor: {{ $item->product->user->name }}
                                </p>
                            </div>
                        </div>

                        <input type="hidden" name="reviews[{{ $index }}][product_id]" value="{{ $item->product->id }}">

                        <!-- Avaliação do Produto -->
                        <div class="mb-6">
                            <h4 class="text-md font-semibold text-gray-900 mb-3">
                                <i class="fas fa-apple-alt text-green-600 mr-2"></i>
                                Como você avalia este produto?
                            </h4>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nota do Produto *</label>
                                <div class="star-rating" data-rating="product_{{ $index }}">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="star" data-value="{{ $i }}">★</span>
                                    @endfor
                                </div>
                                <input type="hidden" name="reviews[{{ $index }}][product_rating]" id="product_rating_{{ $index }}" required>
                            </div>
                            
                            <div>
                                <label for="product_comment_{{ $index }}" class="block text-sm font-medium text-gray-700 mb-2">
                                    Comentário sobre o produto (opcional)
                                </label>
                                <textarea name="reviews[{{ $index }}][product_comment]" 
                                          id="product_comment_{{ $index }}"
                                          rows="3"
                                          placeholder="Como estava a qualidade? Sabor? Frescor? Aparência?"
                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                            </div>
                        </div>

                        <!-- Avaliação do Produtor -->
                        <div class="mb-6">
                            <h4 class="text-md font-semibold text-gray-900 mb-3">
                                <i class="fas fa-user-tie text-blue-600 mr-2"></i>
                                Como você avalia o produtor {{ $item->product->user->name }}?
                            </h4>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nota do Produtor *</label>
                                <div class="star-rating" data-rating="producer_{{ $index }}">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="star" data-value="{{ $i }}">★</span>
                                    @endfor
                                </div>
                                <input type="hidden" name="reviews[{{ $index }}][producer_rating]" id="producer_rating_{{ $index }}" required>
                            </div>
                            
                            <div>
                                <label for="producer_comment_{{ $index }}" class="block text-sm font-medium text-gray-700 mb-2">
                                    Comentário sobre o produtor (opcional)
                                </label>
                                <textarea name="reviews[{{ $index }}][producer_comment]" 
                                          id="producer_comment_{{ $index }}"
                                          rows="3"
                                          placeholder="Como foi o atendimento? Pontualidade? Organização? Comunicação?"
                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                            </div>
                        </div>

                        <!-- Upload de Fotos -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-camera text-purple-600 mr-1"></i>
                                Fotos do produto (opcional)
                            </label>
                            <input type="file" 
                                   name="photos[{{ $item->product->id }}][]" 
                                   multiple 
                                   accept="image/*"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <p class="text-xs text-gray-500 mt-1">
                                Máximo 3 fotos por produto. Formatos: JPG, PNG. Tamanho máximo: 2MB por foto.
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Informações Importantes -->
            <div class="bg-blue-50 rounded-lg p-6 my-8">
                <h3 class="font-semibold text-blue-900 mb-3">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Informações Importantes
                </h3>
                <ul class="text-sm text-blue-800 space-y-2">
                    <li>• Suas avaliações serão públicas e ajudarão outros consumidores</li>
                    <li>• Avalie com honestidade e construtividade</li>
                    <li>• Você pode avaliar tanto o produto quanto o produtor</li>
                    <li>• Fotos são opcionais, mas ajudam outros compradores</li>
                    <li>• Suas avaliações são verificadas (compra confirmada)</li>
                </ul>
            </div>

            <!-- Botões -->
            <div class="flex justify-between items-center">
                <a href="{{ route('orders.show', $order) }}" 
                   class="text-gray-600 hover:text-gray-900 px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar ao Pedido
                </a>
                
                <button type="submit" 
                        class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Enviar Avaliações
                </button>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex items-center justify-center mb-4">
                <i class="fas fa-leaf text-green-400 text-2xl mr-2"></i>
                <span class="text-xl font-bold">AgroPerto</span>
            </div>
            <p class="text-gray-400">Conectando produtores rurais diretamente aos consumidores</p>
            <p class="text-gray-500 text-sm mt-2">© 2024 AgroPerto. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script>
        // Sistema de avaliação por estrelas
        document.querySelectorAll('.star-rating').forEach(rating => {
            const stars = rating.querySelectorAll('.star');
            const ratingType = rating.dataset.rating;
            
            stars.forEach((star, index) => {
                star.addEventListener('click', () => {
                    const value = index + 1;
                    
                    // Atualizar input hidden
                    document.getElementById(`${ratingType.replace('_', '_rating_')}`).value = value;
                    
                    // Atualizar visual das estrelas
                    stars.forEach((s, i) => {
                        if (i < value) {
                            s.classList.add('active');
                        } else {
                            s.classList.remove('active');
                        }
                    });
                });
                
                star.addEventListener('mouseenter', () => {
                    const value = index + 1;
                    stars.forEach((s, i) => {
                        if (i < value) {
                            s.style.color = '#fbbf24';
                        } else {
                            s.style.color = '#d1d5db';
                        }
                    });
                });
            });
            
            rating.addEventListener('mouseleave', () => {
                const currentValue = document.getElementById(`${ratingType.replace('_', '_rating_')}`).value;
                stars.forEach((s, i) => {
                    if (i < currentValue) {
                        s.style.color = '#fbbf24';
                    } else {
                        s.style.color = '#d1d5db';
                    }
                });
            });
        });

        // Validação do formulário
        document.querySelector('form').addEventListener('submit', function(e) {
            let valid = true;
            const requiredRatings = document.querySelectorAll('input[type="hidden"][required]');
            
            requiredRatings.forEach(input => {
                if (!input.value) {
                    valid = false;
                    alert('Por favor, avalie todos os produtos e produtores antes de enviar.');
                }
            });
            
            if (!valid) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
