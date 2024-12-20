<?php

namespace Database\Seeders;

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
        $this->call([
            DepartmentSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            ManufacturerCategorySeeder::class,
            ManufacturerBlacklistSeeder::class,
            CountrySeeder::class,
            ZoneSeeder::class,
            ProductClassSeeder::class,
            ManufacturerSeeder::class,
        ]);
    }
}
