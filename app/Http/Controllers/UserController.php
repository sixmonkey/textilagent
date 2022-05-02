<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    /**
     * The model for this resource
     *
     * @var string
     */
    public string $model = User::class;

    /**
     * allowed sort parameters
     *
     * @var array
     */
    protected array $allowed_sorts = ['name'];

    /**
     * allowed filters
     *
     * @var array
     */
    protected array $allowed_filters = ['admin', 'country_id'];

    /**
     * allowed filters
     *
     * @var array
     */
    protected array $allowed_fields = ['name', 'orders.contract'];

    /**
     * allowed includes
     *
     * @var array
     */
    protected array $allowed_includes = ['orders', 'country'];
}
