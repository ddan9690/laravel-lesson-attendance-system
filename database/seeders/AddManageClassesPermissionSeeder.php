<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddManageClassesPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create the permission if it doesn't exist
        $permission = Permission::firstOrCreate(['name' => 'manage_classes']);

        // 2. Assign it to relevant roles
        $roles = Role::whereIn('name', [
            'super_admin', 
            'principal', 
            'deputy', 
            'dos', 
            'senior_teacher', 
            'committee_member',
            'class_supervisor'
        ])->get();

        foreach ($roles as $role) {
            $role->givePermissionTo($permission);
        }

        $this->command->info('manage_classes permission added and assigned!');
    }
}
