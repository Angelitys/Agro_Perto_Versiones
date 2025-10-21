<?php

namespace App\Http\Controllers;

use App\Services\ProducerNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(ProducerNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Exibir todas as notificações do usuário
     */
    public function index()
    {
        $notifications = $this->notificationService->getAllNotifications(Auth::id());
        $unreadCount = $this->notificationService->getUnreadNotifications(Auth::id())->count();

        return view('notifications.index-simple', compact('notifications', 'unreadCount'));
    }

    /**
     * Marcar notificação como lida
     */
    public function markAsRead($id)
    {
        $success = $this->notificationService->markAsRead($id, Auth::id());

        if ($success) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    /**
     * Marcar todas as notificações como lidas
     */
    public function markAllAsRead()
    {
        $notifications = $this->notificationService->getUnreadNotifications(Auth::id());
        
        foreach ($notifications as $notification) {
            $this->notificationService->markAsRead($notification->id, Auth::id());
        }

        return response()->json(['success' => true]);
    }

    /**
     * Obter contagem de notificações não lidas (para AJAX)
     */
    public function getUnreadCount()
    {
        $count = $this->notificationService->getUnreadNotifications(Auth::id())->count();
        
        return response()->json(['count' => $count]);
    }
}
