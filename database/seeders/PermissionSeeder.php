<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Define all permissions
        $permissions = [
            // Lesson Attendance
            'lesson_capture',
            'lesson_view',
            'lesson_edit',
            'lesson_delete',

            // Remedial Fee Payments
            'payment_capture',
            'payment_view',
            'payment_edit',
            'payment_delete',

            // Academic Structures
            'manage_curricula',
            'manage_forms',
            'manage_grades',
            'manage_streams',
            'manage_classes',
            'promote_students',
            'import_students', 

            // Users & Roles
            'manage_users',
            'manage_roles',

            // Reports
            'view_reports',
        ];

        // 2. Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 3. Assign permissions to roles
        $roles = Role::all();

        foreach ($roles as $role) {
            switch ($role->name) {
                case 'super_admin':
                    $role->syncPermissions([
                        'manage_users',
                        'manage_roles',
                        'lesson_capture',
                        'lesson_view',
                        'lesson_edit',
                        'lesson_delete',
                        'payment_capture',
                        'payment_view',
                        'payment_edit',
                        'payment_delete',
                        'manage_curricula',
                        'manage_classes',
                        'import_students', 
                        'manage_forms',
                        'manage_grades',
                        'manage_streams',
                        'promote_students',
                        'view_reports',
                    ]);
                    break;

                case 'principal':
                case 'deputy':
                case 'dos':
                case 'senior_teacher':
                case 'committee_member':
                    $role->syncPermissions([
                        'lesson_capture',
                        'lesson_view',
                        'lesson_edit',
                        'lesson_delete',
                        'payment_capture',
                        'payment_view',
                        'payment_edit',
                        'payment_delete',
                        'manage_users',
                        'manage_curricula',
                        'manage_classes',
                        'manage_forms',
                        'manage_grades',
                        'manage_streams',
                        'view_reports',
                    ]);
                    break;

                case 'class_supervisor':
                    $role->syncPermissions([
                        'lesson_view',
                        'payment_view',
                        'view_reports',
                        'manage_forms',
                        'manage_grades',
                        'manage_streams',
                    ]);
                    break;

                case 'class_teacher':
                    $role->syncPermissions([
                        'lesson_view',
                        'payment_view',
                        'view_reports',
                        'manage_streams',
                    ]);
                    break;

                case 'teacher':
                    $role->syncPermissions([
                        'lesson_view',
                        'view_reports',
                    ]);
                    break;
            }
        }

        $this->command->info('Permissions assigned to roles successfully!');
    }
}
