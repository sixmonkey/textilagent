<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Lwwcas\LaravelCountries\Database\Seeders\LcDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LcDatabaseSeeder::class);
    }
}
