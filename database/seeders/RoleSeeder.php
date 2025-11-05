<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'Admin', 'created_by' => 'sistem', 'active' => 1],
            ['name' => 'Dosen', 'created_by' => 'sistem', 'active' => 1],
            ['name' => 'Mahasiswa', 'created_by' => 'sistem', 'active' => 1],
            ['name' => 'Koordinator', 'created_by' => 'sistem', 'active' => 1],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
