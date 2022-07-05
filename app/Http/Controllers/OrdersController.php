<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrdersController extends Controller
{
    /**
     * The model for this resource
     *
     * @var string
     */
    public string $model = Order::class;

    /**
     * allowed sort parameters
     *
     * @var array
     */
    protected array $allowed_sorts = ['date'];

    /**
     * allowed fields
     *
     * @var array
     */
    protected array $allowed_fields = [
    ];

    /**
     * allowed includes of relationships
     *
     * @var array|string[]
     */
    protected array $allowed_includes = [
        'currency',
        'agent',
        'seller',
        'purchaser',
        'order_items',
        'order_items.unit',
        'shipments',
        'shipment_items',
        'sub_agents',
        'sub_agents.user'
    ];

    /**
     * allowed filters
     *
     * @var array
     */
    protected array $allowed_filters = [
        'agent_id',
        'seller_id',
        'purchaser_id'
    ];
}
