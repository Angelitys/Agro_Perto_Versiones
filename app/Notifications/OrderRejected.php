<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderRejected extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('⚠️ Pedido Não Confirmado - AgroPerto')
                    ->greeting('Olá!')
                    ->line('Infelizmente, seu pedido #' . $this->order->order_number . ' não pôde ser confirmado pelo produtor.')
                    ->line('**Motivo:**')
                    ->line($this->order->producer_rejection_reason)
                    ->line('**O que fazer agora?**')
                    ->line('• Você pode tentar fazer um novo pedido com outro horário')
                    ->line('• Ou entrar em contato diretamente com o produtor')
                    ->action('Ver Produtos', route('products.index'))
                    ->line('Pedimos desculpas pelo inconveniente.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'message' => 'Seu pedido não pôde ser confirmado.',
            'rejection_reason' => $this->order->producer_rejection_reason,
        ];
    }
}
