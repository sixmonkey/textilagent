<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Stephenjude\DefaultModelSorting\Traits\DefaultOrderBy;
use Znck\Eloquent\Traits\BelongsToThrough;

class Shipment extends Model
{
    use HasFactory;
    use HasRelationships;
    use Traits\HasRelationships;
    use BelongsToThrough;
    use DefaultOrderBy;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'invoice',
        'date'
    ];

    /**
     * the default sort order column
     *
     * @var string
     */
    protected static string $orderByColumn = 'date';


    /**
     * the default sort order direction
     *
     * @var string
     */
    protected static string $orderByColumnDirection = 'desc';

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
     * the related shipment items
     *
     * @return BelongsToMany
     */
    public function uniqueOrderItems(): BelongsToMany
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
        // TODO: orders are listed twice
        return $this
            ->hasManyDeepFromRelations(
                $this->uniqueOrderItems(),
                (new OrderItem())->order()
            );
    }

    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public function scopeOrderId(Builder $query, $value): Builder
    {
        return $query->whereHas('orders', function (Builder $query) use ($value) {
            $query->where('orders.id', '1');
        });
    }

    /**
     * @param Builder $query
     * @param $start
     * @param $end
     * @return Builder
     */
    public function scopeDateBetween(Builder $query, $start, $end = null): Builder
    {
        if ($end === null) {
            return $query->where('date', Carbon::parse($start));
        }
        $range = [Carbon::parse($start), Carbon::parse($end)];
        sort($range);
        return $query->whereBetween('date', $range);
    }

    /**
     * the related seller
     *
     * @return BelongsTo
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'seller_id');
    }

    /**
     * the related purchaser
     *
     * @return BelongsTo
     */
    public function purchaser(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'purchaser_id');
    }

}
