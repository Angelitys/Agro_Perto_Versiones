<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicReview extends Model
{
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
        'is_public',
        'reviewed_at'
    ];

    protected $casts = [
        'photos' => 'array',
        'is_verified' => 'boolean',
        'is_public' => 'boolean',
        'reviewed_at' => 'datetime',
        'product_rating' => 'integer',
        'producer_rating' => 'integer'
    ];

    /**
     * Relacionamento com o usuário que fez a avaliação
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com o produto avaliado
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relacionamento com o produtor avaliado
     */
    public function producer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'producer_id');
    }

    /**
     * Relacionamento com o pedido
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
     * Scope para avaliações de um produto específico
     */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope para avaliações de um produtor específico
     */
    public function scopeForProducer($query, $producerId)
    {
        return $query->where('producer_id', $producerId);
    }

    /**
     * Obter média de avaliações do produto
     */
    public static function getProductAverageRating($productId)
    {
        return static::forProduct($productId)
            ->public()
            ->verified()
            ->avg('product_rating') ?? 0;
    }

    /**
     * Obter média de avaliações do produtor
     */
    public static function getProducerAverageRating($producerId)
    {
        return static::forProducer($producerId)
            ->public()
            ->verified()
            ->avg('producer_rating') ?? 0;
    }

    /**
     * Obter contagem de avaliações do produto
     */
    public static function getProductReviewCount($productId)
    {
        return static::forProduct($productId)
            ->public()
            ->verified()
            ->count();
    }

    /**
     * Obter contagem de avaliações do produtor
     */
    public static function getProducerReviewCount($producerId)
    {
        return static::forProducer($producerId)
            ->public()
            ->verified()
            ->count();
    }

    /**
     * Formatar nota como estrelas
     */
    public function getStarsAttribute()
    {
        return [
            'product' => $this->formatStars($this->product_rating),
            'producer' => $this->formatStars($this->producer_rating)
        ];
    }

    /**
     * Formatar estrelas
     */
    private function formatStars($rating)
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $stars .= '★';
            } else {
                $stars .= '☆';
            }
        }
        return $stars;
    }

    /**
     * Verificar se o usuário pode avaliar este produto/pedido
     */
    public static function canUserReview($userId, $productId, $orderId)
    {
        // Verificar se já existe uma avaliação
        $existingReview = static::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('order_id', $orderId)
            ->first();

        if ($existingReview) {
            return false;
        }

        // Verificar se o pedido foi entregue/retirado
        $order = Order::where('id', $orderId)
            ->where('user_id', $userId)
            ->where('can_review', true)
            ->first();

        return $order !== null;
    }
}
