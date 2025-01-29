<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = ['number', 'shipment_id'];

    public function billingAddress(): HasOne
    {
        return $this->hasOne(Address::class)->where('type', 'billing');
    }

    public function deliveryAddress(): HasOne
    {
        return $this->hasOne(Address::class)->where('type', 'delivery');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function orderLines(): HasMany
    {
        return $this->hasMany(OrderLine::class);
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }
}
