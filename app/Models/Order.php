<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Order extends Model
{
    use HasFactory;
    use HasRelationships;

    /**
     * The accessors to append to the model's array form.
     *
     * @var string[]
     */
    protected $appends = [
        'total'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
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
     * the related shipment items
     *
     * @return HasManyThrough
     */
    public function shipmentItems(): HasManyThrough
    {
        return $this->hasManyThrough(ShipmentItem::class, OrderItem::class);
    }

    /**
     * the related shipments
     *
     * @return HasManyDeep
     */
    public function shipments(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations(
            $this->shipmentItems(),
            (new ShipmentItem())->shipment()
        )->groupBy('shipments.id');
    }

    /**
     * @return float
     */
    public function getTotalAttribute()
    {
        return (float)$this->orderItems()->selectRaw('sum(order_items.amount * order_items.price) as total_value')->first('total_value')->total_value;
    }
}
