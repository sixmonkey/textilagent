<?php

namespace Database\Seeders;

use App\Models\ShipmentItem;
use Illuminate\Database\Seeder;

class ShipmentItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        !ShipmentItem::all()->count() && ShipmentItem::factory()
            ->count(4400)
            ->create();
    }
}
