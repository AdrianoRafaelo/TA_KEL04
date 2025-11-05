<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RefStatusTaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\RefStatusTa::insert([
            ['name' => 'menunggu'],
            ['name' => 'disetujui'],
            ['name' => 'ditolak'],
        ]);
    }
}
