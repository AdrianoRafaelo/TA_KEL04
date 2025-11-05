<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateTaPendaftaranStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil id status 'menunggu' dari tabel ref_status_ta
        $statusMenunggu = DB::table('ref_status_ta')->where('name', 'menunggu')->first();

        if ($statusMenunggu) {
            // Update semua baris di ta_pendaftaran yang belum memiliki status_id
            DB::table('ta_pendaftaran')
                ->whereNull('status_id')
                ->update(['status_id' => $statusMenunggu->id]);
        }
    }
}
