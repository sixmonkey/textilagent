<?php

namespace App\Http\Controllers;

use App\Models\Currency;

class CurrenciesController extends Controller
{
    /**
     * The model for this resource
     *
     * @var string
     */
    public string $model = Currency::class;

    /**
     * allowed sort parameters
     *
     * @var array
     */
    protected array $allowed_sorts = ['code'];

    /**
     * allowed filters
     *
     * @var array
     */
    protected array $allowed_fields = ['code'];
}
