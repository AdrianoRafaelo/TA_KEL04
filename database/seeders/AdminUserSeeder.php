<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Cari atau buat role Admin
        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin'],
            ['created_by' => 'sistem', 'active' => 1]
        );

        // Assign role ke user admin (username 'admin')
        UserRole::firstOrCreate(
            ['username' => 'admin'],
            [
                'role_id' => $adminRole->id,
                'created_by' => 'sistem',
                'active' => 'active',
            ]
        );
    }
}
