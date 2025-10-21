<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Order;
use App\Models\PickupSchedule;

class WhatsAppService
{
    private $apiUrl;
    private $apiToken;
    private $phoneNumberId;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.api_url', 'https://graph.facebook.com/v18.0');
        $this->apiToken = config('services.whatsapp.api_token');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
    }

    /**
     * Enviar mensagem de texto
     */
    public function sendTextMessage($phoneNumber, $message)
    {
        if (!$this->isConfigured()) {
            Log::warning('WhatsApp service not configured. Message not sent.', [
                'phone' => $phoneNumber,
                'message' => $message
            ]);
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}/{$this->phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $this->formatPhoneNumber($phoneNumber),
                'type' => 'text',
                'text' => [
                    'body' => $message
                ]
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp message sent successfully', [
                    'phone' => $phoneNumber,
                    'response' => $response->json()
                ]);
                return true;
            } else {
                Log::error('Failed to send WhatsApp message', [
                    'phone' => $phoneNumber,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Exception sending WhatsApp message', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Enviar mensagem de template
     */
    public function sendTemplateMessage($phoneNumber, $templateName, $parameters = [])
    {
        if (!$this->isConfigured()) {
            Log::warning('WhatsApp service not configured. Template message not sent.', [
                'phone' => $phoneNumber,
                'template' => $templateName
            ]);
            return false;
        }

        try {
            $components = [];
            if (!empty($parameters)) {
                $components[] = [
                    'type' => 'body',
                    'parameters' => array_map(function($param) {
                        return ['type' => 'text', 'text' => $param];
                    }, $parameters)
                ];
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}/{$this->phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $this->formatPhoneNumber($phoneNumber),
                'type' => 'template',
                'template' => [
                    'name' => $templateName,
                    'language' => ['code' => 'pt_BR'],
                    'components' => $components
                ]
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp template message sent successfully', [
                    'phone' => $phoneNumber,
                    'template' => $templateName,
                    'response' => $response->json()
                ]);
                return true;
            } else {
                Log::error('Failed to send WhatsApp template message', [
                    'phone' => $phoneNumber,
                    'template' => $templateName,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Exception sending WhatsApp template message', [
                'phone' => $phoneNumber,
                'template' => $templateName,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Notificar novo pedido para o produtor
     */
    public function notifyNewOrder(Order $order)
    {
        // Agrupar itens por produtor
        $itemsByProducer = $order->items->groupBy('product.user_id');

        foreach ($itemsByProducer as $producerId => $items) {
            $producer = User::find($producerId);
            if (!$producer || !$producer->phone_number) {
                continue;
            }

            $productNames = $items->pluck('product.name')->join(', ');
            $totalValue = $items->sum(function($item) {
                return $item->quantity * $item->price;
            });

            $message = "🛒 *Novo Pedido Recebido!*\n\n";
            $message .= "Pedido: #{$order->id}\n";
            $message .= "Cliente: {$order->user->name}\n";
            $message .= "Produtos: {$productNames}\n";
            $message .= "Valor: R$ " . number_format($totalValue, 2, ',', '.') . "\n\n";
            $message .= "Acesse o sistema para mais detalhes e agendar a retirada.";

            $this->sendTextMessage($producer->phone_number, $message);
        }
    }

    /**
     * Notificar confirmação de pedido para o cliente
     */
    public function notifyOrderConfirmation(Order $order)
    {
        if (!$order->user->phone_number) {
            return;
        }

        $message = "✅ *Pedido Confirmado!*\n\n";
        $message .= "Pedido: #{$order->id}\n";
        $message .= "Status: Confirmado\n";
        $message .= "Total: R$ " . number_format($order->total_amount, 2, ',', '.') . "\n\n";
        $message .= "Aguarde o contato do produtor para agendar a retirada.";

        $this->sendTextMessage($order->user->phone_number, $message);
    }

    /**
     * Notificar agendamento de retirada
     */
    public function notifyPickupScheduled(PickupSchedule $schedule)
    {
        $order = $schedule->order;

        // Notificar cliente
        if ($order->user->phone_number) {
            $message = "📅 *Retirada Agendada!*\n\n";
            $message .= "Pedido: #{$order->id}\n";
            $message .= "Data: {$schedule->pickup_date->format('d/m/Y')}\n";
            $message .= "Horário: {$schedule->pickup_time->format('H:i')}\n";
            $message .= "Local: {$schedule->pickup_location}\n";
            $message .= "Contato: {$schedule->contact_phone}\n\n";
            if ($schedule->pickup_instructions) {
                $message .= "Instruções: {$schedule->pickup_instructions}\n\n";
            }
            $message .= "Não se esqueça de comparecer no horário marcado!";

            $this->sendTextMessage($order->user->phone_number, $message);
        }

        // Notificar produtores
        $itemsByProducer = $order->items->groupBy('product.user_id');
        foreach ($itemsByProducer as $producerId => $items) {
            $producer = User::find($producerId);
            if (!$producer || !$producer->phone_number) {
                continue;
            }

            $message = "📅 *Retirada Agendada!*\n\n";
            $message .= "Pedido: #{$order->id}\n";
            $message .= "Cliente: {$order->user->name}\n";
            $message .= "Data: {$schedule->pickup_date->format('d/m/Y')}\n";
            $message .= "Horário: {$schedule->pickup_time->format('H:i')}\n";
            $message .= "Local: {$schedule->pickup_location}\n\n";
            $message .= "Prepare os produtos para a retirada!";

            $this->sendTextMessage($producer->phone_number, $message);
        }
    }

    /**
     * Notificar mudança de status da retirada
     */
    public function notifyPickupStatusChange(PickupSchedule $schedule)
    {
        $order = $schedule->order;
        $statusMessages = [
            'confirmed' => '✅ Retirada confirmada',
            'completed' => '🎉 Retirada concluída',
            'cancelled' => '❌ Retirada cancelada',
        ];

        $statusMessage = $statusMessages[$schedule->status] ?? 'Status atualizado';

        if ($order->user->phone_number) {
            $message = "*{$statusMessage}*\n\n";
            $message .= "Pedido: #{$order->id}\n";
            $message .= "Data: {$schedule->pickup_date->format('d/m/Y')}\n";
            $message .= "Horário: {$schedule->pickup_time->format('H:i')}\n\n";

            if ($schedule->status === 'completed') {
                $message .= "Obrigado por comprar conosco! 🌱";
            } elseif ($schedule->status === 'cancelled') {
                $message .= "Entre em contato conosco se tiver dúvidas.";
            }

            $this->sendTextMessage($order->user->phone_number, $message);
        }
    }

    /**
     * Notificar lembrete de retirada (1 hora antes)
     */
    public function notifyPickupReminder(PickupSchedule $schedule)
    {
        $order = $schedule->order;

        if ($order->user->phone_number) {
            $message = "⏰ *Lembrete de Retirada*\n\n";
            $message .= "Seu pedido #{$order->id} está agendado para retirada em 1 hora!\n\n";
            $message .= "Horário: {$schedule->pickup_time->format('H:i')}\n";
            $message .= "Local: {$schedule->pickup_location}\n";
            $message .= "Contato: {$schedule->contact_phone}\n\n";
            $message .= "Não se esqueça! 😊";

            $this->sendTextMessage($order->user->phone_number, $message);
        }
    }

    /**
     * Notificar avaliação pendente
     */
    public function notifyPendingReview(Order $order)
    {
        if (!$order->user->phone_number) {
            return;
        }

        $message = "⭐ *Avalie sua compra!*\n\n";
        $message .= "Como foi sua experiência com o pedido #{$order->id}?\n\n";
        $message .= "Sua avaliação é muito importante para melhorarmos nossos serviços.\n\n";
        $message .= "Acesse o sistema para avaliar: " . route('orders.show', $order);

        $this->sendTextMessage($order->user->phone_number, $message);
    }

    /**
     * Verificar se o serviço está configurado
     */
    private function isConfigured()
    {
        return !empty($this->apiToken) && !empty($this->phoneNumberId);
    }

    /**
     * Formatar número de telefone para WhatsApp
     */
    private function formatPhoneNumber($phoneNumber)
    {
        // Remove todos os caracteres não numéricos
        $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Se começar com 0, remove
        if (substr($cleaned, 0, 1) === '0') {
            $cleaned = substr($cleaned, 1);
        }
        
        // Se não começar com 55 (código do Brasil), adiciona
        if (!str_starts_with($cleaned, '55')) {
            $cleaned = '55' . $cleaned;
        }
        
        return $cleaned;
    }

    /**
     * Validar número de telefone
     */
    public function isValidPhoneNumber($phoneNumber)
    {
        $formatted = $this->formatPhoneNumber($phoneNumber);
        
        // Número brasileiro deve ter 13 dígitos (55 + DDD + número)
        return strlen($formatted) >= 12 && strlen($formatted) <= 13;
    }
}
