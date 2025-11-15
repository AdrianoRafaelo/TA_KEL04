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
            ['username' => 'if420086'],
            [
                'nama' => 'Theresya Gurning',
                'nim' => '11420086',
                'role' => 'mahasiswa',
                'prodi' => 'DIV Teknologi Rekayasa Perangkat Lunak',
                'fakultas' => 'Vokasi',
                'status' => 'Aktif',
                'user_id' => 5082,
                'role_id' => 3, // Assuming mahasiswa role_id
            ]
        );
    }
}
