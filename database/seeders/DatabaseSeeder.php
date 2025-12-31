<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run your custom seeders only
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            LessonSeeder::class,
        ]);
    }
}
