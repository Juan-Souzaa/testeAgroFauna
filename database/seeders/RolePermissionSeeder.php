<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'books.view',
            'books.create',
            'books.update',
            'books.delete',
            'users.manage',
            'logs.view',
        ];

        foreach ($permissions as $name) {
            Permission::findOrCreate($name);
        }

        $admin = Role::findOrCreate('admin');
        $editor = Role::findOrCreate('editor');

        $admin->syncPermissions(Permission::all());

        $editor->syncPermissions([
            'books.view',
            'books.create',
            'books.update',
        ]);
    }
}
