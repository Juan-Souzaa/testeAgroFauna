<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        User::factory()->admin()->create()->assignRole('admin');
        User::factory()->editor()->create()->assignRole('editor');
    }
}
