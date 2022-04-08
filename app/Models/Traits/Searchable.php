<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Searchable
{
    use \Laravel\Scout\Searchable;

    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public static function scopeSearch(Builder $query, $value): Builder
    {
        $ids = self::search($value)->get()->pluck('id');

        return $query->whereIn('id', $ids);
    }

    /**
     * @return array
     */
    abstract public function toSearchableArray(): array;
}
