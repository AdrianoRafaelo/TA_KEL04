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

        User::updateOrCreate(
            ['nim' => '123456789'],
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
                'created_by' => null,
                'updated_by' => null,
                'active' => '1',
            ]
        );

        $this->call([
            RoleSeeder::class,
            AdminUserSeeder::class,
            RefStatusTaSeeder::class,
            UpdateTaPendaftaranStatusSeeder::class,
            KurikulumSeeder::class,
            StudentDataSeeder::class,
        ]);
    }
}
