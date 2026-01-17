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
        // Always required
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
        ]);

     
        if (!app()->environment('production')) {
            $this->call([
                LessonSeeder::class,
            ]);
        }
    }
}
