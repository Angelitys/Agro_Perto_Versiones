<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    /**
     * Relacionamento com usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para notificações não lidas
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope para notificações lidas
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope para notificações por tipo
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope para notificações recentes
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Verificar se a notificação foi lida
     */
    public function isRead()
    {
        return !is_null($this->read_at);
    }

    /**
     * Marcar como lida
     */
    public function markAsRead()
    {
        if (!$this->isRead()) {
            $this->update(['read_at' => now()]);
        }
        return $this;
    }

    /**
     * Marcar como não lida
     */
    public function markAsUnread()
    {
        if ($this->isRead()) {
            $this->update(['read_at' => null]);
        }
        return $this;
    }

    /**
     * Accessor para ícone baseado no tipo
     */
    public function getIconAttribute()
    {
        $icons = [
            'info' => 'information-circle',
            'success' => 'check-circle',
            'warning' => 'exclamation-triangle',
            'error' => 'x-circle',
            'order' => 'shopping-cart',
            'review' => 'star',
            'reminder' => 'clock',
        ];

        return $icons[$this->type] ?? 'bell';
    }

    /**
     * Accessor para cor baseada no tipo
     */
    public function getColorAttribute()
    {
        $colors = [
            'info' => 'blue',
            'success' => 'green',
            'warning' => 'yellow',
            'error' => 'red',
            'order' => 'purple',
            'review' => 'yellow',
            'reminder' => 'orange',
        ];

        return $colors[$this->type] ?? 'gray';
    }

    /**
     * Accessor para tempo relativo
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Accessor para URL baseada nos dados
     */
    public function getUrlAttribute()
    {
        if (!$this->data) {
            return null;
        }

        // Determinar URL baseada no tipo e dados
        if (isset($this->data['order_id'])) {
            return route('orders.show', $this->data['order_id']);
        }

        if (isset($this->data['pickup_schedule_id'])) {
            return route('pickup-schedule.show', $this->data['pickup_schedule_id']);
        }

        if (isset($this->data['product_id'])) {
            return route('products.show', $this->data['product_id']);
        }

        if (isset($this->data['review_id'])) {
            return route('reviews.show', $this->data['review_id']);
        }

        return null;
    }

    /**
     * Obter notificações para um usuário
     */
    public static function getForUser($userId, $limit = 50)
    {
        return static::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obter estatísticas de notificações para um usuário
     */
    public static function getStatsForUser($userId)
    {
        $baseQuery = static::where('user_id', $userId);

        return [
            'total' => (clone $baseQuery)->count(),
            'unread' => (clone $baseQuery)->unread()->count(),
            'today' => (clone $baseQuery)->whereDate('created_at', today())->count(),
            'this_week' => (clone $baseQuery)->recent(7)->count(),
            'by_type' => (clone $baseQuery)->selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray(),
        ];
    }

    /**
     * Limpar notificações antigas
     */
    public static function cleanOldNotifications($days = 30)
    {
        return static::where('created_at', '<', now()->subDays($days))->delete();
    }

    /**
     * Criar notificação em lote
     */
    public static function createBulk($notifications)
    {
        $data = collect($notifications)->map(function($notification) {
            return array_merge($notification, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        })->toArray();

        return static::insert($data);
    }
}
