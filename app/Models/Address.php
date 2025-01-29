<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    /** @use HasFactory<\Database\Factories\AddressFactory> */
    use HasFactory;

    protected $fillable = [
        'order_id', 'type', 'companyname', 'name', 'street', 'housenumber',
        'address_line_2', 'zipcode', 'city', 'country', 'email', 'phone'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
