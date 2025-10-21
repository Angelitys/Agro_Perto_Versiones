<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PickupSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'pickup_date',
        'pickup_time',
        'pickup_location',
        'pickup_instructions',
        'contact_phone',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'pickup_date' => 'date',
        'pickup_time' => 'datetime:H:i',
    ];

    /**
     * Relacionamento com pedido
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relacionamento com usuário que criou
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relacionamento com usuário que atualizou
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope para agendas de hoje
     */
    public function scopeToday($query)
    {
        return $query->whereDate('pickup_date', today());
    }

    /**
     * Scope para agendas desta semana
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('pickup_date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Scope para agendas pendentes
     */
    public function scopePending($query)
    {
        return $query->whereIn('status', ['scheduled', 'confirmed']);
    }

    /**
     * Scope para agendas por status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Accessor para data e hora formatadas
     */
    public function getFormattedDateTimeAttribute()
    {
        return $this->pickup_date->format('d/m/Y') . ' às ' . $this->pickup_time->format('H:i');
    }

    /**
     * Accessor para status formatado
     */
    public function getFormattedStatusAttribute()
    {
        $statuses = [
            'scheduled' => 'Agendado',
            'confirmed' => 'Confirmado',
            'completed' => 'Concluído',
            'cancelled' => 'Cancelado',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Accessor para cor do status
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'scheduled' => 'yellow',
            'confirmed' => 'green',
            'completed' => 'gray',
            'cancelled' => 'red',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    /**
     * Verificar se a agenda está atrasada
     */
    public function isOverdue()
    {
        if (in_array($this->status, ['completed', 'cancelled'])) {
            return false;
        }

        $pickupDateTime = Carbon::parse($this->pickup_date->format('Y-m-d') . ' ' . $this->pickup_time->format('H:i'));
        return $pickupDateTime->isPast();
    }

    /**
     * Verificar se pode ser editada
     */
    public function canBeEdited()
    {
        return in_array($this->status, ['scheduled', 'confirmed']) && !$this->isOverdue();
    }

    /**
     * Verificar se pode ser cancelada
     */
    public function canBeCancelled()
    {
        return in_array($this->status, ['scheduled', 'confirmed']);
    }

    /**
     * Obter próximas agendas para um produtor
     */
    public static function getUpcomingForProducer($producerId, $limit = 5)
    {
        return static::whereHas('order.items.product', function($query) use ($producerId) {
            $query->where('user_id', $producerId);
        })
        ->where('pickup_date', '>=', today())
        ->whereIn('status', ['scheduled', 'confirmed'])
        ->orderBy('pickup_date', 'asc')
        ->orderBy('pickup_time', 'asc')
        ->limit($limit)
        ->with(['order.user'])
        ->get();
    }

    /**
     * Obter estatísticas de agendas para um produtor
     */
    public static function getStatsForProducer($producerId)
    {
        $baseQuery = static::whereHas('order.items.product', function($query) use ($producerId) {
            $query->where('user_id', $producerId);
        });

        return [
            'today' => (clone $baseQuery)->today()->pending()->count(),
            'this_week' => (clone $baseQuery)->thisWeek()->pending()->count(),
            'scheduled' => (clone $baseQuery)->byStatus('scheduled')->count(),
            'confirmed' => (clone $baseQuery)->byStatus('confirmed')->count(),
            'completed' => (clone $baseQuery)->byStatus('completed')->count(),
            'overdue' => (clone $baseQuery)->pending()->get()->filter->isOverdue()->count(),
        ];
    }
}
