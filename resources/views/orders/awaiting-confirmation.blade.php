<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido em Análise - AgroPerto</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @keyframes pulse-slow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .pulse-slow {
            animation: pulse-slow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
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
                    <a href="{{ route('orders.index') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-box mr-1"></i> Meus Pedidos
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

    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <!-- Status Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header com ícone animado -->
            <div class="bg-gradient-to-r from-yellow-400 to-orange-500 px-6 py-8 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full mb-4 pulse-slow">
                    <i class="fas fa-clock text-4xl text-orange-500"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Pedido em Análise</h1>
                <p class="text-white text-opacity-90">Aguardando confirmação do produtor</p>
            </div>

            <!-- Conteúdo -->
            <div class="px-6 py-8">
                <!-- Informações do Pedido -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">
                            Pedido #{{ $order->order_number }}
                        </h2>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-hourglass-half mr-2"></i>
                            Em Análise
                        </span>
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    <strong>O que acontece agora?</strong><br>
                                    O produtor foi notificado sobre seu pedido e está verificando a disponibilidade para o horário de retirada solicitado. 
                                    Você receberá uma notificação assim que o pedido for confirmado ou se houver necessidade de ajustes.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detalhes da Retirada -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-calendar-check text-green-600 mr-2"></i>
                        Detalhes da Retirada Solicitada
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-sm text-gray-500 mb-1">Data</div>
                            <div class="font-semibold text-gray-900">
                                <i class="fas fa-calendar mr-2 text-green-600"></i>
                                {{ $order->pickup_date->format('d/m/Y') }} ({{ $order->pickup_date->locale('pt_BR')->isoFormat('dddd') }})
                            </div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-sm text-gray-500 mb-1">Horário</div>
                            <div class="font-semibold text-gray-900">
                                <i class="fas fa-clock mr-2 text-green-600"></i>
                                {{ $order->pickup_time }}
                            </div>
                        </div>
                    </div>
                    
                    @if($order->pickup_notes)
                    <div class="mt-4 bg-gray-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-500 mb-1">Observações</div>
                        <div class="text-gray-900">{{ $order->pickup_notes }}</div>
                    </div>
                    @endif
                </div>

                <!-- Produtos do Pedido -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-shopping-basket text-green-600 mr-2"></i>
                        Produtos
                    </h3>
                    <div class="space-y-3">
                        @foreach($order->orderItems as $item)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $item->product_name }}</h4>
                                <p class="text-sm text-gray-500">
                                    {{ $item->quantity }} {{ $item->product->unit ?? 'un' }} × R$ {{ number_format($item->product_price, 2, ',', '.') }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-user mr-1"></i>
                                    Produtor: {{ $item->product->user->name }}
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
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-900">Total</span>
                            <span class="text-2xl font-bold text-green-600">
                                R$ {{ number_format($order->total_amount, 2, ',', '.') }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">
                            Pagamento: {{ $order->payment_method == 'cash' ? 'Dinheiro na Retirada' : 'PIX' }}
                        </p>
                    </div>
                </div>

                <!-- Timeline de Status -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-stream text-green-600 mr-2"></i>
                        Acompanhamento
                    </h3>
                    <div class="relative">
                        <!-- Linha vertical -->
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                        
                        <!-- Pedido Realizado -->
                        <div class="relative flex items-start mb-6">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-green-500 text-white z-10">
                                <i class="fas fa-check text-sm"></i>
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-gray-900">Pedido Realizado</p>
                                <p class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        <!-- Aguardando Confirmação -->
                        <div class="relative flex items-start mb-6">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-yellow-500 text-white z-10 pulse-slow">
                                <i class="fas fa-hourglass-half text-sm"></i>
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-gray-900">Aguardando Confirmação do Produtor</p>
                                <p class="text-sm text-gray-500">Em andamento...</p>
                            </div>
                        </div>

                        <!-- Próximos passos (desabilitados) -->
                        <div class="relative flex items-start mb-6 opacity-50">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-300 text-white z-10">
                                <i class="fas fa-box text-sm"></i>
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-gray-900">Pedido Confirmado</p>
                                <p class="text-sm text-gray-500">Aguardando...</p>
                            </div>
                        </div>

                        <div class="relative flex items-start opacity-50">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-300 text-white z-10">
                                <i class="fas fa-check-circle text-sm"></i>
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-gray-900">Pronto para Retirada</p>
                                <p class="text-sm text-gray-500">Aguardando...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ações -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('orders.index') }}" class="flex-1 bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition-colors text-center">
                        <i class="fas fa-list mr-2"></i>
                        Ver Todos os Pedidos
                    </a>
                    <a href="{{ route('products.index') }}" class="flex-1 border border-gray-300 text-gray-700 py-3 px-4 rounded-lg font-semibold hover:bg-gray-50 transition-colors text-center">
                        <i class="fas fa-shopping-basket mr-2"></i>
                        Continuar Comprando
                    </a>
                </div>
            </div>
        </div>

        <!-- Dicas -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-gray-900 mb-4">
                <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                Dicas Importantes
            </h3>
            <ul class="space-y-2 text-sm text-gray-600">
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                    <span>Você receberá uma notificação por e-mail quando o produtor confirmar seu pedido</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                    <span>O tempo de confirmação geralmente é de algumas horas</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                    <span>Se o horário não estiver disponível, o produtor pode sugerir um novo horário</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                    <span>Você pode acompanhar o status do pedido a qualquer momento em "Meus Pedidos"</span>
                </li>
            </ul>
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
        // Auto-refresh a cada 30 segundos para verificar se houve confirmação
        setTimeout(function() {
            location.reload();
        }, 30000);
    </script>
</body>
</html>
