<?php

namespace App\Models\Traits;

trait HasRelationships
{
    /**
     * get defined relationships
     *
     * @return array
     */
    public static function definedRelationships(): array
    {
        $reflector = new \ReflectionClass(get_called_class());

        return collect($reflector->getMethods())
            ->filter(
                fn ($method) => !empty($method->getReturnType()) &&
                    str_contains(
                        $method->getReturnType(),
                        'Illuminate\Database\Eloquent\Relations'
                    )
            )
            ->mapWithKeys(function ($relationship) {
                return [$relationship->name => class_basename($relationship->getReturnType()->getName())];
            })
            ->
            all();
    }
}
