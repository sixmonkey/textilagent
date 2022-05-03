<?php

namespace App\Models\Traits;

use App\Models\Country;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasCountry
{
    /**
     * The country associated to this entity
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @param array $values
     * @return array
     */
    protected function getArrayableItems(array $values)
    {
        if (!in_array('country_id', $this->hidden)) {
            $this->hidden[] = 'country_id';
        }
        return parent::getArrayableItems($values);
    }
}
