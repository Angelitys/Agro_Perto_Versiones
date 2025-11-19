<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificações - AgroPerto</title>
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
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-gray-500">Notificações</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header da página -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-bell text-green-600 mr-3"></i>
                    Notificações
                </h1>
                <p class="text-gray-600 mt-2">
                    @if($unreadCount > 0)
                        Você tem {{ $unreadCount }} {{ $unreadCount == 1 ? 'notificação não lida' : 'notificações não lidas' }}
                    @else
                        Todas as notificações foram lidas
                    @endif
                </p>
            </div>
            
            @if($unreadCount > 0)
                <button onclick="markAllAsRead()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-check-double mr-2"></i>
                    Marcar Todas como Lidas
                </button>
            @endif
        </div>

        <!-- Lista de Notificações -->
        <div class="space-y-4">
            @forelse($notifications as $notification)
                <div class="bg-white rounded-lg shadow-md p-6 {{ $notification->read_at ? 'opacity-75' : 'border-l-4 border-green-500' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <div class="flex items-center">
                                    @if($notification->type === 'new_order')
                                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-shopping-cart text-green-600"></i>
                                        </div>
                                    @elseif($notification->type === 'order_update')
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-info-circle text-blue-600"></i>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-bell text-gray-600"></i>
                                        </div>
                                    @endif
                                    
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $notification->title }}</h3>
                                        <p class="text-sm text-gray-500">{{ $notification->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                                
                                @if(!$notification->read_at)
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Nova
                                    </span>
                                @endif
                            </div>
                            
                            <p class="text-gray-700 mb-4">{{ $notification->message }}</p>
                            
                            @if($notification->data)
                                @php $data = json_decode($notification->data, true); @endphp
                                
                                @if($notification->type === 'new_order' && isset($data['products']))
                                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                        <h4 class="font-semibold text-gray-900 mb-3">
                                            <i class="fas fa-list mr-2"></i>
                                            Detalhes do Pedido
                                        </h4>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <p class="text-sm text-gray-600">
                                                    <strong>Cliente:</strong> {{ $data['customer_name'] ?? 'N/A' }}
                                                </p>
                                                <p class="text-sm text-gray-600">
                                                    <strong>Telefone:</strong> {{ $data['customer_phone'] ?? 'N/A' }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-600">
                                                    <strong>Retirada:</strong> {{ isset($data['pickup_date']) ? date('d/m/Y', strtotime($data['pickup_date'])) : 'N/A' }} às {{ $data['pickup_time'] ?? 'N/A' }}
                                                </p>
                                                <p class="text-sm text-gray-600">
                                                    <strong>Pagamento:</strong> {{ $data['payment_method'] === 'cash' ? 'Dinheiro na retirada' : 'PIX' }}
                                                </p>
                                            </div>
                                        </div>
                                        
                                        @if(isset($data['pickup_notes']) && $data['pickup_notes'])
                                            <div class="mb-4">
                                                <p class="text-sm text-gray-600">
                                                    <strong>Observações:</strong> {{ $data['pickup_notes'] }}
                                                </p>
                                            </div>
                                        @endif
                                        
                                        <div class="border-t pt-3">
                                            <h5 class="font-medium text-gray-900 mb-2">Produtos:</h5>
                                            <div class="space-y-2">
                                                @foreach($data['products'] as $product)
                                                    <div class="flex justify-between items-center text-sm">
                                                        <span>{{ $product['name'] }} ({{ $product['quantity'] }} {{ $product['unit'] }})</span>
                                                        <span class="font-medium">R$ {{ number_format($product['subtotal'], 2, ',', '.') }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="border-t mt-2 pt-2 flex justify-between items-center font-semibold">
                                                <span>Total:</span>
                                                <span class="text-green-600">R$ {{ number_format($data['total_value'], 2, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                        
                        <div class="ml-4 flex flex-col space-y-2">
                            @if(!$notification->read_at)
                                <button onclick="markAsRead({{ $notification->id }})" 
                                        class="text-green-600 hover:text-green-700 text-sm">
                                    <i class="fas fa-check mr-1"></i>
                                    Marcar como lida
                                </button>
                            @endif
                            
                            @if($notification->type === 'new_order' && isset($data['order_id']))
                                <a href="{{ route('orders.show', $data['order_id']) }}" 
                                   class="text-blue-600 hover:text-blue-700 text-sm">
                                    <i class="fas fa-eye mr-1"></i>
                                    Ver Pedido
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <i class="fas fa-bell-slash text-gray-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Nenhuma notificação</h3>
                    <p class="text-gray-600">Você não tem notificações no momento.</p>
                </div>
            @endforelse
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
        function markAsRead(notificationId) {
            fetch(`/notifications/${notificationId}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Erro:', error));
        }

        function markAllAsRead() {
            fetch('/notifications/mark-all-as-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Erro:', error));
        }
    </script>
</body>
</html>
