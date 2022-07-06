<?php

namespace Database\Seeders;

use App\Models\SubAgent;
use Illuminate\Database\Seeder;

class SubAgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        !SubAgent::all()->count() && SubAgent::factory()
            ->count(200)
            ->create();
    }
}
