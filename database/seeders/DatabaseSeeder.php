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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'nim' => '123456789',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'created_by' => 'system',
            'updated_by' => 'system',
            'active' => '1',
        ]);

        $this->call([
            RoleSeeder::class,
            AdminUserSeeder::class,
            RefStatusTaSeeder::class,
            UpdateTaPendaftaranStatusSeeder::class,
            KurikulumSeeder::class,
        ]);
    }
}
