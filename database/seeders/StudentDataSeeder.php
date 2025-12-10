<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FtiData;

class StudentDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FtiData::updateOrCreate(
            ['username' => 'if420086'], // Use nim as username
            [
                'nama' => 'Theresya Gurning',
                'nim' => '11420086',
                'role' => 'student',
                'prodi' => 'DIV Teknologi Rekayasa Perangkat Lunak',
                'fakultas' => 'Vokasi',
                'status' => 'Aktif',
                'user_id' => 5082,
                'role_id' => 3, // Mahasiswa role_id (from database)
            ]
        );

        FtiData::updateOrCreate(
            ['username' => 'if321002'], // Use nim as username
            [
                'nama' => 'Samuel Sibuea',
                'nim' => '11321002',
                'role' => 'student',
                'prodi' => 'DIII Teknologi Informasi',
                'fakultas' => 'Vokasi',
                'status' => 'Aktif',
                'user_id' => 5118,
                'role_id' => 3, // Mahasiswa role_id (from database)
            ]
        );
    }
}
