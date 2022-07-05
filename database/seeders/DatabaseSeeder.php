<?php

namespace Database\Seeders;

use App;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // always seed currencies, units and countries
        $this->call(CurrencySeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(CountrySeeder::class);

        if (App::environment() !== 'production') {
            $this->call(UserSeeder::class);
            $this->call(CompanySeeder::class);
            $this->call(OrderSeeder::class);
            $this->call(OrderItemSeeder::class);
            $this->call(SubAgentSeeder::class);
            $this->call(ShipmentSeeder::class);
            $this->call(ShipmentItemSeeder::class);
        }
    }
}
