<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ProducerNotificationService
{
    /**
     * Notificar produtores sobre novo pedido
     */
    public function notifyProducersAboutNewOrder(Order $order)
    {
        try {
            // Obter todos os produtores que têm produtos no pedido
            $producerIds = $order->orderItems()
                ->with('product.user')
                ->get()
                ->pluck('product.user.id')
                ->unique();

            foreach ($producerIds as $producerId) {
                $producer = User::find($producerId);
                
                if ($producer && $producer->type === 'producer') {
                    // Criar notificação no sistema
                    $this->createSystemNotification($producer, $order);
                    
                    // Enviar notificação WhatsApp (se configurado)
                    $this->sendWhatsAppNotification($producer, $order);
                    
                    Log::info("Notificação enviada para produtor", [
                        'producer_id' => $producer->id,
                        'producer_name' => $producer->name,
                        'order_id' => $order->id
                    ]);
                }
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error("Erro ao enviar notificações para produtores", [
                'error' => $e->getMessage(),
                'order_id' => $order->id
            ]);
            
            return false;
        }
    }

    /**
     * Criar notificação no sistema
     */
    private function createSystemNotification(User $producer, Order $order)
    {
        // Obter produtos do produtor no pedido
        $producerProducts = $order->orderItems()
            ->whereHas('product', function($query) use ($producer) {
                $query->where('user_id', $producer->id);
            })
            ->with('product')
            ->get();

        $productNames = $producerProducts->pluck('product.name')->join(', ');
        $totalValue = $producerProducts->sum('subtotal');

        Notification::create([
            'user_id' => $producer->id,
            'type' => 'new_order',
            'title' => 'Novo Pedido Recebido! 🎉',
            'message' => "Você recebeu um novo pedido de {$order->user->name}. Produtos: {$productNames}. Total: R$ " . number_format($totalValue, 2, ',', '.'),
            'data' => json_encode([
                'order_id' => $order->id,
                'customer_name' => $order->user->name,
                'customer_phone' => $order->user->phone_number,
                'pickup_date' => $order->pickup_date,
                'pickup_time' => $order->pickup_time,
                'pickup_notes' => $order->pickup_notes,
                'payment_method' => $order->payment_method,
                'products' => $producerProducts->map(function($item) {
                    return [
                        'name' => $item->product->name,
                        'quantity' => $item->quantity,
                        'unit' => $item->product->unit,
                        'price' => $item->price,
                        'subtotal' => $item->subtotal
                    ];
                })->toArray(),
                'total_value' => $totalValue
            ]),
            'read_at' => null
        ]);
    }

    /**
     * Enviar notificação WhatsApp
     */
    private function sendWhatsAppNotification(User $producer, Order $order)
    {
        if (!$producer->phone_number) {
            Log::warning("Produtor sem telefone cadastrado", [
                'producer_id' => $producer->id
            ]);
            return;
        }

        // Obter produtos do produtor no pedido
        $producerProducts = $order->orderItems()
            ->whereHas('product', function($query) use ($producer) {
                $query->where('user_id', $producer->id);
            })
            ->with('product')
            ->get();

        $totalValue = $producerProducts->sum('subtotal');
        
        // Montar lista de produtos
        $productList = $producerProducts->map(function($item) {
            return "• {$item->product->name}: {$item->quantity} {$item->product->unit} - R$ " . number_format($item->subtotal, 2, ',', '.');
        })->join("\n");

        // Montar mensagem WhatsApp
        $message = "🎉 *NOVO PEDIDO RECEBIDO!*\n\n";
        $message .= "👤 *Cliente:* {$order->user->name}\n";
        $message .= "📱 *Telefone:* {$order->user->phone_number}\n\n";
        $message .= "🛒 *Produtos:*\n{$productList}\n\n";
        $message .= "💰 *Total:* R$ " . number_format($totalValue, 2, ',', '.') . "\n\n";
        $message .= "📅 *Retirada:* " . date('d/m/Y', strtotime($order->pickup_date)) . " às {$order->pickup_time}\n";
        
        if ($order->pickup_notes) {
            $message .= "📝 *Observações:* {$order->pickup_notes}\n";
        }
        
        $message .= "💳 *Pagamento:* " . ($order->payment_method === 'cash' ? 'Dinheiro na retirada' : 'PIX') . "\n\n";
        $message .= "Acesse o sistema para mais detalhes: " . url('/dashboard');

        // Aqui você integraria com a API do WhatsApp
        // Por enquanto, vamos apenas logar a mensagem
        Log::info("Mensagem WhatsApp para produtor", [
            'producer_phone' => $producer->phone_number,
            'message' => $message
        ]);

        // Exemplo de integração com WhatsApp Business API:
        /*
        $whatsappService = new WhatsAppService();
        $whatsappService->sendMessage($producer->phone_number, $message);
        */
    }

    /**
     * Marcar notificação como lida
     */
    public function markAsRead($notificationId, $userId)
    {
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->first();

        if ($notification) {
            $notification->update(['read_at' => now()]);
            return true;
        }

        return false;
    }

    /**
     * Obter notificações não lidas do produtor
     */
    public function getUnreadNotifications($userId)
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Obter todas as notificações do produtor
     */
    public function getAllNotifications($userId, $limit = 20)
    {
        return Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
