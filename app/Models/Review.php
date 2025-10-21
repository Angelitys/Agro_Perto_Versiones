<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'producer_id',
        'order_id',
        'product_rating',
        'producer_rating',
        'product_comment',
        'producer_comment',
        'photos',
        'is_verified',
        'is_public'
    ];

    protected $casts = [
        'photos' => 'array',
        'is_verified' => 'boolean',
        'is_public' => 'boolean',
        'product_rating' => 'integer',
        'producer_rating' => 'integer'
    ];

    /**
     * Relacionamento com usuário (cliente que avalia)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com produto
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relacionamento com produtor
     */
    public function producer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'producer_id');
    }

    /**
     * Relacionamento com pedido
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope para avaliações públicas
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope para avaliações verificadas
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Accessor para obter a avaliação geral (média entre produto e produtor)
     */
    public function getOverallRatingAttribute()
    {
        $ratings = array_filter([$this->product_rating, $this->producer_rating]);
        return count($ratings) > 0 ? round(array_sum($ratings) / count($ratings), 1) : 0;
    }
}

