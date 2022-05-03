<?php

namespace App\Http\Controllers;

use App\Models\Company;

class CompaniesController extends Controller
{
    /**
     * The model for this resource
     *
     * @var string
     */
    public string $model = Company::class;

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
    protected array $allowed_filters = ['country_id'];

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
    protected array $allowed_includes = ['sales', 'purchases', 'country'];
}
