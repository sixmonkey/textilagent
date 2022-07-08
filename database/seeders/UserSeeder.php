<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        !User::all()->count() &&
        User::factory()
            ->create(['admin' => true, 'email' => 'admin@textilexchange.de', 'name' => 'John Doe']) &&
        User::factory()
            ->count(49)
            ->create();
    }
}
