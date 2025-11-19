<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\PickupSchedule;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Criar notificação no sistema
     */
    public function createNotification($userId, $title, $message, $type = 'info', $data = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'data' => $data ? json_encode($data) : null,
            'read_at' => null,
        ]);
    }

    /**
     * Notificar novo pedido
     */
    public function notifyNewOrder(Order $order)
    {
        // Notificar produtores via sistema
        $itemsByProducer = $order->items->groupBy('product.user_id');
        
        foreach ($itemsByProducer as $producerId => $items) {
            $producer = User::find($producerId);
            if (!$producer) continue;

            $productNames = $items->pluck('product.name')->join(', ');
            $totalValue = $items->sum(function($item) {
                return $item->quantity * $item->price;
            });

            // Notificação no sistema
            $this->createNotification(
                $producer->id,
                'Novo Pedido Recebido',
                "Você recebeu um novo pedido de {$order->user->name} no valor de R$ " . number_format($totalValue, 2, ',', '.'),
                'order',
                ['order_id' => $order->id, 'products' => $productNames]
            );

            // Enviar email se configurado
            $this->sendEmailNotification($producer, 'Novo Pedido', [
                'order' => $order,
                'items' => $items,
                'total_value' => $totalValue
            ]);
        }

        // Notificar via WhatsApp
        $this->whatsappService->notifyNewOrder($order);

        // Notificar cliente sobre confirmação
        $this->createNotification(
            $order->user_id,
            'Pedido Realizado',
            "Seu pedido #{$order->id} foi realizado com sucesso! Aguarde o contato do produtor.",
            'success',
            ['order_id' => $order->id]
        );

        $this->whatsappService->notifyOrderConfirmation($order);
    }

    /**
     * Notificar agendamento de retirada
     */
    public function notifyPickupScheduled(PickupSchedule $schedule)
    {
        $order = $schedule->order;

        // Notificar cliente
        $this->createNotification(
            $order->user_id,
            'Retirada Agendada',
            "Sua retirada foi agendada para {$schedule->pickup_date->format('d/m/Y')} às {$schedule->pickup_time->format('H:i')}",
            'info',
            ['pickup_schedule_id' => $schedule->id, 'order_id' => $order->id]
        );

        // Notificar produtores
        $itemsByProducer = $order->items->groupBy('product.user_id');
        foreach ($itemsByProducer as $producerId => $items) {
            $producer = User::find($producerId);
            if (!$producer) continue;

            $this->createNotification(
                $producer->id,
                'Retirada Agendada',
                "Retirada agendada para o pedido #{$order->id} em {$schedule->pickup_date->format('d/m/Y')} às {$schedule->pickup_time->format('H:i')}",
                'info',
                ['pickup_schedule_id' => $schedule->id, 'order_id' => $order->id]
            );
        }

        // Notificar via WhatsApp
        $this->whatsappService->notifyPickupScheduled($schedule);
    }

    /**
     * Notificar mudança de status
     */
    public function notifyStatusChange(Order $order, $oldStatus, $newStatus)
    {
        $statusMessages = [
            'pending' => 'Pendente',
            'confirmed' => 'Confirmado',
            'ready_for_pickup' => 'Pronto para Retirada',
            'delivered' => 'Entregue',
            'cancelled' => 'Cancelado',
        ];

        $message = "Status do pedido #{$order->id} alterado de {$statusMessages[$oldStatus]} para {$statusMessages[$newStatus]}";

        // Notificar cliente
        $this->createNotification(
            $order->user_id,
            'Status do Pedido Atualizado',
            $message,
            $newStatus === 'cancelled' ? 'warning' : 'info',
            ['order_id' => $order->id, 'old_status' => $oldStatus, 'new_status' => $newStatus]
        );

        // Se foi entregue, solicitar avaliação após 1 dia
        if ($newStatus === 'delivered') {
            $this->scheduleReviewRequest($order);
        }
    }

    /**
     * Notificar mudança de status da retirada
     */
    public function notifyPickupStatusChange(PickupSchedule $schedule)
    {
        $order = $schedule->order;
        $statusMessages = [
            'scheduled' => 'Agendada',
            'confirmed' => 'Confirmada',
            'completed' => 'Concluída',
            'cancelled' => 'Cancelada',
        ];

        $message = "Status da retirada do pedido #{$order->id} alterado para {$statusMessages[$schedule->status]}";

        // Notificar cliente
        $this->createNotification(
            $order->user_id,
            'Status da Retirada Atualizado',
            $message,
            $schedule->status === 'cancelled' ? 'warning' : 'info',
            ['pickup_schedule_id' => $schedule->id, 'order_id' => $order->id]
        );

        // Notificar produtores
        $itemsByProducer = $order->items->groupBy('product.user_id');
        foreach ($itemsByProducer as $producerId => $items) {
            $producer = User::find($producerId);
            if (!$producer) continue;

            $this->createNotification(
                $producer->id,
                'Status da Retirada Atualizado',
                $message,
                $schedule->status === 'cancelled' ? 'warning' : 'info',
                ['pickup_schedule_id' => $schedule->id, 'order_id' => $order->id]
            );
        }

        // Notificar via WhatsApp
        $this->whatsappService->notifyPickupStatusChange($schedule);
    }

    /**
     * Enviar lembrete de retirada
     */
    public function sendPickupReminder(PickupSchedule $schedule)
    {
        $order = $schedule->order;

        // Notificar cliente
        $this->createNotification(
            $order->user_id,
            'Lembrete de Retirada',
            "Lembrete: Sua retirada está agendada para hoje às {$schedule->pickup_time->format('H:i')} em {$schedule->pickup_location}",
            'reminder',
            ['pickup_schedule_id' => $schedule->id, 'order_id' => $order->id]
        );

        // Notificar via WhatsApp
        $this->whatsappService->notifyPickupReminder($schedule);
    }

    /**
     * Solicitar avaliação
     */
    public function requestReview(Order $order)
    {
        $this->createNotification(
            $order->user_id,
            'Avalie sua Compra',
            "Como foi sua experiência com o pedido #{$order->id}? Sua avaliação é muito importante!",
            'review',
            ['order_id' => $order->id]
        );

        // Notificar via WhatsApp
        $this->whatsappService->notifyPendingReview($order);
    }

    /**
     * Notificar produto com estoque baixo
     */
    public function notifyLowStock($product)
    {
        $this->createNotification(
            $product->user_id,
            'Estoque Baixo',
            "O produto '{$product->name}' está com estoque baixo ({$product->stock_quantity} {$product->unit})",
            'warning',
            ['product_id' => $product->id]
        );
    }

    /**
     * Notificar nova avaliação recebida
     */
    public function notifyNewReview($review)
    {
        $order = $review->order;
        $itemsByProducer = $order->items->groupBy('product.user_id');

        foreach ($itemsByProducer as $producerId => $items) {
            $producer = User::find($producerId);
            if (!$producer) continue;

            $this->createNotification(
                $producer->id,
                'Nova Avaliação Recebida',
                "Você recebeu uma nova avaliação de {$review->user->name} ({$review->rating} estrelas)",
                'review',
                ['review_id' => $review->id, 'order_id' => $order->id]
            );
        }
    }

    /**
     * Enviar notificação por email
     */
    private function sendEmailNotification($user, $subject, $data)
    {
        try {
            // Implementar envio de email se necessário
            // Mail::to($user->email)->send(new NotificationMail($subject, $data));
            Log::info('Email notification would be sent', [
                'user' => $user->email,
                'subject' => $subject
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send email notification', [
                'user' => $user->email,
                'subject' => $subject,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Agendar solicitação de avaliação
     */
    private function scheduleReviewRequest(Order $order)
    {
        // Implementar agendamento de tarefa para solicitar avaliação após 24h
        // Por enquanto, vamos simular enviando imediatamente
        Log::info('Review request scheduled for order', ['order_id' => $order->id]);
        
        // Em um sistema real, isso seria feito com jobs/queues
        // dispatch(new RequestReviewJob($order))->delay(now()->addDay());
    }

    /**
     * Marcar notificação como lida
     */
    public function markAsRead($notificationId, $userId)
    {
        return Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    /**
     * Marcar todas as notificações como lidas
     */
    public function markAllAsRead($userId)
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    /**
     * Obter notificações não lidas
     */
    public function getUnreadNotifications($userId, $limit = 10)
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Contar notificações não lidas
     */
    public function getUnreadCount($userId)
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->count();
    }
}
