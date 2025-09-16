<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlaced extends Notification
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification\'s delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ["mail"];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Seu pedido #{$this->order->id} foi realizado com sucesso!")
            ->greeting("Olá, {$notifiable->name}!")
            ->line("Seu pedido #{$this->order->id} foi realizado com sucesso e está aguardando confirmação.")
            ->line("Detalhes do Pedido:")
            ->line("Total: R$ " . number_format($this->order->total, 2, \',\', \'.\'))
            ->action("Ver Pedido", url("/orders/{$this->order->id}"))
            ->line("Obrigado por comprar no AgroPerto!");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

