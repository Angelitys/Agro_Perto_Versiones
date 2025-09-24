<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock_quantity',
        'unit',
        'images',
        'category_id',
        'user_id',
        'active',
        'origin',
        'harvest_date',
        'availability_schedule',
        'available_from',
        'available_until',
        'max_daily_quantity',
        'pickup_locations',
        'pickup_instructions'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'active' => 'boolean',
        'images' => 'array',
        'harvest_date' => 'date',
        'availability_schedule' => 'array',
        'available_from' => 'date',
        'available_until' => 'date',
        'pickup_locations' => 'array'
    ];

    /**
     * Relacionamento com categoria
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relacionamento com usuário (produtor)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com itens do carrinho
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Relacionamento com itens do pedido
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope para produtos ativos
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope para produtos em estoque
     */
    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    /**
     * Accessor para primeira imagem
     */
    public function getFirstImageAttribute()
    {
        return $this->images ? $this->images[0] : null;
    }

    /**
     * Verificar se o produto está disponível em uma data específica
     */
    public function isAvailableOnDate($date)
    {
        $date = is_string($date) ? \Carbon\Carbon::parse($date) : $date;
        
        // Verificar se está dentro do período de disponibilidade
        if ($this->available_from && $date->lt($this->available_from)) {
            return false;
        }
        
        if ($this->available_until && $date->gt($this->available_until)) {
            return false;
        }
        
        // Verificar se o dia da semana está disponível
        if ($this->availability_schedule) {
            $dayOfWeek = strtolower($date->format('l')); // monday, tuesday, etc.
            return isset($this->availability_schedule[$dayOfWeek]) && $this->availability_schedule[$dayOfWeek]['available'];
        }
        
        return true;
    }

    /**
     * Obter horários disponíveis para uma data específica
     */
    public function getAvailableTimesForDate($date)
    {
        if (!$this->isAvailableOnDate($date)) {
            return [];
        }
        
        $dayOfWeek = strtolower(\Carbon\Carbon::parse($date)->format('l'));
        
        if ($this->availability_schedule && isset($this->availability_schedule[$dayOfWeek])) {
            return $this->availability_schedule[$dayOfWeek]['times'] ?? [];
        }
        
        return [];
    }

    /**
     * Relacionamento com avaliações
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Obter média de avaliações do produto
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->where('is_public', true)->avg('product_rating') ?? 0;
    }

    /**
     * Obter total de avaliações do produto
     */
    public function getTotalReviewsAttribute()
    {
        return $this->reviews()->where('is_public', true)->count();
    }
}
