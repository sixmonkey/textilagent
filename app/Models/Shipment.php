<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Znck\Eloquent\Traits\BelongsToThrough;

class Shipment extends Model
{
    use HasFactory;
    use HasRelationships;
    use BelongsToThrough;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
    ];

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
     * the related shipment items
     *
     * @return BelongsToMany
     */
    public function orderItems(): BelongsToMany
    {
        return $this->belongsToMany(OrderItem::class, 'shipment_items');
    }

    /**
     * the related orders
     *
     * @return HasManyDeep
     */
    public function orders(): HasManyDeep
    {
        return $this
            ->hasManyDeepFromRelations(
                $this->orderItems(),
                (new OrderItem())->order()
            )
            ->groupBy('orders.id');
    }
}
