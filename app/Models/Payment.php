<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'amount',
        'method',       // ex: mvola, orange_money, airtel_money
        'provider_ref', // référence du fournisseur (transaction ID)
        'status',       // ex: pending, success, failed
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
