<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->country,
            'official_name' => $this->faker->country,
            'abbreviation' => strtoupper($this->faker->randomLetter() . $this->faker->randomLetter()),
            'capital' => $this->faker->city,
            'iso_alpha_2' => $this->faker->countryCode,
            'iso_alpha_3' => $this->faker->countryISOAlpha3,
            'iso_numeric' => $this->faker->numberBetween(1, 99),
            'calling_code' => '+' . $this->faker->numberBetween(11, 333),
            'tld' => '.' . $this->faker->randomLetter() . $this->faker->randomLetter(),
            'emoji' => $this->faker->emoji,
        ];
    }
}
