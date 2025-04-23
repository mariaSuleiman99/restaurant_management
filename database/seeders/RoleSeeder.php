<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'id' =>1,
            'name' => 'System_Admin',
            'guard_name' => 'api',
        ]);
        Role::create([
            'id' =>2,
            'name' => 'Restaurant_Admin',
            'guard_name' => 'api',
        ]);
        Role::create([
            'id' =>3,
            'name' => 'User',
            'guard_name' => 'api',
        ]);
    }
}
