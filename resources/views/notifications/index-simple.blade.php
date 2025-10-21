@extends('layouts.app-simple')

@section('title', 'Notificações - AgroPerto')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-green-600 transition-colors">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Notificações</h1>
                    <p class="text-gray-600 mt-1">Acompanhe seus pedidos e atualizações</p>
                </div>
            </div>
            
            @if($unreadCount > 0)
            <button onclick="markAllAsRead()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                <i class="fas fa-check-double mr-2"></i>
                Marcar todas como lidas
            </button>
            @endif
        </div>
        
        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="fas fa-bell text-blue-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Total</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $notifications->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <i class="fas fa-exclamation-circle text-red-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Não Lidas</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $unreadCount }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i class="fas fa-shopping-cart text-green-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Novos Pedidos</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $notifications->where('type', 'new_order')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($notifications->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($notifications as $notification)
                    <div class="p-6 hover:bg-gray-50 transition-colors {{ $notification->read_at ? 'opacity-75' : 'bg-blue-50' }}" 
                         data-notification-id="{{ $notification->id }}">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4 flex-1">
                                <!-- Icon -->
                                <div class="flex-shrink-0">
                                    @if($notification->type === 'new_order')
                                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-shopping-cart text-green-600"></i>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-bell text-blue-600"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            {{ $notification->title }}
                                        </h3>
                                        @if(!$notification->read_at)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Nova
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <p class="text-gray-700 mb-3">{{ $notification->message }}</p>
                                    
                                    <!-- Notification Details -->
                                    @if($notification->data)
                                        @php $data = json_decode($notification->data, true); @endphp
                                        
                                        @if($notification->type === 'new_order' && isset($data['order_id']))
                                            <div class="bg-gray-50 rounded-lg p-4 mt-3">
                                                <h4 class="font-medium text-gray-900 mb-2">
                                                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                                    Detalhes do Pedido
                                                </h4>
                                                
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                                    <div>
                                                        <p><strong>Cliente:</strong> {{ $data['customer_name'] ?? 'N/A' }}</p>
                                                        @if(isset($data['customer_phone']))
                                                            <p><strong>Telefone:</strong> {{ $data['customer_phone'] }}</p>
                                                        @endif
                                                        <p><strong>Retirada:</strong> {{ isset($data['pickup_date']) ? date('d/m/Y', strtotime($data['pickup_date'])) : 'N/A' }} às {{ $data['pickup_time'] ?? 'N/A' }}</p>
                                                        <p><strong>Pagamento:</strong> {{ $data['payment_method'] === 'cash' ? 'Dinheiro' : 'PIX' }}</p>
                                                    </div>
                                                    
                                                    <div>
                                                        @if(isset($data['products']) && is_array($data['products']))
                                                            <p><strong>Produtos:</strong></p>
                                                            <ul class="mt-1 space-y-1">
                                                                @foreach($data['products'] as $product)
                                                                    <li class="text-xs">
                                                                        • {{ $product['name'] ?? 'N/A' }}: {{ $product['quantity'] ?? 0 }} {{ $product['unit'] ?? '' }}
                                                                        - R$ {{ number_format($product['subtotal'] ?? 0, 2, ',', '.') }}
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                        
                                                        @if(isset($data['total_value']))
                                                            <p class="mt-2 font-semibold text-green-600">
                                                                <strong>Total: R$ {{ number_format($data['total_value'], 2, ',', '.') }}</strong>
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                @if(isset($data['pickup_notes']) && $data['pickup_notes'])
                                                    <div class="mt-3 p-3 bg-yellow-50 rounded-lg">
                                                        <p class="text-sm">
                                                            <i class="fas fa-sticky-note text-yellow-600 mr-2"></i>
                                                            <strong>Observações:</strong> {{ $data['pickup_notes'] }}
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    @endif
                                    
                                    <!-- Timestamp -->
                                    <div class="flex items-center justify-between mt-4">
                                        <p class="text-sm text-gray-500">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                        
                                        @if(!$notification->read_at)
                                            <button onclick="markAsRead({{ $notification->id }})" 
                                                    class="text-sm text-green-600 hover:text-green-700 font-medium">
                                                <i class="fas fa-check mr-1"></i>
                                                Marcar como lida
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bell-slash text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma notificação</h3>
                <p class="text-gray-500 mb-6">Você não possui notificações no momento.</p>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar ao Dashboard
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/mark-as-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Atualizar visualmente a notificação
            const notificationElement = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationElement) {
                notificationElement.classList.remove('bg-blue-50');
                notificationElement.classList.add('opacity-75');
                
                // Remover o badge "Nova"
                const badge = notificationElement.querySelector('.bg-red-100');
                if (badge) {
                    badge.remove();
                }
                
                // Remover o botão "Marcar como lida"
                const button = notificationElement.querySelector('button[onclick*="markAsRead"]');
                if (button) {
                    button.remove();
                }
            }
            
            // Atualizar contador
            updateNotificationCount();
        }
    })
    .catch(error => {
        console.error('Erro ao marcar notificação como lida:', error);
    });
}

function markAllAsRead() {
    fetch('/notifications/mark-all-as-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Recarregar a página para atualizar todas as notificações
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Erro ao marcar todas as notificações como lidas:', error);
    });
}

function updateNotificationCount() {
    fetch('/notifications/unread-count')
        .then(response => response.json())
        .then(data => {
            // Atualizar contador no header se existir
            const countElement = document.querySelector('#notification-count');
            if (countElement) {
                countElement.textContent = data.count || 0;
            }
        })
        .catch(error => {
            console.error('Erro ao atualizar contador de notificações:', error);
        });
}
</script>
@endpush
@endsection
