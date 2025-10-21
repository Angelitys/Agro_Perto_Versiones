<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'postal_code',
        'country',
        'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    /**
     * Relacionamento com usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para endereços padrão
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope para endereços por tipo
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Formatar endereço completo
     */
    public function getFullAddressAttribute()
    {
        $address = $this->street . ', ' . $this->number;
        
        if ($this->complement) {
            $address .= ', ' . $this->complement;
        }
        
        $address .= ', ' . $this->neighborhood . ', ' . $this->city . ' - ' . $this->state;
        $address .= ', CEP: ' . $this->postal_code;
        
        return $address;
    }
}
