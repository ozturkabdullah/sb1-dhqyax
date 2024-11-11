<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Roller
        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);

        // İzinler
        $permissions = [
            'view_dashboard',
            'manage_categories',
            'manage_rentals',
            'manage_users',
            'manage_posts',
            'manage_pages',
            'manage_tags',
            'manage_contacts',
            'view_reports'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Admin tüm izinlere sahip
        $admin->givePermissionTo(Permission::all());

        // Normal kullanıcı izinleri
        $user->givePermissionTo([
            'view_dashboard'
        ]);
    }
}