<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        Restaurant::factory(10)->create(); //should be removed when uploaded to PROD
        $this->call(RoleSeeder::class);
        User::factory()->create([
            'name' => 'System Admin',
            'email' => 'systemAdmin@mail.com',
            'force_update' => false,
            'status' => 'Active',
            'role_id' => 1
        ]);
    }
}
