<?php

namespace App\Http\Controllers;

use App\Models\Shipment;

class ShipmentsController extends Controller
{
    /**
     * The model for this resource
     *
     * @var string
     */
    public string $model = Shipment::class;

    /**
     * allowed sort parameters
     *
     * @var array
     */
    protected array $allowed_sorts = [
        'date'
    ];

    /**
     * allowed fields
     *
     * @var array
     */
    protected array $allowed_fields = [];

    /**
     * allowed includes of relationships
     *
     * @var array|string[]
     */
    protected array $allowed_includes = [
        'shipment_items'
    ];


    /**
     * allowed filters
     *
     * @var array|string[]
     */
    protected array $allowed_filters = [
        'order_id'
    ];
}
