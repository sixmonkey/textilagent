<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Unit::updateOrCreate(['code' => 'kg']);
        Unit::updateOrCreate(['code' => 'mtr']);
        Unit::updateOrCreate(['code' => 'pc']);
    }
}
