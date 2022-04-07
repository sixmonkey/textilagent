<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * The accessors to append to the model's array form.
     *
     * @var string[]
     */
    protected $appends = [
        'amount_left',
        'amount_left_fixed'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'etd' => 'date',
    ];

    /**
     * the related order
     *
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * the related unit
     *
     * @return BelongsTo
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * related shipment items
     *
     * @return HasMany
     */
    public function shipmentItems(): HasMany
    {
        return $this->hasMany(ShipmentItem::class);
    }

    /**
     * the amount still to deliver
     *
     * @return integer
     */
    public function getAmountLeftAttribute(): int
    {
        return $this->amount - $this->shipmentItems()->sum('amount');
    }

    /**
     * the amount still to deliver
     *
     * @return integer
     */
    public function getAmountLeftFixedAttribute(): int
    {
        return max(0, $this->getAmountLeftAttribute());
    }
}
