<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderLine extends Model
{
    /** @use HasFactory<\Database\Factories\OrderLineFactory> */
    use HasFactory;

    protected $fillable = ['order_id', 'amount_ordered', 'name', 'sku', 'ean'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
