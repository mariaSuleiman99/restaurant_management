<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\Table;
use App\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        Restaurant::factory(10)->create(); //should be removed when uploaded to PROD
        Table::factory(10)->create(); //should be removed when uploaded to PROD
        $this->call(RoleSeeder::class);
        $user = User::factory()->create([
            'name' => 'System Admin',
            'email' => 'systemAdmin@mail.com',
            'force_update' => false,
            'status' => 'Active',
        ]);
        // Assign the System Admin role
        $role = Role::where('name', 'System_Admin')->first();
        if ($role) {
            $user->assignRole($role);
        } else {
            throw new \Exception("Role 'System Admin' not found.");
        }
    }
}
