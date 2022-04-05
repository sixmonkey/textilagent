<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $appends = [
        'total'
    ];

    /**
     * the related currency
     *
     * @return BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * the related agent
     *
     * @return BelongsTo
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * the related supplier
     *
     * @return BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'supplier_id');
    }

    /**
     * the related supplier
     *
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'customer_id');
    }

    /**
     * the related order items
     *
     * @return HasMany
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }


    /**
     * @return float
     */
    public function getTotalAttribute()
    {
        return (float)$this->orderItems()->selectRaw('sum(order_items.amount * order_items.price) as total_value')->first('total_value')->total_value;
    }
}
