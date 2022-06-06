<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class CurrencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    #[ArrayShape(['code' => "string"])] public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->currencyCode
        ];
    }
}
