<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Pedido - AgroPerto</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
                        <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-gray-900">Carrinho</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-gray-500">Finalizar Pedido</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Formulário de Checkout -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-clipboard-check text-green-600 mr-2"></i>
                    Finalizar Pedido
                </h2>

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form">
                    @csrf
                    
                    <!-- Informações de Entrega -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-truck text-green-600 mr-2"></i>
                            Informações de Retirada
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="pickup_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Data de Retirada *
                                </label>
                                <input type="date" id="pickup_date" name="pickup_date" required
                                       min="{{ date('Y-m-d') }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       value="{{ old('pickup_date') }}">
                                @error('pickup_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="pickup_time" class="block text-sm font-medium text-gray-700 mb-2">
                                    Horário de Retirada *
                                </label>
                                <select id="pickup_time" name="pickup_time" required
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <option value="">Selecione o horário</option>
                                    <option value="08:00" {{ old('pickup_time') == '08:00' ? 'selected' : '' }}>08:00 - Manhã</option>
                                    <option value="09:00" {{ old('pickup_time') == '09:00' ? 'selected' : '' }}>09:00 - Manhã</option>
                                    <option value="10:00" {{ old('pickup_time') == '10:00' ? 'selected' : '' }}>10:00 - Manhã</option>
                                    <option value="11:00" {{ old('pickup_time') == '11:00' ? 'selected' : '' }}>11:00 - Manhã</option>
                                    <option value="14:00" {{ old('pickup_time') == '14:00' ? 'selected' : '' }}>14:00 - Tarde</option>
                                    <option value="15:00" {{ old('pickup_time') == '15:00' ? 'selected' : '' }}>15:00 - Tarde</option>
                                    <option value="16:00" {{ old('pickup_time') == '16:00' ? 'selected' : '' }}>16:00 - Tarde</option>
                                    <option value="17:00" {{ old('pickup_time') == '17:00' ? 'selected' : '' }}>17:00 - Tarde</option>
                                    <option value="18:00" {{ old('pickup_time') == '18:00' ? 'selected' : '' }}>18:00 - Final da Tarde</option>
                                </select>
                                @error('pickup_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label for="pickup_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Observações para Retirada (opcional)
                            </label>
                            <textarea id="pickup_notes" name="pickup_notes" rows="3"
                                      placeholder="Ex: Prefiro retirar na entrada da feira, tenho carro grande, etc."
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                      >{{ old('pickup_notes') }}</textarea>
                            @error('pickup_notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Método de Pagamento -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-credit-card text-green-600 mr-2"></i>
                            Método de Pagamento
                        </h3>
                        
                        <div class="space-y-3">
                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="cash" class="text-green-600 focus:ring-green-500" {{ old('payment_method') == 'cash' ? 'checked' : '' }} required>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">
                                        <i class="fas fa-money-bill-wave text-green-600 mr-2"></i>
                                        Dinheiro na Retirada
                                    </div>
                                    <div class="text-sm text-gray-500">Pagamento em espécie no momento da retirada</div>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="pix" class="text-green-600 focus:ring-green-500" {{ old('payment_method') == 'pix' ? 'checked' : '' }} required>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">
                                        <i class="fas fa-qrcode text-green-600 mr-2"></i>
                                        PIX
                                    </div>
                                    <div class="text-sm text-gray-500">Pagamento via PIX (chave será enviada por WhatsApp)</div>
                                </div>
                            </label>
                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Botão de Finalizar -->
                    <button type="submit" class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                        <i class="fas fa-check-circle mr-2"></i>
                        Finalizar Pedido
                    </button>
                </form>
            </div>

            <!-- Resumo do Pedido -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-shopping-cart text-green-600 mr-2"></i>
                    Resumo do Pedido
                </h3>

                @if($cart && $cart->items->count() > 0)
                    <div class="space-y-4 mb-6">
                        @foreach($cart->items as $item)
                            <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ $item->product->name }}</h4>
                                    <p class="text-sm text-gray-500">
                                        {{ $item->quantity }} {{ $item->product->unit }} × R$ {{ number_format($item->price, 2, ',', '.') }}
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $item->product->user->name }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">
                                        R$ {{ number_format($item->subtotal, 2, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Total -->
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex justify-between items-center text-lg font-bold text-gray-900">
                            <span>Total:</span>
                            <span class="text-green-600">R$ {{ number_format($cart->total, 2, ',', '.') }}</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            {{ $cart->items->count() }} {{ $cart->items->count() == 1 ? 'item' : 'itens' }} no carrinho
                        </p>
                    </div>

                    <!-- Informações Importantes -->
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <h4 class="font-semibold text-blue-900 mb-2">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Informações Importantes
                        </h4>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• Confirme a data e horário de retirada</li>
                            <li>• Leve um documento com foto</li>
                            <li>• Produtos frescos têm validade limitada</li>
                            <li>• Em caso de dúvidas, entre em contato via WhatsApp</li>
                        </ul>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-shopping-cart text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">Seu carrinho está vazio</p>
                        <a href="{{ route('products.index') }}" class="inline-block mt-4 bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            Explorar Produtos
                        </a>
                    </div>
                @endif
            </div>
        </div>
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
        // Apenas desabilitar o botão após submit para evitar duplo clique
        document.addEventListener('DOMContentLoaded', function() {
            const checkoutForm = document.getElementById('checkout-form');
            
            if (checkoutForm) {
                checkoutForm.addEventListener('submit', function(e) {
                    // Verificar se o formulário é válido usando validação HTML5
                    if (!checkoutForm.checkValidity()) {
                        return; // Deixa o navegador mostrar as mensagens de validação
                    }
                    
                    // Desabilitar o botão de submit para evitar duplo clique
                    const submitButton = checkoutForm.querySelector('button[type="submit"]');
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processando...';
                    }
                });
            }
        });
    </script>
</body>
</html>